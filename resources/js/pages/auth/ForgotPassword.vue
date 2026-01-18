<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
    FieldSet,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { email } from '@/routes/password';
import { Form } from '@inertiajs/vue3';
</script>

<template>
    <AuthLayout>
        <Heading
            title="Forgot password"
            description="Enter your email to receive a password reset link"
        />

        <Form v-bind="email.form()" v-slot="{ errors, processing }">
            <FieldSet>
                <Field>
                    <FieldLabel for="email">Email address</FieldLabel>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="off"
                        autofocus
                        placeholder="email@example.com"
                    />
                    <FieldError :message="errors.email" />
                </Field>

                <Field>
                    <Button
                        :disabled="processing"
                        data-test="email-password-reset-link-button"
                    >
                        <Spinner v-if="processing" />
                        Email password reset link
                    </Button>
                </Field>

                <FieldDescription class="text-center">
                    Or, return to
                    <TextLink :href="login()">log in</TextLink>
                </FieldDescription>
            </FieldSet>
        </Form>
    </AuthLayout>
</template>
