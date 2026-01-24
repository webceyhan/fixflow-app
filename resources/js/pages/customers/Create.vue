<script setup lang="ts">
import CustomerForm from '@/components/customers/CustomerForm.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index } from '@/routes/customers';
import type { Customer, NavItem } from '@/types';

defineProps<{
    customer: Customer;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Customers',
        href: index(),
    },
    {
        title: 'Create',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Create Customer" />

        <EditLayout>
            <CustomerForm :customer />
        </EditLayout>
    </AppLayout>
</template>
