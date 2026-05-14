<x-filament-panels::page>
    @php
        $run = $this->getAgentRun();
        $status = $run?->status ?? 'idle';
        $steps = $run?->steps ?? [];
    @endphp

    @if($isPolling)
        <div wire:poll.2000ms="checkStatus"></div>
    @endif

    {{-- Hero card --}}
    <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <x-heroicon-o-sparkles class="w-6 h-6 text-primary-500" />
                    <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Portfolio Intelligence</h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xl">
                    Analyses your entire portfolio, flags at-risk companies, and creates notes and follow-up tasks automatically.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                {{-- Status badge --}}
                @if($status === 'idle')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 dark:bg-white/10 px-2.5 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                        Idle
                    </span>
                @elseif($status === 'pending' || $status === 'running')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-50 dark:bg-primary-500/10 px-2.5 py-1 text-xs font-medium text-primary-700 dark:text-primary-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse"></span>
                        Running
                    </span>
                @elseif($status === 'completed')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-success-50 dark:bg-success-500/10 px-2.5 py-1 text-xs font-medium text-success-700 dark:text-success-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-success-500"></span>
                        Completed
                    </span>
                @elseif($status === 'failed')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-danger-50 dark:bg-danger-500/10 px-2.5 py-1 text-xs font-medium text-danger-700 dark:text-danger-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-danger-500"></span>
                        Failed
                    </span>
                @endif

                {{-- Run button --}}
                <x-filament::button
                    wire:click="runAnalysis"
                    wire:loading.attr="disabled"
                    :disabled="$status === 'pending' || $status === 'running'"
                    icon="heroicon-o-play"
                    color="primary"
                    size="sm"
                >
                    @if($status === 'pending' || $status === 'running')
                        Analysing…
                    @else
                        Run Portfolio Analysis
                    @endif
                </x-filament::button>
            </div>
        </div>
    </div>

    {{-- Step log --}}
    @if(count($steps) > 0)
        <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-white/5">
                <h3 class="text-sm font-medium text-gray-950 dark:text-white">Analysis Log</h3>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-white/5 max-h-[500px] overflow-y-auto">
                @foreach($steps as $step)
                    @php
                        $type = $step['type'] ?? 'tool_call';
                        $summary = $step['summary'] ?? ($step['content'] ?? '');
                        $timestamp = isset($step['timestamp']) ? \Illuminate\Support\Carbon::parse($step['timestamp'])->format('H:i:s') : '';
                    @endphp

                    <div class="flex items-start gap-3 px-4 py-2.5">
                        {{-- Icon by type --}}
                        <div class="mt-0.5 shrink-0">
                            @if($type === 'tool_call')
                                <div class="w-6 h-6 rounded-full bg-teal-50 dark:bg-teal-500/10 flex items-center justify-center">
                                    <x-heroicon-o-magnifying-glass class="w-3.5 h-3.5 text-teal-600 dark:text-teal-400" />
                                </div>
                            @elseif($type === 'tool_result')
                                <div class="w-6 h-6 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center">
                                    <x-heroicon-o-clipboard-document-list class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" />
                                </div>
                            @elseif($type === 'record_created')
                                <div class="w-6 h-6 rounded-full bg-success-50 dark:bg-success-500/10 flex items-center justify-center">
                                    <x-heroicon-o-check-circle class="w-3.5 h-3.5 text-success-600 dark:text-success-400" />
                                </div>
                            @else
                                <div class="w-6 h-6 rounded-full bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center">
                                    <x-heroicon-o-light-bulb class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" />
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $summary }}</p>
                            @if($type === 'record_created' && isset($step['company']))
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ ucfirst($step['entity_type'] ?? 'record') }} on {{ $step['company'] }}</p>
                            @endif
                        </div>

                        @if($timestamp)
                            <span class="text-xs text-gray-400 dark:text-gray-600 shrink-0 mt-0.5">{{ $timestamp }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Summary card --}}
    @if($status === 'completed' && $run?->summary)
        <div class="rounded-xl border border-success-200 dark:border-success-500/30 bg-success-50 dark:bg-success-500/5 p-5">
            <div class="flex items-center gap-2 mb-3">
                <x-heroicon-o-check-badge class="w-5 h-5 text-success-600 dark:text-success-400" />
                <h3 class="text-sm font-semibold text-success-800 dark:text-success-300">Portfolio Health Summary</h3>
            </div>
            <p class="text-sm text-success-700 dark:text-success-400 leading-relaxed whitespace-pre-wrap">{{ $run->summary }}</p>
        </div>
    @endif

    @if($status === 'failed' && $run?->summary)
        <div class="rounded-xl border border-danger-200 dark:border-danger-500/30 bg-danger-50 dark:bg-danger-500/5 p-5">
            <div class="flex items-center gap-2 mb-2">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-danger-600 dark:text-danger-400" />
                <h3 class="text-sm font-semibold text-danger-800 dark:text-danger-300">Analysis Failed</h3>
            </div>
            <p class="text-sm text-danger-700 dark:text-danger-400">{{ $run->summary }}</p>
        </div>
    @endif
</x-filament-panels::page>
