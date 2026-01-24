<script setup lang="ts">
import CustomerForm from '@/components/customers/CustomerForm.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/customers';
import type { Customer, NavItem } from '@/types';

const props = defineProps<{
    customer: Customer;
}>();

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
        title: 'Edit',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading :title="`Edit ${customer.name}`" />

        <EditLayout>
            <CustomerForm :customer />
        </EditLayout>
    </AppLayout>
</template>
