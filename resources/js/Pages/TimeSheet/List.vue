<script setup>
    import Pagination from '@/Components/Sections/SectionPagination.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import ClasicButton from '@/Components/Buttons/ClasicButton.vue';
    import { ref } from 'vue';
    import { router, usePoll } from '@inertiajs/vue3';
    import Edit from '@/Components/Buttons/Edit.vue';
    import InputListSearch from '@/Components/Inputs/InputListSearch.vue';
    import DateRangeFilter from '@/Components/Sections/SectionDateRangeFilter.vue';
    import InputSelectClasic from '@/Components/Inputs/InputSelectClasic.vue';
    import ModalQrGenerate from '@/Components/TimeSheets/ModalQrGenerate.vue';
    import ModalQrScanner from '@/Components/TimeSheets/ModalQrScanner.vue';

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

    const showQrModal = ref(false);
    const qrCode = ref('');
    const showScanner = ref(false);

    const generateQr = async () => {
        const { qrCode: code } = await fetch(route('generate'), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        }).then(r => r.json());
        qrCode.value = code;
        showQrModal.value = true;
    };

    const search = ref(props.search);
    const dateRange = ref(props.dateRange || { start: '', end: '' });
    const staffId = ref(props.staffId ?? '');
    const selectedStaffText =
        props.staff.find(
            staff => staff.id.toString() === props.staffId?.toString()
        )?.name || '';
    const staffOptions = props.staff.map(staff => ({
        id: staff.id,
        text: staff.name || 'Sin nombre',
    }));

    usePoll(5000, { only: ['timesheets'] });
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
    const formatWorkDate = (dayIn, dayOut) => {
        if (!dayIn) return '';
        const dateIn = new Date(dayIn).toISOString().split('T')[0];
        if (!dayOut) {
            return dateIn;
        }
        const dateOut = new Date(dayOut).toISOString().split('T')[0];
        return dateIn === dateOut ? dateIn : `${dateIn} - ${dateOut}`;
    };
    const formatTime = datetime => {
        if (!datetime) return '';
        const time = datetime.split(' ')[1];
        return time ? time.substring(0, 5) : '';
    };
</script>

<template>
    <AppLayout title="Timesheets">
        <section class="mx-auto px-4 sm:px-6 lg:px-8 py-2 w-full max-w-7xl">
            <div class="flex flex-col gap-4 mt-1">
                <!-- Filters Section -->
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end"
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
                            label="Colaboradores"
                            placeholder="Colaborador"
                            :disabled="false"
                            theme="gray"
                            :bold="true"
                            :CleanButton="true"
                            @change="handleSearch"
                        />
                    </div>
                </div>

                <div class="bg-white shadow-lg rounded-xl p-6">
                    <div
                        class="flex flex-col sm:flex-row gap-4 mb-6 items-center"
                    >
                        <InputListSearch
                            v-model="search"
                            :cleanButton="true"
                            placeholder="Buscar Nombre, Usuario o Url"
                            @search="handleSearch"
                            class="flex-1"
                        />
                        <ClasicButton
                            :roles="['super usuario', 'super_admin', 'Staff']"
                            :permissions="[]"
                            @click="generateQr"
                            class="flex-1 sm:flex-none w-full sm:w-auto flex justify-center"
                        >
                            {{ $t('Create Timesheet') }}
                        </ClasicButton>
                        <ClasicButton
                            :roles="['super usuario', 'super_admin', 'Staff']"
                            :permissions="['create_timesheet']"
                            @click="showScanner = true"
                            variant="success"
                            class="flex-1 sm:flex-none w-full sm:w-auto flex justify-center"
                        >
                            {{ $t('Scan QR') }}
                        </ClasicButton>
                    </div>

                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow-sm"
                        >
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Calendario
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Fecha
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Colaborador
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Tipo
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Hora Ingreso
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Hora Salida
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Horas
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="timesheet in timesheets.data"
                                    :key="timesheet.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-4 text-sm font-medium text-gray-900 truncate max-w-xs"
                                    >
                                        {{
                                            timesheet.calendar ||
                                            'Sin calendario'
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{
                                            formatWorkDate(
                                                timesheet.day_in,
                                                timesheet.day_out
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{
                                            timesheet.staff?.name || 'Sin staff'
                                        }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            :class="
                                                timesheet.type === 'work'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-blue-100 text-blue-800'
                                            "
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                        >
                                            {{ $t(timesheet.type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            :class="
                                                timesheet.type === 'work'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-blue-100 text-blue-800'
                                            "
                                            class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold"
                                        >
                                            {{ formatTime(timesheet.day_in) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            :class="
                                                timesheet.type === 'work'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-blue-100 text-blue-800'
                                            "
                                            class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold"
                                        >
                                            {{ formatTime(timesheet.day_out) }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-sm text-gray-900"
                                    >
                                        {{ timesheet.hours }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <Edit
                                            @click="
                                                $inertia.visit(
                                                    route('timesheets.edit', {
                                                        id: timesheet.id,
                                                    })
                                                )
                                            "
                                            class="text-indigo-600 hover:text-indigo-900 cursor-pointer"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        <Pagination :pagination="timesheets" />
                    </div>
                </div>
            </div>
        </section>

        <ModalQrGenerate
            :show="showQrModal"
            :qr-code="qrCode"
            @close="showQrModal = false"
        />
        <ModalQrScanner :show="showScanner" @close="showScanner = false" />
    </AppLayout>
</template>
