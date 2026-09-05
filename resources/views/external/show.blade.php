<x-app-layout>
    <article class="w-full bg-gray-50 min-h-screen pb-24">
        <!-- Hero Section -->
        <header class="relative w-full h-[50vh] min-h-[350px] max-h-[500px] flex items-end justify-center pb-24 md:pb-32">
            @if($headline->thumbnail_url)
                <div class="absolute inset-0 z-0">
                    <img src="{{ $headline->thumbnail_url }}" alt="{{ $headline->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-black/30"></div>
                </div>
            @else
                <div class="absolute inset-0 z-0 bg-ink">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                </div>
            @endif

            <div class="relative z-10 w-full max-w-4xl px-6 text-center">
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold text-white uppercase tracking-widest mb-5 border border-white/20 shadow-sm">
                    Aggregated Briefing
                </span>
                
                <h1 class="font-display font-black text-3xl md:text-5xl text-white leading-tight mb-5 drop-shadow-lg">
                    {{ $headline->title }}
                </h1>

                <div class="font-sans text-xs md:text-sm text-gray-300 flex items-center justify-center gap-3 uppercase tracking-wider font-semibold drop-shadow">
                    <span>{{ ($headline->published_at ?? $headline->fetched_at)?->format('M d, Y') }}</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-city-accent"></span>
                    <span>2 Min Read</span>
                </div>
            </div>
        </header>

        <!-- Content Section (Clean Card) -->
        <div class="relative z-20 max-w-2xl mx-auto px-8 py-10 -mt-16 bg-white rounded-3xl shadow-xl border border-gray-100">
            @if($headline->ai_summary)
                <div class="mb-10 p-6 rounded-2xl bg-blue-50 border border-blue-100 relative">
                    <div class="absolute -top-3 left-6 bg-white px-3 py-0.5 rounded-full border border-blue-100 shadow-sm flex items-center gap-1.5 text-blue-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="font-sans font-bold text-[10px] uppercase tracking-widest">AI Summary</span>
                    </div>
                    <p class="font-sans font-medium text-lg text-ink leading-relaxed m-0">{{ $headline->ai_summary }}</p>
                </div>
            @endif
            
            <div class="prose prose-ink max-w-none font-serif text-lg text-gray-700 leading-relaxed mb-10">
                {!! nl2br(e($headline->original_snippet)) !!}
            </div>
            
            <div class="flex flex-col sm:flex-row items-center justify-between pt-8 border-t border-gray-100 gap-4">
                <a href="/" class="text-sm font-bold text-gray-400 hover:text-ink transition-colors uppercase tracking-widest order-2 sm:order-1">
                    &larr; Back
                </a>
                <a href="{{ $headline->source_url }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto text-center font-sans text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 py-3 px-8 rounded-full order-1 sm:order-2">
                    Read on {{ $headline->newsSource->name }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>
        </div>
    </article>
</x-app-layout>
