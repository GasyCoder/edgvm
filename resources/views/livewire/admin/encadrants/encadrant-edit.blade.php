<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.encadrants.show', $encadrant) }}" 
               class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Modifier l'encadrant</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Messages flash -->
            @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                {{ session('error') }}
            </div>
            @endif

            <form wire:submit.prevent="save">
                <div class="bg-white rounded-xl shadow-lg p-8 space-y-8">
                    
                    <!-- Section Compte Utilisateur -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Compte Utilisateur
                        </h3>

                        @if($has_account)
                            <!-- L'encadrant a déjà un compte -->
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200 mb-6">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-green-900">Compte utilisateur actif</p>
                                        <p class="text-sm text-green-700 mt-1">Cet encadrant possède un compte utilisateur actif.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nom complet -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                                        Nom complet <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name"
                                           wire:model="name"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('name') border-red-500 @enderror">
                                    @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email"
                                           wire:model="email"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('email') border-red-500 @enderror">
                                    @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <!-- L'encadrant n'a pas encore de compte -->
                            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 mb-6">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-yellow-900">Aucun compte utilisateur</p>
                                        <p class="text-sm text-yellow-700 mt-1">Cet encadrant n'a pas encore de compte utilisateur.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Option pour créer un compte -->
                            <div class="mb-6">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" 
                                           wire:model.live="create_account"
                                           class="w-5 h-5 text-ed-primary rounded focus:ring-2 focus:ring-ed-primary">
                                    <span class="text-sm font-semibold text-gray-700">
                                        Créer un compte utilisateur pour cet encadrant
                                    </span>
                                </label>
                            </div>

                            @if($create_account)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nom complet -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                                        Nom complet <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="name"
                                           wire:model="name"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('name') border-red-500 @enderror">
                                    @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email"
                                           wire:model="email"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('email') border-red-500 @enderror">
                                    @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-900">Compte créé automatiquement</p>
                                        <p class="text-sm text-blue-700 mt-1">
                                            Le compte sera créé avec le mot de passe par défaut : <code class="px-2 py-1 bg-blue-100 rounded font-mono text-xs">password123</code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>

                    <!-- Section Informations académiques -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Informations académiques
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Grade -->
                            <div>
                                <label for="grade" class="block text-sm font-bold text-gray-700 mb-2">
                                    Grade <span class="text-red-500">*</span>
                                </label>
                                <select id="grade"
                                        wire:model="grade"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('grade') border-red-500 @enderror">
                                    <option value="">-- Sélectionner un grade --</option>
                                    <option value="Professeur Titulaire">Professeur Titulaire</option>
                                    <option value="Maître de Conférences">Maître de Conférences</option>
                                    <option value="Maître Assistant">Maître Assistant</option>
                                    <option value="Assistant">Assistant</option>
                                    <option value="Docteur">Docteur</option>
                                </select>
                                @error('grade')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Spécialité -->
                            <div>
                                <label for="specialite" class="block text-sm font-bold text-gray-700 mb-2">
                                    Spécialité
                                </label>
                                <input type="text" 
                                       id="specialite"
                                       wire:model="specialite"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('specialite') border-red-500 @enderror"
                                       placeholder="Ex: Biochimie, Écologie...">
                                @error('specialite')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section Contact -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Informations de contact
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Téléphone -->
                            <div>
                                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">
                                    Téléphone
                                </label>
                                <input type="tel" 
                                       id="phone"
                                       wire:model="phone"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('phone') border-red-500 @enderror"
                                       placeholder="+261 32 00 000 00">
                                @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bureau -->
                            <div>
                                <label for="bureau" class="block text-sm font-bold text-gray-700 mb-2">
                                    Bureau
                                </label>
                                <input type="text" 
                                       id="bureau"
                                       wire:model="bureau"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('bureau') border-red-500 @enderror"
                                       placeholder="Ex: Bâtiment A, Bureau 201">
                                @error('bureau')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 pt-6">
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold shadow-lg">
                            Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.encadrants.show', $encadrant) }}" 
                           class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>