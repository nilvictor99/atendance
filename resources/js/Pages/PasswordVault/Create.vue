<script setup>
    import Form from '@/Components/PasswordVaults/Form.vue';
    import AppLayout from '@/Layouts/AppLayout.vue';

    const handleSubmitted = data => {
        console.log('Contraseña creada:', data);
    };

    const handleCancelled = () => {
        window.history.back();
    };
</script>

<template>
    <AppLayout title="Crear Contraseña">
        <section class="mx-auto px-1 sm:px-6 lg:px-4 py-1 w-full max-w-7xl">
            <div class="flex md:flex-col flex-col gap-1 mt-2">
                <Form
                    mode="create"
                    @submitted="handleSubmitted"
                    @cancelled="handleCancelled"
                />
            </div>
        </section>
    </AppLayout>
</template>
