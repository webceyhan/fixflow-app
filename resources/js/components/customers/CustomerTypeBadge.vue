<script setup lang="ts">
import type { CustomerType } from '@/types';

interface Props {
    value: CustomerType;
}

defineProps<Props>();
</script>

<template>
    <span class="capitalize">
        {{ value }}
    </span>
</template>
