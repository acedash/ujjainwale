<div>
    <!-- Hero Banner -->
    <div class="bg-ink relative overflow-hidden pt-24 pb-32">
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-city-accent text-white text-xs font-bold font-sans uppercase tracking-wider mb-4">Directory Submissions</span>
            <h1 class="font-display font-black text-5xl md:text-6xl text-white mb-6">Put Your Business on the Map</h1>
            <p class="font-sans text-xl text-gray-300 max-w-2xl mx-auto font-light">
                Join Indore's most curated local directory. Submit your details below for our editorial team to review and feature your business.
            </p>
        </div>
    </div>

    <div class="bg-paper pb-24 -mt-16">
        <div class="max-w-4xl mx-auto px-6">
            
            <div class="bg-white rounded-2xl shadow-xl shadow-ink/5 border border-newsprint overflow-hidden relative z-20">
                
                @if($success)
                    <div class="p-12 md:p-20 text-center">
                        <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-8 border-4 border-green-100">
                            <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="font-display font-black text-4xl text-ink mb-4">Application Submitted!</h3>
                        <p class="font-sans text-xl text-gray-600 mb-10 max-w-lg mx-auto">
                            Thank you for joining CityMag. Our editorial team will review your submission shortly. 
                        </p>
                        <a href="{{ route('directory') }}" class="inline-flex items-center gap-2 font-sans font-bold text-white bg-ink hover:bg-city-accent px-8 py-4 rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Explore the Directory
                        </a>
                    </div>
                @else
                    <form wire:submit="submit" class="divide-y divide-newsprint">
                        
                        <!-- Honeypot -->
                        <div style="display:none;"><input type="text" wire:model="honeypot" id="honeypot"></div>

                        <!-- Section 1: Account Setup -->
                        <div class="p-8 md:p-12 grid grid-cols-1 md:grid-cols-3 gap-8 bg-gray-50/50">
                            <div class="md:col-span-1">
                                <h3 class="font-display font-bold text-2xl text-ink mb-2">Account Setup</h3>
                                <p class="font-sans text-gray-500 text-sm">We need an account to link your business to, so you can manage it later.</p>
                            </div>
                            
                            <div class="md:col-span-2 space-y-6">
                                @guest
                                    <div class="group">
                                        <label for="user_name" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Your Full Name <span class="text-city-accent">*</span></label>
                                        <input type="text" wire:model="user_name" id="user_name" placeholder="John Doe" class="w-full rounded-lg border-gray-200 bg-white px-4 py-3 text-ink focus:bg-white focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans">
                                        @error('user_name') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="group">
                                        <label for="user_email" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Email Address <span class="text-city-accent">*</span></label>
                                        <input type="email" wire:model="user_email" id="user_email" placeholder="john@example.com" class="w-full rounded-lg border-gray-200 bg-white px-4 py-3 text-ink focus:bg-white focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans">
                                        @error('user_email') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{!! $message !!}</span> @enderror
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="group">
                                            <label for="user_password" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Password <span class="text-city-accent">*</span></label>
                                            <input type="password" wire:model="user_password" id="user_password" class="w-full rounded-lg border-gray-200 bg-white px-4 py-3 text-ink focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans shadow-sm">
                                            @error('user_password') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="group">
                                            <label for="user_password_confirmation" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Confirm Password <span class="text-city-accent">*</span></label>
                                            <input type="password" wire:model="user_password_confirmation" id="user_password_confirmation" class="w-full rounded-lg border-gray-200 bg-white px-4 py-3 text-ink focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans shadow-sm">
                                        </div>
                                    </div>
                                @endguest

                                @auth
                                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-city-accent text-white flex items-center justify-center font-display font-bold text-xl">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-sans text-sm text-gray-500">Registering business as</p>
                                            <p class="font-sans font-bold text-ink">{{ auth()->user()->name }}</p>
                                            <p class="font-sans text-sm text-gray-500">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </div>

                        <!-- Section 2: Basic Information -->
                        <div class="p-8 md:p-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="md:col-span-1">
                                <h3 class="font-display font-bold text-2xl text-ink mb-2">Basic Details</h3>
                                <p class="font-sans text-gray-500 text-sm">Tell us the core information about your business. Make it sound appealing!</p>
                            </div>
                            
                            <div class="md:col-span-2 space-y-6">
                                <div class="group">
                                    <label for="name" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Business Name <span class="text-city-accent">*</span></label>
                                    <input type="text" wire:model="name" id="name" placeholder="e.g. The Indore Cafe" class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-3 text-ink focus:bg-white focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans">
                                    @error('name') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="group">
                                    <label for="description" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Description <span class="text-city-accent">*</span></label>
                                    <textarea wire:model="description" id="description" rows="5" placeholder="What makes this business special? What do you offer?" class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-3 text-ink focus:bg-white focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans resize-none"></textarea>
                                    @error('description') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Location & Contact -->
                        <div class="p-8 md:p-12 grid grid-cols-1 md:grid-cols-3 gap-8 bg-gray-50/50">
                            <div class="md:col-span-1">
                                <h3 class="font-display font-bold text-2xl text-ink mb-2">Location & Contact</h3>
                                <p class="font-sans text-gray-500 text-sm mb-4">We'll use your address to automatically place a beautiful pin on our interactive map. You can also drag the pin to set your exact location.</p>
                            </div>
                            
                            <div class="md:col-span-2 space-y-6">
                                <div class="group">
                                    <label for="address" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Full Street Address <span class="text-city-accent">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-city-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </div>
                                        <input type="text" wire:model="address" id="address" placeholder="e.g. 123 MG Road, Indore" class="w-full pl-11 rounded-lg border-gray-200 bg-white px-4 py-3 text-ink focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans shadow-sm">
                                    </div>
                                    @error('address') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="group">
                                        <label for="lat" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Latitude <span class="text-city-accent">*</span></label>
                                        <input type="text" wire:model="lat" id="lat" placeholder="e.g. 22.7196" class="w-full rounded-lg border-gray-200 bg-white px-4 py-3 text-ink focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans shadow-sm">
                                        @error('lat') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="group">
                                        <label for="lng" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Longitude <span class="text-city-accent">*</span></label>
                                        <input type="text" wire:model="lng" id="lng" placeholder="e.g. 75.8577" class="w-full rounded-lg border-gray-200 bg-white px-4 py-3 text-ink focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans shadow-sm">
                                        @error('lng') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <button type="button" x-data @click="navigator.geolocation.getCurrentPosition(pos => { $wire.lat = pos.coords.latitude; $wire.lng = pos.coords.longitude; }, err => alert('Could not get location. Please ensure location permissions are granted or enter manually.'));" class="text-sm font-sans font-bold text-city-accent hover:text-ink transition-colors flex items-center gap-1.5 bg-white py-2 px-4 rounded border border-gray-200 shadow-sm w-max">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Get Current Location
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="group">
                                        <label for="phone" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Phone Number</label>
                                        <input type="text" wire:model="phone" id="phone" placeholder="+91..." class="w-full rounded-lg border-gray-200 bg-white px-4 py-3 text-ink focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans shadow-sm">
                                        @error('phone') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="group">
                                        <label for="website" class="block font-sans font-bold text-sm text-ink mb-2 group-focus-within:text-city-accent transition-colors">Website URL</label>
                                        <input type="url" wire:model="website" id="website" placeholder="https://" class="w-full rounded-lg border-gray-200 bg-white px-4 py-3 text-ink focus:border-city-accent focus:ring-2 focus:ring-city-accent/20 transition-all font-sans shadow-sm">
                                        @error('website') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Categorization -->
                        <div class="p-8 md:p-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="md:col-span-1">
                                <h3 class="font-display font-bold text-2xl text-ink mb-2">Categorization</h3>
                                <p class="font-sans text-gray-500 text-sm">Help users find your business by selecting the right categories and neighborhood.</p>
                            </div>
                            
                            <div class="md:col-span-2 space-y-8">
                                <div class="group">
                                    <label class="block font-sans font-bold text-sm text-ink mb-3 group-focus-within:text-city-accent transition-colors">Categories <span class="text-city-accent">*</span></label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                                        @foreach($this->availableCategories as $category)
                                            <label class="flex items-center p-3 rounded-lg border border-gray-200 bg-white cursor-pointer hover:border-city-accent hover:bg-red-50 transition-colors has-[:checked]:border-city-accent has-[:checked]:bg-red-50 has-[:checked]:ring-1 has-[:checked]:ring-city-accent">
                                                <input type="checkbox" wire:model="category_ids" value="{{ $category->id }}" class="w-4 h-4 text-city-accent border-gray-300 rounded focus:ring-city-accent focus:ring-2">
                                                <span class="ml-3 font-sans text-sm text-ink">{{ $category->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('category_ids') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                </div>

                                <div class="group">
                                    <label class="block font-sans font-bold text-sm text-ink mb-3 group-focus-within:text-city-accent transition-colors">Neighborhoods <span class="text-city-accent">*</span></label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                                        @foreach($this->availableNeighborhoods as $neighborhood)
                                            <label class="flex items-center p-3 rounded-lg border border-gray-200 bg-white cursor-pointer hover:border-city-accent hover:bg-red-50 transition-colors has-[:checked]:border-city-accent has-[:checked]:bg-red-50 has-[:checked]:ring-1 has-[:checked]:ring-city-accent">
                                                <input type="checkbox" wire:model="neighborhood_ids" value="{{ $neighborhood->id }}" class="w-4 h-4 text-city-accent border-gray-300 rounded focus:ring-city-accent focus:ring-2">
                                                <span class="ml-3 font-sans text-sm text-ink">{{ $neighborhood->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('neighborhood_ids') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Media & Imagery -->
                        <div class="p-8 md:p-12 grid grid-cols-1 md:grid-cols-3 gap-8 bg-gray-50/50">
                            <div class="md:col-span-1">
                                <h3 class="font-display font-bold text-2xl text-ink mb-2">Media & Imagery</h3>
                                <p class="font-sans text-gray-500 text-sm">Upload high-quality images to make your business stand out in the directory. A great cover image and logo are essential.</p>
                            </div>
                            
                            <div class="md:col-span-2 space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Logo Upload -->
                                    <div class="group">
                                        <label class="block font-sans font-bold text-sm text-ink mb-3">Brand Logo</label>
                                        <div class="relative flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-xl bg-white hover:bg-gray-50 hover:border-city-accent transition-all group cursor-pointer overflow-hidden">
                                            <input type="file" wire:model="logo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="">
                                            
                                            @if ($logo)
                                                <div class="w-full h-32 flex items-center justify-center relative z-20">
                                                    <img src="{{ $logo->temporaryUrl() }}" class="max-h-full max-w-full object-contain rounded-lg drop-shadow-md">
                                                </div>
                                                <div class="mt-4 text-xs font-sans font-medium text-city-accent flex items-center gap-1 z-20">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    Change Logo
                                                </div>
                                            @else
                                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3 group-hover:bg-city-accent/10 group-hover:text-city-accent transition-colors text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                                <p class="font-sans text-sm font-semibold text-ink">Upload Logo</p>
                                                <p class="font-sans text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>
                                            @endif
                                            
                                            <!-- Loading State -->
                                            <div wire:loading wire:target="logo" class="absolute inset-0 bg-white/90 z-30 flex flex-col items-center justify-center">
                                                <svg class="animate-spin h-6 w-6 text-city-accent mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="text-xs font-semibold text-city-accent">Uploading...</span>
                                            </div>
                                        </div>
                                        @error('logo') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Cover Upload -->
                                    <div class="group">
                                        <label class="block font-sans font-bold text-sm text-ink mb-3">Cover Image</label>
                                        <div class="relative flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-xl bg-white hover:bg-gray-50 hover:border-city-accent transition-all group cursor-pointer overflow-hidden">
                                            <input type="file" wire:model="cover" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="">
                                            
                                            @if ($cover)
                                                <div class="w-full h-32 flex items-center justify-center relative z-20">
                                                    <img src="{{ $cover->temporaryUrl() }}" class="w-full h-full object-cover rounded-lg drop-shadow-md">
                                                </div>
                                                <div class="mt-4 text-xs font-sans font-medium text-city-accent flex items-center gap-1 z-20">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    Change Cover
                                                </div>
                                            @else
                                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3 group-hover:bg-city-accent/10 group-hover:text-city-accent transition-colors text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                </div>
                                                <p class="font-sans text-sm font-semibold text-ink">Upload Cover</p>
                                                <p class="font-sans text-xs text-gray-400 mt-1">High-res, 16:9 recommended</p>
                                            @endif
                                            
                                            <!-- Loading State -->
                                            <div wire:loading wire:target="cover" class="absolute inset-0 bg-white/90 z-30 flex flex-col items-center justify-center">
                                                <svg class="animate-spin h-6 w-6 text-city-accent mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="text-xs font-semibold text-city-accent">Uploading...</span>
                                            </div>
                                        </div>
                                        @error('cover') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Gallery Upload -->
                                <div class="group">
                                    <label class="block font-sans font-bold text-sm text-ink mb-3 flex items-center justify-between">
                                        Gallery Images
                                        <span class="text-xs font-normal text-gray-400">Select multiple</span>
                                    </label>
                                    
                                    <div class="relative flex flex-col items-center justify-center p-8 border-2 border-dashed border-gray-300 rounded-xl bg-white hover:bg-gray-50 hover:border-city-accent transition-all group cursor-pointer min-h-[160px]">
                                        <input type="file" wire:model="gallery" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="">
                                        
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3 group-hover:bg-city-accent/10 group-hover:text-city-accent transition-colors text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="font-sans text-sm font-semibold text-ink">Add Photos</p>
                                        <p class="font-sans text-xs text-gray-400 mt-1">Upload multiple photos of your business</p>
                                        
                                        <!-- Loading State -->
                                        <div wire:loading wire:target="gallery" class="absolute inset-0 bg-white/90 z-30 flex flex-col items-center justify-center rounded-xl">
                                            <svg class="animate-spin h-6 w-6 text-city-accent mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-xs font-semibold text-city-accent">Processing Images...</span>
                                        </div>
                                    </div>
                                    @error('gallery.*') <span class="text-red-500 text-xs font-sans mt-2 block font-medium">{{ $message }}</span> @enderror
                                    
                                    @if ($gallery)
                                        <div class="mt-4 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                            @foreach($gallery as $img)
                                                <div class="relative group/img aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-50">
                                                    <img src="{{ $img->temporaryUrl() }}" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="p-8 md:p-12 bg-ink text-white flex flex-col sm:flex-row items-center justify-between gap-6">
                            <p class="text-sm text-gray-400 font-sans max-w-md">
                                By submitting this form, you agree to our <a href="#" class="text-city-accent hover:text-white transition-colors underline decoration-city-accent/30 underline-offset-4">Terms of Service</a> and confirm that you have the authority to represent this business.
                            </p>
                            <button type="submit" class="w-full sm:w-auto font-sans font-bold text-white bg-city-accent hover:bg-[#a33927] px-10 py-4 rounded-lg shadow-lg shadow-city-accent/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                <span wire:loading.remove wire:target="submit">Submit for Review</span>
                                <span wire:loading wire:target="submit">Processing Application...</span>
                                <svg wire:loading.remove wire:target="submit" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
