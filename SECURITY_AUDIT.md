# Security Audit — Combined Perception CRM

> **How to use this document**
> This is a living audit checklist for the Combined Perception CRM (`crm.combinedperception.ai`). Each domain can be audited independently. Run the commands from the repository root unless otherwise noted. Mark findings with `- [x]` when resolved. Re-run after every significant change to the auth, data, or deployment stack.
>
> **Threat model:** Multi-tenant SaaS. The highest-severity incident is a cross-team data leak — one tenant's CRM records exposed to another. Second is a full compromise of the MCP endpoint, which has write access to all CRM data.

---

## Severity Legend

| Level | Meaning |
|-------|---------|
| 🔴 **Critical** | Cross-tenant data exposure or full system compromise possible |
| 🟠 **High** | Auth bypass, secret exposure, or significant data loss risk |
| 🟡 **Medium** | Hardening gap; exploitable with additional conditions |
| 🟢 **Low** | Best-practice deviation; low exploit probability |
| ℹ️ **Info** | Monitoring / observability gap; no direct exploit path |

---

## Domain 1 — Authentication & Session Hardening

**Files to read:** `config/sanctum.php` · `config/session.php` · `config/fortify.php` · `app/Models/PersonalAccessToken.php`

### 1.1 — Token Expiration 🟠

**Current state:** `sanctum.expiration = null` — API tokens never expire.

**Risk:** A leaked token grants permanent access until manually revoked. In practice, tokens left in developer machines, CI environments, or browser storage are never revoked.

**Audit command:**
```bash
# How many tokens are older than 90 days?
php artisan tinker --execute '
echo \App\Models\PersonalAccessToken::where("created_at", "<", now()->subDays(90))->count() . " tokens older than 90 days\n";
'
```

**Pass criteria:** Either `expiration` is set (e.g., 43200 = 30 days), or a documented manual rotation process exists with evidence of execution.

**Remediation:**
```php
// config/sanctum.php
'expiration' => 43200,  // 30 days in minutes
```
Notify users 7 days before expiry. Implement token refresh flow in the Settings → API Tokens UI.

---

### 1.2 — Session Encryption 🟡

**Current state:** `SESSION_ENCRYPT` defaults to `false`.

**Risk:** Session cookie payload is base64-encoded but not encrypted. An attacker with access to the `sessions` database table (e.g., via a SQL injection or compromised backup) can decode session data without the APP_KEY.

**Audit command:**
```bash
# Check production .env (run on Hetzner host):
grep SESSION_ENCRYPT /opt/em-crm/.env
# Expected: SESSION_ENCRYPT=true
```

**Pass criteria:** `SESSION_ENCRYPT=true` in the production environment file.

**Remediation:** Add `SESSION_ENCRYPT=true` to the production `.env`. No code change required — Laravel handles this transparently.

---

### 1.3 — Secure Cookie Flag 🟠

**Current state:** `SESSION_SECURE_COOKIE` is not explicitly forced in compose.yml.

**Risk:** Without `Secure`, the session cookie can be transmitted over HTTP (e.g., during a misconfigured redirect), allowing cookie theft via a man-in-the-middle attack.

**Audit command:**
```bash
# Verify the Set-Cookie header from production:
curl -si https://crm.combinedperception.ai/login | grep -i "set-cookie"
# Expected: includes "Secure" and "HttpOnly" and "SameSite=Lax"
```

**Pass criteria:** `Secure; HttpOnly; SameSite=Lax` all present on the session cookie response header.

**Remediation:**
```bash
# In production .env:
SESSION_SECURE_COOKIE=true
```

---

### 1.4 — Two-Factor Authentication Enforcement 🟡

**Current state:** 2FA is available (Fortify feature enabled) but not enforced.

**Risk:** Admin accounts with access to all team data can be compromised via credential stuffing if 2FA is optional.

**Audit command:**
```bash
# Count users without 2FA enrolled:
php artisan tinker --execute '
$total = \App\Models\User::count();
$with2fa = \App\Models\User::whereNotNull("two_factor_secret")->count();
echo "Total users: $total\nWith 2FA: $with2fa\nWithout 2FA: " . ($total - $with2fa) . "\n";
'
```

