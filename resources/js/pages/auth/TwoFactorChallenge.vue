<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import Heading from '@/components/Heading.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldSet,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/two-factor/login';

interface AuthConfigContent {
    title: string;
    description: string;
    toggleText: string;
}

const authConfigContent = computed<AuthConfigContent>(() => {
    if (showRecoveryInput.value) {
        return {
            title: 'Recovery Code',
            description:
                'Please confirm access to your account by entering one of your emergency recovery codes.',
            toggleText: 'login using an authentication code',
        };
    }

    return {
        title: 'Authentication Code',
        description:
            'Enter the authentication code provided by your authenticator application.',
        toggleText: 'login using a recovery code',
    };
});

const showRecoveryInput = ref<boolean>(false);

const toggleRecoveryMode = (clearErrors: () => void): void => {
    showRecoveryInput.value = !showRecoveryInput.value;
    clearErrors();
    code.value = '';
};

const code = ref<string>('');
</script>

<template>
    <AuthLayout>
        <Heading
            head="Two-Factor Authentication"
            :title="authConfigContent.title"
            :description="authConfigContent.description"
        />

        <Form
            v-if="!showRecoveryInput"
            v-bind="store.form()"
            reset-on-error
            @error="code = ''"
            #default="{ errors, processing, clearErrors }"
        >
            <input type="hidden" name="code" :value="code" />
            <FieldSet>
                <Field>
                    <div class="flex w-full items-center justify-center">
                        <InputOTP
                            id="otp"
                            v-model="code"
                            :maxlength="6"
                            :disabled="processing"
                            autofocus
                        >
                            <InputOTPGroup>
                                <InputOTPSlot
                                    v-for="index in 6"
                                    :key="index"
                                    :index="index - 1"
                                />
                            </InputOTPGroup>
                        </InputOTP>
                    </div>
                    <FieldError :message="errors.code" />
                </Field>

                <Field>
                    <Button type="submit" :disabled="processing">
                        Continue
                    </Button>
                </Field>

                <FieldDescription class="text-center">
                    or you can
                    <TextLink
                        @click.capture.prevent="
                            () => toggleRecoveryMode(clearErrors)
                        "
                    >
                        {{ authConfigContent.toggleText }}
                    </TextLink>
                </FieldDescription>
            </FieldSet>
        </Form>

        <Form
            v-else
            v-bind="store.form()"
            reset-on-error
            #default="{ errors, processing, clearErrors }"
        >
            <FieldSet>
                <Field>
                    <Input
                        name="recovery_code"
                        type="text"
                        placeholder="Enter recovery code"
                        :autofocus="showRecoveryInput"
                        required
                    />
                    <FieldError :message="errors.recovery_code" />
                </Field>

                <Field>
                    <Button type="submit" :disabled="processing">
                        Continue
                    </Button>
                </Field>

                <FieldDescription class="text-center">
                    or you can
                    <TextLink
                        @click.capture.prevent="
                            () => toggleRecoveryMode(clearErrors)
                        "
                    >
                        {{ authConfigContent.toggleText }}
                    </TextLink>
                </FieldDescription>
            </FieldSet>
        </Form>
    </AuthLayout>
</template>
