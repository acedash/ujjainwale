@props(['headlines'])

@if($headlines->isNotEmpty())
    <section class="bg-paper rounded-xl p-8 border border-newsprint">
        <div class="mb-6">
            <h2 class="font-display font-bold text-2xl text-ink">
                <span class="text-city-accent mr-2">|</span>Trending Elsewhere
            </h2>
            <p class="font-sans text-sm text-gray-500 mt-1">Headlines from other local news sources, updated automatically.</p>
        </div>

        <div class="space-y-6">
            @foreach($headlines as $headline)
                <div class="flex items-start gap-4 group">
                    @if($headline->thumbnail_url)
                        <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded overflow-hidden">
                            <img src="{{ $headline->thumbnail_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @endif
                    <div class="flex-grow">
                        <h3 class="font-sans font-bold text-lg text-ink leading-snug mb-1">
                            <a href="{{ $headline->source_url }}" target="_blank" rel="noopener noreferrer" class="hover:text-city-accent transition-colors">
                                {{ $headline->title }}
                            </a>
                        </h3>
                        <p class="font-sans text-sm text-gray-600 mb-2 line-clamp-2">
                            {{ $headline->original_snippet }}
                        </p>
                        <div class="flex items-center gap-2">
                            @if($headline->newsSource->hasMedia('logo'))
                                <img src="{{ $headline->newsSource->getFirstMediaUrl('logo') }}" alt="{{ $headline->newsSource->name }}" class="w-4 h-4 rounded-full">
                            @endif
                            <span class="font-sans text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                {{ $headline->newsSource->name }}
                            </span>
                            <span class="font-sans text-xs text-gray-400">&bull;</span>
                            <span class="font-sans text-xs text-gray-400">
                                {{ $headline->published_at ? $headline->published_at->diffForHumans() : $headline->fetched_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
