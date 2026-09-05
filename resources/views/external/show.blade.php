<x-app-layout>
    <article class="w-full bg-white">
        <!-- Hero Section -->
        <header class="relative w-full h-[60vh] min-h-[400px] max-h-[600px] flex items-end justify-center pb-24 md:pb-32">
            @if($headline->thumbnail_url)
                <div class="absolute inset-0 z-0">
                    <img src="{{ $headline->thumbnail_url }}" alt="{{ $headline->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                </div>
            @else
                <div class="absolute inset-0 z-0 bg-ink">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                </div>
            @endif

            <div class="relative z-10 w-full max-w-4xl px-6 text-center">
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold text-white uppercase tracking-widest mb-6 border border-white/20 shadow-sm">
                    Aggregated Briefing
                </span>
                
                <h1 class="font-display font-black text-4xl md:text-5xl lg:text-6xl text-white leading-[1.15] mb-6 drop-shadow-lg">
                    {{ $headline->title }}
                </h1>

                <div class="font-sans text-sm text-gray-300 flex items-center justify-center gap-3 uppercase tracking-wider font-semibold drop-shadow">
                    <span>{{ ($headline->published_at ?? $headline->fetched_at)?->format('M d, Y') }}</span>
                    <span class="w-1 h-1 rounded-full bg-gray-400"></span>
                    <span>2 Min Read</span>
                </div>
            </div>
        </header>

        <!-- Content Section -->
        <div class="relative z-20 max-w-3xl mx-auto px-6 py-12 -mt-16 md:-mt-24 bg-white rounded-t-[3rem] md:rounded-t-[4rem] shadow-2xl">
            <div class="prose prose-lg prose-ink max-w-none font-serif leading-loose text-gray-800 pt-8">
                
                @if($headline->ai_summary)
                    <div class="mb-14 p-8 rounded-3xl bg-gray-50 border border-gray-100 relative">
                        <div class="absolute -top-3 left-8 bg-white px-3 py-1 rounded-full border border-gray-100 shadow-sm flex items-center gap-1.5 text-city-accent">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span class="font-sans font-bold text-[10px] uppercase tracking-widest">AI Summary</span>
                        </div>
                        <p class="font-sans font-medium text-xl text-ink leading-relaxed m-0">{{ $headline->ai_summary }}</p>
                    </div>
                @endif
                
                <div class="text-xl font-light text-gray-600 leading-relaxed mb-16 px-4 md:px-8">
                    {!! nl2br(e($headline->original_snippet)) !!}
                </div>
                
                <div class="flex flex-col items-center justify-center pt-12 pb-8 border-t border-gray-100">
                    <span class="font-sans text-xs text-gray-400 mb-4 uppercase tracking-widest">Continue Reading</span>
                    <a href="{{ $headline->source_url }}" target="_blank" rel="noopener noreferrer" class="font-sans text-sm font-bold text-city-accent hover:text-ink transition-colors flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100 py-2.5 px-6 rounded-full border border-gray-200">
                        Visit {{ $headline->newsSource->name }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </article>
</x-app-layout>
