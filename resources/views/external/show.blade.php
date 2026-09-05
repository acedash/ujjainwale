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
        <div class="relative z-20 max-w-4xl mx-auto px-6 py-16 -mt-16 md:-mt-24 bg-white rounded-t-[3rem] md:rounded-[3rem] md:mb-24 shadow-2xl flex flex-col border border-gray-100">
            <div class="max-w-2xl mx-auto w-full">
                
                @if($headline->ai_summary)
                    <div class="mb-14 p-8 rounded-3xl bg-blue-50 border border-blue-100 relative">
                        <div class="absolute -top-3 left-8 bg-white px-3 py-1 rounded-full border border-blue-100 shadow-sm flex items-center gap-1.5 text-blue-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span class="font-sans font-bold text-[10px] uppercase tracking-widest">AI Summary</span>
                        </div>
                        <p class="font-sans font-medium text-xl text-ink leading-relaxed m-0">{{ $headline->ai_summary }}</p>
                    </div>
                @endif
                
                <div class="relative my-8">
                    <svg class="absolute -top-8 -left-6 md:-left-12 w-16 h-16 text-gray-100 transform -scale-x-100" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/></svg>
                    
                    <div class="text-2xl md:text-3xl font-serif text-gray-700 leading-relaxed md:leading-[1.8] text-center relative z-10 px-4 font-medium">
                        {!! nl2br(e($headline->original_snippet)) !!}
                    </div>
                </div>
                
                <div class="flex flex-col items-center justify-center pt-16 mt-16 border-t border-gray-100">
                    <span class="font-sans text-sm font-semibold text-gray-400 mb-6 uppercase tracking-[0.2em]">Read Full Story</span>
                    <a href="{{ $headline->source_url }}" target="_blank" rel="noopener noreferrer" class="font-sans text-base font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 flex items-center gap-3 py-4 px-10 rounded-full">
                        Visit {{ $headline->newsSource->name }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </article>
</x-app-layout>
