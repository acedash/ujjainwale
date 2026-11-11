<x-app-layout>
    <div class="bg-paper border-b border-newsprint py-12 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <span class="text-amber-800 font-sans font-bold text-xs tracking-wider uppercase mb-2 inline-flex items-center gap-1.5 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">Category</span>
            <h1 class="font-display font-black text-5xl md:text-6xl text-ink">{{ $category->name }}</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Articles Column -->
            <div class="flex-grow">
                <div class="flex items-baseline justify-between mb-8 border-b border-newsprint pb-4">
                    <h2 class="font-display font-bold text-3xl text-ink">Latest News</h2>
                </div>

                @if($articles->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($articles as $article)
                            <x-article-card :article="$article" variant="standard" />
                        @endforeach
                    </div>
                    <div class="mt-12">
                        {{ $articles->links() }}
                    </div>
                @else
                    <p class="font-sans text-gray-500 italic">No news articles found in this category.</p>
                @endif
            </div>

            <!-- Businesses Sidebar -->
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="flex items-baseline justify-between mb-8 border-b border-newsprint pb-4">
                    <h2 class="font-display font-bold text-2xl text-ink">Local Businesses</h2>
                </div>

                @if($businesses->count())
                    <div class="space-y-6">
                        @foreach($businesses as $business)
                            <a href="{{ route('directory.show', $business) }}" class="block bg-white p-4 border border-newsprint rounded group hover:border-city-accent transition-colors shadow-sm">
                                <h4 class="font-display font-bold text-ink group-hover:text-city-accent mb-1">{{ $business->name }}</h4>
                                <p class="font-sans text-xs text-gray-500 mb-2">{{ $business->address }}</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($business->neighborhoods->take(2) as $hood)
                                        <span class="text-[10px] font-sans text-directory-blue bg-directory-blue/10 px-2 py-0.5 rounded">{{ $hood->name }}</span>
                                    @endforeach
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-6 text-center">
                        <a href="{{ route('directory', ['category' => $category->id]) }}" class="inline-block font-sans text-sm font-bold text-city-accent hover:underline">View all in Directory &rarr;</a>
                    </div>
                @else
                    <p class="font-sans text-gray-500 italic text-sm">No businesses listed in this category.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
