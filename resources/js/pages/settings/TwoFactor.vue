<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { ShieldBan, ShieldCheck } from 'lucide-vue-next';
import { onUnmounted, ref, watch } from 'vue';

import TwoFactorRecoveryCodes from '@/components/settings/TwoFactorRecoveryCodes.vue';
import TwoFactorSetupModal from '@/components/settings/TwoFactorSetupModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Field,
    FieldContent,
    FieldDescription,
    FieldGroup,
    FieldLegend,
    FieldSet,
} from '@/components/ui/field';
import { useToast } from '@/composables/useToast';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/SettingsLayout.vue';
import { disable, enable, show } from '@/routes/two-factor';
import type { NavItem } from '@/types';

const props = defineProps<{
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Two-Factor Authentication',
        href: show(),
    },
];
const toast = useToast();
const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth();
const showSetupModal = ref<boolean>(false);

watch(
    () => props.twoFactorEnabled,
    (enabled) => {
        if (enabled === undefined) return;

        toast.success(
            `Two-factor authentication ${enabled ? 'enabled' : 'disabled'}!`,
        );
    },
);

onUnmounted(() => {
    clearTwoFactorAuthData();
});
</script>

<template>
    <AppLayout :breadcrumbs>
        <SettingsLayout>
            <FieldSet>
                <FieldLegend> Two-Factor Authentication </FieldLegend>
                <FieldDescription>
                    Manage your two-factor authentication settings
                </FieldDescription>

                <FieldGroup v-if="!twoFactorEnabled">
                    <Field>
                        <FieldContent>
                            <Badge variant="destructive">Disabled</Badge>
                        </FieldContent>

                        <FieldDescription>
                            When you enable two-factor authentication, you will
                            be prompted for a secure pin during login. This pin
                            can be retrieved from a TOTP-supported application
                            on your phone.
                        </FieldDescription>
                    </Field>

                    <Field orientation="horizontal">
                        <Button
                            v-if="hasSetupData"
                            @click="showSetupModal = true"
                        >
                            <ShieldCheck />
                            Continue Setup
                        </Button>

                        <Form
                            v-else
                            v-bind="enable.form()"
                            @success="showSetupModal = true"
                            #default="{ processing }"
                        >
                            <Button type="submit" :disabled="processing">
                                <ShieldCheck />
                                Enable 2FA
                            </Button>
                        </Form>
                    </Field>
                </FieldGroup>

                <FieldGroup v-else>
                    <Field>
                        <FieldContent>
                            <Badge variant="default">Enabled</Badge>
                        </FieldContent>

                        <FieldDescription>
                            With two-factor authentication enabled, you will be
                            prompted for a secure, random pin during login,
                            which you can retrieve from the TOTP-supported
                            application on your phone.
                        </FieldDescription>
                    </Field>

                    <TwoFactorRecoveryCodes />

                    <Field orientation="horizontal">
                        <Form v-bind="disable.form()" #default="{ processing }">
                            <Button
                                type="submit"
                                variant="destructive"
                                :disabled="processing"
                            >
                                <ShieldBan />
                                Disable 2FA
                            </Button>
                        </Form>
                    </Field>
                </FieldGroup>

                <TwoFactorSetupModal
                    v-model:isOpen="showSetupModal"
                    :requiresConfirmation
                    :twoFactorEnabled
                />
            </FieldSet>
        </SettingsLayout>
    </AppLayout>
</template>
