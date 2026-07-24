<div class="max-w-md mx-auto mt-16 bg-white p-8 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Join your team on HireFlow</h2>
    <p class="text-gray-500 mb-4">Invited as: {{ str_replace('_', ' ', $role) }} ({{ $email }})</p>
    <div class="mb-4">
        <label class="block text-sm mb-1">Your name</label>
        <input type="text" wire:model="name" class="w-full border rounded p-2">
        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div class="mb-4">
        <label class="block text-sm mb-1">Password</label>
        <input type="password" wire:model="password" class="w-full border rounded p-2">
        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div class="mb-4">
        <label class="block text-sm mb-1">Confirm password</label>
        <input type="password" wire:model="passwordConfirmation" class="w-full border rounded p-2">
    </div>
    <button wire:click="joinTeam" class="bg-teal-600 text-white px-4 py-2 rounded">Join team</button>
</div>
