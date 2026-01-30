<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import TaskForm from '@/components/tasks/TaskForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/tasks';
import type { NavItem, Task } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    task: Task;
}>();

const breadcrumbs = computed<NavItem[]>(() => [
    {
        title: 'Tasks',
        href: index(),
    },
    {
        title: `Task #${props.task.id}`,
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
        <Heading :title="`Edit Task #${task.id}`" />

        <EditLayout>
            <TaskForm :task />
        </EditLayout>
    </AppLayout>
</template>
