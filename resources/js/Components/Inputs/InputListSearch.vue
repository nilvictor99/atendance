<script setup>
    import { ref, watch } from 'vue';
    import Search from '@/Components/Icons/Search.vue';
    import ButtonClean from '@/Components/Buttons/ButtonClean.vue';

    const props = defineProps({
        placeholder: { type: String, default: 'Buscar...' },
        modelValue: { type: String, default: '' },
        cleanButton: { type: Boolean, default: false },
    });

    const emit = defineEmits(['update:modelValue', 'search']);

    const searchTerm = ref(props.modelValue ?? '');

    watch(searchTerm, val => {
        emit('update:modelValue', val);
        debounceSearch(val);
    });

    let timeout;
    function debounceSearch(val) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            emit('search', val);
        }, 400);
    }

    function clearSearch() {
        searchTerm.value = '';
        emit('update:modelValue', '');
        emit('cleared');
    }
</script>

<template>
    <div class="relative">
        <div class="flex">
            <span
                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
            >
                <Search class="w-6 h-6 text-gray-500 font-bold" />
            </span>
            <input
                :class="[
                    'block w-96 pl-10 text-gray-900 bg-transparent bg-white border border-white outline-none dark:border-white focus:ring-2 focus:ring-white focus:ring-opacity-5 focus:border-white font-bold dark:bg-gray-100',
                    cleanButton && (searchTerm || '').length > 0
                        ? 'rounded-r-none rounded-l-md'
                        : 'rounded-md',
                ]"
                :placeholder="placeholder"
                v-model="searchTerm"
                type="text"
                autocomplete="off"
            />
            <ButtonClean
                v-if="cleanButton && (searchTerm || '').length > 0"
                @click="clearSearch"
                :isDeleting="(searchTerm || '').length > 0"
                class="w-8 h-[40px] top-[1px] right-[-31px] flex items-center"
            />
        </div>
    </div>
</template>
