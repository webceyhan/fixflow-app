<script setup lang="ts">
import AppearanceSelector from '@/components/settings/AppearanceSelector.vue';
import {
    Field,
    FieldDescription,
    FieldLegend,
    FieldSet,
} from '@/components/ui/field';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/SettingsLayout.vue';
import { edit } from '@/routes/appearance';
import type { NavItem } from '@/types';

const breadcrumbs: NavItem[] = [
    {
        title: 'Appearance settings',
        href: edit(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <SettingsLayout>
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
    </AppLayout>
</template>
