<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { toUUID } from '@/lib/utils';
import { show } from '@/routes/adjustments';
import type { Adjustment } from '@/types';

interface Props {
    adjustment: Adjustment;
}

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(adjustment)">
        {{ toUUID(adjustment.id, 'ADJ') }}
    </TextLink>
</template>
