<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { ref } from 'vue';

    const totalEmployees = ref(150);
    const presentToday = ref(142);
    const absentToday = ref(8);
    const attendanceRate = ref(94.7);

    const props = defineProps({
        auth: Object,
    });

    const { name, email, profile_photo_url } = props.auth.user;

    const optionsAccess = [
        {
            name: 'Asistencias',
            route: 'TimeSheet',
            background:
                'https://i.pinimg.com/736x/ce/c4/b3/cec4b3cc815353d62435bb666868594a.jpg',
        },
        {
            name: 'Contraseñas',
            route: 'PasswordsVault',
            background:
                'https://i.pinimg.com/736x/ae/48/4a/ae484a15a84631934a735e96ad73147d.jpg',
        },
    ];
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="pb-12 bg-indigo-50 min-h-screen mt-3">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div>
                    <div class="relative px-12">
                        <div>
                            <img
                                src="/System/Samples/fondo-sample.webp"
                                alt="Background User"
                                class="object-cover w-full h-40 rounded-t-xl"
                                style="filter: brightness(0.95) saturate(1.1)"
                            />
                        </div>
                        <div
                            class="absolute -translate-x-1/2 left-1/2 -bottom-8 cursor-pointer"
                        >
                            <a :href="route('dashboard')">
                                <img
                                    class="border-4 border-indigo-100 rounded-full w-36 h-36"
                                    :src="profile_photo_url"
                                    alt="Profile Photo"
                                />
                            </a>
                        </div>
                    </div>

                    <div class="px-12 mt-12">
                        <div
                            class="p-6 bg-white rounded-lg shadow-md border border-gray-100"
                        >
                            <div class="text-center">
                                <span
                                    class="text-lg font-semibold text- gray-900"
                                >
                                    {{ name }}
                                </span>
                            </div>
                            <div class="mt-2 text-center">
                                <span class="text-sm text-gray-500">
                                    {{ email }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="px-12 mt-6">
                        <div
                            class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6"
                        >
                            <h1 class="text-2xl font-bold mb-6">
                                Attendance Dashboard
                            </h1>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                            >
                                <div class="bg-gray-100 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold">
                                        Total Employees
                                    </h3>
                                    <p class="text-2xl">{{ totalEmployees }}</p>
                                </div>
                                <div class="bg-green-100 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold">
                                        Present Today
                                    </h3>
                                    <p class="text-2xl">{{ presentToday }}</p>
                                </div>
                                <div class="bg-red-100 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold">
                                        Absent Today
                                    </h3>
                                    <p class="text-2xl">{{ absentToday }}</p>
                                </div>
                                <div class="bg-yellow-100 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold">
                                        Attendance Rate
                                    </h3>
                                    <p class="text-2xl">
                                        {{ attendanceRate }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 gap-6 px-10 mt-5 sm:grid-cols-2 lg:grid-cols-2"
                >
                    <div
                        v-for="option in optionsAccess"
                        :key="option.name"
                        class="overflow-hidden transition-transform duration-300 transform rounded-xl hover:scale-105"
                    >
                        <a :href="option.route">
                            <div class="relative group">
                                <img
                                    :src="option.background"
                                    alt=""
                                    class="object-cover w-full h-48 rounded-t-xl"
                                    style="
                                        filter: brightness(0.97) saturate(1.1);
                                    "
                                />
                                <div
                                    class="absolute inset-0 transition-opacity duration-300 opacity-75 bg-gradient-to-t from-gray-600 via-transparent to-transparent group-hover:opacity-50"
                                ></div>
                                <div
                                    class="absolute bottom-0 w-full p-4 bg-gradient-to-t from-gray-400 via-transparent to-transparent"
                                >
                                    <span
                                        class="text-lg font-semibold text-gray-50"
                                    >
                                        {{ option.name }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
