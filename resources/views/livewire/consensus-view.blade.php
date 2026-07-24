<div class="max-w-3xl mx-auto mt-10 px-4">
    <a href="/candidates/{{ $application->id }}" class="text-sm text-gray-500">← Back to profile</a>

    <div class="bg-white rounded-lg shadow p-6 mt-4">
        <h1 class="text-xl font-bold mb-1">
            Consensus — {{ $application->candidate->first_name }} {{ $application->candidate->last_name }}
        </h1>
        <p class="text-gray-500 text-sm mb-6">{{ $scorecards->count() }} scorecard(s) submitted</p>

        @if ($scorecards->isEmpty())
            <p class="text-gray-400 text-sm">No scorecards submitted yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2 pr-4">Criterion</th>
                            @foreach ($scorecards as $scorecard)
                                <th class="py-2 pr-4">{{ $scorecard->submittedBy->name }}</th>
                            @endforeach
                            <th class="py-2">Average</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($criteriaNames as $criterion)
                            <tr class="border-b">
                                <td class="py-2 pr-4 font-medium">{{ $criterion }}</td>
                                @foreach ($scorecards as $scorecard)
                                    <td class="py-2 pr-4">{{ $scorecard->ratings[$criterion] ?? '—' }}</td>
                                @endforeach
                                <td class="py-2">
                                    @php
                                        $values = $scorecards->pluck("ratings.$criterion")->filter(fn($v) => is_numeric($v));
                                    @endphp
                                    {{ $values->count() ? round($values->avg(), 1) : '—' }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="py-2 pr-4 font-medium">Decision</td>
                            @foreach ($scorecards as $scorecard)
                                <td class="py-2 pr-4">
                                    <span class="px-2 py-1 rounded text-xs
                                        {{ $scorecard->decision === 'proceed' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $scorecard->decision === 'reject' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $scorecard->decision === 'undecided' ? 'bg-gray-100 text-gray-600' : '' }}">
                                        {{ ucfirst($scorecard->decision) }}
                                    </span>
                                </td>
                            @endforeach
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
