<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import Placeholder from '@/components/Placeholder.vue';
import TicketCard from '@/components/tickets/TicketCard.vue';
import TicketToolbar from '@/components/tickets/TicketToolbar.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { toUUID } from '@/lib/utils';
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

            <Placeholder
                class="col-start-2 row-span-2 row-start-1 max-lg:hidden"
            />
        </ShowLayout>
    </AppLayout>
</template>
