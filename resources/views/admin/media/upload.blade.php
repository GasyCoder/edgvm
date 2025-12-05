<x-app-layout>
<div>
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-gray-600">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li>/</li>
            <li><a href="{{ route('admin.media.index') }}">Médiathèque</a></li>
            <li>/</li>
            <li class="font-semibold">Upload</li>
        </ol>
    </nav>

    <h1 class="text-2xl font-bold mb-6">Uploader des fichiers</h1>

    <!-- Formulaire CLASSIQUE -->
    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <label class="block mb-4">
                <span class="text-gray-700 font-semibold">Sélectionner des fichiers</span>
                <input type="file" 
                       name="files[]" 
                       multiple 
                       required
                       accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                       class="mt-2 block w-full text-sm text-gray-500
                              file:mr-4 file:py-3 file:px-6
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-ed-primary file:text-white
                              hover:file:bg-ed-secondary
                              cursor-pointer"
                       onchange="previewFiles(this)">
            </label>

            @error('files')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <!-- Prévisualisation -->
            <div id="preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
        </div>

        <!-- Boutons -->
        <div class="flex gap-3">
            <button type="submit" 
                    class="px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary font-bold">
                📤 Uploader les fichiers
            </button>
            <a href="{{ route('admin.media.index') }}" 
               class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
function previewFiles(input) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const div = document.createElement('div');
            div.className = 'border border-gray-300 rounded p-2';

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.className = 'w-full h-32 object-cover rounded mb-2';
                img.file = file;

                const reader = new FileReader();
                reader.onload = (e) => img.src = e.target.result;
                reader.readAsDataURL(file);

                div.appendChild(img);
            }

            const info = document.createElement('p');
            info.className = 'text-xs text-gray-600 truncate';
            info.textContent = file.name;
            div.appendChild(info);

            const size = document.createElement('p');
            size.className = 'text-xs text-gray-500';
            size.textContent = (file.size / 1024).toFixed(2) + ' KB';
            div.appendChild(size);

            preview.appendChild(div);
        });
    }
}
</script>
</x-app-layout>