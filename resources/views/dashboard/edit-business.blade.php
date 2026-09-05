<x-dashboard-layout>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="font-display font-bold text-2xl text-ink">Edit Business Details</h2>
        <a href="{{ route('dashboard.businesses') }}" class="font-sans text-sm font-bold text-gray-500 hover:text-ink">&larr; Back to My Businesses</a>
    </div>

    @livewire('edit-business', ['business' => $business])
</x-dashboard-layout>
