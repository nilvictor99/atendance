<script setup>
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import InputLabelClassic from '../Inputs/InputLabelClassic.vue';
    import InputError from '../Inputs/InputError.vue';
    import TextareaClassic from '../Inputs/InputTexareaClasic.vue';
    import SelectClassic from '../Selects/SelectClassic.vue';
    import InputPasswordGenerate from '../Inputs/InputPasswordGenerate.vue';

    const props = defineProps({
        mode: {
            type: String,
            default: 'create',
        },
        initialData: {
            type: Object,
            default: () => ({}),
        },
    });

    const emit = defineEmits(['submitted', 'cancelled']);

    const name = ref(props.initialData.name || '');
    const username = ref(props.initialData.username || '');
    const password = ref(props.initialData.password || '');
    const url = ref(props.initialData.url || '');
    const notes = ref(props.initialData.notes || '');
    const type = ref(props.initialData.type || 'private');
    const active = ref(
        props.initialData.active !== undefined ? props.initialData.active : true
    );
    const isSubmitting = ref(false);
    const errors = ref({});

    const submitForm = async () => {
        isSubmitting.value = true;
        errors.value = {};

        const formData = {
            name: name.value,
            username: username.value,
            password: password.value,
            url: url.value,
            notes: notes.value,
            type: type.value,
            active: active.value,
        };

        try {
            const url =
                props.mode === 'edit'
                    ? `/password-vault/update/${props.initialData.id}`
                    : '/password-vault/store';
            const method = props.mode === 'edit' ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(formData),
            });

            const result = await response.json();

            if (response.ok) {
                emit('submitted', result);
                router.visit('/password-vault/list');
            } else {
                errors.value = result.errors || {
                    general: 'Error al guardar.',
                };
            }
        } catch (error) {
            console.error('Error:', error);
            errors.value = { general: 'Error de conexión.' };
        } finally {
            isSubmitting.value = false;
        }
    };

    // Cancelar
    const cancel = () => {
        emit('cancelled');
    };

    watch(
        () => props.initialData,
        newData => {
            if (newData) {
                name.value = newData.name || '';
                username.value = newData.username || '';
                password.value = newData.password || '';
                url.value = newData.url || '';
                notes.value = newData.notes || '';
                type.value = newData.type || 'private';
                active.value =
                    newData.active !== undefined ? newData.active : true;
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
            {{ mode === 'edit' ? 'Editar Contraseña' : 'Crear Contraseña' }}
        </h2>

        <div v-if="errors.general" class="mb-4 text-red-600">
            {{ errors.general }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <InputLabelClassic
                    v-model="name"
                    label="Nombre"
                    type="text"
                    theme="gray"
                    :required="true"
                    name="name"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Ingresa el nombre"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.initialData.name || ''"
                />
                <InputError
                    class="mt-1"
                    :message="errors.name"
                    theme="dark"
                    color="white"
                />
            </div>
            <div>
                <InputLabelClassic
                    v-model="username"
                    label="Usuario"
                    type="text"
                    theme="gray"
                    name="username"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Ingresa el usuario"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.initialData.username || ''"
                />
                <InputError
                    class="mt-1"
                    :message="errors.username"
                    theme="dark"
                    color="white"
                />
            </div>
            <div>
                <InputPasswordGenerate
                    v-model="password"
                    :generateRoute="route('generate-password')"
                    label="Contraseña"
                    placeholder="Ingresa la contraseña"
                    theme="gray"
                    :initialValue="props.initialData.password || ''"
                    :disabled="false"
                />
                <InputError
                    class="mt-1"
                    :message="errors.password"
                    theme="dark"
                    color="white"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <InputLabelClassic
                    v-model="url"
                    label="URL"
                    type="url"
                    theme="gray"
                    name="url"
                    :autofocus="false"
                    :disabled="false"
                    placeholder="Ingresa la URL"
                    :clearOnFocus="false"
                    :calculateOnInput="false"
                    :defaultValue="props.initialData.url || ''"
                />
                <InputError
                    class="mt-1"
                    :message="errors.url"
                    theme="dark"
                    color="white"
                />
            </div>
            <div>
                <div>
                    <SelectClassic
                        v-model="type"
                        :options="[
                            { value: 'private', label: 'Privado' },
                            { value: 'public', label: 'Público' },
                        ]"
                        label="Tipo *"
                        theme="gray"
                        :disabled="false"
                        placeholder="Seleccione un tipo"
                        :multiple="false"
                        :CleanButton="true"
                    />
                    <InputError
                        class="mt-1"
                        :message="errors.type"
                        theme="dark"
                        color="white"
                    />
                </div>
            </div>
        </div>

        <div class="mt-4">
            <TextareaClassic
                v-model="notes"
                label="Notas"
                theme="gray"
                :font-weight="'semibold'"
                :rows="4"
                placeholder="Ingresa las notas"
                :defaultValue="props.initialData.notes || ''"
            />
        </div>
        <div class="mt-6 flex justify-end space-x-4">
            <ClasicButton type="button" variant="gray" @click="cancel">
                Cancelar
            </ClasicButton>
            <ClasicButton
                type="submit"
                variant="gray"
                :loading="isSubmitting"
                :disabled="isSubmitting"
            >
                {{
                    isSubmitting
                        ? 'Guardando...'
                        : mode === 'edit'
                          ? 'Actualizar'
                          : 'Crear'
                }}
            </ClasicButton>
        </div>
    </form>
</template>
