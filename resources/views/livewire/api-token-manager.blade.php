<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-6">API Tokens</h2>

    @if ($newToken)
        <div class="bg-yellow-50 border border-yellow-300 p-4 rounded mb-6">
            <p class="text-sm text-yellow-800 mb-2">Copy this token now — it won't be shown again:</p>
            <code class="block bg-white p-2 rounded text-xs break-all">{{ $newToken }}</code>
        </div>
    @endif

    <div class="flex gap-2 mb-6">
        <input type="text" wire:model="tokenName" placeholder="Token name (e.g. 'Zapier integration')" class="flex-1 border rounded p-2">
        <button wire:click="generateToken" class="bg-teal-600 text-white px-4 py-2 rounded">Generate</button>
    </div>
    @error('tokenName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b text-gray-500">
                <th class="py-2">Name</th>
                <th class="py-2">Created</th>
                <th class="py-2"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tokens as $token)
                <tr class="border-b">
                    <td class="py-2">{{ $token->name }}</td>
                    <td class="py-2 text-gray-400">{{ $token->created_at->diffForHumans() }}</td>
                    <td class="py-2">
                        <button wire:click="revoke({{ $token->id }})" class="text-red-500 text-xs">Revoke</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