**Pass criteria (minimum):** All users with `system_administrator = true` have 2FA enrolled.

**Remediation (recommended):** Require 2FA for team owners. Add to `AppPanelProvider`:
```php
->requiresTwoFactorAuthentication(isOptional: false)
```

---

### 1.5 — Brute Force Login Bypass 🟡

**Current state:** Fortify limits to 5 attempts/min per `email+IP`. Rate is applied to `POST /login`.

**Risk:** The API route (`/api/v1/user`) and Sanctum token exchange are separate rate-limited stacks — verify they cannot be abused to enumerate valid user emails.

**Audit command:**
```bash
# Verify the login throttle response:
for i in {1..6}; do
  curl -s -o /dev/null -w "%{http_code}\n" -X POST https://crm.combinedperception.ai/login \
    -d "email=test@example.com&password=wrong&_token=fake"
done
# Expected: first 5 return 422, 6th returns 429
```

**Pass criteria:** HTTP 429 returned on the 6th attempt.

---

## Domain 2 — Multi-Tenancy Isolation *(Critical)*

**Files to read:** `app/Http/Middleware/SetApiTeamContext.php` · `app/Models/Scopes/TeamScope.php` · `app/Http/Middleware/ApplyTenantScopes.php` · `app/Models/PersonalAccessToken.php`

### 2.1 — PHP-FPM Deployment Confirmation 🔴

**Current state:** `SetApiTeamContext` uses `Model::addGlobalScope()` (static state) in `handle()` and `Model::clearBootedModels()` in `terminate()`. This is **not safe under Octane/Swoole/RoadRunner** — if `terminate()` fails or is not called (e.g., process recycled mid-request), the previous tenant's scope persists into the next request.

**Risk:** Cross-tenant data leak. One team's records exposed to another team's API requests.

**Audit command:**
```bash
# Verify the runtime is FPM, not Octane:
docker compose exec app php -r "echo php_sapi_name() . PHP_EOL;"
# Expected: fpm-fcgi   (NOT cli-server, swoole, or roadrunner)

# Also verify no Octane package is installed:
composer show | grep -i octane
# Expected: no output
```

**Pass criteria:** Runtime is `fpm-fcgi`. Octane is not installed.

**If Octane is ever adopted:** Refactor `SetApiTeamContext` to store team context in request-scoped storage (e.g., `$request->attributes->set('team', $team)`) and apply scopes per-query rather than via static global scope registration.

---

### 2.2 — `withoutGlobalScopes()` Audit 🔴

**Current state:** Every call to `withoutGlobalScopes()` bypasses the `TeamScope` and could expose records from other teams.

**Audit command:**
```bash
grep -rn "withoutGlobalScopes" app/ packages/ --include="*.php"
```

**Pass criteria:** The only expected instance is in `CustomFieldValidationService` (intentional, uses explicit `where('tenant_id', ...)` instead). Any other call requires documented justification.

**For each unexpected result:**
- Confirm the query has an explicit `where('team_id', ...)` or `whereBelongsTo($team)` guard
- If not, add the team constraint or remove `withoutGlobalScopes()`

---

### 2.3 — Cross-Tenant Relationship Navigation 🔴

**Risk:** An Eloquent relationship on a model that loads related records without applying the global scope can leak data across teams. Example: `$company->notes` — if `Note` has `TeamScope` but the eager load runs in a context where the scope isn't applied.

**Audit commands:**
```bash
# Find all relationship definitions that load cross-model data:
grep -rn "hasMany\|hasOne\|belongsTo\|hasManyThrough\|morphMany" app/Models/ --include="*.php" | grep -v "//.*has"

# Test cross-tenant access in a test environment:
php artisan test --compact --filter="cross.team\|tenant.isolation\|team.scope"
```

**Pass criteria:** All listed tests pass. No relationship returns records from a different `team_id` than the authenticated user's current team.

---

### 2.4 — `X-Team-Id` Header Trust Surface 🟠

**Current state:** `SetApiTeamContext` accepts `X-Team-Id` from the request header to set the active team. It validates that the user belongs to that team — but the header is still a client-controlled value.

**Risk:** A user who belongs to Team A and Team B can freely switch team context mid-session via header manipulation. This is intended behavior but must be logged for anomaly detection.

