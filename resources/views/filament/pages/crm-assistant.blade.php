<x-filament-panels::page>
    @php
        $run     = $this->getAgentRun();
        $status  = $run?->status ?? 'idle';
        $steps   = $run?->steps ?? [];
        $summary = $this->getRunSummary();

        // SVG health gauge
        $score        = $summary['health_score'] ?? 0;
        $circumference = 314;
        $dashOffset   = round($circumference * (1 - $score / 100), 2);
        $gaugeColor   = match(true) {
            $score >= 80 => '#22c55e',
            $score >= 60 => '#f59e0b',
            default      => '#ef4444',
        };
        $gaugeBg = match(true) {
            $score >= 80 => 'text-success-600 dark:text-success-400',
            $score >= 60 => 'text-warning-500 dark:text-warning-400',
            default      => 'text-danger-500 dark:text-danger-400',
        };
    @endphp

    @if($isPolling)
        <div wire:poll.2000ms="checkStatus"></div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         HERO BANNER
         ═══════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-2xl p-6 md:p-8"
         style="background: linear-gradient(135deg, #172343 0%, #1e2f54 55%, #4d5fd8 100%);">

        {{-- Decorative grid dots --}}
        <div class="pointer-events-none absolute inset-0 opacity-10"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1 rounded-full border border-white/20 bg-white/10 px-2.5 py-0.5 text-xs font-medium text-white/80 backdrop-blur-sm">
                        <x-heroicon-o-sparkles class="w-3 h-3" />
                        AI
                    </span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-white md:text-3xl">Portfolio Intelligence</h1>
                <p class="mt-1 text-sm text-white/60">Autonomous analysis · Real-time insights · Actionable results</p>

                @if($run)
                    <p class="mt-3 text-xs text-white/40">
                        Last run {{ $run->created_at->diffForHumans() }}
                        @if($run->status === 'completed') · Analysis complete @elseif($run->status === 'failed') · Analysis failed @elseif($run->status === 'running') · Running now… @endif
                    </p>
                @endif
            </div>

            <div class="flex items-center gap-3 shrink-0">
                {{-- Status badge --}}
                @if($status === 'idle')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white/70 backdrop-blur-sm border border-white/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-white/50"></span>
                        No runs yet
                    </span>
                @elseif($status === 'pending' || $status === 'running')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-medium text-white backdrop-blur-sm border border-white/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        Analysing…
                    </span>
                @elseif($status === 'completed')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-success-500/20 px-3 py-1 text-xs font-medium text-success-300 backdrop-blur-sm border border-success-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-success-400"></span>
                        Completed
                    </span>
                @elseif($status === 'failed')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-danger-500/20 px-3 py-1 text-xs font-medium text-danger-300 backdrop-blur-sm border border-danger-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-danger-400"></span>
                        Failed
                    </span>
                @endif

                <x-filament::button
                    wire:click="runAnalysis"
                    wire:loading.attr="disabled"
                    :disabled="$status === 'pending' || $status === 'running'"
                    icon="heroicon-o-play"
                    color="gray"
                    size="sm"
                >
                    @if($status === 'pending' || $status === 'running')
                        Analysing…
                    @else
                        Run Analysis
                    @endif
                </x-filament::button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         RESULTS DASHBOARD (completed only)
         ═══════════════════════════════════════════════════ --}}
    @if($status === 'completed' && count($summary) > 0)
        {{-- Stat cards row --}}
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            @php
                $cards = [
                    ['label' => 'Companies', 'value' => $summary['total'], 'icon' => 'heroicon-o-building-office-2', 'color' => 'info', 'bg' => 'bg-info-50 dark:bg-info-500/10', 'text' => 'text-info-600 dark:text-info-400'],
                    ['label' => 'Healthy',   'value' => $summary['healthy'], 'icon' => 'heroicon-o-check-circle',     'color' => 'success', 'bg' => 'bg-success-50 dark:bg-success-500/10', 'text' => 'text-success-600 dark:text-success-400'],
                    ['label' => 'At-Risk',   'value' => $summary['at_risk'], 'icon' => 'heroicon-o-exclamation-triangle', 'color' => $summary['at_risk'] === 0 ? 'success' : 'danger', 'bg' => $summary['at_risk'] === 0 ? 'bg-success-50 dark:bg-success-500/10' : 'bg-danger-50 dark:bg-danger-500/10', 'text' => $summary['at_risk'] === 0 ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400'],
                    ['label' => 'Actions',   'value' => $summary['actions'], 'icon' => 'heroicon-o-bolt', 'color' => 'primary', 'bg' => 'bg-primary-50 dark:bg-primary-500/10', 'text' => 'text-primary-600 dark:text-primary-400'],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 p-4 flex items-center gap-4"
                     style="animation: count-up-fade 0.4s ease-out {{ $loop->index * 80 }}ms both;">
                    <div class="shrink-0 rounded-xl p-2.5 {{ $card['bg'] }}">
                        <x-dynamic-component :component="$card['icon']" class="w-5 h-5 {{ $card['text'] }}" />
                    </div>
                    <div>
                        <div
                            class="text-2xl font-bold text-gray-950 dark:text-white tabular-nums"
                            x-data="{ count: 0, target: {{ $card['value'] }} }"
                            x-init="$nextTick(() => {
                                if (target === 0) { count = 0; return; }
                                let elapsed = 0, duration = 700, start = performance.now();
                                requestAnimationFrame(function tick(now) {
                                    elapsed = now - start;
                                    count = Math.min(Math.round((elapsed / duration) * target), target);
                                    if (elapsed < duration) requestAnimationFrame(tick);
                                });
                            })"
                            x-text="count">0</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $card['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Health gauge + Company matrix --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            {{-- Health gauge --}}
            <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 p-6 flex flex-col items-center justify-center gap-4">
                <h3 class="text-sm font-semibold text-gray-950 dark:text-white self-start">Portfolio Health</h3>

                <div class="relative flex items-center justify-center">
                    <svg class="-rotate-90 drop-shadow-sm" viewBox="0 0 120 120" width="150" height="150">
                        <circle cx="60" cy="60" r="50" fill="none" stroke-width="10"
                                class="stroke-gray-100 dark:stroke-white/10" />
                        <circle cx="60" cy="60" r="50" fill="none" stroke-width="10"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $circumference }}"
                                stroke="{{ $gaugeColor }}"
                                style="transition: stroke-dashoffset 1.4s cubic-bezier(0.4, 0, 0.2, 1);"
                                x-data
                                x-init="$nextTick(() => {
                                    setTimeout(() => {
                                        $el.style.strokeDashoffset = '{{ $dashOffset }}';
                                    }, 200);
                                })" />
                    </svg>
                    <div class="absolute flex flex-col items-center">
                        <span
                            class="text-3xl font-bold tabular-nums {{ $gaugeBg }}"
                            x-data="{ count: 0 }"
                            x-init="setTimeout(() => {
                                let target = {{ $score }}, start = performance.now(), duration = 1200;
                                requestAnimationFrame(function tick(now) {
                                    let p = Math.min((now - start) / duration, 1);
                                    count = Math.round(p * target);
                                    if (p < 1) requestAnimationFrame(tick);
                                });
                            }, 200)"
                            x-text="count + '%'">0%</span>
                        <span class="text-xs text-gray-400 dark:text-gray-500">health score</span>
                    </div>
                </div>

                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-success-500"></span>
                        <span class="text-gray-600 dark:text-gray-300">{{ $summary['healthy'] }} healthy</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-danger-500"></span>
                        <span class="text-gray-600 dark:text-gray-300">{{ $summary['at_risk'] }} at-risk</span>
                    </div>
                </div>
            </div>

            {{-- Company risk matrix --}}
            <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-white/5">
                    <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Company Risk Overview</h3>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-white/5 max-h-[260px] overflow-y-auto">
                    @foreach($summary['companies'] as $i => $company)
                        @php
                            $isAtRisk = $company['risk'] === 'at_risk';
                        @endphp
                        <div class="flex items-center justify-between px-5 py-3 opacity-0"
                             style="animation: fade-slide-in 0.3s ease-out {{ $i * 50 }}ms forwards;">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0
                                            {{ $isAtRisk ? 'bg-danger-50 dark:bg-danger-500/10' : 'bg-success-50 dark:bg-success-500/10' }}">
                                    @if($isAtRisk)
                                        <x-heroicon-o-exclamation-triangle class="w-3.5 h-3.5 text-danger-500 dark:text-danger-400" />
                                    @else
                                        <x-heroicon-o-check-circle class="w-3.5 h-3.5 text-success-500 dark:text-success-400" />
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $company['name'] }}</span>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                         {{ $isAtRisk
                                            ? 'bg-danger-50 dark:bg-danger-500/10 text-danger-700 dark:text-danger-400'
                                            : 'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400' }}">
                                {{ $isAtRisk ? 'At-Risk' : 'Healthy' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         ACTIVITY LOG (collapsible)
         ═══════════════════════════════════════════════════ --}}
    @if(count($steps) > 0)
        <div
            class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 overflow-hidden"
            x-data="{ open: {{ $isPolling ? 'true' : 'false' }} }">

            <button
                @click="open = !open"
                class="w-full flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-list-bullet class="w-4 h-4 text-gray-400 dark:text-gray-500" />
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Analysis Log</span>
                    <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-white/10 px-2 py-0.5 text-xs font-medium text-gray-500 dark:text-gray-400">
                        {{ count($steps) }} steps
                    </span>
                </div>
                <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                           :class="open ? 'rotate-180' : ''" />
            </button>

            <div x-show="open" x-collapse>
                <div class="divide-y divide-gray-50 dark:divide-white/5 max-h-[380px] overflow-y-auto border-t border-gray-100 dark:border-white/5">
                    @foreach($steps as $step)
                        @php
                            $type      = $step['type'] ?? 'tool_call';
                            $summary_t = $step['summary'] ?? ($step['content'] ?? '');
                            $ts        = isset($step['timestamp'])
                                ? \Illuminate\Support\Carbon::parse($step['timestamp'])->format('H:i:s')
                                : '';
                        @endphp
                        <div class="flex items-start gap-3 px-5 py-2.5">
                            <div class="mt-0.5 shrink-0">
                                @if($type === 'tool_call')
                                    <div class="w-5 h-5 rounded-full bg-teal-50 dark:bg-teal-500/10 flex items-center justify-center">
                                        <x-heroicon-o-magnifying-glass class="w-3 h-3 text-teal-600 dark:text-teal-400" />
                                    </div>
                                @elseif($type === 'tool_result')
                                    <div class="w-5 h-5 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center">
                                        <x-heroicon-o-clipboard-document-list class="w-3 h-3 text-gray-400 dark:text-gray-500" />
                                    </div>
                                @elseif($type === 'record_created')
                                    <div class="w-5 h-5 rounded-full bg-success-50 dark:bg-success-500/10 flex items-center justify-center">
                                        <x-heroicon-o-check-circle class="w-3 h-3 text-success-500 dark:text-success-400" />
                                    </div>
                                @else
                                    <div class="w-5 h-5 rounded-full bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center">
                                        <x-heroicon-o-light-bulb class="w-3 h-3 text-violet-500 dark:text-violet-400" />
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">{{ $summary_t }}</p>
                                @if($type === 'record_created' && isset($step['company']))
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                        {{ ucfirst($step['entity_type'] ?? 'record') }} on {{ $step['company'] }}
                                    </p>
                                @endif
                            </div>
                            @if($ts)
                                <span class="text-xs text-gray-300 dark:text-gray-600 shrink-0 font-mono mt-0.5">{{ $ts }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         PORTFOLIO HEALTH REPORT (markdown)
         ═══════════════════════════════════════════════════ --}}
    @if($status === 'completed' && $run?->summary)
        <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-4 border-b border-gray-100 dark:border-white/5">
                <x-heroicon-o-document-chart-bar class="w-4 h-4 text-primary-500" />
                <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Portfolio Health Report</h3>
            </div>
            <div class="px-5 py-5">
                <div class="prose prose-sm dark:prose-invert max-w-none
                            prose-headings:font-semibold prose-headings:tracking-tight
                            prose-h2:text-base prose-h3:text-sm
                            prose-table:text-xs prose-td:py-1.5 prose-th:py-1.5
                            prose-hr:border-gray-100 dark:prose-hr:border-white/10">
                    {!! \Illuminate\Support\Str::markdown($run->summary, ['html_input' => 'strip']) !!}
                </div>
            </div>
        </div>
    @endif

    {{-- Failed state --}}
    @if($status === 'failed' && $run?->summary)
        <div class="rounded-xl border border-danger-200 dark:border-danger-500/30 bg-danger-50 dark:bg-danger-500/5 p-5">
            <div class="flex items-center gap-2 mb-2">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-danger-600 dark:text-danger-400" />
                <h3 class="text-sm font-semibold text-danger-800 dark:text-danger-300">Analysis Failed</h3>
            </div>
            <p class="text-sm text-danger-700 dark:text-danger-400">{{ $run->summary }}</p>
        </div>
    @endif

    {{-- Empty state --}}
    @if($status === 'idle')
        <div class="rounded-xl border border-dashed border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-10 flex flex-col items-center gap-3 text-center">
            <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center">
                <x-heroicon-o-sparkles class="w-6 h-6 text-primary-500" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">No analysis yet</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Click "Run Analysis" to analyse your portfolio.</p>
            </div>
        </div>
    @endif
</x-filament-panels::page>
