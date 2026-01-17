<script setup lang="ts">
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';
import { router, usePage } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { SearchIcon } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    placeholder?: string;
}

defineProps<Props>();

const filter = computed<Record<string, any>>(
    () => usePage().props?.filter ?? {},
);

const debouncedSearch = useDebounceFn((val: string) => {
    router.reload({
        data: {
            page: undefined, // Reset page
            filter: {
                ...filter.value,
                search: val || undefined,
            },
        },
    });
}, 200);

const proxyValue = computed({
    get: () => filter.value?.search || '',
    set: (val: string) => debouncedSearch(val),
});
</script>

<template>
    <InputGroup class="md:max-w-2xs">
        <InputGroupInput
            v-model="proxyValue"
            :placeholder="placeholder || 'Search...'"
        />
        <InputGroupAddon>
            <SearchIcon />
        </InputGroupAddon>
    </InputGroup>
</template>
