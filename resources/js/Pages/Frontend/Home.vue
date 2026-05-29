<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';
import SectionHeading from '@/Components/Frontend/UI/SectionHeading.vue';
import AppButton from '@/Components/Frontend/UI/AppButton.vue';
import { useReducedMotion } from '@/composables/useReducedMotion';

const props = defineProps({
    slider: Object,
    stats: Object,
    eads: Array,
    actualites: Array,
    evenementsFuturs: Array,
    partenaires: Array,
    messageDirection: Object,
});

const { reduced } = useReducedMotion();

// Slider state
const currentSlide = ref(0);
const sliderInterval = ref(null);
const direction = ref(1);
const isPaused = ref(false);

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
    startAutoplay();
};

const stopAutoplay = () => {
    if (sliderInterval.value) {
        clearInterval(sliderInterval.value);
        sliderInterval.value = null;
    }
};

const startAutoplay = () => {
    stopAutoplay();
    if (!hasMultipleSlides.value || reduced.value) return;
    sliderInterval.value = setInterval(() => {
        if (!isPaused.value) {
            nextSlide();
        }
    }, 7000);
};

const togglePause = () => {
    isPaused.value = !isPaused.value;
};

const handleVisibility = () => {
    isPaused.value = document.hidden;
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
    return parts.slice(0, 2).map((p) => p.charAt(0)).join('').toUpperCase();
};

const truncateText = (text, length = 140) => {
    if (!text) return '';

    let plain = text;
    if (typeof document !== 'undefined') {
        const el = document.createElement('div');
        el.innerHTML = text;
        plain = el.textContent || el.innerText || '';
    } else {
        plain = text.replace(/<[^>]*>/g, '');
    }

    plain = plain.replace(/\s+/g, ' ').trim();

    return plain.length > length ? plain.substring(0, length).trimEnd() + '…' : plain;
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
    startAutoplay();
    document.addEventListener('visibilitychange', handleVisibility);
});

