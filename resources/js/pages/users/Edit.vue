<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import UserForm from '@/components/users/UserForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/users';
import type { NavItem, User } from '@/types';

const props = defineProps<{
    user: User;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Users',
        href: index(),
    },
    {
        title: props.user.name,
        href: show(props.user),
    },
    {
        title: 'Edit',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading :title="`Edit ${user.name}`" />

        <EditLayout>
            <UserForm :user />
        </EditLayout>
    </AppLayout>
</template>
