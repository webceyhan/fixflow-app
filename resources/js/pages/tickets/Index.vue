<script setup lang="ts">
import AssigneeFilter from '@/components/filters/AssigneeFilter.vue';
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import TicketTable from '@/components/tickets/TicketTable.vue';
import { PRIORITY, TICKET_STATUS } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/tickets';
import type { NavItem, Paginated, Ticket } from '@/types';

defineProps<{
    tickets: Paginated<Ticket>;
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
            <FilterContainer>
                <SearchFilter placeholder="Search tickets..." />

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

                <DatePresetFilter name="created_at" />
            </FilterContainer>

            <TicketTable :tickets="tickets.data" />

            <Pagination v-bind="tickets" />
        </IndexLayout>
    </AppLayout>
</template>
