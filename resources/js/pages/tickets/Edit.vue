<script setup lang="ts">
import { computed } from 'vue';

import type { NavItem, Ticket } from '@/types';

import Heading from '@/components/Heading.vue';
import TicketForm from '@/components/tickets/TicketForm.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/tickets';

const props = defineProps<{
    ticket: Ticket;
}>();

const breadcrumbs = computed<NavItem[]>(() => [
    {
        title: 'Tickets',
        href: index(),
    },
    {
        title: props.ticket.ref,
        href: show(props.ticket),
    },
    {
        title: 'Edit',
        href: '',
    },
]);
</script>

<template>
    <Heading title="Edit Ticket" />

    <EditLayout>
        <TicketForm :ticket />
    </EditLayout>
</template>
