<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Membres du jury</h2>

            <a href="{{ route('admin.jurys.create') }}"
               class="px-5 py-2.5 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-semibold shadow">
                + Nouveau membre
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Messages flash --}}
            @if(session()->has('success'))
                <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Barre de recherche --}}
            <div class="bg-white rounded-xl shadow p-4 flex items-center gap-3">
                <input type="text"
                       wire:model.debounce.500ms="search"
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                       placeholder="Rechercher par nom, université, grade...">
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Grade</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Université</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Contact</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($jurys as $jury)
                        <tr>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                {{ $jury->nom }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $jury->grade ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $jury->universite ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                @if($jury->email)
                                    <div>{{ $jury->email }}</div>
                                @endif
                                @if($jury->phone)
                                    <div class="text-xs text-gray-500">{{ $jury->phone }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.jurys.edit', $jury) }}"
                                       class="px-3 py-1.5 text-xs bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100">
                                        Modifier
                                    </a>
                                    <button type="button"
                                            onclick="confirm('Supprimer ce membre du jury ?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $jury->id }})"
                                            class="px-3 py-1.5 text-xs bg-red-50 text-red-700 rounded-lg hover:bg-red-100">
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                Aucun membre du jury enregistré.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="px-4 py-3 border-t bg-gray-50">
                    {{ $jurys->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
