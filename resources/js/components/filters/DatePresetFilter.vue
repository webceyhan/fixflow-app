<script setup lang="ts">
import { DATE_PRESET } from '@/constants/enums';
import { toOptions } from '@/lib/utils';
import { CalendarIcon } from 'lucide-vue-next';
import SelectFilter from './SelectFilter.vue';

interface Props {
    name: string;
    title?: string;
    placeholder?: string;
}

defineProps<Props>();
</script>

<template>
    <SelectFilter
        :name
        :title="title ?? 'Date'"
        :icon="CalendarIcon"
        :options="toOptions(DATE_PRESET)"
        highlight-on-hover
    />
</template>
