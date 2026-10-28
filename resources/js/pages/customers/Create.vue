<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import CustomerForm from '@/components/customers/CustomerForm.vue';
import Heading from '@/components/Heading.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index } from '@/routes/customers';
import type { Customer } from '@/types';

defineProps<{
    customer: Customer;
}>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Customers',
            href: index(),
        },
        {
            title: 'Create',
            href: '',
        },
    ],
});
</script>

<template>
    <Heading title="Create Customer" />

    <EditLayout>
        <CustomerForm :customer />
    </EditLayout>
</template>
