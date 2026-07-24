<div>
    <h2 class="font-semibold mb-3">Activity</h2>
    @if ($events->isEmpty())
        <p class="text-sm text-gray-400">No activity yet.</p>
    @else
        <div class="space-y-2">
            @foreach ($events as $event)
                <div class="text-sm flex justify-between border-b pb-2">
                    <span>{{ $event['text'] }}</span>
                    <span class="text-gray-400">{{ $event['by'] }} · {{ $event['at']?->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
