<script>
    export default {
        name: 'FooterLayout',
    };
</script>
<script setup>
    import InstagramIcon from '@/Components/Icons/Instagram.vue';
    import TwiterIcon from '@/Components/Icons/Twiter.vue';
    import GithubIcon from '@/Components/Icons/Github.vue';
</script>

<template>
    <div class="bg-gray-500 text-white py-2">
        <div class="max-w-8xl mx-auto px-2 sm:px-4 lg:px-4">
            <div class="flex justify-between text-center items-center">
                <div class="mb-2">
                    <h3 class="text-lg font-semibold">Attendance</h3>
                    <p class="text-sm text-gray-100">
                        Todos los derechos reservados
                    </p>
                </div>
                <div class="flex space-x-4">
                    <slot name="social">
                        <a
                            href="#"
                            class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                        >
                            <InstagramIcon />
                        </a>
                        <a
                            href="#"
                            class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                        >
                            <TwiterIcon />
                        </a>
                        <a
                            href="#"
                            class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                        >
                            <GithubIcon />
                        </a>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>
