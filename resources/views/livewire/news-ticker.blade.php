<div class="w-full bg-ink text-white border-b border-gray-800 overflow-hidden relative h-10 flex items-center">
    <div class="absolute left-0 top-0 bottom-0 z-10 w-8 bg-gradient-to-r from-ink to-transparent pointer-events-none">
    </div>
    <div class="absolute right-0 top-0 bottom-0 z-10 w-8 bg-gradient-to-l from-ink to-transparent pointer-events-none">
    </div>

    <div class="flex whitespace-nowrap animate-marquee hover:[animation-play-state:paused]">
        <!-- Double the content for seamless looping -->
        @for($i = 0; $i < 2; $i++)
            <div class="flex items-center space-x-8 px-4">
                @foreach($headlines as $headline)
                    @php
                        $isExternal = $headline instanceof \App\Models\ExternalHeadline;
                        $url = $isExternal ? route('external.show', $headline) : route('articles.show', $headline);
                        $target = '';
                        $category = $headline->categories->first()?->name ?? 'Update';
                    @endphp
                    <a href="{{ $url }}" {!! $target !!} class="flex items-center group">
                        <span
                            class="text-city-accent font-sans font-bold text-[10px] uppercase tracking-widest mr-2">{{ $category }}</span>
                        <span
                            class="font-display text-sm text-gray-200 group-hover:text-white transition-colors">{{ $headline->title }}</span>
                        <span class="mx-6 text-gray-700">&bull;</span>
                    </a>
                @endforeach
            </div>
        @endfor
    </div>
</div>