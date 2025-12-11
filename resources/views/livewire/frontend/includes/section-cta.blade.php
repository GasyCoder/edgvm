<!-- CTA - version claire, pro et moderne -->
<section class="relative py-16 bg-gradient-to-b from-gray-50 via-white to-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Carte centrale --}}
        <div class="relative rounded-2xl p-[1px] bg-gradient-to-r from-emerald-500 via-emerald-400 to-ed-yellow shadow-sm">
            {{-- Carte blanche intérieure --}}
            <div class="bg-white/95 rounded-2xl px-6 py-10 md:px-10 md:py-12">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">

                    {{-- Colonne gauche : texte --}}
                    <div class="text-center md:text-left max-w-xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-ed-primary/5 rounded-full border border-ed-primary/15 mb-4">
                            <span class="w-2 h-2 rounded-full bg-ed-primary"></span>
                            <span class="text-ed-primary text-[11px] font-semibold tracking-[0.16em] uppercase">
                                Rejoindre l’EDGVM
                            </span>
                        </div>

                        <h2 class="text-2xl sm:text-2xl font-bold text-gray-900 leading-snug">
                            Prêt à commencer votre parcours doctoral&nbsp;?
                        </h2>

                        <p class="mt-3 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Intégrez l’École Doctorale Génie du Vivant et Modélisation, participez à des projets de 
                            recherche ambitieux et contribuez à l’avancement des connaissances au sein de l’Université de Mahajanga.
                        </p>
                    </div>

                    {{-- Colonne droite : boutons --}}
                    <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row gap-3 justify-center md:justify-end">
                        <a href="{{ route('ead.index') }}"
                        class="group inline-flex items-center justify-center px-6 py-3 
                                text-sm md:text-[15px] font-semibold rounded-lg
                                bg-ed-primary text-white shadow-sm hover:shadow-md
                                hover:bg-ed-secondary transition-all duration-200">
                            <span>Candidater maintenant</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>

                        <a href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center px-6 py-3
                                text-sm md:text-[15px] font-semibold rounded-lg
                                bg-white text-ed-primary border border-gray-200
                                hover:border-ed-primary/60 hover:bg-ed-primary/5 shadow-sm hover:shadow-md
                                transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Nous contacter</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>