<script setup lang="ts">
import DeviceForm from '@/components/devices/DeviceForm.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/devices';
import type { Device, NavItem } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    device: Device;
}>();

const breadcrumbs = computed<NavItem[]>(() => [
    {
        title: 'Devices',
        href: index(),
    },
    {
        title: props.device.model,
        href: show(props.device),
    },
    {
        title: 'Edit',
        href: '',
    },
]);
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading :title="`Edit ${device.model}`" />

        <EditLayout>
            <DeviceForm :device />
        </EditLayout>
    </AppLayout>
</template>
