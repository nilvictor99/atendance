<script setup>
    import Edit from '@/Components/Icons/Edit.vue';

    defineEmits(['click']);
</script>

<template>
    <section class="tooltip tooltip-warning" data-tip="Editar">
        <button
            @click="$emit('click')"
            class="btn btn-link btn-lg p-1 hover:bg-gray-100 rounded-md inline-flex items-center gap-x-4 font-bold rounded-lg border border-transparent text-orange-500 hover:text-orange-600 focus:outline-none text-center"
            type="button"
            aria-label="Editar"
            title="Editar"
        >
            <Edit />
        </button>
    </section>
</template>
