<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import type { User } from '@/types';

import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import Placeholder from '@/components/Placeholder.vue';
import UserCard from '@/components/users/UserCard.vue';
import UserToolbar from '@/components/users/UserToolbar.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { index, update } from '@/routes/users';

const props = defineProps<{
    user: User;
    isAdmin?: boolean;
}>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Users',
            href: index(),
        },
        {
            title: props.user.name,
            href: '',
        },
    ],
});
</script>

<template>
    <Heading title="User Details">
        <UserToolbar v-if="isAdmin" :user />
    </Heading>

    <ShowLayout>
        <UserCard :user />

        <NoteCard :data="user" :href="update(user)" />

        <Placeholder class="col-start-2 row-span-2 row-start-1 max-lg:hidden" />
    </ShowLayout>
</template>
