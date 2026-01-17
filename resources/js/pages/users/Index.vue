<script setup lang="ts">
import { CreateAction } from '@/components/action';
import {
    FilterGroup,
    MultiSelectFilter,
    RelativeDateFilter,
    SearchFilter,
} from '@/components/filter';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import UserTable from '@/components/users/UserTable.vue';
import { USER_ROLE, USER_ROLE_ICON_MAP, USER_STATUS } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { create, index } from '@/routes/users';
import type { NavItem, Paginated, User, UserRole, UserStatus } from '@/types';

defineProps<{
    users: Paginated<User>;
    filter?: {
        search?: string;
        role?: UserRole;
        status?: UserStatus;
    };
    isAdmin?: boolean;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Users',
        href: index(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Users">
            <CreateAction v-if="isAdmin" :href="create()" />
        </Heading>

        <IndexLayout>
            <FilterGroup>
                <SearchFilter
                    placeholder="Search users ..."
                    :value="filter?.search"
                />

                <MultiSelectFilter
                    name="role"
                    title="Role"
                    :options="toOptions(USER_ROLE, USER_ROLE_ICON_MAP)"
                />

                <MultiSelectFilter
                    name="status"
                    title="Status"
                    :options="toOptions(USER_STATUS)"
                />

                <RelativeDateFilter name="created_at" />
            </FilterGroup>

            <UserTable :users="users.data" />

            <Pagination v-bind="users" />
        </IndexLayout>
    </AppLayout>
</template>
