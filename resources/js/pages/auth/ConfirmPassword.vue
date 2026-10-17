<script setup lang="ts">
import { Form } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Field, FieldError, FieldLabel, FieldSet } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/password/confirm';
</script>

<template>
    <AuthLayout>
        <Heading
            head="Confirm password"
            title="Confirm your password"
            description="This is a secure area of the application. Please confirm your password before continuing."
        />

        <Form
            v-bind="store.form()"
            reset-on-success
            v-slot="{ errors, processing }"
        >
            <FieldSet>
                <Field>
                    <FieldLabel htmlFor="password">Password</FieldLabel>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        autofocus
                    />
                    <FieldError :message="errors.password" />
                </Field>

                <Field>
                    <Button
                        :disabled="processing"
                        data-test="confirm-password-button"
                    >
                        <Spinner v-if="processing" />
                        Confirm Password
                    </Button>
                </Field>
            </FieldSet>
        </Form>
    </AuthLayout>
</template>
