<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
    // Tambahkan prop baru untuk mode sidebar
    isSidebarLink: {
        type: Boolean,
        default: false,
    }
});

const classes = computed(() => {
    if (props.isSidebarLink) {
        // Class untuk link di sidebar
        return props.active
            ? 'block px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-md'
            : 'block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-md';
    } else {
        // Class untuk link di navbar (default Breeze)
        return props.active
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
    }
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>