<script setup lang="ts">
import type { TaskStatus } from '@/types';

import StatusBadge from '@/components/StatusBadge.vue';
import { TASK_STATUS_COLOR_MAP } from '@/constants';

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
