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
import { USER_STATUS } from '@/constants';
import { store, update } from '@/routes/users';
import type { User } from '@/types';

import UserRoleSelector from './UserRoleSelector.vue';

interface Props {
    user: User;
}

const { user } = defineProps<Props>();

const action = user.id ? update(user) : store();

const { errors, form, isEditing, processing } = useSaveForm({
    entityName: 'User',
    action,
    data: {
        name: user.name ?? '',
        email: user.email ?? '',
        phone: user.phone ?? '',
        note: user.note ?? '',
        role: user.role,
        status: user.status,
    },
});
</script>

<template>
    <form @submit.prevent="form.submit()">
        <FieldGroup>
            <Field>
                <UserRoleSelector v-model="form.role" />
            </Field>

            <FieldSet>
                <FieldLegend>Account Information</FieldLegend>
                <FieldDescription>
                    User profile and contact details
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
                                        v-for="(value, key) in USER_STATUS"
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
