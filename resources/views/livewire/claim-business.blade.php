<div>
    @if(!$business->owner_id)
        @if($success)
            <div class="mt-6 bg-green-50 border border-green-200 text-green-800 p-4 rounded-md">
                <p class="font-medium">Claim request submitted!</p>
                <p class="text-sm mt-1">Our team will review your request. This business listing is currently under review and temporarily hidden from the public directory until verified.</p>
            </div>
        @else
            <div class="mt-6">
                <button wire:click="$set('showModal', true)" class="text-sm text-[#C4442E] font-medium hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Own this business? Claim it now.
                </button>
            </div>

            @if($showModal)
                <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Claim "{{ $business->name }}"
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">
                                            By claiming this business, you are requesting administrative access to manage its listing, photos, and contact information.
                                        </p>
                                        <div class="text-left">
                                            <label for="verification_details" class="block text-sm font-medium text-gray-700">Verification Details</label>
                                            <p class="text-xs text-gray-500 mb-2">Please explain your relationship to this business and provide any links/info that can help us verify your claim.</p>
                                            <textarea wire:model="verification_details" id="verification_details" rows="4" class="shadow-sm focus:ring-[#C4442E] focus:border-[#C4442E] block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                            @error('verification_details') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                                <button type="button" wire:click="claim" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#C4442E] text-base font-medium text-white hover:bg-[#a33927] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C4442E] sm:ml-3 sm:w-auto sm:text-sm">
                                    Submit Claim
                                </button>
                                <button type="button" wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C4442E] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endif
</div>
