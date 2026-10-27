<script setup lang="ts">
import StatusBadge from '@/components/StatusBadge.vue';
import { TASK_STATUS_COLOR_MAP } from '@/constants';
import type { TaskStatus } from '@/types';

type Props = {
    value: TaskStatus;
};

defineProps<Props>();
</script>

<template>
    <StatusBadge :color="TASK_STATUS_COLOR_MAP[value]">
        {{ value.replace('_', ' ') }}
    </StatusBadge>
</template>
