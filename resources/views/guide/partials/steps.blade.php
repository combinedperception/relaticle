<section id="steps" class="py-20 md:py-28 bg-white dark:bg-gray-950"
         x-data="{ openStep: 0 }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-14" id="steps-header">
            <span class="inline-block text-xs font-bold uppercase tracking-widest mb-4 px-3 py-1 rounded-full"
                  style="background: rgba(97,113,247,0.1); color: #6171F7; border: 1px solid rgba(97,113,247,0.2);">
                Quick Start
            </span>
            <h2 class="font-display text-3xl sm:text-4xl font-black text-gray-950 dark:text-white tracking-tight leading-[1.1]"
                style="font-family: 'Archivo Black', system-ui, sans-serif;">
                Five steps to operational
            </h2>
            <p class="mt-4 text-gray-500 dark:text-gray-400 text-base sm:text-lg max-w-lg mx-auto leading-relaxed">
                Follow this walkthrough in order. Each step builds on the last. Click any step to expand the details.
            </p>
        </div>

        {{-- Timeline --}}
        @php
        $steps = [
            [
                'num' => '01',
                'title' => 'Create your workspace',
                'summary' => 'Set up your team, invite colleagues, and configure your first workspace.',
                'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                'color' => '#6171F7',
                'detail' => [
                    'Register an account at the login page and confirm your email.',
                    'Click "Create Team" and enter your organisation name.',
                    'Invite colleagues by entering their email addresses — they\'ll receive an invitation email.',
                    'Set your team slug — this becomes part of your workspace URL.',
                    'You\'re now in your workspace. All data is isolated to your team by default.',
                ],
                'tip' => 'Pro tip: use a descriptive team name like "Acme Corp — Sales" if you plan to run multiple workspaces.',
            ],
            [
                'num' => '02',
                'title' => 'Add companies & contacts',
                'summary' => 'Build your contact network — import from CSV or add records one by one.',
                'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                'color' => '#10BAE9',
                'detail' => [
                    'Navigate to Companies in the left sidebar and click "New Company".',
                    'Fill in name, industry, website, and any custom fields you\'ve configured.',
                    'Inside a company, add People — contacts linked to that organisation.',
                    'Use the Import button to bulk-import from a CSV file (columns auto-mapped).',
                    'Each record gets an Activity Timeline automatically populated as you interact.',
                ],
                'tip' => 'Pro tip: import a CSV first to build your base, then enrich individual records manually.',
            ],
            [
                'num' => '03',
                'title' => 'Build your pipeline',
                'summary' => 'Create opportunities, assign pipeline stages, and start tracking deals.',
                'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                'color' => '#7c3aed',
                'detail' => [
                    'Open Opportunities in the sidebar to see the kanban board view.',
                    'Click "New Opportunity" — link it to a company and set a value and close date.',
                    'Drag cards between pipeline stages as deals progress.',
                    'Add notes and tasks directly on each opportunity card.',
                    'Use filters to view by stage, owner, value range, or close-date window.',
                ],
                'tip' => 'Pro tip: create a "Closed Lost" stage to capture why deals are lost — invaluable for AI analysis.',
            ],
            [
                'num' => '04',
                'title' => 'Customise your data model',
                'summary' => 'Add custom fields to any entity — no migrations, no code.',
                'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>',
                'color' => '#f59e0b',
                'detail' => [
                    'Go to Settings → Custom Fields in the sidebar.',
                    'Choose an entity (Company, Person, Opportunity) and click "Add Field".',
                    'Pick from 22 field types: text, number, currency, date, select, multi-select, URL, email, phone, relationship, and more.',
                    'Set conditional visibility rules — show a field only when another field has a specific value.',
                    'Enable per-field encryption for sensitive data (API keys, NDA status).',
                ],
                'tip' => 'Pro tip: use Relationship fields to link custom entities together — builds a flexible graph of your data.',
            ],
            [
                'num' => '05',
                'title' => 'Connect your AI agent',
                'summary' => 'Point any MCP-compatible AI at the CRM and give it 30 tools to work with.',
                'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v3"/><path d="M15 13v4a2 2 0 002 2h4M15 17l4-4"/></svg>',
                'color' => '#10BAE9',
                'detail' => [
                    'Generate an API token under Settings → API Tokens.',
                    'In Claude Desktop (or any MCP client), add a server config pointing to your CRM\'s MCP endpoint: <code class="bg-gray-100 dark:bg-white/10 px-1 rounded text-xs">https://your-domain.com/mcp</code>',
                    'Set the Authorization header with your Bearer token.',
                    'Ask Claude: "List my top 5 companies by ARR" — it calls list-companies automatically.',
                    'Use natural language to create records, update stages, generate summaries, or discover your schema.',
                ],
                'tip' => 'Pro tip: ask the agent to "describe available tools" first — it will read the 30-tool manifest and plan accordingly.',
            ],
        ];
        @endphp

        <div class="relative" id="steps-timeline">
            {{-- Vertical connecting line --}}
            <div class="absolute left-[27px] top-0 bottom-0 w-px hidden sm:block"
                 style="background: linear-gradient(to bottom, transparent, #6171F7 10%, #10BAE9 90%, transparent);"
                 aria-hidden="true"></div>

            <div class="space-y-4">
                @foreach($steps as $i => $step)
                    <div class="guide-step-card relative sm:pl-16"
                         x-on:click="openStep = (openStep === {{ $i }}) ? null : {{ $i }}"
                         role="button"
                         tabindex="0"
                         @keydown.enter="openStep = (openStep === {{ $i }}) ? null : {{ $i }}"
                         :aria-expanded="(openStep === {{ $i }}).toString()">

                        {{-- Number badge --}}
                        <div class="absolute left-0 top-5 w-[54px] h-[54px] hidden sm:flex items-center justify-center rounded-full flex-shrink-0 transition-all duration-300"
                             :style="openStep === {{ $i }} ? 'background: linear-gradient(135deg, #6171F7, #10BAE9); box-shadow: 0 0 20px rgba(97,113,247,0.4);' : 'background: rgba(97,113,247,0.12); border: 1px solid rgba(97,113,247,0.3);'">
                            <span class="text-xs font-black" :style="openStep === {{ $i }} ? 'color: white;' : 'color: #6171F7;'"
                                  style="font-family: 'Archivo Black', system-ui, sans-serif;">{{ $step['num'] }}</span>
                        </div>

                        {{-- Card --}}
                        <div class="rounded-2xl cursor-pointer transition-all duration-300 border"
                             :class="openStep === {{ $i }}
                                 ? 'bg-white dark:bg-white/[0.05] border-gray-200 dark:border-white/[0.12] shadow-lg dark:shadow-black/30'
                                 : 'bg-gray-50 dark:bg-white/[0.02] border-gray-100 dark:border-white/[0.06] hover:border-gray-200 dark:hover:border-white/[0.10] hover:shadow-md'">

                            {{-- Summary row --}}
                            <div class="flex items-center gap-4 p-5">
                                {{-- Mobile badge --}}
                                <div class="sm:hidden w-10 h-10 flex items-center justify-center rounded-xl flex-shrink-0 transition-all duration-300"
                                     :style="openStep === {{ $i }} ? 'background: linear-gradient(135deg, #6171F7, #10BAE9);' : 'background: rgba(97,113,247,0.12);'">
                                    <span class="text-[11px] font-black" :style="openStep === {{ $i }} ? 'color: white;' : 'color: #6171F7;'"
                                          style="font-family: 'Archivo Black', system-ui, sans-serif;">{{ $step['num'] }}</span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <span class="text-base font-bold text-gray-950 dark:text-white">{{ $step['title'] }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $step['summary'] }}</p>
                                </div>

                                {{-- Chevron --}}
                                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full transition-all duration-300"
                                     :class="openStep === {{ $i }} ? 'bg-primary/10' : 'bg-gray-100 dark:bg-white/[0.05]'">
                                    <svg class="w-3.5 h-3.5 transition-transform duration-300"
                                         :class="openStep === {{ $i }} ? 'rotate-180' : ''"
                                         :style="openStep === {{ $i }} ? 'color: #6171F7;' : 'color: #9ca3af;'"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Expanded detail --}}
                            <div x-show="openStep === {{ $i }}"
                                 x-transition:enter="transition-all duration-300 ease-out"
                                 x-transition:enter-start="opacity-0 max-h-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition-all duration-200 ease-in"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="px-5 pb-5">
                                <div class="border-t border-gray-100 dark:border-white/[0.06] pt-5">
                                    <ol class="space-y-3">
                                        @foreach($step['detail'] as $di => $detail)
                                            <li class="flex items-start gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                <span class="mt-0.5 w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold text-white"
                                                      style="background: linear-gradient(135deg, #6171F7, #10BAE9); min-width: 1.25rem;">
                                                    {{ $di + 1 }}
                                                </span>
                                                <span>{!! $detail !!}</span>
                                            </li>
                                        @endforeach
                                    </ol>
                                    <div class="mt-4 flex items-start gap-2 rounded-xl px-4 py-3 text-sm"
                                         style="background: rgba(16,186,233,0.06); border: 1px solid rgba(16,186,233,0.18); color: rgba(16,186,233,0.9);">
                                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ $step['tip'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        if (typeof inView === 'undefined' || typeof animate === 'undefined') return;
        inView('#steps-header', function (el) {
            animate(el, { opacity: [0, 1], y: [28, 0] }, { duration: 0.7, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.4 });
        document.querySelectorAll('.guide-step-card').forEach(function (card, i) {
            inView(card, function (el) {
                animate(el, { opacity: [0, 1], x: [-20, 0] }, {
                    duration: 0.5, delay: i * 0.08, easing: [0.16, 1, 0.3, 1],
                });
            }, { amount: 0.2 });
        });
    }());
</script>
