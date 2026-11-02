<script setup lang="ts">
import type { DeviceType } from '@/types';

type Props = {
    value: DeviceType;
};

defineProps<Props>();
</script>

<template>
    <span class="capitalize">
        {{ value }}
    </span>
</template>
