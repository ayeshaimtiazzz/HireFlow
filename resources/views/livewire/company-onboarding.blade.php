<div class="max-w-xl mx-auto mt-16 bg-white p-8 rounded-lg shadow">
    @if ($submitted)
        <div class="text-center">
            <h2 class="text-2xl font-bold text-teal-700 mb-2">You're all set!</h2>
            <p class="text-gray-600 mb-4">Your workspace is ready at:</p>
            <a href="http://{{ session('onboarding_domain') }}/login" class="text-teal-600 underline">
                {{ session('onboarding_domain') }}/login
            </a>
        </div>
    @else
        <div class="mb-6 flex justify-between text-sm text-gray-500">
            <span class="{{ $step === 1 ? 'font-bold text-teal-600' : '' }}">1. Company</span>
            <span class="{{ $step === 2 ? 'font-bold text-teal-600' : '' }}">2. Your Account</span>
            <span class="{{ $step === 3 ? 'font-bold text-teal-600' : '' }}">3. Plan</span>
        </div>

        @if ($step === 1)
            <h2 class="text-xl font-bold mb-4">Tell us about your company</h2>
            <div class="mb-4">
                <label class="block text-sm mb-1">Company name</label>
                <input type="text" wire:model="companyName" class="w-full border rounded p-2">
                @error('companyName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-1">Company size</label>
                <select wire:model="companySize" class="w-full border rounded p-2">
                    <option value="">Select...</option>
                    <option value="1-10">1–10 employees</option>
                    <option value="11-50">11–50 employees</option>
                    <option value="51-200">51–200 employees</option>
                    <option value="200+">200+ employees</option>
                </select>
                @error('companySize') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <button wire:click="nextStep" class="bg-teal-600 text-white px-4 py-2 rounded">Next</button>
        @endif

        @if ($step === 2)
            <h2 class="text-xl font-bold mb-4">Create your account</h2>
            <div class="mb-4">
                <label class="block text-sm mb-1">Your name</label>
                <input type="text" wire:model="adminName" class="w-full border rounded p-2">
                @error('adminName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-1">Email</label>
                <input type="email" wire:model="adminEmail" class="w-full border rounded p-2">
                @error('adminEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-1">Password</label>
                <input type="password" wire:model="adminPassword" class="w-full border rounded p-2">
                @error('adminPassword') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-1">Confirm password</label>
                <input type="password" wire:model="adminPassword_confirmation" class="w-full border rounded p-2">
            </div>
            <button wire:click="previousStep" class="px-4 py-2 rounded border mr-2">Back</button>
            <button wire:click="nextStep" class="bg-teal-600 text-white px-4 py-2 rounded">Next</button>
        @endif

        @if ($step === 3)
            <h2 class="text-xl font-bold mb-4">Choose a plan</h2>
            <div class="space-y-3 mb-4">
                <label class="flex items-center border rounded p-3">
                    <input type="radio" wire:model="plan" value="starter" class="mr-2">
                    Starter — Free
                </label>
                <label class="flex items-center border rounded p-3">
                    <input type="radio" wire:model="plan" value="growth" class="mr-2">
                    Growth — $49/mo
                </label>
                <label class="flex items-center border rounded p-3">
                    <input type="radio" wire:model="plan" value="enterprise" class="mr-2">
                    Enterprise — Contact us
                </label>
            </div>
            <button wire:click="previousStep" class="px-4 py-2 rounded border mr-2">Back</button>
            <button wire:click="submit" class="bg-teal-600 text-white px-4 py-2 rounded">Create workspace</button>
        @endif
    @endif
</div>
