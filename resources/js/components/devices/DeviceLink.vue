<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/devices';
import type { Device } from '@/types';

interface Props {
    device: Device;
    full?: boolean;
}

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(device)">
        {{ full ? device.brand : '' }}
        {{ device.model }}
        {{ device.color }}
    </TextLink>
</template>
