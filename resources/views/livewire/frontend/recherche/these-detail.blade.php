<div>
    {{-- Hero --}}
    <section class="relative gradient-primary pt-20 md:pt-24 pb-14 md:pb-16 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10"></div>
            <div class="absolute -top-10 right-0 w-72 h-72 bg-white/15 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 -left-16 w-80 h-80 bg-emerald-500/25 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="mb-6">
                <ol class="flex items-center gap-2 text-xs md:text-sm text-white/75">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition">
                            Accueil
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </li>
                    <li>
                        <a href="{{ route('theses.index') }}" class="hover:text-white transition">
                            Thèses
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </li>
                    <li class="text-white font-semibold line-clamp-1 max-w-[260px] md:max-w-xl">
                        {{ $these->sujet_these }}
                    </li>
                </ol>
            </nav>

            {{-- Titre & infos clés --}}
            @php
                $doctorantName = $these->doctorant?->user?->name;
                $doctorantInitials = $doctorantName
                    ? strtoupper(mb_substr($doctorantName, 0, 2))
                    : '';
            @endphp

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                {{-- Bloc gauche : sujet + infos doctorant/spécialité/EAD --}}
                <div class="flex-1 space-y-4">
                    <p class="text-[11px] md:text-xs uppercase tracking-[0.2em] text-emerald-100">
                        Thèse de doctorat
                    </p>

                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white leading-snug">
                        {{ $these->sujet_these }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-3 text-xs text-white/85">
                        @if($doctorantName)
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/20">
                                <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-[11px] font-semibold">
                                    {{ $doctorantInitials }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-white">
                                        {{ $doctorantName }}
                                    </span>
                                    <span class="text-[11px] text-white/70">
                                        Doctorant
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($these->specialite)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-100/60 text-xs">
                                <svg class="w-4 h-4 text-emerald-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6v12m6-6H6"/>
                                </svg>
                                {{ $these->specialite->nom ?? $these->specialite->intitule ?? 'Spécialité' }}
                            </span>
                        @endif

                        @if($these->ead)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 border border-teal-100/60 text-xs">
                                <svg class="w-4 h-4 text-teal-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
                                </svg>
                                {{ $these->ead->sigle ?? $these->ead->nom ?? 'Équipe d’accueil' }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Bloc droit : statut + dates --}}
                <div class="w-full lg:w-auto lg:min-w-[260px] flex flex-col items-start lg:items-end gap-3">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full border text-xs font-semibold {{ $these->statut_badge_classes }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current/70 mr-1.5"></span>
                        {{ $these->statut_label }}
                    </span>

                    <div class="flex flex-col items-start lg:items-end gap-1.5 text-[11px] text-white/85">
                        @if($these->date_debut)
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                                             a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Début de la thèse : {{ $these->date_debut->format('d/m/Y') }}
                            </span>
                        @endif

                        @if($these->statut === 'soutenue')
                            @if($these->date_publication)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0
                                                 9 9 0 0118 0z"/>
                                    </svg>
                                    Date de soutenance : {{ $these->date_publication->format('d/m/Y') }}
                                </span>
                            @endif
                        @else
                            @if($these->date_prevue_fin)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0
                                                 9 9 0 0118 0z"/>
                                    </svg>
                                    Fin prévue : {{ $these->date_prevue_fin->format('d/m/Y') }}
                                </span>
                            @endif
                        @endif
                    </div>

                    <a href="{{ route('theses.index') }}"
                       class="inline-flex items-center gap-2 text-[11px] text-white/80 hover:text-white mt-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 19l-7-7 7-7"/>
                        </svg>
                        Retour à la liste des thèses
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Contenu principal --}}
    <section class="py-12 md:py-16 bg-gradient-to-b from-slate-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Colonne principale élargie (9/12) --}}
            <div class="lg:col-span-9 space-y-6">
                {{-- Résumé + bouton PDF en bas --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-gradient-to-b from-emerald-600 to-teal-600 rounded-full"></span>
                        Résumé de la thèse
                    </h2>

                    @if($these->resume_these)
                        <div class="text-sm md:text-base text-gray-800 text-justify leading-relaxed space-y-3">
                            {!! nl2br(e($these->resume_these)) !!}
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">
                            Le résumé de cette thèse n’a pas encore été renseigné.
                        </p>
                    @endif

                    @if($these->fichier_pdf_url)
                        <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <p class="text-xs text-gray-500">
                                Le manuscrit complet est disponible au format PDF.
                            </p>
                            <a href="{{ $these->fichier_pdf_url }}" target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-emerald-600 text-white text-xs font-semibold shadow-sm hover:bg-emerald-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/>
                                    </svg>
                                Télécharger le manuscrit (PDF)
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Mots-clés --}}
                @if($these->mots_cles)
                    @php
                        $keywords = collect(explode(',', $these->mots_cles))
                            ->map(fn($k) => trim($k))
                            ->filter();
                    @endphp

                    @if($keywords->isNotEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-7">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 7h.01M7 3h10a2 2 0 012 2v4
                                             a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h2z"/>
                                </svg>
                                Mots-clés
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($keywords as $keyword)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs border border-emerald-100">
                                        {{ $keyword }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Informations principales (juste sous le résumé) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-7">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-gradient-to-b from-emerald-600 to-teal-600 rounded-full"></span>
                        Informations principales
                    </h2>

                    <dl class="space-y-2 text-sm">
                        @if($doctorantName)
                            <div class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Doctorant :</dt>
                                <dd>{{ $doctorantName }}</dd>
                            </div>
                        @endif

                        @if($these->specialite)
                            <div class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Spécialité :</dt>
                                <dd>{{ $these->specialite->nom ?? $these->specialite->intitule ?? '' }}</dd>
                            </div>
                        @endif

                        @if($these->ead)
                            <div class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Équipe d’accueil :</dt>
                                <dd>{{ $these->ead->nom ?? '' }}</dd>
                            </div>
                        @endif

                        @if($these->universite_soutenance)
                            <div class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Université :</dt>
                                <dd>{{ $these->universite_soutenance }}</dd>
                            </div>
                        @endif

                        @if($these->date_debut)
                            <div class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Début de la thèse :</dt>
                                <dd>{{ $these->date_debut->format('d/m/Y') }}</dd>
                            </div>
                        @endif

                        @if($these->statut === 'soutenue' && $these->date_publication)
                            <div class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Date de soutenance :</dt>
                                <dd>{{ $these->date_publication->format('d/m/Y') }}</dd>
                            </div>
                        @elseif($these->date_prevue_fin)
                            <div class="flex flex-wrap gap-x-2 text-gray-900">
                                <dt class="font-medium text-gray-700">Fin prévue :</dt>
                                <dd>{{ $these->date_prevue_fin->format('d/m/Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Description / Notes complémentaires --}}
                @if($these->description)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-7">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 16h8M8 12h8M11 8h5m-7 8H7m0-4h1m0-4h1M5 4h14a2 2 0 012 2v12
                                         a2 2 0 01-2 2H5a2 2 0 01-2-2V6
                                         a2 2 0 012-2z"/>
                            </svg>
                            Notes complémentaires
                        </h3>
                        <div class="text-sm text-gray-800 text-justify leading-relaxed space-y-3">
                            {!! nl2br(e($these->description)) !!}
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar (3/12) --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- Encadrants --}}
                @if($these->encadrants && $these->encadrants->count())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-7">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg
                            class="w-5 h-5 text-emerald-600 flex-shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5S14.343 11 16 11zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zM8 13c-2.21 0-4 1.79-4 4v1h7.5M16 13c2.21 0 4 1.79 4 4v1h-7.5"
                            />
                        </svg>
                        Encadrement
                    </h3>

                        <ul class="space-y-3 text-sm">
                            @foreach($these->encadrants as $encadrant)
                                @php
                                    $encName = $encadrant->user->name
                                        ?? trim(($encadrant->prenom ?? '') . ' ' . ($encadrant->nom ?? ''));
                                    $encInitials = $encadrant->user?->name
                                        ? strtoupper(mb_substr($encadrant->user->name, 0, 2))
                                        : strtoupper(mb_substr(trim(($encadrant->prenom ?? $encadrant->nom ?? '')), 0, 2));
                                @endphp
                                <li class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-xs font-semibold text-emerald-700">
                                        {{ $encInitials }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $encName }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $encadrant->pivot->role ?? 'Encadrant' }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Jury --}}
                @if($these->jurys && $these->jurys->count())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-7">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg
                                class="w-5 h-5 text-slate-600 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0zM5 20a4 4 0 014-4h6a4 4 0 014 4v1H5v-1z"
                                />
                            </svg>
                            Jury de soutenance
                        </h3>

                        <ul class="space-y-3 text-sm">
                            @foreach($these->jurys->sortBy('pivot.ordre') as $jury)
                                @php
                                    $juryName = $jury->user->name
                                        ?? trim(($jury->prenom ?? '') . ' ' . ($jury->nom ?? ''));
                                    $juryInitials = $jury->user?->name
                                        ? strtoupper(mb_substr($jury->user->name, 0, 2))
                                        : strtoupper(mb_substr(trim(($jury->prenom ?? $jury->nom ?? '')), 0, 2));
                                @endphp
                                <li class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-xs font-semibold text-slate-700">
                                        {{ $juryInitials }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $juryName }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $jury->pivot->role ?? 'Membre du jury' }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- CTA contact --}}
                <div class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-2xl shadow-xl p-6 text-white">
                    <h3 class="text-sm font-semibold mb-2">
                        Une question sur cette thèse ?
                    </h3>
                    <p class="text-xs text-white/80 mb-4 leading-relaxed">
                        Pour toute information complémentaire (copie du manuscrit, détails de la soutenance, contact du doctorant ou de l’encadrement),
                        vous pouvez contacter le secrétariat de l’École Doctorale.
                    </p>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-semibold rounded-full bg-white text-emerald-700 hover:bg-emerald-50 transition">
                        Contacter l’EDGVM
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
