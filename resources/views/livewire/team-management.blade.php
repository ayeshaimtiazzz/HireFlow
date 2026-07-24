<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6">Team Management</h2>

    @if ($inviteSent)
        <div class="bg-green-50 text-green-700 p-3 rounded mb-4">
            Invitation sent! It'll appear in the mail log for now (no real email server set up yet).
        </div>
    @endif

    <div class="mb-8 border-b pb-6">
        <h3 class="font-semibold mb-3">Invite a teammate</h3>
        <div class="flex gap-2 mb-2">
            <input type="email" wire:model="inviteEmail" placeholder="teammate@email.com"
                   class="flex-1 border rounded p-2">
            <select wire:model="inviteRole" class="border rounded p-2">
                <option value="recruiter">Recruiter</option>
                <option value="hiring_manager">Hiring Manager</option>
                <option value="company_admin">Company Admin</option>
            </select>
            <button wire:click="invite" class="bg-teal-600 text-white px-4 py-2 rounded">Invite</button>
        </div>
        @error('inviteEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <h3 class="font-semibold mb-3">Current team</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="py-2">Name</th>
                    <th class="py-2">Email</th>
                    <th class="py-2">Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teamMembers as $member)
                    <tr class="border-b">
                        <td class="py-2">{{ $member->name }}</td>
                        <td class="py-2">{{ $member->email }}</td>
                        <td class="py-2">{{ $member->getRoleNames()->first() ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
