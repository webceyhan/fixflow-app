<script setup lang="ts">
import {
    AssigneeFilter,
    FilterGroup,
    MultiSelectFilter,
    RelativeDateFilter,
    SearchFilter,
} from '@/components/filter';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import TicketTable from '@/components/tickets/TicketTable.vue';
import { PRIORITY, TICKET_STATUS } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/tickets';
import type {
    NavItem,
    Paginated,
    Priority,
    Ticket,
    TicketStatus,
} from '@/types';

defineProps<{
    tickets: Paginated<Ticket>;
    filter?: {
        search?: string;
        assignee?: string;
        priority?: Priority;
        status?: TicketStatus;
        created_at?: string;
    };
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Tickets',
        href: index(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Tickets" />

        <IndexLayout>
            <FilterGroup>
                <SearchFilter
                    placeholder="Search tickets ..."
                    :value="filter?.search"
                />

                <AssigneeFilter />

                <MultiSelectFilter
                    name="priority"
                    title="Priority"
                    :options="toOptions(PRIORITY)"
                />

                <MultiSelectFilter
                    name="status"
                    title="Status"
                    :options="toOptions(TICKET_STATUS)"
                />

                <RelativeDateFilter name="created_at" />
            </FilterGroup>

            <TicketTable :tickets="tickets.data" />

            <Pagination v-bind="tickets" />
        </IndexLayout>
    </AppLayout>
</template>
