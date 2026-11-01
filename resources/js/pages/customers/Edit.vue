<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import type { Customer } from '@/types';

import CustomerForm from '@/components/customers/CustomerForm.vue';
import Heading from '@/components/Heading.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/customers';

const props = defineProps<{
    customer: Customer;
}>();

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
            title: 'Edit',
            href: '',
        },
    ],
});
</script>

<template>
    <Heading :title="`Edit ${customer.name}`" />

    <EditLayout>
        <CustomerForm :customer />
    </EditLayout>
</template>
