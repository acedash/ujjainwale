<x-app-layout>
    <!-- Background Elements -->
    <div class="fixed inset-0 z-[-1] bg-paper overflow-hidden">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-city-accent/5 blur-[120px] mix-blend-multiply pointer-events-none"></div>
        <div class="absolute top-[30%] -right-[10%] w-[40%] h-[60%] rounded-full bg-blue-500/5 blur-[120px] mix-blend-multiply pointer-events-none"></div>
    </div>

    <!-- Glassmorphic Header -->
    <div class="sticky top-0 z-40 bg-white/70 backdrop-blur-xl border-b border-white/20 shadow-sm shadow-ink/5 pt-8 pb-6 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-city-accent to-[#a33927] text-white flex items-center justify-center font-display font-black text-3xl shadow-lg shadow-city-accent/30">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="font-display font-black text-4xl text-ink tracking-tight">My Dashboard</h1>
                    <p class="font-sans text-gray-500 mt-1 flex items-center gap-2">
                        Welcome back, <span class="font-bold text-ink">{{ auth()->user()->name }}</span>
                    </p>
                </div>
            </div>
            
            @if(auth()->user()->canAccessPanel(new \Filament\Panel('admin')))
                <a href="/admin" class="group inline-flex items-center gap-2 font-sans text-sm font-bold text-ink bg-white/80 backdrop-blur-md border border-gray-200/50 px-5 py-2.5 rounded-full hover:bg-city-accent hover:text-white hover:border-city-accent transition-all shadow-sm">
                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Staff Panel
                </a>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row gap-12 relative z-10">
            <!-- Sidebar -->
            <div class="w-full lg:w-64 flex-shrink-0">
                <div class="sticky top-32 bg-white/40 backdrop-blur-3xl rounded-3xl border border-white/50 p-4 shadow-sm">
                    <nav class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 font-sans text-sm font-bold rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-city-accent/10 to-transparent text-city-accent border-l-4 border-city-accent' : 'text-gray-500 hover:bg-white/60 hover:text-ink hover:shadow-sm' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-city-accent' : 'text-gray-400 group-hover:text-ink transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Overview
                        </a>
                        
                        <a href="{{ route('dashboard.reviews') }}" class="group flex items-center gap-3 px-4 py-3 font-sans text-sm font-bold rounded-2xl transition-all {{ request()->routeIs('dashboard.reviews') ? 'bg-gradient-to-r from-city-accent/10 to-transparent text-city-accent border-l-4 border-city-accent' : 'text-gray-500 hover:bg-white/60 hover:text-ink hover:shadow-sm' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('dashboard.reviews') ? 'text-city-accent' : 'text-gray-400 group-hover:text-ink transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            My Reviews
                        </a>

                        <a href="{{ route('dashboard.businesses') }}" class="group flex items-center gap-3 px-4 py-3 font-sans text-sm font-bold rounded-2xl transition-all {{ request()->routeIs('dashboard.businesses*') ? 'bg-gradient-to-r from-city-accent/10 to-transparent text-city-accent border-l-4 border-city-accent' : 'text-gray-500 hover:bg-white/60 hover:text-ink hover:shadow-sm' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('dashboard.businesses*') ? 'text-city-accent' : 'text-gray-400 group-hover:text-ink transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            My Businesses
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 font-sans text-sm font-bold rounded-2xl transition-all {{ request()->routeIs('profile.edit') ? 'bg-gradient-to-r from-city-accent/10 to-transparent text-city-accent border-l-4 border-city-accent' : 'text-gray-500 hover:bg-white/60 hover:text-ink hover:shadow-sm' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('profile.edit') ? 'text-city-accent' : 'text-gray-400 group-hover:text-ink transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Settings
                        </a>
                        
                        <div class="pt-4 mt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full group flex items-center gap-3 text-left px-4 py-3 font-sans text-sm font-bold text-red-500 rounded-2xl hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5 text-red-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-grow max-w-4xl">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>
