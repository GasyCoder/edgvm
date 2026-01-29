<script setup>
import Navbar from '@/Components/Frontend/Navbar.vue';
import Footer from '@/Components/Frontend/Footer.vue';
import ScrollToTop from '@/Components/Frontend/ScrollToTop.vue';
import FlashMessage from '@/Components/Common/FlashMessage.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col overflow-x-hidden">
        <Navbar />

        <FlashMessage />

        <main id="main-content" class="flex-grow">
            <slot />
        </main>

        <Footer />
        <ScrollToTop />
    </div>
</template>
