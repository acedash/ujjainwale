<x-app-layout title="Trending News">
    <div class="bg-paper border-b border-newsprint py-12 md:py-16 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <span class="text-amber-800 font-sans font-bold text-xs tracking-widest uppercase mb-3 inline-flex items-center gap-1.5 bg-amber-50 px-3.5 py-1 rounded-full border border-amber-200">
                Live News Aggregator &middot; ताज़ा सुर्खियां
            </span>
            <h1 class="font-display font-black text-4xl md:text-6xl text-ink tracking-tight mb-4">Trending News</h1>
            <p class="font-sans text-gray-500 max-w-xl mx-auto text-base md:text-lg">
                Real-time news and verified local updates across Ujjain, Malwa, and surrounding regions.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-10 py-10 md:py-16">
        <!-- Category Filter Pills -->
        @if(isset($categories) && $categories->isNotEmpty())
            <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 border-b border-gray-200 scrollbar-none">
                <a href="{{ route('external.index') }}" 
                   class="px-4 py-2 rounded-full font-sans text-xs uppercase tracking-wider font-bold transition-colors whitespace-nowrap {{ !request('category') ? 'bg-ink text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All News
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('external.index', ['category' => $category->slug]) }}" 
                       class="px-4 py-2 rounded-full font-sans text-xs uppercase tracking-wider font-bold transition-colors whitespace-nowrap {{ request('category') === $category->slug ? 'bg-city-accent text-midnight' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $category->name }}
                        @if($category->slug === 'ujjain-news')
                            <span class="ml-1 text-[9px] bg-red-600 text-white font-bold px-1.5 py-0.2 rounded uppercase">Ujjain</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif

        @if($headlines->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($headlines as $headline)
                    <div class="bg-white p-6 border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between group">
                        <div>
                            @if($headline->thumbnail_url)
                                <div class="h-48 -mx-6 -mt-6 mb-5 overflow-hidden rounded-t-2xl bg-gray-100">
                                    <img src="{{ $headline->thumbnail_url }}" 
                                         alt="{{ $headline->title }}"
                                         referrerpolicy="no-referrer"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='{{ asset('images/ujjain-wale.jpeg') }}';"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @endif

                            <div class="flex items-center gap-2 mb-2.5 flex-wrap">
                                <span class="text-[10px] font-sans font-black text-blue-900 bg-blue-50 px-2 py-0.5 rounded border border-blue-200 uppercase tracking-wider">
                                    {{ $headline->newsSource->name ?? 'Live' }}
                                </span>
                                @if($headline->categories->isNotEmpty())
                                    @foreach($headline->categories->take(2) as $hCat)
                                        <span class="text-[10px] font-sans font-bold text-amber-900 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                            {{ $hCat->name }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>

                            <h3 class="font-display font-bold text-xl text-ink leading-snug mb-3 group-hover:text-amber-800 transition-colors">
                                <a href="{{ route('external.show', $headline->id) }}">
                                    {{ $headline->title }}
                                </a>
                            </h3>
                            @if($headline->original_snippet)
                                <p class="font-sans text-sm text-gray-500 mb-4 line-clamp-3 leading-relaxed">
                                    {{ $headline->original_snippet }}
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 text-xs font-sans text-gray-400">
                            <span>
                                {{ $headline->published_at ? $headline->published_at->diffForHumans() : $headline->fetched_at?->diffForHumans() }}
                            </span>
                            <a href="{{ route('external.show', $headline->id) }}" class="font-bold text-amber-800 hover:text-amber-900">
                                Read Story &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-12">
                {{ $headlines->links() }}
            </div>
        @else
            <div class="text-center py-24">
                <p class="text-xl text-gray-500">No headlines found in this category.</p>
                @if(request('category'))
                    <a href="{{ route('external.index') }}" class="inline-block mt-4 text-sm font-bold text-amber-800 hover:underline">
                        &larr; View All News
                    </a>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
