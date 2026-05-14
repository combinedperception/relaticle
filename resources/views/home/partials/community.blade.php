<section class="relative py-24 md:py-32 overflow-hidden bg-gray-50 dark:bg-gray-950/60">
    {{-- Dot pattern --}}
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.04]"
         style="background-image: radial-gradient(circle, #172343 1px, transparent 1px); background-size: 24px 24px; mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, black 20%, transparent 100%);"
         aria-hidden="true"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16 md:mb-20" id="audience-header">
            <span class="inline-block text-xs font-bold uppercase tracking-widest mb-4 px-3 py-1 rounded-full"
                  style="background: rgba(16,186,233,0.1); color: #10BAE9; border: 1px solid rgba(16,186,233,0.2);">
                Who It's For
            </span>
            <h2 class="font-display text-3xl sm:text-4xl md:text-[2.6rem] font-black text-gray-950 dark:text-white tracking-tight leading-[1.1] text-balance">
                Built for teams that move<br class="hidden sm:block"/>
                <span style="background: linear-gradient(120deg, #6171F7, #10BAE9); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    with intelligence.
                </span>
            </h2>
            <p class="mt-5 text-gray-500 dark:text-gray-400 text-base sm:text-lg max-w-lg mx-auto leading-relaxed">
                Combined Perception CRM is purpose-built for enterprise teams that are already working with AI and need their CRM to keep up.
            </p>
        </div>

        {{-- Audience cards --}}
        <div id="audience-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @php
            $audiences = [
                [
                    'badge' => 'Enterprise Teams',
                    'headline' => 'Operations and relationship teams managing complex, high-value accounts',
                    'points' => [
                        'Multiple teams sharing CRM data with strict access boundaries',
                        'Complex account hierarchies with custom fields',
                        'Executive-level relationship tracking',
                        'Audit trails and compliance-ready activity logs',
                    ],
                    'gradient' => 'from-[#6171F7] to-[#10BAE9]',
                    'accent' => '#6171F7',
                ],
                [
                    'badge' => 'AI-Forward Orgs',
                    'headline' => 'Teams already using AI assistants and looking to extend them into CRM workflows',
                    'points' => [
                        'Let Claude or GPT read and update your CRM directly',
                        '30 MCP tools for full CRUD from any agent',
                        'AI-generated record summaries and risk scores',
                        'Structured data for RAG and prompt augmentation',
                    ],
                    'gradient' => 'from-[#10BAE9] to-[#6171F7]',
                    'accent' => '#10BAE9',
                ],
                [
                    'badge' => 'Lean Startups',
                    'headline' => 'Small, fast-moving teams who need CRM power without the enterprise price tag',
                    'points' => [
                        'Get productive in hours, not weeks',
                        'No per-seat lock-in or vendor dependency',
                        'Self-hosted — your data, your infrastructure',
                        'Extensible custom fields adapt to your model',
                    ],
                    'gradient' => 'from-[#7c3aed] to-[#6171F7]',
                    'accent' => '#7c3aed',
                ],
            ];
            @endphp

            @foreach($audiences as $aud)
                <div class="audience-card relative rounded-2xl p-7 flex flex-col gap-5
                            bg-white dark:bg-white/[0.03]
                            border border-gray-200 dark:border-white/[0.07]
                            hover:shadow-lg dark:hover:shadow-black/20 transition-shadow duration-300">

                    {{-- Badge --}}
                    <span class="inline-flex w-fit text-xs font-bold px-3 py-1 rounded-full text-white"
                          style="background: linear-gradient(135deg, {{ $aud['accent'] }}, {{ $aud['accent'] }}aa);">
                        {{ $aud['badge'] }}
                    </span>

                    <h3 class="text-[15px] font-bold text-gray-950 dark:text-white leading-snug">
                        {{ $aud['headline'] }}
                    </h3>

                    <ul class="flex flex-col gap-2.5">
                        @foreach($aud['points'] as $point)
                            <li class="flex items-start gap-2.5 text-sm text-gray-500 dark:text-gray-400">
                                <span class="mt-0.5 w-4 h-4 flex-shrink-0 rounded-full flex items-center justify-center"
                                      style="background: {{ $aud['accent'] }}18;">
                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 12 12" style="color: {{ $aud['accent'] }};">
                                        <path d="M2 6l3 3 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    (function () {
        if (typeof inView === 'undefined' || typeof animate === 'undefined') return;
        inView('#audience-header', function (el) {
            animate(el, { opacity: [0, 1], y: [28, 0] }, { duration: 0.7, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.4 });
        document.querySelectorAll('.audience-card').forEach(function (card, i) {
            inView(card, function (el) {
                animate(el, { opacity: [0, 1], y: [20, 0] }, {
                    duration: 0.55,
                    delay: i * 0.1,
                    easing: [0.16, 1, 0.3, 1],
                });
            }, { amount: 0.2 });
        });
    }());
</script>
