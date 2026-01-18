<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel, FieldSet } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { update } from '@/routes/password';
import { Form } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <AuthLayout>
        <Heading
            title="Reset password"
            description="Please enter your new password below"
        />

        <Form
            v-bind="update.form()"
            :transform="(data) => ({ ...data, token, email })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
        >
            <FieldSet>
                <Field>
                    <FieldLabel for="email">Email</FieldLabel>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="email"
                        v-model="inputEmail"
                        readonly
                    />
                    <FieldError :message="errors.email" />
                </Field>

                <Field>
                    <FieldLabel for="password">Password</FieldLabel>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        autofocus
                        placeholder="Password"
                    />
                    <FieldError :message="errors.password" />
                </Field>

                <Field>
                    <FieldLabel for="password_confirmation">
                        Confirm Password
                    </FieldLabel>
                    <Input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Confirm password"
                    />
                    <FieldError :message="errors.password_confirmation" />
                </Field>

                <Field>
                    <Button
                        type="submit"
                        :disabled="processing"
                        data-test="reset-password-button"
                    >
                        <Spinner v-if="processing" />
                        Reset password
                    </Button>
                </Field>
            </FieldSet>
        </Form>
    </AuthLayout>
</template>
