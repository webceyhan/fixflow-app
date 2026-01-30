<script setup lang="ts">
import { CreateAction } from '@/components/action';
import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import TaskList from '@/components/tasks/TaskList.vue';
import TicketCard from '@/components/tickets/TicketCard.vue';
import TicketToolbar from '@/components/tickets/TicketToolbar.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { toUUID } from '@/lib/utils';
import { create as createTask } from '@/routes/tasks';
import { index, update } from '@/routes/tickets';
import type { NavItem, Ticket } from '@/types';

const props = defineProps<{
    ticket: Ticket;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Tickets',
        href: index(),
    },
    {
        title: toUUID(props.ticket.id, 'TCK'),
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Ticket Details">
            <TicketToolbar :ticket />
        </Heading>

        <ShowLayout>
            <TicketCard :ticket />

            <NoteCard :data="ticket" :href="update(ticket)" />

            <section class="row-span-2 space-y-2 lg:col-start-2 lg:row-start-1">
                <h3>Tasks</h3>

                <TaskList :tasks="ticket.tasks" />

                <CreateAction
                    class="w-full"
                    variant="ghost"
                    size="lg"
                    :href="createTask(ticket)"
                >
                    Add new task
                </CreateAction>
            </section>
        </ShowLayout>
    </AppLayout>
</template>
