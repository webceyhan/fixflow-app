<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import TicketForm from '@/components/tickets/TicketForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/tickets';
import type { NavItem, Ticket } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    ticket: Ticket;
}>();

const breadcrumbs = computed<NavItem[]>(() => [
    {
        title: 'Tickets',
        href: index(),
    },
    {
        title: props.ticket.title,
        href: show(props.ticket),
    },
    {
        title: 'Edit',
        href: '',
    },
]);
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading :title="`Edit ${ticket.title}`" />

        <EditLayout>
            <TicketForm :ticket />
        </EditLayout>
    </AppLayout>
</template>
