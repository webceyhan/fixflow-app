<script setup lang="ts">
import CustomerLink from '@/components/customers/CustomerLink.vue';
import { DateTime } from '@/components/data';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    DescriptionList,
    DescriptionListItem,
} from '@/components/ui/description-list';
import type { Device } from '@/types';

import DeviceStatusBadge from './DeviceStatusBadge.vue';
import DeviceTypeBadge from './DeviceTypeBadge.vue';

interface Props {
    device: Device;
}

defineProps<Props>();
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Overview</CardTitle>
        </CardHeader>

        <CardContent>
            <DescriptionList>
                <DescriptionListItem v-if="device.customer" title="Customer">
                    <CustomerLink :customer="device.customer" />
                </DescriptionListItem>

                <DescriptionListItem title="Model">
                    {{ device.model }}
                </DescriptionListItem>

                <DescriptionListItem title="Brand">
                    {{ device.brand }}
                </DescriptionListItem>

                <DescriptionListItem title="Type">
                    <DeviceTypeBadge :value="device.type" />
                </DescriptionListItem>

                <DescriptionListItem v-if="device.color" title="Color">
                    {{ device.color }}
                </DescriptionListItem>

                <DescriptionListItem v-if="device.imei" title="IMEI">
                    {{ device.imei }}
                </DescriptionListItem>

                <DescriptionListItem
                    v-if="device.serial_number"
                    title="Serial Number"
                >
                    {{ device.serial_number }}
                </DescriptionListItem>

                <DescriptionListItem
                    v-if="device.purchase_date"
                    title="Purchase Date"
                >
                    <DateTime :value="device.purchase_date" />
                </DescriptionListItem>

                <DescriptionListItem
                    v-if="device.warranty_expire_date"
                    title="Warranty Expiration"
                >
                    <DateTime :value="device.warranty_expire_date" />
                </DescriptionListItem>

                <DescriptionListItem title="Status">
                    <DeviceStatusBadge :value="device.status" />
                </DescriptionListItem>

                <DescriptionListItem title="Created On">
                    <DateTime :value="device.created_at" />
                </DescriptionListItem>
            </DescriptionList>
        </CardContent>

        <CardFooter>
            <small class="text-muted-foreground">
                Last update on
                <DateTime :value="device.updated_at" full />
            </small>
        </CardFooter>
    </Card>
</template>
