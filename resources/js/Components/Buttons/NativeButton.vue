<script>
    export default {
        props: {
            loading: {
                type: Boolean,
                default: false,
            },
            type: {
                type: String,
                default: 'submit',
            },
            disabled: {
                type: Boolean,
                default: false,
            },
            size: {
                type: String,
                default: 'normal',
            },
            theme: {
                type: String,
                default: 'orange',
                validator: value =>
                    ['orange', 'danger', 'indigo', 'gray'].includes(value),
            },
        },
        computed: {
            textSize() {
                return this.size === 'square' ? 'text-sm' : 'text-sm';
            },
            themeClasses() {
                switch (this.theme) {
                    case 'danger':
                        return 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white';
                    case 'indigo':
                        return 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 text-white';
                    case 'gray':
                        return 'bg-gray-400 hover:bg-gray-500 focus:ring-gray-300 text-white';
                    case 'orange':
                    default:
                        return 'bg-[#d45704] hover:bg-[#d66b18] focus:ring-white text-white';
                }
            },
        },
    };
</script>

<template>
    <button
        :type="type"
        :class="[
            'flex items-center font-medium rounded-lg border border-transparent',
            'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:pointer-events-none',
            size === 'square'
                ? 'justify-center size-[46px]'
                : 'py-2 px-4 gap-x-2',
            textSize,
            themeClasses,
        ]"
        :disabled="loading || disabled"
    >
        <span
            v-if="loading"
            class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent rounded-full"
            role="status"
            aria-label="loading"
        >
            <span class="sr-only">Loading...</span>
        </span>
        <span v-if="!loading || size !== 'square'">
            <slot></slot>
        </span>
    </button>
</template>
