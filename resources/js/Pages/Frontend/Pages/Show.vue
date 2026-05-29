<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Container from '@/Components/Frontend/UI/Container.vue';

const props = defineProps({
    page: Object,
});

const openOrganigramme = ref(false);

const isApropos = computed(() => props.page?.slug === 'a-propos');

const hasContent = computed(() => {
    return Boolean(props.page?.contenu) || (props.page?.sections || []).length > 0;
});

const formatText = (content) => {
    if (!content) {
        return '';
    }

    return content.replace(/\n/g, '<br>');
};

defineOptions({
    layout: FrontendLayout,
});
</script>

<template>
    <Head :title="page?.titre || 'Page'" />

    <!-- Hero -->
    <section class="mt-16 bg-ed-teal-dark py-12 text-white sm:mt-20 sm:py-16">
        <Container>
            <nav aria-label="Fil d'Ariane">
                <ol class="flex flex-wrap items-center gap-2 text-sm text-white/70">
                    <li>
                        <Link :href="route('home')" class="transition-colors hover:text-ed-yellow">Accueil</Link>
                    </li>
                    <li aria-hidden="true">/</li>
                    <li class="font-semibold text-white">{{ page?.titre }}</li>
                </ol>
            </nav>

            <h1 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">
                {{ page?.titre }}
            </h1>

            <p v-if="page?.updated_at" class="mt-3 text-sm text-white/70">
                Dernière mise à jour : <span class="font-semibold text-white/90">{{ page.updated_at }}</span>
            </p>
        </Container>
    </section>

    <!-- Content -->
    <section class="bg-gray-50 py-12 md:py-16">
        <Container>
            <!-- Empty state -->
            <div v-if="!hasContent" class="mx-auto max-w-2xl rounded-xl border border-gray-200 bg-white p-10 text-center md:p-12">
                <h2 class="text-lg font-semibold text-gray-900">Contenu en cours de rédaction</h2>
                <p class="mt-2 text-sm leading-relaxed text-gray-600">
                    Cette page sera prochainement enrichie par l'équipe de l'École Doctorale.
                </p>
            </div>

            <!-- With content -->
            <div v-else :class="isApropos ? 'grid grid-cols-1 gap-8 lg:grid-cols-3' : ''">
                <div :class="isApropos ? 'lg:col-span-2' : 'mx-auto max-w-8xl'">
                    <div class="space-y-8">
                        <!-- Main content -->
                        <article v-if="page?.contenu" class="rounded-xl border border-gray-200 bg-white p-6 md:p-8">
                            <div class="prose max-w-none text-justify leading-relaxed text-gray-700" v-html="page.contenu"></div>
                        </article>

                        <!-- Sections -->
                        <template v-if="page?.sections?.length">
                            <section
                                v-for="section in page.sections"
                                :key="section.id"
                                class="overflow-hidden rounded-xl border border-gray-200 bg-white"
                            >
                                <div v-if="section.image_url" class="grid grid-cols-1 lg:grid-cols-2">
                                    <div
                                        class="relative h-56 w-full overflow-hidden sm:h-64 lg:h-auto lg:min-h-[260px]"
                                        :class="section.index % 2 === 0 ? '' : 'lg:order-2'"
                                    >
                                        <img :src="section.image_url" :alt="section.titre || 'Illustration'" class="h-full w-full object-cover" loading="lazy" decoding="async">
                                    </div>

                                    <div class="p-6 md:p-8" :class="section.index % 2 === 0 ? '' : 'lg:order-1'">
                                        <h2 v-if="section.titre" class="text-lg font-semibold text-gray-900 md:text-xl">
                                            {{ section.titre }}
                                        </h2>
                                        <div v-if="section.contenu" class="prose mt-3 max-w-none text-justify leading-relaxed text-gray-700" v-html="formatText(section.contenu)"></div>
                                    </div>
                                </div>

                                <div v-else class="p-6 md:p-8">
                                    <h2 v-if="section.titre" class="text-lg font-semibold text-gray-900 md:text-xl">
                                        {{ section.titre }}
                                    </h2>
                                    <div v-if="section.contenu" class="prose mt-3 max-w-none text-justify leading-relaxed text-gray-700" v-html="formatText(section.contenu)"></div>
                                </div>
                            </section>
                        </template>
                    </div>
                </div>

                <!-- Aside : organigramme (a-propos uniquement) -->
                <aside v-if="isApropos" class="lg:col-span-1">
                    <div class="lg:sticky lg:top-24">
                        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                            <div class="border-b border-gray-100 p-5">
                                <h3 class="text-sm font-semibold text-gray-900">Organigramme de l'École Doctorale</h3>
                                <p class="mt-1 text-xs leading-relaxed text-gray-500">
                                    Direction, équipes d'accueil et instances de l'EDGVM.
                                </p>
                            </div>

                            <button type="button" class="block w-full" @click="openOrganigramme = true" aria-label="Agrandir l'organigramme">
                                <img
                                    src="/images/organigramme-edgvm.png"
                                    alt="Organigramme de l'École Doctorale EDGVM"
                                    class="w-full bg-gray-50 object-contain"
                                    loading="lazy"
                                >
                            </button>

                            <div class="flex items-center justify-between gap-2 border-t border-gray-100 p-4">
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-ed-primary transition-colors hover:text-ed-secondary"
                                    @click="openOrganigramme = true"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                    </svg>
                                    Agrandir
                                </button>

                                <a
                                    href="/docs/organigramme-edgvm.pdf"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-600 transition-colors hover:text-ed-primary"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M4 6a2 2 0 012-2h7l5 5v9a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                                    </svg>
                                    Version PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </Container>
    </section>

    <!-- Modal organigramme -->
    <Teleport to="body">
        <div
            v-if="openOrganigramme"
            class="fixed inset-0 z-[999] flex items-center justify-center bg-black/70 p-4"
            @click.self="openOrganigramme = false"
        >
            <div class="w-full max-w-5xl">
                <div class="mb-3 flex items-center justify-between gap-3 text-white">
                    <h4 class="text-sm font-semibold md:text-base">Organigramme de l'École Doctorale EDGVM</h4>
                    <div class="flex items-center gap-2">
                        <a
                            href="/docs/organigramme-edgvm.pdf"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-gray-800 shadow"
                        >
                            PDF
                        </a>
                        <button
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-white/30 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-white/10"
                            @click="openOrganigramme = false"
                        >
                            Fermer
                        </button>
                    </div>
                </div>

                <div class="max-h-[78vh] overflow-auto rounded-xl bg-white shadow-2xl">
                    <img src="/images/organigramme-edgvm.png" alt="Organigramme de l'École Doctorale EDGVM" class="h-auto w-full">
                </div>
            </div>
        </div>
    </Teleport>
</template>
