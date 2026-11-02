<script setup lang="ts">
import type { CustomerType } from '@/types';

type Props = {
    value: CustomerType;
};

defineProps<Props>();
</script>

<template>
    <span class="capitalize">
        {{ value }}
    </span>
</template>
