<div>
    <div class="mb-4 text-sm text-gray-600">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Welcome to Indore Mag!</h2>
        <p>You have been invited to join the team. Please set a password for your account (<strong>{{ $user->email }}</strong>) to get started.</p>
    </div>

    <form wire:submit="submit">
        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
            <input wire:model="password" id="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-[#C4442E] focus:ring focus:ring-[#C4442E] focus:ring-opacity-50" type="password" name="password" required autocomplete="new-password" />
            @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
            <input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-[#C4442E] focus:ring focus:ring-[#C4442E] focus:ring-opacity-50" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-city-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-90 focus:bg-opacity-90 active:bg-opacity-100 focus:outline-none focus:ring-2 focus:ring-city-accent focus:ring-offset-2 transition ease-in-out duration-150">
                Set Password & Login
            </button>
        </div>
    </form>
</div>
