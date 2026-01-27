<script setup>
import { computed } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    typeFilter: String,
    evenements: Object,
});

const page = usePage();

const typeLabels = {
    '': 'Tous les types',
    soutenance: 'Soutenances',
    seminaire: 'Seminaires',
    conference: 'Conferences',
    atelier: 'Ateliers',
    autre: 'Autres evenements',
};

const newsletterForm = useForm({
    email: '',
    nom: '',
    type: 'autre',
});

const flashSuccess = computed(() => page.props.flash?.success);

const updateType = (value) => {
    router.get(route('evenements.index'), { type: value || '' }, { preserveScroll: true, preserveState: true });
};

const submitNewsletter = () => {
    newsletterForm.post(route('newsletter.subscribe'), {
        preserveScroll: true,
        onSuccess: () => {
            newsletterForm.reset('email', 'nom');
        },
    });
};

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Evenements" />

    <div>
        <section class="relative gradient-primary pt-24 md:pt-28 pb-16 md:pb-20 overflow-hidden">
            <div class="absolute inset-0 opacity-15 pointer-events-none">
                <div class="absolute -top-10 right-0 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 -left-10 w-40 h-40 bg-ed-yellow rounded-full blur-3xl"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center text-white">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 mb-4">
                    <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                    <span class="text-xs font-semibold tracking-[0.18em] uppercase">
                        Agenda EDGVM
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black mb-3">
                     Agenda - evenements a venir
                </h1>
                <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto">
                    Consultez les soutenances, seminaires, conferences et ateliers programmes par l'Ecole Doctorale.
                </p>
            </div>
        </section>

        <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <main class="lg:col-span-8 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm text-gray-800 font-medium">
                                    {{ evenements?.meta?.total || 0 }} evenement(s) a venir
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5" v-if="typeFilter">
                                    Filtre par type :
                                    <span class="font-semibold text-gray-800">
                                        {{ typeLabels[typeFilter] || 'Type selectionne' }}
                                    </span>
                                </p>
                                <p v-else class="text-xs text-gray-500 mt-0.5">
                                    Tous les types d'evenements sont affiches.
                                </p>
                            </div>

                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>A venir</span>
                                </span>
                                <span class="hidden sm:inline-block text-gray-300">*</span>
                                <span class="hidden sm:inline-block">
                                    Les evenements passes ou archives sont consultables via l'historique (si disponible).
                                </span>
                            </div>
                        </div>

                        <div v-if="!evenements?.data?.length" class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-200 py-14 px-4 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                      d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm text-gray-600 font-medium">
                                Aucun evenement a venir ne correspond actuellement a ces criteres.
                            </p>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <article
                                v-for="event in evenements.data"
                                :key="event.id"
                                class="group bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-ed-primary/40 hover:-translate-y-0.5 transition-all duration-200 flex flex-col h-full overflow-hidden"
                            >
                                <div class="relative h-32 sm:h-40 overflow-hidden">
                                    <div v-if="event.image_url" class="absolute inset-0">
                                        <img
                                            :src="event.image_url"
                                            alt="Image de l'evenement"
                                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                                        >
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/20 to-transparent"></div>
                                    </div>
                                    <div v-else class="absolute inset-0 bg-gradient-to-br from-emerald-600 via-emerald-500 to-yellow-400">
                                        <div class="absolute -top-6 -right-10 w-32 h-32 rounded-full bg-white/10 blur-2xl"></div>
                                        <div class="absolute bottom-[-30px] left-[-10px] w-28 h-28 rounded-full bg-white/15 blur-2xl"></div>
                                        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.7) 1px, transparent 0); background-size: 16px 16px;"></div>
                                    </div>

                                    <div class="absolute left-3 top-3">
                                        <div class="flex flex-col items-center justify-center w-16 h-16 rounded-2xl bg-white/95 shadow-lg shadow-black/25 border border-white/70">
                                            <span class="text-lg font-extrabold leading-none text-gray-900">
                                                {{ event.jour }}
                                            </span>
                                            <span class="text-[10px] uppercase tracking-wide text-gray-500">
                                                {{ event.mois }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 sm:p-5 flex-1 flex flex-col gap-3">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2 mb-1">
                                                <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold" :class="event.type_classe">
                                                    {{ event.type_texte }}
                                                </span>

                                                <div class="flex items-center gap-1.5">
                                                    <span v-if="event.est_important" class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-semibold text-red-600">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M12 9v3.5m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/>
                                                        </svg>
                                                        Important
                                                    </span>

                                                    <span v-if="event.est_termine" class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-700">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Termine
                                                    </span>
                                                </div>
                                            </div>

                                            <h2 class="text-sm sm:text-[15px] font-semibold text-gray-900 leading-snug line-clamp-2 group-hover:text-ed-primary">
                                                {{ event.titre }}
                                            </h2>
                                        </div>
                                    </div>

                                    <div class="space-y-1 text-[12px] text-gray-600">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span>{{ event.periode_aff }}</span>
                                            </span>

                                            <span v-if="event.heure_debut_aff" class="inline-flex items-center gap-1 text-gray-500">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>{{ event.heure_debut_aff }}</span>
                                            </span>
                                        </div>

                                        <div v-if="event.lieu" class="inline-flex items-center gap-1.5 text-gray-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                      d="M17.657 16.657L13.414 20.9a1 1 0 0 1-1.414 0L6.343 16.657a8 8 0 1 1 11.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                      d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                            </svg>
                                            <span class="line-clamp-1">{{ event.lieu }}</span>
                                        </div>
                                    </div>

                                    <p v-if="event.description" class="mt-1.5 text-[13px] text-gray-600 leading-relaxed line-clamp-3">
                                        {{ event.description }}
                                    </p>
                                </div>

                                <div class="border-t border-gray-100 px-4 sm:px-5 py-3 flex items-center justify-between">
                                    <Link :href="route('evenements.show', event.id)" class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-ed-primary hover:text-ed-secondary">
                                        <span>Voir le detail</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </Link>

                                    <a v-if="event.lien_inscription && !event.est_termine" :href="event.lien_inscription" target="_blank" class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/70 px-3 py-1.5 text-[11px] font-semibold text-emerald-700 hover:bg-emerald-50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span>S'inscrire</span>
                                    </a>
                                    <span v-else-if="event.est_termine" class="inline-flex items-center gap-1.5 text-[11px] font-medium text-gray-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Termine
                                    </span>
                                </div>
                            </article>
                        </div>

                        <div class="mt-8">
                            <Pagination v-if="evenements?.links" :links="evenements.links" />
                        </div>
                    </main>

                    <aside class="lg:col-span-4 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-ed-primary/10 text-ed-primary">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                                    </svg>
                                </span>
                                <span>Filtrer par type</span>
                            </h3>

                            <div class="space-y-2">
                                <select
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                    :value="typeFilter || ''"
                                    @change="updateType($event.target.value)"
                                >
                                    <option value="">Tous types</option>
                                    <option value="soutenance">Soutenance</option>
                                    <option value="seminaire">Seminaire</option>
                                    <option value="conference">Conference</option>
                                    <option value="atelier">Atelier</option>
                                    <option value="autre">Autre</option>
                                </select>

                                <p v-if="typeFilter" class="mt-1 text-[11px] text-gray-500">
                                    Filtre actif :
                                    <span class="font-semibold text-gray-800">
                                        {{ typeLabels[typeFilter] || 'Type selectionne' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-ed-primary to-ed-secondary rounded-2xl shadow-md p-5 text-white">
                            <h3 class="text-sm font-bold mb-3 flex items-center gap-2">
                                <span class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-white/15">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                                    </svg>
                                </span>
                                <span>Vue d'ensemble</span>
                            </h3>

                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-white/85">Evenements listes</span>
                                    <span class="text-xl font-bold">{{ evenements?.meta?.total || 0 }}</span>
                                </div>
                                <p class="text-xs text-white/80">
                                    Seuls les evenements futurs, publies et non archives sont affiches dans cet agenda.
                                </p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-ed-primary via-ed-secondary to-yellow-600 rounded-2xl shadow-xl overflow-hidden">
                            <div class="p-6 text-white">
                                <div class="text-center mb-4">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-lg rounded-xl flex items-center justify-center mx-auto mb-3 border border-white/30">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold mb-1">Newsletter</h3>
                                    <p class="text-white/80 text-xs">Recevez nos prochaines actualites et evenements</p>
                                </div>

                                <form class="space-y-2.5" @submit.prevent="submitNewsletter">
                                    <input
                                        v-model="newsletterForm.email"
                                        type="email"
                                        placeholder="Votre adresse email"
                                        class="w-full px-3 py-2.5 text-sm rounded-lg bg-white/20 backdrop-blur-md border border-white/30 text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:outline-none transition"
                                        required
                                    >

                                    <input
                                        v-model="newsletterForm.nom"
                                        type="text"
                                        placeholder="Nom (optionnel)"
                                        class="w-full px-3 py-2.5 text-sm rounded-lg bg-white/20 backdrop-blur-md border border-white/30 text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:outline-none transition"
                                    >

                                    <button type="submit" class="w-full px-4 py-2.5 bg-white text-ed-primary rounded-lg font-bold hover:bg-ed-yellow transition shadow-lg text-sm" :disabled="newsletterForm.processing">
                                        S'abonner
                                    </button>
                                </form>

                                <div v-if="flashSuccess" class="mt-3 p-3 bg-green-500/30 backdrop-blur-md border border-green-300/40 rounded-lg text-white text-xs">
                                    OK {{ flashSuccess }}
                                </div>

                                <div v-if="newsletterForm.errors.email" class="mt-3 p-3 bg-red-500/30 backdrop-blur-md border border-red-300/40 rounded-lg text-white text-xs">
                                    X {{ newsletterForm.errors.email }}
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
</template>
