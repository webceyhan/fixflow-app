<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';

import Fallback from './Fallback.vue';

interface Props {
    value?: string;
}

defineProps<Props>();
</script>

<template>
    <Fallback v-if="!value" />

    <TextLink v-else :href="`tel:${value}`" native>
        {{ value }}
    </TextLink>
</template>
