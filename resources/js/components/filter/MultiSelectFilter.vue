<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
    CommandSeparator,
} from '@/components/ui/command';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Separator } from '@/components/ui/separator';
import type { Option } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { FilterIcon } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface Props {
    name: string;
    title: string;
    options: Option[];
}

const props = defineProps<Props>();

const filter = computed<Record<string, any>>(
    () => usePage().props?.filter ?? {},
);

const selectedValues = ref<Set<string>>(
    // Decode comma-separated string into a Set
    new Set(filter.value?.[props.name]?.split(',') || []),
);

const selectedOptions = computed<Option[]>(() =>
    props.options.filter(({ value }) => selectedValues.value.has(value)),
);

function handleSelect(value: any) {
    if (selectedValues.value.has(value)) {
        selectedValues.value.delete(value);
    } else {
        selectedValues.value.add(value);
    }
}

function handleClear() {
    selectedValues.value.clear();
}

watch(selectedValues.value, (values) => {
    router.reload({
        data: {
            page: undefined, // Reset page
            filter: {
                ...filter.value,
                // Encode Set into a comma-separated string
                [props.name]: Array.from(values).join(',') || undefined,
            },
        },
    });
});
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button class="border-dashed" variant="outline">
                <FilterIcon class="opacity-50" />
                {{ title }}

                <template v-if="selectedValues.size > 0">
                    <Separator class="mx-1 h-4" orientation="vertical" />

                    <!-- mobile-only -->
                    <Badge
                        class="-mx-1 rounded-sm px-1 font-normal lg:hidden"
                        variant="secondary"
                    >
                        {{ selectedValues.size }}
                    </Badge>

                    <!-- desktop-only -->
                    <div class="-mx-1 hidden gap-x-1 lg:flex">
                        <Badge
                            v-if="selectedValues.size > 2"
                            class="rounded-sm px-1 font-normal"
                            variant="secondary"
                        >
                            {{ selectedValues.size }} selected
                        </Badge>

                        <template v-else>
                            <Badge
                                v-for="option in selectedOptions"
                                :key="option.value"
                                class="rounded-sm px-1 font-normal"
                                variant="secondary"
                            >
                                {{ option.label }}
                            </Badge>
                        </template>
                    </div>
                </template>
            </Button>
        </PopoverTrigger>

        <PopoverContent class="w-50 p-0" align="start">
            <Command>
                <CommandInput :placeholder="title" />
                <CommandList>
                    <CommandEmpty>No results found.</CommandEmpty>
                    <CommandGroup>
                        <CommandItem
                            v-for="option in options"
                            :key="option.value"
                            :value="option.value"
                            @select="handleSelect(option.value)"
                        >
                            <!-- checkbox -->
                            <Checkbox
                                tabindex="-1"
                                class="mr-2 border-primary/25"
                                :model-value="selectedValues.has(option.value)"
                            />

                            <!-- icon -->
                            <component
                                v-if="option.icon"
                                :is="option.icon"
                                class="text-muted-foreground"
                            />

                            <!-- label -->
                            <span>{{ option.label }}</span>

                            <!-- This can be enabled later to show a check icon instead of a checkbox. -->
                            <!-- <CheckIcon
                                v-if="selectedValues.has(option.value)"
                                class="ml-auto"
                            /> -->
                        </CommandItem>
                    </CommandGroup>

                    <!-- clear action -->
                    <template v-if="selectedValues.size > 0">
                        <CommandSeparator />
                        <CommandGroup>
                            <CommandItem
                                class="justify-center text-center"
                                :value="null"
                                @select="handleClear"
                            >
                                Clear filters
                            </CommandItem>
                        </CommandGroup>
                    </template>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
