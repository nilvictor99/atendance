<script setup>
    import Form from '@/Components/PasswordVaults/Form.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';

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
    <AppLayout title="Editar Contraseña">
        <section class="mx-auto px-1 sm:px-6 lg:px-4 py-1 w-full max-w-7xl">
            <div class="flex md:flex-col flex-col gap-1 mt-2">
                <Form mode="edit" :data="data" @cancelled="handleCancelled" />
            </div>
        </section>
    </AppLayout>
</template>
