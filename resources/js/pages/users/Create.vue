<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import UserForm from '@/components/users/UserForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index } from '@/routes/users';
import type { NavItem, User } from '@/types';

defineProps<{
    user: User;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Users',
        href: index(),
    },
    {
        title: 'Create',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Create User" />

        <EditLayout>
            <UserForm :user />
        </EditLayout>
    </AppLayout>
</template>
