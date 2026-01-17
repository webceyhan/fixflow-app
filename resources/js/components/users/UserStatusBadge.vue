<script setup lang="ts">
import StatusBadge from '@/components/StatusBadge.vue';
import { USER_STATUS_COLOR_MAP } from '@/constants';
import type { UserStatus } from '@/types';

interface Props {
    value: UserStatus;
}

defineProps<Props>();
</script>

<template>
    <StatusBadge :color="USER_STATUS_COLOR_MAP[value]">
        {{ value }}
    </StatusBadge>
</template>
