<script setup lang="ts">
import { FormActions } from '@/components/form';
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
import { CUSTOMER_STATUS } from '@/constants';
import { store, update } from '@/routes/customers';
import type { Customer } from '@/types';
import CustomerTypeSelector from './CustomerTypeSelector.vue';

interface Props {
    customer: Customer;
}

const { customer } = defineProps<Props>();

const action = customer.id ? update(customer) : store();

const { errors, form, isEditing, processing } = useSaveForm({
    entityName: 'Customer',
    action,
    data: {
        name: customer.name ?? '',
        company: customer.company ?? '',
        tax_number: customer.tax_number ?? '',
        email: customer.email ?? '',
        phone: customer.phone ?? '',
        address: customer.address ?? '',
        note: customer.note ?? '',
        type: customer.type,
        status: customer.status,
    },
});
</script>

<template>
    <form @submit.prevent="form.submit()">
        <FieldGroup>
            <Field>
                <CustomerTypeSelector v-model="form.type" />
            </Field>

            <FieldSet>
                <FieldLegend>Basic Information</FieldLegend>
                <FieldDescription>
                    Customer identity and business details
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="name">Name</FieldLabel>
                        <Input
                            id="name"
                            placeholder="John Doe"
                            v-model="form.name"
                        />
                        <FieldError :message="errors.name" />
                    </Field>

                    <Field v-if="form.type === 'business'">
                        <FieldLabel for="company">Company</FieldLabel>
                        <Input
                            id="company"
                            placeholder="Acme Inc."
                            v-model="form.company"
                        />
                        <FieldError :message="errors.company" />
                    </Field>

                    <Field v-if="form.type === 'business'">
                        <FieldLabel for="tax_number">Tax Number</FieldLabel>
                        <Input
                            id="tax_number"
                            placeholder="123-45-6789"
                            v-model="form.tax_number"
                        />
                        <FieldError :message="errors.tax_number" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet>
                <FieldLegend>Contact Information</FieldLegend>
                <FieldDescription>
                    Customer contact details and address
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="email">Email</FieldLabel>
                        <Input
                            id="email"
                            type="email"
                            placeholder="john@example.com"
                            v-model="form.email"
                        />
                        <FieldError :message="errors.email" />
                    </Field>

                    <Field>
                        <FieldLabel for="phone">Phone</FieldLabel>
                        <Input
                            id="phone"
                            type="tel"
                            placeholder="+1234567890"
                            v-model="form.phone"
                        />
                        <FieldError :message="errors.phone" />
                    </Field>

                    <Field>
                        <FieldLabel for="address">Address</FieldLabel>
                        <Textarea
                            id="address"
                            placeholder="123 Main St, City, State 12345"
                            v-model="form.address"
                        />
                        <FieldError :message="errors.address" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet v-if="isEditing">
                <FieldLegend>Account Management</FieldLegend>
                <FieldDescription>
                    Account status and internal notes
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="status">Status</FieldLabel>
                        <Select v-model="form.status">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(value, key) in CUSTOMER_STATUS"
                                        :key
                                        :value
                                    >
                                        {{ key }}
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
                            placeholder="Additional information..."
                            v-model="form.note"
                        />
                        <FieldError :message="errors.note" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FormActions :processing :is-editing />
        </FieldGroup>
    </form>
</template>
