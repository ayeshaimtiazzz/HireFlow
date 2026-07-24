<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
    @if ($submitted)
        <div class="text-center">
            <h2 class="text-2xl font-bold text-teal-700 mb-2">Application submitted!</h2>
            <p class="text-gray-500">We'll be in touch about the {{ $jobPosting->title }} role.</p>
        </div>
    @else
        <h2 class="text-xl font-bold mb-1">Apply for {{ $jobPosting->title }}</h2>
        <p class="text-gray-500 text-sm mb-6">{{ $jobPosting->location }}</p>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm mb-1">First name</label>
                <input type="text" wire:model="firstName" class="w-full border rounded p-2">
                @error('firstName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm mb-1">Last name</label>
                <input type="text" wire:model="lastName" class="w-full border rounded p-2">
                @error('lastName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">Email</label>
            <input type="email" wire:model="email" class="w-full border rounded p-2">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">Phone (optional)</label>
            <input type="text" wire:model="phone" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">LinkedIn (optional)</label>
            <input type="text" wire:model="linkedinUrl" class="w-full border rounded p-2">
        </div>

        <div class="mb-6">
            <label class="block text-sm mb-1">Resume (PDF or DOCX, max 5MB)</label>
            <input type="file" wire:model="resume" class="w-full border rounded p-2">
            @error('resume') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            <div wire:loading wire:target="resume" class="text-sm text-gray-400 mt-1">Uploading...</div>
        </div>

        <button wire:click="submitApplication" class="bg-teal-600 text-white px-4 py-2 rounded w-full">
            Submit application
        </button>
    @endif
</div>
