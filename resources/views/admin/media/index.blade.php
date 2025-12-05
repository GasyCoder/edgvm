<x-app-layout>
<div>
    <!-- Messages Flash -->
    @if (session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Médiathèque</h1>
        </div>
        <a href="{{ route('admin.media.upload') }}" 
           class="px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold">
            📤 Uploader
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Total</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Images</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['images'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Documents</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['documents'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Vidéos</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['videos'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Taille</p>
            <p class="text-xl font-bold">{{ number_format($stats['taille_totale'] / 1024 / 1024, 2) }} MB</p>
        </div>
    </div>

    <!-- Galerie -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($medias as $media)
        <div class="bg-white rounded-lg shadow overflow-hidden group relative">
            <div class="aspect-square bg-gray-100 flex items-center justify-center">
                @if($media->type === 'image')
                    <img src="{{ $media->url }}" alt="{{ $media->nom_original }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                @else
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                @endif
            </div>

            <!-- Actions au survol -->
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                <a href="{{ $media->url }}" target="_blank"
                   class="p-2 bg-white rounded-full hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </a>
                <form action="{{ route('admin.media.destroy', $media) }}" method="POST" 
                      onsubmit="return confirm('Supprimer ce média ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 bg-red-600 rounded-full hover:bg-red-700">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Info -->
            <div class="p-2 border-t">
                <p class="text-xs text-gray-600 truncate">{{ $media->nom_original }}</p>
                <p class="text-xs text-gray-400">{{ number_format($media->taille_bytes / 1024, 2) }} KB</p>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">Aucun média</p>
            <a href="{{ route('admin.media.upload') }}" class="mt-4 inline-block px-4 py-2 bg-ed-primary text-white rounded">
                Uploader le premier
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $medias->links() }}
    </div>
</div>
</x-app-layout>