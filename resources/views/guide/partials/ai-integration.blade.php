<section class="py-20 md:py-28" style="background: var(--cp-navy);">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16" id="ai-header">
            <span class="inline-block text-xs font-bold uppercase tracking-widest mb-4 px-3 py-1 rounded-full"
                  style="background: rgba(124,58,237,0.15); color: #a78bfa; border: 1px solid rgba(124,58,237,0.3);">
                AI Integration
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-[1.1]"
                style="font-family: 'Archivo Black', system-ui, sans-serif;">
                30 MCP Tools. One Connection.
            </h2>
            <p class="mt-4 text-base sm:text-lg max-w-xl mx-auto leading-relaxed"
               style="color: rgba(255,255,255,0.5);">
                Give any AI model complete read/write access to your CRM data. No custom integrations. No middleware. Just point and go.
            </p>
        </div>

        {{-- Connection diagram --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-0 mb-16" id="ai-diagram">
            @php
            $nodes = [
                ['label' => 'Claude / GPT', 'sub' => 'AI Agent', 'color' => '#6171F7', 'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v3"/><path d="M15 13v4a2 2 0 002 2h4M15 17l4-4"/></svg>'],
                ['label' => 'MCP Client',   'sub' => 'Transport',  'color' => '#10BAE9', 'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'],
                ['label' => 'CP CRM',       'sub' => 'MCP Server', 'color' => '#6171F7', 'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>'],
                ['label' => 'PostgreSQL',   'sub' => 'Data Layer', 'color' => '#10BAE9', 'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>'],
            ];
            @endphp

            @foreach($nodes as $ni => $node)
                <div class="ai-node flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-3 transition-all duration-300"
                         style="background: linear-gradient(135deg, {{ $node['color'] }}22, {{ $node['color'] }}11); border: 1px solid {{ $node['color'] }}44;">
                        <div style="color: {{ $node['color'] }};">{!! $node['icon'] !!}</div>
                    </div>
                    <div class="text-sm font-bold text-white" style="font-family: 'Archivo Black', system-ui, sans-serif;">{{ $node['label'] }}</div>
                    <div class="text-xs mt-0.5" style="color: rgba(255,255,255,0.4);">{{ $node['sub'] }}</div>
                </div>

                @if(!$loop->last)
                    {{-- Arrow --}}
                    <div class="flex items-center justify-center sm:px-4 rotate-90 sm:rotate-0">
                        <svg width="48" height="16" viewBox="0 0 48 16" fill="none">
                            <line x1="0" y1="8" x2="36" y2="8" stroke="url(#arrowGrad{{ $ni }})" stroke-width="1.5" stroke-dasharray="4 3"/>
                            <polygon points="36,4 48,8 36,12" fill="url(#arrowGrad{{ $ni }})"/>
                            <defs>
                                <linearGradient id="arrowGrad{{ $ni }}" x1="0" y1="0" x2="48" y2="0" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#6171F7" stop-opacity="0.5"/>
                                    <stop offset="100%" stop-color="#10BAE9" stop-opacity="0.8"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Tool categories + terminal --}}
        <div class="grid lg:grid-cols-2 gap-8 items-start">

            {{-- Left: tool category cards --}}
            <div class="space-y-4" id="ai-cards">
                @php
                $toolCategories = [
                    [
                        'name'  => 'Read Tools',
                        'count' => '12 tools',
                        'color' => '#10BAE9',
                        'desc'  => 'List, get, search, and filter across all entities. Agents can discover your schema before querying.',
                        'tools' => ['list-companies', 'get-person', 'search-records', 'describe-schema', 'list-opportunities', 'get-activity-log'],
                        'icon'  => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>',
                    ],
                    [
                        'name'  => 'Write Tools',
                        'count' => '13 tools',
                        'color' => '#6171F7',
                        'desc'  => 'Full CRUD: create, update, delete, and bulk-modify companies, people, opportunities, notes, and tasks.',
                        'tools' => ['create-company', 'update-record', 'delete-record', 'create-note', 'create-task', 'bulk-update'],
                        'icon'  => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
                    ],
                    [
                        'name'  => 'AI Tools',
                        'count' => '5 tools',
                        'color' => '#f59e0b',
                        'desc'  => 'Request AI-generated summaries, risk assessments, relationship health scores, and deal narratives.',
                        'tools' => ['get-ai-summary', 'assess-deal-risk', 'relationship-health', 'generate-narrative', 'bulk-summarise'],
                        'icon'  => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                    ],
                ];
                @endphp

                @foreach($toolCategories as $cat)
                    <div class="rounded-2xl p-5 border transition-all duration-200 hover:border-opacity-60"
                         style="background: rgba(255,255,255,0.03); border-color: {{ $cat['color'] }}33;">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background: {{ $cat['color'] }}1a; color: {{ $cat['color'] }};">
                                {!! $cat['icon'] !!}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-bold text-white" style="font-family: 'Archivo Black', system-ui, sans-serif;">{{ $cat['name'] }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                          style="background: {{ $cat['color'] }}1a; color: {{ $cat['color'] }}; border: 1px solid {{ $cat['color'] }}33;">{{ $cat['count'] }}</span>
                                </div>
                                <p class="text-xs leading-relaxed mb-3" style="color: rgba(255,255,255,0.45);">{{ $cat['desc'] }}</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($cat['tools'] as $tool)
                                        <code class="text-[10px] px-2 py-0.5 rounded font-mono"
                                              style="background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.07);">{{ $tool }}</code>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Right: conversation terminal --}}
            <div id="ai-terminal"
                 x-data="aiConversation()"
                 x-init="start()">
                <div class="rounded-2xl overflow-hidden"
                     style="background: #0d1117; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 32px 64px rgba(0,0,0,0.5);">

                    {{-- Chrome --}}
                    <div class="flex items-center gap-2 px-4 py-3 border-b" style="background: #161b22; border-color: rgba(255,255,255,0.08);">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full" style="background: #ff5f57;"></div>
                            <div class="w-3 h-3 rounded-full" style="background: #febc2e;"></div>
                            <div class="w-3 h-3 rounded-full" style="background: #28c840;"></div>
                        </div>
                        <span class="flex-1 text-center text-[11px]" style="color: rgba(255,255,255,0.3); font-family: monospace;">
                            Claude Desktop — MCP Session
                        </span>
                    </div>

                    {{-- Messages --}}
                    <div class="p-5 font-mono text-[12px] leading-relaxed min-h-[320px] space-y-4">
                        <template x-for="(msg, i) in visibleMessages" :key="i">
                            <div>
                                {{-- User message --}}
                                <template x-if="msg.role === 'user'">
                                    <div class="flex items-start gap-3">
                                        <div class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0 mt-0.5 text-[9px] font-bold"
                                             style="background: #6171F7; color: white; font-family: 'Archivo Black', system-ui;">U</div>
                                        <span style="color: rgba(255,255,255,0.75);" x-text="msg.text"></span>
                                    </div>
                                </template>

                                {{-- Tool call --}}
                                <template x-if="msg.role === 'tool'">
                                    <div class="flex items-start gap-3 pl-8">
                                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-[11px]"
                                             style="background: rgba(97,113,247,0.12); border: 1px solid rgba(97,113,247,0.25); color: #a5b4fc; font-family: monospace;">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span x-text="msg.text"></span>
                                        </div>
                                    </div>
                                </template>

                                {{-- Assistant message --}}
                                <template x-if="msg.role === 'assistant'">
                                    <div class="flex items-start gap-3">
                                        <div class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0 mt-0.5 text-[9px] font-bold"
                                             style="background: linear-gradient(135deg, #6171F7, #10BAE9); color: white; font-family: 'Archivo Black', system-ui;">A</div>
                                        <span style="color: rgba(255,255,255,0.65);" x-text="msg.text"></span>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Typing indicator --}}
                        <div x-show="isTyping" class="flex items-center gap-3 pl-8">
                            <div class="flex gap-1">
                                <div class="w-1.5 h-1.5 rounded-full animate-bounce" style="background: #6171F7; animation-delay: 0ms;"></div>
                                <div class="w-1.5 h-1.5 rounded-full animate-bounce" style="background: #10BAE9; animation-delay: 150ms;"></div>
                                <div class="w-1.5 h-1.5 rounded-full animate-bounce" style="background: #6171F7; animation-delay: 300ms;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Status bar --}}
                    <div class="px-5 py-3 border-t flex items-center gap-3" style="background: rgba(97,113,247,0.04); border-color: rgba(255,255,255,0.06);">
                        <div class="w-1.5 h-1.5 rounded-full animate-pulse" style="background: #28c840;"></div>
                        <span class="text-[10px]" style="color: rgba(255,255,255,0.3); font-family: monospace;">MCP connected · 30 tools loaded</span>
                        <span class="ml-auto text-[10px]" style="color: rgba(255,255,255,0.2); font-family: monospace;" x-text="'session #' + sessionId"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Connection snippet --}}
        <div class="mt-12 rounded-2xl overflow-hidden border" id="ai-snippet"
             style="background: #0d1117; border-color: rgba(255,255,255,0.08);">
            <div class="flex items-center gap-3 px-5 py-3 border-b" style="background: #161b22; border-color: rgba(255,255,255,0.06);">
                <div class="w-2 h-2 rounded-full" style="background: #f59e0b;"></div>
                <span class="text-[11px] font-mono" style="color: rgba(255,255,255,0.4);">claude_desktop_config.json</span>
                <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded"
                      style="background: rgba(40,200,64,0.1); color: #28c840; border: 1px solid rgba(40,200,64,0.2);">Ready to paste</span>
            </div>
            <div class="p-5 font-mono text-[12px] leading-loose overflow-x-auto">
