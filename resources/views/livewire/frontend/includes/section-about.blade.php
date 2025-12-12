<section
    id="a-propos"
    aria-labelledby="about-heading"
    class="relative overflow-hidden bg-white py-14 md:py-16"
>
    {{-- Décorations discrètes --}}
    <div class="pointer-events-none absolute -top-24 right-[-6rem] h-72 w-72 rounded-full bg-ed-primary/7 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 left-[-6rem] h-72 w-72 rounded-full bg-ed-yellow/8 blur-3xl"></div>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center lg:gap-14">

            {{-- Colonne gauche (texte) --}}
            <div class="lg:col-span-6">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 rounded-full border border-ed-primary/20 bg-ed-primary/8 px-3 py-1.5">
                    <span class="h-2 w-2 rounded-full bg-ed-primary"></span>
                    <span class="text-[11px] font-semibold tracking-[0.16em] uppercase text-ed-primary">
                        À propos de l’EDGVM
                    </span>
                </div>

                {{-- Titre --}}
                <h2 id="about-heading" class="mt-4 text-2xl md:text-3xl lg:text-[2rem] font-bold tracking-tight text-gray-900">
                    École Doctorale
                    <span class="text-ed-primary">Génie du Vivant</span>
                    et Modélisation
                </h2>

                {{-- Intro --}}
                <p class="mt-3 text-sm sm:text-base leading-relaxed text-gray-600 max-w-2xl">
                    Une structure d’excellence de l’Université de Mahajanga, dédiée à la formation
                    et à l’accompagnement des doctorants dans les domaines du vivant, de la modélisation
                    et de l’innovation scientifique.
                </p>

                {{-- Points clés (cards) --}}
                <div class="mt-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Card 1 --}}
                     <div class="group rounded-xl border border-gray-100 bg-white p-4 transition hover:-translate-y-[1px] hover:shadow-md">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl text-ed-primary">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 stroke-current"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12v5c0 .5.3 1 1 1.3 1.6.8 3.6 1.7 5 1.7s3.4-.9 5-1.7c.7-.3 1-.8 1-1.3v-5" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900">
                                    Formation doctorale structurée
                                </p>
                                <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                    Séminaires, ateliers méthodologiques et encadrement rapproché pour sécuriser le parcours de thèse.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2 --}}
                     <div class="group rounded-xl border border-gray-100 bg-white p-4 transition hover:-translate-y-[1px] hover:shadow-md">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl text-ed-yellow">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 stroke-current"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.5 2.5l1.1 3.3 3.3 1.1-3.3 1.1-1.1 3.3-1.1-3.3-3.3-1.1 3.3-1.1 1.1-3.3z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 9.5l.9 2.6 2.6.9-2.6.9-.9 2.6-.9-2.6-2.6-.9 2.6-.9.9-2.6z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 18.5l.7 2.1 2.1.7-2.1.7L4 24l-.7-2.1L1.2 21l2.1-.7L4 18.5z" />
                            </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900">
                                    Pluridisciplinarité &amp; ouverture
                                </p>
                                <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                    Projets croisant sciences du vivant, modélisation, environnement, santé, innovation technologique.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3 --}}
                     <div class="group rounded-xl border border-gray-100 bg-white p-4 transition hover:-translate-y-[1px] hover:shadow-md">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 stroke-current"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 20a6 6 0 0116 0" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 8.5a2.5 2.5 0 10-2-4.5" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20 18.5a5.5 5.5 0 00-3-4" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900">
                                    Encadrement qualifié
                                </p>
                                <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                    Encadrants et équipes d’accueil reconnus, impliqués dans des collaborations nationales et internationales.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 4 --}}
                    <div class="group rounded-xl border border-gray-100 bg-white p-4 transition hover:-translate-y-[1px] hover:shadow-md">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 stroke-current"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21s7-4.4 7-11a7 7 0 10-14 0c0 6.6 7 11 7 11z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 10.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900">
                                    Impact territorial et régional
                                </p>
                                <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                    Des thèses ancrées dans les enjeux du territoire : environnement, santé, ressources, développement durable.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a
                        href="{{ route('pages.show', 'a-propos') }}"
                        class="group inline-flex items-center gap-2 rounded-xl bg-ed-primary px-6 py-3 text-sm font-semibold text-white
                               shadow-md transition hover:-translate-y-[1px] hover:bg-ed-secondary hover:shadow-lg
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/50"
                    >
                        <span>Découvrir l’École</span>
                        <svg class="h-5 w-5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>

                    <a
                        href="#actualites-agenda"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700
                               shadow-sm transition hover:border-ed-primary/50 hover:text-ed-primary hover:shadow-md
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40"
                    >
                        <span>Voir les actualités</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Colonne droite (vidéo) --}}
            <div class="lg:col-span-6">
                <div class="relative">
                    {{-- Cadre premium --}}
                    <div class="relative overflow-hidden rounded-xl border border-gray-100 bg-white shadow-2xl">
                        {{-- Header mini (style card) --}}
                        <div class="flex items-center justify-between gap-3 px-5 py-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-ed-yellow/15 text-ed-yellow">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Présentation EDGVM</p>
                                    <p class="text-[11px] text-gray-500">Vidéo officielle</p>
                                </div>
                            </div>

                            <span class="hidden sm:inline-flex items-center gap-2 rounded-full bg-gray-50 px-3 py-1 text-[11px] font-semibold text-gray-700 border border-gray-100">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Lecture disponible
                            </span>
                        </div>

                        {{-- Vidéo responsive 16:9 --}}
                        <div class="relative pb-[56.25%] h-0 bg-gray-900">
                            <iframe
                                class="absolute inset-0 h-full w-full"
                                src="https://www.youtube-nocookie.com/embed/ePyaHxQ8jpM?controls=1&modestbranding=1&rel=0"
                                title="Présentation EDGVM"
                                frameborder="0"
                                loading="lazy"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                            ></iframe>
                        </div>

                        {{-- Footer mini --}}
                        <div class="px-5 py-4">
                            <p class="text-[12px] text-gray-600 leading-relaxed">
                                Découvrez la mission de l’EDGVM, ses axes de recherche, et les opportunités offertes aux doctorants.
                            </p>
                        </div>
                    </div>

                    {{-- Accent décoratif --}}
                    <div class="pointer-events-none absolute -z-10 -right-6 -bottom-6 h-24 w-24 rounded-3xl bg-ed-primary/10 blur-xl"></div>
                </div>
            </div>

        </div>
    </div>
</section>
