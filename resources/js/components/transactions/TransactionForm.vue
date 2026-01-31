<script setup lang="ts">
import { FormActions } from '@/components/form';
import {
    Field,
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
import { TRANSACTION_METHOD } from '@/constants';
import { store, update } from '@/routes/transactions';
import type { Transaction } from '@/types';
import TransactionTypeSelector from './TransactionTypeSelector.vue';

interface Props {
    transaction: Transaction;
}

const { transaction } = defineProps<Props>();

const action = transaction.id
    ? update(transaction)
    : store(transaction.invoice_id);

const { errors, form, isEditing, processing } = useSaveForm({
    entityName: 'Transaction',
    action,
    data: {
        amount: transaction.amount ?? 0,
        method: transaction.method,
        type: transaction.type,
        note: transaction.note ?? '',
    },
});
</script>

<template>
    <form @submit.prevent="form.submit()">
        <FieldGroup>
            <FieldSet>
                <FieldLegend>Transaction Information</FieldLegend>
                <FieldDescription>
                    Update amount, payment method and transaction type.
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <TransactionTypeSelector v-model="form.type" />
                    </Field>

                    <Field>
                        <FieldLabel for="amount">Amount</FieldLabel>
                        <InputGroup>
                            <InputGroupAddon>$</InputGroupAddon>
                            <InputGroupInput
                                id="amount"
                                v-model="form.amount"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                            />
                        </InputGroup>
                        <FieldError :message="errors.amount" />
                    </Field>

                    <Field>
                        <FieldLabel for="method">Payment Method</FieldLabel>
                        <Select v-model="form.method">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a method" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(
                                            value, key
                                        ) in TRANSACTION_METHOD"
                                        :key
                                        :value
                                    >
                                        {{ key }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <FieldError :message="errors.method" />
                    </Field>

                    <Field>
                        <FieldLabel for="note">Note</FieldLabel>
                        <Textarea
                            id="note"
                            v-model="form.note"
                            placeholder="Enter transaction note ..."
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
