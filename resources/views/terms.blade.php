<x-guest-layout 
    :title="'Terms of Service - ' . config('app.name')"
    description="Combined Perception CRM Terms of Service — Use of the Combined Perception CRM platform is subject to the following terms."
    :ogTitle="'Terms of Service - ' . config('app.name')"
    ogDescription="Read the Terms of Service for Combined Perception CRM. Learn about the rules and guidelines for using the platform.">
    <x-legal-document
        title="Terms of Service"
        subtitle="Use of the Combined Perception CRM platform is subject to the following terms of service."
        :content="$terms"
    />
</x-guest-layout>
