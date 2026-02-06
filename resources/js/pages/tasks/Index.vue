<script setup lang="ts">
import AssigneeFilter from '@/components/filters/AssigneeFilter.vue';
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import TaskTable from '@/components/tasks/TaskTable.vue';
import { PRIORITY, TASK_STATUS, TASK_TYPE } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/tasks';
import type { NavItem, Paginated, Task } from '@/types';

defineProps<{
    tasks: Paginated<Task>;
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
            <FilterContainer>
                <SearchFilter placeholder="Search tasks..." />

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

                <DatePresetFilter name="created_at" />
            </FilterContainer>

            <TaskTable :tasks="tasks.data" />

            <Pagination v-bind="tasks" />
        </IndexLayout>
    </AppLayout>
</template>
