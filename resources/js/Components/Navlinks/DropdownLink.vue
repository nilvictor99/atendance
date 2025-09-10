<script setup>
    import { Link, usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';

    const props = defineProps({
        href: String,
        as: String,
        roles: {
            type: Array,
            default: () => [],
        },
        permissions: {
            type: Array,
            default: () => [],
        },
    });

    const hasAccess = computed(() => {
        if (props.roles.length === 0 && props.permissions.length === 0) {
            return true;
        }

        const user = usePage().props.auth?.user;
        if (!user) return false;

        const hasRole =
            props.roles.length === 0 ||
            props.roles.some(role => user.roles.includes(role));

        const hasPermission =
            props.permissions.length === 0 ||
            props.permissions.some(permission =>
                user.permissions.includes(permission)
            );

        return hasRole && hasPermission;
    });
</script>

<template>
    <div v-if="hasAccess">
        <button
            v-if="as == 'button'"
            type="submit"
            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
        >
            <slot />
        </button>

        <a
            v-else-if="as == 'a'"
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
        >
            <slot />
        </a>

        <Link
            v-else
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
        >
            <slot />
        </Link>
    </div>
</template>
