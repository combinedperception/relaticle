<x-guest-layout
    title="Getting Started — Combined Perception CRM"
    description="Complete guide to the Combined Perception CRM platform. Step-by-step walkthrough, capability reference, AI agent integration, and how to contribute."
    ogTitle="Getting Started — Combined Perception CRM"
    ogDescription="Your complete guide to Combined Perception CRM — step-by-step onboarding, AI agent integration, and developer resources.">

    @push('header')
        @vite('resources/js/motion.js')
    @endpush

    @include('guide.partials.hero')
    @include('guide.partials.steps')
    @include('guide.partials.capabilities')
    @include('guide.partials.ai-integration')
    @include('guide.partials.bug-reporting')
    @include('guide.partials.resources')

</x-guest-layout>
