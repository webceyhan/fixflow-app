<script setup lang="ts">
import { CreateAction } from '@/components/action';
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import UserTable from '@/components/users/UserTable.vue';
import { USER_ROLE, USER_ROLE_ICON_MAP, USER_STATUS } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { create, index } from '@/routes/users';
import type { NavItem, Paginated, User } from '@/types';

defineProps<{
    users: Paginated<User>;
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
            <FilterContainer>
                <SearchFilter placeholder="Search users..." />

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

                <DatePresetFilter name="created_at" />
            </FilterContainer>

            <UserTable :users="users.data" />

            <Pagination v-bind="users" />
        </IndexLayout>
    </AppLayout>
</template>
