<script setup lang="ts">
import DeviceTable from '@/components/devices/DeviceTable.vue';
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import { DEVICE_STATUS, DEVICE_TYPE } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/devices';
import type { Device, NavItem, Paginated } from '@/types';

defineProps<{
    devices: Paginated<Device>;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Devices',
        href: index(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Devices" />

        <IndexLayout>
            <FilterContainer>
                <SearchFilter placeholder="Search devices..." />

                <MultiSelectFilter
                    name="type"
                    title="Type"
                    :options="toOptions(DEVICE_TYPE)"
                />

                <MultiSelectFilter
                    name="status"
                    title="Status"
                    :options="toOptions(DEVICE_STATUS)"
                />

                <DatePresetFilter name="created_at" />
            </FilterContainer>

            <DeviceTable :devices="devices.data" />

            <Pagination v-bind="devices" />
        </IndexLayout>
    </AppLayout>
</template>
