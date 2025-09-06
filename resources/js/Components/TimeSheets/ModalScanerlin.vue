<script setup>
    import { ref, watch } from 'vue';
    import { QrcodeStream } from 'vue-qrcode-reader';
    import XIcon from '../Icons/XIcon.vue';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import Modal from '../Modals/Modal.vue';
    import InputError from '../Inputs/InputError.vue';
    import LoadPoints from '@/Components/Icons/LoadPoints.vue';
    import InputSelectClasic from '../Inputs/InputSelectClasic.vue';

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
    const selectedCamera = ref('work');
    const scannedResult = ref(''); // Nueva variable para mostrar el resultado escaneado

    watch(
        () => props.show,
        newShow => {
            if (!newShow) {
                stopScanning();
                scannedResult.value = ''; // Limpiar resultado al cerrar
            }
        }
    );

    const onDetect = detectedCodes => {
        if (detectedCodes.length > 0) {
            const result = detectedCodes[0].rawValue;
            scannedResult.value = result; // Mostrar el resultado
            scanning.value = false; // Detener escaneo automáticamente
            loading.value = false;
        }
    };

    const onError = err => {
        cameraError.value = err.message;
        loading.value = false;
        emit('error', err);
    };

    const startScanning = () => {
        if (!selectedCamera.value) {
            error.value = 'Selecciona un tipo antes de escanear.';
            return;
        }
        scanning.value = true;
        error.value = '';
        cameraError.value = '';
        loading.value = true;
        scannedResult.value = ''; // Limpiar resultado anterior
    };

    const stopScanning = () => {
        scanning.value = false;
        loading.value = false;
    };

    const clearResult = () => {
        scannedResult.value = '';
    };

    const closeModal = () => {
        stopScanning();
        scannedResult.value = '';
        emit('close');
    };
</script>

<template>
    <Modal :show="props.show" @close="closeModal" maxWidth="md">
        <div class="p-6 sm:p-6 lg:p-8">
            <div class="flex justify-between items-center mb-4">
                <h2
                    class="text-lg sm:text-xl lg:text-2xl font-semibold text-gray-800"
                >
                    Escáner de Código QR (Vista Previa)
                </h2>
                <button
                    @click="closeModal"
                    class="text-gray-500 hover:text-gray-700 transition-colors"
                >
                    <XIcon class="h-6 w-6 sm:h-8 sm:w-8" />
                </button>
            </div>

            <div class="qr-scanner">
                <div
                    class="flex flex-col sm:flex-row gap-2 p-2 justify-center mb-4"
                >
                    <div
                        class="flex flex-col sm:flex-col gap-2 w-full sm:w-[200px]"
                    >
                        <InputSelectClasic
                            v-if="!scanning"
                            v-model="selectedCamera"
                            :options="[
                                { id: 'work', text: 'Trabajo' },
                                { id: 'break', text: 'Descanso' },
                            ]"
                            placeholder="Tipo"
                            theme="gray"
                            :required="true"
                            :CleanButton="true"
                            class="w-full sm:w-auto"
                        />
                        <ClasicButton
                            @click="startScanning"
                            :disabled="scanning"
                            variant="blue"
                            class="w-full sm:w-auto flex justify-center"
                        >
                            <LoadPoints v-if="scanning" :variant="'white'" />
                            <span v-else>Iniciar Escaneo</span>
                        </ClasicButton>
                    </div>
                    <ClasicButton
                        v-if="scanning"
                        @click="stopScanning"
                        variant="danger"
                        class="w-full sm:w-auto flex justify-center"
                    >
                        Detener Escaneo
                    </ClasicButton>
                </div>

                <!-- Área para mostrar el resultado escaneado -->
                <div
                    v-if="scannedResult"
                    class="mt-4 p-4 bg-gray-100 rounded-lg"
                >
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">
                        Resultado Escaneado:
                    </h3>
                    <p class="text-sm text-gray-600 break-all">
                        {{ scannedResult }}
                    </p>
                    <ClasicButton
                        @click="clearResult"
                        variant="gray"
                        class="mt-2 text-xs"
                    >
                        Limpiar
                    </ClasicButton>
                </div>

                <div v-if="scanning" class="mt-4 relative">
                    <div
                        v-if="loading"
                        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg z-10"
                    >
                        <LoadPoints />
                    </div>
                    <div
                        class="absolute inset-0 flex items-center justify-center pointer-events-none z-20"
                    >
                        <div
                            class="border-4 border-blue-500 rounded-lg w-64 h-64 sm:w-80 sm:h-80 lg:w-96 lg:h-96 opacity-70"
                        ></div>
                    </div>
                    <qrcode-stream
                        @detect="onDetect"
                        @error="onError"
                        :formats="['qr_code']"
                        :torch="false"
                        class="rounded-lg border border-gray-300 shadow-md w-full max-w-xs sm:max-w-md lg:max-w-lg mx-auto"
                    />
                    <InputError class="mt-2 text-center text-sm sm:text-base">
                        {{ $t(cameraError) }}
                    </InputError>
                </div>
                <InputError class="mt-2 text-center text-sm sm:text-base">
                    {{ $t(error) }}
                </InputError>
            </div>
        </div>
    </Modal>
</template>
