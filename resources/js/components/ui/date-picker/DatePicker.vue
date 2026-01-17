<script setup lang="ts">
import type { DateValue } from '@internationalized/date';
import { DateFormatter, getLocalTimeZone, parseDate } from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { computed, type HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';

const props = defineProps<{
    modelValue?: string;
    placeholder?: string;
    class?: HTMLAttributes['class'];
    minValue?: string;
    maxValue?: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const df = new DateFormatter('en-GB', {
    dateStyle: 'short',
});

// Convert string (yyyy-MM-dd) to DateValue and vice versa
const dateValue = computed({
    get: (): DateValue | undefined => {
        if (!props.modelValue) return undefined;
        try {
            return parseDate(props.modelValue);
        } catch {
            return undefined;
        }
    },
    set: (val: DateValue | undefined) => {
        if (val) {
            emit('update:modelValue', val.toString());
        } else {
            emit('update:modelValue', '');
        }
    },
});
</script>

<template>
    <Popover v-slot="{ close }">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                :class="
                    cn(
                        'w-full justify-start text-left font-normal',
                        !dateValue && 'text-muted-foreground',
                        props.class
                    )
                "
            >
                <CalendarIcon />
                {{
                    dateValue
                        ? df.format(dateValue.toDate(getLocalTimeZone()))
                        : placeholder || 'Pick a date'
                }}
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0" align="start">
            <Calendar
                v-model="dateValue"
                layout="month-and-year"
                :placeholder="dateValue"
                :min-value="minValue ? parseDate(minValue) : undefined"
                :max-value="maxValue ? parseDate(maxValue) : undefined"
                @update:model-value="close"
                initial-focus
            />
        </PopoverContent>
    </Popover>
</template>
