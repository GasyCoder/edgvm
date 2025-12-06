<div>
    @if (session()->has('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Total abonnés</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">✅ Actifs</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['actifs'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">❌ Inactifs</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['inactifs'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">🎓 Doctorants</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['doctorants'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">👨‍🏫 Encadrants</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['encadrants'] }}</p>
        </div>
    </div>

    <!-- Header avec bouton Ajouter -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-4 flex-1">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Rechercher par email ou nom..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary">
            
            <select wire:model.live="filterType" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary">
                <option value="all">Tous types</option>
                <option value="doctorant">Doctorants</option>
                <option value="encadrant">Encadrants</option>
                <option value="autre">Autres</option>
            </select>

            <select wire:model.live="filterActif" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary">
                <option value="all">Tous statuts</option>
                <option value="actif">Actifs</option>
                <option value="inactif">Inactifs</option>
            </select>
        </div>

        <button wire:click="create" 
                class="ml-4 px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary font-bold whitespace-nowrap">
            ➕ Ajouter un abonné
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscription</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($subscribers as $subscriber)
            <tr class="hover:bg-gray-50 {{ !$subscriber->actif ? 'bg-red-50' : '' }}">
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $subscriber->email }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ $subscriber->nom ?? '-' }}
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                        @if($subscriber->type === 'doctorant') bg-blue-100 text-blue-800
                        @elseif($subscriber->type === 'encadrant') bg-purple-100 text-purple-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($subscriber->type) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ $subscriber->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4">
                    <div class="space-y-1">
                        <!-- Toggle actif/inactif -->
                        <div class="flex items-center gap-2">
                            <button wire:click="toggleActif({{ $subscriber->id }})"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                                        {{ $subscriber->actif ? 'bg-green-600' : 'bg-red-600' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                            {{ $subscriber->actif ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                            <span class="text-xs font-semibold {{ $subscriber->actif ? 'text-green-600' : 'text-red-600' }}">
                                {{ $subscriber->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                        
                        <!-- Date de désinscription si existe -->
                        @if(!$subscriber->actif && $subscriber->desabonne_le)
                        <div class="text-xs text-red-600">
                            Désabonné le {{ $subscriber->desabonne_le->format('d/m/Y') }}
                        </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 text-right text-sm">
                    <div class="flex justify-end gap-2">
                        <button wire:click="edit({{ $subscriber->id }})"
                                class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                title="Modifier">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button wire:click="delete({{ $subscriber->id }})"
                                onclick="return confirm('Supprimer cet abonné ?')"
                                class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition"
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
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="text-gray-400">
                            <svg class="mx-auto h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-gray-500">Aucun abonné trouvé</p>
                            <button wire:click="create" 
                                    class="mt-4 px-4 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary">
                                Ajouter le premier abonné
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $subscribers->links() }}
    </div>

    <!-- Modal Création/Édition -->
    @if($editing)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 wire:click="$set('editing', false)"></div>
            
            <div class="relative bg-white rounded-lg max-w-lg w-full shadow-xl">
                <!-- Header -->
                <div class="bg-gradient-to-r from-ed-primary to-ed-secondary px-6 py-4 rounded-t-lg">
                    <h3 class="text-lg font-bold text-white">
                        {{ $subscriberId ? '✏️ Modifier l\'abonné' : '➕ Nouvel abonné' }}
                    </h3>
                </div>
                
                <!-- Body -->
                <form wire:submit="save" class="p-6 space-y-4">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               wire:model="email"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="exemple@email.com"
                               required>
                        @error('email') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Nom -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nom complet <span class="text-gray-500 text-xs">(optionnel)</span>
                        </label>
                        <input type="text" 
                               wire:model="nom"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="Prénom Nom">
                        @error('nom') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                required>
                            <option value="doctorant">🎓 Doctorant</option>
                            <option value="encadrant">👨‍🏫 Encadrant</option>
                            <option value="autre">👤 Autre</option>
                        </select>
                        @error('type') 
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Actif -->
                    <div class="pt-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" 
                                   wire:model="actif"
                                   class="w-5 h-5 text-ed-primary rounded focus:ring-2 focus:ring-ed-primary">
                            <span class="text-sm font-medium text-gray-700">
                                Abonnement actif
                            </span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500 ml-8">
                            Les abonnés inactifs ne recevront pas les notifications
                        </p>
                    </div>

                    <!-- Info -->
                    @if($subscriberId && $subscriberCreatedAt)
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-xs text-gray-500">
                            <strong>Inscrit le :</strong> 
                            {{ $subscriberCreatedAt->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    @endif

                    <!-- Boutons -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" 
                                wire:click="$set('editing', false)" 
                                class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold">
                            {{ $subscriberId ? '💾 Mettre à jour' : '✅ Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>