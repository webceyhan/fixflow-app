<script setup lang="ts">
import { PRIORITY_COLOR_MAP } from '@/constants';
import { PriorityIcon } from '@/icons';
import type { Priority } from '@/types';

/**
 * text-netural
 * text-info
 * text-error
 */
interface Props {
    value: Priority;
}

defineProps<Props>();
</script>

<template>
    <span class="flex items-center gap-1 capitalize">
        <PriorityIcon
            :class="`text-${PRIORITY_COLOR_MAP[value]} size-4`"
            :value
        />
        {{ value }}
    </span>
</template>
