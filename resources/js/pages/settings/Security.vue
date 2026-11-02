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

const title = 'Security settings';

setLayoutProps({
    breadcrumbs: [
        {
            title,
            href: edit(),
        },
    ],
});
</script>

<template>
    <SettingsLayout>
        <Head :title />

        <ManagePassword :passwordRules />

        <ManageTwoFactor
            :canManageTwoFactor
            :requiresConfirmation
            :twoFactorEnabled
        />

        <ManagePasskeys :canManagePasskeys :passkeys />
    </SettingsLayout>
</template>
