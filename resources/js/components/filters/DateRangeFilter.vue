<script setup lang="ts">
import { DatePicker } from '@/components/ui/date-picker';
import { useFilter } from '@/composables/useFilter';

interface Props {
    name: string;
}

const props = defineProps<Props>();

type DateRange = {
    from?: string;
    to?: string;
};

// Formats:
// - "2022-01-01" = from date only (>= 2022-01-01)
// - "2022-01-01,2023-01-01" = date range (>= 2022-01-01 AND <= 2023-01-01)
// - "null,2022-01-01" = until date only (<= 2022-01-01)
const { state, update } = useFilter<DateRange>({
    name: props.name,
    decode: (value) => {
        const [from, to] = value?.split(',') ?? [];
        return { from, to };
    },
    encode: (value) => {
        const { from, to } = value;
        return [from, to].join(',');
    },
});

// Default max value to current year end (December 31st of current year)
const currentYear = new Date().getFullYear();
const maxDate = `${currentYear}-12-31`;
const minDate = `2020-01-01`;
</script>

<template>
    <div class="flex items-center rounded-lg border border-dashed">
        <DatePicker
            class="w-fit rounded-r-none border-0 border-r"
            placeholder="From"
            :min-value="minDate"
            :max-value="maxDate"
            :model-value="state.from"
            @update:model-value="update({ ...state, from: $event })"
        />

        <DatePicker
            class="w-fit rounded-l-none border-0 border-l"
            placeholder="To"
            :min-value="state.from || minDate"
            :max-value="maxDate"
            :model-value="state.to"
            @update:model-value="update({ ...state, to: $event })"
        />
    </div>
</template>
