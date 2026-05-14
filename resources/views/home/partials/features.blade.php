<section id="features" class="py-20 md:py-28 bg-white dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="text-center mb-14" id="features-header">
            <span class="inline-block text-xs font-bold uppercase tracking-widest mb-4 px-3 py-1 rounded-full"
                  style="background: rgba(97,113,247,0.1); color: #6171F7; border: 1px solid rgba(97,113,247,0.2);">
                Capabilities
            </span>
            <h2 class="font-display text-3xl sm:text-4xl md:text-[2.75rem] font-black text-gray-950 dark:text-white tracking-tight leading-[1.1] text-balance">
                Everything your team needs.<br class="hidden sm:block"/>
                <span style="background: linear-gradient(120deg, #6171F7, #10BAE9); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Everything your AI needs too.
                </span>
            </h2>
            <p class="mt-5 text-gray-500 dark:text-gray-400 text-base sm:text-lg max-w-xl mx-auto leading-relaxed">
                A complete relationship intelligence platform designed from the ground up to be operated by both humans and AI agents.
            </p>
        </div>

        {{-- Capabilities grid --}}
        <div id="capabilities-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            @php
            $capabilities = [
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                    'title' => 'Company & People Management',
                    'desc' => 'Centralise your accounts, contacts, and relationship history. Rich profiles with activity timelines and cross-linked records.',
                    'accent' => '#6171F7',
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                    'title' => 'Sales Pipeline',
                    'desc' => 'Visual kanban-style pipeline with customisable stages, deal tracking, and forecasting. Move opportunities from first contact to closed.',
                    'accent' => '#10BAE9',
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v3"/><path d="M15 13v4a2 2 0 002 2h4M15 17l4-4"/></svg>',
                    'title' => 'AI-Powered Insights',
                    'desc' => 'Auto-generated summaries, relationship scoring, and narrative explanations for each record — powered by Claude and updated as your data evolves.',
                    'accent' => '#7c3aed',
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>',
                    'title' => 'Custom Data Model',
                    'desc' => '22 field types — text, currency, date, select, relationship links, conditional visibility, and per-field encryption. No migrations or code changes needed.',
                    'accent' => '#f59e0b',
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><path d="M7 8h.01M11 8h2M7 11h.01M11 11h6"/></svg>',
                    'title' => 'MCP Integration — 30 Tools',
                    'desc' => 'A full MCP server with 30 tools for CRUD, schema discovery, search, and bulk operations. Connect Claude, GPT, or any MCP-compatible AI agent.',
                    'accent' => '#10BAE9',
                ],
                [
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',
                    'title' => 'Team Collaboration',
                    'desc' => 'Multi-team workspaces with 5-layer authorization, shared notes, activity logs, task assignment, and database notifications.',
                    'accent' => '#6171F7',
                ],
            ];
            @endphp

            @foreach($capabilities as $cap)
                <div class="capability-card group relative rounded-2xl p-6 transition-all duration-300
                            bg-gray-50 hover:bg-white dark:bg-white/[0.03] dark:hover:bg-white/[0.06]
                            border border-gray-100 dark:border-white/[0.07] hover:border-gray-200 dark:hover:border-white/[0.12]
                            hover:shadow-xl dark:hover:shadow-black/30">

                    {{-- Hover accent glow --}}
                    <div class="absolute top-0 right-0 w-24 h-24 rounded-2xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                         style="background: radial-gradient(circle at top right, {{ $cap['accent'] }}22 0%, transparent 70%);" aria-hidden="true"></div>

                    {{-- Icon container --}}
                    <div class="mb-5 w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background: {{ $cap['accent'] }}18; color: {{ $cap['accent'] }};">
                        {!! $cap['icon'] !!}
                    </div>

                    <h3 class="text-base font-bold text-gray-950 dark:text-white mb-2.5">
                        {{ $cap['title'] }}
                    </h3>
                    <p class="text-sm leading-relaxed text-gray-500 dark:text-gray-400">
                        {{ $cap['desc'] }}
                    </p>
                </div>
            @endforeach

        </div>
    </div>
</section>

<script>
    (function () {
        if (typeof inView === 'undefined' || typeof animate === 'undefined') return;
        inView('#features-header', function (el) {
            animate(el, { opacity: [0, 1], y: [28, 0] }, { duration: 0.7, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.4 });
        var cards = document.querySelectorAll('.capability-card');
        cards.forEach(function (card, i) {
            inView(card, function (el) {
                animate(el, { opacity: [0, 1], y: [24, 0] }, {
                    duration: 0.55,
                    delay: (i % 3) * 0.07,
                    easing: [0.16, 1, 0.3, 1],
                });
            }, { amount: 0.15 });
        });
    }());
</script>
