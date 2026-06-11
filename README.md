<div align="center">

<img src="https://img.shields.io/badge/CP-Combined%20Perception-6171F7?style=for-the-badge&labelColor=172343&color=6171F7" alt="Combined Perception"/>

# Combined Perception CRM

**AI-native relationship management for enterprise teams**

[![PHP 8.4](https://img.shields.io/badge/PHP-8.4-777bb4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Laravel 13](https://img.shields.io/badge/Laravel-13-ff2d20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament 5](https://img.shields.io/badge/Filament-5-f59e0b?style=flat-square)](https://filamentphp.com)
[![30 MCP Tools](https://img.shields.io/badge/MCP%20Tools-30-10BAE9?style=flat-square)](https://crm.combinedperception.ai/guide)
[![Dependency Audit](https://img.shields.io/badge/deps-audited-3fb950?style=flat-square&logo=dependabot&logoColor=white)](.github/workflows/security.yml)
[![Tests](https://img.shields.io/badge/Tests-1100%2B-28c840?style=flat-square)](https://github.com/combinedperception/relaticle/actions)
[![AGPL-3.0](https://img.shields.io/badge/License-AGPL--3.0-blue?style=flat-square)](LICENSE)

[**Live App**](https://crm.combinedperception.ai) · [**Platform Guide**](https://crm.combinedperception.ai/guide) · [**Issues**](https://github.com/combinedperception/relaticle/issues) · [**Upstream: Relaticle**](https://github.com/relaticle/relaticle)

</div>

---

## What Is This?

**Combined Perception CRM** is a production-deployed, AI-augmented CRM built for enterprise teams that operate alongside AI agents. It gives any MCP-compatible model (Claude, GPT, custom LLMs) full read/write access to your relationship data through 30 structured tools — while humans interact through a polished Filament-powered UI.

This repository is a fork of [Relaticle](https://github.com/relaticle/relaticle) — an excellent open-source CRM released under AGPL-3.0. We extend it with Combined Perception–specific capabilities, brand identity, and production deployment infrastructure. The original Relaticle documentation is preserved in [`README_RELATICLE.md`](README_RELATICLE.md).

The system runs at [`crm.combinedperception.ai`](https://crm.combinedperception.ai) as a private SaaS. This repository contains the full source.

---

## Platform Capabilities

### Relationship Intelligence

| Feature | Detail |
|---------|--------|
| **Companies** | Full profiles with industry, size, website, and arbitrary custom fields. Bulk import/export via CSV. |
| **People** | Contact records linked to companies. Roles, email, phone, relationship history. |
| **Activity Timeline** | Automatic log of every note, change, email, task, and AI interaction on every record. |
| **Notes** | Rich-text notes on any record. Mention colleagues, attach files, pin critical context. |
| **Custom Fields** | 22 field types: text, number, currency, date, select, multi-select, URL, email, phone, relationship, encrypted, conditionally visible — no migrations needed. |
| **5-Layer Authorization** | Sanctum → Team membership → Role → Policy → Record-level tenant scope. Nothing leaks between teams. |

### Sales Pipeline

| Feature | Detail |
|---------|--------|
| **Kanban Board** | Drag-and-drop opportunities across configurable stages. |
| **Opportunity Records** | Value, close date, linked company/person, stage, owner, and full activity log. |
| **Pipeline Stages** | Create, rename, reorder stages to match your process. |
| **Filtering & Sorting** | Filter by stage, owner, value range, close date, or any custom field. |
| **Task Management** | Assign tasks to teammates; they're notified instantly and tracked per opportunity. |
| **AI Summaries** | One-click AI narrative: status, risk level, next steps. |

### AI Agent Integration — 30 MCP Tools

The `/mcp` endpoint exposes the full CRM over the [Model Context Protocol](https://modelcontextprotocol.io). Any MCP-compatible client gets:

| Category | Count | What's covered |
|----------|-------|---------------|
| **Read** | 12 tools | `list-*`, `get-*`, `search-records`, `describe-schema`, activity log retrieval |
| **Write** | 13 tools | `create-*`, `update-record`, `delete-record`, `bulk-update`, task/note creation |
| **AI** | 5 tools | `get-ai-summary`, `assess-deal-risk`, `relationship-health`, narrative generation, batch summarise |

**Claude Desktop config:**
```json
{
  "mcpServers": {
    "combined-perception-crm": {
      "url": "https://crm.combinedperception.ai/mcp",
      "headers": {
        "Authorization": "Bearer <your-api-token>"
      }
    }
  }
}
```

Generate your API token under **Settings → API Tokens**. Tokens are team-scoped and revocable instantly.

### Team Collaboration

- **Multi-team workspaces** — each team at `/app/{slug}/`, data fully isolated
- **Role-based access** — member and owner roles within teams
- **Database notifications** — real-time alerts for mentions, assignments, and changes
- **Task assignment** — tasks appear in the assignee's queue with instant notification
- **Audit log** — every write: user, timestamp, changed fields — full compliance trail

---

## Architecture

```
┌──────────────────────────────────────────────────────────┐
│                  Your Team / AI Agent                     │
└──────────────┬───────────────────────────┬───────────────┘
               │  Browser / Filament UI    │  MCP Protocol
               ▼                           ▼
┌─────────────────────────┐   ┌────────────────────────────┐
│  Laravel + Filament 5   │   │  Laravel MCP Server        │
│  Livewire 4 · Alpine.js │   │  /mcp  ·  30 tools         │
│  Tailwind 4             │   │  Bearer token auth         │
└──────────┬──────────────┘   └──────────────┬─────────────┘
           │                                  │
           └─────────────────┬────────────────┘
                             ▼
┌──────────────────────────────────────────────────────────┐
│          PostgreSQL 17 — Multi-tenant data store          │
│   Actions layer · Custom Fields · Tenant-scoped queries   │
└──────────────────────────────────┬───────────────────────┘
                                   │
                                   ▼
                    ┌──────────────────────────┐
                    │  Redis + Laravel Horizon  │
                    │  Queues · Sessions        │
                    │  Cache                    │
                    └──────────────────────────┘
```

**Stack:** PHP 8.4 · Laravel 13 · Filament 5 · Livewire 4 · Alpine.js 3 · Tailwind 4 · PostgreSQL 17 · Redis · Laravel MCP · Laravel Horizon · Pest v4 · Docker · GitHub Actions

---

## Getting Started

### Prerequisites

- PHP 8.4+
- PostgreSQL 17+
- Node.js 20.19+ or 22.12+
- Redis
- [Laravel Herd](https://herd.laravel.com) (recommended for local dev) or any web server

### Local Development

```bash
# 1. Clone the repository
git clone https://github.com/combinedperception/relaticle.git
cd relaticle

# 2. Install PHP and Node dependencies
composer install
npm install

# 3. Configure environment
cp .env.example .env
php artisan key:generate
# Edit .env: DB_CONNECTION=pgsql, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 4. Run database migrations
php artisan migrate

# 5. (Optional) Seed demo data
php artisan db:seed

# 6. Build frontend assets
npm run build

# 7. Serve the application
# If using Laravel Herd, the site is auto-served at https://relaticle.test
# Otherwise:
php artisan serve
```

### Docker Compose (Production-like)

```bash
# Copy and configure environment
cp .env.example .env
# Required: APP_KEY (generate with `php artisan key:generate --show`)
# Required: DB_PASSWORD (choose a strong password)
# Required: APP_URL (e.g. https://crm.yourdomain.com)

# Start all services (app, postgres, redis, horizon)
docker compose up -d

# Run database migrations
docker compose exec app php artisan migrate

# Visit http://localhost:8080
```

The Docker image is published to `ghcr.io/combinedperception/relaticle:latest` on every push to `main`.

---

## Development Workflow

This codebase is production software with paying users. Every change goes through the same quality gates as CI. **Run these before every commit:**

```bash
# 1. Auto-fix code style
vendor/bin/pint --dirty --format agent

# 2. Check for recommended refactors (apply if suggested)
vendor/bin/rector --dry-run
# vendor/bin/rector   # to apply

# 3. Static analysis — zero new errors against baseline
vendor/bin/phpstan analyse

# 4. Type coverage — must stay at or above 99.9%
composer test:type-coverage

# 5. Full test suite
php artisan test --compact
# Or filtered: php artisan test --compact --filter=YourFeatureTest
```

### Architecture Constraints

**Actions layer — mandatory.** All write operations (create, update, delete) go through classes in `app/Actions/`. Never inline business logic in controllers, Livewire components, or MCP tools.

```
app/
├── Actions/          ← all write operations live here
├── Http/Controllers/ ← thin controllers, delegate to Actions
├── Livewire/         ← UI components, call Actions
├── Mcp/Tools/        ← MCP tools, call Actions
└── Filament/         ← admin UI resources, call Actions
```

**PostgreSQL exclusively.** No SQLite/MySQL compatibility layers or conditional SQL. The project uses PostgreSQL-specific features.

**Strict PHP.** Every file: `declare(strict_types=1)`. All method parameters and return types explicitly typed. Constructor property promotion used throughout. No empty constructors.

**Migrations — `up()` only.** No `down()` methods. Schema changes are forward-only in production.

**Custom fields.** Models with `UsesCustomFields` trait handle `custom_fields` automatically. Do not call `saveCustomFields()` manually — just pass `custom_fields` through `$data` to `create()`/`update()`.

### Testing Philosophy

Test through real entry points — not isolated unit tests for action classes:

```php
// Good: test via the HTTP or Filament entry point
it('creates a company via the Filament resource', function () {
    livewire(CreateCompany::class)
        ->fillForm(['name' => 'Acme Corp'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Company::where('name', 'Acme Corp')->exists())->toBeTrue();
});

// Avoid: testing the action class directly in isolation
it('creates a company', function () {
    $action = new CreateCompanyAction();
    // ... this creates maintenance burden without catching real bugs
});
```

Every new feature needs Pest tests covering:
- Happy path (successful operation)
- Validation failures
- Authorization failures (another team cannot access, unauthenticated request fails)

Use `mutates(ClassName::class)` to declare mutation test coverage. Tests run against a real PostgreSQL database — no mocks.

---

## CI/CD Pipeline

| Event | What runs |
|-------|-----------|
| **Pull request** | Lint (Pint) → Rector check → PHPStan → Type coverage → Pest tests (5 parallel shards) + Playwright browser tests |
| **Push to `main`** | Same test suite → SSH deploy to `crm.combinedperception.ai` → `docker compose pull && up -d` → `php artisan migrate` → cache clear |
| **Push to `main` or tag** | Docker image build → push `ghcr.io/combinedperception/relaticle:latest` (and semver tag) |

All CI jobs run on PHP 8.4 against PostgreSQL Alpine. The test suite is sharded across 5 workers to keep PR feedback under 5 minutes.

A separate daily workflow monitors Filament vendor views for upstream breaking changes and opens a GitHub Issue automatically if a hash drift is detected.

---

## Collaboration Guidelines

This project targets senior software engineers, especially those working at the intersection of AI, machine learning, and robotics — where reliability and correctness under concurrent, multi-tenant conditions are non-negotiable.

### Branch Strategy

```
main                  ← production; every merge triggers deploy
└── feat/your-feature ← feature branches
└── fix/bug-name      ← bug fix branches
└── chore/task-name   ← tooling / non-functional changes
```

- Branch off `main`, PR back to `main`
- No long-lived develop branch
- Prefer squash merges to keep history linear

### Pull Request Expectations

- **Tests are not optional.** Every PR must include Pest feature tests. No exceptions.
- **Describe the why.** The PR body should explain the motivation, not just what changed. The diff shows what — the body shows why.
- **Link the issue.** Every PR references a GitHub Issue.
- **CI must be green** before requesting a review.
- Use the [PR template](.github/PULL_REQUEST_TEMPLATE_EM_PORTFOLIO.md) as a starting point.

### Code Review Focus Areas

**Tenant isolation — highest severity.** A cross-team data leak is a critical incident. Every PR that touches Eloquent queries, model relationships, or middleware must demonstrate that global scopes remain applied and cannot be bypassed. Look for:
- `withoutGlobalScopes()` calls that skip tenant scope
- Raw `DB::` queries that bypass model-level scoping
- New routes or MCP tools that don't go through `SetApiTeamContext`

**Authorization — required on every action.** All new action classes or controller methods must call `$this->authorize()` and have a matching Policy. Filament resources that use native `CreateAction`/`EditAction` must use `->authorize()` on the resource class.

**No lazy shortcuts.** This is production software. No placeholder implementations, no `// TODO: add validation`, no uncaught edge cases. Write code you'd be comfortable presenting in a security audit.

### MCP Tool Development

Adding new AI agent capabilities:

1. Create the tool class in `app/Mcp/Tools/`
2. Register it in the MCP tool manifest
3. Ensure it appears in `describe-schema` output so agents can discover it
4. **Never bypass tenant context** — the tool must go through `SetApiTeamContext` middleware; never call `withoutGlobalScopes()` inside a tool
5. Write Pest tests for: successful operation, auth failure (no token), wrong-team access attempt

### Working with AI/ML Pipelines

If your change involves the MCP layer or AI-generated content:

- The MCP endpoint is `POST /mcp` (StreamableHTTP transport) with `Authorization: Bearer <token>`
- AI summaries go through the `get-ai-summary`, `assess-deal-risk`, and similar tools — they call an LLM under the hood
- Schema discovery (`describe-schema`) returns custom field definitions in the team's context; always test this with a team that has custom fields configured
- MCP tool names use `kebab-case`; parameter names use `snake_case`

---

## Reporting Bugs

1. **Reproduce it** — identify exact steps, browser/OS, any relevant custom field configuration
2. **Check existing issues** — search [open and closed issues](https://github.com/combinedperception/relaticle/issues?q=is:issue) before opening a new one
3. **Open a report** — use the [bug report template](https://github.com/combinedperception/relaticle/issues/new?template=bug_report.md): clear title, numbered steps, expected vs. actual behavior, relevant logs
4. **Label it** — add `bug` and `priority: high` if it blocks core workflows

**Security vulnerabilities** must not be reported via public GitHub Issues. Email the maintainers directly and allow 48 hours for acknowledgement before any public disclosure.

---

## Attribution & Licence

This project is a fork of **[Relaticle](https://github.com/relaticle/relaticle)** by the Relaticle contributors, released under the [GNU Affero General Public License v3.0 (AGPL-3.0)](LICENSE).

Modifications, extensions, and the Combined Perception brand are by **[Combined Perception](https://github.com/combinedperception)**.

The original Relaticle project documentation is preserved in [`README_RELATICLE.md`](README_RELATICLE.md).

Network use of this software (running it as a service) triggers the copyleft provisions of AGPL-3.0. If you deploy this software and make modifications, you must make those modifications available under the same licence.

---

<div align="center">
<sub>Built on the foundations of <a href="https://github.com/relaticle/relaticle">Relaticle</a> open-source (AGPL-3.0) · Extended by <a href="https://github.com/combinedperception">Combined Perception</a></sub>
</div>
