<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import type { User } from '@/types';

import Heading from '@/components/Heading.vue';
import UserForm from '@/components/users/UserForm.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index } from '@/routes/users';

defineProps<{
    user: User;
}>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Users',
            href: index(),
        },
        {
            title: 'Create',
            href: '',
        },
    ],
});
</script>

<template>
    <Heading title="Create User" />

    <EditLayout>
        <UserForm :user />
    </EditLayout>
</template>
