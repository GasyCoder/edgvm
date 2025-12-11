<div class="max-w-10xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
    {{-- En-tête --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Paramètres du site
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Configurez l’identité du site, les informations de contact, le SEO et la maintenance.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-full bg-ed-primary/10 text-ed-primary text-xs font-semibold">
                {{ auth()->user()->email ?? '' }}
            </span>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-900 text-white text-xs font-semibold shadow-sm">
                Espace administration
            </span>
        </div>
    </div>

    {{-- Messages flash / erreurs globales --}}
    <div class="space-y-3">
        @if (session()->has('settings_saved'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 flex items-start gap-2">
                <span class="mt-0.5">
                    ✅
                </span>
                <p>{{ session('settings_saved') }}</p>
            </div>
        @endif

        @if (session()->has('security_updated'))
            <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 flex items-start gap-2">
                <span class="mt-0.5">
                    🔐
                </span>
                <p>{{ session('security_updated') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- COLONNE GAUCHE / CENTRE : PARAMÈTRES GÉNÉRAUX + SEO + MAINTENANCE --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Bloc identité du site / contact --}}
            <form wire:submit.prevent="save" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">
                            Identité du site & contacts
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Nom, coordonnées et informations publiques affichées sur le site.
                        </p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-ed-primary/10 text-ed-primary text-[11px] font-semibold">
                        Général
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Nom du site --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Nom du site
                        </label>
                        <input type="text"
                               wire:model.defer="settings.site_name"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="Ex : EDGVM – Université de Mahajanga">
                        @error('settings.site_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slogan / baseline (optionnel) --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Slogan / baseline (optionnel)
                        </label>
                        <input type="text"
                               wire:model.defer="settings.site_baseline"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="Ex : Génie du vivant et modélisation">
                        @error('settings.site_baseline')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email de contact --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Email de contact
                        </label>
                        <input type="email"
                               wire:model.defer="settings.contact_email"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="contact@edgvm.mg">
                        @error('settings.contact_email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Téléphone --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Téléphone
                        </label>
                        <input type="text"
                               wire:model.defer="settings.contact_phone"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="+261 xx xx xxx xx">
                        @error('settings.contact_phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Adresse --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Adresse postale
                        </label>
                        <input type="text"
                               wire:model.defer="settings.contact_address"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="Campus Ambondrona, Université de Mahajanga, Madagascar">
                        @error('settings.contact_address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Réseaux sociaux --}}
                <div class="border-t border-gray-100 pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Facebook
                        </label>
                        <input type="text"
                               wire:model.defer="settings.social_facebook"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="https://facebook.com/…">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            X / Twitter
                        </label>
                        <input type="text"
                               wire:model.defer="settings.social_twitter"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="https://x.com/…">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            LinkedIn
                        </label>
                        <input type="text"
                               wire:model.defer="settings.social_linkedin"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="https://linkedin.com/…">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            YouTube
                        </label>
                        <input type="text"
                               wire:model.defer="settings.social_youtube"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="https://youtube.com/…">
                    </div>
                </div>

                <div class="flex justify-end pt-3 border-t border-gray-100 mt-2">
                    <button type="submit"
                            wire:target="save"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-ed-primary text-white text-sm font-semibold
                                   shadow-sm hover:bg-ed-secondary focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:ring-offset-1
                                   disabled:opacity-60">
                        <span wire:loading.remove wire:target="save">Enregistrer les paramètres</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Enregistrement…</span>
                        </span>
                    </button>
                </div>
            </form>

            {{-- Bloc SEO / META / SITEMAP --}}
            <form wire:submit.prevent="saveSeo" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">
                            SEO & métadonnées
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Titre, description et mots-clés pour le référencement.
                        </p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[11px] font-semibold border border-amber-100">
                        SEO
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Titre par défaut (balise &lt;title&gt;)
                        </label>
                        <input type="text"
                               wire:model.defer="settings.meta_title"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="EDGVM – École Doctorale Génie du Vivant et Modélisation">
                        @error('settings.meta_title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Meta description
                        </label>
                        <textarea rows="3"
                                  wire:model.defer="settings.meta_description"
                                  class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                         focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                  placeholder="Brève description de l’EDGVM pour les moteurs de recherche…"></textarea>
                        @error('settings.meta_description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Mots-clés (séparés par des virgules)
                        </label>
                        <input type="text"
                               wire:model.defer="settings.meta_keywords"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="doctorat, EDGVM, Mahajanga, génie du vivant, modélisation…">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            URL du sitemap (si généré)
                        </label>
                        <input type="text"
                               wire:model.defer="settings.sitemap_url"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                      focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                               placeholder="{{ url('sitemap.xml') }}">
                    </div>
                </div>

                <div class="flex justify-end pt-3 border-t border-gray-100 mt-2">
                    <button type="submit"
                            wire:target="saveSeo"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-semibold
                                   shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-800/60 focus:ring-offset-1
                                   disabled:opacity-60">
                        <span wire:loading.remove wire:target="saveSeo">Mettre à jour le SEO</span>
                        <span wire:loading wire:target="saveSeo" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Enregistrement…</span>
                        </span>
                    </button>
                </div>
            </form>

            {{-- Bloc Maintenance --}}
            <form wire:submit.prevent="saveMaintenance" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">
                            Mode maintenance
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Activez la maintenance avec un message personnalisé pour les visiteurs.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer"
                                   wire:model.defer="settings.maintenance_enabled">
                            <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full
                                        peer peer-checked:bg-ed-primary
                                        relative transition-colors">
                                <span class="absolute left-0.5 top-0.5 h-4 w-4 bg-white rounded-full shadow
                                             transition-transform peer-checked:translate-x-5"></span>
                            </div>
                            <span class="ml-2 text-xs font-medium text-gray-700">
                                @if($settings['maintenance_enabled'] ?? false)
                                    Activé
                                @else
                                    Désactivé
                                @endif
                            </span>
                        </label>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Message affiché aux visiteurs
                        </label>
                        <textarea rows="3"
                                  wire:model.defer="settings.maintenance_message"
                                  class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm
                                         focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                  placeholder="Le site est momentanément en maintenance. Merci de revenir ultérieurement."></textarea>
                    </div>

                    <p class="text-[11px] text-gray-500">
                        Astuce : laissez l’accès normal pour les comptes administrateurs pendant la maintenance.
                    </p>
                </div>

                <div class="flex justify-end pt-3 border-t border-gray-100 mt-2">
                    <button type="submit"
                            wire:target="saveMaintenance"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 text-white text-sm font-semibold
                                   shadow-sm hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500/60 focus:ring-offset-1
                                   disabled:opacity-60">
                        <span wire:loading.remove wire:target="saveMaintenance">Mettre à jour le mode maintenance</span>
                        <span wire:loading wire:target="saveMaintenance" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Enregistrement…</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        {{-- COLONNE DROITE : LOGO / FAVICON / SÉCURITÉ --}}
        <div class="space-y-6">
            {{-- Logo & favicon --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">
                            Logo & favicon
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Identité visuelle utilisée dans le frontend et l’administration.
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    {{-- Logo --}}
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 rounded-2xl border border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden">
                            @if($current_logo_url ?? false)
                                <img src="{{ $current_logo_url }}" alt="Logo actuel" class="w-full h-full object-contain">
                            @else
                                <span class="text-[11px] text-gray-400 text-center px-2">
                                    Logo
                                </span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-gray-700 mb-1">
                                Logo principal
                            </p>
                            <input type="file"
                                   wire:model="logo"
                                   class="block w-full text-xs text-gray-500
                                          file:mr-3 file:py-1.5 file:px-3
                                          file:rounded-md file:border-0
                                          file:text-xs file:font-semibold
                                          file:bg-ed-primary/10 file:text-ed-primary
                                          hover:file:bg-ed-primary/20">
                            <p class="mt-1 text-[11px] text-gray-400">
                                PNG ou SVG, fond transparent recommandé.
                            </p>
                            @error('logo')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Favicon --}}
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl border border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden">
                            @if($current_favicon_url ?? false)
                                <img src="{{ $current_favicon_url }}" alt="Favicon actuel" class="w-full h-full object-contain">
                            @else
                                <span class="text-[11px] text-gray-400 text-center px-1">
                                    Favicon
                                </span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-gray-700 mb-1">
                                Favicon
                            </p>
                            <input type="file"
                                   wire:model="favicon"
                                   class="block w-full text-xs text-gray-500
                                          file:mr-3 file:py-1.5 file:px-3
                                          file:rounded-md file:border-0
                                          file:text-xs file:font-semibold
                                          file:bg-slate-900/5 file:text-slate-900
                                          hover:file:bg-slate-900/10">
                            <p class="mt-1 text-[11px] text-gray-400">
                                Icône 32×32 ou 64×64 (PNG).
                            </p>
                            @error('favicon')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button"
                            wire:click="saveMedia"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-semibold
                                   shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-800/60 focus:ring-offset-1
                                   disabled:opacity-60">
                        <span wire:loading.remove wire:target="saveMedia">Mettre à jour logo & favicon</span>
                        <span wire:loading wire:target="saveMedia" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Sauvegarde…</span>
                        </span>
                    </button>
                </div>
            </div>

            {{-- Sécurité / comptes --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">
                            Sécurité & comptes
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Mettre à jour le mot de passe admin et gérer le compte secrétaire.
                        </p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-50 text-red-600 text-[11px] font-semibold border border-red-100">
                        Sécurité
                    </span>
                </div>

                {{-- Mot de passe admin --}}
                <form wire:submit.prevent="updateAdminPassword" class="space-y-3 border-b border-gray-100 pb-4">
                    <p class="text-xs font-semibold text-gray-700">
                        Mettre à jour le mot de passe administrateur
                    </p>

                    <div class="space-y-2">
                        <div>
                            <label class="block text-[11px] font-medium text-gray-600 mb-1">
                                Mot de passe actuel
                            </label>
                            <input type="password"
                                   wire:model.defer="security.current_password"
                                   class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm
                                          focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            @error('security.current_password')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-medium text-gray-600 mb-1">
                                Nouveau mot de passe
                            </label>
                            <input type="password"
                                   wire:model.defer="security.new_password"
                                   class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm
                                          focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            @error('security.new_password')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-medium text-gray-600 mb-1">
                                Confirmation
                            </label>
                            <input type="password"
                                   wire:model.defer="security.new_password_confirmation"
                                   class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm
                                          focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                wire:target="updateAdminPassword"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-ed-primary text-white text-xs font-semibold
                                       shadow-sm hover:bg-ed-secondary focus:outline-none focus:ring-2 focus:ring-ed-primary/60 focus:ring-offset-1
                                       disabled:opacity-60">
                            <span wire:loading.remove wire:target="updateAdminPassword">Mettre à jour le mot de passe</span>
                            <span wire:loading wire:target="updateAdminPassword" class="inline-flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span>En cours…</span>
                            </span>
                        </button>
                    </div>
                </form>

                {{-- Création / gestion du compte secrétaire --}}
                <form wire:submit.prevent="createOrUpdateSecretaire" class="space-y-3">
                    <p class="text-xs font-semibold text-gray-700">
                        Compte secrétaire
                    </p>

                    <div class="space-y-2">
                        <div>
                            <label class="block text-[11px] font-medium text-gray-600 mb-1">
                                Nom complet secrétaire
                            </label>
                            <input type="text"
                                   wire:model.defer="secretaire.name"
                                   class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm
                                          focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            @error('secretaire.name')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-medium text-gray-600 mb-1">
                                Email secrétaire
                            </label>
                            <input type="email"
                                   wire:model.defer="secretaire.email"
                                   class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm
                                          focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            @error('secretaire.email')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-medium text-gray-600 mb-1">
                                Mot de passe (si création / réinitialisation)
                            </label>
                            <input type="password"
                                   wire:model.defer="secretaire.password"
                                   class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm
                                          focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            @error('secretaire.password')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                wire:target="createOrUpdateSecretaire"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold
                                       shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/60 focus:ring-offset-1
                                       disabled:opacity-60">
                            <span wire:loading.remove wire:target="createOrUpdateSecretaire">
                                Sauvegarder le compte secrétaire
                            </span>
                            <span wire:loading wire:target="createOrUpdateSecretaire" class="inline-flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span>En cours…</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
