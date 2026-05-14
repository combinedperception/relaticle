<section class="relative pt-32 pb-0 md:pt-44 overflow-hidden" style="background: var(--cp-navy);">

    {{-- Background glows --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[500px] rounded-full opacity-25"
             style="background: radial-gradient(ellipse at center, #6171F7 0%, transparent 65%); filter: blur(80px);"></div>
        <div class="absolute top-1/4 right-0 w-[400px] h-[400px] rounded-full opacity-15"
             style="background: radial-gradient(ellipse at center, #10BAE9 0%, transparent 65%); filter: blur(100px);"></div>
    </div>

    {{-- Dot grid --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.04]"
         style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 2.5rem 2.5rem; mask-image: radial-gradient(ellipse 80% 60% at 50% 40%, black 20%, transparent 100%);"
         aria-hidden="true"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center pb-12 md:pb-20">

            {{-- Left: text --}}
            <div>
                {{-- Badge --}}
                <div class="hero-enter hero-enter-1 mb-6">
                    <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold"
                          style="background: rgba(97,113,247,0.15); border: 1px solid rgba(97,113,247,0.35); color: #a5b4fc;">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Platform Guide
                    </span>
                </div>

                <h1 class="hero-enter hero-enter-2 font-display leading-[1.05] tracking-[-0.04em]">
                    <span class="block text-3xl sm:text-[2.5rem] md:text-5xl font-black text-white">Your Platform.</span>
                    <span class="block text-3xl sm:text-[2.5rem] md:text-5xl font-black mt-1"
                          style="background: linear-gradient(120deg, #6171F7 0%, #10BAE9 55%, #e0f2fe 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        Your AI. Your Rules.
                    </span>
                </h1>

                <p class="hero-enter hero-enter-3 mt-6 text-[15px] sm:text-lg leading-relaxed"
                   style="color: rgba(255,255,255,0.58);">
                    Everything you need to get from zero to a fully operational AI-native CRM — step by step, with no guesswork.
                </p>

                <div class="hero-enter hero-enter-4 flex flex-col sm:flex-row gap-3 mt-8">
                    <a href="#steps"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white transition-all duration-200"
                       style="background: linear-gradient(135deg, #6171F7 0%, #10BAE9 100%); box-shadow: 0 0 24px rgba(97,113,247,0.4);">
                        Start Walkthrough
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                            <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="https://github.com/combinedperception/relaticle" target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-200"
                       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.18); color: rgba(255,255,255,0.8);">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        View on GitHub
                    </a>
                </div>
            </div>

            {{-- Right: Animated terminal --}}
            <div class="hero-enter hero-enter-5 relative"
                 x-data="guideTerminal()"
                 x-init="start()">

                {{-- Terminal glow --}}
                <div class="absolute -inset-4 rounded-2xl pointer-events-none opacity-30"
                     style="background: radial-gradient(ellipse at center, #6171F7 0%, transparent 70%); filter: blur(40px);" aria-hidden="true"></div>

                {{-- Terminal window --}}
                <div class="relative rounded-2xl overflow-hidden"
                     style="background: #0d1117; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 32px 64px rgba(0,0,0,0.5);">

                    {{-- Chrome bar --}}
                    <div class="flex items-center gap-2 px-4 py-3 border-b" style="background: #161b22; border-color: rgba(255,255,255,0.08);">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full" style="background: #ff5f57;"></div>
                            <div class="w-3 h-3 rounded-full" style="background: #febc2e;"></div>
                            <div class="w-3 h-3 rounded-full" style="background: #28c840;"></div>
                        </div>
                        <span class="flex-1 text-center text-[11px]" style="color: rgba(255,255,255,0.3); font-family: monospace;">
                            Combined Perception CRM — AI Agent Terminal
                        </span>
                    </div>

                    {{-- Terminal body --}}
                    <div class="p-5 font-mono text-[12px] leading-relaxed min-h-[260px]" style="color: #e6edf3;">
                        {{-- Prompt line --}}
                        <div class="flex items-center gap-2 mb-3">
                            <span style="color: #10BAE9;">●</span>
                            <span style="color: rgba(255,255,255,0.4);">mcp-client</span>
                            <span style="color: #6171F7;">~</span>
                            <span style="color: #e6edf3;">$</span>
                            <span style="color: #10BAE9;" x-text="displayCommand" class="ml-1"></span>
                            <span x-show="showCursor" class="w-2 h-4 inline-block animate-pulse" style="background: #10BAE9; vertical-align: middle;"></span>
                        </div>

                        {{-- Output lines --}}
                        <div x-show="outputLines.length > 0" class="space-y-1 pl-4 border-l-2" style="border-color: rgba(97,113,247,0.3);">
                            <template x-for="(line, i) in outputLines" :key="i">
                                <div x-text="line.text" :style="`color: ${line.color}; opacity: ${line.opacity}`" class="transition-opacity duration-300"></div>
                            </template>
                        </div>

                        {{-- Next prompt --}}
                        <div x-show="showNextPrompt" class="flex items-center gap-2 mt-3">
                            <span style="color: #10BAE9;">●</span>
                            <span style="color: rgba(255,255,255,0.4);">mcp-client</span>
                            <span style="color: #6171F7;">~</span>
                            <span style="color: #e6edf3;">$</span>
                            <span class="w-2 h-4 inline-block ml-1 animate-pulse" style="background: #10BAE9; vertical-align: middle;"></span>
                        </div>
                    </div>

                    {{-- Tool indicator strip --}}
                    <div class="px-5 py-3 flex items-center gap-3 border-t" style="background: rgba(97,113,247,0.05); border-color: rgba(255,255,255,0.06);">
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color: rgba(255,255,255,0.3);">Active Tool</span>
                        <span class="text-[11px] font-mono font-semibold px-2 py-0.5 rounded" style="background: rgba(97,113,247,0.15); color: #a5b4fc;" x-text="activeTool"></span>
                        <span class="ml-auto text-[10px]" style="color: rgba(255,255,255,0.25);">30 MCP tools available</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Fade to white --}}
    <div class="h-20" style="background: linear-gradient(to bottom, var(--cp-navy) 0%, white 100%);" aria-hidden="true"></div>
    <div class="h-20 dark:block hidden -mt-20" style="background: linear-gradient(to bottom, var(--cp-navy) 0%, #030712 100%);" aria-hidden="true"></div>
