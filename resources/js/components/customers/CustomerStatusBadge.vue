<script setup lang="ts">
import type { CustomerStatus } from '@/types';

import StatusBadge from '@/components/StatusBadge.vue';
import { CUSTOMER_STATUS_COLOR_MAP } from '@/constants';

type Props = {
    value: CustomerStatus;
};

defineProps<Props>();
</script>

<template>
    <StatusBadge :color="CUSTOMER_STATUS_COLOR_MAP[value]">
        {{ value }}
    </StatusBadge>
</template>
