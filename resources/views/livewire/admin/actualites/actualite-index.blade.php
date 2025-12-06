<div>
    <!-- Messages Flash -->
    @if (session()->has('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Total</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Visibles</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['visibles'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Cachées</p>
            <p class="text-2xl font-bold text-gray-600">{{ $stats['cachees'] }}</p>
        </div>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6 gap-4">
        <div class="flex-1 max-w-md">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Rechercher une actualité..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary">
        </div>
        
        <select wire:model.live="filterVisible" 
                class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="all">Toutes</option>
            <option value="visible">Visibles</option>
            <option value="hidden">Cachées</option>
        </select>

        <a href="{{ route('admin.actualites.create') }}" 
           class="px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary font-bold">
            ➕ Nouvelle actualité
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th> <!-- ← AJOUTÉ -->
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auteur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visible</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($actualites as $actualite)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        @if($actualite->image)
                            <img src="{{ $actualite->image->url }}" class="w-16 h-16 object-cover rounded">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $actualite->titre }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit(strip_tags($actualite->contenu), 60) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($actualite->category)
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full text-white"
                                style="background-color: {{ $actualite->category->couleur }}">
                                {{ $actualite->category->nom }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">Aucune</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $actualite->auteur?->name ?? 'Non défini' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $actualite->date_publication?->format('d/m/Y') ?? 'Non définie' }}
                    </td>
                    <td class="px-6 py-4">
                        <button wire:click="toggleVisibility({{ $actualite->id }})"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                       {{ $actualite->visible ? 'bg-ed-primary' : 'bg-gray-200' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                         {{ $actualite->visible ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </td>
                    <td class="px-6 py-4 text-right text-sm">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.actualites.edit', $actualite) }}"
                               class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button wire:click="confirmDelete({{ $actualite->id }})"
                                    class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        Aucune actualité trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $actualites->links() }}
    </div>

    <!-- Modal suppression -->
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="$set('confirmingDeletion', false)"></div>
            
            <div class="relative bg-white rounded-lg max-w-lg w-full p-6">
                <h3 class="text-lg font-bold mb-4">Confirmer la suppression</h3>
                <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cette actualité ? Cette action est irréversible.</p>
                
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('confirmingDeletion', false)"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                        Annuler
                    </button>
                    <button wire:click="deleteActualite"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>