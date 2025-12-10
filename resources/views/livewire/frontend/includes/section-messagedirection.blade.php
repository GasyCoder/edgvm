@props(['messageDirection', 'stats'])

@if($messageDirection)
    <section class="py-16 md:py-20 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Titre --}}
            <div class="text-center mb-8 md:mb-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-ed-primary/10 rounded-full mb-3">
                    <div class="w-2 h-2 bg-ed-primary rounded-full"></div>
                    <span class="text-ed-primary font-semibold text-xs md:text-sm">Direction</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                    Mot de la Directrice
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <!-- Photo -->
                <div class="relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-xl">
                        <img 
                            src="{{ $messageDirection->photo_path 
                                    ? asset('storage/'.$messageDirection->photo_path) 
                                    : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&q=80' }}"
                            alt="{{ $messageDirection->nom }}" 
                            class="w-full h-72 md:h-[380px] lg:h-[420px] object-cover object-top">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-ed-primary/85 via-ed-primary/40 to-transparent"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-5 md:p-6 text-white">
                            {{-- <h3 class="text-2xl md:text-3xl font-black mb-1.5">
                                {{ $messageDirection->nom }}
                            </h3> --}}
                            @if($messageDirection->fonction)
                                <p class="text-sm md:text-base text-white/90 mb-0.5">
                                    {{ $messageDirection->fonction }}
                                </p>
                            @endif
                            {{-- @if($messageDirection->institution)
                                <p class="text-xs md:text-sm text-white/80 mb-4">
                                    {{ $messageDirection->institution }}
                                </p>
                            @endif --}}
                            
                            <div class="flex flex-wrap gap-2.5">
                                @if($messageDirection->telephone)
                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-md rounded-lg border border-white/15">
                                        <svg class="w-4 h-4 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <span class="text-xs md:text-sm">{{ $messageDirection->telephone }}</span>
                                    </div>
                                @endif

                                @if($messageDirection->email)
                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-md rounded-lg border border-white/15">
                                        <svg class="w-4 h-4 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs md:text-sm">{{ $messageDirection->email }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-52 h-52 bg-ed-yellow/20 rounded-full blur-3xl -z-10"></div>
                </div>

                <!-- Message -->
                <div class="space-y-5">
                    <div class="relative bg-white rounded-3xl p-6 md:p-7 shadow-md border border-gray-100">
                        <!-- Quote -->
                        <svg class="absolute -top-3 -left-3 w-9 h-9 text-ed-yellow/20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                        
                        @if($messageDirection->citation)
                            <p class="text-base md:text-lg text-gray-700 leading-relaxed mb-4 italic">
                                "{{ $messageDirection->citation }}"
                            </p>
                        @endif
                        
                        @if($messageDirection->message_intro)
                            <p class="text-sm md:text-base text-gray-600 leading-relaxed mb-2">
                                {!! nl2br(e($messageDirection->message_intro)) !!}
                            </p>
                        @endif
                        
                        <div class="flex items-center gap-3 pt-4 border-t border-gray-100 mt-4">
                            <div class="flex-grow">
                                <p class="font-bold text-ed-primary text-sm md:text-base">
                                    {{ $messageDirection->nom }}
                                </p>
                                @if($messageDirection->fonction)
                                    <p class="text-gray-600 text-xs md:text-sm">
                                        {{ $messageDirection->fonction }} - {{ $messageDirection->institution }}
                                    </p>
                                @endif
                            </div>
                            <div class="w-12 h-1 bg-ed-yellow rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
