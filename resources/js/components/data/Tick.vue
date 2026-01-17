<script setup lang="ts">
import { CheckIcon, ClockAlertIcon, MinusIcon } from 'lucide-vue-next';

interface Props {
    value?: any;
    pending?: boolean;
}

defineProps<Props>();
</script>

<template>
    <span class="flex items-center gap-1 *:size-4">
        <slot />

        <CheckIcon v-if="!!value" class="text-success" />
        <component
            v-else
            :is="pending ? ClockAlertIcon : MinusIcon"
            class="text-neutral"
        />
    </span>
</template>
