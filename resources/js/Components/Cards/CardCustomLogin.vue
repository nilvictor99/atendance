<template>
    <div
        class="bg-white/20 backdrop-blur-[20px] rounded-[12px] z-5 flex flex-col shadow-[0_0_5px_0_rgba(22,22,22,0.1)] min-w-[400px] p-5 animate-fade-in-up"
    >
        <div class="flex justify-center animate-bounce-in">
            <slot name="logo" />
        </div>
        <div class="animate-slide-up">
            <slot />
        </div>
    </div>
</template>

<style scoped>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounce-in {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        50% {
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out;
    }

    .animate-bounce-in {
        animation: bounce-in 0.8s ease-out;
    }

    .animate-slide-up {
        animation: slide-up 0.7s ease-out 0.3s both;
    }
</style>
