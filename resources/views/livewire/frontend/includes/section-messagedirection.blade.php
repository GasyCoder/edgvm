@props(['messageDirection', 'stats'])

@if($messageDirection)
    <section class="py-24 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-ed-primary/10 rounded-full mb-6">
                    <div class="w-2 h-2 bg-ed-primary rounded-full"></div>
                    <span class="text-ed-primary font-semibold text-sm">Direction</span>
                </div>
                <h2 class="text-4xl md:text-4xl font-black text-gray-900">
                    Mot de la Directrice
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Photo -->
                <div class="relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                        <img 
                            src="{{ $messageDirection->photo_path 
                                    ? asset('storage/'.$messageDirection->photo_path) 
                                    : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&q=80' }}"
                            alt="{{ $messageDirection->nom }}" 
                            class="w-full h-[550px] object-cover">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-ed-primary/90 via-ed-primary/40 to-transparent"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                            <h3 class="text-3xl font-black mb-2">{{ $messageDirection->nom }}</h3>
                            @if($messageDirection->fonction)
                                <p class="text-lg text-white/90 mb-1">{{ $messageDirection->fonction }}</p>
                            @endif
                            @if($messageDirection->institution)
                                <p class="text-white/80 text-sm mb-6">{{ $messageDirection->institution }}</p>
                            @endif
                            
                            <div class="flex flex-wrap gap-3">
                                @if($messageDirection->telephone)
                                    <div class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-lg border border-white/20">
                                        <svg class="w-4 h-4 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $messageDirection->telephone }}</span>
                                    </div>
                                @endif

                                @if($messageDirection->email)
                                    <div class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-lg border border-white/20">
                                        <svg class="w-4 h-4 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $messageDirection->email }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-72 h-72 bg-ed-yellow/20 rounded-full blur-3xl -z-10"></div>
                </div>

                <!-- Message -->
                <div class="space-y-6">
                    <div class="relative bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-gray-100">
                        <!-- Quote -->
                        <svg class="absolute -top-4 -left-4 w-12 h-12 text-ed-yellow/20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                        
                        @if($messageDirection->citation)
                            <p class="text-lg text-gray-700 leading-relaxed mb-6 italic">
                                "{{ $messageDirection->citation }}"
                            </p>
                        @endif
                        
                        @if($messageDirection->message_intro)
                            <p class="text-base text-gray-600 leading-relaxed mb-6">
                                {!! nl2br(e($messageDirection->message_intro)) !!}
                            </p>
                        @endif
                        
                        <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                            <div class="flex-grow">
                                <p class="font-bold text-ed-primary text-lg">{{ $messageDirection->nom }}</p>
                                @if($messageDirection->fonction)
                                    <p class="text-gray-600 text-sm">{{ $messageDirection->fonction }}</p>
                                @endif
                            </div>
                            <div class="w-14 h-1 bg-ed-yellow rounded-full"></div>
                        </div>
                    </div>

                    <!-- Chiffres -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white rounded-xl shadow-lg p-5 text-center border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <p class="text-3xl font-black text-ed-primary mb-1">{{ $stats['doctorants'] }}</p>
                            <p class="text-xs text-gray-600 font-medium">Doctorants</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-5 text-center border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <p class="text-3xl font-black text-ed-primary mb-1">{{ $stats['equipes'] }}</p>
                            <p class="text-xs text-gray-600 font-medium">Équipes</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-5 text-center border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <p class="text-3xl font-black text-ed-primary mb-1">{{ $stats['theses_soutenues'] }}</p>
                            <p class="text-xs text-gray-600 font-medium">Thèses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
