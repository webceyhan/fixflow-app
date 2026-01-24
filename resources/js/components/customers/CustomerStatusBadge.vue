<script setup lang="ts">
import StatusBadge from '@/components/StatusBadge.vue';
import { CUSTOMER_STATUS_COLOR_MAP } from '@/constants';
import type { CustomerStatus } from '@/types';

interface Props {
    value: CustomerStatus;
}

defineProps<Props>();
</script>

<template>
    <StatusBadge :color="CUSTOMER_STATUS_COLOR_MAP[value]">
        {{ value }}
    </StatusBadge>
</template>
