<script setup lang="ts">
import { Form } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldGroup,
    FieldLabel,
    FieldLegend,
    FieldSet,
} from '@/components/ui/field';
import { update } from '@/routes/user-password';
import { PasswordInput } from '@/components/ui/password-input';

export type Props = {
    passwordRules: string;
};

defineProps<Props>();
</script>

<template>
    <Form
        v-bind="update.form()"
        v-slot="{ errors, processing }"
        :options="{ preserveScroll: true }"
        reset-on-success
        :reset-on-error="[
            'password',
            'password_confirmation',
            'current_password',
        ]"
    >
        <FieldSet>
            <FieldLegend>Update password</FieldLegend>
            <FieldDescription>
                Ensure your account is using a long, random password to stay
                secure
            </FieldDescription>

            <FieldGroup>
                <Field>
                    <FieldLabel for="current_password">
                        Current password
                    </FieldLabel>
                    <PasswordInput
                        id="current_password"
                        name="current_password"
                        autocomplete="current-password"
                        placeholder="Current password"
                    />
                    <FieldError :message="errors.current_password" />
                </Field>

                <Field>
                    <FieldLabel for="password"> New password </FieldLabel>
                    <PasswordInput
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="New password"
                        :passwordrules="passwordRules"
                    />
                    <FieldError :message="errors.password" />
                </Field>

                <Field>
                    <FieldLabel for="password_confirmation">
                        Confirm password
                    </FieldLabel>
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Confirm password"
                        :passwordrules="passwordRules"
                    />
                    <FieldError :message="errors.password_confirmation" />
                </Field>

                <Field orientation="horizontal">
                    <Button
                        :disabled="processing"
                        data-test="update-password-button"
                    >
                        Save password
                    </Button>
                </Field>
            </FieldGroup>
        </FieldSet>
    </Form>
</template>
