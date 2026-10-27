<script setup lang="ts">
import type { AdjustmentReason } from '@/types';

type Props = {
    value: AdjustmentReason;
};

defineProps<Props>();

const formatReason = (reason: AdjustmentReason) => {
    return reason.replace(/_/g, ' ');
};
</script>

<template>
    <span class="inline-flex items-center gap-1.5 capitalize">
        {{ formatReason(value) }}
    </span>
</template>
