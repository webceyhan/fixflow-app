<script setup lang="ts">
import type { Adjustment } from '@/types';

import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/adjustments';

type Props = {
    adjustment: Adjustment;
};

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(adjustment)">
        {{ adjustment.ref }}
    </TextLink>
</template>
