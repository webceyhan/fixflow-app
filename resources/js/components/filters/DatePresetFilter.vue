<script setup lang="ts">
import { CalendarIcon } from '@lucide/vue';

import { DATE_PRESET } from '@/constants/enums';
import { toOptions } from '@/lib/utils';

import SelectFilter from './SelectFilter.vue';

type Props = {
    name: string;
    title?: string;
    placeholder?: string;
};

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
