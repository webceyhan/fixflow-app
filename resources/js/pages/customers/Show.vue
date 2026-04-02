<script setup lang="ts">
import { CreateAction } from '@/components/action';
import CustomerCard from '@/components/customers/CustomerCard.vue';
import CustomerToolbar from '@/components/customers/CustomerToolbar.vue';
import DeviceList from '@/components/devices/DeviceList.vue';
import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { index, update } from '@/routes/customers';
import { create as createDevice } from '@/routes/devices';
import type { Customer, NavItem } from '@/types';

const props = defineProps<{
    customer: Customer;
    isAdmin?: boolean;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Customers',
        href: index(),
    },
    {
        title: props.customer.name,
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Customer Details">
            <CustomerToolbar v-if="isAdmin" :customer />
        </Heading>

        <ShowLayout>
            <CustomerCard :customer />

            <NoteCard :data="customer" :href="update(customer)" />

            <section class="row-span-2 space-y-2 lg:col-start-2 lg:row-start-1">
                <h3>Devices</h3>

                <DeviceList :devices="customer.devices" />

                <CreateAction
                    class="w-full"
                    variant="ghost"
                    size="lg"
                    :href="createDevice(customer)"
                >
                    Add new device
                </CreateAction>
            </section>
        </ShowLayout>
    </AppLayout>
</template>
