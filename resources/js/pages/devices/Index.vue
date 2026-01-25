<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import DeviceTable from '@/components/devices/DeviceTable.vue';
import {
    FilterGroup,
    MultiSelectFilter,
    RelativeDateFilter,
    SearchFilter,
} from '@/components/filter';
import { DEVICE_STATUS, DEVICE_TYPE } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/devices';
import type {
    Device,
    DeviceStatus,
    DeviceType,
    NavItem,
    Paginated,
} from '@/types';

defineProps<{
    devices: Paginated<Device>;
    filter?: {
        search?: string;
        type?: DeviceType;
        status?: DeviceStatus;
        created_at?: string;
    };
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
            <FilterGroup>
                <SearchFilter
                    placeholder="Search devices ..."
                    :value="filter?.search"
                />

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

                <RelativeDateFilter name="created_at" />
            </FilterGroup>

            <DeviceTable :devices="devices.data" />

            <Pagination v-bind="devices" />
        </IndexLayout>
    </AppLayout>
</template>
