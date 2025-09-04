<script setup>
    import { watch, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import InputError from '../Inputs/InputError.vue';
    import InputTimeClassic from '../Inputs/InputTimeClassic.vue';
    import InputDateClasic from '../Inputs/InputDateClasic.vue';
    import ClassicLabel from '../Labels/ClassicLabel.vue';

    const props = defineProps({
        data: {
            type: Object,
            required: true,
        },
    });

    const emit = defineEmits(['submitted', 'cancelled']);

    // Extract date from day_in/day_out or calendar
    const extractDate = data => {
        if (data.day_in) return data.day_in.split(' ')[0];
        if (data.calendar) return data.calendar;
        return '';
    };

    const extractTime = datetime => {
        if (!datetime) return '';
        const time = datetime.split(' ')[1];
        return time ? time.substring(0, 5) : ''; // Take only HH:MM
    };

    const form = useForm({
        calendar: extractDate(props.data),
        day_in: extractTime(props.data.day_in),
        day_out: extractTime(props.data.day_out),
    });

    const staffName = computed(() => props.data?.staff?.name || '');
    const staffType = computed(() => props.data?.type || '');
    const calendarYear = computed(() => props.data?.calendar || '');

    const submitForm = () => {
        form.put(route('timesheets.update', props.data.id), {
            onSuccess: () => {
                emit('submitted');
            },
        });
    };

    const cancel = () => {
        emit('cancelled');
    };

    watch(
        () => props.data,
        newData => {
            if (newData) {
                form.calendar = extractDate(newData);
                form.day_in = extractTime(newData.day_in);
                form.day_out = extractTime(newData.day_out);
            }
        },
        { deep: true }
    );
</script>

<template>
    <form
        @submit.prevent="submitForm"
        class="bg-white p-6 rounded-lg shadow-md w-full mx-auto"
    >
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Editar Asistencia</h2>
            <div class="flex space-x-2 items-center">
                <span
                    v-if="staffName"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-300"
                >
                    <strong class="mr-1">Staff:</strong> {{ staffName }}
                </span>
                <span
                    v-if="staffType"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-300"
                >
                    <strong class="mr-1">Tipo:</strong> {{ staffType }}
                </span>
                <span
                    v-if="calendarYear"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-300"
                >
                    <strong class="mr-1">Calendario:</strong> {{ calendarYear }}
                </span>
            </div>
        </div>

        <div v-if="form.errors.general" class="mb-4 text-red-600">
            {{ form.errors.general }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <ClassicLabel
                    for="calendar"
                    class="mb-1"
                    value="Fecha de Asistencia"
                />
                <InputDateClasic
                    v-model="form.calendar"
                    label="Fecha de Asistencia"
                    theme="gray"
                    :required="true"
                    name="calendar"
                    placeholder="Selecciona la fecha"
                    :disabled="false"
                    :minDate="null"
                    :maxDate="null"
                    :allowInput="true"
                    :yearRange="10"
                />
                <InputError class="mt-1" :message="form.errors.calendar" />
            </div>
            <div>
                <InputTimeClassic
                    v-model="form.day_in"
                    label="Hora de Inicio"
                    name="day_in"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Selecciona la hora de inicio"
                    theme="gray"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="form.day_in"
                />
                <InputError class="mt-1" :message="form.errors.day_in" />
            </div>
            <div>
                <InputTimeClassic
                    v-model="form.day_out"
                    label="Hora de Fin"
                    name="day_out"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Selecciona la hora de fin"
                    theme="gray"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="form.day_out"
                />
                <InputError class="mt-1" :message="form.errors.day_out" />
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-4">
            <ClasicButton type="button" variant="gray" @click="cancel">
                Cancelar
            </ClasicButton>
            <ClasicButton
                type="submit"
                variant="gray"
                :loading="form.processing"
                :disabled="form.processing"
            >
                {{ form.processing ? 'Guardando...' : 'Actualizar' }}
            </ClasicButton>
        </div>
    </form>
</template>
