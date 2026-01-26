<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import TicketForm from '@/components/tickets/TicketForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { show as deviceShow } from '@/routes/devices';
import { index } from '@/routes/tickets';
import type { Device, NavItem, Ticket } from '@/types';

const props = defineProps<{
    ticket: Ticket;
    device: Device;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Tickets',
        href: index(),
    },
    {
        title: props.device.model,
        href: deviceShow(props.device),
    },
    {
        title: 'Create',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Create Ticket" />

        <EditLayout>
            <TicketForm :ticket />
        </EditLayout>
    </AppLayout>
</template>
