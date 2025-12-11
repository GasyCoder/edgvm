<!-- About Section - UI/UX épuré pour EDGVM -->
<section class="pt-10 pb-14 md:pt-12 md:pb-16 bg-white relative overflow-hidden">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 lg:gap-16 items-center">

            {{-- Colonne gauche : contenu texte --}}
            <div>
                {{-- Tag / sur-titre --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-ed-primary/10 rounded-full mb-5">
                    <span class="w-2 h-2 bg-ed-primary rounded-full"></span>
                    <span class="text-ed-primary font-bold text-xs tracking-[0.16em] ">
                        À propos de l’EDGVM
                    </span>
                </div>

                {{-- Titre --}}
                <h2 class="text-2xl md:text-3xl lg:text-[2rem] font-bold text-gray-900 leading-snug">
                    École Doctorale 
                    <span class="text-ed-primary">Génie du Vivant</span> et Modélisation
                </h2>

                {{-- Sous-titre / phrase de contexte --}}
                <p class="mt-3 text-sm sm:text-base text-gray-600">
                    Une structure d’excellence de l’Université de Mahajanga, dédiée à la formation 
                    et à l’accompagnement des doctorants dans les domaines du vivant, de la modélisation 
                    et de l’innovation scientifique.
                </p>

                {{-- Points clés sous forme de liste --}}
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex h-6 w-6 items-center justify-center rounded-lg bg-ed-primary/10">
                            <span class="text-ed-primary text-xs font-semibold">1</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                Formation doctorale structurée
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Séminaires, ateliers méthodologiques et encadrement rapproché 
                                pour sécuriser le parcours de thèse.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex h-6 w-6 items-center justify-center rounded-lg bg-ed-yellow/15">
                            <span class="text-ed-yellow text-xs font-semibold">2</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                Pluridisciplinarité & ouverture
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Projets de recherche croisant sciences du vivant, modélisation, 
                                environnement, santé, innovation technologique, etc.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-50">
                            <span class="text-emerald-600 text-xs font-semibold">3</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                Encadrement qualifié
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Encadrants et équipes d’accueil reconnus, impliqués dans des 
                                collaborations nationales et internationales.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-50">
                            <span class="text-indigo-600 text-xs font-semibold">4</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                Impact territorial et régional
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Des thèses ancrées dans les enjeux du territoire : environnement, 
                                santé, ressources, développement durable.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- CTA principal --}}
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('pages.show', 'a-propos') }}"
                       class="group inline-flex items-center px-7 py-3.5 bg-ed-primary text-white rounded-xl 
                              hover:bg-ed-secondary transition-all duration-200 font-semibold shadow-md hover:shadow-lg">
                        <span>Découvrir l’École</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Colonne droite : vidéo YouTube stylée --}}
            <div class="relative">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-gray-900/90">
                    {{-- Container vidéo responsive 16:9 --}}
                    <div class="relative pb-[56.25%] h-0">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full"
                            src="https://www.youtube.com/embed/ePyaHxQ8jpM?controls=1&modestbranding=1&rel=0" 
                            title="Présentation EDGVM" 
                            frameborder="0" 
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

                {{-- Badge "Vidéo de présentation" --}}
                <div class="absolute -top-4 left-4">
                    <div class="bg-ed-yellow text-gray-900 px-4 py-2 rounded-xl font-semibold text-xs sm:text-sm shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                        </svg>
                        <span>Vidéo de présentation</span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
