<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import UserForm from '@/components/users/UserForm.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/users';
import type { User } from '@/types';

const props = defineProps<{
    user: User;
}>();

setLayoutProps({
    breadcrumbs: [
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
    ],
});
</script>

<template>
    <Heading :title="`Edit ${user.name}`" />

    <EditLayout>
        <UserForm :user />
    </EditLayout>
</template>
