<div x-data="{ mobileMenuOpen: false }">
    <header class="py-4 px-6 md:px-10 sticky top-0 bg-white border-b border-gray-200 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Left Side: Logo, Weather, and Nav -->
            <div class="flex items-center gap-8 lg:gap-12">
                <!-- Logo & Weather -->
                <div class="flex items-center gap-6">
                    <a href="/" class="relative z-50 hover:opacity-80 transition-opacity">
                        <!-- We might need the original or a dark version of the logo, but let's assume it has good contrast -->
                        <img src="{{ asset('images/ujjain-wale.jpeg') }}" alt="Ujjain Logo" class="h-10 w-auto">
                    </a>
                    
                    <!-- Weather / Date (Desktop only) -->
                    <div class="hidden lg:flex flex-col border-l border-gray-300 pl-6 py-1 relative z-50">
                        <span class="text-[10px] font-sans font-bold text-gray-500 uppercase tracking-widest">{{ now()->format('l, M j') }}</span>
                        <span class="text-xs font-sans font-bold text-ink flex items-center gap-1 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>
                            32°C Ujjain
                        </span>
                    </div>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center space-x-5 lg:space-x-8 font-sans font-bold text-xs uppercase tracking-widest relative z-50">
                    <a href="{{ route('home') }}" class="text-ink hover:text-city-accent transition-colors">Home</a>
                    
                    <!-- Categories Dropdown -->
                    <div x-data="{ open: false }" @mouseleave="open = false" class="relative">
                        <button @mouseenter="open = true" @click="open = !open" class="text-ink hover:text-city-accent transition-colors flex items-center gap-1 focus:outline-none py-2">
                            News
                            <svg :class="{'rotate-180': open}" class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <!-- Dropdown Panel -->
                        <div x-show="open" style="display: none;" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute top-full left-0 w-48 pt-2">
                            <div class="bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 p-2 flex flex-col gap-1 relative z-50">
                                @foreach(\App\Models\Category::take(5)->get() as $category)
                                    <a href="{{ route('categories.show', $category) }}" class="px-4 py-2 hover:bg-gray-50 text-ink hover:text-city-accent rounded-lg transition-colors">{{ $category->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('home') }}#trending" class="text-ink hover:text-city-accent transition-colors">Trending</a>
                    
                    <a href="{{ route('directory') }}" class="text-city-accent hover:opacity-80 transition-opacity">Directory</a>
                    
                    @auth
                        @if(auth()->user()->hasRole(['Admin', 'Editor', 'Author']))
                            <a href="{{ url('/portal') }}" class="text-ink hover:text-city-accent transition-colors">Staff Panel</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-ink hover:text-city-accent transition-colors">Profile</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-ink hover:text-city-accent transition-colors">Log In</a>
                    @endauth
                </nav>
            </div>

            <!-- Right Side: Search / List Business / Mobile Menu -->
            <div class="flex items-center space-x-4 relative z-50">
                <!-- CTA Button -->
                <a href="{{ route('directory') }}" class="hidden md:block bg-city-accent text-midnight px-4 py-2.5 rounded-full hover:bg-city-accent/90 transition-colors shadow-sm whitespace-nowrap font-sans font-bold text-xs uppercase tracking-widest">
                    List Business
                </a>
                
                <button @click="$dispatch('open-search')" class="text-ink hover:text-city-accent">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-ink">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" style="display:none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" style="display:none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="absolute top-full left-0 w-full bg-paper border-b border-newsprint shadow-xl z-40 md:hidden">
            <nav class="flex flex-col font-sans font-medium p-6 space-y-4">
                <a href="{{ route('home') }}" class="text-ink hover:text-city-accent transition-colors text-lg border-b border-newsprint pb-2">Home</a>
                <a href="{{ route('home') }}" class="text-ink hover:text-city-accent transition-colors text-lg border-b border-newsprint pb-2">News</a>
                <a href="{{ route('home') }}#trending" class="text-ink hover:text-city-accent transition-colors text-lg border-b border-newsprint pb-2">Trending</a>
                <a href="{{ route('directory') }}" class="text-directory-blue hover:opacity-80 transition-opacity text-lg border-b border-newsprint pb-2">Local Businesses</a>
                
                @auth
                    @if(auth()->user()->hasRole(['Admin', 'Editor', 'Author']))
                        <a href="{{ url('/portal') }}" class="text-ink hover:text-city-accent transition-colors text-lg border-b border-newsprint pb-2">Staff Panel</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-ink hover:text-city-accent transition-colors text-lg border-b border-newsprint pb-2">My Profile</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-ink hover:text-city-accent transition-colors text-lg border-b border-newsprint pb-2">Log In</a>
                @endauth
                
                <a href="{{ route('directory') }}" class="bg-city-accent text-white text-center px-4 py-3 rounded-lg hover:bg-city-accent/90 transition-colors shadow-sm font-bold tracking-widest uppercase text-sm mt-4">
                    List Your Business
                </a>
            </nav>
        </div>
    </header>

    @livewire('global-search')
</div>