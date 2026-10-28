<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import Placeholder from '@/components/Placeholder.vue';
import TaskCard from '@/components/tasks/TaskCard.vue';
import TaskToolbar from '@/components/tasks/TaskToolbar.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { index, update } from '@/routes/tasks';
import type { Task } from '@/types';

const props = defineProps<{
    task: Task;
}>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Tasks',
            href: index(),
        },
        {
            title: props.task.ref,
            href: '',
        },
    ],
});
</script>

<template>
    <Heading title="Task Details">
        <TaskToolbar :task />
    </Heading>

    <ShowLayout>
        <TaskCard :task />

        <NoteCard :data="task" :href="update(task)" />

        <Placeholder class="col-start-2 row-span-2 row-start-1 max-lg:hidden" />
    </ShowLayout>
</template>
