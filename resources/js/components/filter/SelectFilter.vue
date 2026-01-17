<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import type { Icon, Option } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    name: string;
    options: Option[];
    placeholder?: string;
    icon?: Icon;
}

const props = defineProps<Props>();

const filter = computed<Record<string, any>>(
    () => usePage().props?.filter ?? {},
);

const proxyValue = computed({
    get: () => filter.value?.[props.name] || 'any',
    set: (val: any) => {
        router.reload({
            data: {
                page: undefined, // Reset page
                filter: {
                    ...filter.value,
                    [props.name]: val === 'any' ? undefined : val,
                },
            },
        });
    },
});
</script>

<template>
    <Select v-model="proxyValue">
        <SelectTrigger class="border-dashed">
            <component v-if="icon" class="opacity-50" :is="icon" />
            <SelectValue
                class="font-medium"
                :placeholder="placeholder || 'Select...'"
            />
        </SelectTrigger>
        <SelectContent>
            <SelectItem value="any">
                {{ placeholder || 'Select...' }}
            </SelectItem>
            <SelectItem
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </SelectItem>
        </SelectContent>
    </Select>
</template>
