<section class="relative pt-32 pb-0 md:pt-44 overflow-hidden"
         style="background: var(--cp-navy);">

    {{-- Radial glow spots --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[600px] rounded-full opacity-30"
             style="background: radial-gradient(ellipse at center, #6171F7 0%, transparent 65%); filter: blur(80px);"></div>
        <div class="absolute top-1/3 right-[-10%] w-[500px] h-[400px] rounded-full opacity-20"
             style="background: radial-gradient(ellipse at center, #10BAE9 0%, transparent 65%); filter: blur(100px);"></div>
    </div>

    {{-- Subtle dot-grid overlay --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.045]"
         style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 2.5rem 2.5rem; mask-image: radial-gradient(ellipse 80% 70% at 50% 40%, black 20%, transparent 100%);"
         aria-hidden="true"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">

        {{-- Pre-alpha badge --}}
        <div class="hero-enter hero-enter-1 flex justify-center mb-8">
            <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold"
                  style="background: rgba(97,113,247,0.15); border: 1px solid rgba(97,113,247,0.4); color: #a5b4fc;">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background: #10BAE9;"></span>
                Private Pre-Alpha &nbsp;·&nbsp; Combined Perception
            </span>
        </div>

        {{-- Headline --}}
        <h1 class="hero-enter hero-enter-2 font-display leading-[1.05] tracking-[-0.04em] text-balance">
            <span class="block text-[2.25rem] sm:text-5xl md:text-[3.5rem] lg:text-[4rem] font-black text-white">
                The CRM Built for
            </span>
            <span class="block text-[2.25rem] sm:text-5xl md:text-[3.5rem] lg:text-[4rem] font-black mt-2"
                  style="background: linear-gradient(120deg, #6171F7 0%, #10BAE9 55%, #e0f2fe 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                AI&#8209;Augmented Teams
            </span>
        </h1>

        {{-- Tagline --}}
        <p class="hero-enter hero-enter-3 mt-7 text-[16px] sm:text-lg leading-relaxed max-w-2xl mx-auto"
           style="color: rgba(255,255,255,0.58);">
            Combined Perception CRM gives your team a unified view of companies, contacts, and pipelines — and lets AI agents read, write, and reason over your CRM data in real time via 30 MCP tools.
        </p>

        {{-- CTAs --}}
        <div class="hero-enter hero-enter-4 flex flex-col sm:flex-row items-center justify-center gap-3 mt-10">
            <a href="{{ route('login') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3 rounded-xl text-sm font-bold transition-all duration-200 text-white"
               style="background: linear-gradient(135deg, #6171F7 0%, #10BAE9 100%); box-shadow: 0 0 28px rgba(97,113,247,0.45);">
                Sign In
            </a>
            <a href="{{ route('contact') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3 rounded-xl text-sm font-bold transition-all duration-200"
               style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.18); color: rgba(255,255,255,0.85);">
                Request Access
            </a>
        </div>

        {{-- Stats row --}}
        <div class="hero-enter hero-enter-5 mt-16 flex flex-wrap justify-center gap-10 sm:gap-16 pb-16">
            @foreach([
                ['30', 'MCP Tools'],
                ['22', 'Custom Field Types'],
                ['1,100+', 'Automated Tests'],
                ['5-Layer', 'Authorization'],
            ] as $stat)
                <div class="text-center">
                    <div class="text-[1.75rem] sm:text-3xl font-black text-white tracking-tight"
                         style="font-family: 'Archivo Black', system-ui, sans-serif;">{{ $stat[0] }}</div>
                    <div class="text-xs mt-1.5 font-medium tracking-wide uppercase"
                         style="color: rgba(255,255,255,0.35);">{{ $stat[1] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Fade to next section --}}
    <div class="absolute bottom-0 left-0 right-0 h-28 pointer-events-none"
         style="background: linear-gradient(to bottom, transparent 0%, var(--cp-navy) 100%);" aria-hidden="true"></div>
</section>

{{-- Gradient bridge: navy → page background --}}
<div class="h-20" style="background: linear-gradient(to bottom, var(--cp-navy) 0%, white 100%);" aria-hidden="true"></div>
<div class="h-20 dark:block hidden -mt-20" style="background: linear-gradient(to bottom, var(--cp-navy) 0%, #030712 100%);" aria-hidden="true"></div>
