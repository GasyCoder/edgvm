<!-- CTA - Senior / large / bg plus clair / card plus white -->
<section class="relative py-10 md:py-20 bg-white overflow-hidden">
    {{-- décor très discret (encore plus léger) --}}
    <div class="pointer-events-none absolute -top-28 right-[-10rem] w-[32rem] h-[32rem] bg-ed-primary/3 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-28 left-[-10rem] w-[32rem] h-[32rem] bg-ed-yellow/4 rounded-full blur-3xl"></div>

    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-12 relative">
        {{-- card (plus white) --}}
        <div class="relative rounded-3xl bg-white/95 backdrop-blur-sm">
            {{-- accent très léger --}}
            <div class="h-[2px] rounded-t-3xl bg-gradient-to-r from-transparent via-ed-primary/20 to-transparent"></div>

            <div class="px-7 py-10 sm:px-10 md:px-14 md:py-12 lg:px-16 lg:py-14">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                    {{-- Texte --}}
                    <div class="lg:col-span-8 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-ed-primary/5 mb-5">
                            <span class="w-2 h-2 rounded-full bg-ed-primary"></span>
                            <span class="text-ed-primary text-[11px] font-semibold tracking-[0.20em] uppercase">
                                Rejoindre l’EDGVM
                            </span>
                        </div>

                        <h2 class="text-2xl sm:text-3xl lg:text-[2.25rem] font-bold text-gray-900 leading-tight tracking-tight">
                            Prêt à commencer votre parcours doctoral&nbsp;?
                        </h2>

                        <p class="mt-4 text-sm sm:text-base text-gray-600 leading-relaxed max-w-3xl mx-auto lg:mx-0">
                            Intégrez l’École Doctorale Génie du Vivant et Modélisation, participez à des projets de recherche
                            ambitieux et contribuez à l’avancement des connaissances au sein de l’Université de Mahajanga.
                        </p>

                        <div class="mt-6 flex flex-wrap items-center justify-center lg:justify-start gap-x-6 gap-y-2 text-[12px] text-gray-500">
                            <span class="inline-flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Encadrement & suivi
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-ed-yellow"></span>
                                Recherche & innovation
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-ed-primary"></span>
                                Université de Mahajanga
                            </span>
                        </div>
                    </div>

                    {{-- Boutons côte à côte (desktop), stack (mobile) --}}
                    <div class="lg:col-span-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('ead.index') }}"
                               class="group inline-flex items-center justify-center px-6 py-3.5
                                      text-sm md:text-[15px] font-semibold rounded-2xl
                                      bg-ed-primary text-white
                                      hover:bg-ed-secondary transition-colors duration-200
                                      focus:outline-none focus:ring-2 focus:ring-ed-primary/25 focus:ring-offset-2">
                                <span>Candidater</span>
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                          d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>

                            <a href="{{ route('contact') }}"
                               class="inline-flex items-center justify-center px-6 py-3.5
                                      text-sm md:text-[15px] font-semibold rounded-2xl
                                      bg-gray-50 text-gray-900
                                      hover:bg-gray-100 transition-colors duration-200
                                      focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>Contacter</span>
                            </a>
                        </div>

                        <p class="mt-3 text-center lg:text-left text-[12px] text-gray-500">
                            Réponse rapide pour les demandes d’informations.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
