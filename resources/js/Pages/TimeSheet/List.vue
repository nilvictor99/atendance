<script setup>
    import Pagination from '@/Components/Sections/SectionPagination.vue';
    import { computed } from 'vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import ClasicButton from '@/Components/Buttons/ClasicButton.vue';
    import { ref, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';
    import Edit from '@/Components/Buttons/Edit.vue';
    import { usePage } from '@inertiajs/vue3';
    import InputListSearch from '@/Components/Inputs/InputListSearch.vue';
    import DateRangeFilter from '@/Components/Sections/SectionDateRangeFilter.vue';
    import InputSelectClasic from '@/Components/Inputs/InputSelectClasic.vue';

    const props = defineProps({
        timesheets: {
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
        staff: {
            type: Array,
            default: () => [],
        },
        staffId: { type: [String, Number], default: '' },
    });
    const search = ref(props.search);
    const dateRange = ref(props.dateRange || { start: '', end: '' });
    const selectedStaffText = computed(() => {
        if (!props.staffId) return '';
        const selectedStaff = props.staff.find(
            staff => staff.id.toString() === props.staffId.toString()
        );
        return selectedStaff?.profile?.full_name || selectedStaff?.name || '';
    });
    const staffId = ref(props.staffId ?? '');
    const staffOptions = computed(() =>
        props.staff.map(staff => ({
            id: staff.id,
            text: staff.profile?.full_name || staff.name || 'Sin nombre',
            originalData: staff,
        }))
    );

    function handleSearch(val) {
        if (typeof val === 'object' && val !== null) {
            dateRange.value = val;
        }
        if (typeof val === 'string') {
            search.value = val;
        }
        router.get(
            route('timesheets.list'),
            {
                search: search.value,
                start: dateRange.value.start,
                end: dateRange.value.end,
                staff_id: staffId.value,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }

    const isDeleting = ref(false);
    const showDeleteModal = ref(false);
    const timesheetToDelete = ref(null);
    const itemType = ref('Timesheet');
    const itemDetails = computed(() => {
        if (!timesheetToDelete.value) return {};
        return (
            data.value.find(item => item.id === timesheetToDelete.value) || {}
        );
    });
    const confirmDelete = () => {
        if (!timesheetToDelete.value) return;

        isDeleting.value = true;
        router.delete(
            route('timesheets.delete', { id: timesheetToDelete.value }),
            {
                preserveScroll: true,
                onFinish: () => {
                    isDeleting.value = false;
                    showDeleteModal.value = false;
                    timesheetToDelete.value = null;
                },
            }
        );
    };

    const data = computed(
        () =>
            props.timesheets.data?.map(timesheet => ({
                ...timesheet,
                staff: timesheet.staff || {
                    profile: { full_name: 'Sin staff' },
                },
                user: timesheet.user || {
                    profile: { full_name: 'Sin usuario' },
                },
            })) || []
    );

    const page = usePage();
    const pdfUrl = page.props.jetstream?.flash?.pdf;

    onMounted(() => {
        if (pdfUrl) {
            const url = pdfUrl.startsWith('http')
                ? pdfUrl
                : `${window.location.origin}${pdfUrl}`;
            window.open(url, '_blank');
        }
    });
</script>

<template>
    <AppLayout title="Timesheets">
        <section class="mx-auto px-1 sm:px-6 lg:px-4 py-1 w-full max-w-7xl">
            <div class="flex md:flex-col flex-col gap-1 mt-2">
                <div
                    class="h-24 grid grid-cols-1 md:grid-cols-2 gap-2 items-center justify-between bg-white shadow sm:rounded-lg px-4 py-2"
                >
                    <DateRangeFilter
                        v-model="dateRange"
                        label="Filtrar por fechas"
                        borderColor="border-orange-400"
                        focusColor="focus:border-orange-500 focus:ring-orange-200"
                        :bold="true"
                        :disabled="false"
                        :cleanButton="true"
                        @filter="handleSearch"
                    />
                    <InputSelectClasic
                        v-model="staffId"
                        :options="staffOptions"
                        :initialValue="{
                            id: props.staffId,
                            text: selectedStaffText,
                        }"
                        label="Elige un staff"
                        placeholder="Selecciona un staff"
                        :disabled="false"
                        theme="orange"
                        :bold="true"
                        :CleanButton="true"
                        @change="handleSearch"
                    />
                </div>

                <div class="py-2 px-4 bg-white shadow sm:rounded-lg w-full">
                    <div
                        class="flex justify-between sm:rounded-lg px-1 py-1 mb-2"
                    >
                        <InputListSearch
                            v-model="search"
                            :cleanButton="true"
                            placeholder="Buscar por staff, usuario o tipo"
                            @search="handleSearch"
                        />
                        <ClasicButton
                            @click="$inertia.visit(route('timesheets.create'))"
                            variant="primary"
                            >Nuevo Timesheet</ClasicButton
                        >
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
                                                    Calendario
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    Staff
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    Tipo
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    Día Entrada
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    Día Salida
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase"
                                                >
                                                    Horas
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
                                                v-for="timesheet in data"
                                                :key="timesheet.id"
                                            >
                                                <td
                                                    class="max-w-[200px] px-4 py-4 text-sm font-medium text-gray-800"
                                                >
                                                    {{ timesheet.calendar }}
                                                </td>
                                                <td
                                                    class="max-w-[200px] px-4 py-4 text-sm text-gray-800"
                                                >
                                                    {{
                                                        timesheet.staff?.profile
                                                            ?.full_name ||
                                                        timesheet.staff?.name ||
                                                        'Sin staff'
                                                    }}
                                                </td>
                                                <td
                                                    class="px-4 py-4 text-sm text-gray-800"
                                                >
                                                    {{ timesheet.type }}
                                                </td>
                                                <td
                                                    class="px-4 py-4 text-sm text-gray-800"
                                                >
                                                    {{ timesheet.day_in }}
                                                </td>
                                                <td
                                                    class="px-4 py-4 text-sm text-gray-800"
                                                >
                                                    {{ timesheet.day_out }}
                                                </td>
                                                <td
                                                    class="px-4 py-4 text-sm text-gray-800"
                                                >
                                                    {{ timesheet.hours }}
                                                </td>
                                                <td
                                                    class="flex items-center justify-end px-5 py-4 whitespace-nowrap text-sm font-medium"
                                                >
                                                    <Edit
                                                        @click="
                                                            $inertia.visit(
                                                                route(
                                                                    'timesheets.edit',
                                                                    {
                                                                        id: timesheet.id,
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
                                    <Pagination :pagination="timesheets" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <ModalDelete
            :show="showDeleteModal"
            title="Eliminar Timesheet"
            description="¿Está seguro que desea eliminar este timesheet? Esta acción no se puede deshacer."
            :item-type="itemType"
            :item-id="timesheetToDelete"
            :item-details="itemDetails"
            @close="showDeleteModal = false"
            @confirm="confirmDelete"
        />
    </AppLayout>
</template>
