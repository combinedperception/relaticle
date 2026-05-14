<footer class="py-12 md:py-16 border-t border-gray-100 dark:border-gray-900 bg-white dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-8 pb-10 border-b border-gray-100 dark:border-gray-900">

            {{-- Company Info --}}
            <div class="md:col-span-5 space-y-5">
                <a href="{{ url('/') }}" class="inline-flex w-fit" aria-label="Combined Perception CRM Home">
                    <x-brand.logo-lockup size="md" class="text-black dark:text-white" />
                </a>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed max-w-md">
                    AI-native CRM for enterprise teams. Manage relationships and let AI agents operate your data in real time.
                </p>

                <div class="flex space-x-4">
                    <a href="https://github.com/combinedperception" target="_blank" rel="noopener noreferrer"
                       class="text-gray-400 hover:text-primary dark:hover:text-primary-400 transition-colors"
                       aria-label="GitHub">
                        <x-ri-github-fill class="h-5 w-5" />
                    </a>
                    <a href="https://linkedin.com/company/combined-perception" target="_blank" rel="noopener noreferrer"
                       class="text-gray-400 hover:text-primary dark:hover:text-primary-400 transition-colors"
                       aria-label="LinkedIn">
                        <x-ri-linkedin-box-fill class="h-5 w-5" />
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="md:col-span-3">
                <h3 class="font-medium text-xs text-black dark:text-white uppercase tracking-wider mb-4">
                    Quick Links
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/') }}"
                           class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-400 text-sm transition-colors">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#features') }}"
                           class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-400 text-sm transition-colors">
                            Capabilities
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#architecture') }}"
                           class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-400 text-sm transition-colors">
                            Architecture
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Legal --}}
            <div class="md:col-span-4">
                <h3 class="font-medium text-xs text-black dark:text-white uppercase tracking-wider mb-4">
                    Support & Legal
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('privacy-policy') }}"
                           class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-400 text-sm transition-colors">
                            Privacy Policy
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('terms-of-service') }}"
                           class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-400 text-sm transition-colors">
                            Terms of Service
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}"
                           class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary-400 text-sm transition-colors">
                            Contact Us
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-8 flex flex-col md:flex-row md:justify-between items-start md:items-center gap-3">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">&copy; {{ date('Y') }} Combined Perception. All rights reserved.</p>
                <p class="text-gray-400 dark:text-gray-600 text-[11px] mt-1">
                    Built on the foundations of
                    <a href="https://github.com/relaticle/relaticle" target="_blank" rel="noopener"
                       class="underline underline-offset-2 hover:text-gray-500 dark:hover:text-gray-400 transition-colors">
                        Relaticle
                    </a>
                    open-source (AGPL-3.0).
                </p>
            </div>
            <x-theme-switcher />
        </div>
    </div>
</footer>
