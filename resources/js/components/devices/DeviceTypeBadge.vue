<script setup lang="ts">
import type { DeviceType } from '@/types';

interface Props {
    value: DeviceType;
}

defineProps<Props>();
</script>

<template>
    <span class="capitalize">
        {{ value }}
    </span>
</template>
