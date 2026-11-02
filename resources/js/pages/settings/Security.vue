<script setup lang="ts">
import { Head, setLayoutProps } from '@inertiajs/vue3';

import type { Props as ManagePasskeysProps } from '@/components/settings/ManagePasskeys.vue';
import type { Props as ManagePasswordProps } from '@/components/settings/ManagePassword.vue';
import type { Props as ManageTwoFactorProps } from '@/components/settings/ManageTwoFactor.vue';

import ManagePasskeys from '@/components/settings/ManagePasskeys.vue';
import ManagePassword from '@/components/settings/ManagePassword.vue';
import ManageTwoFactor from '@/components/settings/ManageTwoFactor.vue';
import SettingsLayout from '@/layouts/SettingsLayout.vue';
import { edit } from '@/routes/security';

type Props = ManagePasswordProps & ManagePasskeysProps & ManageTwoFactorProps;

defineProps<Props>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Security settings',
            href: edit(),
        },
    ],
});
</script>

<template>
    <SettingsLayout>
        <h1 class="sr-only">Security settings</h1>

        <ManagePassword :passwordRules />

        <ManageTwoFactor
            :canManageTwoFactor
            :requiresConfirmation
            :twoFactorEnabled
        />

        <ManagePasskeys :canManagePasskeys :passkeys />

        <Head title="Security settings" />
    </SettingsLayout>
</template>
