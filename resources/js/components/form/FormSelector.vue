<script setup lang="ts">
import {
    Field,
    FieldContent,
    FieldDescription,
    FieldLabel,
    FieldTitle,
} from '@/components/ui/field';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import type { Option } from '@/types';

interface Props {
    options: Option[];
}

defineProps<Props>();

const model = defineModel<any>();
</script>

<template>
    <RadioGroup class="lg:flex" v-model="model">
        <FieldLabel
            class="hover:bg-muted/25"
            v-for="{ value, label, icon } in options"
            :key="value"
            :for="value"
        >
            <Field orientation="horizontal">
                <FieldContent class="items-center gap-3 max-lg:flex-row">
                    <FieldDescription>
                        <component :is="icon" class="lg:size-10" />
                    </FieldDescription>
                    <FieldTitle>{{ label }}</FieldTitle>
                </FieldContent>
                <RadioGroupItem :id="value" :value class="lg:hidden" />
            </Field>
        </FieldLabel>
    </RadioGroup>
</template>
