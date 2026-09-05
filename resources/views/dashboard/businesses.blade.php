<x-dashboard-layout>
    <div class="relative bg-white/70 backdrop-blur-xl p-8 border border-white/50 rounded-3xl shadow-sm overflow-hidden">
        <!-- Subtle Glow -->
        <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-blue-500/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 relative z-10">
            <div>
                <h2 class="font-display font-black text-3xl text-ink">My Businesses</h2>
                <p class="font-sans text-gray-500 mt-1">Manage and track your directory listings.</p>
            </div>
            <a href="{{ route('directory.register') }}" class="inline-flex items-center gap-2 font-sans text-sm font-bold text-white bg-city-accent hover:bg-[#a33927] px-5 py-2.5 rounded-full transition-all shadow-md shadow-city-accent/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Business
            </a>
        </div>

        <div class="relative z-10">
            @if($businesses->count() > 0)
                <div class="grid grid-cols-1 gap-6">
                    @foreach($businesses as $business)
                        <div class="group bg-white rounded-2xl border border-gray-200/60 p-6 shadow-sm hover:shadow-lg hover:border-city-accent/30 transition-all flex flex-col md:flex-row md:items-center gap-6">
                            
                            <!-- Thumbnail (if exists) -->
                            <div class="w-full md:w-48 h-32 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden relative border border-gray-100">
                                @if($business->getFirstMediaUrl('cover'))
                                    <img src="{{ $business->getFirstMediaUrl('cover') }}" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                @endif
                                
                                <!-- Status Badge Overlay -->
                                <div class="absolute top-2 right-2">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider backdrop-blur-md shadow-sm border {{ $business->status === 'published' ? 'bg-green-500/90 text-white border-green-600' : 'bg-yellow-400/90 text-ink border-yellow-500' }}">
                                        {{ $business->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="flex-grow">
                                <h3 class="font-display font-bold text-2xl text-ink group-hover:text-city-accent transition-colors">{{ $business->name }}</h3>
                                <p class="font-sans text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $business->address }}
                                </p>
                                
                                <div class="flex flex-wrap items-center gap-2 mt-4">
                                    @foreach($business->categories->take(2) as $category)
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-sans font-medium">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                    <span class="px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full text-xs font-sans font-bold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        {{ $business->reviews()->count() }} Reviews
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex flex-row md:flex-col gap-2 md:w-32 flex-shrink-0">
                                <a href="{{ route('directory.show', $business) }}" class="flex-1 flex items-center justify-center gap-2 font-sans text-sm font-bold text-ink bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View
                                </a>
                                <a href="{{ route('dashboard.businesses.edit', $business) }}" class="flex-1 flex items-center justify-center gap-2 font-sans text-sm font-bold text-white bg-ink border border-ink px-4 py-2.5 rounded-xl hover:bg-city-accent hover:border-city-accent transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white/50 backdrop-blur-sm rounded-2xl border border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="font-display font-bold text-xl text-ink mb-2">No Businesses Yet</h3>
                    <p class="font-sans text-gray-500 mb-6 max-w-sm mx-auto">You haven't claimed or registered any businesses. Add one to start reaching local customers.</p>
                    <a href="{{ route('directory.register') }}" class="inline-flex font-sans text-sm font-bold text-white bg-city-accent px-6 py-3 rounded-full hover:bg-[#a33927] transition-all shadow-lg shadow-city-accent/30 hover:-translate-y-0.5">Register a Business</a>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
