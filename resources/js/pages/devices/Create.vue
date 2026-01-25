<script setup lang="ts">
import DeviceForm from '@/components/devices/DeviceForm.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { show as customerShow } from '@/routes/customers';
import { index } from '@/routes/devices';
import type { Customer, Device, NavItem } from '@/types';

const props = defineProps<{
    device: Device;
    customer: Customer;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Devices',
        href: index(),
    },
    {
        title: props.customer.name,
        href: customerShow(props.customer),
    },
    {
        title: 'Create',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Create Device" />

        <EditLayout>
            <DeviceForm :device />
        </EditLayout>
    </AppLayout>
</template>
