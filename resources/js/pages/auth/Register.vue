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
import { store } from '@/routes/register';
import { Form } from '@inertiajs/vue3';
</script>

<template>
    <AuthLayout>
        <Heading
            head="Register"
            title="Create an account"
            description="Enter your details below to create your account"
        />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
        >
            <FieldSet>
                <Field>
                    <FieldLabel for="name">Name</FieldLabel>
                    <Input
                        id="name"
                        type="text"
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="Full name"
                    />
                    <FieldError :message="errors.name" />
                </Field>

                <Field>
                    <FieldLabel for="email">Email address</FieldLabel>
                    <Input
                        id="email"
                        type="email"
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="email@example.com"
                    />
                    <FieldError :message="errors.email" />
                </Field>

                <Field>
                    <FieldLabel for="phone">Phone number</FieldLabel>
                    <Input
                        id="phone"
                        type="tel"
                        :tabindex="3"
                        autocomplete="tel"
                        name="phone"
                        placeholder="Phone number"
                    />
                    <FieldError :message="errors.phone" />
                </Field>

                <Field>
                    <FieldLabel for="password">Password</FieldLabel>
                    <Input
                        id="password"
                        type="password"
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Password"
                    />
                    <FieldError :message="errors.password" />
                </Field>

                <Field>
                    <FieldLabel for="password_confirmation">
                        Confirm password
                    </FieldLabel>
                    <Input
                        id="password_confirmation"
                        type="password"
                        :tabindex="5"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                    />
                    <FieldError :message="errors.password_confirmation" />
                </Field>

                <Field>
                    <Button
                        type="submit"
                        tabindex="6"
                        :disabled="processing"
                        data-test="register-user-button"
                    >
                        <Spinner v-if="processing" />
                        Create account
                    </Button>
                </Field>

                <FieldDescription class="text-center">
                    Already have an account?
                    <TextLink :href="login()" :tabindex="6">Log in</TextLink>
                </FieldDescription>
            </FieldSet>
        </Form>
    </AuthLayout>
</template>
