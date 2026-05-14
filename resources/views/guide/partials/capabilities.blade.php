<section class="py-20 md:py-28 bg-gray-50 dark:bg-gray-950/60"
         x-data="{ activeTab: 'relationships' }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-12" id="caps-header">
            <span class="inline-block text-xs font-bold uppercase tracking-widest mb-4 px-3 py-1 rounded-full"
                  style="background: rgba(16,186,233,0.1); color: #10BAE9; border: 1px solid rgba(16,186,233,0.2);">
                Platform Capabilities
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-950 dark:text-white tracking-tight leading-[1.1]"
                style="font-family: 'Archivo Black', system-ui, sans-serif;">
                What you can do
            </h2>
            <p class="mt-4 text-gray-500 dark:text-gray-400 text-base sm:text-lg max-w-lg mx-auto leading-relaxed">
                Every feature explained. Click a tab to explore in depth.
            </p>
        </div>

        {{-- Tab bar --}}
        <div class="flex flex-wrap gap-2 justify-center mb-8 relative" id="caps-tabs">
            @php
            $tabs = [
                ['id' => 'relationships', 'label' => 'Relationships', 'icon' => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'],
                ['id' => 'pipeline',       'label' => 'Pipeline',       'icon' => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>'],
                ['id' => 'ai',             'label' => 'AI & MCP',        'icon' => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v3"/><path d="M15 13v4a2 2 0 002 2h4M15 17l4-4"/></svg>'],
                ['id' => 'collab',         'label' => 'Collaboration',  'icon' => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'],
            ];
            @endphp

            @foreach($tabs as $tab)
                <button type="button"
                        @click="activeTab = '{{ $tab['id'] }}'"
                        class="caps-tab inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 cursor-pointer"
                        :class="activeTab === '{{ $tab['id'] }}'
                            ? 'text-white shadow-md'
                            : 'bg-white dark:bg-white/[0.04] border border-gray-200 dark:border-white/[0.08] text-gray-600 dark:text-gray-400 hover:border-gray-300 dark:hover:border-white/[0.15]'"
                        :style="activeTab === '{{ $tab['id'] }}' ? 'background: linear-gradient(135deg, #6171F7, #10BAE9); border: 1px solid transparent;' : ''">
                    {!! $tab['icon'] !!}
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        {{-- Tab panels --}}
        @php
        $panels = [
            'relationships' => [
                'title' => 'Relationship Intelligence',
                'desc'  => 'Build a living map of every company, contact, and interaction your team touches.',
                'color' => '#6171F7',
                'items' => [
                    ['Companies', 'Full company profiles with industry, size, custom fields, and linked contacts. Bulk import from CSV in seconds.'],
                    ['People', 'Individual contact records linked to companies. Track roles, emails, phone numbers, and relationship history.'],
                    ['Activity Timeline', 'Automatic log of every note, change, email, task, and AI interaction — attached to every record.'],
                    ['Notes', 'Rich-text notes on any record. Mention colleagues, attach files, and pin important context.'],
                    ['Custom Fields', '22 field types with conditional visibility, per-field encryption, and relationship linking. No migrations needed.'],
                    ['CSV Import / Export', 'Bulk import companies or people from any spreadsheet. Export filtered data back to CSV at any time.'],
                ],
            ],
            'pipeline' => [
                'title' => 'Sales Pipeline',
                'desc'  => 'Visual deal tracking from first contact to closed — with full forecast visibility.',
                'color' => '#10BAE9',
                'items' => [
                    ['Kanban Board', 'Drag-and-drop opportunities across customisable stages. See all deals at a glance.'],
                    ['Opportunity Records', 'Each deal has a value, close date, linked company/person, stage, owner, and activity log.'],
                    ['Pipeline Stages', 'Create any stages you need. Reorder them. Rename them. The CRM adapts to your process.'],
                    ['Filtering & Sorting', 'Filter by stage, owner, value range, close date, or any custom field. Sort by any column.'],
                    ['Task Management', 'Attach tasks to opportunities. Assign to teammates. Track completion across your pipeline.'],
                    ['AI Summaries', 'One-click AI-generated narrative summary of any opportunity\'s status, risk, and next steps.'],
                ],
            ],
            'ai' => [
                'title' => 'AI Agent Integration',
                'desc'  => 'Give any AI model read/write access to your CRM data through 30 MCP tools.',
                'color' => '#7c3aed',
                'items' => [
                    ['30 MCP Tools', 'Complete CRUD coverage: list, get, create, update, delete across all entities. Plus search, schema-discovery, and bulk ops.'],
                    ['Schema Discovery', 'Agents can call describe-schema to learn your custom field definitions — no hardcoding required.'],
                    ['Claude Integration', 'Optimised for Claude Desktop and Claude API. Works with any MCP-compatible client.'],
                    ['AI Summaries', 'Request AI-generated risk assessments, relationship health scores, and narrative explanations.'],
                    ['Bulk Operations', 'Single MCP call to update multiple records, run searches, or generate summaries in batch.'],
                    ['Token Auth', 'Each API token is scoped to a team. Revoke instantly from the Settings panel.'],
                ],
            ],
            'collab' => [
                'title' => 'Team Collaboration',
                'desc'  => 'Multi-team workspaces with strict authorization, shared context, and real-time notifications.',
                'color' => '#f59e0b',
                'items' => [
                    ['Multi-Team Workspaces', 'Each team sees only its own data. Invite members, manage roles, and keep workspaces independent.'],
                    ['5-Layer Authorization', 'Ownership → Team membership → Role → Policy → Record-level checks. Nothing leaks between teams.'],
                    ['Database Notifications', 'Real-time notifications for mentions, assignments, and record changes — delivered in the UI.'],
                    ['Shared Notes', 'Notes on any record are visible to all team members. Pin critical context for the whole team.'],
                    ['Task Assignment', 'Assign tasks to any team member. They\'re notified instantly and the task appears in their queue.'],
                    ['Audit Log', 'Every write operation is logged with user, timestamp, and changed fields — full compliance trail.'],
                ],
            ],
        ];
        @endphp

        @foreach($panels as $tabId => $panel)
            <div x-show="activeTab === '{{ $tabId }}'"
                 x-transition:enter="transition-all duration-300 ease-out"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition-all duration-150 ease-in"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="caps-panel rounded-2xl bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/[0.07] overflow-hidden">

                {{-- Panel header --}}
                <div class="px-6 py-5 border-b border-gray-100 dark:border-white/[0.06]"
                     style="background: linear-gradient(to right, {{ $panel['color'] }}08, transparent);">
                    <h3 class="font-black text-lg text-gray-950 dark:text-white"
                        style="font-family: 'Archivo Black', system-ui, sans-serif;">{{ $panel['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $panel['desc'] }}</p>
                </div>

                {{-- Feature grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-0 divide-y sm:divide-y-0 divide-gray-100 dark:divide-white/[0.05]">
                    @foreach($panel['items'] as $feat)
                        <div class="p-5 flex items-start gap-3 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors duration-200
                                    {{ $loop->index > 0 && $loop->index % 2 === 0 ? 'sm:border-t sm:border-gray-100 sm:dark:border-white/[0.05]' : '' }}
                                    {{ $loop->index > 0 && $loop->index % 3 === 0 ? 'lg:border-t lg:border-gray-100 lg:dark:border-white/[0.05]' : '' }}">
                            <div class="w-1.5 h-1.5 rounded-full mt-2 flex-shrink-0"
                                 style="background: {{ $panel['color'] }};"></div>
                            <div>
                                <div class="text-sm font-bold text-gray-950 dark:text-white">{{ $feat[0] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">{{ $feat[1] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

<script>
    (function () {
        if (typeof inView === 'undefined' || typeof animate === 'undefined') return;
        inView('#caps-header', function (el) {
            animate(el, { opacity: [0, 1], y: [28, 0] }, { duration: 0.7, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.4 });
        inView('#caps-tabs', function (el) {
            var tabs = el.querySelectorAll('.caps-tab');
            animate(tabs, { opacity: [0, 1], y: [16, 0] }, { delay: function(_, i) { return i * 0.07; }, duration: 0.4, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.5 });
    }());
</script>
