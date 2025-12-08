    <!-- About Section - DESIGN MODERNE -->
    <section class="py-24 bg-white relative overflow-hidden">
        <!-- Motifs décoratifs -->
        <div class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-gradient-to-br from-ed-primary/5 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-[40rem] h-[40rem] bg-gradient-to-tr from-ed-yellow/5 to-transparent rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-ed-primary/10 rounded-full mb-6">
                        <div class="w-2 h-2 bg-ed-primary rounded-full"></div>
                        <span class="text-ed-primary font-semibold text-sm">À propos</span>
                    </div>
                    
                    <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-6 leading-tight">
                        École Doctorale<br>
                        <span class="text-ed-primary">Génie du Vivant</span> et Modélisation
                    </h2>
                    
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        L'EDGVM est un centre d'excellence en recherche qui forme les docteurs de demain dans les domaines du génie du vivant, de la modélisation mathématique et de l'innovation technologique.
                    </p>
                    
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Notre mission est de développer des compétences de haut niveau en recherche scientifique et de contribuer à l'avancement des connaissances.
                    </p>
                    
                    <a href="{{ route('pages.show', 'a-propos') }}" class="group inline-flex items-center px-8 py-4 bg-ed-primary text-white rounded-xl hover:bg-ed-secondary transition-all duration-300 font-bold shadow-lg hover:shadow-xl hover:scale-[1.02]">
                        <span>Découvrir l'école</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <!-- Vidéo YouTube avec design moderne -->
                <div class="relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl group">
                        <!-- Container vidéo responsive 16:9 -->
                        <div class="relative pb-[56.25%] h-0">
                            <iframe 
                                class="absolute top-0 left-0 w-full h-full"
                                src="https://www.youtube.com/embed/ePyaHxQ8jpM?controls=1&modestbranding=1&rel=0" 
                                title="Présentation EDGVM" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                        <!-- Overlay décoratif (apparaît au hover) -->
                        <div class="absolute inset-0 bg-gradient-to-t from-ed-primary/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    </div>
                    
                    <!-- Badge "Vidéo de présentation" -->
                    <div class="absolute -top-4 -left-4 z-10">
                        <div class="bg-ed-yellow text-gray-900 px-4 py-2 rounded-xl font-bold text-sm shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                            </svg>
                            <span>Vidéo présentation</span>
                        </div>
                    </div>
                    
                    <!-- Éléments décoratifs -->
                    <div class="absolute -bottom-6 -right-6 w-72 h-72 bg-ed-yellow/20 rounded-full blur-3xl -z-10"></div>
                    <div class="absolute -top-6 -left-6 w-48 h-48 bg-ed-primary/20 rounded-full blur-2xl -z-10"></div>
                    
                    <!-- Stats flottants -->
                    <div class="absolute -bottom-6 left-8 bg-white rounded-2xl shadow-2xl p-4 z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-ed-primary/10 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">109+</p>
                                <p class="text-xs text-gray-600">Doctorants</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
