<script setup lang="ts">
import CustomerCard from '@/components/customers/CustomerCard.vue';
import CustomerToolbar from '@/components/customers/CustomerToolbar.vue';
import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import Placeholder from '@/components/Placeholder.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { toUUID } from '@/lib/utils';
import { index, update } from '@/routes/customers';
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
        title: toUUID(props.customer.id, 'CST'),
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

            <Placeholder
                class="col-start-2 row-span-2 row-start-1 max-lg:hidden"
            />
        </ShowLayout>
    </AppLayout>
</template>
