<script setup>
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { QrcodeStream } from 'vue-qrcode-reader';
    import XIcon from '../Icons/XIcon.vue';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import Modal from '../Modals/Modal.vue';
    import InputError from '../Inputs/InputError.vue';

    const props = defineProps({
        show: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['close']);

    const scanning = ref(false);
    const error = ref('');
    const cameraError = ref('');
    const loading = ref(false);

    watch(
        () => props.show,
        newShow => {
            if (!newShow) {
                stopScanning();
            }
        }
    );

    const onDetect = detectedCodes => {
        if (detectedCodes.length > 0) {
            const result = detectedCodes[0].rawValue;
            scanning.value = false;
            loading.value = false;
            router.post(
                route('scan'),
                { qr_data: result },
                {
                    onSuccess: () => {
                        emit('close');
                        router.reload();
                    },
                }
            );
        }
    };

    const onError = err => {
        cameraError.value = err.message;
        loading.value = false;
        emit('error', err);
    };

    const startScanning = () => {
        scanning.value = true;
        error.value = '';
        cameraError.value = '';
        loading.value = true;
    };

    const stopScanning = () => {
        scanning.value = false;
        loading.value = false;
    };

    const closeModal = () => {
        stopScanning();
        emit('close');
    };
</script>

<template>
    <Modal :show="props.show" @close="closeModal" maxWidth="md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Escanear QR de Asistencia</h2>
                <button
                    @click="closeModal"
                    class="text-gray-500 hover:text-gray-700"
                >
                    <XIcon class="h-5 w-5" />
                </button>
            </div>

            <div class="qr-scanner">
                <div class="flex gap-2 p-4 justify-center mb-4">
                    <ClasicButton
                        @click="startScanning"
                        :disabled="scanning"
                        variant="blue"
                    >
                        {{ scanning ? 'Escaneando...' : 'Iniciar Escaneo' }}
                    </ClasicButton>
                    <ClasicButton
                        v-if="scanning"
                        @click="stopScanning"
                        variant="danger"
                    >
                        Detener Escaneo
                    </ClasicButton>
                </div>
                <div v-if="scanning" class="mt-4 relative">
                    <div
                        v-if="loading"
                        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded"
                    >
                        <p class="text-white">Cargando cámara...</p>
                    </div>
                    <qrcode-stream
                        @detect="onDetect"
                        @error="onError"
                        :formats="['qr_code']"
                        :torch="false"
                        class="rounded border"
                    />
                    <InputError class="mt-1">
                        {{ $t(cameraError) }}
                    </InputError>
                </div>
                <InputError class="mt-1">
                    {{ $t(error) }}
                </InputError>
            </div>
        </div>
    </Modal>
</template>
