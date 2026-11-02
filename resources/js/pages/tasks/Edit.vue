<script setup lang="ts">
import { computed } from 'vue';

import type { NavItem, Task } from '@/types';

import Heading from '@/components/Heading.vue';
import TaskForm from '@/components/tasks/TaskForm.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/tasks';

const props = defineProps<{
    task: Task;
}>();

const breadcrumbs = computed<NavItem[]>(() => [
    {
        title: 'Tasks',
        href: index(),
    },
    {
        title: props.task.ref,
        href: show(props.task),
    },
    {
        title: 'Edit',
        href: '',
    },
]);
</script>

<template>
    <Heading title="Edit Task" />

    <EditLayout>
        <TaskForm :task />
    </EditLayout>
</template>
