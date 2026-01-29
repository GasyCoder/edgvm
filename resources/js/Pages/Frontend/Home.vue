<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

const props = defineProps({
    slider: Object,
    stats: Object,
    eads: Array,
    actualites: Array,
    evenementsFuturs: Array,
    partenaires: Array,
    messageDirection: Object,
});

// Slider state
const currentSlide = ref(0);
const sliderInterval = ref(null);
const direction = ref(1);

const slides = computed(() => props.slider?.slides || []);
const hasMultipleSlides = computed(() => slides.value.length > 1);

const nextSlide = () => {
    if (!slides.value.length) return;
    direction.value = 1;
    currentSlide.value = (currentSlide.value + 1) % slides.value.length;
};

const previousSlide = () => {
    if (!slides.value.length) return;
    direction.value = -1;
    currentSlide.value = currentSlide.value === 0 ? slides.value.length - 1 : currentSlide.value - 1;
};

const goToSlide = (index) => {
    if (!slides.value.length || index === currentSlide.value) return;
    direction.value = index > currentSlide.value ? 1 : -1;
    currentSlide.value = index;
    resetAutoplay();
};

const startAutoplay = () => {
    sliderInterval.value = setInterval(nextSlide, 7000);
};

const resetAutoplay = () => {
    if (sliderInterval.value) {
        clearInterval(sliderInterval.value);
        startAutoplay();
    }
};

// Partenaires carousel
const partenaireSlide = ref(0);
const partenairesPerSlide = 5;
const partenaireSlides = computed(() => {
    const result = [];
    for (let i = 0; i < props.partenaires.length; i += partenairesPerSlide) {
        result.push(props.partenaires.slice(i, i + partenairesPerSlide));
    }
    return result;
});

const nextPartenaire = () => {
    partenaireSlide.value = (partenaireSlide.value + 1) % partenaireSlides.value.length;
};

const prevPartenaire = () => {
    partenaireSlide.value = partenaireSlide.value === 0 ? partenaireSlides.value.length - 1 : partenaireSlide.value - 1;
};

const getInitials = (nom) => {
    if (!nom) return 'P';
    const parts = nom.trim().split(/\s+/);
    return parts.slice(0, 2).map(p => p.charAt(0)).join('').toUpperCase();
};

const truncateText = (text, length = 140) => {
    if (!text) return '';
    const stripped = text.replace(/<[^>]*>/g, '');
    return stripped.length > length ? stripped.substring(0, length) + '...' : stripped;
};

const getContrastColor = (hexColor) => {
    if (!hexColor) return '#1f2937';
    const hex = hexColor.replace('#', '');
    const r = parseInt(hex.substr(0, 2), 16);
    const g = parseInt(hex.substr(2, 2), 16);
    const b = parseInt(hex.substr(4, 2), 16);
    const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
    return luminance > 0.5 ? '#1f2937' : '#FFFFFF';
};

onMounted(() => {
    if (hasMultipleSlides.value) {
        startAutoplay();
    }
});

