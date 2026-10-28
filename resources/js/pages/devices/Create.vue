<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import DeviceForm from '@/components/devices/DeviceForm.vue';
import Heading from '@/components/Heading.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/customers';
import type { Customer, Device } from '@/types';

const props = defineProps<{
    device: Device;
    customer: Customer;
}>();

const title = 'Create Device';

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Customers',
            href: index(),
        },
        {
            title: props.customer.name,
            href: show(props.customer),
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
        <DeviceForm :device />
    </EditLayout>
</template>
