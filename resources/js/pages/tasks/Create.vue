<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import TaskForm from '@/components/tasks/TaskForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index } from '@/routes/tasks';
import { show as ticketShow } from '@/routes/tickets';
import type { NavItem, Task, Ticket } from '@/types';

const props = defineProps<{
    task: Task;
    ticket: Ticket;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Tasks',
        href: index(),
    },
    {
        title: props.ticket.title,
        href: ticketShow(props.ticket),
    },
    {
        title: 'Create',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Create Task" />

        <EditLayout>
            <TaskForm :task />
        </EditLayout>
    </AppLayout>
</template>
