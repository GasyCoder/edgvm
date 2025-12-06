<div>
    @if (session()->has('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <input type="text" 
               wire:model.live.debounce.300ms="search" 
               placeholder="Rechercher une catégorie..."
               class="px-4 py-2 border rounded-lg">
        
        <button wire:click="create" 
                class="px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary font-bold">
            ➕ Nouvelle catégorie
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Couleur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Articles</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visible</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $category->nom }}</div>
                        @if($category->description)
                        <div class="text-xs text-gray-500">{{ Str::limit($category->description, 50) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block w-8 h-8 rounded" style="background-color: {{ $category->couleur }}"></span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $category->actualites_count ?? 0 }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded {{ $category->visible ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->visible ? 'Oui' : 'Non' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button wire:click="edit({{ $category->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                            Modifier
                        </button>
                        <button wire:click="delete({{ $category->id }})" 
                                onclick="return confirm('Supprimer cette catégorie ?')"
                                class="text-red-600 hover:text-red-900">
                            Supprimer
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $categories->links() }}
    </div>

    <!-- Modal Édition -->
    @if($editing)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="$set('editing', false)"></div>
            
            <div class="relative bg-white rounded-lg max-w-lg w-full p-6">
                <h3 class="text-lg font-bold mb-4">
                    {{ $categoryId ? 'Modifier' : 'Nouvelle' }} catégorie
                </h3>
                
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nom *</label>
                        <input type="text" wire:model="nom" class="w-full px-4 py-2 border rounded-lg" required>
                        @error('nom') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea wire:model="description" class="w-full px-4 py-2 border rounded-lg" rows="3"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Couleur</label>
                        <input type="color" wire:model="couleur" class="w-20 h-10 border rounded cursor-pointer">
                    </div>

                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="visible" class="rounded">
                            <span class="text-sm font-medium">Visible</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="$set('editing', false)" 
                                class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary">
                            {{ $categoryId ? 'Mettre à jour' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>