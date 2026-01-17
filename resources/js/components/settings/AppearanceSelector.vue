<script setup lang="ts">
import { FormSelector } from '@/components/form';
import { useAppearance } from '@/composables/useAppearance';
import type { Appearance, Option } from '@/types';
import { Monitor, Moon, Sun } from 'lucide-vue-next';
import { computed } from 'vue';

const options: Option<Appearance>[] = [
    { value: 'light', label: 'Light', icon: Sun },
    { value: 'dark', label: 'Dark', icon: Moon },
    { value: 'system', label: 'System', icon: Monitor },
] as const;

const { appearance, updateAppearance } = useAppearance();

const proxyValue = computed({
    get: () => appearance.value,
    set: (val) => updateAppearance(val),
});
</script>

<template>
    <FormSelector :options v-model="proxyValue" />
</template>
