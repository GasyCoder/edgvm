<div>
    <!-- Messages Flash -->
    @if (session()->has('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Header avec recherche et boutons -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="flex-1 max-w-md w-full">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Rechercher un média..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
        </div>
        
        <div class="flex gap-3 flex-wrap">
            <!-- Filtres par type -->
            <select wire:model.live="typeFilter" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                <option value="all">Tous les types</option>
                <option value="image">Images</option>
                <option value="document">Documents</option>
                <option value="video">Vidéos</option>
            </select>

            <!-- Toggle vue -->
            <button wire:click="toggleViewMode" 
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                @if($viewMode === 'grid')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                    </svg>
                @endif
            </button>
            
            <a href="{{ route('admin.media.upload') }}" 
               class="inline-flex items-center gap-2 px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Uploader
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Images</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['images'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Documents</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['documents'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Vidéos</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['videos'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Taille totale</p>
            <p class="text-xl font-bold text-gray-900">{{ number_format($stats['taille_totale'] / 1024 / 1024, 2) }} MB</p>
        </div>
    </div>

    <!-- Galerie / Liste -->
    @if($viewMode === 'grid')
        <!-- Vue Grille -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @forelse($medias as $media)
            <div class="bg-white rounded-lg shadow-md overflow-hidden group relative">
                <!-- Image de prévisualisation -->
                <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
                    @if($media->type === 'image')
                        <img src="{{ $media->url }}" 
                             alt="{{ $media->nom_original }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    @else
                        <div class="flex flex-col items-center justify-center p-4">
                            @if($media->type === 'document')
                                <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @elseif($media->type === 'video')
                                <svg class="w-16 h-16 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                            <p class="text-xs text-gray-500 mt-2 text-center truncate w-full px-2">{{ $media->nom_original }}</p>
                        </div>
                    @endif
                </div>

                <!-- Actions au survol -->
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                    <a href="{{ $media->url }}" target="_blank"
                       class="p-2 bg-white rounded-full hover:bg-gray-100 transition" title="Voir">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    <button wire:click="confirmDelete({{ $media->id }})"
                            class="p-2 bg-red-600 rounded-full hover:bg-red-700 transition" title="Supprimer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>

                <!-- Info -->
                <div class="p-2 border-t">
                    <p class="text-xs text-gray-600 truncate" title="{{ $media->nom_original }}">{{ $media->nom_original }}</p>
                    <p class="text-xs text-gray-400">{{ number_format($media->taille_bytes / 1024, 2) }} KB</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
                <p class="mt-2 text-gray-500">Aucun média trouvé</p>
                <a href="{{ route('admin.media.upload') }}" 
                   class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition">
                    Uploader le premier média
                </a>
            </div>
            @endforelse
        </div>
    @else
        <!-- Vue Liste -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aperçu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Taille</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($medias as $media)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($media->type === 'image')
                                <img src="{{ $media->url }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $media->nom_original }}</div>
                            <div class="text-xs text-gray-500">{{ $media->nom_fichier }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded
                                @if($media->type === 'image') bg-blue-100 text-blue-800
                                @elseif($media->type === 'document') bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($media->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($media->taille_bytes / 1024, 2) }} KB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $media->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ $media->url }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <button wire:click="confirmDelete({{ $media->id }})"
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
                            Aucun média trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    <!-- Pagination -->
    <div class="mt-6">
        {{ $medias->links() }}
    </div>

    <!-- Modal de confirmation de suppression -->
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('confirmingDeletion', false)"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmer la suppression</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer ce média ? Le fichier sera définitivement supprimé du serveur. Cette action est irréversible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="deleteMedia" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Supprimer
                    </button>
                    <button wire:click="$set('confirmingDeletion', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>