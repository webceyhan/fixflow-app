<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import { DateTime } from '@/components/data';
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
    InputGroup,
    InputGroupAddon,
    InputGroupButton,
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
import { PRIORITY, TICKET_STATUS } from '@/constants';
import { store, update } from '@/routes/tickets';
import type { Ticket } from '@/types';

interface Props {
    ticket: Ticket;
}

const { ticket } = defineProps<Props>();

const action = ticket.id ? update(ticket) : store(ticket.device_id);

const { errors, form, isEditing, processing } = useSaveForm({
    entityName: 'Ticket',
    action,
    data: {
        title: ticket.title ?? '',
        assignee_id: ticket.assignee_id ?? '',
        note: ticket.note ?? '',
        due_date: ticket.due_date ?? '',
        priority: ticket.priority,
        status: ticket.status,
    },
});

const assigneeName = ref(ticket.assignee?.name ?? '');

function handleAssign() {
    const { user } = usePage().props.auth;

    // unasign if already assigned to me
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
                <FieldLegend>Issue Details</FieldLegend>
                <FieldDescription>
                    Brief summary of the problem or request
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="title">Title</FieldLabel>
                        <Input
                            id="title"
                            placeholder="Screen not responding"
                            v-model="form.title"
                        />
                        <FieldError :message="form.errors.title" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet>
                <FieldLegend>Priority & Scheduling</FieldLegend>
                <FieldDescription>
                    Urgency level and target completion date
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="priority">Priority</FieldLabel>
                        <Select v-model="form.priority">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a priority" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(value, key) in PRIORITY"
                                        :key
                                        :value
                                    >
                                        {{ key }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <FieldError :message="errors.priority" />
                    </Field>

                    <Field>
                        <FieldLabel for="due_date">Due Date</FieldLabel>
                        <DatePicker id="due_date" v-model="form.due_date" />
                        <FieldDescription v-if="form.due_date">
                            Due on
                            <DateTime :value="form.due_date" />
                        </FieldDescription>
                        <FieldError :message="errors.due_date" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet v-if="isEditing">
                <FieldLegend>Workflow & Notes</FieldLegend>
                <FieldDescription>
                    Assignment, Workflow status and internal notes
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="assignee_id"> Assignee </FieldLabel>

                        <InputGroup>
                            <InputGroupInput
                                id="assignee_id"
                                v-model="assigneeName"
                                placeholder="Unassigned"
                                disabled
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
                        <FieldLabel for="status">Status</FieldLabel>
                        <Select v-model="form.status">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(value, key) in TICKET_STATUS"
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
