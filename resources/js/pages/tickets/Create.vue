<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import TicketForm from '@/components/tickets/TicketForm.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/devices';
import type { Device, Ticket } from '@/types';

const props = defineProps<{
    ticket: Ticket;
    device: Device;
}>();

const title = 'Create Ticket';

setLayoutProps({
    breadcrumbs: [
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
    ],
});
</script>

<template>
    <Heading :title />

    <EditLayout>
        <TicketForm :ticket />
    </EditLayout>
</template>
