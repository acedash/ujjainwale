<x-app-layout>
    <div class="bg-paper border-b border-newsprint py-12 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <span class="text-city-accent font-sans font-bold text-sm tracking-wider uppercase mb-2 block">Aggregated</span>
            <h1 class="font-display font-black text-5xl md:text-6xl text-ink">Trending</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12">
        @if($headlines->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($headlines as $headline)
                    <div class="bg-white p-6 border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                        @if($headline->thumbnail_url)
                            <div class="h-48 -mx-6 -mt-6 mb-5 overflow-hidden rounded-t-2xl">
                                <img src="{{ $headline->thumbnail_url }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <h3 class="font-display font-bold text-xl text-ink leading-snug mb-3">
                            <a href="{{ route('external.show', $headline->id) }}" class="hover:text-city-accent transition-colors">
                                {{ $headline->title }}
                            </a>
                        </h3>
                        <p class="font-sans text-sm text-gray-500 mb-4 line-clamp-3">
                            {{ $headline->original_snippet }}
                        </p>
                        <div class="flex items-center gap-2">
                            <span class="font-sans text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                {{ $headline->newsSource->name }}
                            </span>
                            <span class="font-sans text-xs text-gray-400">&bull;</span>
                            <span class="font-sans text-xs text-gray-400">
                                {{ $headline->published_at ? $headline->published_at->diffForHumans() : $headline->fetched_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-12">
                {{ $headlines->links() }}
            </div>
        @else
            <div class="text-center py-24">
                <p class="text-xl text-gray-500">No external headlines available.</p>
            </div>
        @endif
    </div>
</x-app-layout>
