<section class="py-20 md:py-28 bg-gray-50 dark:bg-gray-950/60" id="bugs">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-14" id="bug-header">
            <span class="inline-block text-xs font-bold uppercase tracking-widest mb-4 px-3 py-1 rounded-full"
                  style="background: rgba(239,68,68,0.08); color: #f87171; border: 1px solid rgba(239,68,68,0.2);">
                Bug Reporting
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-950 dark:text-white tracking-tight leading-[1.1]"
                style="font-family: 'Archivo Black', system-ui, sans-serif;">
                Found a bug? We want to know.
            </h2>
            <p class="mt-4 text-gray-500 dark:text-gray-400 text-base sm:text-lg max-w-lg mx-auto leading-relaxed">
                High-quality bug reports fix issues faster. Follow these four steps and we'll triage within 48 hours.
            </p>
        </div>

        {{-- 4-step flow --}}
        @php
        $bugSteps = [
            [
                'num'   => '01',
                'title' => 'Reproduce it',
                'color' => '#6171F7',
                'icon'  => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>',
                'items' => [
                    'Identify the exact steps that trigger the bug',
                    'Note your browser, OS, and any relevant custom field setup',
                    'Check if it happens in multiple browsers or just one',
                    'Capture a screenshot or screen recording if possible',
                ],
            ],
            [
                'num'   => '02',
                'title' => 'Check existing issues',
                'color' => '#10BAE9',
                'icon'  => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>',
                'items' => [
                    'Search GitHub Issues for keywords from your bug',
                    'Check open and closed issues — it may already be fixed',
                    'If found, add a +1 reaction or extra reproduction details',
                    'Review the milestone to see if it\'s already scheduled',
                ],
            ],
            [
                'num'   => '03',
                'title' => 'Open a new issue',
                'color' => '#f59e0b',
                'icon'  => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>',
                'items' => [
                    'Use a clear title: "[Bug] What breaks, where, and how"',
                    'Include numbered reproduction steps',
                    'State the expected behaviour vs. what actually happened',
                    'Attach screenshots, console errors, or network trace if relevant',
                ],
            ],
            [
                'num'   => '04',
                'title' => 'Label and submit',
                'color' => '#28c840',
                'icon'  => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'items' => [
                    'Add the "bug" label before submitting',
                    'Add "priority: high" if it blocks core workflows',
                    'We triage all new issues within 48 hours',
                    'You\'ll receive email updates as the issue progresses',
                ],
            ],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-12" id="bug-steps">
            @foreach($bugSteps as $bsi => $bs)
                <div class="bug-step-card rounded-2xl p-6 bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/[0.07] hover:shadow-lg dark:hover:shadow-black/30 transition-all duration-300 hover:-translate-y-0.5">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                             style="background: {{ $bs['color'] }}15; color: {{ $bs['color'] }}; border: 1px solid {{ $bs['color'] }}25;">
                            {!! $bs['icon'] !!}
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest mb-0.5"
                                 style="color: {{ $bs['color'] }};">Step {{ $bs['num'] }}</div>
                            <div class="text-base font-black text-gray-950 dark:text-white leading-tight"
                                 style="font-family: 'Archivo Black', system-ui, sans-serif;">{{ $bs['title'] }}</div>
                        </div>
                    </div>
                    <ul class="space-y-2.5">
                        @foreach($bs['items'] as $item)
                            <li class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" viewBox="0 0 14 14" fill="none">
                                    <circle cx="7" cy="7" r="6.5" stroke="{{ $bs['color'] }}" stroke-opacity="0.3"/>
                                    <path d="M4.5 7l2 2 3-3" stroke="{{ $bs['color'] }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        {{-- GitHub CTA --}}
        <div class="text-center" id="bug-cta">
            <a href="https://github.com/combinedperception/relaticle/issues/new?template=bug_report.md"
               target="_blank" rel="noopener"
               class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl text-sm font-bold text-white transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5"
               style="background: linear-gradient(135deg, #6171F7 0%, #10BAE9 100%); box-shadow: 0 0 32px rgba(97,113,247,0.3);">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
                Open a Bug Report on GitHub
            </a>

            <p class="mt-5 text-sm text-gray-500 dark:text-gray-500">
                Or
                <a href="https://github.com/combinedperception/relaticle/issues"
                   target="_blank" rel="noopener"
                   class="underline underline-offset-2 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    browse open issues
                </a>
                to see what's already being tracked.
            </p>
        </div>

        {{-- Security disclosure note --}}
        <div class="mt-10 rounded-2xl px-6 py-4 flex items-start gap-3 max-w-2xl mx-auto"
             style="background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.15);">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color: #f87171;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="text-sm" style="color: rgba(248,113,113,0.85);">
                <strong class="font-bold">Security vulnerabilities</strong> should not be reported via public GitHub Issues. Email the maintainers directly and allow 48 hours before disclosure.
            </div>
        </div>

    </div>
</section>

<script>
    (function () {
        if (typeof inView === 'undefined' || typeof animate === 'undefined') return;
        inView('#bug-header', function (el) {
            animate(el, { opacity: [0, 1], y: [28, 0] }, { duration: 0.7, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.4 });
        inView('#bug-steps', function (el) {
            var cards = el.querySelectorAll('.bug-step-card');
            animate(cards, { opacity: [0, 1], y: [24, 0] }, { delay: function(_, i) { return i * 0.1; }, duration: 0.5, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.2 });
        inView('#bug-cta', function (el) {
            animate(el, { opacity: [0, 1], scale: [0.96, 1] }, { duration: 0.5, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.5 });
    }());
</script>
