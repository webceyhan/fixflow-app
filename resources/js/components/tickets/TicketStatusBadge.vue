<script setup lang="ts">
import StatusBadge from '@/components/StatusBadge.vue';
import { TICKET_STATUS_COLOR_MAP } from '@/constants';
import type { TicketStatus } from '@/types';

interface Props {
    value: TicketStatus;
}

defineProps<Props>();
</script>

<template>
    <StatusBadge :color="TICKET_STATUS_COLOR_MAP[value]">
        {{ value.replace('_', ' ') }}
    </StatusBadge>
</template>
