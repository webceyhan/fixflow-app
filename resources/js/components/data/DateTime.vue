<script setup lang="ts">
import { formatDate, formatTimestamp } from '@/lib/utils';

import Fallback from './Fallback.vue';

interface Props {
    value?: string;
    full?: boolean;
}

defineProps<Props>();
</script>

<template>
    <Fallback v-if="!value" />

    <time v-else :datetime="value">
        {{ full ? formatTimestamp(value) : formatDate(value) }}
    </time>
</template>
