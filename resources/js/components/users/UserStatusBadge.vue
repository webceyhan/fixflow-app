<script setup lang="ts">
import type { UserStatus } from '@/types';

import StatusBadge from '@/components/StatusBadge.vue';
import { USER_STATUS_COLOR_MAP } from '@/constants';

type Props = {
    value: UserStatus;
};

defineProps<Props>();
</script>

<template>
    <StatusBadge :color="USER_STATUS_COLOR_MAP[value]">
        {{ value }}
    </StatusBadge>
</template>
