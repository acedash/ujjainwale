<div x-data="{ mobileMenuOpen: false }">
    <header class="py-3 px-6 md:px-10 sticky top-0 bg-white/95 backdrop-blur-md border-b border-gray-200 z-50 transition-all duration-300 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Left Side: Logo, Branding, Weather, and Nav -->
            <div class="flex items-center gap-6 lg:gap-10">
                <!-- Logo & Brand Name -->
                <div class="flex items-center gap-4">
                    <a href="/" class="flex items-center gap-3 relative z-50 group">
                        <img src="{{ asset('images/ujjain-wale.jpeg') }}" alt="Ujjain Wale Logo" class="h-10 w-auto rounded shadow-sm border border-yellow-300/80 group-hover:scale-105 transition-transform">
                        <div class="flex flex-col">
                            <span class="font-display font-black text-lg tracking-tight text-ink uppercase leading-none">Ujjain<span class="text-amber-600">Wale</span></span>
                            <span class="text-[9px] font-sans font-bold tracking-widest text-gray-400 uppercase mt-0.5">The City Journal</span>
                        </div>
                    </a>
                    
                    <!-- Weather / Date (Desktop only) -->
                    <div class="hidden xl:flex flex-col border-l border-gray-200 pl-4 py-0.5 relative z-50">
                        <span class="text-[10px] font-sans font-bold text-gray-400 uppercase tracking-widest">{{ now()->format('l, M j') }}</span>
                        <span class="text-xs font-sans font-bold text-ink flex items-center gap-1.5 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>
                            32°C Ujjain
                        </span>
                    </div>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center space-x-5 lg:space-x-7 font-sans font-bold text-xs uppercase tracking-widest relative z-50">
                    <a href="{{ route('home') }}" class="py-2 transition-all relative {{ request()->routeIs('home') ? 'text-ink font-black after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[3px] after:bg-city-accent after:rounded-full' : 'text-gray-700 hover:text-ink' }}">Home</a>
                    <a href="{{ route('blog') }}" class="py-2 transition-all relative {{ request()->routeIs('blog') ? 'text-ink font-black after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[3px] after:bg-city-accent after:rounded-full' : 'text-gray-700 hover:text-ink' }}">Blog</a>
                    
                    <!-- Categories Dropdown -->
                    <div x-data="{ open: false }" @mouseleave="open = false" class="relative">
                        <button @mouseenter="open = true" @click="open = !open" class="text-gray-700 hover:text-ink transition-colors flex items-center gap-1 focus:outline-none py-2 font-bold text-xs uppercase tracking-widest">
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
                             class="absolute top-full left-0 w-52 pt-2">
                            <div class="bg-white rounded-xl shadow-[0_10px_35px_rgba(0,0,0,0.12)] border border-gray-100 p-2 flex flex-col gap-1 relative z-50">
                                @foreach(\App\Models\Category::take(5)->get() as $category)
                                    <a href="{{ route('categories.show', $category) }}" class="px-3.5 py-2 hover:bg-amber-50/70 hover:text-amber-900 text-gray-700 text-xs font-semibold rounded-lg transition-colors">{{ $category->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('home') }}#trending" class="py-2 text-gray-700 hover:text-ink transition-colors">Trending</a>
                    
                    <a href="{{ route('directory') }}" class="py-2 transition-all relative {{ request()->routeIs('directory*') ? 'text-ink font-black after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[3px] after:bg-city-accent after:rounded-full' : 'text-gray-700 hover:text-ink' }}">Directory</a>
                    
                    @auth
                        @if(auth()->user()->hasRole(['Admin', 'Editor', 'Author']))
                            <a href="{{ url('/portal') }}" class="py-2 text-gray-700 hover:text-ink transition-colors">Staff Panel</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="py-2 text-gray-700 hover:text-ink transition-colors">Profile</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="py-2 text-gray-700 hover:text-ink transition-colors">Log In</a>
                    @endauth
                </nav>
            </div>

            <!-- Right Side: Search / List Business / Mobile Menu -->
            <div class="flex items-center space-x-3.5 relative z-50">
                <!-- CTA Button -->
                <a href="{{ route('directory') }}" class="hidden md:inline-flex items-center gap-1.5 bg-city-accent hover:bg-yellow-300 text-ink font-black px-4 py-2 rounded-full border border-yellow-300 shadow-sm transition-all text-xs tracking-wider uppercase font-sans hover:shadow-md hover:-translate-y-0.5">
                    List Business
                </a>
                
                <button type="button" @click="$dispatch('open-search')" aria-label="Search" class="p-2 rounded-full text-gray-600 hover:text-ink hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-full text-gray-700 hover:text-ink hover:bg-gray-100 transition-colors">
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
                <a href="{{ route('home') }}" class="text-ink hover:text-amber-700 transition-colors text-lg border-b border-newsprint pb-2 {{ request()->routeIs('home') ? 'font-bold text-amber-700' : '' }}">Home</a>
                <a href="{{ route('blog') }}" class="text-ink hover:text-amber-700 transition-colors text-lg border-b border-newsprint pb-2 {{ request()->routeIs('blog') ? 'font-bold text-amber-700' : '' }}">Blog</a>
                <button type="button" @click="mobileMenuOpen = false; $dispatch('open-search')" class="text-left text-ink hover:text-amber-700 transition-colors text-lg border-b border-newsprint pb-2 flex items-center justify-between">
                    <span>Search</span>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                <a href="{{ route('home') }}#trending" class="text-ink hover:text-amber-700 transition-colors text-lg border-b border-newsprint pb-2">Trending</a>
                <a href="{{ route('directory') }}" class="text-directory-blue hover:opacity-80 transition-opacity text-lg border-b border-newsprint pb-2 {{ request()->routeIs('directory*') ? 'font-bold' : '' }}">Local Businesses</a>
                
                @auth
                    @if(auth()->user()->hasRole(['Admin', 'Editor', 'Author']))
                        <a href="{{ url('/portal') }}" class="text-ink hover:text-amber-700 transition-colors text-lg border-b border-newsprint pb-2">Staff Panel</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-ink hover:text-amber-700 transition-colors text-lg border-b border-newsprint pb-2">My Profile</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-ink hover:text-amber-700 transition-colors text-lg border-b border-newsprint pb-2">Log In</a>
                @endauth
                
                <a href="{{ route('directory') }}" class="bg-city-accent text-ink text-center px-4 py-3 rounded-lg hover:bg-yellow-300 border border-yellow-300 transition-colors shadow-sm font-black tracking-widest uppercase text-sm mt-4">
                    List Your Business
                </a>
            </nav>
        </div>
    </header>

    @livewire('global-search')
</div>
