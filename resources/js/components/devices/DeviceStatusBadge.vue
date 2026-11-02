<script setup lang="ts">
import type { DeviceStatus } from '@/types';

import StatusBadge from '@/components/StatusBadge.vue';
import { DEVICE_STATUS_COLOR_MAP } from '@/constants';

type Props = {
    value: DeviceStatus;
};

defineProps<Props>();
</script>

<template>
    <StatusBadge :color="DEVICE_STATUS_COLOR_MAP[value]">
        {{ value.replace('_', ' ') }}
    </StatusBadge>
</template>
