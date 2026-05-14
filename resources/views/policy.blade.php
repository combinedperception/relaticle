<x-guest-layout 
    :title="'Privacy Policy - ' . config('app.name')"
    description="Combined Perception CRM Privacy Policy — How we collect, use, and protect your personal information."
    :ogTitle="'Privacy Policy - ' . config('app.name')"
    ogDescription="Read the Privacy Policy for Combined Perception CRM. Learn about how we handle your personal information and protect your privacy.">
    <x-legal-document
        title="Privacy Policy"
        subtitle="How we collect, use, and protect your personal information."
        :content="$policy"
    />
</x-guest-layout>
