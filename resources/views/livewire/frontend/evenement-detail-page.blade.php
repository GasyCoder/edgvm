<div class="container mx-auto p-6 max-w-4xl">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-start gap-6">
            <div class="text-center">
                <div class="text-5xl font-extrabold">{{ $evenement->jour }}</div>
                <div class="text-sm uppercase">{{ $evenement->mois }}</div>
            </div>

            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $evenement->titre }}</h1>
                        <div class="mt-2 text-sm text-gray-600">{{ $evenement->periode_aff }} @if($evenement->heure_debut) • {{ $evenement->heure_debut_aff }} @endif</div>
                        @if($evenement->lieu) <div class="text-sm text-gray-600 mt-1">{{ $evenement->lieu }}</div> @endif
                    </div>

                    <div class="text-right">
                        <span class="inline-flex px-3 py-1 rounded {{ $evenement->type_classe }}">{{ $evenement->type_texte }}</span>
                        @if($evenement->est_important)
                            <div class="mt-2"><span class="inline-flex px-2 py-1 bg-red-600 text-white rounded">Important</span></div>
                        @endif
                    </div>
                </div>

                <div class="mt-6 prose max-w-none">
                    {!! nl2br(e($evenement->description)) !!}
                </div>

                @if($evenement->lieu)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-2">Lieu</h3>
                    <!-- Simple map placeholder (intégration Google Maps ou Leaflet selon clefs) -->
                    <div class="w-full h-64 bg-gray-100 rounded flex items-center justify-center">
                        <div class="text-sm text-gray-500">Carte du lieu : {{ $evenement->lieu }}</div>
                    </div>
                </div>
                @endif

                <div class="mt-6 flex items-center gap-3">
                    @if($evenement->lien_inscription)
                        <a href="{{ $evenement->lien_inscription }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded">S'inscrire</a>
                    @endif
                    <a href="{{ route('evenements.index') }}" class="px-4 py-2 border rounded">Retour à l'agenda</a>
                </div>
            </div>
        </div>
    </div>
</div>