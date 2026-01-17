<script setup lang="ts">
import { CalendarIcon } from 'lucide-vue-next';
import SelectFilter from './SelectFilter.vue';

interface Props {
    name: string;
    placeholder?: string;
}

defineProps<Props>();
</script>

<template>
    <SelectFilter
        :name="name"
        :icon="CalendarIcon"
        :placeholder="placeholder || 'Anytime'"
        :options="[
            { label: 'Today', value: 'today' },
            { label: 'Last week', value: 'last_week' },
            { label: 'Last month', value: 'last_month' },
            { label: 'Last year', value: 'last_year' },
        ]"
    />
</template>
