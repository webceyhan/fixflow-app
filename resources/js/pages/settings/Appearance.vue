<script setup lang="ts">
import { setLayoutProps, Head } from '@inertiajs/vue3';

import AppearanceSelector from '@/components/settings/AppearanceSelector.vue';
import {
    Field,
    FieldDescription,
    FieldLegend,
    FieldSet,
} from '@/components/ui/field';
import SettingsLayout from '@/layouts/SettingsLayout.vue';
import { edit } from '@/routes/appearance';

const title = 'Appearance settings';

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

        <FieldSet>
            <FieldLegend> Appearance Settings </FieldLegend>
            <FieldDescription>
                Update your account's appearance settings
            </FieldDescription>

            <Field>
                <AppearanceSelector />
            </Field>
        </FieldSet>
    </SettingsLayout>
</template>
