<script setup>
    import { ref, watch } from 'vue';
    import {
        QrcodeStream,
        QrcodeCapture,
        QrcodeDropZone,
        setZXingModuleOverrides,
    } from 'vue-qrcode-reader';
    import XIcon from '../Icons/XIcon.vue';
    import ClasicButton from '../Buttons/ClasicButton.vue';
    import Modal from '../Modals/Modal.vue';
    import InputError from '../Inputs/InputError.vue';
    import LoadPoints from '@/Components/Icons/LoadPoints.vue';
    import InputSelectClasic from '../Inputs/InputSelectClasic.vue';

    // Configurar overrides para el detector (opcional, para personalizar)
    setZXingModuleOverrides({
        locateFile: file =>
            `https://cdn.jsdelivr.net/npm/@zxing/library@0.19.3/${file}`,
    });

    const props = defineProps({
        show: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['close']);

    const activeTab = ref('stream'); // Pestaña activa: 'stream', 'capture', 'dropzone'
    const scanning = ref(false);
    const error = ref('');
    const cameraError = ref('');
    const loading = ref(false);
    const selectedCamera = ref('work');
    const scannedResult = ref('');
    const detectedCodes = ref([]);
    const torchEnabled = ref(false); // Para torch en Stream
    const formats = ref(['qr_code']); // Formatos de barcode

    watch(
        () => props.show,
        newShow => {
            if (!newShow) {
                stopScanning();
                scannedResult.value = '';
                detectedCodes.value = [];
            }
        }
    );

    const onDetect = (detectedCodesArray, mode = 'stream') => {
        if (detectedCodesArray.length > 0) {
            scannedResult.value = detectedCodesArray[0].rawValue;
            detectedCodes.value = detectedCodesArray;
            if (mode === 'capture' || mode === 'dropzone') {
                // Para Capture y DropZone, no detener
            }
        } else {
            detectedCodes.value = [];
        }
    };

    const onError = (err, mode) => {
        if (mode === 'stream') cameraError.value = err.message;
        else error.value = err.message;
        loading.value = false;
        emit('error', err);
    };

    const startScanning = () => {
        if (!selectedCamera.value) {
            error.value = 'Selecciona un tipo.';
            return;
        }
        scanning.value = true;
        error.value = '';
        cameraError.value = '';
        loading.value = true;
        scannedResult.value = '';
        detectedCodes.value = [];
    };

    const stopScanning = () => {
        scanning.value = false;
        loading.value = false;
        detectedCodes.value = [];
    };

    const clearResult = () => {
        scannedResult.value = '';
        detectedCodes.value = [];
    };

    const toggleTorch = () => {
        torchEnabled.value = !torchEnabled.value;
    };

    const closeModal = () => {
        stopScanning();
        scannedResult.value = '';
        detectedCodes.value = [];
        emit('close');
    };
</script>

<template>
    <Modal :show="props.show" @close="closeModal" maxWidth="lg">
        <div class="p-6 sm:p-6 lg:p-8">
            <div class="flex justify-between items-center mb-4">
                <h2
                    class="text-lg sm:text-xl lg:text-2xl font-semibold text-gray-800"
                >
                    Prueba Completa de vue-qrcode-reader
                </h2>
                <button
                    @click="closeModal"
                    class="text-gray-500 hover:text-gray-700 transition-colors"
                >
                    <XIcon class="h-6 w-6 sm:h-8 sm:w-8" />
                </button>
            </div>

            <!-- Pestañas para alternar modos -->
            <div class="flex border-b border-gray-200 mb-4">
                <button
                    @click="activeTab = 'stream'"
                    :class="
                        activeTab === 'stream'
                            ? 'border-blue-500 text-blue-600'
                            : 'border-transparent text-gray-500'
                    "
                    class="py-2 px-4 border-b-2 font-medium text-sm"
                >
                    Stream (Cámara)
                </button>
                <button
                    @click="activeTab = 'capture'"
                    :class="
                        activeTab === 'capture'
                            ? 'border-blue-500 text-blue-600'
                            : 'border-transparent text-gray-500'
                    "
                    class="py-2 px-4 border-b-2 font-medium text-sm"
                >
                    Capture (Archivo)
                </button>
                <button
                    @click="activeTab = 'dropzone'"
                    :class="
                        activeTab === 'dropzone'
                            ? 'border-blue-500 text-blue-600'
                            : 'border-transparent text-gray-500'
                    "
                    class="py-2 px-4 border-b-2 font-medium text-sm"
                >
                    DropZone (Arrastrar)
                </button>
            </div>

            <div class="qr-scanner">
                <!-- Controles comunes -->
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
                        <InputSelectClasic
                            v-model="formats"
                            :options="[
                                { id: 'qr_code', text: 'QR Code' },
                                { id: 'code_128', text: 'Code 128' },
                                { id: 'ean_13', text: 'EAN-13' },
                            ]"
                            placeholder="Formatos"
                            theme="gray"
                            multiple
                            class="w-full sm:w-auto"
                        />
                        <ClasicButton
                            @click="startScanning"
                            :disabled="scanning"
                            variant="blue"
                            class="w-full sm:w-auto flex justify-center"
                        >
                            <LoadPoints v-if="scanning" :variant="'white'" />
                            <span v-else>Iniciar</span>
                        </ClasicButton>
                    </div>
                    <ClasicButton
                        v-if="scanning && activeTab === 'stream'"
                        @click="stopScanning"
                        variant="danger"
                        class="w-full sm:w-auto flex justify-center"
                    >
                        Detener
                    </ClasicButton>
                    <ClasicButton
                        v-if="activeTab === 'stream'"
                        @click="toggleTorch"
                        variant="gray"
                        class="w-full sm:w-auto flex justify-center"
                    >
                        Torch: {{ torchEnabled ? 'On' : 'Off' }}
                    </ClasicButton>
                </div>

                <!-- Área para mostrar el resultado -->
                <div
                    v-if="scannedResult"
                    class="mt-4 p-4 bg-gray-100 rounded-lg"
                >
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">
                        Resultado:
                    </h3>
                    <p class="text-sm text-gray-600 break-all">
                        {{ scannedResult }}
                    </p>
                    <ClasicButton
                        @click="clearResult"
                        variant="gray"
                        class="mt-2 text-xs"
                        >Limpiar</ClasicButton
                    >
                </div>

                <!-- Modo Stream -->
                <div
                    v-if="activeTab === 'stream' && scanning"
                    class="mt-4 relative"
                >
                    <div
                        v-if="loading"
                        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg z-10"
                    >
                        <LoadPoints />
                    </div>
                    <svg
                        v-if="detectedCodes.length > 0"
                        class="absolute inset-0 w-full h-full pointer-events-none z-20"
                        viewBox="0 0 640 480"
                    >
                        <g v-for="(code, index) in detectedCodes" :key="index">
                            <polygon
                                :points="
                                    code.cornerPoints
                                        .map(p => `${p.x},${p.y}`)
                                        .join(' ')
                                "
                                fill="none"
                                stroke="red"
                                stroke-width="4"
                                opacity="0.8"
                            />
                            <text
                                :x="
                                    code.boundingBox.x +
                                    code.boundingBox.width / 2
                                "
                                :y="
                                    code.boundingBox.y +
                                    code.boundingBox.height / 2
                                "
                                text-anchor="middle"
                                dominant-baseline="middle"
                                fill="white"
                                font-size="16"
                                font-weight="bold"
                                stroke="black"
                                stroke-width="2"
                            >
                                {{ code.rawValue }}
                            </text>
                        </g>
                    </svg>
                    <qrcode-stream
                        @detect="codes => onDetect(codes, 'stream')"
                        @error="err => onError(err, 'stream')"
                        :formats="formats"
                        :torch="torchEnabled"
                        class="rounded-lg border border-gray-300 shadow-md w-full max-w-xs sm:max-w-md lg:max-w-lg mx-auto"
                    />
                    <InputError class="mt-2 text-center text-sm sm:text-base">{{
                        $t(cameraError)
                    }}</InputError>
                </div>

                <!-- Modo Capture -->
                <div v-if="activeTab === 'capture'" class="mt-4">
                    <qrcode-capture
                        @detect="codes => onDetect(codes, 'capture')"
                        @error="err => onError(err, 'capture')"
                        :formats="formats"
                        class="w-full"
                    />
                    <p class="text-sm text-gray-500 mt-2">
                        Sube un archivo para escanear.
                    </p>
                </div>

                <!-- Modo DropZone -->
                <div v-if="activeTab === 'dropzone'" class="mt-4">
                    <qrcode-drop-zone
                        @detect="codes => onDetect(codes, 'dropzone')"
                        @error="err => onError(err, 'dropzone')"
                        :formats="formats"
                        class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center"
                    >
                        <p class="text-gray-500">
                            Arrastra una imagen aquí para escanear.
                        </p>
                    </qrcode-drop-zone>
                </div>

                <InputError class="mt-2 text-center text-sm sm:text-base">{{
                    $t(error)
                }}</InputError>
            </div>
        </div>
    </Modal>
</template>
