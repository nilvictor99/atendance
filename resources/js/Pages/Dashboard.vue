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
            route: 'timesheet',
            background:
                'https://i.pinimg.com/736x/ce/c4/b3/cec4b3cc815353d62435bb666868594a.jpg',
        },
        {
            name: 'Contraseñas',
            route: 'password-vault',
            background:
                'https://i.pinimg.com/736x/ae/48/4a/ae484a15a84631934a735e96ad73147d.jpg',
        },
    ];
</script>

<template>
    <AppLayout title="Dashboard">
        <div
            class="pb-12 bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen mt-3"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <div class="relative px-4 sm:px-6 lg:px-12">
                        <div class="relative">
                            <img
                                src="/System/Samples/fondo-sample.webp"
                                alt="Background User"
                                class="object-cover w-full h-32 sm:h-40 rounded-t-xl shadow-lg"
                                style="filter: brightness(0.95) saturate(1.1)"
                            />
                            <div
                                class="absolute inset-0 bg-black bg-opacity-20 rounded-t-xl"
                            ></div>
                        </div>
                        <div
                            class="absolute -translate-x-1/2 left-1/2 -bottom-8 cursor-pointer"
                        >
                            <a :href="route('dashboard')" class="block">
                                <img
                                    class="border-4 border-white rounded-full w-24 h-24 sm:w-32 sm:h-32 lg:w-36 lg:h-36 shadow-xl hover:shadow-2xl transition-shadow duration-300"
                                    :src="profile_photo_url"
                                    alt="Profile Photo"
                                />
                            </a>
                        </div>
                    </div>

                    <div class="px-4 sm:px-6 lg:px-12 mt-12">
                        <div
                            class="p-6 bg-white rounded-lg shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300"
                        >
                            <div class="text-center">
                                <span
                                    class="text-xl sm:text-2xl font-bold text-gray-900"
                                >
                                    {{ name }}
                                </span>
                            </div>
                            <div class="mt-2 text-center">
                                <span
                                    class="text-sm sm:text-base text-gray-600"
                                >
                                    {{ email }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 sm:px-6 rounded-lg lg:px-12">
                        <div
                            class="bg-white rounded-md overflow-hidden shadow-xl sm:rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300"
                        >
                            <h3
                                class="text-2xl sm:text-3xl font-bold mb-6 text-gray-800"
                            >
                                {{ $t('Statistics') }}
                            </h3>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6"
                            >
                                <div
                                    class="bg-gradient-to-r from-gray-100 to-gray-200 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
                                >
                                    <h3
                                        class="text-lg font-semibold text-gray-700"
                                    >
                                        {{ $t('Total Employees') }}
                                    </h3>
                                    <p class="text-3xl font-bold text-gray-900">
                                        {{ totalEmployees }}
                                    </p>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-green-100 to-green-200 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
                                >
                                    <h3
                                        class="text-lg font-semibold text-green-700"
                                    >
                                        {{ $t('Present Today') }}
                                    </h3>
                                    <p
                                        class="text-3xl font-bold text-green-900"
                                    >
                                        {{ presentToday }}
                                    </p>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-red-100 to-red-200 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
                                >
                                    <h3
                                        class="text-lg font-semibold text-red-700"
                                    >
                                        {{ $t('Absent Today') }}
                                    </h3>
                                    <p class="text-3xl font-bold text-red-900">
                                        {{ absentToday }}
                                    </p>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-yellow-100 to-yellow-200 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
                                >
                                    <h3
                                        class="text-lg font-semibold text-yellow-700"
                                    >
                                        {{ $t('Attendance Rate') }}
                                    </h3>
                                    <p
                                        class="text-3xl font-bold text-yellow-900"
                                    >
                                        {{ attendanceRate }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Options Access -->
                    <div
                        class="grid grid-cols-1 gap-6 px-4 sm:px-6 lg:px-10 mt-5 sm:grid-cols-2 lg:grid-cols-2"
                    >
                        <div
                            v-for="option in optionsAccess"
                            :key="option.name"
                            class="overflow-hidden transition-transform duration-300 transform rounded-xl hover:scale-105 shadow-lg hover:shadow-2xl"
                        >
                            <a :href="option.route" class="block">
                                <div class="relative group">
                                    <img
                                        :src="option.background"
                                        alt=""
                                        class="object-cover w-full h-40 sm:h-48 rounded-t-xl"
                                        style="
                                            filter: brightness(0.97)
                                                saturate(1.1);
                                        "
                                    />
                                    <div
                                        class="absolute inset-0 transition-opacity duration-300 opacity-75 bg-gradient-to-t from-gray-600 via-transparent to-transparent group-hover:opacity-50 rounded-t-xl"
                                    ></div>
                                    <div
                                        class="absolute bottom-0 w-full p-4 bg-gradient-to-t from-gray-400 via-transparent to-transparent rounded-b-xl"
                                    >
                                        <span
                                            class="text-lg sm:text-xl font-semibold text-white"
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
        </div>
    </AppLayout>
</template>