<pre style="color: #e6edf3; margin: 0;"><span style="color: rgba(255,255,255,0.3);">{</span>
  <span style="color: #7ee787;">"mcpServers"</span><span style="color: rgba(255,255,255,0.3);">:</span> <span style="color: rgba(255,255,255,0.3);">{</span>
    <span style="color: #7ee787;">"combined-perception-crm"</span><span style="color: rgba(255,255,255,0.3);">:</span> <span style="color: rgba(255,255,255,0.3);">{</span>
      <span style="color: #7ee787;">"url"</span><span style="color: rgba(255,255,255,0.3);">:</span> <span style="color: #a5d6ff;">"https://crm.combinedperception.ai/mcp"</span><span style="color: rgba(255,255,255,0.3);">,</span>
      <span style="color: #7ee787;">"headers"</span><span style="color: rgba(255,255,255,0.3);">:</span> <span style="color: rgba(255,255,255,0.3);">{</span>
        <span style="color: #7ee787;">"Authorization"</span><span style="color: rgba(255,255,255,0.3);">:</span> <span style="color: #a5d6ff;">"Bearer &lt;your-api-token&gt;"</span>
      <span style="color: rgba(255,255,255,0.3);">}</span>
    <span style="color: rgba(255,255,255,0.3);">}</span>
  <span style="color: rgba(255,255,255,0.3);">}</span>
<span style="color: rgba(255,255,255,0.3);">}</span></pre>
            </div>
        </div>

    </div>
