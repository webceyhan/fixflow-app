<script setup lang="ts">
import type { Device } from '@/types';

import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/devices';

type Props = {
    device: Device;
    full?: boolean;
};

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(device)">
        {{ full ? device.brand : '' }}
        {{ device.model }}
        {{ device.color }}
    </TextLink>
</template>