onUnmounted(() => {
    stopAutoplay();
    document.removeEventListener('visibilitychange', handleVisibility);
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
        class="relative mt-16 overflow-hidden text-white sm:mt-20"
        role="region"
        aria-roledescription="carrousel"
        aria-label="Diaporama de présentation de l'École Doctorale"
        @mouseenter="isPaused = true"
        @mouseleave="isPaused = false"
        @focusin="isPaused = true"
        @focusout="isPaused = false"
    >
        <header class="sr-only">
            <h1>École Doctorale Génie du Vivant et Modélisation (EDGVM) - Université de Mahajanga</h1>
        </header>

        <p class="sr-only" aria-live="polite">
            Diapositive {{ currentSlide + 1 }} sur {{ slides.length }}
        </p>

        <!-- Background -->
        <div class="absolute inset-0 -z-10 bg-ed-teal-dark"></div>

        <Container class="relative py-10 sm:py-14 lg:py-20">
            <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-12 lg:gap-12">
                <!-- Left column: Text content -->
                <div class="relative lg:col-span-6">
                    <div class="relative min-h-[320px] sm:min-h-[340px] lg:min-h-[400px]">
                        <template v-for="(slide, index) in slides" :key="slide.id">
                            <Transition
                                enter-active-class="transition-opacity duration-700 ease-out"
                                enter-from-class="opacity-0"
                                enter-to-class="opacity-100"
                                leave-active-class="transition-opacity duration-300 ease-in"
                                leave-from-class="opacity-100"
                                leave-to-class="opacity-0"
                            >
                                <div
                                    v-show="currentSlide === index"
                                    class="absolute inset-0 flex flex-col justify-center space-y-4 sm:space-y-5"
                                >
                                    <!-- Kicker -->
                                    <div v-if="slide.badge_texte" class="flex w-fit items-center gap-3">
                                        <span class="h-5 w-1 rounded-full bg-ed-yellow"></span>
                                        <span class="text-[11px] font-semibold uppercase tracking-[0.2em] text-white/90">
                                            {{ slide.badge_texte }}
                                        </span>
                                    </div>

                                    <!-- Title -->
                                    <h2 class="max-w-2xl text-balance text-xl font-bold leading-snug text-white drop-shadow-[0_1px_6px_rgba(0,0,0,0.3)] sm:text-2xl md:text-[1.75rem] md:leading-[1.3] lg:text-[2.125rem] lg:leading-[1.3]">
                                        <span v-if="slide.titre_ligne1">{{ slide.titre_ligne1 }} </span>
                                        <span v-if="slide.titre_highlight" class="text-ed-yellow">{{ slide.titre_highlight }}</span>
                                        <span v-if="slide.titre_ligne2"> {{ slide.titre_ligne2 }}</span>
                                    </h2>

                                    <!-- Description -->
                                    <p
                                        v-if="slide.description"
                                        class="max-w-2xl text-sm leading-relaxed text-white/85 line-clamp-3 sm:text-[15px]"
                                    >
                                        {{ slide.description }}
                                    </p>

                                    <!-- CTA -->
                                    <div class="mt-1 flex flex-wrap items-center gap-3">
                                        <a
                                            v-if="slide.lien_cta && slide.texte_cta"
                                            :href="slide.lien_cta"
                                            class="group inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold shadow-lg shadow-black/20 transition-all duration-200 hover:-translate-y-px hover:brightness-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-white/60 focus-visible:ring-offset-2 focus-visible:ring-offset-transparent motion-reduce:transform-none"
                                            :style="{
                                                backgroundColor: slide.couleur_cta || '#FFFFFF',
                                                color: getContrastColor(slide.couleur_cta),
                                            }"
                                        >
                                            <span>{{ slide.texte_cta }}</span>
                                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5 motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 7l5 5m0 0-5 5m5-5H6" />
                                            </svg>
                                        </a>

                                        <Link
                                            v-if="index === 0"
                                            :href="route('pages.show', 'a-propos')"
                                            class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-5 py-3 text-sm font-semibold text-white/90 backdrop-blur-sm transition-colors duration-200 hover:border-white/50 hover:bg-white/10 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/50 focus-visible:ring-offset-2 focus-visible:ring-offset-transparent"
                                        >
                                            <span>Découvrir l'École</span>
                                        </Link>
                                    </div>

                                </div>
                            </Transition>
                        </template>
                    </div>
                </div>

                <!-- Right column: Image -->
                <div class="lg:col-span-6">
                    <div class="group relative h-[240px] w-full overflow-hidden rounded-3xl border border-white/15 shadow-2xl shadow-black/40 ring-1 ring-white/10 sm:h-[320px] md:h-[400px] lg:h-[460px]">
                        <template v-for="(slide, index) in slides" :key="'img-' + slide.id">
                            <Transition
                                enter-active-class="transition-opacity duration-700 ease-out"
                                enter-from-class="opacity-0"
                                enter-to-class="opacity-100"
                                leave-active-class="transition-opacity duration-500 ease-in"
                                leave-from-class="opacity-100"
                                leave-to-class="opacity-0"
                            >
                                <div v-show="currentSlide === index" class="absolute inset-0">
                                    <img
                                        :src="slide.image_url || 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=1200&q=80&auto=format&fit=crop'"
                                        :alt="slide.titre_complet"
                                        :loading="index === 0 ? 'eager' : 'lazy'"
                                        decoding="async"
                                        class="h-full w-full object-cover"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-black/20"></div>
                                </div>
                            </Transition>
                        </template>

                        <!-- Top-left label -->
                        <div class="pointer-events-none absolute left-4 top-4 z-10 text-[10px] font-semibold uppercase tracking-[0.18em] text-white/95 drop-shadow-[0_1px_4px_rgba(0,0,0,0.6)]">
                            École Doctorale EDGVM
                        </div>

                        <!-- Pause / Play -->
                        <button
                            v-if="hasMultipleSlides && !reduced"
                            @click="togglePause"
                            class="absolute right-3 top-3 z-10 inline-flex h-9 w-9 items-center justify-center rounded-full bg-black/35 text-white ring-1 ring-white/20 backdrop-blur transition hover:bg-black/55 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
                            :aria-label="isPaused ? 'Lire le diaporama' : 'Mettre le diaporama en pause'"
                        >
                            <svg v-if="isPaused" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                            <svg v-else class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M6 5h4v14H6zM14 5h4v14h-4z" />
                            </svg>
                        </button>

                        <!-- Side arrows -->
                        <template v-if="hasMultipleSlides">
                            <button
                                @click="previousSlide"
                                class="absolute left-3 top-1/2 z-10 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-black/35 text-white ring-1 ring-white/20 backdrop-blur transition hover:bg-black/55 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
                                aria-label="Diapositive précédente"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <button
                                @click="nextSlide"
                                class="absolute right-3 top-1/2 z-10 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-black/35 text-white ring-1 ring-white/20 backdrop-blur transition hover:bg-black/55 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
                                aria-label="Diapositive suivante"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </template>

                        <!-- Dots -->
                        <div v-if="hasMultipleSlides" class="absolute inset-x-0 bottom-3 z-10 flex items-center justify-center gap-1.5">
                            <button
                                v-for="(slide, slideIndex) in slides"
                                :key="'dot-' + slide.id"
                                @click="goToSlide(slideIndex)"
                                :class="[
                                    'h-1.5 rounded-full transition-all duration-200',
                                    currentSlide === slideIndex ? 'w-6 bg-white' : 'w-2 bg-white/50 hover:bg-white/80',
                                ]"
                                :aria-label="'Aller à la diapositive ' + (slideIndex + 1)"
                                :aria-current="currentSlide === slideIndex ? 'true' : undefined"
                            ></button>
                        </div>
                    </div>
                </div>
            </div>
        </Container>
    </section>

    <!-- Stats Section -->
    <section class="relative bg-gray-50 py-16">
        <Container>
            <!-- Header -->
            <SectionHeading
                class="mb-10 md:mb-12"
                eyebrow="Chiffres clés"
                title="Une communauté doctorale dynamique"
                subtitle="L'École Doctorale EDGVM rassemble doctorants, encadrants et équipes d'accueil engagés dans la recherche et l'innovation."
            />

            <!-- Chiffres clés -->
            <div class="grid grid-cols-2 gap-px overflow-hidden rounded-xl border border-gray-200 bg-gray-200 lg:grid-cols-4">
                <div class="bg-white p-6 md:p-8">
                    <p class="text-3xl font-semibold tracking-tight text-ed-primary tabular-nums md:text-4xl">{{ stats.doctorants }}<span class="align-top text-lg text-ed-primary/70">+</span></p>
                    <p class="mt-2 text-sm font-semibold text-gray-900">Doctorants</p>
                    <p class="mt-1 text-sm leading-relaxed text-gray-500">Inscrits et suivis au sein de l'École Doctorale.</p>
                </div>

                <div class="bg-white p-6 md:p-8">
                    <p class="text-3xl font-semibold tracking-tight text-ed-primary tabular-nums md:text-4xl">{{ stats.encadrants }}<span class="align-top text-lg text-ed-primary/70">+</span></p>
                    <p class="mt-2 text-sm font-semibold text-gray-900">Encadrants</p>
                    <p class="mt-1 text-sm leading-relaxed text-gray-500">Encadrants et co-encadrants mobilisés sur les projets de recherche.</p>
                </div>

                <div class="bg-white p-6 md:p-8">
                    <p class="text-3xl font-semibold tracking-tight text-ed-primary tabular-nums md:text-4xl">{{ stats.theses_soutenues }}<span class="align-top text-lg text-ed-primary/70">+</span></p>
                    <p class="mt-2 text-sm font-semibold text-gray-900">Thèses soutenues</p>
                    <p class="mt-1 text-sm leading-relaxed text-gray-500">Soutenues au sein de l'École ces dernières années.</p>
                </div>

                <div class="bg-white p-6 md:p-8">
                    <p class="text-3xl font-semibold tracking-tight text-ed-primary tabular-nums md:text-4xl">{{ stats.equipes }}</p>
                    <p class="mt-2 text-sm font-semibold text-gray-900">Équipes d'accueil</p>
                    <p class="mt-1 text-sm leading-relaxed text-gray-500">Équipes et laboratoires partenaires des travaux de thèse.</p>
                </div>
            </div>
        </Container>
    </section>

    <!-- About Section -->
    <section id="a-propos" class="relative bg-white py-14 md:py-16">

        <Container class="relative">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center lg:gap-14">
                <!-- Left column (text) -->
                <div class="lg:col-span-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-ed-primary">
                        À propos de l'EDGVM
                    </p>

                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-gray-900 md:text-3xl lg:text-[2rem]">
                        École Doctorale
                        <span class="text-ed-primary">Génie du Vivant</span>
                        et Modélisation
                    </h2>

                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 sm:text-base">
                        Une structure d'excellence de l'Université de Mahajanga, dédiée à la formation
                        et à l'accompagnement des doctorants dans les domaines du vivant, de la modélisation
                        et de l'innovation scientifique.
                    </p>

                    <!-- Axes -->
                    <dl class="mt-8 divide-y divide-gray-200 border-t border-gray-200">
                        <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-6">
                            <dt class="text-sm font-semibold text-gray-900">Formation doctorale</dt>
                            <dd class="text-sm leading-relaxed text-gray-600 sm:col-span-2">
                                Séminaires, ateliers méthodologiques et encadrement rapproché tout au long du parcours de thèse.
                            </dd>
                        </div>

                        <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-6">
                            <dt class="text-sm font-semibold text-gray-900">Pluridisciplinarité</dt>
                            <dd class="text-sm leading-relaxed text-gray-600 sm:col-span-2">
                                Projets à l'interface des sciences du vivant, de la modélisation, de l'environnement et de la santé.
                            </dd>
                        </div>

                        <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-6">
                            <dt class="text-sm font-semibold text-gray-900">Encadrement</dt>
                            <dd class="text-sm leading-relaxed text-gray-600 sm:col-span-2">
                                Encadrants et équipes d'accueil reconnus, engagés dans des collaborations nationales et internationales.
                            </dd>
                        </div>

                        <div class="grid grid-cols-1 gap-1 py-4 sm:grid-cols-3 sm:gap-6">
                            <dt class="text-sm font-semibold text-gray-900">Ancrage territorial</dt>
                            <dd class="text-sm leading-relaxed text-gray-600 sm:col-span-2">
                                Des recherches au service des enjeux du territoire : santé, environnement, ressources et développement durable.
                            </dd>
                        </div>
                    </dl>

                    <!-- CTA -->
                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <AppButton :href="route('pages.show', 'a-propos')" variant="primary" size="lg">
                            <span>Découvrir l'École</span>
                            <svg class="h-5 w-5 transition-transform group-hover:translate-x-0.5 motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </AppButton>

                        <a
                            href="#actualites-agenda"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-ed-primary/50 hover:text-ed-primary hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40"
                        >
                            <span>Voir les actualités</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Right column (video) -->
                <div class="lg:col-span-6">
                    <div class="relative">
                        <div class="relative overflow-hidden rounded-xl border border-gray-100 bg-white shadow-2xl">
                            <div class="flex items-center justify-between gap-3 px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-ed-yellow/15 text-ed-yellow-dark">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Présentation EDGVM</p>
                                        <p class="text-[11px] text-gray-500">Vidéo officielle</p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative h-0 bg-gray-900 pb-[56.25%]">
                                <iframe
                                    class="absolute inset-0 h-full w-full"
                                    src="https://www.youtube-nocookie.com/embed/ePyaHxQ8jpM?controls=1&modestbranding=1&rel=0"
                                    title="Présentation EDGVM"
                                    frameborder="0"
                                    loading="lazy"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                ></iframe>
                            </div>

                            <div class="px-5 py-4">
                                <p class="text-[12px] leading-relaxed text-gray-600">
                                    Découvrez la mission de l'EDGVM, ses axes de recherche, et les opportunités offertes aux doctorants.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Container>
    </section>

    <!-- Message Direction Section -->
    <section v-if="messageDirection" class="relative bg-gray-50 py-16 md:py-20">

        <Container class="relative z-10">
            <SectionHeading
                class="mb-10"
                eyebrow="Direction"
                title="Mot de la Directrice"
                subtitle="Une vision, des priorités et des engagements au service des doctorants et de la recherche."
            />

            <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-12 lg:gap-12">
                <!-- PHOTO -->
                <div class="lg:col-span-5">
                    <figure class="relative overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-black/5">
                        <img
                            :src="messageDirection.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=1200&q=80'"
                            :alt="'Portrait de ' + messageDirection.nom"
                            class="h-72 w-full object-cover object-top sm:h-80 md:h-96 lg:h-[420px]"
                            loading="lazy"
                            decoding="async"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>

                        <figcaption class="absolute inset-x-0 bottom-0 p-5 text-white md:p-6">
                            <p class="text-base font-semibold leading-tight md:text-lg">{{ messageDirection.nom }}</p>
                            <p v-if="messageDirection.fonction || messageDirection.institution" class="mt-1 text-xs text-white/85 md:text-sm">
                                {{ messageDirection.fonction }}<span v-if="messageDirection.institution"> · {{ messageDirection.institution }}</span>
                            </p>
                        </figcaption>
                    </figure>
                </div>

                <!-- TEXT -->
                <div class="lg:col-span-7">
                    <blockquote v-if="messageDirection.citation" class="text-base font-medium leading-relaxed text-gray-800 md:text-lg">
                        « {{ messageDirection.citation }} »
                    </blockquote>

                    <p v-if="messageDirection.message_intro" class="mt-5 whitespace-pre-line text-sm leading-relaxed text-gray-600">
                        {{ messageDirection.message_intro }}
                    </p>

                    <div class="mt-8 border-t border-gray-200 pt-5">
                        <p class="text-sm font-semibold text-gray-900">{{ messageDirection.nom }}</p>
                        <p v-if="messageDirection.fonction || messageDirection.institution" class="mt-0.5 text-sm text-gray-500">
                            {{ messageDirection.fonction }}<span v-if="messageDirection.institution"> · {{ messageDirection.institution }}</span>
                        </p>

                        <div v-if="messageDirection.email || messageDirection.telephone" class="mt-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                            <a
                                v-if="messageDirection.email"
                                :href="'mailto:' + messageDirection.email"
                                class="inline-flex items-center gap-2 text-gray-600 transition-colors hover:text-ed-primary"
                            >
                                <svg class="h-4 w-4 text-ed-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>{{ messageDirection.email }}</span>
                            </a>

                            <a
                                v-if="messageDirection.telephone"
                                :href="'tel:' + messageDirection.telephone"
                                class="inline-flex items-center gap-2 text-gray-600 transition-colors hover:text-ed-primary"
                            >
                                <svg class="h-4 w-4 text-ed-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>{{ messageDirection.telephone }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </Container>
    </section>

    <!-- EAD (Équipes d'Accueil Doctorales) Section -->
    <section v-if="eads && eads.length > 0" id="ead" class="relative bg-white py-14 md:py-16">

        <Container class="relative z-10">
            <SectionHeading
                class="mb-10 md:mb-12"
                align="center"
                eyebrow="Équipes d'accueil"
                title="Équipes d'Accueil Doctorales"
                subtitle="Des équipes structurées pour accompagner les doctorants dans leurs travaux de recherche, encadrement et collaborations."
            />

            <!-- Desktop : tableau classique -->
            <div class="hidden overflow-hidden rounded-xl border border-gray-200 md:block">
                <table class="min-w-full divide-y divide-gray-200 text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-600">Équipe d'accueil</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-600">Spécialités</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-600">Doctorants</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-600">
                                <span class="sr-only">Action</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="ead in eads" :key="ead.id" class="transition-colors hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <Link
                                    :href="route('ead.show', ead.slug)"
                                    class="font-semibold text-gray-900 transition-colors hover:text-ed-primary focus:outline-none focus-visible:text-ed-primary"
                                >
                                    {{ ead.nom }}
                                </Link>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-semibold tabular-nums text-gray-900">{{ ead.specialites_count }}</td>
                            <td class="px-6 py-4 text-center text-sm font-semibold tabular-nums text-gray-900">{{ ead.theses_count }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <Link
                                    :href="route('ead.show', ead.slug)"
                                    class="group inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary transition-colors hover:text-ed-secondary focus:outline-none focus-visible:text-ed-secondary"
                                >
                                    <span>Voir l'équipe</span>
                                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5 motion-reduce:transform-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile : liste -->
            <ul class="divide-y divide-gray-200 overflow-hidden rounded-xl border border-gray-200 md:hidden">
                <li v-for="ead in eads" :key="ead.id" class="bg-white p-4">
                    <Link
                        :href="route('ead.show', ead.slug)"
                        class="font-semibold text-gray-900 transition-colors hover:text-ed-primary focus:outline-none focus-visible:text-ed-primary"
                    >
                        {{ ead.nom }}
                    </Link>
                    <dl class="mt-3 flex items-center gap-6 text-sm">
                        <div class="flex items-baseline gap-1.5">
                            <dt class="text-gray-500">Spécialités</dt>
                            <dd class="font-semibold tabular-nums text-gray-900">{{ ead.specialites_count }}</dd>
                        </div>
                        <div class="flex items-baseline gap-1.5">
                            <dt class="text-gray-500">Doctorants</dt>
                            <dd class="font-semibold tabular-nums text-gray-900">{{ ead.theses_count }}</dd>
                        </div>
                    </dl>

                    <Link
                        :href="route('ead.show', ead.slug)"
                        class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary transition-colors hover:text-ed-secondary"
                    >
                        <span>Voir l'équipe</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </li>
            </ul>

        </Container>
    </section>

    <!-- Actualités + Agenda Section -->
    <section id="actualites-agenda" class="relative bg-gray-50 py-16 md:py-20">

        <Container class="relative z-10">
            <header class="mb-10 flex flex-col gap-6 md:mb-12 md:flex-row md:items-end md:justify-between">
                <SectionHeading
                    eyebrow="Dernières informations"
                    title="Actualités &amp; agenda des événements"
                    subtitle="Suivez la vie de l'École Doctorale : annonces, séminaires, soutenances, conférences et activités scientifiques majeures."
                />

                <div class="flex flex-wrap gap-2.5">
                    <AppButton :href="route('actualites.index')" variant="outline" size="sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0-4-4m4 4-4 4" />
                        </svg>
                        <span>Toutes les actualités</span>
                    </AppButton>

                    <AppButton :href="route('evenements.index')" variant="primary" size="sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z" />
                        </svg>
                        <span>Agenda complet</span>
                    </AppButton>
                </div>
            </header>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
                <!-- Actualités -->
                <div class="space-y-5 lg:col-span-8">
                    <div v-if="actualites && actualites.length === 0" class="rounded-2xl border border-dashed border-gray-200 bg-white p-10 text-center shadow-sm">
                        <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm font-medium text-gray-600">
                            Aucune actualité publiée pour le moment.
                        </p>
                    </div>

                    <div v-else class="space-y-4">
                        <article
                            v-for="actualite in actualites"
                            :key="actualite.id"
                            class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-ed-primary/35 hover:shadow-md motion-reduce:transform-none"
                        >
                            <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-stretch">
                                <!-- Media -->
                                <div class="relative h-44 w-full overflow-hidden rounded-xl sm:h-auto sm:w-40 md:w-44">
                                    <img
                                        v-if="actualite.image_url"
                                        :src="actualite.image_url"
                                        :alt="actualite.titre"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105 motion-reduce:transform-none"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                    <div v-else class="flex h-full w-full items-center justify-center bg-ed-primary">
                                        <svg class="h-9 w-9 text-white/70" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" />
                                            <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z" />
                                        </svg>
                                    </div>

                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-transparent"></div>

                                    <div v-if="actualite.est_important" class="absolute left-2 top-2">
                                        <span class="inline-flex items-center rounded-full bg-red-600 px-2 py-0.5 text-[10px] font-semibold text-white shadow">
                                            Important
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex min-w-0 flex-1 flex-col">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] text-gray-500">
                                        <time :datetime="actualite.date_publication">{{ actualite.date_formatted || '—' }}</time>
                                        <template v-if="actualite.category">
                                            <span aria-hidden="true" class="text-gray-300">·</span>
                                            <span class="font-semibold" :style="{ color: actualite.category.couleur || '#16826A' }">{{ actualite.category.nom }}</span>
                                        </template>
                                        <span aria-hidden="true" class="text-gray-300">·</span>
                                        <span>{{ actualite.vues || 0 }} vue<span v-if="(actualite.vues || 0) > 1">s</span></span>
                                    </div>

                                    <h3 class="mt-2 text-sm font-semibold leading-snug line-clamp-2 md:text-[15px]">
                                        <Link
                                            :href="route('actualites.show', actualite.slug)"
                                            class="rounded text-gray-900 transition-colors group-hover:text-ed-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40"
                                        >
                                            {{ actualite.titre }}
                                        </Link>
                                    </h3>

                                    <p class="mt-1.5 text-[13px] leading-relaxed text-gray-600 line-clamp-2">
                                        {{ truncateText(actualite.contenu) }}
                                    </p>

                                    <div v-if="actualite.tags && actualite.tags.length > 0" class="mt-2 flex flex-wrap gap-1.5">
                                        <span
                                            v-for="tag in actualite.tags.slice(0, 3)"
                                            :key="tag.id"
                                            class="rounded-lg bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-700"
                                        >
                                            #{{ tag.nom }}
                                        </span>
                                    </div>

                                    <div class="mt-auto pt-3">
                                        <Link
                                            :href="route('actualites.show', actualite.slug)"
                                            class="group/lire inline-flex items-center gap-1.5 text-[13px] font-semibold text-ed-primary transition-colors hover:text-ed-secondary focus:outline-none focus-visible:text-ed-secondary"
                                        >
                                            <span>Lire l'article</span>
                                            <svg class="h-4 w-4 transition-transform group-hover/lire:translate-x-0.5 motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <div class="pointer-events-none absolute inset-y-0 left-0 w-1 bg-ed-primary/0 transition group-hover:bg-ed-primary/40"></div>
                        </article>
                    </div>
                </div>

                <!-- Agenda -->
                <aside class="lg:col-span-4">
                    <div class="lg:sticky lg:top-24">
                        <div class="rounded-2xl border border-gray-100 bg-white/95 p-4 shadow-lg backdrop-blur md:p-5">
                            <div class="mb-4 flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="flex items-center gap-2 text-sm font-bold text-gray-900 md:text-base">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-ed-primary/10 text-ed-primary">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z" />
                                            </svg>
                                        </span>
                                        <span>Agenda à venir</span>
                                    </h3>
                                    <p class="mt-1 text-[11px] text-gray-500">
                                        Séminaires, soutenances, conférences et ateliers.
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
                                        <div class="flex flex-col items-center">
                                            <div class="flex h-12 w-12 flex-col items-center justify-center rounded-2xl bg-ed-primary/10 text-ed-primary">
                                                <span class="text-sm font-extrabold leading-none">{{ evenement.jour }}</span>
                                                <span class="text-[10px] uppercase tracking-wide">{{ evenement.mois }}</span>
                                            </div>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <Link :href="route('evenements.show', evenement.slug)" class="rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40">
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
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ evenement.heure_debut_aff }}
                                                    </span>

                                                    <span v-if="evenement.lieu" class="inline-flex items-center gap-1 text-[10px] text-gray-500">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1 1 0 0 1-1.414 0L6.343 16.657a8 8 0 1 1 11.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                                        </svg>
                                                        {{ evenement.lieu.length > 26 ? evenement.lieu.substring(0, 26) + '...' : evenement.lieu }}
                                                    </span>
                                                </div>
                                            </Link>

                                            <div class="mt-2 flex items-center justify-between">
                                                <Link
                                                    :href="route('evenements.show', evenement.slug)"
                                                    class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-ed-primary hover:text-ed-secondary"
                                                >
                                                    <span>Détails</span>
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </Link>

                                                <a
                                                    v-if="evenement.lien_inscription"
                                                    :href="evenement.lien_inscription"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="text-[11px] font-semibold text-emerald-600 underline hover:text-emerald-700"
                                                >
                                                    S'inscrire
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="py-6 text-center text-[12px] text-gray-500">
                                Aucun événement à venir pour le moment.
                            </div>

                            <div class="mt-4 text-center">
                                <Link
                                    :href="route('evenements.index')"
                                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-[12px] font-semibold text-gray-700 shadow-sm transition hover:border-ed-primary/50 hover:text-ed-primary hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40"
                                >
                                    <span>Voir tous les événements</span>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </Container>
    </section>

    <!-- Partenaires Section -->
    <section v-if="partenaires && partenaires.length > 0" class="border-t border-gray-100 bg-white py-14">
        <Container>
            <SectionHeading
                class="mb-10"
                eyebrow="Nos partenaires"
                title="Ils nous font confiance"
                subtitle="Partenaires académiques, institutionnels et projets collaboratifs qui accompagnent l'EDGVM."
            />

            <!-- Carousel -->
            <div class="relative">
                <div class="overflow-hidden">
                    <div
                        class="flex transition-transform duration-500 ease-out motion-reduce:transition-none"
                        :style="`transform: translateX(-${partenaireSlide * 100}%)`"
                    >
                        <div
                            v-for="(slide, slideIndex) in partenaireSlides"
                            :key="slideIndex"
                            class="w-full shrink-0"
                        >
                            <div class="mb-6 grid grid-cols-2 items-stretch gap-x-8 gap-y-10 sm:grid-cols-3 lg:grid-cols-5">
                                <component
                                    v-for="partenaire in slide"
                                    :key="partenaire.id"
                                    :is="partenaire.url ? 'a' : 'div'"
                                    :href="partenaire.url || undefined"
                                    :target="partenaire.url ? '_blank' : undefined"
                                    :rel="partenaire.url ? 'noopener noreferrer' : undefined"
                                    class="group flex flex-col items-center gap-3 text-center"
                                >
                                    <div class="flex h-12 items-center justify-center sm:h-14">
                                        <img
                                            v-if="partenaire.logo_url"
                                            :src="partenaire.logo_url"
                                            :alt="partenaire.nom"
                                            loading="lazy"
                                            decoding="async"
                                            class="h-10 w-auto object-contain opacity-80 grayscale transition-all duration-300 group-hover:opacity-100 group-hover:grayscale-0 sm:h-12"
                                        >
                                        <div v-else class="flex h-12 w-12 items-center justify-center rounded-full bg-ed-primary/10 text-sm font-bold text-ed-primary">
                                            {{ getInitials(partenaire.nom) }}
                                        </div>
                                    </div>

                                    <div class="max-w-[11rem]">
                                        <p
                                            class="text-xs font-semibold text-gray-700 line-clamp-2 transition-colors group-hover:text-ed-primary sm:text-sm"
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

                <template v-if="partenaireSlides.length > 1">
                    <button
                        type="button"
                        @click="prevPartenaire"
                        class="absolute left-0 top-1/2 hidden h-9 w-9 -translate-x-4 -translate-y-1/2 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-md transition hover:bg-ed-primary hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40 sm:flex"
                        aria-label="Partenaires précédents"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        @click="nextPartenaire"
                        class="absolute right-0 top-1/2 hidden h-9 w-9 -translate-y-1/2 translate-x-4 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-md transition hover:bg-ed-primary hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40 sm:flex"
                        aria-label="Partenaires suivants"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </template>

                <div v-if="partenaireSlides.length > 1" class="mt-3 flex items-center justify-center gap-2">
                    <button
                        v-for="(_, index) in partenaireSlides"
                        :key="index"
                        type="button"
                        @click="partenaireSlide = index"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="partenaireSlide === index ? 'w-6 bg-ed-primary' : 'w-2 bg-gray-300'"
                        :aria-label="'Aller au groupe de partenaires ' + (index + 1)"
                    ></button>
                </div>
            </div>

            <div class="mt-6 text-center">
                <Link
                    :href="route('contact')"
                    class="inline-flex items-center gap-2 rounded-full border border-ed-primary/30 bg-ed-primary/5 px-5 py-2.5 text-sm font-semibold text-ed-primary transition-all duration-300 hover:bg-ed-primary hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-ed-primary/40"
                >
                    <span>Devenir partenaire</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </Container>
    </section>

    <!-- CTA Section -->
    <section class="border-t border-gray-200 bg-gray-50 py-16">
        <Container class="max-w-3xl text-center">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                Rejoignez l'École Doctorale EDGVM
            </h2>
            <p class="mx-auto mt-3 max-w-2xl text-gray-600">
                Découvrez nos programmes de recherche, nos équipes d'accueil et les opportunités
                de formation doctorale au sein de l'Université de Mahajanga.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <AppButton :href="route('contact')" variant="primary" size="lg">
                    Nous contacter
                </AppButton>
                <AppButton :href="route('doctorants.index')" variant="outline" size="lg">
                    Découvrir nos doctorants
                </AppButton>
            </div>
        </Container>
    </section>
</template>
