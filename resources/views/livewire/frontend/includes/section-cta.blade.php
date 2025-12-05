    <!-- CTA - DESIGN MODERNE -->
    <section class="relative py-20 bg-gradient-to-br from-ed-primary via-ed-secondary to-teal-700 overflow-hidden">
        <!-- Motifs animés -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-ed-yellow rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>
        
        <!-- Grille -->
        <div class="absolute inset-0 opacity-5">
            <div class="h-full w-full" style="background-image: linear-gradient(white 1px, transparent 1px), linear-gradient(90deg, white 1px, transparent 1px); background-size: 50px 50px;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8">
                    <svg class="w-4 h-4 text-ed-yellow" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                    <span class="text-white text-sm font-semibold">Rejoignez-nous</span>
                </div>

                <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight">
                    Prêt à commencer votre<br class="hidden md:block">parcours doctoral ?
                </h2>
                
                <p class="text-xl text-white/90 mb-10 max-w-3xl mx-auto">
                    Rejoignez l'EDGVM et contribuez à l'avancement de la science
                </p>

                <!-- Stats inline -->
                <div class="flex flex-wrap justify-center gap-8 mb-10">
                    <div class="text-center">
                        <p class="text-4xl font-black text-white mb-1">109</p>
                        <p class="text-white/80 text-sm font-medium">Doctorants actifs</p>
                    </div>
                    <div class="w-px h-16 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-4xl font-black text-white mb-1">8</p>
                        <p class="text-white/80 text-sm font-medium">Équipes d'accueil</p>
                    </div>
                    <div class="w-px h-16 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-4xl font-black text-white mb-1">23</p>
                        <p class="text-white/80 text-sm font-medium">Thèses soutenues</p>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('register') }}" class="group px-8 py-4 bg-ed-yellow text-gray-900 rounded-xl hover:bg-ed-yellow-dark transition-all duration-300 font-bold shadow-2xl hover:shadow-ed-yellow/50 hover:scale-[1.02] inline-flex items-center">
                        <span>Candidater maintenant</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-4 bg-white text-ed-primary rounded-xl hover:bg-white/90 transition-all duration-300 font-bold shadow-xl hover:scale-[1.02] inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Nous contacter</span>
                    </a>
                </div>
            </div>
        </div>
    </section>