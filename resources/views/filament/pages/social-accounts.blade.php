<x-filament-panels::page>
    <div class="space-y-6">
        @if(count($connectedAccounts) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($connectedAccounts as $account)
                    <x-filament::card>
                        <div class="flex items-center space-x-4">
                            @if(isset($account->meta['avatar']))
                                <img src="{{ $account->meta['avatar'] }}" alt="Avatar" class="w-12 h-12 rounded-full">
                            @endif
                            <div>
                                <h3 class="text-lg font-medium">{{ ucfirst($account->provider) }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $account->meta['name'] ?? 'Connected' }}
                                </p>
                            </div>
                        </div>
                    </x-filament::card>
                @endforeach
            </div>
        @else
            <x-filament::card>
                <div class="text-center py-6">
                    <p class="text-gray-500 dark:text-gray-400">No social accounts connected yet.</p>
                    <p class="text-sm mt-2">Click "Connect Facebook" in the top right to get started.</p>
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament-panels::page>
