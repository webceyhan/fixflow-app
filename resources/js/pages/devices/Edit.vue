<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import DeviceForm from '@/components/devices/DeviceForm.vue';
import Heading from '@/components/Heading.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/devices';
import type { Device } from '@/types';

const props = defineProps<{
    device: Device;
}>();

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
            title: 'Edit',
            href: '',
        },
    ],
});
</script>

<template>
    <Heading title="Edit Device" />

    <EditLayout>
        <DeviceForm :device />
    </EditLayout>
</template>
