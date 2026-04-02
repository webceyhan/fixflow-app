<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import TaskForm from '@/components/tasks/TaskForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/tickets';
import type { NavItem, Task, Ticket } from '@/types';

const props = defineProps<{
    task: Task;
    ticket: Ticket;
}>();

const title = 'Create Task';

const breadcrumbs: NavItem[] = [
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
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading :title />

        <EditLayout>
            <TaskForm :task />
        </EditLayout>
    </AppLayout>
</template>
