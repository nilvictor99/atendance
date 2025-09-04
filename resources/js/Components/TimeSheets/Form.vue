<script setup>
    import { watch } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import InputLabelClassic from '../Inputs/InputLabelClassic.vue';
    import InputError from '../Inputs/InputError.vue';
    import SelectClassic from '../Selects/SelectClassic.vue';

    const props = defineProps({
        mode: {
            type: String,
            default: 'create',
        },
        data: {
            type: Object,
            default: () => ({}),
        },
        staffOptions: {
            type: Array,
            default: () => [],
        },
        userOptions: {
            type: Array,
            default: () => [],
        },
    });

    const emit = defineEmits(['submitted', 'cancelled']);

    const form = useForm({
        calendar: props.data.calendar || '',
        staff_id: props.data.staff_id || '',
        user_id: props.data.user_id || '',
        type: props.data.type || '',
        day_in: props.data.day_in || '',
        day_out: props.data.day_out || '',
        hours: props.data.hours || '',
    });

    const submitForm = () => {
        if (props.mode === 'edit') {
            form.put(route('timesheets.update', props.data.id), {
                onSuccess: () => {
                    emit('submitted');
                },
            });
        } else {
            form.post(route('timesheets.store'), {
                onSuccess: () => {
                    emit('submitted');
                },
            });
        }
    };

    const cancel = () => {
        emit('cancelled');
    };

    watch(
        () => props.data,
        newData => {
            if (newData) {
                form.calendar = newData.calendar || '';
                form.staff_id = newData.staff_id || '';
                form.user_id = newData.user_id || '';
                form.type = newData.type || '';
                form.day_in = newData.day_in || '';
                form.day_out = newData.day_out || '';
                form.hours = newData.hours || '';
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
        <h2 class="text-2xl font-bold mb-6 text-gray-900">
            {{ mode === 'edit' ? 'Editar Asistencia' : 'Crear Asistencia' }}
        </h2>

        <div v-if="form.errors.general" class="mb-4 text-red-600">
            {{ form.errors.general }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <InputLabelClassic
                    v-model="form.calendar"
                    label="Calendario"
                    type="date"
                    theme="gray"
                    :required="true"
                    name="calendar"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Selecciona la fecha"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.calendar || ''"
                />
                <InputError class="mt-1" :message="form.errors.calendar" />
            </div>
            <div>
                <SelectClassic
                    v-model="form.staff_id"
                    :options="staffOptions"
                    label="Staff"
                    theme="gray"
                    :disabled="false"
                    placeholder="Selecciona el staff"
                    :multiple="false"
                    :CleanButton="true"
                />
                <InputError class="mt-1" :message="form.errors.staff_id" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <SelectClassic
                    v-model="form.user_id"
                    :options="userOptions"
                    label="Usuario"
                    theme="gray"
                    :disabled="false"
                    placeholder="Selecciona el usuario"
                    :multiple="false"
                    :CleanButton="true"
                />
                <InputError class="mt-1" :message="form.errors.user_id" />
            </div>
            <div>
                <InputLabelClassic
                    v-model="form.type"
                    label="Tipo"
                    type="text"
                    theme="gray"
                    name="type"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Ingresa el tipo"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.type || ''"
                />
                <InputError class="mt-1" :message="form.errors.type" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <InputLabelClassic
                    v-model="form.day_in"
                    label="Día de Entrada"
                    type="datetime-local"
                    theme="gray"
                    name="day_in"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Selecciona la fecha y hora de entrada"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.day_in || ''"
                />
                <InputError class="mt-1" :message="form.errors.day_in" />
            </div>
            <div>
                <InputLabelClassic
                    v-model="form.day_out"
                    label="Día de Salida"
                    type="datetime-local"
                    theme="gray"
                    name="day_out"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Selecciona la fecha y hora de salida"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.day_out || ''"
                />
                <InputError class="mt-1" :message="form.errors.day_out" />
            </div>
            <div>
                <InputLabelClassic
                    v-model="form.hours"
                    label="Horas"
                    type="number"
                    theme="gray"
                    name="hours"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Ingresa las horas"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.data.hours || ''"
                />
                <InputError class="mt-1" :message="form.errors.hours" />
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
                {{
                    form.processing
                        ? 'Guardando...'
                        : mode === 'edit'
                          ? 'Actualizar'
                          : 'Crear'
                }}
            </ClasicButton>
        </div>
    </form>
</template>
