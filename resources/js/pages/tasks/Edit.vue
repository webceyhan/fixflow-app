<script setup lang="ts">
import { computed } from 'vue';

import Heading from '@/components/Heading.vue';
import TaskForm from '@/components/tasks/TaskForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/tasks';
import type { NavItem, Task } from '@/types';

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
    <AppLayout :breadcrumbs>
        <Heading title="Edit Task" />

        <EditLayout>
            <TaskForm :task />
        </EditLayout>
    </AppLayout>
</template>
