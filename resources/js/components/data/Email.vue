<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';

import Fallback from './Fallback.vue';

type Props = {
    value?: string;
};

defineProps<Props>();
</script>

<template>
    <Fallback v-if="!value" />

    <TextLink v-else :href="`mailto:${value}`" native>
        {{ value }}
    </TextLink>
</template>
