<script setup lang="ts">
import {
    Command,
    CommandEmpty,
    CommandInput,
    CommandList,
} from '@/components/ui/command';

interface Props {
    placeholder?: string;
    highlightOnHover?: boolean;
    searchable?: boolean;
    multiple?: boolean;
}

defineProps<Props>();

const modelValue = defineModel<string | string[] | null>();
</script>

<template>
    <Command
        v-model="modelValue"
        :selection-behavior="multiple ? 'toggle' : 'replace'"
        :highlight-on-hover
        :multiple
    >
        <CommandInput v-if="searchable" :placeholder />

        <CommandList>
            <CommandEmpty>No results found.</CommandEmpty>

            <slot />
        </CommandList>
    </Command>
</template>
