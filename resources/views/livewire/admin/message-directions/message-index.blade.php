<div class="space-y-6">
    {{-- Flash messages --}}
    @if (session('success'))
        <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Nom</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Fonction</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Email</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Visible</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($messages as $message)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $message->nom }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $message->fonction ?: '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $message->email ?: '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button
                                wire:click="toggleVisibility({{ $message->id }})"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                       {{ $message->visible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $message->visible ? 'Affiché' : 'Masqué' }}
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.message-directions.edit', $message) }}"
                                class="px-3 py-1 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                    Éditer
                                </a>

                                <button
                                    wire:click="delete({{ $message->id }})"
                                    wire:confirm="Supprimer ce message ?"
                                    class="px-3 py-1 rounded-lg border border-red-200 text-xs font-medium text-red-600 hover:bg-red-50">
                                    Supprimer
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Aucun message enregistré pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3 border-t border-gray-100">
            {{ $messages->links() }}
        </div>
    </div>
</div>
