<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

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

    galleryIndex.value = galleryIndex.value === 0
        ? gallery.value.length - 1
        : galleryIndex.value - 1;
};

const nextGallery = () => {
    if (!gallery.value.length) {
        return;
    }

    galleryIndex.value = galleryIndex.value === gallery.value.length - 1
        ? 0
        : galleryIndex.value + 1;
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
    <Head :title="actualite?.titre || 'Actualite'" />

    <div>
        <section class="relative h-[50vh] sm:h-[55vh] md:h-[60vh] min-h-[350px] sm:min-h-[400px] md:min-h-[450px] overflow-hidden">
            <div v-if="actualite?.image_url" class="absolute inset-0">
                <div
                    class="absolute inset-0 bg-center bg-cover"
                    :style="{
                        backgroundImage: `url('${actualite.image_url}')`,
                        backgroundPosition: '50% 35%',
                        filter: 'brightness(0.75) blur(0.5px)'
                    }"
                ></div>
            </div>
            <div v-else class="absolute inset-0 bg-gradient-to-br from-ed-primary via-ed-secondary to-purple-600"></div>

            <div class="absolute inset-0 bg-black/20"></div>

            <div class="absolute inset-0 flex items-center justify-center md:items-end">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 w-full">
                    <nav class="mb-4">
                        <ol class="flex items-center gap-2 text-xs text-white/70">
                            <li><Link :href="route('home')" class="hover:text-white transition">Accueil</Link></li>
                            <li>></li>
                            <li><Link :href="route('actualites.index')" class="hover:text-white transition">Actualites</Link></li>
                            <li v-if="actualite?.category">></li>
                            <li v-if="actualite?.category" class="text-white font-semibold">{{ actualite.category.nom }}</li>
                        </ol>
                    </nav>

                    <div class="flex flex-wrap gap-2 mb-5">
                        <span v-if="actualite?.est_important" class="px-3 py-1.5 bg-gradient-to-r from-red-600 to-orange-600 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1.5">
                            <span></span> Important
                        </span>
                        <span
                            v-if="actualite?.category"
                            class="px-3 py-1.5 text-white text-xs font-bold rounded-full shadow-md backdrop-blur-lg border border-white/30"
                            :style="{ background: `linear-gradient(135deg, ${actualite.category.couleur}99, ${actualite.category.couleur}dd)` }"
                        >
                            {{ actualite.category.nom }}
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight max-w-4xl" style="text-shadow: 0 2px 15px rgba(0,0,0,0.4);">
                        {{ actualite?.titre }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-white text-xs sm:text-sm relative z-10">
                        <div class="flex flex-wrap items-center gap-2 px-2 py-1.5 bg-black/30 rounded backdrop-blur-sm">
                            <template v-if="actualite?.auteur">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">{{ actualite.auteur.name }}</span>
                                </div>
                                <span class="text-white/60">*</span>
                            </template>

                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ actualite?.date_publication_human }}</span>
                            </div>

                            <span class="text-white/60">*</span>

                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>{{ actualite?.vues_formatted }} vues</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <article class="lg:col-span-8">
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                            <div class="p-6 md:p-10">
                                <div
                                    class="prose prose-gray max-w-none prose-headings:font-bold prose-headings:text-gray-900
                                           prose-h1:text-2xl prose-h2:text-xl prose-h3:text-lg prose-p:text-gray-700
                                           prose-p:leading-relaxed prose-p:text-base prose-a:text-ed-primary
                                           prose-a:no-underline hover:prose-a:underline prose-strong:text-gray-900
                                           prose-strong:font-semibold prose-ul:text-gray-700 prose-ol:text-gray-700
                                           prose-img:rounded-xl prose-img:shadow-lg"
                                    v-html="actualite?.contenu"
                                ></div>
                            </div>

                            <div v-if="gallery.length" class="px-6 md:px-10 pb-6">
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Galerie photos
                                        <span class="text-sm text-gray-500 font-normal">({{ gallery.length }})</span>
                                    </h3>

                                    <div class="relative rounded-xl overflow-hidden shadow-lg mb-4 group">
                                        <div class="aspect-video bg-gray-200">
                                            <img
                                                v-if="gallery[galleryIndex]"
                                                :src="gallery[galleryIndex].url"
                                                :alt="`Photo ${galleryIndex + 1}`"
                                                class="absolute inset-0 w-full h-full object-cover"
                                            >
                                        </div>

                                        <button
                                            v-if="gallery.length > 1"
                                            type="button"
                                            class="absolute left-2 sm:left-3 top-1/2 -translate-y-1/2 w-11 h-11 sm:w-10 sm:h-10 bg-white/90 sm:bg-white/80 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition sm:opacity-0 sm:group-hover:opacity-100 flex items-center justify-center"
                                            @click="prevGallery"
                                        >
                                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>

                                        <button
                                            v-if="gallery.length > 1"
                                            type="button"
                                            class="absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 w-11 h-11 sm:w-10 sm:h-10 bg-white/90 sm:bg-white/80 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition sm:opacity-0 sm:group-hover:opacity-100 flex items-center justify-center"
                                            @click="nextGallery"
                                        >
                                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>

                                        <div v-if="gallery.length > 1" class="absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-black/50 backdrop-blur-sm text-white rounded-full text-xs font-semibold">
                                            {{ galleryIndex + 1 }} / {{ gallery.length }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-4 gap-2 sm:grid-cols-5 md:grid-cols-7">
                                        <button
                                            v-for="(photo, index) in gallery"
                                            :key="photo.id"
                                            type="button"
                                            class="aspect-square rounded-lg overflow-hidden transition shadow-md"
                                            :class="galleryIndex === index ? 'ring-2 ring-ed-primary' : 'opacity-60 hover:opacity-100'"
                                            @click="galleryIndex = index"
                                        >
                                            <img :src="photo.url" :alt="`Thumbnail ${index + 1}`" class="w-full h-full object-cover">
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="actualite?.tags?.length" class="px-6 md:px-10 pb-6">
                                <h4 class="text-sm font-bold text-gray-600 uppercase mb-3">Tags</h4>
                                <div class="flex flex-wrap gap-2">
                                    <Link
                                        v-for="tag in actualite.tags"
                                        :key="tag.id"
                                        :href="route('actualites.index', { tag: tag.slug })"
                                        class="px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-xs font-semibold transition"
                                    >
                                        #{{ tag.nom }}
                                    </Link>
                                </div>
                            </div>

                            <div class="px-4 sm:px-6 md:px-10 pb-6">
                                <h4 class="text-sm font-bold text-gray-600 uppercase mb-3">Partager</h4>
                                <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
                                    <a :href="shareLinks.facebook" target="_blank" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        Facebook
                                    </a>

                                    <a :href="shareLinks.twitter" target="_blank" class="px-3 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                        Twitter
                                    </a>

                                    <a :href="shareLinks.linkedin" target="_blank" class="px-3 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                        LinkedIn
                                    </a>

                                    <a :href="shareLinks.whatsapp" target="_blank" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        WhatsApp
                                    </a>
                                </div>
                            </div>

                            <div class="px-6 md:px-10 pb-6 pt-6 border-t border-gray-100">
                                <div class="flex justify-between gap-4">
                                    <Link v-if="precedent" :href="route('actualites.show', precedent.slug)" class="group flex items-center gap-2 text-ed-primary hover:text-ed-secondary transition">
                                        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="text-sm font-semibold">Article precedent</span>
                                    </Link>
                                    <div v-else></div>

                                    <Link v-if="suivant" :href="route('actualites.show', suivant.slug)" class="group flex items-center gap-2 text-ed-primary hover:text-ed-secondary transition">
                                        <span class="text-sm font-semibold">Article suivant</span>
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </article>

                    <aside class="lg:col-span-4 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Articles similaires</h3>
                            <div class="space-y-4">
                                <Link
                                    v-for="item in similaires"
                                    :key="item.id"
                                    :href="route('actualites.show', item.slug)"
                                    class="group flex items-center gap-3"
                                >
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                        <img v-if="item.image_url" :src="item.image_url" :alt="item.titre" class="w-full h-full object-cover">
                                        <div v-else class="w-full h-full bg-gradient-to-br from-ed-primary to-ed-secondary"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 group-hover:text-ed-primary line-clamp-2">
                                            {{ item.titre }}
                                        </p>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
</template>
