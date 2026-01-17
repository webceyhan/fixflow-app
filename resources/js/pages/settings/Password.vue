<script setup lang="ts">
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
import { Input } from '@/components/ui/input';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/SettingsLayout.vue';
import { edit, update } from '@/routes/user-password';
import type { NavItem } from '@/types';
import { Form } from '@inertiajs/vue3';

const toast = useToast();

const breadcrumbs: NavItem[] = [
    {
        title: 'Password settings',
        href: edit(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <SettingsLayout>
            <Form
                v-bind="update.form()"
                v-slot="{ errors, processing }"
                :options="{ preserveScroll: true }"
                @success="toast.success('Password updated successfully!')"
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
                        Ensure your account is using a long, random password to
                        stay secure
                    </FieldDescription>

                    <FieldGroup>
                        <Field>
                            <FieldLabel for="current_password">
                                Current password
                            </FieldLabel>
                            <Input
                                id="current_password"
                                name="current_password"
                                type="password"
                                autocomplete="current-password"
                                placeholder="Current password"
                            />
                            <FieldError :message="errors.current_password" />
                        </Field>

                        <Field>
                            <FieldLabel for="password">
                                New password
                            </FieldLabel>
                            <Input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                placeholder="New password"
                            />
                            <FieldError :message="errors.password" />
                        </Field>

                        <Field>
                            <FieldLabel for="password_confirmation">
                                Confirm password
                            </FieldLabel>
                            <Input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                placeholder="Confirm password"
                            />
                            <FieldError
                                :message="errors.password_confirmation"
                            />
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
        </SettingsLayout>
    </AppLayout>
</template>
