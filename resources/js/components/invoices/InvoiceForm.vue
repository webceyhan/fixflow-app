<script setup lang="ts">
import { FormActions } from '@/components/form';
import { DatePicker } from '@/components/ui/date-picker';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldGroup,
    FieldLabel,
    FieldLegend,
    FieldSeparator,
    FieldSet,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
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
import { INVOICE_STATUS } from '@/constants';
import { toIsoDate } from '@/lib/utils';
import { update } from '@/routes/invoices';
import type { Invoice } from '@/types';

interface Props {
    invoice: Invoice;
}

const { invoice } = defineProps<Props>();

const { errors, form, processing } = useSaveForm({
    entityName: 'Invoice',
    action: update(invoice),
    data: {
        total: invoice.total ?? 0,
        discount_amount: invoice.discount_amount ?? 0,
        fee_amount: invoice.fee_amount ?? 0,
        due_date: toIsoDate(invoice.due_date) ?? '',
        status: invoice.status,
        note: invoice.note ?? '',
    },
});
</script>

<template>
    <form @submit.prevent="form.submit()">
        <FieldGroup>
            <FieldSet>
                <FieldLegend>Amounts</FieldLegend>
                <FieldDescription>
                    Invoice total, discounts, fees, and payments
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="total">Total</FieldLabel>
                        <Input
                            id="total"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            v-model="form.total"
                        />
                        <FieldError :message="errors.total" />
                    </Field>

                    <Field>
                        <FieldLabel for="discount_amount">Discount</FieldLabel>
                        <Input
                            id="discount_amount"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            v-model="form.discount_amount"
                        />
                        <FieldError :message="errors.discount_amount" />
                    </Field>

                    <Field>
                        <FieldLabel for="fee_amount">Fee</FieldLabel>
                        <Input
                            id="fee_amount"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            v-model="form.fee_amount"
                        />
                        <FieldError :message="errors.fee_amount" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet>
                <FieldLegend>Timeline & Status</FieldLegend>
                <FieldDescription>
                    Due date and invoice status
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="due_date">Due Date</FieldLabel>
                        <DatePicker id="due_date" v-model="form.due_date" />
                        <FieldError :message="errors.due_date" />
                    </Field>

                    <Field>
                        <FieldLabel for="status">Status</FieldLabel>
                        <Select v-model="form.status">
                            <SelectTrigger id="status">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(label, value) in INVOICE_STATUS"
                                        :key="value"
                                        :value="value"
                                    >
                                        {{ label }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <FieldError :message="errors.status" />
                    </Field>

                    <Field>
                        <FieldLabel for="note">Note</FieldLabel>
                        <Textarea
                            id="note"
                            rows="5"
                            placeholder="Add any additional notes..."
                            v-model="form.note"
                        />
                        <FieldError :message="errors.note" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FormActions :processing />
        </FieldGroup>
    </form>
</template>
