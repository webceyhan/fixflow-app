<script setup lang="ts">
import type { InertiaLinkProps } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

interface Props extends Omit<InertiaLinkProps, 'href'> {
    href?: InertiaLinkProps['href'] | null;
    tabindex?: number;
    native?: boolean;
}

const props = defineProps<Props>();
</script>

<template>
    <span v-if="!href">
        <slot />
    </span>

    <component
        v-else
        class="underline decoration-current/25 underline-offset-4 transition-colors duration-200 ease-out hover:decoration-current"
        :is="native ? 'a' : Link"
        v-bind="props"
    >
        <slot />
    </component>
</template>
