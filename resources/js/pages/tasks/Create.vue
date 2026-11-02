<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import type { Task, Ticket } from '@/types';

import Heading from '@/components/Heading.vue';
import TaskForm from '@/components/tasks/TaskForm.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/tickets';

const props = defineProps<{
    task: Task;
    ticket: Ticket;
}>();

const title = 'Create Task';

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Tickets',
            href: index(),
        },
        {
            title: props.ticket.ref,
            href: show(props.ticket),
        },
        {
            title,
            href: '',
        },
    ],
});
</script>

<template>
    <Heading :title />

    <EditLayout>
        <TaskForm :task />
    </EditLayout>
</template>