**Audit command:**
```bash
# Verify team membership is checked before accepting the header:
grep -n "X-Team-Id\|belongsToTeam\|header" app/Http/Middleware/SetApiTeamContext.php
# Expected: team membership validation before setUser()

# Attempt to access another team's data by spoofing the header:
curl -H "Authorization: Bearer <valid-token-for-team-A>" \
     -H "X-Team-Id: <id-of-team-B>" \
     https://crm.combinedperception.ai/api/v1/companies
# Expected: 403 Forbidden (not a list of Team B's companies)
```

**Pass criteria:** HTTP 403 when `X-Team-Id` references a team the token's user does not belong to.

---

### 2.5 — Token Team Pinning Immutability 🟠

**Current state:** `PersonalAccessToken.team_id` is set at creation and validated immutable in the `updating` hook.

**Audit command:**
```bash
# Attempt to re-assign a token's team_id via tinker (should fail):
php artisan tinker --execute '
$token = \App\Models\PersonalAccessToken::first();
$originalTeam = $token->team_id;
$token->team_id = "00000000000000000000000000";
$token->save();
echo ($token->fresh()->team_id === $originalTeam) ? "PASS: team_id is immutable\n" : "FAIL: team_id was changed\n";
'
```

**Pass criteria:** Output is `PASS: team_id is immutable`.

---

## Domain 3 — API & MCP Authorization

**Files to read:** `routes/api.php` · `app/Http/Middleware/EnsureTokenHasAbility.php` · `app/Mcp/Servers/RelaticleServer.php` · `app/Mcp/Tools/Concerns/ChecksTokenAbility.php`

### 3.1 — Complete API Middleware Coverage 🔴

**Risk:** Any API route not behind the full middleware stack (`auth:sanctum` + `EnsureTokenHasAbility` + `SetApiTeamContext`) is unauthenticated or lacks tenant scoping.

**Audit command:**
```bash
php artisan route:list --path=api --except-vendor 2>&1 | grep -v "sanctum\|ForceJson"
# Review every row — all api/v1/* routes must show auth:sanctum in the middleware column
```

**Pass criteria:** No `/api/v1/` route is missing `auth:sanctum` and `SetApiTeamContext`.

---

### 3.2 — MCP Endpoint Authentication 🔴

**Audit commands:**
```bash
# Unauthenticated MCP request must be rejected:
curl -s -o /dev/null -w "%{http_code}" -X POST https://crm.combinedperception.ai/mcp \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc":"2.0","method":"tools/list","id":1}'
# Expected: 401

# Invalid token must be rejected:
curl -s -o /dev/null -w "%{http_code}" -X POST https://crm.combinedperception.ai/mcp \
  -H "Authorization: Bearer invalid-token-here" \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc":"2.0","method":"tools/list","id":1}'
# Expected: 401
```

**Pass criteria:** Both return HTTP 401.

---

### 3.3 — IDOR on API Endpoints 🔴

**Risk:** If model binding resolves a record by ID before the policy gate checks team ownership, an attacker can guess IDs of records in other teams.

**Audit command:**
```bash
# Create a test company in Team A. Then try to fetch it with a Team B token:
# 1. Get Team A company ID
# 2. Use Team B's API token in Authorization header
curl -H "Authorization: Bearer <team-b-token>" \
  https://crm.combinedperception.ai/api/v1/companies/<team-a-company-id>
# Expected: 403 or 404 (not 200 with Team A's data)
```

**Pass criteria:** HTTP 403 or 404. Not 200.

---

### 3.4 — Rate Limit Bucket Sharing 🟡

**Audit commands:**
```bash
# Make 61 write requests with two different tokens from the same team (30 + 31 each):
# The 61st total write should be rate-limited (shared team bucket of 60/min)
# This requires a test script — run in a staging environment

# Verify rate limit headers are returned:
curl -v -X POST https://crm.combinedperception.ai/api/v1/companies \
  -H "Authorization: Bearer <valid-write-token>" \
  -H "Content-Type: application/json" \
  -d '{"name":"Rate Limit Test"}' 2>&1 | grep -i "x-ratelimit"
# Expected: X-RateLimit-Limit, X-RateLimit-Remaining headers present
```

---

## Domain 4 — Input Validation & Injection

