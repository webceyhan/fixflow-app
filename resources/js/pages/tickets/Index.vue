<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import type { Paginated, Ticket } from '@/types';

import AssigneeFilter from '@/components/filters/AssigneeFilter.vue';
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import TicketTable from '@/components/tickets/TicketTable.vue';
import { PRIORITY, TICKET_STATUS } from '@/constants';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/tickets';

defineProps<{
    tickets: Paginated<Ticket>;
}>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Tickets',
            href: index(),
        },
    ],
});
</script>

<template>
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
</template>
