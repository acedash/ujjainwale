@props(['neighborhoods'])

<div class="w-full border-b border-gray-200 bg-gray-50 overflow-x-auto whitespace-nowrap scrollbar-hide">
    <div class="max-w-7xl mx-auto px-8 md:px-12 py-3 flex space-x-3 items-center">
        <a href="{{ route('directory') }}" class="inline-flex items-center bg-ink hover:bg-ink/80 text-white px-4 py-1.5 rounded-full text-[10px] font-sans font-bold uppercase tracking-wider transition-colors">
            All Neighborhoods
        </a>
        @foreach($neighborhoods as $neighborhood)
            <a href="{{ route('neighborhoods.show', $neighborhood) }}" class="flex-shrink-0 inline-flex items-center bg-white border border-gray-200 hover:border-amber-300 hover:bg-amber-50/60 text-gray-700 hover:text-amber-900 px-4 py-1.5 rounded-full text-xs font-sans font-medium transition-colors snap-start">
                {{ $neighborhood->name }}
            </a>
        @endforeach
    </div>
</div>