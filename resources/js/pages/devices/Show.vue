<script setup lang="ts">
import DeviceCard from '@/components/devices/DeviceCard.vue';
import DeviceToolbar from '@/components/devices/DeviceToolbar.vue';
import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import Placeholder from '@/components/Placeholder.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { toUUID } from '@/lib/utils';
import { index, update } from '@/routes/devices';
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
        title: toUUID(props.device.id, 'DVC'),
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

            <Placeholder
                class="col-start-2 row-span-2 row-start-1 max-lg:hidden"
            />
        </ShowLayout>
    </AppLayout>
</template>
