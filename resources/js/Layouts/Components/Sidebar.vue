<script setup>
    import { ref } from 'vue';
    import NavLink from '@/Components/Navlinks/NavLink.vue';

    // Example modules and submodules; adjust as needed
    const modules = ref([
        {
            name: 'Dashboard',
            route: 'dashboard',
            submodules: [],
        },
        {
            name: 'Asistencias',
            route: 'timesheet',
            submodules: [
                { name: 'Registro', route: 'timesheet.register' },
                { name: 'Reportes', route: 'timesheet.reports' },
            ],
        },
        {
            name: 'Contraseñas',
            route: 'password-vault',
            submodules: [
                { name: 'Lista', route: 'password-vault.list' },
                { name: 'Agregar', route: 'password-vault.add' },
            ],
        },
    ]);

    const expandedModules = ref({});
</script>

<template>
    <aside class="bg-gray-200 w-64 min-h-screen p-4 overflow-y-auto">
        <nav>
            <ul class="space-y-2">
                <li v-for="(module, index) in modules" :key="index">
                    <div
                        class="flex justify-center items-center border border-gray-300 rounded-md bg-white p-2 hover:bg-gray-100 cursor-pointer"
                        @click="
                            expandedModules[index] = !expandedModules[index]
                        "
                    >
                        <NavLink
                            :href="route(module.route)"
                            :active="route().current(module.route)"
                            class="block text-center"
                        >
                            {{ module.name }}
                        </NavLink>
                    </div>
                    <ul
                        v-if="
                            expandedModules[index] &&
                            module.submodules.length > 0
                        "
                        class="ml-4 space-y-1 mt-1"
                    >
                        <li
                            v-for="(sub, subIndex) in module.submodules"
                            :key="subIndex"
                        >
                            <div
                                class="border border-gray-300 rounded-md bg-white p-2 hover:bg-gray-100"
                            >
                                <NavLink
                                    :href="route(sub.route)"
                                    :active="route().current(sub.route)"
                                    class="block"
                                >
                                    {{ sub.name }}
                                </NavLink>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </aside>
</template>
