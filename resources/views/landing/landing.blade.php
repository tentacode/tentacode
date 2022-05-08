<x-layout>
    @include('landing.greeting-message')
    @include('landing.strengths')
    @include('landing.recently-blogged')
    @include('landing.portfolio')
    <x-landing.contact-form />
    @include('landing.footer')
</x-layout>
