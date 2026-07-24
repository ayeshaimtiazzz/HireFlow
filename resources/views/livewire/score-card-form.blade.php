<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
    @if ($submitted)
        <div class="text-center">
            <h2 class="text-xl font-bold text-teal-700 mb-2">Scorecard submitted!</h2>
            <a href="/candidates/{{ $application->id }}" class="text-teal-600 underline text-sm">
                ← Back to candidate profile
            </a>
        </div>
    @else
        <h2 class="text-xl font-bold mb-1">Scorecard for {{ $application->candidate->first_name }} {{ $application->candidate->last_name }}</h2>
        <p class="text-gray-500 text-sm mb-6">{{ $application->jobPosting->title }}</p>

        @foreach ($template->criteria as $criterion)
            <div class="mb-4">
                <label class="block text-sm mb-1">{{ $criterion['name'] }}</label>
                @if ($criterion['type'] === 'rating')
                    <div class="flex gap-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                wire:click="$set('ratings.{{ $criterion['name'] }}', {{ $i }})"
                                class="w-10 h-10 rounded border {{ ($ratings[$criterion['name']] ?? null) == $i ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-gray-500' }}"
                            >
                                {{ $i }}
                            </button>
                        @endfor
                    </div>
                @else
                    <textarea wire:model="ratings.{{ $criterion['name'] }}" rows="3" class="w-full border rounded p-2"></textarea>
                @endif
            </div>
        @endforeach

        <div class="mb-6">
            <label class="block text-sm mb-1">Decision</label>
            <select wire:model="decision" class="w-full border rounded p-2">
                <option value="undecided">Undecided</option>
                <option value="proceed">Proceed</option>
                <option value="reject">Reject</option>
            </select>
        </div>

        <button wire:click="submit" class="bg-teal-600 text-white px-4 py-2 rounded w-full">
            Submit scorecard
        </button>
    @endif
</div>
