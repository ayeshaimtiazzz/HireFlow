<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
    <a href="/candidates/{{ $application->id }}" class="text-sm text-gray-500">← Back to profile</a>

    <h2 class="text-xl font-bold mt-4 mb-6">Interview Slots</h2>

    <div class="border-b pb-6 mb-6">
        <h3 class="font-semibold mb-3">Add a time slot</h3>
        <div class="grid grid-cols-3 gap-2 mb-3">
            <input type="date" wire:model="date" class="border rounded p-2">
            <input type="time" wire:model="time" class="border rounded p-2">
            <select wire:model="durationMinutes" class="border rounded p-2">
                <option value="15">15 min</option>
                <option value="30">30 min</option>
                <option value="60">60 min</option>
            </select>
        </div>
        <input type="text" wire:model="location" placeholder="Location or meeting link" class="w-full border rounded p-2 mb-3">
        <button wire:click="createSlot" class="bg-teal-600 text-white px-4 py-2 rounded">Add slot</button>
        @error('date') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-6">
        <h3 class="font-semibold mb-3">Open slots</h3>
        @forelse ($slots as $slot)
            <div class="flex justify-between items-center border-b py-2 text-sm">
                <span>{{ $slot->starts_at->format('M j, g:i A') }} ({{ $slot->duration_minutes }} min)</span>
                <span class="text-gray-400">{{ $slot->location }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-400">No open slots yet.</p>
        @endforelse
    </div>

    <div class="bg-gray-50 p-4 rounded">
        <p class="text-sm text-gray-600 mb-2">Share this link with the candidate to let them pick a slot:</p>
        <div class="flex gap-2">
            <input type="text" readonly value="{{ $this->getBookingUrl() }}" class="flex-1 border rounded p-2 text-xs bg-white">
        </div>
    </div>
</div>
