<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-ed-primary text-white text-xs font-semibold">
                        ED
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Espace utilisateur</p>
                        <p class="text-xs text-slate-500">EDGVM</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <Link :href="route('dashboard')" class="text-slate-600 hover:text-ed-primary">Dashboard</Link>
                    <span class="text-slate-300">|</span>
                    <span class="text-slate-500">{{ page.props.auth?.user?.name ?? 'Utilisateur' }}</span>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-8">
            <slot />
        </main>
    </div>
</template>
