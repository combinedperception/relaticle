<section class="relative overflow-hidden" style="background: var(--cp-navy);">

    {{-- Background glow --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[700px] h-[400px] rounded-full opacity-20"
             style="background: radial-gradient(ellipse at center, #6171F7 0%, transparent 65%); filter: blur(80px);"></div>
    </div>

    {{-- Grid overlay --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.04]"
         style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 3rem 3rem; mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black 0%, transparent 100%);"
         aria-hidden="true"></div>

    <div class="relative max-w-4xl mx-auto py-24 md:py-32 px-4 sm:px-6 lg:px-8 text-center" id="cta-section">

        {{-- Tag --}}
        <div class="flex justify-center mb-6">
            <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold"
                  style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.7);">
                <span class="w-1.5 h-1.5 rounded-full" style="background: #10BAE9;"></span>
                Currently in Private Pre-Alpha
            </span>
        </div>

        {{-- Heading --}}
        <h2 class="font-display text-3xl sm:text-4xl md:text-[2.75rem] font-black leading-[1.05] tracking-[-0.03em] text-balance"
            style="color: white;">
            Ready to work smarter<br class="hidden sm:block"/>
            <span style="background: linear-gradient(120deg, #6171F7 0%, #10BAE9 60%, #e0f2fe 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                with your relationships?
            </span>
        </h2>

        {{-- Subtext --}}
        <p class="mt-6 text-base sm:text-lg leading-relaxed max-w-xl mx-auto"
           style="color: rgba(255,255,255,0.55);">
            Combined Perception CRM is in active development and being rolled out to select enterprise teams. Request access to join the early cohort.
        </p>

        {{-- CTA Buttons --}}
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('contact') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl text-sm font-bold text-white transition-all duration-200"
               style="background: linear-gradient(135deg, #6171F7 0%, #10BAE9 100%); box-shadow: 0 0 32px rgba(97,113,247,0.5);">
                Request Early Access
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" stroke="currentColor" stroke-width="2">
                    <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <a href="{{ route('login') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-xl text-sm font-bold transition-all duration-200"
               style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.18); color: rgba(255,255,255,0.8);">
                Sign In
            </a>
        </div>

        {{-- Fine print --}}
        <p class="mt-8 text-xs" style="color: rgba(255,255,255,0.3);">
            This platform is in pre-alpha. Features and interfaces are subject to change.
        </p>
    </div>
</section>

<script>
    (function () {
        if (typeof inView === 'undefined' || typeof animate === 'undefined') return;
        inView('#cta-section', function (el) {
            animate(el, { opacity: [0, 1], y: [24, 0] }, { duration: 0.8, easing: [0.16, 1, 0.3, 1] });
        }, { amount: 0.3 });
    }());
</script>
