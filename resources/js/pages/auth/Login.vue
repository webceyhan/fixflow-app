<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
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
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form } from '@inertiajs/vue3';

defineProps<{
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <AuthLayout>
        <Heading
            head="Log in"
            title="Log in to your account"
            description="Enter your email and password below to log in"
        />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
        >
            <FieldSet>
                <Field>
                    <FieldLabel for="email">Email address</FieldLabel>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="email@example.com"
                    />
                    <FieldError :message="errors.email" />
                </Field>

                <Field>
                    <div class="flex items-center justify-between">
                        <FieldLabel for="password">Password</FieldLabel>
                        <FieldDescription>
                            <TextLink
                                v-if="canResetPassword"
                                :href="request()"
                                :tabindex="5"
                            >
                                Forgot password?
                            </TextLink>
                        </FieldDescription>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <FieldError :message="errors.password" />
                </Field>

                <Field orientation="horizontal">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <FieldLabel for="remember"> Remember me </FieldLabel>
                </Field>

                <Field>
                    <Button
                        type="submit"
                        :tabindex="4"
                        :disabled="processing"
                        data-test="login-button"
                    >
                        <Spinner v-if="processing" />
                        Log in
                    </Button>
                </Field>

                <FieldDescription v-if="canRegister" class="text-center">
                    Don't have an account?
                    <TextLink :href="register()" :tabindex="5">
                        Sign up
                    </TextLink>
                </FieldDescription>
            </FieldSet>
        </Form>
    </AuthLayout>
</template>
