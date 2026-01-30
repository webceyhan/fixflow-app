<script setup lang="ts">
import StatusBadge from '@/components/StatusBadge.vue';
import { ORDER_STATUS_COLOR_MAP } from '@/constants';
import type { OrderStatus } from '@/types';

interface Props {
    value: OrderStatus;
}

defineProps<Props>();
</script>

<template>
    <StatusBadge :color="ORDER_STATUS_COLOR_MAP[value]">
        {{ value.replace('_', ' ') }}
    </StatusBadge>
</template>
