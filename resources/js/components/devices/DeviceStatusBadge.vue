<script setup lang="ts">
import StatusBadge from '@/components/StatusBadge.vue';
import { DEVICE_STATUS_COLOR_MAP } from '@/constants';
import type { DeviceStatus } from '@/types';

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