onUnmounted(() => {
    if (sliderInterval.value) {
        clearInterval(sliderInterval.value);
    }
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head title="Accueil" />

    <!-- Hero Section with Slider -->
    <section
        v-if="slider && slides.length > 0"
        class="relative mt-16 sm:mt-20 text-white overflow-x-hidden"
    >
        <header class="sr-only">
            <h1>Ecole Doctorale Genie du Vivant et Modelisation (EDGVM) - Universite de Mahajanga</h1>
        </header>

        <!-- Background -->
        <div class="absolute inset-0 -z-10">
            <template v-for="(slide, index) in slides" :key="slide.id">
                <Transition
                    enter-active-class="transition-opacity duration-500"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition-opacity duration-500"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-show="currentSlide === index"
                        :class="['absolute inset-0 bg-gradient-to-br', slide.couleur_fond]"
                    ></div>
                </Transition>
            </template>
            <div class="absolute inset-0 bg-gradient-to-b from-black/45 via-black/25 to-black/35"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.10),transparent_55%)]"></div>
            <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-white/25 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/25 via-black/10 to-black/25"></div>
            <div class="pointer-events-none absolute -top-24 -right-24 w-[520px] h-[520px] rounded-full bg-ed-yellow/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-28 -left-28 w-[520px] h-[520px] rounded-full bg-ed-primary/12 blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-10 items-center min-h-[280px] sm:min-h-[360px] lg:min-h-[460px]">
                <!-- Left column: Text content -->
                <div class="relative flex flex-col justify-center min-h-[200px] sm:min-h-[240px] pr-2 sm:pr-4 lg:pr-10 min-w-0">
                    <template v-for="(slide, index) in slides" :key="slide.id">
                        <Transition
                            enter-active-class="transition ease-out duration-700"
                            enter-from-class="opacity-0 translate-x-full"
                            enter-to-class="opacity-100 translate-x-0"
                            leave-active-class="transition ease-in duration-500"
                            leave-from-class="opacity-100 translate-x-0"
                            leave-to-class="opacity-0 -translate-x-full"
                        >
                            <div
                                v-show="currentSlide === index"
                                class="absolute inset-0 flex flex-col justify-center space-y-2 sm:space-y-4"
                            >
                                <!-- Badge - hidden on mobile -->
                                <div
                                    v-if="slide.badge_texte"
                                    class="hidden sm:inline-flex items-center gap-2 mb-1 w-fit px-3 py-1.5 rounded-full bg-white/10 ring-1 ring-white/15 backdrop-blur"
                                >
                                    <span class="w-2 h-2 rounded-full bg-ed-yellow"></span>
                                    <span class="text-[11px] font-semibold tracking-[0.18em] uppercase text-white/90">
                                        {{ slide.badge_texte }}
                                    </span>
                                </div>

                                <!-- Title -->
                                <h2 class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-tight leading-[1.2] sm:leading-[1.12] drop-shadow-[0_6px_18px_rgba(0,0,0,0.28)] break-words">
                                    <span v-if="slide.titre_ligne1" :style="{ color: slide.couleur_texte_titre || '#FFFFFF' }">{{ slide.titre_ligne1 }} </span>
                                    <span v-if="slide.titre_highlight" :style="{ color: slide.couleur_texte_titre || '#FFFFFF' }">{{ slide.titre_highlight }}</span>
                                    <span v-if="slide.titre_ligne2" :style="{ color: slide.couleur_texte_titre || '#FFFFFF' }"> {{ slide.titre_ligne2 }}</span>
                                </h2>

                                <!-- Description - hidden on mobile -->
                                <p
                                    v-if="slide.description"
                                    class="hidden sm:block text-sm md:text-base text-white/85 leading-relaxed max-w-xl break-words"
                                >
                                    {{ slide.description }}
                                </p>

                                <!-- CTA -->
                                <div class="mt-2 sm:mt-4 flex flex-wrap gap-2 sm:gap-3">
                                    <a
                                        v-if="slide.lien_cta && slide.texte_cta"
                                        :href="slide.lien_cta"
                                        class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 rounded-xl text-xs sm:text-sm font-semibold shadow-lg shadow-black/20 hover:brightness-[0.98] hover:-translate-y-[1px] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-offset-transparent"
                                        :style="{
                                            backgroundColor: slide.couleur_cta || '#FFFFFF',
                                            color: getContrastColor(slide.couleur_cta)
                                        }"
                                    >
                                        <span>{{ slide.texte_cta }}</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 7l5 5m0 0-5 5m5-5H6"/>
                                        </svg>
                                    </a>

                                    <Link
                                        v-if="index === 0"
                                        :href="route('pages.show', 'a-propos')"
                                        class="hidden sm:inline-flex items-center px-6 py-3 rounded-xl border border-white/30 text-sm font-semibold text-white/90 hover:bg-white/10 hover:border-white/40 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-1 focus:ring-offset-transparent"
                                    >
                                        <span>Decouvrir l'Ecole</span>
                                    </Link>
                                </div>

                                <!-- Micro-infos - hidden on mobile -->
                                <div class="hidden md:flex pt-2 flex-wrap gap-2 text-[11px] text-white/75">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-black/10 px-3 py-1 ring-1 ring-white/10">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                        Universite de Mahajanga
                                    </span>
                                    <span class="inline-flex items-center gap-2 rounded-full bg-black/10 px-3 py-1 ring-1 ring-white/10">
                                        Recherche - Innovation - Excellence
                                    </span>
                                </div>
                            </div>
                        </Transition>
                    </template>
                </div>

                <!-- Right column: Image -->
                <div class="relative min-h-[200px] sm:min-h-[280px] md:min-h-[320px] lg:min-h-[420px] flex items-center justify-center min-w-0">
                    <template v-for="(slide, index) in slides" :key="'img-' + slide.id">
                        <Transition
                            enter-active-class="transition ease-out duration-700"
                            enter-from-class="opacity-0 translate-x-full"
                            enter-to-class="opacity-100 translate-x-0"
                            leave-active-class="transition ease-in duration-500"
                            leave-from-class="opacity-100 translate-x-0"
                            leave-to-class="opacity-0 -translate-x-full"
                        >
                            <div
                                v-show="currentSlide === index"
                                class="absolute inset-0 flex items-center justify-center"
                            >
                                <div class="w-full max-w-xl bg-white/6 backdrop-blur-md rounded-3xl border border-white/15 shadow-2xl shadow-black/30 overflow-hidden">
                                    <div class="aspect-[4/3] md:aspect-[5/3] lg:aspect-[4/3] relative">
                                        <img
                                            :src="slide.image_url || 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=1200&q=80&auto=format&fit=crop'"
                                            :alt="slide.titre_complet"
                                            :loading="index === 0 ? 'eager' : 'lazy'"
                                            decoding="async"
                                            class="w-full h-full object-cover"
                                        >
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-black/10"></div>
                                    </div>

                                    <div class="px-4 py-3 flex items-center justify-between text-[11px] sm:text-xs text-white/85 bg-black/10">
                                        <span class="inline-flex items-center gap-2">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                            <span class="uppercase tracking-[0.14em] font-semibold">Ecole Doctorale EDGVM</span>
                                        </span>
                                        <span class="hidden sm:inline text-white/75">Recherche - Innovation - Excellence</span>
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </template>

                    <!-- Navigation & pagination -->
                    <div v-if="hasMultipleSlides" class="absolute -bottom-6 right-2 sm:right-4 flex items-center gap-3">
                        <button
                            @click="previousSlide"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/85 text-ed-primary shadow-md hover:bg-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/50"
                            aria-label="Slide precedent"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <button
                            @click="nextSlide"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/85 text-ed-primary shadow-md hover:bg-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/50"
                            aria-label="Slide suivant"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <div class="flex gap-1.5">
                            <button
                                v-for="(slide, slideIndex) in slides"
                                :key="'dot-' + slide.id"
                                @click="goToSlide(slideIndex)"
                                :class="[
                                    'h-1.5 rounded-full transition-all duration-200',
                                    currentSlide === slideIndex ? 'w-6 bg-white' : 'w-2 bg-white/35 hover:bg-white/60'
                                ]"
                                :aria-label="'Aller au slide ' + (slideIndex + 1)"
                            ></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="relative py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10 md:mb-12">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-ed-primary/8 border border-ed-primary/15">
                        <span class="w-2 h-2 rounded-full bg-ed-primary"></span>
                        <p class="text-[11px] font-semibold tracking-[0.18em] text-ed-primary uppercase">Chiffres cles</p>
                    </div>

                    <h2 class="mt-4 text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 leading-tight">
                        Une communaute doctorale dynamique
                    </h2>

                    <p class="mt-2 text-sm sm:text-base text-gray-600">
                        L'Ecole Doctorale EDGVM rassemble doctorants, encadrants et equipes d'accueil engages dans la recherche et l'innovation.
                    </p>
                </div>

                <div class="text-sm text-gray-500">
                    <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white/80 backdrop-blur px-3 py-1">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span>Donnees indicatives a jour</span>
                    </span>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
                <!-- Card 1: Doctorants -->
                <div class="group bg-white rounded-2xl p-6 md:p-7 shadow-sm ring-1 ring-gray-100/80 hover:shadow-md hover:ring-ed-primary/25 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-ed-primary to-ed-secondary rounded-xl flex items-center justify-center mb-4 md:mb-5 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-2xl md:text-2xl font-bold text-gray-900 tracking-tight">{{ stats.doctorants }}+</h3>
                        <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Actifs</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">Doctorants inscrits et suivis au sein de l'Ecole Doctorale.</p>
                    <div class="mt-5 h-0.5 w-12 bg-gradient-to-r from-ed-primary to-ed-secondary rounded-full"></div>
                </div>

                <!-- Card 2: Encadrants -->
                <div class="group bg-white rounded-2xl p-6 md:p-7 shadow-sm ring-1 ring-gray-100/80 hover:shadow-md hover:ring-ed-yellow/20 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-ed-yellow to-amber-500 rounded-xl flex items-center justify-center mb-4 md:mb-5 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-2xl md:text-2xl font-bold text-gray-900 tracking-tight">{{ stats.encadrants }}+</h3>
                        <span class="text-xs font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">Encadrement</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">Encadrants et co-encadrants mobilises dans les projets de recherche.</p>
                    <div class="mt-5 h-0.5 w-12 bg-gradient-to-r from-ed-yellow to-amber-500 rounded-full"></div>
                </div>

                <!-- Card 3: Theses soutenues -->
                <div class="group bg-white rounded-2xl p-6 md:p-7 shadow-sm ring-1 ring-gray-100/80 hover:shadow-md hover:ring-emerald-500/25 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-4 md:mb-5 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-2xl md:text-2xl font-bold text-gray-900 tracking-tight">{{ stats.theses_soutenues }}+</h3>
                        <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Soutenances</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">Theses soutenues au sein de l'Ecole Doctorale ces dernieres annees.</p>
                    <div class="mt-5 h-0.5 w-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full"></div>
                </div>

                <!-- Card 4: Equipes -->
                <div class="group bg-white rounded-2xl p-6 md:p-7 shadow-sm ring-1 ring-gray-100/80 hover:shadow-md hover:ring-indigo-500/25 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-ed-primary to-indigo-600 rounded-xl flex items-center justify-center mb-4 md:mb-5 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-2xl md:text-2xl font-bold text-gray-900 tracking-tight">{{ stats.equipes }}</h3>
                        <span class="text-xs font-medium text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full">Structures</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">Equipes d'accueil et laboratoires partenaires pour les travaux de these.</p>
                    <div class="mt-5 h-0.5 w-12 bg-gradient-to-r from-ed-primary to-indigo-600 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="a-propos" class="relative overflow-hidden bg-white py-14 md:py-16">
        <!-- Decorations -->
        <div class="pointer-events-none absolute -top-24 right-[-6rem] h-72 w-72 rounded-full bg-ed-primary/7 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-[-6rem] h-72 w-72 rounded-full bg-ed-yellow/8 blur-3xl"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center lg:gap-14">
                <!-- Left column (text) -->
                <div class="lg:col-span-6">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 rounded-full border border-ed-primary/20 bg-ed-primary/8 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-ed-primary"></span>
                        <span class="text-[11px] font-semibold tracking-[0.16em] uppercase text-ed-primary">
                            A propos de l'EDGVM
                        </span>
                    </div>

                    <!-- Title -->
                    <h2 class="mt-4 text-2xl md:text-3xl lg:text-[2rem] font-bold tracking-tight text-gray-900">
                        Ecole Doctorale
                        <span class="text-ed-primary">Genie du Vivant</span>
                        et Modelisation
                    </h2>

                    <!-- Intro -->
                    <p class="mt-3 text-sm sm:text-base leading-relaxed text-gray-600 max-w-2xl">
                        Une structure d'excellence de l'Universite de Mahajanga, dediee a la formation
                        et a l'accompagnement des doctorants dans les domaines du vivant, de la modelisation
                        et de l'innovation scientifique.
                    </p>

                    <!-- Key points -->
                    <div class="mt-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Card 1 -->
                        <div class="group rounded-xl border border-gray-100 bg-white p-4 transition hover:-translate-y-[1px] hover:shadow-md">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl text-ed-primary">
                                    <svg class="h-5 w-5 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c0 .5.3 1 1 1.3 1.6.8 3.6 1.7 5 1.7s3.4-.9 5-1.7c.7-.3 1-.8 1-1.3v-5" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Formation doctorale structuree</p>
                                    <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                        Seminaires, ateliers methodologiques et encadrement rapproche pour securiser le parcours de these.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="group rounded-xl border border-gray-100 bg-white p-4 transition hover:-translate-y-[1px] hover:shadow-md">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl text-ed-yellow">
                                    <svg class="h-5 w-5 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 2.5l1.1 3.3 3.3 1.1-3.3 1.1-1.1 3.3-1.1-3.3-3.3-1.1 3.3-1.1 1.1-3.3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 9.5l.9 2.6 2.6.9-2.6.9-.9 2.6-.9-2.6-2.6-.9 2.6-.9.9-2.6z" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Pluridisciplinarite &amp; ouverture</p>
                                    <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                        Projets croisant sciences du vivant, modelisation, environnement, sante, innovation technologique.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="group rounded-xl border border-gray-100 bg-white p-4 transition hover:-translate-y-[1px] hover:shadow-md">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl text-emerald-600">
                                    <svg class="h-5 w-5 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 20a6 6 0 0116 0" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Encadrement qualifie</p>
                                    <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                        Encadrants et equipes d'accueil reconnus, impliques dans des collaborations nationales et internationales.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="group rounded-xl border border-gray-100 bg-white p-4 transition hover:-translate-y-[1px] hover:shadow-md">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl text-indigo-600">
                                    <svg class="h-5 w-5 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.4 7-11a7 7 0 10-14 0c0 6.6 7 11 7 11z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Impact territorial et regional</p>
                                    <p class="mt-1 text-sm text-gray-600 leading-relaxed">
                                        Des theses ancrees dans les enjeux du territoire : environnement, sante, ressources, developpement durable.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <Link
                            :href="route('pages.show', 'a-propos')"
                            class="group inline-flex items-center gap-2 rounded-xl bg-ed-primary px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-[1px] hover:bg-ed-secondary hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/50"
                        >
                            <span>Decouvrir l'Ecole</span>
                            <svg class="h-5 w-5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </Link>

                        <a
                            href="#actualites-agenda"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-ed-primary/50 hover:text-ed-primary hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40"
                        >
                            <span>Voir les actualites</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Right column (video) -->
                <div class="lg:col-span-6">
                    <div class="relative">
                        <!-- Premium frame -->
                        <div class="relative overflow-hidden rounded-xl border border-gray-100 bg-white shadow-2xl">
                            <!-- Mini header -->
                            <div class="flex items-center justify-between gap-3 px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-ed-yellow/15 text-ed-yellow">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Presentation EDGVM</p>
                                        <p class="text-[11px] text-gray-500">Video officielle</p>
                                    </div>
                                </div>

                                <span class="hidden sm:inline-flex items-center gap-2 rounded-full bg-gray-50 px-3 py-1 text-[11px] font-semibold text-gray-700 border border-gray-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Lecture disponible
                                </span>
                            </div>

                            <!-- Video responsive 16:9 -->
                            <div class="relative pb-[56.25%] h-0 bg-gray-900">
                                <iframe
                                    class="absolute inset-0 h-full w-full"
                                    src="https://www.youtube-nocookie.com/embed/ePyaHxQ8jpM?controls=1&modestbranding=1&rel=0"
                                    title="Presentation EDGVM"
                                    frameborder="0"
                                    loading="lazy"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                ></iframe>
                            </div>

                            <!-- Mini footer -->
                            <div class="px-5 py-4">
                                <p class="text-[12px] text-gray-600 leading-relaxed">
                                    Decouvrez la mission de l'EDGVM, ses axes de recherche, et les opportunites offertes aux doctorants.
                                </p>
                            </div>
                        </div>

                        <!-- Decorative accent -->
                        <div class="pointer-events-none absolute -z-10 -right-6 -bottom-6 h-24 w-24 rounded-3xl bg-ed-primary/10 blur-xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Message Direction Section -->
    <section v-if="messageDirection" class="py-16 md:py-20 bg-white relative overflow-hidden">
        <!-- Background decorations -->
        <div class="pointer-events-none absolute -top-28 right-[-6rem] w-80 h-80 bg-ed-primary/5 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-[-6rem] left-[-7rem] w-96 h-96 bg-ed-yellow/5 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-ed-primary/10 border border-ed-primary/15">
                        <span class="w-2 h-2 bg-ed-primary rounded-full"></span>
                        <span class="text-ed-primary font-semibold text-xs tracking-[0.16em] uppercase">Direction</span>
                    </div>

                    <h2 class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                        Mot de la Directrice
                    </h2>

                    <p class="mt-2 text-sm sm:text-[15px] text-gray-600 max-w-2xl">
                        Une vision, des priorites et des engagements au service des doctorants et de la recherche.
                    </p>
                </div>

                <div class="hidden md:flex items-center gap-2 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span>Message institutionnel</span>
                    </span>
                </div>
            </header>

            <!-- Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- PHOTO -->
                <div class="lg:col-span-5 flex items-center">
                    <figure class="relative w-full">
                        <div class="relative rounded-3xl overflow-hidden bg-white shadow-xl ring-1 ring-black/5">
                            <img
                                :src="messageDirection.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=1200&q=80'"
                                :alt="messageDirection.nom"
                                class="w-full h-64 sm:h-80 md:h-[420px] lg:h-[520px] object-cover object-top"
                                loading="lazy"
                                decoding="async"
                            >

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/15 to-transparent"></div>

                            <!-- Signature -->
                            <figcaption class="absolute bottom-0 left-0 right-0 p-5 md:p-6 text-white">
                                <p class="text-base md:text-lg font-semibold leading-tight">
                                    {{ messageDirection.nom }}
                                </p>

                                <p v-if="messageDirection.fonction || messageDirection.institution" class="text-xs md:text-sm text-white/85 mt-1">
                                    {{ messageDirection.fonction }}
                                    <span v-if="messageDirection.institution"> - {{ messageDirection.institution }}</span>
                                </p>
                            </figcaption>
                        </div>

                        <!-- Offset frame -->
                        <div class="hidden md:block absolute -z-10 -bottom-4 -left-4 w-full h-full rounded-3xl border border-gray-200/70"></div>
                    </figure>
                </div>

                <!-- TEXT -->
                <div class="lg:col-span-7 flex">
                    <div class="relative w-full">
                        <!-- Side accent -->
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 rounded-full bg-gradient-to-b from-ed-primary/70 via-ed-primary/25 to-ed-yellow/35"></div>

                        <div class="ml-5 bg-white rounded-xl border-gray-50 ring-1 ring-gray-100/70 p-6 md:p-8">
                            <!-- Citation -->
                            <p v-if="messageDirection.citation" class="text-xl md:text-lg text-gray-900 leading-relaxed">
                                "{{ messageDirection.citation }}"
                            </p>
                            <div v-if="messageDirection.citation" class="mt-4 h-px w-16 bg-gray-200"></div>

                            <!-- Message -->
                            <div v-if="messageDirection.message_intro" class="mt-4 text-sm md:text-base text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ messageDirection.message_intro }}
                            </div>

                            <!-- Contacts -->
                            <div class="mt-6 flex flex-wrap gap-2">
                                <span v-if="messageDirection.email" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs text-gray-700">
                                    <svg class="w-4 h-4 text-ed-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ messageDirection.email }}</span>
                                </span>

                                <span v-if="messageDirection.telephone" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs text-gray-700">
                                    <svg class="w-4 h-4 text-ed-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>{{ messageDirection.telephone }}</span>
                                </span>
                            </div>

                            <!-- Footer signature -->
                            <div class="mt-7 flex items-center gap-3">
                                <div class="h-1 w-16 rounded-full bg-gradient-to-r from-ed-primary to-ed-yellow"></div>
                                <span class="text-xs text-gray-500">EDGVM - Universite de Mahajanga</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- EAD (Equipes d'Accueil Doctorales) Section -->
    <section v-if="eads && eads.length > 0" id="ead" class="py-14 md:py-16 bg-gradient-to-b from-gray-50 via-white to-gray-50 relative overflow-hidden">
        <!-- Background decorations -->
        <div class="pointer-events-none absolute -top-16 right-[-3rem] w-64 h-64 bg-ed-primary/8 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-[-4rem] left-[-3rem] w-64 h-64 bg-ed-yellow/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <header class="text-center mb-10 md:mb-12">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-ed-primary/10 rounded-full mb-3 border border-ed-primary/20">
                    <span class="w-2 h-2 rounded-full bg-ed-primary"></span>
                    <span class="text-[11px] font-semibold uppercase tracking-[0.18em] text-ed-primary">
                        Equipes d'accueil
                    </span>
                </div>

                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 mb-2 leading-snug">
                    Equipes d'Accueil Doctorales
                </h2>

                <p class="text-sm md:text-base text-slate-600 max-w-3xl mx-auto">
                    Des equipes structurees pour accompagner les doctorants dans leurs travaux de recherche, encadrement et collaborations.
                </p>
            </header>

            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
                <article
                    v-for="ead in eads"
                    :key="ead.id"
                    class="group relative h-full rounded-2xl bg-white/85 backdrop-blur border border-slate-100 ring-1 ring-black/5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden"
                >
                    <!-- Accent bar -->
                    <div class="h-[3px] w-full bg-gradient-to-r from-ed-primary via-ed-secondary to-ed-yellow"></div>

                    <!-- Hover glow -->
                    <div class="pointer-events-none absolute -inset-16 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute inset-0 bg-gradient-to-r from-ed-primary/12 via-transparent to-ed-yellow/12 blur-2xl"></div>
                    </div>

                    <div class="relative p-4 sm:p-5 flex flex-col h-full">
                        <!-- Header -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-ed-primary/10 ring-1 ring-ed-primary/10 flex items-center justify-center flex-none">
                                    <svg class="w-5 h-5 text-ed-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h4.5a1 1 0 01.7.29l2.5 2.5a1 1 0 00.7.29H18a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                    </svg>
                                </div>

                                <span
                                    v-if="ead.domaine"
                                    class="hidden sm:inline-flex max-w-[10rem] truncate px-2.5 py-1 rounded-full bg-ed-yellow/10 text-amber-800 ring-1 ring-amber-200/60 text-[11px] font-semibold"
                                    :title="ead.domaine"
                                >
                                    {{ ead.domaine }}
                                </span>
                            </div>

                            <span
                                v-if="ead.domaine"
                                class="sm:hidden inline-flex max-w-[9.5rem] truncate px-2 py-1 rounded-full bg-ed-yellow/10 text-amber-800 ring-1 ring-amber-200/60 text-[10px] font-semibold"
                                :title="ead.domaine"
                            >
                                {{ ead.domaine }}
                            </span>
                        </div>

                        <!-- Name -->
                        <h3 class="text-sm md:text-[15px] font-bold text-slate-900 mb-2 leading-snug line-clamp-2">
                            <Link
                                :href="route('ead.show', ead.slug)"
                                class="hover:text-ed-primary transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40 rounded-md"
                            >
                                {{ ead.nom }}
                            </Link>
                        </h3>

                        <!-- Description -->
                        <p class="text-[12px] text-slate-500 mb-4 leading-relaxed min-h-[2.5rem]">
                            Equipe d'accueil doctorale impliquee dans l'encadrement, le suivi et la valorisation des recherches.
                        </p>

                        <!-- Stats -->
                        <div class="mt-auto">
                            <div class="grid grid-cols-2 gap-3 mb-3 pb-3 border-t border-slate-100 pt-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-xl bg-ed-primary/8 ring-1 ring-ed-primary/10 flex items-center justify-center flex-none">
                                        <svg class="w-4 h-4 text-ed-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] uppercase tracking-wide text-slate-500">Specialites</p>
                                        <p class="text-sm font-semibold text-slate-900">{{ ead.specialites_count }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 ring-1 ring-emerald-200/40 flex items-center justify-center flex-none">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5a3.5 3.5 0 110 7 3.5 3.5 0 010-7zM6 20a6 6 0 1112 0H6z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] uppercase tracking-wide text-slate-500">Doctorants</p>
                                        <p class="text-sm font-semibold text-slate-900">{{ ead.theses_count }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- CTA -->
                            <Link
                                :href="route('ead.show', ead.slug)"
                                class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-ed-primary hover:text-ed-secondary transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40 rounded-md"
                            >
                                <span>Voir l'equipe</span>
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </Link>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Global button -->
            <div class="text-center mt-8">
                <Link
                    :href="route('ead.index')"
                    class="inline-flex items-center px-6 py-2.5 rounded-xl bg-ed-primary text-white text-sm font-semibold shadow-md hover:shadow-lg hover:bg-ed-secondary transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40"
                >
                    <span>Voir toutes les equipes</span>
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </Link>
            </div>
        </div>
    </section>

    <!-- Actualites + Agenda Section -->
    <section id="actualites-agenda" class="relative overflow-hidden py-16 md:py-20 bg-gradient-to-b from-gray-50 via-white to-gray-50">
        <!-- Decorations -->
        <div class="pointer-events-none absolute -top-24 right-[-6rem] h-72 w-72 rounded-full bg-ed-primary/7 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-[-5rem] left-[-6rem] h-72 w-72 rounded-full bg-ed-yellow/8 blur-3xl"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <header class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between mb-10 md:mb-12">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 rounded-full border border-ed-primary/20 bg-ed-primary/8 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-ed-primary"></span>
                        <span class="text-[11px] font-semibold tracking-[0.16em] uppercase text-ed-primary">
                            Dernieres informations
                        </span>
                    </div>

                    <h2 class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                        Actualites &amp; agenda des evenements
                    </h2>

                    <p class="mt-2 text-sm sm:text-[15px] leading-relaxed text-gray-600">
                        Suivez la vie de l'Ecole Doctorale : annonces, seminaires, soutenances, conferences et activites scientifiques majeures.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2.5">
                    <Link
                        :href="route('actualites.index')"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-[12px] font-semibold text-gray-700 shadow-sm transition hover:-translate-y-[1px] hover:border-ed-primary/50 hover:text-ed-primary hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/50"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0-4-4m4 4-4 4"/>
                        </svg>
                        <span>Toutes les actualites</span>
                    </Link>

                    <Link
                        :href="route('evenements.index')"
                        class="inline-flex items-center gap-2 rounded-xl bg-ed-primary px-4 py-2.5 text-[12px] font-semibold text-white shadow-md transition hover:-translate-y-[1px] hover:bg-ed-secondary hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/50"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Agenda complet</span>
                    </Link>
                </div>
            </header>

            <!-- Layout -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
                <!-- Actualites -->
                <div class="lg:col-span-8 space-y-5">
                    <div v-if="actualites && actualites.length === 0" class="rounded-2xl border border-dashed border-gray-200 bg-white p-10 text-center shadow-sm">
                        <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-600">
                            Aucune actualite publiee pour le moment.
                        </p>
                    </div>

                    <div v-else class="space-y-4">
                        <article
                            v-for="actualite in actualites"
                            :key="actualite.id"
                            class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-[2px] hover:border-ed-primary/35 hover:shadow-md"
                        >
                            <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-stretch">
                                <!-- Media -->
                                <div class="relative w-full overflow-hidden rounded-xl sm:w-40 md:w-44 h-44 sm:h-auto">
                                    <img
                                        v-if="actualite.image_url"
                                        :src="actualite.image_url"
                                        :alt="actualite.titre"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                    <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-ed-primary to-ed-secondary">
                                        <svg class="h-9 w-9 text-white/70" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"/>
                                            <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                                        </svg>
                                    </div>

                                    <!-- Overlay -->
                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-transparent"></div>

                                    <!-- Important badge -->
                                    <div v-if="actualite.est_important" class="absolute left-2 top-2">
                                        <span class="inline-flex items-center rounded-full bg-red-600 px-2 py-0.5 text-[10px] font-semibold text-white shadow">
                                            Important
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex min-w-0 flex-1 flex-col">
                                    <!-- Meta row -->
                                    <div class="flex flex-wrap items-center gap-2 text-[11px] text-gray-500">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-50 px-2 py-0.5 border border-gray-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                                            </svg>
                                            <time :datetime="actualite.date_publication">
                                                {{ actualite.date_formatted || '-' }}
                                            </time>
                                        </span>

                                        <span
                                            v-if="actualite.category"
                                            class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold text-gray-800 border"
                                            :style="{
                                                backgroundColor: (actualite.category.couleur || '#6366f1') + '22',
                                                borderColor: (actualite.category.couleur || '#6366f1') + '33'
                                            }"
                                        >
                                            {{ actualite.category.nom }}
                                        </span>

                                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-50 px-2 py-0.5 border border-gray-100">
                                            <svg class="w-3.5 h-3.5 text-ed-primary" viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7C3.732 7.943 7.523 5 12 5zm0 11a4 4 0 100-8 4 4 0 000 8z"/>
                                                <path d="M12 10a2 2 0 100 4 2 2 0 000-4z"/>
                                            </svg>
                                            <span class="font-semibold text-gray-700">{{ actualite.vues || 0 }}</span>
                                        </span>
                                    </div>

                                    <!-- Title -->
                                    <h3 class="mt-2 text-sm md:text-[15px] font-semibold leading-snug line-clamp-2">
                                        <Link
                                            :href="route('actualites.show', actualite.slug)"
                                            class="text-gray-900 transition-colors group-hover:text-ed-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40 rounded"
                                        >
                                            {{ actualite.titre }}
                                        </Link>
                                    </h3>

                                    <!-- Excerpt -->
                                    <p class="mt-1.5 text-[13px] leading-relaxed text-gray-600 line-clamp-2">
                                        {{ truncateText(actualite.contenu) }}
                                    </p>

                                    <!-- Tags -->
                                    <div v-if="actualite.tags && actualite.tags.length > 0" class="mt-2 flex flex-wrap gap-1.5">
                                        <span
                                            v-for="tag in actualite.tags.slice(0, 3)"
                                            :key="tag.id"
                                            class="rounded-lg bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-700"
                                        >
                                            #{{ tag.nom }}
                                        </span>
                                    </div>

                                    <!-- CTA row -->
                                    <div class="mt-auto pt-3 flex items-center justify-between">
                                        <span class="text-[11px] text-gray-400">
                                            Mise a jour reguliere
                                        </span>

                                        <Link
                                            :href="route('actualites.show', actualite.slug)"
                                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-[12px] font-semibold text-ed-primary shadow-sm transition hover:border-ed-primary/50 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40"
                                        >
                                            <span>Lire</span>
                                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-ed-primary/10">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </span>
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <!-- Left accent on hover -->
                            <div class="pointer-events-none absolute inset-y-0 left-0 w-1 bg-ed-primary/0 group-hover:bg-ed-primary/40 transition"></div>
                        </article>
                    </div>
                </div>

                <!-- Agenda -->
                <aside class="lg:col-span-4">
                    <div class="lg:sticky lg:top-24">
                        <div class="rounded-2xl border border-gray-100 bg-white/95 p-4 md:p-5 shadow-lg backdrop-blur">
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div>
                                    <h3 class="text-sm md:text-base font-bold text-gray-900 flex items-center gap-2">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-ed-primary/10 text-ed-primary">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                                            </svg>
                                        </span>
                                        <span>Agenda a venir</span>
                                    </h3>
                                    <p class="mt-1 text-[11px] text-gray-500">
                                        Seminaires, soutenances, conferences et ateliers.
                                    </p>
                                </div>
                            </div>

                            <div v-if="evenementsFuturs && evenementsFuturs.length > 0" class="space-y-3">
                                <div
                                    v-for="evenement in evenementsFuturs"
                                    :key="evenement.id"
                                    class="group rounded-2xl border border-gray-100 p-3 transition hover:border-ed-primary/40 hover:bg-ed-primary/[0.03]"
                                >
                                    <div class="flex gap-3">
                                        <!-- Date -->
                                        <div class="flex flex-col items-center">
                                            <div class="h-12 w-12 rounded-2xl bg-ed-primary/10 text-ed-primary flex flex-col items-center justify-center">
                                                <span class="text-sm font-extrabold leading-none">{{ evenement.jour }}</span>
                                                <span class="text-[10px] uppercase tracking-wide">{{ evenement.mois }}</span>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="min-w-0 flex-1">
                                            <Link :href="route('evenements.show', evenement.slug)" class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40 rounded">
                                                <div class="flex items-start justify-between gap-2">
                                                    <h4 class="text-[13px] font-semibold text-gray-900 line-clamp-2 group-hover:text-ed-primary">
                                                        {{ evenement.titre }}
                                                    </h4>

                                                    <span v-if="evenement.est_important" class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-semibold text-red-600">
                                                        Important
                                                    </span>
                                                </div>

                                                <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                                    <span :class="['rounded-full px-2 py-0.5 text-[10px] font-semibold', evenement.type_classe]">
                                                        {{ evenement.type_texte }}
                                                    </span>

                                                    <span v-if="evenement.heure_debut_aff" class="inline-flex items-center gap-1 text-[10px] text-gray-500">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        {{ evenement.heure_debut_aff }}
                                                    </span>

                                                    <span v-if="evenement.lieu" class="inline-flex items-center gap-1 text-[10px] text-gray-500">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1 1 0 0 1-1.414 0L6.343 16.657a8 8 0 1 1 11.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                        </svg>
                                                        {{ evenement.lieu.length > 26 ? evenement.lieu.substring(0, 26) + '...' : evenement.lieu }}
                                                    </span>
                                                </div>
                                            </Link>

                                            <!-- Actions -->
                                            <div class="mt-2 flex items-center justify-between">
                                                <Link
                                                    :href="route('evenements.show', evenement.slug)"
                                                    class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-ed-primary hover:text-ed-secondary"
                                                >
                                                    <span>Details</span>
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </Link>

                                                <a
                                                    v-if="evenement.lien_inscription"
                                                    :href="evenement.lien_inscription"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-700 underline"
                                                >
                                                    S'inscrire
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="py-6 text-center text-[12px] text-gray-500">
                                Aucun evenement a venir pour le moment.
                            </div>

                            <div class="mt-4 text-center">
                                <Link
                                    :href="route('evenements.index')"
                                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-[12px] font-semibold text-gray-700 shadow-sm transition hover:border-ed-primary/50 hover:text-ed-primary hover:shadow-md"
                                >
                                    <span>Voir tous les evenements</span>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <!-- Partenaires Section -->
    <section v-if="partenaires && partenaires.length > 0" class="py-14 bg-gradient-to-b from-white via-slate-50 to-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">
                <div class="text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-ed-primary/10 rounded-full mb-3">
                        <div class="w-2 h-2 bg-ed-primary rounded-full"></div>
                        <span class="text-ed-primary font-semibold text-xs">Nos partenaires</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 mb-2">
                        Ils nous font confiance
                    </h2>

                    <p class="text-sm md:text-base text-gray-600 max-w-2xl md:max-w-xl mx-auto md:mx-0">
                        Partenaires academiques, institutionnels et projets collaboratifs qui accompagnent l'EDGVM.
                    </p>
                </div>

                <div class="flex justify-center md:justify-end">
                    <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs text-gray-600 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Partenariats &amp; collaborations
                    </span>
                </div>
            </div>

            <!-- Carousel -->
            <div class="relative">
                <!-- Slides container -->
                <div class="overflow-hidden">
                    <div
                        class="flex transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${partenaireSlide * 100}%)`"
                    >
                        <div
                            v-for="(slide, slideIndex) in partenaireSlides"
                            :key="slideIndex"
                            class="w-full shrink-0"
                        >
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-x-8 gap-y-10 items-stretch mb-6">
                                <component
                                    v-for="partenaire in slide"
                                    :key="partenaire.id"
                                    :is="partenaire.url ? 'a' : 'div'"
                                    :href="partenaire.url || undefined"
                                    :target="partenaire.url ? '_blank' : undefined"
                                    :rel="partenaire.url ? 'noopener noreferrer' : undefined"
                                    class="group flex flex-col items-center text-center gap-3"
                                >
                                    <div class="flex items-center justify-center h-12 sm:h-14">
                                        <img
                                            v-if="partenaire.logo_url"
                                            :src="partenaire.logo_url"
                                            :alt="partenaire.nom"
                                            loading="lazy"
                                            decoding="async"
                                            class="h-10 sm:h-12 w-auto object-contain opacity-80 group-hover:opacity-100 grayscale group-hover:grayscale-0 transition-all duration-300"
                                        >
                                        <div v-else class="h-12 w-12 rounded-full bg-ed-primary/10 flex items-center justify-center text-ed-primary font-bold text-sm">
                                            {{ getInitials(partenaire.nom) }}
                                        </div>
                                    </div>

                                    <div class="max-w-[11rem]">
                                        <p
                                            class="text-xs sm:text-sm font-semibold text-gray-700 line-clamp-2 group-hover:text-ed-primary transition-colors"
                                            :title="partenaire.nom"
                                        >
                                            {{ partenaire.nom }}
                                        </p>

                                        <p v-if="partenaire.description" class="mt-1 text-[11px] text-gray-500 line-clamp-2">
                                            {{ partenaire.description }}
                                        </p>
                                    </div>
                                </component>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation buttons -->
                <template v-if="partenaireSlides.length > 1">
                    <button
                        type="button"
                        @click="prevPartenaire"
                        class="hidden sm:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-9 h-9 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-600 hover:bg-ed-primary hover:text-white transition"
                        aria-label="Partenaires precedent"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <button
                        type="button"
                        @click="nextPartenaire"
                        class="hidden sm:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-9 h-9 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-600 hover:bg-ed-primary hover:text-white transition"
                        aria-label="Partenaires suivant"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </template>

                <!-- Pagination dots -->
                <div v-if="partenaireSlides.length > 1" class="flex items-center justify-center gap-2 mt-3">
                    <button
                        v-for="(_, index) in partenaireSlides"
                        :key="index"
                        type="button"
                        @click="partenaireSlide = index"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="partenaireSlide === index ? 'w-6 bg-ed-primary' : 'w-2 bg-gray-300'"
                        :aria-label="'Aller au slide ' + (index + 1)"
                    ></button>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center mt-6">
                <Link
                    :href="route('contact')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-ed-primary/30 text-sm font-semibold text-ed-primary bg-ed-primary/5 hover:bg-ed-primary hover:text-white transition-all duration-300"
                >
                    <span>Devenir partenaire</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </Link>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-br from-ed-primary to-ed-secondary text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold mb-4">
                Rejoignez l'Ecole Doctorale EDGVM
            </h2>
            <p class="text-white/80 mb-8 max-w-2xl mx-auto">
                Decouvrez nos programmes de recherche, nos equipes d'accueil et les opportunites
                de formation doctorale au sein de l'Universite de Mahajanga.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <Link
                    :href="route('contact')"
                    class="px-8 py-3 bg-ed-yellow text-gray-900 rounded-xl font-semibold hover:bg-ed-yellow-dark transition-colors"
                >
                    Nous contacter
                </Link>
                <Link
                    :href="route('doctorants.index')"
                    class="px-8 py-3 bg-white/10 text-white border border-white/30 rounded-xl font-semibold hover:bg-white/20 transition-colors"
                >
                    Decouvrir nos doctorants
                </Link>
            </div>
        </div>
    </section>
</template>
