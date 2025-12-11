<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-lg md:text-xl text-slate-900 leading-tight">
                    Paramètres du site
                </h2>
                <p class="text-xs md:text-sm text-slate-500 mt-0.5">
                    Configuration générale, SEO, apparence, maintenance et sécurité.
                </p>
            </div>
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full
                         text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Paramètres</span>
            </span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6 space-y-6">

        {{-- MESSAGES FLASH --}}
        @if(session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs md:text-sm text-emerald-800 flex items-start gap-2">
                <span class="mt-0.5">✅</span>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if(session('password_status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs md:text-sm text-emerald-800 flex items-start gap-2">
                <span class="mt-0.5">🔐</span>
                <span>{{ session('password_status') }}</span>
            </div>
        @endif

        @if(session('secretary_status'))
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs md:text-sm text-amber-800 flex items-start gap-2">
                <span class="mt-0.5">👤</span>
                <span>{{ session('secretary_status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs md:text-sm text-red-800">
                <p class="font-semibold mb-1">Certaines informations sont à corriger :</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULAIRE PRINCIPAL PARAMÈTRES --}}
        <form wire:submit.prevent="save" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Colonne 1/2 : Général + Contact/RS --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Infos générales --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 md:p-6">
                        <h3 class="text-sm font-semibold text-slate-900 mb-3 flex items-center gap-2">
                            <span class="inline-flex w-7 h-7 items-center justify-center rounded-xl bg-ed-primary/10 text-ed-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 7h18M3 12h18M3 17h18"/>
                                </svg>
                            </span>
                            <span>Informations générales</span>
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nom du site</label>
                                <input type="text"
                                       wire:model.defer="site_name"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Titre SEO principal</label>
                                <input type="text"
                                       wire:model.defer="seo_title"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                                <p class="mt-1 text-[11px] text-slate-500">
                                    Utilisé comme titre par défaut si aucun titre spécifique n’est défini.
                                </p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Description SEO globale</label>
                                <textarea rows="3"
                                          wire:model.defer="meta_description"
                                          class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                                 focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Mots-clés (séparés par des virgules)
                                </label>
                                <textarea rows="2"
                                          wire:model.defer="meta_keywords"
                                          class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                                 focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                          placeholder="ex : doctorat, EDGVM, recherche, sciences du vivant"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Contact + Réseaux sociaux --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 md:p-6">
                        <h3 class="text-sm font-semibold text-slate-900 mb-3 flex items-center gap-2">
                            <span class="inline-flex w-7 h-7 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5h18M8 5v14m8-14v14M5 19h2m10 0h2"/>
                                </svg>
                            </span>
                            <span>Contacts & réseaux sociaux</span>
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email du site</label>
                                <input type="email"
                                       wire:model.defer="site_email"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Téléphone</label>
                                <input type="text"
                                       wire:model.defer="site_phone"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Adresse</label>
                                <textarea rows="2"
                                          wire:model.defer="site_address"
                                          class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                                 focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Facebook</label>
                                <input type="url"
                                       wire:model.defer="facebook_url"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">X / Twitter</label>
                                <input type="url"
                                       wire:model.defer="twitter_url"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">LinkedIn</label>
                                <input type="url"
                                       wire:model.defer="linkedin_url"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">YouTube</label>
                                <input type="url"
                                       wire:model.defer="youtube_url"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Instagram</label>
                                <input type="url"
                                       wire:model.defer="instagram_url"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">URL du sitemap</label>
                                <input type="url"
                                       wire:model.defer="sitemap_url"
                                       placeholder="https://edgvm.mg/sitemap.xml"
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Colonne 3 : Apparence + Maintenance --}}
                <div class="space-y-6">
                    {{-- Apparence --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 md:p-6">
                        <h3 class="text-sm font-semibold text-slate-900 mb-3 flex items-center gap-2">
                            <span class="inline-flex w-7 h-7 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 7h18M3 12h18M3 17h18"/>
                                </svg>
                            </span>
                            <span>Apparence (logo & favicon)</span>
                        </h3>

                        <div class="space-y-4 text-sm">
                            {{-- Logo --}}
                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-slate-700">
                                    Logo du site
                                </label>
                                @if($logo_current_path)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/'.$logo_current_path) }}"
                                             alt="Logo actuel"
                                             class="h-10 object-contain bg-slate-50 rounded-lg border border-slate-200 px-2 py-1">
                                        <span class="text-[11px] text-slate-500">Logo actuel</span>
                                    </div>
                                @else
                                    <p class="text-[11px] text-slate-500">
                                        Aucun logo défini pour le moment.
                                    </p>
                                @endif
                                <input type="file"
                                       wire:model="logoUpload"
                                       class="mt-1 block w-full text-xs text-slate-600
                                              file:mr-3 file:py-1.5 file:px-3
                                              file:rounded-full file:border-0
                                              file:text-xs file:font-semibold
                                              file:bg-slate-100 file:text-slate-700
                                              hover:file:bg-slate-200">
                                @error('logoUpload')
                                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Favicon --}}
                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-slate-700">
                                    Favicon
                                </label>
                                @if($favicon_current_path)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/'.$favicon_current_path) }}"
                                             alt="Favicon actuel"
                                             class="h-6 w-6 object-contain bg-slate-50 rounded border border-slate-200">
                                        <span class="text-[11px] text-slate-500">Favicon actuel</span>
                                    </div>
                                @else
                                    <p class="text-[11px] text-slate-500">
                                        Aucun favicon défini pour le moment.
                                    </p>
                                @endif
                                <input type="file"
                                       wire:model="faviconUpload"
                                       class="mt-1 block w-full text-xs text-slate-600
                                              file:mr-3 file:py-1.5 file:px-3
                                              file:rounded-full file:border-0
                                              file:text-xs file:font-semibold
                                              file:bg-slate-100 file:text-slate-700
                                              hover:file:bg-slate-200">
                                <p class="mt-1 text-[11px] text-slate-500">
                                    De préférence carré (ex : 64×64, 128×128).
                                </p>
                                @error('faviconUpload')
                                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Maintenance --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 md:p-6">
                        <h3 class="text-sm font-semibold text-slate-900 mb-3 flex items-center gap-2">
                            <span class="inline-flex w-7 h-7 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <span>Mode maintenance</span>
                        </h3>

                        <div class="space-y-3 text-sm">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                       wire:model="maintenance_mode"
                                       class="rounded border-slate-300 text-ed-primary focus:ring-ed-primary">
                                <span class="text-slate-700">
                                    Activer le mode maintenance
                                </span>
                            </label>
                            <p class="text-[11px] text-slate-500">
                                La logique d’affichage de la page de maintenance sera à brancher dans un middleware global.
                            </p>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Message de maintenance
                                </label>
                                <textarea rows="3"
                                          wire:model.defer="maintenance_message"
                                          class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                                 focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                          placeholder="Ex : Le site est temporairement en maintenance pour mise à jour. Merci de votre compréhension."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bouton sauvegarde --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-5 py-2.5 text-sm font-semibold text-white
                               shadow-sm hover:bg-ed-secondary focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:ring-offset-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Enregistrer les paramètres</span>
                </button>
            </div>
        </form>

        {{-- Bloc Sécurité : mot de passe + secrétaire --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Mot de passe admin --}}
            <form wire:submit.prevent="updatePassword"
                  class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 md:p-6 space-y-4">
                <h3 class="text-sm font-semibold text-slate-900 mb-1 flex items-center gap-2">
                    <span class="inline-flex w-7 h-7 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 11c.828 0 1.5-.672 1.5-1.5S12.828 8 12 8s-1.5.672-1.5 1.5S11.172 11 12 11zm0 2v3m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2h-1V7a5 5 0 00-10 0v2H6a2 2 0 00-2 2v7a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <span>Sécurité : mot de passe administrateur</span>
                </h3>
                <p class="text-[11px] text-slate-500">
                    Modifier le mot de passe de votre compte administrateur connecté.
                </p>

                <div class="space-y-3 text-sm">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Mot de passe actuel
                        </label>
                        <input type="password"
                               wire:model.defer="current_password"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                        @error('current_password')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nouveau mot de passe
                        </label>
                        <input type="password"
                               wire:model.defer="password"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                        @error('password')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Confirmation du nouveau mot de passe
                        </label>
                        <input type="password"
                               wire:model.defer="password_confirmation"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs md:text-sm font-semibold text-white
                                   shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500/70 focus:ring-offset-1">
                        <span>Mettre à jour le mot de passe</span>
                    </button>
                </div>
            </form>

            {{-- Création compte secrétaire --}}
            <form wire:submit.prevent="createSecretary"
                  class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 md:p-6 space-y-4">
                <h3 class="text-sm font-semibold text-slate-900 mb-1 flex items-center gap-2">
                    <span class="inline-flex w-7 h-7 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </span>
                    <span>Créer un compte secrétaire</span>
                </h3>
                <p class="text-[11px] text-slate-500">
                    Création rapide d’un compte avec le rôle <strong>secrétaire</strong>.
                </p>

                <div class="space-y-3 text-sm">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nom complet
                        </label>
                        <input type="text"
                               wire:model.defer="secretary_name"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                        @error('secretary_name')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Adresse email
                        </label>
                        <input type="email"
                               wire:model.defer="secretary_email"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                        @error('secretary_email')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Mot de passe (laisser vide pour générer automatiquement)
                        </label>
                        <input type="password"
                               wire:model.defer="secretary_password"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                        @error('secretary_password')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs md:text-sm font-semibold text-white
                                   shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/70 focus:ring-offset-1">
                        <span>Créer le compte secrétaire</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
