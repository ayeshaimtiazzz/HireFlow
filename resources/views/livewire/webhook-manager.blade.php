<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6">Webhooks</h2>

    <div class="border-b pb-6 mb-6">
        <input type="text" wire:model="url" placeholder="https://your-service.com/webhook" class="w-full border rounded p-2 mb-3">
        @error('url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <div class="flex gap-4 mb-3">
            @foreach ($availableEvents as $event)
                <label class="flex items-center text-sm">
                    <input type="checkbox" wire:model="selectedEvents" value="{{ $event }}" class="mr-2">
                    {{ $event }}
                </label>
            @endforeach
        </div>
        <button wire:click="create" class="bg-teal-600 text-white px-4 py-2 rounded">Add webhook</button>
    </div>

    @foreach ($webhooks as $webhook)
        <div class="flex justify-between items-center border-b py-3 text-sm">
            <div>
                <p class="font-medium">{{ $webhook->url }}</p>
                <p class="text-gray-400 text-xs">{{ implode(', ', $webhook->events) }}</p>
            </div>
            <button wire:click="toggle({{ $webhook->id }})" class="text-xs {{ $webhook->is_active ? 'text-green-600' : 'text-gray-400' }}">
                {{ $webhook->is_active ? 'Active' : 'Paused' }}
            </button>
        </div>
    @endforeach
</div>
