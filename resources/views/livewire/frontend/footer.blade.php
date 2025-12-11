<footer class="relative bg-ed-primary text-white/90">
    <div class="absolute inset-x-0 -top-px h-[2px] bg-gradient-to-r from-ed-yellow via-white/70 to-ed-secondary opacity-90"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-12">
            
            {{-- Bloc À propos --}}
            <div class="md:col-span-5">
                <div class="flex items-center space-x-3 mb-5">
                    <div class="bg-ed-secondary rounded-xl p-2.5">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold tracking-tight">EDGVM</h3> 
                        <p class="text-xs text-white/70">École Doctorale Génie du Vivant et Modélisation</p>
                    </div>
                </div>

                <p class="text-sm text-white/80 leading-relaxed mb-5">
                    {{ $appSettings->meta_description 
                        ?? "École Doctorale de l’Université de Mahajanga, dédiée à la formation doctorale, à la recherche scientifique et à l’innovation dans les domaines du vivant et de la modélisation." }}
                </p>

                {{-- Réseaux sociaux --}}
                <div class="flex flex-wrap gap-3">
                    @if(!empty($appSettings->facebook_url))
                        <a href="{{ $appSettings->facebook_url }}" target="_blank"
                           class="w-9 h-9 rounded-full border border-white/25 flex items-center justify-center 
                                  text-white/80 hover:text-ed-primary hover:bg-ed-yellow transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073C24 5.403 18.627 0 12 0S0 5.403 0 12.073C0 18.09 4.388 22.953 10.125 24v-8.385H7.078v-3.542h3.047V9.356c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953h-1.513c-1.491 0-1.956.925-1.956 1.874v2.24h3.328l-.532 3.542h-2.796V24C19.612 22.953 24 18.09 24 12.073z"/>
                            </svg>
                        </a>
                    @endif

                    @if(!empty($appSettings->linkedin_url))
                        <a href="{{ $appSettings->linkedin_url }}" target="_blank"
                           class="w-9 h-9 rounded-full border border-white/25 flex items-center justify-center 
                                  text-white/80 hover:text-ed-primary hover:bg-ed-yellow transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.026-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.047c.476-.9 1.636-1.851 3.369-1.851 3.602 0 4.266 2.372 4.266 5.456v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zM6.97 20.452H3.701V9H6.97v11.452z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

                       {{-- Bloc Liens rapides (inchangé) --}}
            <div class="md:col-span-3">
                <h4 class="text-sm font-semibold tracking-wide uppercase mb-5 text-white/80">
                    Liens rapides
                </h4>
                <ul class="space-y-2.5 text-sm">
                    <li>
                        <a href="{{ route('pages.show', 'a-propos') }}" 
                           class="flex items-center gap-2 text-white/70 hover:text-ed-yellow transition-colors">
                            <span class="w-1 h-1 rounded-full bg-ed-yellow"></span>
                            <span>À propos de l’EDGVM</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('programmes.index') }}" 
                           class="flex items-center gap-2 text-white/70 hover:text-ed-yellow transition-colors">
                            <span class="w-1 h-1 rounded-full bg-ed-yellow"></span>
                            <span>Parcours et équipes de recherche</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('actualites.index') }}" 
                           class="flex items-center gap-2 text-white/70 hover:text-ed-yellow transition-colors">
                            <span class="w-1 h-1 rounded-full bg-ed-yellow"></span>
                            <span>Actualités &amp; événements</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" 
                           class="flex items-center gap-2 text-white/70 hover:text-ed-yellow transition-colors">
                            <span class="w-1 h-1 rounded-full bg-ed-yellow"></span>
                            <span>Contact &amp; informations pratiques</span>
                        </a>
                    </li>
                </ul>
            </div>


            {{-- Bloc Contact --}}
            <div class="md:col-span-4">
                <h4 class="text-sm font-semibold tracking-wide uppercase mb-5 text-white/80">
                    Contact
                </h4>
                <ul class="space-y-3.5 text-sm text-white/80">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                            <svg class="w-5 h-5 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <span>
                            {{ $appSettings->site_address ?? "Université de Mahajanga" }}<br>
                            <span class="text-white/60">Mahajanga, Madagascar</span>
                        </span>
                    </li>

                    @if(!empty($appSettings->site_email))
                        <li class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                                <svg class="w-5 h-5 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <a href="mailto:{{ $appSettings->site_email }}" class="hover:text-ed-yellow transition-colors">
                                {{ $appSettings->site_email }}
                            </a>
                        </li>
                    @endif

                    @if(!empty($appSettings->site_phone))
                        <li class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                                <svg class="w-5 h-5 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 19.25V21a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 8.999V7a2 2 0 012-2z"/>
                                </svg>
                            </span>
                            <span>{{ $appSettings->site_phone }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Bas de page --}}
        <div class="border-t border-white/10 mt-10 pt-6 text-center space-y-1.5">
            <p class="text-xs sm:text-sm text-white/60">
                &copy; {{ date('Y') }} {{ $appSettings->site_name ?? 'EDGVM – École Doctorale Génie du Vivant et Modélisation' }}. Tous droits réservés.
            </p>
            <p class="text-[11px] text-white/40">
                Plateforme développée par 
                <a href="#" target="_blank" class="text-ed-yellow hover:text-ed-yellow/80 font-semibold transition-colors">
                    DTIC – Université de Mahajanga
                </a>
            </p>
        </div>
    </div>
</footer>
