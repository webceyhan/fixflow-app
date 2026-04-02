<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import Placeholder from '@/components/Placeholder.vue';
import TaskCard from '@/components/tasks/TaskCard.vue';
import TaskToolbar from '@/components/tasks/TaskToolbar.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { index, update } from '@/routes/tasks';
import type { NavItem, Task } from '@/types';

const props = defineProps<{
    task: Task;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Tasks',
        href: index(),
    },
    {
        title: props.task.ref,
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Task Details">
            <TaskToolbar :task />
        </Heading>

        <ShowLayout>
            <TaskCard :task />

            <NoteCard :data="task" :href="update(task)" />

            <Placeholder
                class="col-start-2 row-span-2 row-start-1 max-lg:hidden"
            />
        </ShowLayout>
    </AppLayout>
</template>
