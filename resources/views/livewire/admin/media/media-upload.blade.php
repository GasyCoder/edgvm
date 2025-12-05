<div>
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-gray-600">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-ed-primary">Dashboard</a></li>
            <li>/</li>
            <li><a href="{{ route('admin.media.index') }}" class="hover:text-ed-primary">Médiathèque</a></li>
            <li>/</li>
            <li class="text-gray-900 font-semibold">Upload</li>
        </ol>
    </nav>

    <!-- Messages Flash -->
    @if (session()->has('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Zone d'upload principale -->
        <div class="lg:col-span-2">
            
            <!-- Zone Drag & Drop -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Sélectionner des fichiers</h3>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center hover:border-ed-primary transition"
                     x-data="{ dragging: false }"
                     @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="dragging = false; $wire.upload('files', $event.dataTransfer.files)"
                     :class="{ 'border-ed-primary bg-ed-primary/5': dragging }">
                    
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    
                    <div class="mt-4">
                        <label for="file-upload" class="cursor-pointer">
                            <span class="mt-2 text-base text-gray-600">
                                <span class="font-semibold text-ed-primary hover:text-ed-secondary">Cliquez pour parcourir</span>
                                ou glissez-déposez vos fichiers ici
                            </span>
                            <input id="file-upload" 
                                   type="file" 
                                   wire:model="files" 
                                   multiple 
                                   class="sr-only">
                        </label>
                        <p class="mt-1 text-xs text-gray-500">
                            PNG, JPG, GIF, PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX jusqu'à 10MB
                        </p>
                    </div>
                </div>

                @error('files.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prévisualisation des fichiers sélectionnés -->
            @if(!empty($files))
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Fichiers sélectionnés ({{ count($files) }})</h3>
                
                <div class="space-y-3">
                    @foreach($files as $index => $file)
                    <div class="flex items-center gap-4 p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <!-- Icône/Preview -->
                        <div class="flex-shrink-0">
                            @if(str_starts_with($file->getMimeType(), 'image/'))
                                <img src="{{ $file->temporaryUrl() }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $file->getClientOriginalName() }}</p>
                            <p class="text-sm text-gray-500">
                                {{ number_format($file->getSize() / 1024, 2) }} KB
                                <span class="mx-1">•</span>
                                {{ $file->getMimeType() }}
                            </p>
                        </div>
                        
                        <!-- Bouton supprimer -->
                        <button wire:click="removeFile({{ $index }})" 
                                type="button"
                                class="flex-shrink-0 text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>

                <!-- Bouton d'upload -->
                <div class="mt-6 flex gap-3">
                    <button wire:click="upload" 
                            type="button"
                            class="flex-1 px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="upload">
                            Uploader {{ count($files) }} fichier(s)
                        </span>
                        <span wire:loading wire:target="upload" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Upload en cours...
                        </span>
                    </button>
                    
                    <a href="{{ route('admin.media.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-center">
                        Annuler
                    </a>
                </div>
            </div>
            @endif

            <!-- Fichiers récemment uploadés -->
            @if(!empty($uploadedFiles))
            <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Fichiers uploadés avec succès</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($uploadedFiles as $media)
                    <div class="border border-green-200 rounded-lg overflow-hidden bg-green-50">
                        @if($media->type === 'image')
                            <img src="{{ $media->url }}" class="w-full h-32 object-cover">
                        @else
                            <div class="w-full h-32 bg-green-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-2">
                            <p class="text-xs text-gray-600 truncate">{{ $media->nom_original }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.media.index') }}" 
                       class="inline-flex items-center gap-2 text-ed-primary hover:text-ed-secondary font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour à la médiathèque
                    </a>
                </div>
            </div>
            @endif

        </div>

        <!-- Sidebar avec infos et conseils -->
        <div class="space-y-6">
            
            <!-- Formats acceptés -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Formats acceptés</h3>
                
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-sm text-gray-700 mb-1">Images</h4>
                        <p class="text-xs text-gray-500">JPG, JPEG, PNG, GIF, WebP, SVG</p>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-sm text-gray-700 mb-1">Documents</h4>
                        <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</p>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-sm text-gray-700 mb-1">Vidéos</h4>
                        <p class="text-xs text-gray-500">MP4, WebM, OGG</p>
                    </div>
                </div>
            </div>

            <!-- Conseils -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-blue-900 mb-3">💡 Conseils</h3>
                
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Optimisez vos images avant l'upload pour réduire le temps de chargement
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Taille recommandée pour les slides : 1920x1080px
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Utilisez des noms de fichiers descriptifs
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Maximum 10 MB par fichier
                    </li>
                </ul>
            </div>

            <!-- Raccourcis -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions rapides</h3>
                
                <div class="space-y-2">
                    <a href="{{ route('admin.media.index') }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                        📁 Voir la médiathèque
                    </a>
                    <a href="{{ route('admin.sliders.index') }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                        🎨 Gérer les sliders
                    </a>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
                        🏠 Retour au dashboard
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>