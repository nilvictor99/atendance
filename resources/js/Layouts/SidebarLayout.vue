<script setup>
    import { ref } from 'vue';
    import { Head } from '@inertiajs/vue3';
    import Banner from '@/Components/Utils/Banner.vue';
    import Header from '@//Layouts/Components/Header.vue';
    import Sidebar from '@/Layouts/Components/Sidebar.vue';
    import Footer from '@/Layouts/Components/Footer.vue';

    defineProps({
        title: String,
    });

    const showingSidebar = ref(false);

    const toggleSidebar = () => {
        showingSidebar.value = !showingSidebar.value;
    };
</script>

<template>
    <div>
        <Head :title="title" />
        <Banner />
        <div class="min-h-screen bg-white flex flex-col">
            <Header :toggleSidebar="toggleSidebar" />
            <div class="flex flex-1 relative">
                <transition name="slide">
                    <Sidebar
                        v-if="showingSidebar"
                        class="absolute z-10 md:relative md:z-auto"
                    />
                </transition>
                <main class="flex-1 p-6">
                    <slot />
                </main>
            </div>

            <Footer />
        </div>
    </div>
</template>

<style>
    .slide-enter-active,
    .slide-leave-active {
        transition: transform 0.3s ease;
    }
    .slide-enter-from,
    .slide-leave-to {
        transform: translateX(-100%);
    }
</style>
