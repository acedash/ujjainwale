<x-app-layout>
    <!-- Header -->
    <div class="bg-paper border-b border-newsprint py-12 md:py-16 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <span class="text-city-accent font-sans font-bold text-xs tracking-widest uppercase mb-3 block">Ujjain Journal &amp; Stories</span>
            <h1 class="font-display font-black text-4xl md:text-6xl text-ink tracking-tight mb-4">Blog</h1>
            <p class="font-sans text-gray-500 max-w-xl mx-auto text-base md:text-lg">
                Explore local stories, cultural reflections, in-depth reports, and the pulse of Ujjain.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-10 py-10 md:py-16">
        <!-- Filters & Search Toolbar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 border-b border-gray-200 pb-8">
            <!-- Category Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none">
                <a href="{{ route('blog') }}" 
                   class="px-4 py-2 rounded-full font-sans text-xs uppercase tracking-wider font-bold transition-colors whitespace-nowrap {{ !request('category') ? 'bg-ink text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All Stories
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('blog', array_merge(request()->except('page'), ['category' => $category->slug])) }}" 
                       class="px-4 py-2 rounded-full font-sans text-xs uppercase tracking-wider font-bold transition-colors whitespace-nowrap {{ request('category') === $category->slug ? 'bg-city-accent text-midnight' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('blog') }}" class="relative w-full md:w-80">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search articles..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-full border border-gray-200 bg-white text-sm font-sans focus:outline-none focus:border-city-accent focus:ring-1 focus:ring-city-accent text-ink placeholder-gray-400">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                @if(request('search'))
                    <a href="{{ route('blog', request()->except('search', 'page')) }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-ink text-xs font-bold">
                        &times;
                    </a>
                @endif
            </form>
        </div>

        <!-- Articles Grid -->
        @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                @foreach($articles as $article)
                    <x-article-card :article="$article" variant="standard" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-16">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm max-w-xl mx-auto px-6">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <h3 class="font-display font-bold text-xl text-ink mb-2">No articles found</h3>
                <p class="font-sans text-gray-500 text-sm mb-6">
                    @if(request('search') || request('category'))
                        We couldn't find any articles matching your current filters.
                    @else
                        No published articles are available yet.
                    @endif
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('blog') }}" class="inline-flex items-center px-4 py-2 bg-ink text-white rounded-lg text-xs font-sans font-bold uppercase tracking-wider hover:bg-city-accent hover:text-midnight transition-colors">
                        Clear all filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>

