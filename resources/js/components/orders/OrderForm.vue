<script setup lang="ts">
import { FormActions } from '@/components/form';
import { Checkbox } from '@/components/ui/checkbox';
import { DatePicker } from '@/components/ui/date-picker';
import {
    Field,
    FieldContent,
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
    InputGroup,
    InputGroupAddon,
    InputGroupButton,
    InputGroupInput,
} from '@/components/ui/input-group';
import {
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from '@/components/ui/number-field';
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
import { ORDER_STATUS } from '@/constants';
import { toIsoDate } from '@/lib/utils';
import { store, update } from '@/routes/orders';
import type { Order } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Props {
    order: Order;
}

const { order } = defineProps<Props>();

const action = order.id ? update(order) : store(order.ticket_id);

const { errors, form, isEditing, processing } = useSaveForm({
    entityName: 'Order',
    action,
    data: {
        assignee_id: order.assignee_id ?? '',
        name: order.name ?? '',
        url: order.url ?? '',
        supplier: order.supplier ?? '',
        quantity: order.quantity ?? 1,
        cost: order.cost ?? 0,
        is_billable: order.is_billable,
        due_date: toIsoDate(order.due_date) ?? '',
        note: order.note ?? '',
        status: order.status,
        approved_at: toIsoDate(order.approved_at) ?? '',
    },
});

const isApprovedProxy = computed({
    get: () => !!form.approved_at,
    set: (value: boolean) => {
        form.approved_at = value ? new Date().toISOString() : '';
    },
});

const assigneeName = ref(order.assignee?.name ?? '');

function handleAssign() {
    const { user } = usePage().props.auth;

    // unassign if already assigned to me
    if (form.assignee_id === user.id) {
        form.assignee_id = '';
        assigneeName.value = '';
    } else {
        // assign to me
        form.assignee_id = user.id;
        assigneeName.value = user.name;
    }
}
</script>

<template>
    <form @submit.prevent="form.submit()">
        <FieldGroup>
            <FieldSet>
                <FieldLegend>Order Details</FieldLegend>
                <FieldDescription>
                    Item name, supplier, and optional URL
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="name">Name</FieldLabel>
                        <Input
                            id="name"
                            type="text"
                            placeholder="Item name..."
                            v-model="form.name"
                        />
                        <FieldError :message="errors.name" />
                    </Field>

                    <Field>
                        <FieldLabel for="supplier">Supplier</FieldLabel>
                        <Input
                            id="supplier"
                            type="text"
                            placeholder="Supplier name..."
                            v-model="form.supplier"
                        />
                        <FieldError :message="errors.supplier" />
                    </Field>

                    <Field>
                        <FieldLabel for="url">URL</FieldLabel>
                        <Input
                            id="url"
                            type="url"
                            placeholder="https://..."
                            v-model="form.url"
                        />
                        <FieldDescription>
                            Optional product or tracking URL
                        </FieldDescription>
                        <FieldError :message="errors.url" />
                    </Field>

                    <Field v-if="!isEditing" orientation="horizontal">
                        <Checkbox
                            id="is_preapproved"
                            v-model="isApprovedProxy"
                        />
                        <FieldContent>
                            <FieldLabel for="is_preapproved">
                                Pre-approved
                            </FieldLabel>
                            <FieldDescription>
                                Pre-approve this order for immediate processing
                            </FieldDescription>
                        </FieldContent>
                        <FieldError :message="errors.approved_at" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet>
                <FieldLegend>Quantity & Cost</FieldLegend>
                <FieldDescription>
                    Order quantity, cost, and billing status
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="quantity">Quantity</FieldLabel>
                        <NumberField
                            id="quantity"
                            :min="1"
                            v-model="form.quantity"
                        >
                            <NumberFieldContent>
                                <NumberFieldDecrement />
                                <NumberFieldInput />
                                <NumberFieldIncrement />
                            </NumberFieldContent>
                        </NumberField>
                        <FieldError :message="errors.quantity" />
                    </Field>

                    <Field>
                        <FieldLabel for="cost">Cost</FieldLabel>
                        <Input
                            id="cost"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            v-model="form.cost"
                        />
                        <FieldError :message="errors.cost" />
                    </Field>

                    <Field orientation="horizontal">
                        <Checkbox id="is_billable" v-model="form.is_billable" />
                        <FieldContent>
                            <FieldLabel for="is_billable">Billable</FieldLabel>
                            <FieldDescription>
                                Mark this order as billable to include in
                                invoices
                            </FieldDescription>
                        </FieldContent>
                        <FieldError :message="errors.is_billable" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet v-if="isEditing">
                <FieldLegend>Workflow & Notes</FieldLegend>
                <FieldDescription>
                    Assignment, due date, status, and internal notes
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="assignee_id"> Assignee </FieldLabel>

                        <InputGroup>
                            <InputGroupInput
                                id="assignee_id"
                                v-model="assigneeName"
                                placeholder="Unassigned"
                                readonly
                            />
                            <InputGroupAddon align="inline-end">
                                <InputGroupButton
                                    variant="secondary"
                                    type="button"
                                    @click="handleAssign"
                                >
                                    {{
                                        form.assignee_id ===
                                        $page.props.auth.user.id
                                            ? 'unassign'
                                            : 'assign to me'
                                    }}
                                </InputGroupButton>
                            </InputGroupAddon>
                        </InputGroup>

                        <FieldError :message="errors.assignee_id" />
                    </Field>

                    <Field>
                        <FieldLabel for="due_date">Due Date</FieldLabel>
                        <DatePicker id="due_date" v-model="form.due_date" />
                        <FieldDescription>
                            Set the expected delivery or completion date
                        </FieldDescription>
                        <FieldError :message="errors.due_date" />
                    </Field>

                    <Field>
                        <FieldLabel for="status">Status</FieldLabel>
                        <Select v-model="form.status">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(value, key) in ORDER_STATUS"
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
                        <FieldLabel for="approved_at">
                            Approval Date
                        </FieldLabel>
                        <DatePicker
                            id="approved_at"
                            v-model="form.approved_at"
                        />
                        <FieldDescription>
                            Set the date when this order was approved
                        </FieldDescription>
                        <FieldError :message="errors.approved_at" />
                    </Field>

                    <Field>
                        <FieldLabel for="note">Note</FieldLabel>
                        <Textarea
                            id="note"
                            placeholder="Order details..."
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
