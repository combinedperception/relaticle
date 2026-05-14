<section class="py-20 md:py-28" style="background: var(--cp-navy);">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-14" id="res-header">
            <span class="inline-block text-xs font-bold uppercase tracking-widest mb-4 px-3 py-1 rounded-full"
                  style="background: rgba(16,186,233,0.1); color: #10BAE9; border: 1px solid rgba(16,186,233,0.2);">
                Developer Resources
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-[1.1]"
                style="font-family: 'Archivo Black', system-ui, sans-serif;">
                Everything you need to go deeper
            </h2>
            <p class="mt-4 text-base sm:text-lg max-w-lg mx-auto leading-relaxed"
               style="color: rgba(255,255,255,0.5);">
                Source code, licence, and the full tech stack — all open for inspection.
            </p>
        </div>

        {{-- Cards row --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-14" id="res-cards">

            {{-- GitHub card --}}
            <div class="res-card sm:col-span-2 lg:col-span-1 rounded-2xl p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
                 style="background: rgba(255,255,255,0.03); border: 1px solid rgba(97,113,247,0.35);">
                <div class="absolute inset-0 pointer-events-none opacity-10"
                     style="background: radial-gradient(ellipse at top left, #6171F7 0%, transparent 60%);"
                     aria-hidden="true"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                             style="background: rgba(97,113,247,0.15); border: 1px solid rgba(97,113,247,0.3);">
                            <svg class="w-5 h-5 fill-current text-white" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-widest mb-0.5" style="color: rgba(255,255,255,0.3);">Repository</div>
                            <div class="text-sm font-bold text-white" style="font-family: monospace;">combinedperception/relaticle</div>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed mb-5" style="color: rgba(255,255,255,0.45);">
                        Full Laravel source: controllers, Filament resources, MCP tools, tests (1,100+), CI/CD pipeline, and Docker deployment stack.
                    </p>
                    <div class="flex gap-3">
                        <a href="https://github.com/combinedperception/relaticle"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white transition-all duration-200 hover:opacity-90"
                           style="background: linear-gradient(135deg, #6171F7, #10BAE9);">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            View Repo
                        </a>
                        <a href="https://github.com/combinedperception/relaticle/issues"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-200"
                           style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: rgba(255,255,255,0.65);">
                            Issues
                        </a>
                    </div>
                </div>
            </div>

            {{-- Licence card --}}
            <div class="res-card rounded-2xl p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
                 style="background: rgba(255,255,255,0.03); border: 1px solid rgba(16,186,233,0.25);">
                <div class="absolute inset-0 pointer-events-none opacity-10"
                     style="background: radial-gradient(ellipse at top right, #10BAE9 0%, transparent 65%);"
                     aria-hidden="true"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                             style="background: rgba(16,186,233,0.12); border: 1px solid rgba(16,186,233,0.25);">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color: #10BAE9;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-widest mb-0.5" style="color: rgba(255,255,255,0.3);">Licence</div>
                            <div class="text-sm font-bold text-white">AGPL-3.0</div>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed mb-4" style="color: rgba(255,255,255,0.45);">
                        GNU Affero General Public License v3. Network use triggers the copyleft. Read the full licence before deploying.
                    </p>
                    <p class="text-[11px]" style="color: rgba(255,255,255,0.25);">
                        Built on the foundations of
                        <a href="https://github.com/relaticle/relaticle" target="_blank" rel="noopener"
                           class="underline underline-offset-2 hover:opacity-60 transition-opacity" style="color: rgba(255,255,255,0.4);">
                            Relaticle
                        </a>
                        open-source.
                    </p>
                </div>
            </div>

            {{-- Tech stack card --}}
            <div class="res-card rounded-2xl p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
                 style="background: rgba(255,255,255,0.03); border: 1px solid rgba(245,158,11,0.2);">
                <div class="absolute inset-0 pointer-events-none opacity-10"
                     style="background: radial-gradient(ellipse at bottom left, #f59e0b 0%, transparent 65%);"
                     aria-hidden="true"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                             style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2);">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color: #f59e0b;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold uppercase tracking-widest mb-0.5" style="color: rgba(255,255,255,0.3);">Tech Stack</div>
                            <div class="text-sm font-bold text-white">Core dependencies</div>
                        </div>
                    </div>
                    @php
                    $stack = [
                        ['PHP 8.4',       '#777bb4'],
                        ['Laravel 13',    '#ff2d20'],
                        ['Filament 5',    '#f59e0b'],
                        ['Livewire 4',    '#fb70a9'],
                        ['Alpine.js',     '#77c1d2'],
                        ['Tailwind 4',    '#38bdf8'],
                        ['PostgreSQL',    '#336791'],
                        ['Laravel MCP',   '#6171F7'],
                        ['Laravel Sanctum','#ef4444'],
                        ['Pest v4',       '#28c840'],
                    ];
                    @endphp
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($stack as $s)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                  style="background: {{ $s[1] }}18; color: {{ $s[1] }}; border: 1px solid {{ $s[1] }}30;">
                                {{ $s[0] }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- Bottom CTA --}}
        <div class="text-center" id="res-cta">
            <p class="text-sm mb-6" style="color: rgba(255,255,255,0.4);">
                Ready to start? Go back to the walkthrough.
            </p>
            <a href="#steps"
               class="inline-flex items-center gap-2 px-7 py-3 rounded-xl text-sm font-bold text-white transition-all duration-200 hover:opacity-90"
               style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15);">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                Back to Quick Start
            </a>
        </div>

    </div>
</section>

<script>
    (function () {
        if (typeof inView === 'undefined' || typeof animate === 'undefined') return;
        inView('#res-header', function (el) {
            animate(el, { opacity: [0, 1], y: [28, 0] }, { duration: 0.7, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.4 });
        inView('#res-cards', function (el) {
            var cards = el.querySelectorAll('.res-card');
            animate(cards, { opacity: [0, 1], y: [24, 0] }, { delay: function(_, i) { return i * 0.12; }, duration: 0.5, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.2 });
        inView('#res-cta', function (el) {
            animate(el, { opacity: [0, 1], y: [16, 0] }, { duration: 0.5, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.5 });
    }());
</script>