</section>

<script>
function aiConversation() {
    var conversations = [
        [
            { role: 'user',      text: 'How many open opportunities do we have over $50k?', delay: 600 },
            { role: 'tool',      text: 'list-opportunities → filter: value > 50000, stage != closed', delay: 900 },
            { role: 'assistant', text: 'You have 7 open opportunities valued above $50k, totalling $612,000 combined pipeline.', delay: 1200 },
        ],
        [
            { role: 'user',      text: 'Summarise the relationship health for Acme Corp.', delay: 600 },
            { role: 'tool',      text: 'get-ai-summary → company_id: 1, include: health, risk, last_contact', delay: 900 },
            { role: 'assistant', text: 'Acme Corp: Relationship Health 82% ██████████. Low risk. Last contact 3 days ago. Renewal due Q2 — recommend scheduling a check-in call.', delay: 1200 },
        ],
        [
            { role: 'user',      text: 'Create a follow-up task for the QuantumEdge deal.', delay: 600 },
            { role: 'tool',      text: 'create-task → opportunity: "QuantumEdge Expansion", due: tomorrow', delay: 900 },
            { role: 'assistant', text: 'Task created: "Follow up on QuantumEdge Expansion" — due tomorrow, assigned to you. Added to the opportunity activity log.', delay: 1200 },
        ],
    ];

    return {
        visibleMessages: [],
        isTyping: false,
        sessionId: 1,
        currentConv: 0,

        start() {
            var self = this;
            setTimeout(function() { self.playConversation(); }, 800);
        },

        playConversation() {
            var self = this;
            var conv = conversations[self.currentConv];
            self.visibleMessages = [];
            var i = 0;

            function showNext() {
                if (i >= conv.length) {
                    setTimeout(function() {
                        self.currentConv = (self.currentConv + 1) % conversations.length;
                        self.sessionId++;
                        self.playConversation();
                    }, 3200);
                    return;
                }
                var msg = conv[i];
                i++;
                self.isTyping = (msg.role === 'assistant');
                setTimeout(function() {
                    self.isTyping = false;
                    self.visibleMessages.push(msg);
                    setTimeout(showNext, msg.delay);
                }, msg.role === 'assistant' ? 900 : 0);
            }

            showNext();
        }
    };
}
</script>

<script>
    (function () {
        if (typeof inView === 'undefined' || typeof animate === 'undefined') return;
        inView('#ai-header', function (el) {
            animate(el, { opacity: [0, 1], y: [28, 0] }, { duration: 0.7, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.4 });
        inView('#ai-diagram', function (el) {
            var nodes = el.querySelectorAll('.ai-node');
            animate(nodes, { opacity: [0, 1], scale: [0.85, 1] }, { delay: function(_, i) { return i * 0.1; }, duration: 0.5, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.4 });
        inView('#ai-cards', function (el) {
            animate(el.children, { opacity: [0, 1], x: [-24, 0] }, { delay: function(_, i) { return i * 0.1; }, duration: 0.5, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.2 });
        inView('#ai-terminal', function (el) {
            animate(el, { opacity: [0, 1], x: [24, 0] }, { duration: 0.6, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.2 });
        inView('#ai-snippet', function (el) {
            animate(el, { opacity: [0, 1], y: [16, 0] }, { duration: 0.5, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.3 });
    }());
</script>
