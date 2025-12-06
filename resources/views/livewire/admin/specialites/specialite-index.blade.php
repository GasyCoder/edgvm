<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Spécialités</h2>
            <a href="{{ route('admin.specialites.create') }}" 
               class="px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold shadow-lg">
                ➕ Nouvelle spécialité
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-10xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Messages flash -->
            @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                {{ session('error') }}
            </div>
            @endif

            <!-- Filtres -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Recherche -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">🔍 Recherche</label>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Nom, description..."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                    </div>

                    <!-- Filtre EAD -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">📚 EAD</label>
                        <select wire:model.live="ead_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            <option value="">Toutes les EAD</option>
                            @foreach($eads as $ead)
                            <option value="{{ $ead->id }}">{{ $ead->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($search || $ead_id)
                <div class="mt-4">
                    <button wire:click="$set('search', ''); $set('ead_id', '')" 
                            class="text-sm text-red-600 hover:text-red-800 font-semibold">
                        Effacer les filtres
                    </button>
                </div>
                @endif
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Nom
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                EAD
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Description
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Thèses
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($specialites as $specialite)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $specialite->nom }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($specialite->ead)
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold">
                                    {{ $specialite->ead->nom }}
                                </span>
                                @else
                                <span class="text-gray-400 text-sm">Aucune</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 line-clamp-2">{{ $specialite->description }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm text-gray-900">
                                    <span class="font-semibold">{{ $specialite->theses_en_cours }}</span> en cours
                                    <span class="text-gray-400 mx-1">|</span>
                                    <span class="font-semibold">{{ $specialite->theses_soutenues }}</span> soutenues
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Bouton Modifier -->
                                    <a href="{{ route('admin.specialites.edit', $specialite) }}" 
                                    class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition"
                                    title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Bouton Supprimer -->
                                    <button wire:click="confirmDelete({{ $specialite->id }})" 
                                            class="inline-flex items-center justify-center w-9 h-9 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition"
                                            title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Aucune spécialité trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50">
                    {{ $specialites->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation suppression -->
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4">⚠️ Confirmer la suppression</h3>
            <p class="text-gray-600 mb-6">
                Êtes-vous sûr de vouloir supprimer cette spécialité ? Cette action est irréversible.
            </p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete" 
                        class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Annuler
                </button>
                <button wire:click="delete" 
                        class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
    @endif
</div>