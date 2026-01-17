<script setup lang="ts">
import { DatePicker } from '@/components/ui/date-picker';
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

interface Props {
    name: string;
}

const props = defineProps<Props>();

const filter = computed<Record<string, any>>(
    () => usePage().props?.filter ?? {},
);

// Decode the filter value
// Formats:
// - "2022-01-01" = from date only (>= 2022-01-01)
// - "2022-01-01,2023-01-01" = date range (>= 2022-01-01 AND <= 2023-01-01)
// - "null,2022-01-01" = until date only (<= 2022-01-01)
const filterValue = filter.value?.[props.name] || '';
const parts = filterValue.split(',');

const dateFrom = ref<string>(parts[0] === 'null' ? '' : parts[0] || '');
const dateTo = ref<string>(parts[1] || '');

// Default max value to current year end (December 31st of current year)
const currentYear = new Date().getFullYear();
const defaultMaxValue = `${currentYear}-12-31`;
const defaultMinValue = `2020-01-01`;

watch([dateFrom, dateTo], ([from, to]) => {
    // Encode filter value
    let value: string | undefined;

    if (from && to) {
        // Both dates: range filter "2022-01-01,2023-01-01"
        value = `${from},${to}`;
    } else if (from) {
        // Only from date: "2022-01-01"
        value = from;
    } else if (to) {
        // Only to date: "null,2022-01-01"
        value = `null,${to}`;
    } else {
        // No dates selected
        value = undefined;
    }

    router.reload({
        data: {
            page: undefined, // Reset page
            filter: {
                ...filter.value,
                [props.name]: value,
            },
        },
    });
});
</script>

<template>
    <div class="gap-2- flex items-center rounded-lg border border-dashed">
        <DatePicker
            v-model="dateFrom"
            class="w-fit rounded-r-none border-0 border-r"
            placeholder="From"
            :min-value="defaultMinValue"
            :max-value="defaultMaxValue"
        />
        <!-- <MoveHorizontalIcon class="text-muted-foreground" /> -->
        <DatePicker
            v-model="dateTo"
            class="w-fit rounded-l-none border-0 border-l"
            placeholder="To"
            :min-value="dateFrom || defaultMinValue"
            :max-value="defaultMaxValue"
        />
    </div>
</template>
