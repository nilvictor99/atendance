<script setup>
    import { defineEmits } from 'vue';
    import Backspace from '../Icons/Backspace.vue';

    defineProps({
        buttonClass: {
            type: String,
            default:
                'bg-[#3c1415] w-16 h-[42px] absolute flex items-center justify-center top-0 right-0 p-2 text-white rounded-[0px_5px_5px_0px] hover:bg-[#5e0f0b] focus:outline-none focus:ring-none focus:ring-[#3c1415]-400',
        },
        isDeleting: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['click']);

    function onClick() {
        emit('click');
    }
</script>
<template>
    <button type="button" @click="onClick" :class="buttonClass">
        <Backspace />
    </button>
</template>
