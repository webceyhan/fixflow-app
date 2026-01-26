<script setup lang="ts">
import SelectFilter from './SelectFilter.vue';
</script>

<template>
    <SelectFilter
        name="assignee"
        placeholder="Anybody"
        :options="[
            { value: 'me', label: 'For me' },
            { value: 'others', label: 'For others' },
            { value: 'none', label: 'Unassigned' },
        ]"
    />
</template>
