<section id="architecture" class="py-24 md:py-32 bg-white dark:bg-gray-950 relative overflow-hidden">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16 md:mb-20" id="arch-header">
            <span class="inline-block text-xs font-bold uppercase tracking-widest mb-4 px-3 py-1 rounded-full"
                  style="background: rgba(97,113,247,0.1); color: #6171F7; border: 1px solid rgba(97,113,247,0.2);">
                Architecture
            </span>
            <h2 class="font-display text-3xl sm:text-4xl md:text-[2.6rem] font-black text-gray-950 dark:text-white tracking-tight leading-[1.1] text-balance">
                How it all fits together
            </h2>
            <p class="mt-5 text-gray-500 dark:text-gray-400 text-base sm:text-lg max-w-lg mx-auto leading-relaxed">
                A production-grade Laravel stack that speaks both human and machine — designed for real-time AI integration without sacrificing data integrity.
            </p>
        </div>

        {{-- Architecture flow diagram --}}
        <div id="arch-diagram" class="relative">

            {{-- Outer card --}}
            <div class="rounded-3xl border border-gray-100 dark:border-white/[0.07] bg-gray-50 dark:bg-white/[0.02] p-8 md:p-12">

                {{-- Flow: horizontal on md+, vertical on mobile --}}
                <div class="flex flex-col md:flex-row items-stretch gap-4 md:gap-0">

                    @php
                    $nodes = [
                        [
                            'label' => 'Your Team',
                            'sublabel' => 'Browser / API',
                            'icon' => '<svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',
                            'color' => '#6171F7',
                        ],
                        [
                            'label' => 'Combined Perception CRM',
                            'sublabel' => 'Laravel · PostgreSQL · Redis',
                            'icon' => '<svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4.03 3-9 3S3 13.66 3 12"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/></svg>',
                            'color' => '#10BAE9',
                            'wide' => true,
                        ],
                        [
                            'label' => 'MCP Server',
                            'sublabel' => '30 Tools · JSON-RPC',
                            'icon' => '<svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>',
                            'color' => '#7c3aed',
                        ],
                        [
                            'label' => 'AI Agents',
                            'sublabel' => 'Claude · GPT · Any MCP Client',
                            'icon' => '<svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a2 2 0 012 2v1a2 2 0 01-2 2 2 2 0 01-2-2V4a2 2 0 012-2z"/><path d="M12 15a2 2 0 012 2v1a2 2 0 01-2 2 2 2 0 01-2-2v-1a2 2 0 012-2z"/><path d="M4 9a2 2 0 012-2h1a2 2 0 012 2 2 2 0 01-2 2H6a2 2 0 01-2-2z"/><path d="M15 9a2 2 0 012-2h1a2 2 0 012 2 2 2 0 01-2 2h-1a2 2 0 01-2-2z"/><path d="M10 9h4M12 11v4M8.5 7.5l1.5 1.5M15.5 7.5L14 9M8.5 10.5l2 2M13 13l2.5 2.5"/></svg>',
                            'color' => '#f59e0b',
                        ],
                    ];
                    @endphp

                    @foreach($nodes as $i => $node)
                        {{-- Node --}}
                        <div class="arch-node flex-1 {{ $node['wide'] ?? false ? 'md:flex-[1.4]' : '' }} flex flex-col items-center justify-center text-center gap-3 rounded-2xl p-5
                                    bg-white dark:bg-white/[0.04] border border-gray-200 dark:border-white/[0.08]
                                    hover:shadow-md dark:hover:shadow-black/30 transition-shadow duration-300">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background: {{ $node['color'] }}18; color: {{ $node['color'] }};">
                                {!! $node['icon'] !!}
                            </div>
                            <div>
                                <div class="font-bold text-sm text-gray-950 dark:text-white leading-tight">{{ $node['label'] }}</div>
                                <div class="text-xs mt-1" style="color: {{ $node['color'] }}; opacity: 0.8;">{{ $node['sublabel'] }}</div>
                            </div>
                        </div>

                        {{-- Arrow connector (between nodes) --}}
                        @if(!$loop->last)
                            <div class="arch-arrow flex items-center justify-center px-1 md:px-2 py-2 md:py-0 flex-shrink-0">
                                {{-- Horizontal arrow (desktop) --}}
                                <svg class="hidden md:block w-8 h-5" viewBox="0 0 32 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="0" y1="10" x2="24" y2="10" stroke="url(#grad-h-{{ $i }})" stroke-width="1.5" stroke-dasharray="4 3"/>
                                    <polyline points="20,5 28,10 20,15" stroke="url(#grad-h-{{ $i }})" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                    <defs>
                                        <linearGradient id="grad-h-{{ $i }}" x1="0" y1="0" x2="32" y2="0" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#6171F7"/>
                                            <stop offset="1" stop-color="#10BAE9"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                                {{-- Vertical arrow (mobile) --}}
                                <svg class="md:hidden w-5 h-8" viewBox="0 0 20 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="10" y1="0" x2="10" y2="24" stroke="url(#grad-v-{{ $i }})" stroke-width="1.5" stroke-dasharray="4 3"/>
                                    <polyline points="5,20 10,28 15,20" stroke="url(#grad-v-{{ $i }})" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                    <defs>
                                        <linearGradient id="grad-v-{{ $i }}" x1="0" y1="0" x2="0" y2="32" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#6171F7"/>
                                            <stop offset="1" stop-color="#10BAE9"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Tech stack chips --}}
                <div class="mt-10 pt-8 border-t border-gray-100 dark:border-white/[0.06]">
                    <p class="text-xs font-semibold uppercase tracking-widest text-center text-gray-400 dark:text-gray-600 mb-5">Technology Stack</p>
                    <div class="flex flex-wrap justify-center gap-2.5">
                        @foreach(['Laravel 13', 'PHP 8.4', 'PostgreSQL', 'Redis', 'Filament 5', 'Livewire 4', 'Alpine.js', 'Docker'] as $tech)
                            <span class="text-xs font-medium px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-white/[0.05] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/[0.07]">
                                {{ $tech }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        if (typeof inView === 'undefined' || typeof animate === 'undefined') return;
        inView('#arch-header', function (el) {
            animate(el, { opacity: [0, 1], y: [28, 0] }, { duration: 0.7, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.4 });
        inView('#arch-diagram', function (el) {
            animate(el, { opacity: [0, 1], y: [20, 0] }, { duration: 0.8, easing: [0.16, 1, 0.3, 1] });
            document.querySelectorAll('.arch-node').forEach(function (node, i) {
                animate(node, { opacity: [0, 1], scale: [0.95, 1] }, {
                    duration: 0.5, delay: i * 0.1, easing: [0.16, 1, 0.3, 1],
                });
            });
        }, { amount: 0.3 });
    }());
</script>
