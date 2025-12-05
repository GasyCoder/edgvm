
    <!-- Programmes - DESIGN GRID MODERNE -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-ed-primary/10 rounded-full mb-6">
                    <div class="w-2 h-2 bg-ed-primary rounded-full"></div>
                    <span class="text-ed-primary font-semibold text-sm">Nos programmes</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">Programmes de Recherche</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Des parcours de recherche adaptés à vos ambitions scientifiques
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($specialites as $specialite)
                <div class="group bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden border-2 border-transparent hover:border-ed-primary/20 hover:-translate-y-2">
                    <div class="h-48 bg-gradient-to-br from-ed-primary via-ed-secondary to-teal-600 relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/5"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-ed-primary transition-colors">
                            {{ $specialite->nom }}
                        </h3>
                        <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                            {{ $specialite->description }}
                        </p>
                        <a href="{{ route('programmes.show', $specialite->id) }}" 
                           class="group/link inline-flex items-center text-ed-primary font-bold hover:text-ed-secondary transition-colors">
                            <span>En savoir plus</span>
                            <svg class="w-5 h-5 ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('programmes') }}" class="group inline-flex items-center px-8 py-4 bg-ed-primary text-white rounded-xl hover:bg-ed-secondary transition-all duration-300 font-bold shadow-lg hover:shadow-xl hover:scale-[1.02]">
                    <span>Voir tous les programmes</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>