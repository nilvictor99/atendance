<script setup>
    import Modal from '@/Components/Modals/Modal.vue';
    import XIcon from '../Icons/XIcon.vue';

    defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        qrCode: {
            type: String,
            default: '',
        },
    });

    const emit = defineEmits(['close']);

    const closeModal = () => {
        emit('close');
    };
</script>

<template>
    <Modal :show="show" @close="closeModal" maxWidth="md">
        <div class="p-2 flex justify-between items-center border-b">
            <h2 class="text-lg font-semibold">Código QR</h2>
            <button
                class="top-4 right-4 text-gray-500 hover:text-gray-700 focus:outline-none"
                @click="closeModal"
            >
                <XIcon class="h-8 w-8" />
            </button>
        </div>
        <div class="p-6">
            <img :src="qrCode" alt="QR Code" class="mx-auto mb-4" />
        </div>
    </Modal>
</template>
