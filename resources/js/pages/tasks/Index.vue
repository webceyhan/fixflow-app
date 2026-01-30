<script setup lang="ts">
import {
    AssigneeFilter,
    FilterGroup,
    MultiSelectFilter,
    RelativeDateFilter,
    SearchFilter,
} from '@/components/filter';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import TaskTable from '@/components/tasks/TaskTable.vue';
import { PRIORITY, TASK_STATUS, TASK_TYPE } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/tasks';
import type {
    NavItem,
    Paginated,
    Priority,
    Task,
    TaskStatus,
    TaskType,
} from '@/types';

defineProps<{
    tasks: Paginated<Task>;
    filter?: {
        search?: string;
        assignee?: string;
        type?: TaskType;
        priority?: Priority;
        status?: TaskStatus;
        created_at?: string;
    };
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Tasks',
        href: index(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Tasks" />

        <IndexLayout>
            <FilterGroup>
                <SearchFilter
                    placeholder="Search tasks ..."
                    :value="filter?.search"
                />

                <AssigneeFilter />

                <MultiSelectFilter
                    name="type"
                    title="Type"
                    :options="toOptions(TASK_TYPE)"
                />

                <MultiSelectFilter
                    name="priority"
                    title="Priority"
                    :options="toOptions(PRIORITY)"
                />

                <MultiSelectFilter
                    name="status"
                    title="Status"
                    :options="toOptions(TASK_STATUS)"
                />

                <RelativeDateFilter name="created_at" />
            </FilterGroup>

            <TaskTable :tasks="tasks.data" />

            <Pagination v-bind="tasks" />
        </IndexLayout>
    </AppLayout>
</template>
