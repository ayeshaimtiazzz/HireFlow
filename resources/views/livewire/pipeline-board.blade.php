<div>
    <div class="max-w-7xl mx-auto mt-6 px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ $jobPosting->title }} — Pipeline</h1>
            <a href="/jobs" class="text-sm text-gray-500">← All Jobs</a>
        </div>

        <div class="flex gap-4 overflow-x-auto pb-4">
            @foreach ($stages as $stage)
                <div class="bg-gray-100 rounded-lg p-3 w-64 flex-shrink-0">
                    <h3 class="font-semibold text-sm mb-3 flex justify-between">
                        {{ $stage->name }}
                        <span class="text-gray-400">{{ $applicationsByStage->get($stage->id, collect())->count() }}</span>
                    </h3>

                    <div
                        x-data
                        x-init="
                            new Sortable($el, {
                                group: 'pipeline',
                                animation: 150,
                                onAdd: (evt) => {
                                    $wire.moveApplication(evt.item.dataset.applicationId, {{ $stage->id }});
                                }
                            });
                        "
                        data-stage-id="{{ $stage->id }}"
                        class="space-y-2 min-h-[100px]"
                    >
                        @foreach ($applicationsByStage->get($stage->id, collect()) as $application)
                            <div
                                data-application-id="{{ $application->id }}"
                                class="bg-white p-3 rounded shadow cursor-move"
                            >
                                <a href="/candidates/{{ $application->id }}" class="block">
                                    <p class="font-medium text-sm">{{ $application->candidate->first_name }} {{ $application->candidate->last_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $application->candidate->email }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