</section>

<script>
function guideTerminal() {
    var sessions = [
        {
            tool: 'list-companies',
            command: 'cp-crm list-companies --limit 5',
            output: [
                { text: '✓ Connected to Combined Perception CRM', color: '#28c840', opacity: '1' },
                { text: '─────────────────────────────────────', color: 'rgba(255,255,255,0.15)', opacity: '1' },
                { text: '  1. Acme Corp        [Active]   $240k ARR', color: '#e6edf3', opacity: '1' },
                { text: '  2. Vertex AI Labs   [Active]   $180k ARR', color: '#e6edf3', opacity: '1' },
                { text: '  3. Neural Systems   [At Risk]  $95k ARR',  color: '#f97316', opacity: '1' },
                { text: '  4. DeepTech Inc     [New]      —',         color: '#e6edf3', opacity: '1' },
                { text: '  5. QuantumEdge      [Active]   $320k ARR', color: '#e6edf3', opacity: '1' },
                { text: '',                                            color: '', opacity: '0' },
                { text: '  Showing 5 of 24 companies.', color: 'rgba(255,255,255,0.4)', opacity: '1' },
            ]
        },
        {
            tool: 'get-ai-summary',
            command: 'cp-crm get-ai-summary --company "Acme Corp"',
            output: [
                { text: '✓ Generating AI summary…', color: '#28c840', opacity: '1' },
                { text: '─────────────────────────────────────', color: 'rgba(255,255,255,0.15)', opacity: '1' },
                { text: '  Relationship Health:  ████████░░  82%', color: '#10BAE9', opacity: '1' },
                { text: '  Risk Level:          LOW',               color: '#28c840', opacity: '1' },
                { text: '  Last Contact:        3 days ago',         color: '#e6edf3', opacity: '1' },
                { text: '  Open Opportunities:  2  ($145k)',         color: '#a5b4fc', opacity: '1' },
                { text: '',                                           color: '', opacity: '0' },
                { text: '  "Strong relationship. Renewal due Q2."', color: 'rgba(255,255,255,0.55)', opacity: '1' },
            ]
        },
        {
            tool: 'create-opportunity',
            command: 'cp-crm create-opportunity --name "Q2 Expansion"',
            output: [
                { text: '✓ Opportunity created successfully', color: '#28c840', opacity: '1' },
                { text: '─────────────────────────────────────', color: 'rgba(255,255,255,0.15)', opacity: '1' },
                { text: '  ID:      OPP-2024-0089',              color: '#a5b4fc', opacity: '1' },
                { text: '  Name:    Q2 Expansion',               color: '#e6edf3', opacity: '1' },
                { text: '  Stage:   Discovery',                  color: '#10BAE9', opacity: '1' },
                { text: '  Value:   $0 (set via update-record)', color: 'rgba(255,255,255,0.4)', opacity: '1' },
                { text: '  Team:    Combined Perception',        color: '#e6edf3', opacity: '1' },
                { text: '',                                      color: '', opacity: '0' },
                { text: '  Ready. Use update-record to set value.', color: 'rgba(255,255,255,0.4)', opacity: '1' },
            ]
        },
    ];

    return {
        currentSession: 0,
        displayCommand: '',
        activeTool: sessions[0].tool,
        outputLines: [],
        showCursor: true,
        showNextPrompt: false,
        typingTimeout: null,

        start() {
            var self = this;
            setTimeout(function() { self.playSession(); }, 600);
        },

        playSession() {
            var self = this;
            var s = sessions[self.currentSession];
            self.displayCommand = '';
            self.outputLines = [];
            self.showNextPrompt = false;
            self.showCursor = true;
            self.activeTool = s.tool;

            // Type command
            var i = 0;
            var typeTimer = setInterval(function() {
                self.displayCommand += s.command[i];
                i++;
                if (i >= s.command.length) {
                    clearInterval(typeTimer);
                    self.showCursor = false;
                    setTimeout(function() { self.showOutput(s.output); }, 400);
                }
            }, 38);
        },

        showOutput(lines) {
            var self = this;
            var i = 0;
            var showTimer = setInterval(function() {
                if (i < lines.length) {
                    self.outputLines.push(lines[i]);
                    i++;
                } else {
                    clearInterval(showTimer);
                    setTimeout(function() {
                        self.showNextPrompt = true;
                        setTimeout(function() {
                            self.currentSession = (self.currentSession + 1) % sessions.length;
                            self.playSession();
                        }, 2400);
                    }, 600);
                }
            }, 110);
        }
    };
}
</script>
