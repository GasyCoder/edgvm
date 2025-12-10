{{-- resources/views/livewire/frontend/contact-page.blade.php --}}
<div class="mt-20 lg:mt-24">
    {{-- HERO CONTACT ÉPURÉ --}}
    <section class="relative pt-20 pb-10 bg-white border-b border-gray-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                {{-- Colonne gauche : texte uniquement --}}
                <div class="space-y-4">
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-ed-primary/5 border border-ed-primary/15">
                        <span class="w-2 h-2 rounded-full bg-ed-primary"></span>
                        <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-ed-primary">
                            Contact EDGVM
                        </span>
                    </div>

                    {{-- Titre plus simple, moins lourd --}}
                    <h1 class="text-xl sm:text-2xl font-semibold leading-snug text-gray-900">
                        Une question sur l’EDGVM&nbsp;?<br>
                        <span class="text-ed-primary font-semibold">Écrivez-nous simplement.</span>
                    </h1>

                    {{-- Sous-titre plus court --}}
                    <p class="text-sm sm:text-base text-gray-600 max-w-xl">
                        Pour toute demande liée à la formation doctorale, aux projets de recherche
                        ou aux partenariats, l’équipe EDGVM reste à votre écoute.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION PRINCIPALE : FORMULAIRE + SIDEBAR --}}
    <section class="bg-gray-50 py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Grille principale --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">
                {{-- COLONNE PRINCIPALE : FORMULAIRE --}}
                <main class="lg:col-span-2">
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-5 py-7 md:px-8 md:py-8">
                        {{-- Header formulaire --}}
                        <div class="mt-6 mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg md:text-xl font-semibold text-gray-900">
                                    Envoyer un message à l’EDGVM
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">
                                    Les champs marqués d’un <span class="text-red-500">*</span> sont obligatoires.
                                </p>
                            </div>
                            <div class="flex items-center gap-2 text-[11px] text-gray-500">
                                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Réponse en général sous 2–3 jours ouvrés</span>
                            </div>
                        </div>

                        {{-- Message succès --}}
                        @if (session()->has('success'))
                            <div class="mb-5 flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif

                        {{-- FORMULAIRE --}}
                        <form wire:submit.prevent="submit" class="space-y-5">
                            {{-- Nom complet --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model.defer="fullName"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900
                                           focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60"
                                    placeholder="Ex. : RABE Jean Michel"
                                    autocomplete="name"
                                >
                                @error('fullName')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Téléphone + Email --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Téléphone
                                    </label>
                                    <input
                                        type="text"
                                        wire:model.defer="phone"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900
                                               focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60"
                                        placeholder="+261 ..."
                                        autocomplete="tel"
                                    >
                                    @error('phone')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        E-mail <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        wire:model.defer="email"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900
                                               focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60"
                                        placeholder="vous@example.com"
                                        autocomplete="email"
                                    >
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Objet --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Objet de la demande <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model.defer="subject"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900
                                           focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60"
                                >
                                    <option value="">Sélectionnez un objet</option>
                                    @foreach($subjectOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('subject')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Message --}}
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Message <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-gray-400">
                                        {{ strlen($messageContent ?? '') }}/250
                                    </span>
                                </div>
                                <textarea
                                    wire:model.defer="messageContent"
                                    rows="4"
                                    maxlength="250"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900
                                           focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60 resize-none"
                                    placeholder="Merci de décrire clairement votre demande (entre 10 et 250 caractères)."
                                ></textarea>
                                @error('messageContent')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Captcha --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Vérification anti-robot <span class="text-red-500">*</span>
                                </label>

                                @php
                                    // On construit le texte du captcha
                                    $captchaText = "{$captchaA} + {$captchaB} = ?";
                                @endphp

                                <div class="flex flex-wrap items-center gap-3">
                                    {{-- Zone captcha avec bruit + chiffres "bouleversés" --}}
                                    <div
                                        class="relative inline-flex items-center justify-center px-4 py-2 rounded-xl
                                            border border-ed-primary/40 bg-slate-900 text-amber-200
                                            font-mono text-sm shadow-inner overflow-hidden"
                                    >
                                        {{-- Motif points --}}
                                        <div
                                            class="pointer-events-none absolute inset-0 opacity-25
                                                bg-[radial-gradient(circle_at_1px_1px,#ffffff_0.6px,transparent_0)]
                                                [background-size:6px_6px]"
                                        ></div>

                                        {{-- Motif rayures obliques --}}
                                        <div
                                            class="pointer-events-none absolute inset-0 opacity-30 mix-blend-soft-light
                                                bg-[repeating-linear-gradient(135deg,rgba(255,255,255,0.35)_0,rgba(255,255,255,0.35)_1px,transparent_1px,transparent_5px)]"
                                        ></div>

                                        {{-- Chiffres / caractères "bouleversés" --}}
                                        <div class="relative z-10 flex items-center">
                                            @foreach(str_split($captchaText) as $char)
                                                @php
                                                    // Rotation aléatoire entre -18° et +18°
                                                    $rotate = rand(-18, 18);
                                                    // Décalage vertical entre -4px et +4px
                                                    $translateY = rand(-4, 4);
                                                    // Taille aléatoire entre 12px et 18px
                                                    $fontSize = rand(12, 18);
                                                    // Opacité entre 0.75 et 1
                                                    $opacity = rand(75, 100) / 100;
                                                @endphp

                                                <span
                                                    class="inline-block mx-[2px] select-none"
                                                    style="
                                                        transform: translateY({{ $translateY }}px) rotate({{ $rotate }}deg);
                                                        font-size: {{ $fontSize }}px;
                                                        opacity: {{ $opacity }};
                                                    "
                                                >
                                                    {{ $char }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Champ de réponse --}}
                                    <input
                                        type="number"
                                        wire:model.defer="captchaAnswer"
                                        class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-900
                                            focus:bg-white focus:border-ed-primary focus:ring-1 focus:ring-ed-primary/60"
                                        placeholder="Réponse"
                                    >

                                    {{-- Bouton refresh --}}
                                    <button
                                        type="button"
                                        wire:click="generateCaptcha"
                                        class="inline-flex items-center gap-1 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-600
                                            hover:border-ed-primary/60 hover:text-ed-primary transition-colors"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v6h6M20 20v-6h-6M5 13a7 7 0 0112-5M19 11a7 7 0 01-12 5"/>
                                        </svg>
                                        <span>Rafraîchir</span>
                                    </button>
                                </div>

                                @error('captchaAnswer')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Bouton d’envoi BIEN VISIBLE --}}
                            <div class="pt-1 flex justify-end">
                                <button
                                    type="submit"
                                    wire:target="submit"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="cursor-not-allowed opacity-70"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
                                        bg-ed-primary text-white text-sm font-semibold
                                        shadow-md hover:bg-ed-secondary mb-6
                                        focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:ring-offset-1"
                                >
                                    {{-- Icône spinner : uniquement en loading --}}
                                    <svg
                                        wire:loading
                                        wire:target="submit"
                                        class="w-4 h-4 animate-spin"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12" cy="12" r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8v4l3.5-3.5A8 8 0 014 12z"
                                        ></path>
                                    </svg>

                                    {{-- Texte normal (hors loading) --}}
                                    <span wire:loading.remove wire:target="submit">
                                        Envoyer le message
                                    </span>

                                    {{-- Texte en loading : même ligne que le spinner --}}
                                    <span wire:loading wire:target="submit">
                                        Envoi en cours...
                                    </span>

                                    {{-- Flèche à droite (hors loading) --}}
                                    <svg
                                        wire:loading.remove
                                        wire:target="submit"
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1"
                                            d="M5 12h14m0 0l-6-6m6 6-6 6" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    {{-- CARTE GOOGLE MAPS SOUS LE FORMULAIRE : RECTANGLE LARGE --}}
                    <div class="mt-10">
                        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 pt-4 pb-3 flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">
                                        Localisation de l’EDGVM
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Campus de l’Université de Mahajanga – carte indicative.
                                    </p>
                                </div>

                                {{-- Bouton lien Google Maps --}}
                                <a
                                    href="https://maps.app.goo.gl/voZ55LuSPhUhLPgeA"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full
                                        text-[11px] font-medium text-ed-primary bg-ed-primary/5 border border-ed-primary/20
                                        hover:bg-ed-primary hover:text-white transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M12 2.75C8.824 2.75 6.25 5.324 6.25 8.5c0 4.151 3.932 8.323 5.322 9.708a.6.6 0 0 0 .856 0C13.818 16.823 17.75 12.651 17.75 8.5 17.75 5.324 15.176 2.75 12 2.75Z"
                                            stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                                        />
                                        <circle
                                            cx="12" cy="8.75" r="2.25"
                                            stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                                        />
                                    </svg>
                                    <span>Ouvrir dans Google Maps</span>
                                </a>
                            </div>

                            <div class="border-t border-gray-100">
                                {{-- Hauteur bien visible : plus haute sur desktop --}}
                                <div class="w-full h-64 sm:h-72 lg:h-96">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d38285.076541149974!2d46.304207461175146!3d-15.724177142489477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2203fb01d46585b9%3A0x97be146e7e440729!2sB%C3%A2timent%20Kakal%20Universit%C3%A9%20de%20Mahajanga!5e1!3m2!1sfr!2smg!4v1765391554859!5m2!1sfr!2smg"
                                        style="border:0;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                        class="w-full h-full"
                                    ></iframe>
                                </div>

                            </div>
                        </div>
                    </div>

                </main>

                {{-- SIDEBAR DROITE --}}
                <aside class="space-y-6 lg:col-span-1 lg:self-start">
                    {{-- Coordonnées --}}
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm">
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-9 w-9 rounded-2xl bg-ed-primary/10 flex items-center justify-center">
                                    <svg
                                        class="w-4 h-4 text-ed-primary"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        {{-- Icône localisation simple et bien lisible --}}
                                        <path d="M12 2.25C8.27 2.25 5.25 5.27 5.25 9c0 4.49 5 9.68 6.34 11.04a1 1 0 0 0 1.42 0C13.99 18.68 18.75 13.49 18.75 9c0-3.73-3.02-6.75-6.75-6.75zm0 9a2.25 2.25 0 1 1 0-4.5 2.25 2.25 0 0 1 0 4.5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-ed-primary">
                                        Coordonnées
                                    </p>
                                    <h2 class="text-sm font-semibold text-gray-900">
                                        École Doctorale EDGVM
                                    </h2>
                                </div>
                            </div>

                            <ul class="space-y-3 text-sm text-gray-700">
                                <li>
                                    Université de Mahajanga<br>
                                    <span class="text-xs text-gray-500">
                                        École Doctorale Génie du Vivant et Modélisation – Mahajanga, Madagascar
                                    </span>
                                </li>

                                <li class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-ed-primary/10 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <a href="mailto:contact@edgvm.mg"
                                       class="hover:text-ed-primary transition-colors">
                                        contact@edgvm.mg
                                    </a>
                                </li>

                                <li class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-ed-primary/10 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.69l1.49 4.47a1 1 0 01-.5 1.21l-2.24 1.12a11 11 0 005.5 5.5l1.12-2.24a1 1 0 011.21-.5l4.47 1.49a1 1 0 01.69.95V19a2 2 0 01-2 2h-1C9.7 21 3 14.3 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <span>+261 32 00 000 00</span>
                                </li>
                            </ul>

                            <p class="mt-4 text-xs leading-relaxed text-gray-500">
                                Pour les demandes administratives spécifiques (inscription, soutenance, etc.),
                                vous pouvez également contacter directement le secrétariat de l’École Doctorale.
                            </p>
                        </div>
                    </div>

                    {{-- Infos + réseaux sociaux --}}
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm">
                        <div class="p-5 space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-1">
                                    Horaires & délais
                                </h3>
                                <ul class="space-y-1 text-xs text-gray-600">
                                    <li>
                                        <span class="font-medium text-gray-800">Secrétariat :</span>
                                        lundi – vendredi, 7h30 – 18h00.
                                    </li>
                                    <li>
                                        <span class="font-medium text-gray-800">Réponse par e-mail :</span>
                                        en général sous 1 à 2 jours ouvrés.
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-2">
                                    Suivez l’EDGVM
                                </h3>
                                <div class="flex flex-wrap gap-3">
                                    {{-- Facebook --}}
                                    <a href="#"
                                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold
                                              bg-blue-600/10 text-blue-700 border border-blue-600/20
                                              hover:bg-blue-600 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M22.675 0H1.325C.593 0 0 .593 0 1.326v21.348C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.127V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.407 24 24 23.407 24 22.674V1.326C24 .593 23.407 0 22.675 0z"/>
                                        </svg>
                                        <span>Facebook</span>
                                    </a>

                                    {{-- LinkedIn --}}
                                    <a href="#"
                                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold
                                              bg-sky-700/10 text-sky-800 border border-sky-700/20
                                              hover:bg-blue-700 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zM6.674 20.452H3.996V9h2.678v11.452z"/>
                                        </svg>
                                        <span>LinkedIn</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
