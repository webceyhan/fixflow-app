<script setup lang="ts">
import { computed } from 'vue';

import Heading from '@/components/Heading.vue';
import TicketForm from '@/components/tickets/TicketForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/tickets';
import type { NavItem, Ticket } from '@/types';

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
    <AppLayout :breadcrumbs>
        <Heading title="Edit Ticket" />

        <EditLayout>
            <TicketForm :ticket />
        </EditLayout>
    </AppLayout>
</template>
