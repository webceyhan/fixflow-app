<script setup lang="ts">
import { CreateAction } from '@/components/action';
import Heading from '@/components/Heading.vue';
import InvoiceCard from '@/components/invoices/InvoiceCard.vue';
import NoteCard from '@/components/NoteCard.vue';
import OrderList from '@/components/orders/OrderList.vue';
import TaskList from '@/components/tasks/TaskList.vue';
import TicketAttachmentCard from '@/components/tickets/TicketAttachmentCard.vue';
import TicketCard from '@/components/tickets/TicketCard.vue';
import TicketOrderStats from '@/components/tickets/TicketOrderStats.vue';
import TicketTaskStats from '@/components/tickets/TicketTaskStats.vue';
import TicketToolbar from '@/components/tickets/TicketToolbar.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { create as createOrder } from '@/routes/orders';
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
        title: props.ticket.ref,
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

            <TicketAttachmentCard
                :ticket-id="ticket.id"
                :attachments="ticket.attachments"
            />

            <div class="row-span-2 space-y-4 lg:col-start-2 lg:row-start-1">
                <section class="space-y-2">
                    <h3>Tasks</h3>

                    <TicketTaskStats
                        v-if="ticket.tasks?.length"
                        :tasks="ticket.tasks"
                    />

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

                <section class="space-y-2">
                    <h3>Orders</h3>

                    <TicketOrderStats
                        v-if="ticket.orders?.length"
                        :orders="ticket.orders"
                    />

                    <OrderList :orders="ticket.orders" />

                    <CreateAction
                        class="w-full"
                        variant="ghost"
                        size="lg"
                        :href="createOrder(ticket)"
                    >
                        Add new order
                    </CreateAction>
                </section>

                <section class="space-y-2">
                    <h3>Invoice</h3>

                    <InvoiceCard :invoice="ticket.invoice!" embedded />
                </section>
            </div>
        </ShowLayout>
    </AppLayout>
</template>