**Files to read:** `app/Http/Controllers/Api/V1/` · `app/Actions/` · `packages/ImportWizard/`

### 4.1 — Raw SQL Audit 🟠

**Audit command:**
```bash
grep -rn "whereRaw\|selectRaw\|DB::statement\|DB::select\|DB::insert\|DB::update\|DB::delete" \
  app/ packages/ --include="*.php" | grep -v "whereRaw('1 = 0')"
```

**Pass criteria:** Every result uses parameterized bindings (second array argument), never string concatenation of user input. Example of **safe** usage: `whereRaw('LOWER(name) = ?', [strtolower($input)])`. Example of **unsafe** usage: `whereRaw("name = '$input'")`.

---

### 4.2 — CSV Import Security 🟠

**Audit commands:**
```bash
# Find all file handling in ImportWizard:
grep -rn "storeAs\|putFile\|move\|originalExtension\|getClientOriginalName\|getMimeType" \
  packages/ImportWizard/ app/ --include="*.php"

# Verify MIME validation exists (not just extension check):
grep -rn "mimes\|mimetypes\|image/\|text/csv\|application/vnd" \
  packages/ImportWizard/ app/ --include="*.php"
```

**Pass criteria:**
- File upload validates MIME type using PHP's `finfo` or Laravel's `mimes` validation rule (not just the extension)
- Uploaded files are stored outside the `public/` disk by default
- Maximum file size is enforced (`max:` validation rule)

---

### 4.3 — Unescaped Blade Output (XSS) 🟠

**Audit command:**
```bash
grep -rn "{!!" resources/views --include="*.blade.php"
```

