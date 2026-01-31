<script setup lang="ts">
import { TransactionMethodIcon } from '@/icons';
import type { TransactionMethod } from '@/types';

interface Props {
    value: TransactionMethod;
}

defineProps<Props>();
</script>

<template>
    <span class="inline-flex items-center gap-1.5 capitalize">
        <TransactionMethodIcon class="size-4" :value="value" />
        {{ value }}
    </span>
</template>
