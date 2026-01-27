<script setup>
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

const props = defineProps({
    specialite: Object,
    autresSpecialites: Array,
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="specialite?.nom || 'Programme'" />

    <div>
        <section class="relative gradient-primary pt-24 md:pt-28 pb-16 md:pb-20 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <nav class="mb-6">
                    <ol class="flex items-center gap-2 text-xs text-white/70">
                        <li><Link :href="route('home')" class="hover:text-white transition">Accueil</Link></li>
                        <li>></li>
                        <li><Link :href="route('programmes.index')" class="hover:text-white transition">Programmes</Link></li>
                        <li>></li>
                        <li class="text-white font-semibold">{{ specialite?.nom }}</li>
                    </ol>
                </nav>

                <div v-if="specialite?.ead" class="mb-4">
                    <Link :href="route('ead.show', specialite.ead.slug)" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-lg text-white text-sm font-semibold rounded-full border border-white/30 hover:bg-white/30 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ specialite.ead.nom }}
                    </Link>
                </div>

                <h1 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    {{ specialite?.nom }}
                </h1>

                <div class="flex flex-wrap gap-4 text-white/90 text-sm">
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-lg rounded-full px-4 py-2 border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>{{ specialite?.theses_en_cours || 0 }} doctorants en cours</span>
                    </div>

                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-lg rounded-full px-4 py-2 border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ specialite?.theses_soutenues || 0 }} theses soutenues</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-xl p-8">
                            <div class="prose prose-gray max-w-none mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Presentation</h2>
                                <p class="text-gray-700 leading-relaxed">{{ specialite?.description }}</p>
                            </div>

                            <div v-if="specialite?.ead?.responsable" class="bg-blue-50 rounded-xl p-6 mb-8">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Responsable de l'EAD
                                </h3>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-blue-200 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ specialite.ead.responsable.name }}</p>
                                        <p v-if="specialite.ead.responsable.grade" class="text-xs text-gray-600">{{ specialite.ead.responsable.grade }}</p>
                                        <a v-if="specialite.ead.responsable.email" :href="`mailto:${specialite.ead.responsable.email}`" class="text-sm text-blue-600 hover:text-blue-800">
                                            {{ specialite.ead.responsable.email }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="lg:col-span-1">
                        <div class="lg:sticky lg:top-24 space-y-6">
                            <div v-if="specialite?.ead" class="bg-white rounded-2xl shadow-xl p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Equipe d'accueil</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">EAD</p>
                                        <p class="font-semibold text-gray-900">{{ specialite.ead.nom }}</p>
                                    </div>
                                    <div v-if="specialite.ead.domaine">
                                        <p class="text-sm text-gray-500 mb-1">Domaine</p>
                                        <p class="font-semibold text-gray-900">{{ specialite.ead.domaine }}</p>
                                    </div>
                                    <Link :href="route('ead.show', specialite.ead.slug)" class="block text-center px-4 py-2.5 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-semibold text-sm">
                                        Voir l'EAD ->
                                    </Link>
                                </div>
                            </div>

                            <div v-if="autresSpecialites?.length" class="bg-white rounded-2xl shadow-xl p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Autres programmes</h3>
                                <div class="space-y-3">
                                    <Link
                                        v-for="item in autresSpecialites"
                                        :key="item.id"
                                        :href="route('programmes.show', item.slug)"
                                        class="block p-3 rounded-lg hover:bg-gray-50 transition"
                                    >
                                        <p class="font-semibold text-gray-900 text-sm hover:text-ed-primary line-clamp-2">
                                            {{ item.nom }}
                                        </p>
                                    </Link>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-ed-primary to-ed-secondary rounded-2xl shadow-xl p-6 text-white">
                                <h3 class="text-lg font-bold mb-3">Vous etes interesse ?</h3>
                                <p class="text-sm text-white/80 mb-4">
                                    Contactez-nous pour plus d'informations sur ce programme doctoral.
                                </p>
                                <Link :href="route('contact')" class="block text-center px-4 py-2.5 bg-white text-ed-primary rounded-lg hover:bg-ed-yellow transition font-bold text-sm">
                                    Nous contacter
                                </Link>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
</template>
