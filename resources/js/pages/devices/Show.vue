<script setup lang="ts">
import { CreateAction } from '@/components/action';
import DeviceCard from '@/components/devices/DeviceCard.vue';
import DeviceToolbar from '@/components/devices/DeviceToolbar.vue';
import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import TicketList from '@/components/tickets/TicketList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { index, update } from '@/routes/devices';
import { create as createTicket } from '@/routes/tickets';
import type { Device, NavItem } from '@/types';

const props = defineProps<{
    device: Device;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Devices',
        href: index(),
    },
    {
        title: props.device.ref,
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Device Details">
            <DeviceToolbar :device />
        </Heading>

        <ShowLayout>
            <DeviceCard :device />

            <NoteCard :data="device" :href="update(device)" />

            <section class="row-span-2 space-y-2 lg:col-start-2 lg:row-start-1">
                <h3>Tickets</h3>

                <TicketList :tickets="device.tickets" />

                <CreateAction
                    class="w-full"
                    variant="ghost"
                    size="lg"
                    :href="createTicket(device)"
                >
                    Add new ticket
                </CreateAction>
            </section>
        </ShowLayout>
    </AppLayout>
</template>
