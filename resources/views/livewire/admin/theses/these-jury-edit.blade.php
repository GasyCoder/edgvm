<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.theses.show', $these) }}"
                   class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Gérer le jury de la thèse</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $these->sujet_these ? Str::limit($these->sujet_these, 110) : 'Sujet non défini' }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session()->has('success'))
                <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Infos doctorant --}}
            <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-lg">
                        {{ strtoupper(mb_substr($these->doctorant->user?->name ?? 'D', 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ $these->doctorant->user?->name ?? 'Pas de compte' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Matricule : {{ $these->doctorant->matricule }}
                    </p>
                </div>
            </div>

            {{-- Formulaire jury --}}
            <form wire:submit.prevent="save" class="bg-white rounded-xl shadow p-6 space-y-4">

                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7h18M3 12h18M3 17h18"/>
                        </svg>
                        Composition du jury
                    </h3>

                    <button type="button"
                            wire:click="addRow"
                            class="inline-flex items-center px-3 py-1.5 text-xs bg-ed-primary text-white rounded-lg hover:bg-ed-secondary">
                        + Ajouter un membre
                    </button>
                </div>

                <p class="text-xs text-gray-500 mb-4">
                    Définissez les membres du jury, leur rôle (Président, Rapporteur, Examinateur…) et l’ordre
                    d’affichage (1 = Président, 2 = Rapporteur, etc.).
                </p>

                <div class="space-y-3">
                    @foreach($juryRows as $index => $row)
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end border border-gray-100 rounded-lg p-3"
                             wire:key="jury-row-{{ $index }}">
                            {{-- Jury --}}
                            <div class="md:col-span-5">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Membre du jury
                                </label>
                                <select
                                    wire:model="juryRows.{{ $index }}.jury_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent text-sm">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($allJurys as $jury)
                                        <option value="{{ $jury->id }}">
                                            {{ $jury->nom }}
                                            @if($jury->universite)
                                                – {{ $jury->universite }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('juryRows.'.$index.'.jury_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Rôle --}}
                            <div class="md:col-span-4">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Rôle dans le jury
                                </label>
                                <input type="text"
                                       wire:model="juryRows.{{ $index }}.role"
                                       placeholder="Président, Rapporteur, Examinateur..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent text-sm">
                                @error('juryRows.'.$index.'.role')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ordre --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                    Ordre
                                </label>
                                <input type="number"
                                       wire:model="juryRows.{{ $index }}.ordre"
                                       min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent text-sm">
                                @error('juryRows.'.$index.'.ordre')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Supprimer --}}
                            <div class="md:col-span-1 flex justify-end">
                                <button type="button"
                                        wire:click="removeRow({{ $index }})"
                                        class="px-2 py-2 text-xs text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit"
                            class="flex-1 px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold shadow">
                        Enregistrer le jury
                    </button>
                    <a href="{{ route('admin.theses.show', $these) }}"
                       class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>