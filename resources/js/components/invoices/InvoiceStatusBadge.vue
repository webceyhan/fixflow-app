<script setup lang="ts">
import StatusBadge from '@/components/StatusBadge.vue';
import { INVOICE_STATUS_COLOR_MAP } from '@/constants';
import type { InvoiceStatus } from '@/types';

interface Props {
    value: InvoiceStatus;
}

defineProps<Props>();
</script>

<template>
    <StatusBadge :color="INVOICE_STATUS_COLOR_MAP[value]">
        {{ value.replace('_', ' ') }}
    </StatusBadge>
</template>
