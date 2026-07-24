<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6">{{ $jobPosting ? 'Edit' : 'Create' }} Job Posting</h2>

    <div class="mb-4">
        <label class="block text-sm mb-1">Title</label>
        <input type="text" wire:model="title" class="w-full border rounded p-2">
        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-1">Description</label>
        <textarea wire:model="description" rows="6" class="w-full border rounded p-2"></textarea>
        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm mb-1">Employment type</label>
            <select wire:model="employmentType" class="w-full border rounded p-2">
                <option value="full-time">Full-time</option>
                <option value="part-time">Part-time</option>
                <option value="contract">Contract</option>
            </select>
        </div>
        <div>
            <label class="block text-sm mb-1">Location type</label>
            <select wire:model="locationType" class="w-full border rounded p-2">
                <option value="onsite">Onsite</option>
                <option value="remote">Remote</option>
                <option value="hybrid">Hybrid</option>
            </select>
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-1">Location</label>
        <input type="text" wire:model="location" class="w-full border rounded p-2" placeholder="Islamabad, Pakistan">
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm mb-1">Salary min</label>
            <input type="number" wire:model="salaryMin" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block text-sm mb-1">Salary max</label>
            <input type="number" wire:model="salaryMax" class="w-full border rounded p-2">
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-1">Skills (comma-separated)</label>
        <input type="text" wire:model="skillsInput" class="w-full border rounded p-2" placeholder="PHP, Laravel, MySQL">
    </div>

    <div class="mb-6">
        <label class="block text-sm mb-1">Status</label>
        <select wire:model="status" class="w-full border rounded p-2">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
            <option value="closed">Closed</option>
            <option value="archived">Archived</option>
        </select>
    </div>

    <button wire:click="save" class="bg-teal-600 text-white px-4 py-2 rounded">Save job posting</button>
</div>
