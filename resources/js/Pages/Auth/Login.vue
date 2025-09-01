<script setup>
    import { Head, useForm } from '@inertiajs/vue3';
    import CardCustomLogin from '@/Components/Cards/CardCustomLogin.vue';
    import AuthenticationCardLogo from '@/Components/Cards/AuthenticationCardLogo.vue';
    import InputError from '@/Components/Inputs/InputError.vue';
    import Checkbox from '@/Components/Inputs/Checkbox.vue';
    import TextInput from '@/Components/Inputs/TextInput.vue';
    import InputRevealablePassword from '@/Components/Inputs/InputRevealablePassword.vue';
    import NativeButton from '@/Components/Buttons/NativeButton.vue';
    import ClassicLabel from '@/Components/Labels/ClassicLabel.vue';

    defineProps({
        canResetPassword: Boolean,
        status: String,
    });

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = () => {
        form.transform(data => ({
            ...data,
            remember: form.remember ? 'on' : '',
        })).post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    };
</script>

<template>
    <Head title="Log in" />

    <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap"
        rel="stylesheet"
    />

    <div
        class="h-screen bg-cover bg-center bg-no-repeat overflow-hidden flex items-center justify-center relative"
        style="background-image: url('/System/Samples/background.webp')"
    >
        <CardCustomLogin>
            <template #logo>
                <AuthenticationCardLogo />
            </template>
            <div
                class="mt-2 mb-2 text-white text-center p-2 border-t-[5px] border-b-[5px] border-white/20 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-pulse font-bold text-xl tracking-wider"
            >
                <span class="animate-fade-in">Iniciar Sesión</span>
            </div>
            <form @submit.prevent="submit">
                <div>
                    <ClassicLabel :weight="'bold'" :size="'lg'" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="correo electrónico"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <ClassicLabel
                        :weight="'bold'"
                        :size="'lg'"
                        value="Password"
                    />
                    <InputRevealablePassword
                        id="password"
                        v-model="form.password"
                        class="mt-1 block w-full"
                        placeholder="contraseña"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mb-5 mt-4">
                    <label class="flex items-center">
                        <Checkbox
                            v-model:checked="form.remember"
                            name="remember"
                        />
                        <span class="ms-2 text-sm text-gray-100"
                            >Mantener la Sesion</span
                        >
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <NativeButton
                        :class="{ 'opacity-25': form.processing }"
                        :theme="'gray'"
                        :disabled="form.processing"
                    >
                        <span
                            v-if="form.processing"
                            class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-white rounded-full"
                            role="status"
                            aria-label="loading"
                        >
                            <span class="sr-only">Loading...</span>
                        </span>
                        <span v-if="!form.processing">Iniciar Sesion</span>
                    </NativeButton>
                </div>
            </form>
        </CardCustomLogin>
        <span class="square square-tl"></span>
        <span class="square square-tr"></span>
        <span class="square square-bl"></span>
        <span class="square square-br"></span>
        <span class="star star1"></span>
        <span class="star star2"></span>
    </div>
</template>

<style scoped>
    .square {
        height: 100vh;
        width: 50vw;
        display: table;
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
    }

    .square.square-tl {
        top: -80%;
        left: -10%;
        animation: bounce 6s infinite ease-in-out;
        background: rgb(34, 34, 34, 0.1);
        z-index: 50;
    }

    .square.square-tr {
        top: 0%;
        right: -30%;
        animation: bounce 5s infinite ease-in-out;
    }

    .square.square-bl {
        bottom: -70%;
        left: -15%;
        animation: bounce 4s infinite ease-in-out;
    }

    .square.square-br {
        bottom: 0%;
        right: -40%;
        animation: bounce 3s infinite ease-in-out;
        background: rgb(34, 34, 34, 0.1);
    }

    @keyframes bounce {
        0% {
            transform: translateY(0px) rotate(45deg);
        }
        50% {
            transform: translateY(20px) rotate(45deg);
            border-radius: 50px;
        }
        100% {
            transform: translateY(0px) rotate(45deg);
        }
    }

    .star {
        height: 50px;
        width: 50px;
        display: table;
        position: absolute;
        box-shadow: 0 0 5px 0 rgba(34, 34, 34, 0.5);
        transition: 0.5s;
    }

    .star1 {
        bottom: -10%;
        left: -30%;
        transform: rotate(-30deg);
        animation: sweep 4s infinite;
        background: rgba(34, 34, 34, 0.5);
    }

    .star2 {
        bottom: -30%;
        left: -10%;
        transform: rotate(-30deg);
        animation: sweep 3s infinite;
        background: rgb(255, 255, 255, 0.5);
    }

    @keyframes sweep {
        100% {
            bottom: 120%;
            left: 120%;
            transform: rotate(360deg);
        }
    }
</style>
