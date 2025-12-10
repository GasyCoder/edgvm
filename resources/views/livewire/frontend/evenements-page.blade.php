<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-extrabold">Agenda — Événements à venir</h1>
        <div class="flex items-center gap-3">
            <select wire:model="typeFilter" class="px-3 py-2 border rounded">
                <option value="">Tous types</option>
                <option value="soutenance">Soutenance</option>
                <option value="seminaire">Séminaire</option>
                <option value="conference">Conférence</option>
                <option value="atelier">Atelier</option>
                <option value="autre">Autre</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($evenements as $e)
            <article class="bg-white rounded-lg shadow p-4">
                <div class="flex items-start gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold">{{ $e->jour }}</div>
                        <div class="text-sm">{{ $e->mois }}</div>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">{{ $e->titre }}</h2>
                            <span class="inline-flex px-2 py-1 text-xs rounded {{ $e->type_classe }}">{{ $e->type_texte }}</span>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            <div>{{ $e->periode_aff }} @if($e->heure_debut) • {{ $e->heure_debut_aff }} @endif</div>
                            @if($e->lieu)<div class="mt-1">{{ $e->lieu }}</div>@endif
                        </div>

                        <p class="mt-3 text-sm text-gray-700 line-clamp-3">{{ Str::limit(strip_tags($e->description), 180) }}</p>

                        <div class="mt-4 flex items-center justify-between">
                            <a href="{{ route('evenements.show', $e) }}" class="text-sm font-medium underline">Voir le détail</a>
                            @if($e->lien_inscription)
                                <a href="{{ $e->lien_inscription }}" target="_blank" class="text-sm px-3 py-1 border rounded">S'inscrire</a>
                            @endif
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center text-gray-500">Aucun événement à venir pour le moment.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $evenements->links() }}
    </div>
</div>
