<script setup>
    import Pagination from '@/Components/Sections/SectionPagination.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import ClasicButton from '@/Components/Buttons/ClasicButton.vue';
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import Edit from '@/Components/Buttons/Edit.vue';
    import InputListSearch from '@/Components/Inputs/InputListSearch.vue';
    import DateRangeFilter from '@/Components/Sections/SectionDateRangeFilter.vue';
    import CustomTooltip from '@/Components/Utils/CustomTooltip.vue';
    import CopyableText from '@/Components/Utils/CopyableText.vue';

    const props = defineProps({
        passwordVaults: {
            type: Object,
            required: true,
        },
        search: {
            type: String,
            default: '',
        },
        dateRange: {
            type: Object,
            default: () => ({ start: '', end: '' }),
        },
    });

    const search = ref(props.search);
    const dateRange = ref(props.dateRange || { start: '', end: '' });

    function handleSearch(val) {
        if (typeof val === 'object' && val !== null) {
            dateRange.value = val;
        }
        router.get(
            route('password-vault.list'),
            {
                search: search.value,
                start: dateRange.value.start,
                end: dateRange.value.end,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }
</script>

<template>
    <AppLayout title="Password Vault">
        <section class="mx-auto px-1 sm:px-6 lg:px-4 py-1 w-full max-w-7xl">
            <div class="flex md:flex-col flex-col gap-1 mt-2">
                <!-- Filtros -->
                <div
                    class="h-24 grid grid-cols-1 md:grid-cols-2 gap-2 items-center justify-between bg-white shadow sm:rounded-lg px-4 py-2"
                >
                    <DateRangeFilter
                        v-model="dateRange"
                        label="Filtrar por fechas"
                        borderColor="border-gray-300"
                        focusColor="focus:border-gray-500 focus:ring-gray-200"
                        :bold="true"
                        :disabled="false"
                        :cleanButton="true"
                        @filter="handleSearch"
                    />
                </div>

                <div class="py-2 px-4 bg-white shadow sm:rounded-lg w-full">
                    <div
                        class="flex justify-between sm:rounded-lg px-1 py-1 mb-2"
                    >
                        <InputListSearch
                            v-model="search"
                            :cleanButton="true"
                            placeholder="Buscar por nombre, usuario o URL"
                            @search="handleSearch"
                        />
                        <ClasicButton
                            @click="
                                $inertia.visit(route('password-vault.create'))
                            "
                        >
                            Nueva Contraseña
                        </ClasicButton>
                    </div>

                    <div class="flex flex-col space-y-4">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div
                                    class="overflow-hidden border border-gray-200 rounded-lg min-h-[400px] mb-2"
                                >
                                    <table
                                        class="min-w-full divide-y divide-gray-200"
                                    >
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    Nombre
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    Usuario
                                                </th>

                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    Contraseña
                                                </th>

                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    URL
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    Tipo
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr
                                                v-for="vault in passwordVaults.data"
                                                :key="vault.id"
                                            >
                                                <td
                                                    class="max-w-[200px] px-4 py-4 text-sm font-medium text-gray-800"
                                                >
                                                    {{ vault.name }}
                                                </td>
                                                <td
                                                    class="max-w-[200px] px-4 py-4 text-sm text-gray-800"
                                                >
                                                    <CopyableText
                                                        :text="vault.username"
                                                        theme="gray"
                                                        size="sm"
                                                        :truncate="true"
                                                        :maxLength="15"
                                                    />
                                                </td>
                                                <td
                                                    class="max-w-[200px] px-4 py-4 text-sm text-gray-800"
                                                >
                                                    <CopyableText
                                                        :text="vault.password"
                                                        theme="gray"
                                                        size="sm"
                                                        :truncate="true"
                                                        :maxLength="15"
                                                    />
                                                </td>
                                                <td
                                                    class="px-4 py-4 text-sm text-gray-800"
                                                >
                                                    <CustomTooltip
                                                        :content="vault.url"
                                                        theme="gray"
                                                    >
                                                        <a
                                                            :href="vault.url"
                                                            target="_blank"
                                                            class="truncate max-w-[13ch] block font-bold text-indigo-600 cursor-pointer hover:underline"
                                                        >
                                                            {{
                                                                vault.url
                                                                    .split(' ')
                                                                    .slice(
                                                                        0,
                                                                        10
                                                                    )
                                                                    .join(' ') +
                                                                (vault.url.split(
                                                                    ' '
                                                                ).length > 10
                                                                    ? '...'
                                                                    : '')
                                                            }}
                                                        </a>
                                                    </CustomTooltip>
                                                </td>
                                                <td
                                                    class="px-4 py-4 text-sm text-gray-800"
                                                >
                                                    <span
                                                        :class="
                                                            vault.type ===
                                                            'public'
                                                                ? 'bg-green-100 text-green-800'
                                                                : 'bg-blue-100 text-blue-800'
                                                        "
                                                        class="px-2 py-1 rounded-full text-xs font-medium inline-block"
                                                    >
                                                        {{ $t(vault.type) }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="flex items-center justify-end px-5 py-4 whitespace-nowrap text-sm font-medium"
                                                >
                                                    <Edit
                                                        @click="
                                                            $inertia.visit(
                                                                route(
                                                                    'password-vault.edit',
                                                                    {
                                                                        id: vault.id,
                                                                    }
                                                                )
                                                            )
                                                        "
                                                    />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="px-2 py-1">
                                    <Pagination :pagination="passwordVaults" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
