<x-guest-layout
    title="Combined Perception CRM — AI-Native Relationship Management"
    description="Combined Perception CRM is an AI-native relationship management platform for enterprise teams. Manage companies, people, pipelines, and let AI agents do the heavy lifting."
    ogTitle="Combined Perception CRM — AI-Native Relationship Management"
    ogDescription="Purpose-built CRM for enterprise teams who move with AI. 30 MCP tools, custom data model, team collaboration, and full pipeline management."
    :ogImage="url('/images/open-graph.jpg')">

    @push('header')
        @vite('resources/js/motion.js')
    @endpush

    @include('home.partials.hero')
    @include('home.partials.features')
    @include('home.partials.community')
    @include('home.partials.faq')
    @include('home.partials.start-building')

    @php
        $schema = (new \Spatie\SchemaOrg\Graph())
            ->softwareApplication(fn ($app) => $app
                ->name('Combined Perception CRM')
                ->applicationCategory('BusinessApplication')
                ->applicationSubCategory('CRM')
                ->operatingSystem('Linux, macOS, Windows')
                ->description('An AI-native CRM platform for enterprise teams. Manage relationships, pipelines, and let AI agents operate your CRM data via 30 MCP tools.')
                ->url(url('/'))
            )
            ->organization(fn ($org) => $org
                ->name('Combined Perception')
                ->url(url('/'))
                ->logo(asset('favicon.svg'))
                ->sameAs(['https://github.com/combinedperception'])
            )
            ->website(fn ($site) => $site
                ->name('Combined Perception CRM')
                ->url(url('/'))
            );
    @endphp

    {!! $schema->toScript() !!}
</x-guest-layout>
