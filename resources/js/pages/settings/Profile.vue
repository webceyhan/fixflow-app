<script setup lang="ts">
import { Form, usePage, setLayoutProps, Head } from '@inertiajs/vue3';

import DeleteUser from '@/components/settings/DeleteUser.vue';
import TextLink from '@/components/TextLink.vue';
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
import SettingsLayout from '@/layouts/SettingsLayout.vue';
import { edit, update } from '@/routes/profile';
import { send } from '@/routes/verification';

defineProps<{
    mustVerifyEmail: boolean;
}>();

const title = 'Profile settings';

setLayoutProps({
    breadcrumbs: [
        {
            title,
            href: edit(),
        },
    ],
});

const { user } = usePage().props.auth;
</script>

<template>
    <SettingsLayout>
        <Head :title />

        <Form v-bind="update.form()" v-slot="{ errors, processing }">
            <FieldSet>
                <FieldLegend>Profile information</FieldLegend>
                <FieldDescription>
                    Update your name, email address, and phone number
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="name">Name</FieldLabel>
                        <Input
                            id="name"
                            name="name"
                            :default-value="user.name"
                            autocomplete="name"
                            placeholder="Full name"
                        />
                        <FieldError :message="errors.name" />
                    </Field>

                    <Field>
                        <FieldLabel for="email">Email address</FieldLabel>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            :default-value="user.email"
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <FieldError :message="errors.email" />
                    </Field>

                    <Field>
                        <FieldLabel for="phone">Phone number</FieldLabel>
                        <Input
                            id="phone"
                            type="tel"
                            name="phone"
                            :default-value="user.phone"
                            autocomplete="tel"
                            placeholder="Phone number"
                        />
                        <FieldError :message="errors.phone" />
                    </Field>

                    <Field v-if="mustVerifyEmail && !user.email_verified_at">
                        <FieldDescription>
                            Your email address is unverified.
                            <TextLink :href="send()" as="button">
                                Click here to resend the verification email.
                            </TextLink>
                        </FieldDescription>
                    </Field>

                    <Field orientation="horizontal">
                        <Button
                            :disabled="processing"
                            data-test="update-profile-button"
                        >
                            Save
                        </Button>
                    </Field>
                </FieldGroup>
            </FieldSet>
        </Form>

        <DeleteUser />
    </SettingsLayout>
</template>
