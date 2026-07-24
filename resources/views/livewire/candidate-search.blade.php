<div class="max-w-xl mx-auto mt-10">
    <input
        type="text"
        wire:model.live.debounce.300ms="query"
        placeholder="Search candidates by name, email, or skill..."
        class="w-full border rounded p-3"
    >

    @if ($query && strlen($query) >= 2)
        <div class="bg-white rounded shadow mt-2 divide-y">
            @forelse ($results as $candidate)
                @if ($candidate->application_id)
                    <a href="/candidates/{{ $candidate->application_id }}" class="block p-3 text-sm hover:bg-gray-50">
                        <p class="font-medium">{{ $candidate->first_name }} {{ $candidate->last_name }}</p>
                        <p class="text-gray-400">{{ $candidate->email }}</p>
                    </a>
                @else
                    <div class="p-3 text-sm text-gray-400">
                        {{ $candidate->first_name }} {{ $candidate->last_name }} (no application on file)
                    </div>
                @endif
            @empty
                <p class="p-3 text-sm text-gray-400">No results found.</p>
            @endforelse
        </div>
    @endif
</div>
