<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { UserRoundMinusIcon, UsersIcon } from 'lucide-vue-next';
import { computed } from 'vue';

import { USER_ROLE_ICON_MAP } from '@/constants';
import type { Option, User } from '@/types';

import SelectFilter from './SelectFilter.vue';

const page = usePage();
const authUser = page.props.auth.user as User;
const assignableUsers = page.props.assignableUsers as User[];

const pinnedOptions: Option[] = [
    {
        value: 'me',
        label: 'Me',
        icon: USER_ROLE_ICON_MAP[authUser.role],
        pinned: true,
    },
    {
        value: 'others',
        label: 'Others',
        icon: UsersIcon,
        pinned: true,
    },
    {
        value: 'none',
        label: 'Nobody',
        icon: UserRoundMinusIcon,
        pinned: true,
    },
];

const options = computed<Option[]>(() =>
    assignableUsers
        // exclude user corresponding to ME preset
        .filter(({ id }) => id !== authUser.id)
        // convert to Option type
        .map(({ id, name, role }) => ({
            value: `${id}`,
            label: name,
            icon: USER_ROLE_ICON_MAP[role],
        })),
);
</script>

<template>
    <SelectFilter
        name="assignee"
        title="Assignee"
        :options="[...pinnedOptions, ...options]"
    />
</template>
