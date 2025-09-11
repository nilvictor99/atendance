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
    import Delete from '@/Components/Buttons/Delete.vue';
    import ModalDelete from '@/Components/Modals/ModalDelete.vue';

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

    const isDeleting = ref(false);
    const showDeleteModal = ref(false);
    const vaultToDelete = ref(null);
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

    const handleDelete = vault => {
        vaultToDelete.value = vault.id;
        showDeleteModal.value = true;
    };

    const confirmDelete = () => {
        if (!vaultToDelete.value) return;
        isDeleting.value = true;
        router.delete(
            route('password-vault.destroy', { id: vaultToDelete.value }),
            {
                preserveScroll: true,
                onFinish: () => {
                    isDeleting.value = false;
                    showDeleteModal.value = false;
                    vaultToDelete.value = null;
                },
                onError: console.error,
            }
        );
    };
</script>

<template>
    <AppLayout title="Password Vault">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-2 w-full max-w-7xl">
            <div class="flex flex-col gap-4 mt-1">
                <!-- Filtros -->
                <div class="bg-white shadow-lg rounded-lg p-4 md:p-6">
                    <div
                        class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-end"
                    >
                        <DateRangeFilter
                            v-model="dateRange"
                            label="Filtrar por fechas"
                            borderColor="border-gray-300"
                            focusColor="focus:border-indigo-500 focus:ring-indigo-200"
                            :bold="true"
                            :disabled="false"
                            :cleanButton="true"
                            @filter="handleSearch"
                        />
                    </div>
                </div>

                <div class="bg-white shadow-lg rounded-lg p-4 md:p-6">
                    <div
                        class="flex flex-col sm:flex-row gap-4 mb-6 items-center"
                    >
                        <InputListSearch
                            v-model="search"
                            :cleanButton="true"
                            placeholder="Buscar por nombre, usuario o URL"
                            @search="handleSearch"
                            class="flex-1"
                        />
                        <ClasicButton
                            :roles="['super usuario', 'super_admin', 'Staff']"
                            :permissions="['create_password::vault']"
                            @click="
                                $inertia.visit(route('password-vault.create'))
                            "
                            class="flex-1 sm:flex-none w-full sm:w-auto flex justify-center"
                        >
                            Nueva Contraseña
                        </ClasicButton>
                    </div>

                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden"
                        >
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Nombre
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Usuario
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Contraseña
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        URL
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Tipo
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="vault in passwordVaults.data"
                                    :key="vault.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="px-4 py-4 text-sm font-medium text-gray-900 truncate max-w-xs"
                                    >
                                        {{ vault.name }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <CopyableText
                                            :text="vault.username"
                                            theme="gray"
                                            size="sm"
                                            :truncate="true"
                                            :maxLength="15"
                                        />
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <CopyableText
                                            :text="vault.password"
                                            theme="gray"
                                            size="sm"
                                            :truncate="true"
                                            :maxLength="15"
                                        />
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <CustomTooltip
                                            :content="vault.url"
                                            theme="gray"
                                        >
                                            <a
                                                :href="vault.url"
                                                target="_blank"
                                                class="truncate max-w-xs block text-indigo-600 hover:text-indigo-800 hover:underline"
                                            >
                                                {{
                                                    vault.url.length > 30
                                                        ? vault.url.substring(
                                                              0,
                                                              30
                                                          ) + '...'
                                                        : vault.url
                                                }}
                                            </a>
                                        </CustomTooltip>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <span
                                            :class="
                                                vault.type === 'public'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-blue-100 text-blue-800'
                                            "
                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                        >
                                            {{ $t(vault.type) }}
                                        </span>
                                    </td>
                                    <td
                                        class="flex justify-center px-4 py-4 text-center text-sm font-medium"
                                    >
                                        <Edit
                                            :roles="[
                                                'super usuario',
                                                'super_admin',
                                            ]"
                                            :permissions="[
                                                'update_password::vault',
                                            ]"
                                            @click="
                                                $inertia.visit(
                                                    route(
                                                        'password-vault.edit',
                                                        { id: vault.id }
                                                    )
                                                )
                                            "
                                            class="text-indigo-600 hover:text-indigo-900 cursor-pointer"
                                        />
                                        <Delete
                                            :roles="[
                                                'super usuario',
                                                'super_admin',
                                            ]"
                                            :permissions="[
                                                'delete_password::vault',
                                            ]"
                                            @click="handleDelete(vault)"
                                            class="text-red-600 hover:text-red-900 cursor-pointer"
                                            :disabled="isDeleting"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <Pagination :pagination="passwordVaults" />
                    </div>
                </div>
            </div>
        </section>

        <ModalDelete
            :show="showDeleteModal"
            title="Eliminar Contraseña"
            description="¿Está seguro que desea eliminar esta contraseña? Esta acción no se puede deshacer."
            itemType="Contraseña"
            :itemId="vaultToDelete"
            @close="showDeleteModal = false"
            @confirm="confirmDelete"
            :loading="isDeleting"
        />
    </AppLayout>
</template>
