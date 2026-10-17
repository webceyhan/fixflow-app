<script setup lang="ts">
import { computed } from 'vue';

import DeviceForm from '@/components/devices/DeviceForm.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/devices';
import type { Device, NavItem } from '@/types';

const props = defineProps<{
    device: Device;
}>();

const breadcrumbs = computed<NavItem[]>(() => [
    {
        title: 'Devices',
        href: index(),
    },
    {
        title: props.device.ref,
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
        <Heading title="Edit Device" />

        <EditLayout>
            <DeviceForm :device />
        </EditLayout>
    </AppLayout>
</template>
