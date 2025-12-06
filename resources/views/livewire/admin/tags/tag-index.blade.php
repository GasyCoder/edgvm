<div>
    @if (session()->has('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <input type="text" 
               wire:model.live.debounce.300ms="search" 
               placeholder="Rechercher un tag..."
               class="px-4 py-2 border rounded-lg">
        
        <button wire:click="create" 
                class="px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary font-bold">
            ➕ Nouveau tag
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Articles</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($tags as $tag)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $tag->nom }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $tag->slug }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $tag->actualites_count }}</td>
                    <td class="px-6 py-4 text-right">
                        <button wire:click="edit({{ $tag->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                            Modifier
                        </button>
                        <button wire:click="delete({{ $tag->id }})" 
                                onclick="return confirm('Supprimer ce tag ?')"
                                class="text-red-600 hover:text-red-900">
                            Supprimer
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $tags->links() }}</div>

    @if($editing)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="$set('editing', false)"></div>
            
            <div class="relative bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-bold mb-4">{{ $tagId ? 'Modifier' : 'Nouveau' }} tag</h3>
                
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nom *</label>
                        <input type="text" wire:model="nom" class="w-full px-4 py-2 border rounded-lg" required>
                        @error('nom') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="$set('editing', false)" 
                                class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary">
                            {{ $tagId ? 'Mettre à jour' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>