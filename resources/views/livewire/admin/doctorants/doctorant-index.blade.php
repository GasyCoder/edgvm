<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Doctorants</h2>
            <div class="flex gap-3">
                <!-- Exporter -->
                <a href="{{ route('admin.doctorants.export') }}" 
                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exporter Excel
                </a>
                
                <!-- Importer -->
                <!-- Importer (sans modal) -->
                <form action="{{ route('admin.doctorants.import') }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                    class="flex items-center gap-2"
                    id="doctorants-import-form">
                    @csrf

                    {{-- Input fichier caché --}}
                    <input type="file"
                        name="import_file"
                        id="import_file"
                        accept=".xlsx,.xls"
                        class="hidden"
                        onchange="
                                const fileInput = this;
                                const fileNameSpan = document.getElementById('import-file-name');
                                const submitBtn = document.getElementById('import-submit-btn');
                                if (fileInput.files.length > 0) {
                                    fileNameSpan.textContent = fileInput.files[0].name;
                                    submitBtn.classList.remove('hidden');
                                } else {
                                    fileNameSpan.textContent = '';
                                    submitBtn.classList.add('hidden');
                                }
                        ">

                    {{-- Bouton qui ouvre le sélecteur de fichier --}}
                    <label for="import_file"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-lg flex items-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Importer Excel
                    </label>

                    {{-- Nom du fichier sélectionné --}}
                    <span id="import-file-name" class="max-w-[180px] truncate text-sm text-gray-700"></span>

                    {{-- Bouton Importer qui apparaît après sélection --}}
                    <button type="submit"
                            id="import-submit-btn"
                            class="hidden px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm font-semibold">
                        Importer
                    </button>
                </form>

                
                <!-- Nouveau -->
                <a href="{{ route('admin.doctorants.create') }}" 
                class="px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau Doctorant
                </a>
            </div>
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
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Recherche -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">🔍 Recherche</label>
                        <input type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Nom, email, matricule..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                    </div>

                    <!-- EAD -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">🏢 EAD</label>
                        <select wire:model.live="ead_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            <option value="">Toutes les EAD</option>
                            @foreach($eads as $ead)
                            <option value="{{ $ead->id }}">{{ $ead->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Compte utilisateur -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">👤 Compte</label>
                        <select wire:model.live="has_account"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            <option value="">Tous</option>
                            <option value="yes">Avec compte</option>
                            <option value="no">Sans compte</option>
                        </select>
                    </div>

                    <!-- Statut -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">📊 Statut</label>
                        <select wire:model.live="statut"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="actif">Actif</option>
                            <option value="diplome">Diplômé</option>
                            <option value="suspendu">Suspendu</option>
                            <option value="abandonne">Abandonné</option>
                        </select>
                    </div>
                </div>

                @if($search || $statut || $ead_id || $has_account)
                <div class="mt-4">
                    <button wire:click="$set('search', ''); $set('statut', ''); $set('ead_id', ''); $set('has_account', '')" 
                            class="text-sm text-red-600 hover:text-red-800 font-semibold">
                        Réinitialiser les filtres
                    </button>
                </div>
                @endif
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Doctorant
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Matricule
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Niveau
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Directeur
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    EAD
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Compte
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($doctorants as $doctorant)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-ed-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-ed-primary" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                 {{ $doctorant->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $doctorant->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-mono text-gray-900">{{ $doctorant->matricule }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold">
                                        {{ $doctorant->niveau }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($doctorant->directeur && $doctorant->directeur->user)
                                    <div class="text-sm text-gray-900">{{ $doctorant->directeur->user->name }}</div>
                                    @else
                                    <span class="text-gray-400 text-sm">Non défini</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($doctorant->ead)
                                    <span class="text-sm text-gray-900">{{ Str::limit($doctorant->ead->nom, 30) }}</span>
                                    @else
                                    <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($doctorant->hasUser())
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Actif
                                    </span>
                                    @else
                                    <button wire:click="openCreateAccountModal({{ $doctorant->id }})"
                                            class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold hover:bg-orange-200 transition">
                                        Créer compte
                                    </button>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($doctorant->statut === 'actif')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">
                                        Actif
                                    </span>
                                    @elseif($doctorant->statut === 'diplome')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">
                                        Diplômé
                                    </span>
                                    @elseif($doctorant->statut === 'suspendu')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold">
                                        Suspendu
                                    </span>
                                    @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold">
                                        Abandonné
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">

                                        <!-- Voir -->
                                        <a href="{{ route('admin.doctorants.show', $doctorant) }}" 
                                        class="inline-flex items-center justify-center w-9 h-9 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition"
                                        title="Voir le profil">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Modifier -->
                                        <a href="{{ route('admin.doctorants.edit', $doctorant) }}" 
                                        class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition"
                                        title="Modifier">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Supprimer -->
                                        <button wire:click="confirmDelete({{ $doctorant->id }})" 
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
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <p class="text-lg font-medium">Aucun doctorant trouvé</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50">
                    {{ $doctorants->links() }}
                </div>
            </div>
        </div>
    </div>


    <!-- Modal suppression -->
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Confirmer la suppression</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Êtes-vous sûr de vouloir supprimer ce doctorant ? Cette action est irréversible.
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
    
    <!-- Modal Créer Compte -->
    @if($showCreateAccountModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Créer un compte utilisateur</h3>
            </div>
            
            <p class="text-gray-600 mb-4">
                Ce doctorant pourra se connecter avec son email et le mot de passe défini.
            </p>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Mot de passe <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                    wire:model="accountPassword"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary"
                    placeholder="Minimum 8 caractères">
                @error('accountPassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button wire:click="closeCreateAccountModal" 
                        class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Annuler
                </button>
                <button wire:click="createUserAccount" 
                        class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Créer le compte</span>
                    <span wire:loading>Création...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>