<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import TicketForm from '@/components/tickets/TicketForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/devices';
import type { Device, NavItem, Ticket } from '@/types';

const props = defineProps<{
    ticket: Ticket;
    device: Device;
}>();

const title = 'Create Ticket';

const breadcrumbs: NavItem[] = [
    {
        title: 'Devices',
        href: index(),
    },
    {
        title: props.device.ref,
        href: show(props.device),
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
            <TicketForm :ticket />
        </EditLayout>
    </AppLayout>
</template>