**For each result, classify:**
- ✅ **Safe**: `{!! $attributes->merge(...) !!}` (component attribute merging — Blade's own internals)
- ✅ **Safe**: `{!! $tab['icon'] !!}` (hardcoded SVG string from a PHP array in the same file, not user input)
- ❌ **Unsafe**: Any `{!! $user->name !!}`, `{!! $record->notes !!}`, `{!! request('query') !!}`, or similar where the value originates from user input or a database column

**Pass criteria:** Zero instances of user-controlled or database-sourced values rendered with `{!! !!}`.

---

### 4.4 — Mass Assignment Protection 🟠

**Audit command:**
```bash
# Check fillable arrays for security-sensitive fields:
grep -rn "fillable\|guarded" app/Models/ --include="*.php" -A 20 | \
  grep -E "team_id|user_id|is_admin|role|abilities|system_administrator"
```

**Pass criteria:** `team_id`, `user_id`, `is_admin`, `role`, `abilities`, and `system_administrator` are **not** in any model's `$fillable` array. These should only be set programmatically.

---

## Domain 5 — HTTP Security Headers & CORS

**Files to read:** `config/cors.php` · `bootstrap/app.php`

### 5.1 — Security Header Audit 🟠

**Audit command:**
```bash
curl -si https://crm.combinedperception.ai/ | grep -iE \
  "x-frame-options|x-content-type-options|strict-transport-security|content-security-policy|referrer-policy|permissions-policy"
```

**Expected headers (all must be present):**

| Header | Expected value |
|--------|---------------|
| `X-Frame-Options` | `DENY` or `SAMEORIGIN` |
| `X-Content-Type-Options` | `nosniff` |
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains` |
| `Referrer-Policy` | `strict-origin-when-cross-origin` |
| `Content-Security-Policy` | At minimum `default-src 'self'` with Google Fonts allowlisted |

**Remediation:** Add a security headers middleware. Laravel has no built-in CSP — use `spatie/laravel-csp` or add headers in the Nginx/Hetzner proxy config:
```nginx
add_header X-Frame-Options "DENY" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
```

---

### 5.2 — CORS Configuration 🟡

**Audit command:**
```bash
cat config/cors.php

# Test cross-origin preflight:
curl -si -X OPTIONS https://crm.combinedperception.ai/api/v1/companies \
  -H "Origin: https://evil.example.com" \
  -H "Access-Control-Request-Method: GET" | grep -i "access-control"
```

**Pass criteria:** `Access-Control-Allow-Origin` does NOT return `*` or `https://evil.example.com`. It should only return the allowed origins configured in `config/cors.php`.

---

## Domain 6 — Secrets & Environment

**Files to read:** `.env.example` · `compose.yml` · `.github/workflows/deploy.yml`

### 6.1 — Production Debug Mode 🔴

**Audit command (run on Hetzner):**
```bash
grep "APP_DEBUG" /opt/em-crm/.env
# Expected: APP_DEBUG=false

# Also verify via HTTP — a debug page leaks full stack traces + config values:
curl -s https://crm.combinedperception.ai/non-existent-route-12345 | grep -i "stack\|trace\|vendor"
# Expected: generic 404 page, no stack trace
```

**Pass criteria:** `APP_DEBUG=false`. Generic error pages returned.

---

### 6.2 — PostgreSQL TLS Enforcement 🟠

**Current state:** `sslmode='prefer'` allows unencrypted connections if TLS fails — it does not require encryption.

**Audit commands:**
```bash
# Check current setting:
grep -n "sslmode" config/database.php

# Test that an unencrypted connection is rejected (run from app container):
docker compose exec app php -r "
\$pdo = new PDO(
  'pgsql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE') . ';sslmode=disable',
  getenv('DB_USERNAME'), getenv('DB_PASSWORD')
);
echo 'Connected without TLS - FAIL' . PHP_EOL;
" 2>&1
# Expected: connection error (if sslmode=require is enforced on the server)
```

**Remediation:**
```php
// config/database.php — pgsql connection
'sslmode' => env('DB_SSLMODE', 'require'),
```

---

### 6.3 — Git History Secret Scan 🔴

**Audit command:**
```bash
# Scan full git history for potential secrets:
git log --all --oneline | wc -l  # total commits to scan

# Quick scan for common secret patterns in history:
git grep -i "password\s*=\s*['\"].\{8,\}" $(git rev-list --all) 2>/dev/null | head -20
git grep -i "APP_KEY=base64" $(git rev-list --all) 2>/dev/null | head -5
git grep -E "secret['\"]?\s*[:=]\s*['\"][A-Za-z0-9+/]{20,}" $(git rev-list --all) 2>/dev/null | head -10
```

**Pass criteria:** No secrets found in git history. If any are found: rotate them immediately, then consider using `git filter-repo` to remove from history and force-push.

---

### 6.4 — GitHub Actions Secret Hygiene 🟡

**Audit:**
```bash
# List all workflow files and check for echo/print of secrets:
grep -rn "echo.*SECRET\|print.*SECRET\|run.*echo.*PASS\|DEPLOY_SSH_KEY" \
  .github/workflows/ --include="*.yml"
# Expected: no output
```

**Pass criteria:** No workflow step prints or echoes a secret value. `DEPLOY_SSH_KEY` is an ed25519 key (not RSA 2048), scoped to a dedicated deploy user on Hetzner.

---

## Domain 7 — Encryption at Rest & Custom Fields

**Files to read:** `config/app.php` · `config/database.php` · `packages/` (custom-fields)

### 7.1 — Custom Field Plaintext Storage 🟠

**Risk:** Fields marked "encrypted" in the custom field configuration may store values in plaintext if the package-level encryption is misconfigured or missing.

**Audit command (run against the production database):**
```bash
# Step 1: Create a custom field with encryption enabled, save a value
# Step 2: Query the raw table:
docker compose exec postgres psql -U "$DB_USERNAME" -d "$DB_DATABASE" -c \
  "SELECT field_value FROM custom_field_values LIMIT 20;"
# Expected: encrypted fields show as: eyJpdiI6I... (base64 encrypted ciphertext)
# Fail: plaintext value like "secret-api-key" visible directly
```

**Pass criteria:** Encrypted field values are not human-readable in the database.

---

### 7.2 — Backup Encryption 🟠

**Audit:**
- Verify Hetzner volume snapshots or `pg_dump` backups are encrypted at rest
- If using Hetzner Volumes: confirm encryption is enabled on the volume (Hetzner encrypts volumes by default at the infrastructure level — verify this is the case for your setup)
- If using `pg_dump` to external storage: verify GPG or AES encryption is applied before upload

```bash
# Check if any backup scripts exist:
find /opt/em-crm -name "*backup*" -o -name "*dump*" 2>/dev/null
```

---

### 7.3 — APP_KEY Rotation Readiness 🟡

**Audit commands:**
```bash
# Verify APP_PREVIOUS_KEYS is configured (needed for zero-downtime key rotation):
grep "APP_PREVIOUS_KEYS" /opt/em-crm/.env
# If empty or missing, rotation will break existing sessions and encrypted data

# Verify cipher is AES-256-CBC:
php artisan tinker --execute 'echo config("app.cipher") . PHP_EOL;'
# Expected: AES-256-CBC
```

**Rotation procedure (document this):**
1. Add current `APP_KEY` to `APP_PREVIOUS_KEYS`
2. Generate new `APP_KEY` with `php artisan key:generate --show`
3. Set new key in `.env`
4. Restart containers — sessions re-encrypt transparently

---

## Domain 8 — Container & Infrastructure Security

**Files to read:** `Dockerfile` · `compose.yml` · `.github/workflows/deploy.yml`

### 8.1 — Container Runs as Non-Root 🟡

**Audit command:**
```bash
docker compose exec app whoami
# Expected: www-data (not root)

docker compose exec horizon whoami
# Expected: www-data
```

**Pass criteria:** Neither `app` nor `horizon` service runs as root.

---

### 8.2 — Production Image Tag Pinning 🟡

**Current state:** `compose.yml` uses `ghcr.io/combinedperception/relaticle:latest`.

**Risk:** `:latest` in production means the next `docker compose pull` picks up whatever was most recently pushed — including a broken or compromised build.

**Remediation:** Pin to a digest or a semver tag in production:
```yaml
# compose.yml
image: ghcr.io/combinedperception/relaticle@sha256:<digest>
# Or use a versioned tag:
image: ghcr.io/combinedperception/relaticle:v1.2.3
```

---

### 8.3 — Migration Race Condition 🟡

**Current state:** `AUTORUN_LARAVEL_MIGRATION=true` means migrations run on container startup. If two containers start simultaneously (e.g., rolling deploy), both run migrations concurrently.

**Audit:** Add `--isolated` flag to the migration command in the entrypoint to use an atomic database lock:

```bash
# Verify current migration command in the base image:
docker compose exec app cat /etc/s6-overlay/s6-rc.d/laravel-migrate/run 2>/dev/null || \
  docker compose exec app cat /start_container 2>/dev/null
```

**Remediation:** If the base image supports it, set `AUTORUN_LARAVEL_MIGRATION_ISOLATED=true`, or override the migration step in the deployment workflow to run with `php artisan migrate --isolated`.

---

### 8.4 — Hetzner Firewall Rules 🔴

**Audit (run from an external host, not the Hetzner server):**
```bash
# Database port must NOT be publicly accessible:
nc -zv crm.combinedperception.ai 5432
# Expected: Connection refused or timeout

# Redis port must NOT be publicly accessible:
nc -zv crm.combinedperception.ai 6379
# Expected: Connection refused or timeout

# Only 80 and 443 should be open:
nmap -p 80,443,5432,6379,8080 crm.combinedperception.ai
```

**Pass criteria:** Only ports 80 and 443 respond. All other ports return timeout or refused.

---

### 8.5 — SSH Deploy Key Restrictions 🟠

**Audit (on the Hetzner server):**
```bash
cat ~/.ssh/authorized_keys
# Expected: the deploy key line begins with:
# command="/path/to/restricted-command",no-agent-forwarding,no-port-forwarding,no-pty,no-user-rc,no-X11-forwarding ssh-ed25519 ...
```

**Pass criteria:** The deploy key is ed25519 (not RSA-2048), has a `command=` restriction limiting it to only the deploy script, and has all forwarding flags disabled.

---

## Domain 9 — Dependency & Supply Chain

### 9.1 — Vulnerability Scan 🟠

**Audit commands:**
```bash
# PHP dependencies:
composer audit
# Expected: "No security vulnerability advisories found."

# Node dependencies:
npm audit
# Expected: "found 0 vulnerabilities"

# Check for outdated packages with known issues:
composer outdated --direct 2>&1 | head -30
```

**Pass criteria:** Zero vulnerabilities in both outputs. Set up GitHub Dependabot to run these automatically:

```yaml
# .github/dependabot.yml
version: 2
updates:
  - package-ecosystem: "composer"
    directory: "/"
    schedule:
      interval: "weekly"
  - package-ecosystem: "npm"
    directory: "/"
    schedule:
      interval: "weekly"
  - package-ecosystem: "github-actions"
    directory: "/"
    schedule:
      interval: "weekly"
```

---

### 9.2 — GitHub Actions SHA Pinning 🟡

**Audit command:**
```bash
# Find unpinned third-party actions (using @v1, @v2, @v3, @v4 — not a SHA):
grep -rn "uses:.*@v[0-9]" .github/workflows/ --include="*.yml" | grep -v "actions/\|docker/"
```

**Risk:** An unpinned third-party action at a mutable tag (e.g., `@v1`) can be replaced by a malicious version at any time — this is a supply-chain attack vector.

**Remediation:** Pin each third-party action to a commit SHA:
```yaml
# Instead of:
uses: appleboy/ssh-action@v1

# Use:
uses: appleboy/ssh-action@v1.0.0  # or the full SHA
```

---

### 9.3 — Internal Package Wildcard Constraints 🟡

**Audit command:**
```bash
# Check for wildcard version constraints in internal packages:
find packages/ -name "composer.json" -exec grep -n "\"*\"\|\"^0\"\|\">= 0\"" {} \; 2>/dev/null
```

**Pass criteria:** No internal package references an external dependency with `*` as the version constraint.

---

## Domain 10 — Logging, Monitoring & Incident Response

**Files to read:** `config/logging.php` · `config/sentry.php` · `app/Providers/AppServiceProvider.php`

### 10.1 — Sentry Error Tracking Active 🟠

**Audit commands:**
```bash
# Verify DSN is set in production:
docker compose exec app php artisan tinker --execute '
echo config("sentry.dsn") ? "Sentry DSN: configured\n" : "Sentry DSN: MISSING\n";
'

# Trigger a test event:
php artisan sentry:test
# Expected: "Sending test event... done. Event ID: ..."
```

**Pass criteria:** Sentry DSN is configured and test event is received in the Sentry project.

---

### 10.2 — Failed Login Audit Logging ℹ️

**Current state:** No custom audit log for failed login attempts detected.

**Audit command:**
```bash
grep -rn "AuthenticationException\|failed.*login\|lockout\|TooManyAttempts" \
  app/ --include="*.php"
```

**Recommended implementation:** Add to `app/Providers/FortifyServiceProvider.php`:
```php
use Laravel\Fortify\Fortify;

Fortify::authenticateThrough(function () {
    return [
        // ... existing pipeline steps ...
        function ($request, $next) {
            \Log::channel('daily')->warning('Login attempt', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return $next($request);
        }
    ];
});
```

---

### 10.3 — CRM Audit Trail (Activity Log) ℹ️

**Current state:** No `spatie/laravel-activitylog` or equivalent detected. The CRM stores activity on a timeline but without structured write audit logging.

**Risk:** In the event of a compliance inquiry or data breach investigation, there is no authoritative log of who created, modified, or deleted each Company, Person, or Opportunity record.

**Recommended implementation:**
1. `composer require spatie/laravel-activitylog`
2. Add `use Spatie\Activitylog\Traits\LogsActivity;` to `Company`, `People`, `Opportunity`, `Note`, `Task` models
3. Configure `logFillable()` to log all `$fillable` attribute changes

---

### 10.4 — Anomaly Detection Runbook ℹ️

**Define alerting thresholds in Sentry or a separate monitoring tool:**

| Event | Threshold | Action |
|-------|-----------|--------|
| Login failures | > 20/min for same email | Lock account + alert |
| API 429 responses | > 50/min for same team | Investigate abuse |
| Bulk MCP delete | Any `bulk-delete` tool call | Require confirmation + log |
| Token from 2 IPs | Same token, different IPs within 5 min | Revoke token + alert |
| `withoutGlobalScopes` call | Any in production log | Critical alert — investigate immediately |

---

### 10.5 — Incident Runbook: Compromised Token 🟠

Document and test this procedure:

```bash
# 1. Revoke all tokens for a specific team immediately:
php artisan tinker --execute '
$teamId = "<ulid-of-compromised-team>";
$count = \App\Models\PersonalAccessToken::where("team_id", $teamId)->delete();
echo "Revoked $count tokens for team $teamId\n";
'

# 2. Force all sessions for that team's users to expire:
php artisan tinker --execute '
$userIds = \App\Models\User::whereHas("teams", fn($q) => $q->where("id", "<team-id>"))->pluck("id");
\Illuminate\Support\Facades\DB::table("sessions")
    ->whereIn("user_id", $userIds)
    ->delete();
echo "Cleared sessions for " . $userIds->count() . " users\n";
'

# 3. Rotate APP_KEY (see Domain 7.3 procedure)

# 4. Notify affected team's users of the incident
```

---

## Audit Summary Checklist

Copy this to your issue tracker and check off each item:

### Critical (resolve before next deployment)
- [ ] **2.1** Confirm PHP-FPM runtime (not Octane) — `SetApiTeamContext` is not Octane-safe
- [ ] **2.2** Audit all `withoutGlobalScopes()` calls — none should bypass tenant isolation
- [ ] **3.1** Verify every `/api/v1/` route is behind the full middleware stack
- [ ] **3.2** Confirm MCP endpoint rejects unauthenticated requests with HTTP 401
- [ ] **3.3** Test IDOR: Team B token cannot access Team A records via direct ID
- [ ] **6.1** Confirm `APP_DEBUG=false` in production
- [ ] **6.3** Run git history secret scan — rotate any found credentials immediately
- [ ] **8.4** Verify PostgreSQL (5432) and Redis (6379) are not publicly accessible

### High (resolve within 1 sprint)
- [ ] **1.2** Set `SESSION_ENCRYPT=true` in production
- [ ] **1.3** Set `SESSION_SECURE_COOKIE=true` in production
- [ ] **1.1** Set `sanctum.expiration` to 43200 (30 days)
- [ ] **4.1** Audit all raw SQL usages for unsanitized user input
- [ ] **4.3** Audit all `{!! !!}` Blade usages — no user-controlled data reaches unescaped output
- [ ] **4.4** Confirm `team_id`, `user_id`, and security fields are not in model `$fillable`
- [ ] **5.1** Add `X-Frame-Options`, `X-Content-Type-Options`, `HSTS`, `CSP` headers
- [ ] **6.2** Change PostgreSQL `sslmode` from `prefer` to `require`
- [ ] **8.5** Verify SSH deploy key is ed25519 with `command=` restriction
- [ ] **10.1** Confirm Sentry DSN is configured in production

### Medium (resolve within 1 month)
- [ ] **1.4** Require 2FA for all team owner accounts
- [ ] **2.3** Write cross-tenant relationship tests covering all `hasMany` relationships
- [ ] **3.4** Add `X-RateLimit-*` headers to API responses
- [ ] **4.2** Audit CSV import MIME validation and file size limits
- [ ] **7.1** Verify encrypted custom field values are ciphertext in the database
- [ ] **7.3** Document and test APP_KEY rotation procedure
- [ ] **8.2** Pin Docker image to a digest or semver tag in `compose.yml`
- [ ] **8.3** Add `--isolated` flag to migration command in deploy workflow
- [ ] **9.1** Add Dependabot config for Composer + npm + GitHub Actions
- [ ] **9.2** Pin third-party GitHub Actions to commit SHAs

### Low / Info (backlog)
- [ ] **1.5** Periodically test brute-force protection is active
- [ ] **2.4** Log `X-Team-Id` header switches for anomaly detection
- [ ] **6.4** Confirm no GitHub Actions workflow echoes secret values
- [ ] **7.2** Document backup encryption procedure
- [ ] **8.1** Confirm `app` and `horizon` containers run as `www-data`
- [ ] **9.3** Audit internal package dependency constraints
- [ ] **10.2** Add failed login attempt logging to Fortify pipeline
- [ ] **10.3** Add `spatie/laravel-activitylog` for CRM record audit trail
- [ ] **10.4** Define anomaly detection thresholds and alerting rules
- [ ] **10.5** Test and document incident response runbook for compromised tokens

---

*Last audited: — (fill in date)*
*Audited by: — (fill in name)*
*Next scheduled review: — (fill in date, recommend quarterly)*
