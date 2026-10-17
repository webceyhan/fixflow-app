<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useSaveForm } from '@/composables/useSaveForm';
import { PRIORITY, TASK_STATUS } from '@/constants';
import { toIsoDate } from '@/lib/utils';
import { store, update } from '@/routes/tasks';
import type { Task } from '@/types';

import TaskTypeSelector from './TaskTypeSelector.vue';

interface Props {
    task: Task;
}

const { task } = defineProps<Props>();

const action = task.id ? update(task) : store(task.ticket_id);

const { errors, form, isEditing, processing } = useSaveForm({
    entityName: 'Task',
    action,
    data: {
        assignee_id: task.assignee_id ?? '',
        cost: task.cost ?? 0,
        is_billable: task.is_billable,
        note: task.note ?? '',
        priority: task.priority,
        type: task.type,
        status: task.status,
        approved_at: toIsoDate(task.approved_at) ?? '',
    },
});

const isApprovedProxy = computed({
    get: () => !!form.approved_at,
    set: (value: boolean) => {
        form.approved_at = value ? new Date().toISOString() : '';
    },
});

const assigneeName = ref(task.assignee?.name ?? '');

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
            <Field>
                <TaskTypeSelector v-model="form.type" />
            </Field>

            <FieldSet>
                <FieldLegend>Task Details</FieldLegend>
                <FieldDescription>
                    Priority and task description
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
                                Pre-approve this task for immediate execution
                            </FieldDescription>
                        </FieldContent>
                        <FieldError :message="errors.approved_at" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet>
                <FieldLegend>Billing & Cost</FieldLegend>
                <FieldDescription>
                    Cost estimation and billable status
                </FieldDescription>

                <FieldGroup>
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
                                Mark this task as billable to include in
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
                    Assignment, workflow status and internal notes
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
                        <FieldLabel for="status">Status</FieldLabel>
                        <Select v-model="form.status">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(value, key) in TASK_STATUS"
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
                            Set the date when this task was approved
                        </FieldDescription>
                        <FieldError :message="errors.approved_at" />
                    </Field>

                    <Field>
                        <FieldLabel for="note">Note</FieldLabel>
                        <Textarea
                            id="note"
                            placeholder="Task details..."
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
