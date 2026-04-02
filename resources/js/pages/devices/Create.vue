<script setup lang="ts">
import DeviceForm from '@/components/devices/DeviceForm.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/customers';
import type { Customer, Device, NavItem } from '@/types';

const props = defineProps<{
    device: Device;
    customer: Customer;
}>();

const title = 'Create Device';

const breadcrumbs: NavItem[] = [
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
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading :title />

        <EditLayout>
            <DeviceForm :device />
        </EditLayout>
    </AppLayout>
</template>
