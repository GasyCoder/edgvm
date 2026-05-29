<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';

const props = defineProps({
    actualite: Object,
    similaires: Array,
    precedent: Object,
    suivant: Object,
    shareUrl: String,
});

const galleryIndex = ref(0);
const gallery = computed(() => props.actualite?.galerie || []);

const prevGallery = () => {
    if (!gallery.value.length) {
        return;
    }

    galleryIndex.value = galleryIndex.value === 0 ? gallery.value.length - 1 : galleryIndex.value - 1;
};

const nextGallery = () => {
    if (!gallery.value.length) {
        return;
    }

    galleryIndex.value = galleryIndex.value === gallery.value.length - 1 ? 0 : galleryIndex.value + 1;
};

const shareLinks = computed(() => {
    const url = encodeURIComponent(props.shareUrl || '');
    const title = encodeURIComponent(props.actualite?.titre || '');

    return {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
        twitter: `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
        linkedin: `https://www.linkedin.com/shareArticle?mini=true&url=${url}&title=${title}`,
        whatsapp: `https://wa.me/?text=${title}%20-%20${url}`,
    };
});

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="actualite?.titre || 'Actualité'" />

    <!-- Hero image -->
    <section class="relative mt-16 sm:mt-20">
        <div class="relative h-[320px] overflow-hidden bg-ed-teal-dark sm:h-[400px]">
            <img v-if="actualite?.image_url" :src="actualite.image_url" :alt="actualite.titre" class="absolute inset-0 h-full w-full object-cover" style="object-position: 50% 35%;">
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/45 to-black/20"></div>

            <div class="absolute inset-0 flex items-end">
                <div class="mx-auto w-full max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
                    <nav class="mb-4 flex flex-wrap items-center gap-2 text-sm text-white/70" aria-label="Fil d'Ariane">
                        <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                        <span aria-hidden="true">/</span>
                        <Link :href="route('actualites.index')" class="transition-colors hover:text-ed-yellow">Actualités</Link>
                        <template v-if="actualite?.category">
                            <span aria-hidden="true">/</span>
                            <span class="font-semibold text-white">{{ actualite.category.nom }}</span>
                        </template>
                    </nav>

                    <div class="mb-4 flex flex-wrap gap-2">
                        <span v-if="actualite?.est_important" class="rounded-full bg-red-600 px-3 py-1 text-xs font-semibold text-white shadow">Important</span>
                        <span v-if="actualite?.category" class="rounded-full px-3 py-1 text-xs font-semibold text-white shadow" :style="{ backgroundColor: actualite.category.couleur || '#16826A' }">
                            {{ actualite.category.nom }}
                        </span>
                    </div>

                    <h1 class="max-w-4xl text-2xl font-bold leading-tight text-white drop-shadow-[0_2px_10px_rgba(0,0,0,0.5)] sm:text-3xl md:text-4xl">
                        {{ actualite?.titre }}
                    </h1>

                    <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-white/85">
                        <span v-if="actualite?.auteur" class="inline-flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                            {{ actualite.auteur.name }}
                        </span>
                        <span v-if="actualite?.date_publication_human">{{ actualite.date_publication_human }}</span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            {{ actualite?.vues_formatted }} vues
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-12">
        <Container>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <article class="lg:col-span-8">
                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                        <div class="p-6 md:p-10">
                            <div
                                class="prose max-w-none text-justify prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-ed-primary prose-a:no-underline hover:prose-a:underline prose-img:rounded-xl"
                                v-html="actualite?.contenu"
                            ></div>
                        </div>

                        <!-- Galerie -->
                        <div v-if="gallery.length" class="px-6 pb-6 md:px-10">
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                                <h3 class="mb-4 text-sm font-semibold text-gray-900">Galerie photos ({{ gallery.length }})</h3>

                                <div class="group relative mb-3 overflow-hidden rounded-xl border border-gray-200">
                                    <div class="aspect-video bg-gray-200">
                                        <img v-if="gallery[galleryIndex]" :src="gallery[galleryIndex].url" :alt="`Photo ${galleryIndex + 1}`" class="absolute inset-0 h-full w-full object-cover">
                                    </div>

                                    <template v-if="gallery.length > 1">
                                        <button type="button" class="absolute left-3 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-gray-800 shadow transition hover:bg-white" @click="prevGallery" aria-label="Photo précédente">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                                        </button>
                                        <button type="button" class="absolute right-3 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-gray-800 shadow transition hover:bg-white" @click="nextGallery" aria-label="Photo suivante">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                        </button>
                                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 rounded-full bg-black/50 px-3 py-1 text-xs font-semibold text-white">{{ galleryIndex + 1 }} / {{ gallery.length }}</div>
                                    </template>
                                </div>

                                <div class="grid grid-cols-4 gap-2 sm:grid-cols-6 md:grid-cols-7">
                                    <button
                                        v-for="(photo, index) in gallery"
                                        :key="photo.id"
                                        type="button"
                                        class="aspect-square overflow-hidden rounded-lg border transition"
                                        :class="galleryIndex === index ? 'border-ed-primary ring-1 ring-ed-primary' : 'border-gray-200 opacity-70 hover:opacity-100'"
                                        @click="galleryIndex = index"
                                    >
                                        <img :src="photo.url" :alt="`Miniature ${index + 1}`" class="h-full w-full object-cover">
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div v-if="actualite?.tags?.length" class="px-6 pb-6 md:px-10">
                            <h4 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Tags</h4>
                            <div class="flex flex-wrap gap-2">
                                <Link
                                    v-for="tag in actualite.tags"
                                    :key="tag.id"
                                    :href="route('actualites.index', { tag: tag.slug })"
                                    class="rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-ed-primary hover:text-white"
                                >
                                    #{{ tag.nom }}
                                </Link>
                            </div>
                        </div>

                        <!-- Partage -->
                        <div class="px-6 pb-6 md:px-10">
                            <h4 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Partager</h4>
                            <div class="flex flex-wrap gap-2">
                                <a :href="shareLinks.facebook" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-ed-primary/40 hover:text-ed-primary">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg>
                                    Facebook
                                </a>
                                <a :href="shareLinks.twitter" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-ed-primary/40 hover:text-ed-primary">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" /></svg>
                                    Twitter
                                </a>
                                <a :href="shareLinks.linkedin" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-ed-primary/40 hover:text-ed-primary">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" /></svg>
                                    LinkedIn
                                </a>
                                <a :href="shareLinks.whatsapp" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-ed-primary/40 hover:text-ed-primary">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" /></svg>
                                    WhatsApp
                                </a>
                            </div>
                        </div>

                        <!-- Navigation préc / suiv -->
                        <div class="flex items-center justify-between gap-4 border-t border-gray-100 px-6 py-5 md:px-10">
                            <Link v-if="precedent" :href="route('actualites.show', precedent.slug)" class="group inline-flex items-center gap-2 text-sm font-semibold text-ed-primary transition hover:text-ed-secondary">
                                <svg class="h-5 w-5 transition-transform group-hover:-translate-x-0.5 motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                Article précédent
                            </Link>
                            <span v-else></span>

                            <Link v-if="suivant" :href="route('actualites.show', suivant.slug)" class="group inline-flex items-center gap-2 text-sm font-semibold text-ed-primary transition hover:text-ed-secondary">
                                Article suivant
                                <svg class="h-5 w-5 transition-transform group-hover:translate-x-0.5 motion-reduce:transform-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </Link>
                        </div>
                    </div>
                </article>

                <!-- Aside : similaires -->
                <aside class="lg:col-span-4">
                    <div class="lg:sticky lg:top-24">
                        <div class="rounded-xl border border-gray-200 bg-white p-5">
                            <h3 class="mb-4 text-sm font-semibold text-gray-900">Articles similaires</h3>
                            <div v-if="similaires?.length" class="space-y-4">
                                <Link
                                    v-for="item in similaires"
                                    :key="item.id"
                                    :href="route('actualites.show', item.slug)"
                                    class="group flex items-center gap-3"
                                >
                                    <div class="h-16 w-16 flex-none overflow-hidden rounded-lg bg-gray-100">
                                        <img v-if="item.image_url" :src="item.image_url" :alt="item.titre" class="h-full w-full object-cover">
                                        <div v-else class="h-full w-full bg-ed-primary"></div>
                                    </div>
                                    <p class="line-clamp-2 text-sm font-semibold text-gray-900 transition-colors group-hover:text-ed-primary">{{ item.titre }}</p>
                                </Link>
                            </div>
                            <p v-else class="text-sm text-gray-500">Aucun article similaire pour le moment.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </Container>
    </section>
</template>
