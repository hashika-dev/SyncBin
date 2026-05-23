<x-app-layout>
    @slot('hideNav')
        true
    @endslot

    <div class="py-0">
        @include('dashboards.admin')
    </div>
</x-app-layout>