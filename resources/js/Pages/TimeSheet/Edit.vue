<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import FormEdit from '@/Components/TimeSheets/FormEdit.vue';

    defineProps({
        data: {
            type: Object,
            required: true,
        },
    });

    const handleCancelled = () => {
        window.history.back();
    };
</script>

<template>
    <AppLayout title="Editar Asistencia">
        <section class="mx-auto px-1 sm:px-6 lg:px-4 py-1 w-full max-w-7xl">
            <div class="flex md:flex-col flex-col gap-1 mt-2">
                <FormEdit :data="data" @cancelled="handleCancelled" />
            </div>
        </section>
    </AppLayout>
</template>
