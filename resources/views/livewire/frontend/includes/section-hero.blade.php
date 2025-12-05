    <!-- Hero Slider Section - DESIGN MODERNE -->
    <section class="relative h-[75vh] mt-20 overflow-hidden" x-data="slider()">
        <!-- Slides Container -->
        <div class="relative h-full">
            <!-- Slide 1 -->
            <div x-show="currentSlide === 0" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-1000"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 w-full h-full">
                <div class="absolute inset-0 bg-gradient-to-br from-ed-primary/95 via-ed-secondary/90 to-teal-800/95"></div>
                <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=1920&q=80" 
                     class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-20" 
                     alt="Recherche EDGVM">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                
                <div class="relative h-full flex items-center z-10">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-4xl">
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-6">
                                <div class="w-2 h-2 bg-ed-yellow rounded-full animate-pulse"></div>
                                <span class="text-white text-sm font-medium">Université de Mahajanga</span>
                            </div>
                            
                            <h1 class="text-3xl md:text-5xl font-black mb-6 leading-[1.1] text-white">
                                Le <span class="text-ed-yellow">Génie du Vivant</span><br>
                                au Service de l'Humanité
                            </h1>
                            
                            <p class="text-xl md:text-2xl mb-8 text-white/90 font-light max-w-2xl">
                                École Doctorale de recherche scientifique d'excellence
                            </p>
                            
                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('register') }}" class="group px-8 py-4 bg-ed-yellow text-gray-900 rounded-xl hover:bg-ed-yellow-dark transition-all duration-300 font-bold shadow-2xl hover:shadow-ed-yellow/50 hover:scale-[1.02] inline-flex items-center">
                                    <span>Candidater maintenant</span>
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('about') }}" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white border-2 border-white/30 rounded-xl hover:bg-white hover:text-ed-primary transition-all duration-300 font-bold hover:scale-[1.02]">
                                    Découvrir l'école
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div x-show="currentSlide === 1" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-1000"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 w-full h-full">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-800/95 via-ed-primary/90 to-green-900/95"></div>
                <img src="https://images.unsplash.com/photo-1576086213369-97a306d36557?w=1920&q=80" 
                     class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-20" 
                     alt="Recherche biotechnologie">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                
                <div class="relative h-full flex items-center z-10">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-4xl">
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-6">
                                <svg class="w-4 h-4 text-ed-yellow" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                <span class="text-white text-sm font-medium">8 Équipes de Recherche</span>
                            </div>
                            
                            <h1 class="text-3xl md:text-5xl font-black mb-6 leading-[1.1] text-white">
                                <span class="text-ed-yellow">Excellence</span> en<br>
                                Recherche Scientifique
                            </h1>
                            
                            <p class="text-xl md:text-2xl mb-8 text-white/90 font-light max-w-2xl">
                                Biotechnologie • Environnement • Santé • Géosciences
                            </p>
                            
                            <a href="{{ route('programmes') }}" class="group inline-flex items-center px-8 py-4 bg-ed-yellow text-gray-900 rounded-xl hover:bg-ed-yellow-dark transition-all duration-300 font-bold shadow-2xl hover:shadow-ed-yellow/50 hover:scale-[1.02]">
                                <span>Explorer nos recherches</span>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div x-show="currentSlide === 2" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-1000"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 w-full h-full">
                <div class="absolute inset-0 bg-gradient-to-br from-green-900/95 via-ed-secondary/90 to-teal-700/95"></div>
                <img src="https://images.unsplash.com/photo-1507413245164-6160d8298b31?w=1920&q=80" 
                     class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-20" 
                     alt="Doctorants EDGVM">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                
                <div class="relative h-full flex items-center z-10">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-4xl">
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-6">
                                <svg class="w-4 h-4 text-ed-yellow" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                                <span class="text-white text-sm font-medium">Formation Doctorale d'Excellence</span>
                            </div>
                            
                            <h1 class="text-3xl md:text-5xl font-black mb-6 leading-[1.1] text-white">
                                Rejoignez <span class="text-ed-yellow">109 Doctorants</span><br>
                                en Formation
                            </h1>
                            
                            <p class="text-xl md:text-2xl mb-8 text-white/90 font-light max-w-2xl">
                                23 thèses soutenues • 20 soutenances en cours
                            </p>
                            
                            <a href="{{ route('contact') }}" class="group inline-flex items-center px-8 py-4 bg-ed-yellow text-gray-900 rounded-xl hover:bg-ed-yellow-dark transition-all duration-300 font-bold shadow-2xl hover:shadow-ed-yellow/50 hover:scale-[1.02]">
                                <span>Nous contacter</span>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation moderne -->
        <button @click="previousSlide()" 
                class="absolute left-6 md:left-10 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-ed-primary transition-all duration-300 flex items-center justify-center group">
            <svg class="w-6 h-6 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button @click="nextSlide()" 
                class="absolute right-6 md:right-10 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-ed-primary transition-all duration-300 flex items-center justify-center group">
            <svg class="w-6 h-6 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- Pagination moderne -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="goToSlide(index)"
                        :class="currentSlide === index ? 'w-10 bg-white' : 'w-2 bg-white/40 hover:bg-white/60'"
                        class="h-2 rounded-full transition-all duration-300">
                </button>
            </template>
        </div>
    </section>