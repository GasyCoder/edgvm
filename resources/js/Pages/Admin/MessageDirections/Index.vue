<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/Admin/PageHeader.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
import Pagination from '@/Components/Common/Pagination.vue';

const props = defineProps({
    messages: Object,
});

const toggleVisibility = (message) => {
    router.patch(route('admin.message-directions.toggle', message.id), {}, { preserveScroll: true });
};

const deleteMessage = (message) => {
    router.delete(route('admin.message-directions.destroy', message.id), { preserveScroll: true });
};
</script>

<template>
    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-slate-900">Messages direction</h1>
        </template>

        <Head title="Messages direction" />
        <FlashMessage />

        <div class="space-y-6">
            <PageHeader
                badge="Communication"
                title="Messages direction"
                description="Gerer les messages."
            />

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-100 text-left text-xs uppercase tracking-[0.16em] text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Message</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="message in messages.data" :key="message.id" class="hover:bg-slate-50/60">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div v-if="message.photo_url" class="h-10 w-10 overflow-hidden rounded-full border border-slate-200">
                                        <img :src="message.photo_url" alt="" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ message.nom }}</p>
                                        <p v-if="message.fonction" class="mt-1 text-xs text-slate-500">{{ message.fonction }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="rounded-full border px-2.5 py-1 text-xs"
                                    :class="message.visible ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-white text-slate-500'"
                                >
                                    {{ message.visible ? 'Visible' : 'Masque' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white" @click="toggleVisibility(message)">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ message.visible ? 'Masquer' : 'Afficher' }}
                                    </button>
                                    <Link :href="route('admin.message-directions.edit', message.id)" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-white">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 16.536A4 4 0 0110 17H7v-3a4 4 0 011-2.536z" />
                                        </svg>
                                        Modifier
                                    </Link>
                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" @click="deleteMessage(message)">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h4a1 1 0 011 1m-7 0h8" />
                                        </svg>
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="!messages.data.length" class="px-6 py-10 text-center text-sm text-slate-500">
                    Aucun message disponible.
                </div>
            </div>

            <Pagination v-if="messages.links" :links="messages.links" />
        </div>
    </AdminLayout>
</template>
