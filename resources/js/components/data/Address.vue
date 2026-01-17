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

    <TextLink
        v-else
        class="whitespace-pre-line"
        :href="`http://maps.google.com/?q=${value}`"
        target="_blank"
        native
    >
        {{ value }}
    </TextLink>
</template>
