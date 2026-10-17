<script setup lang="ts">
import { computed } from 'vue';

import { FormActions } from '@/components/form';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Field,
    FieldContent,
    FieldDescription,
    FieldError,
    FieldGroup,
    FieldLabel,
    FieldLegend,
    FieldSet,
} from '@/components/ui/field';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useSaveForm } from '@/composables/useSaveForm';
import { ADJUSTMENT_REASON } from '@/constants';
import { store, update } from '@/routes/adjustments';
import type { Adjustment, AdjustmentReason } from '@/types';

import AdjustmentTypeSelector from './AdjustmentTypeSelector.vue';

interface Props {
    adjustment: Adjustment;
}

const { adjustment } = defineProps<Props>();

const action = adjustment.id
    ? update(adjustment)
    : store(adjustment.invoice_id);

const { errors, form, isEditing, processing } = useSaveForm({
    entityName: 'Adjustment',
    action,
    data: {
        amount: adjustment.amount ?? 0,
        is_percent: adjustment.is_percent ?? false,
        type: adjustment.type,
        reason: adjustment.reason,
        note: adjustment.note ?? '',
    },
});

const FEE_REASONS: AdjustmentReason[] = [
    ADJUSTMENT_REASON.RushService,
    ADJUSTMENT_REASON.Delivery,
    ADJUSTMENT_REASON.LatePayment,
    ADJUSTMENT_REASON.Cancellation,
];

const reasonOptions = computed(() => {
    return Object.entries(ADJUSTMENT_REASON).filter(([, value]) =>
        form.type === 'fee'
            ? FEE_REASONS.includes(value)
            : !FEE_REASONS.includes(value),
    );
});
</script>

<template>
    <form @submit.prevent="form.submit()">
        <FieldGroup>
            <Field>
                <AdjustmentTypeSelector v-model="form.type" />
            </Field>

            <FieldSet>
                <FieldLegend>Adjustment Information</FieldLegend>
                <FieldDescription>
                    Update amount, percentage option and adjustment reason.
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="reason">Reason</FieldLabel>
                        <Select v-model="form.reason">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a reason" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="[key, value] in reasonOptions"
                                        :key
                                        :value
                                    >
                                        {{ key }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <FieldError :message="errors.reason" />
                    </Field>

                    <Field>
                        <FieldLabel for="amount">Amount</FieldLabel>
                        <InputGroup>
                            <InputGroupAddon v-if="!form.is_percent">
                                $
                            </InputGroupAddon>
                            <InputGroupInput
                                id="amount"
                                v-model="form.amount"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                            />
                            <InputGroupAddon v-if="form.is_percent">
                                %
                            </InputGroupAddon>
                        </InputGroup>
                        <FieldError :message="errors.amount" />
                    </Field>

                    <Field orientation="horizontal">
                        <Checkbox id="is_percent" v-model="form.is_percent" />

                        <FieldContent>
                            <FieldLabel for="is_percent">
                                Percentage Based
                            </FieldLabel>
                            <FieldDescription>
                                Apply this adjustment as a percentage of invoice
                                total.
                            </FieldDescription>
                        </FieldContent>
                        <FieldError :message="errors.is_percent" />
                    </Field>

                    <Field>
                        <FieldLabel for="note">Note</FieldLabel>
                        <Textarea
                            id="note"
                            v-model="form.note"
                            placeholder="Enter adjustment note ..."
                            rows="6"
                        />
                        <FieldError :message="errors.note" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FormActions :processing :is-editing />
        </FieldGroup>
    </form>
</template>
