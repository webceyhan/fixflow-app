<script setup lang="ts">
import {
    ChangeAction,
    CreateAction,
    DeleteAction,
    EditAction,
} from '@/components/action';
import { ButtonGroup } from '@/components/ui/button-group';
import { CUSTOMER_STATUS } from '@/constants';
import { toOptions } from '@/lib/utils';
import { destroy, edit, update } from '@/routes/customers';
import { create as createDevice } from '@/routes/devices';
import type { Customer } from '@/types';

interface Props {
    customer: Customer;
}

defineProps<Props>();
</script>

<template>
    <ButtonGroup>
        <ButtonGroup>
            <EditAction :href="edit(customer)" />
            <DeleteAction record-name="customer" :href="destroy(customer)" />
        </ButtonGroup>

        <ButtonGroup>
            <ChangeAction
                name="status"
                :data="customer"
                :href="update(customer)"
                :options="toOptions(CUSTOMER_STATUS)"
            />
        </ButtonGroup>

        <ButtonGroup>
            <CreateAction :href="createDevice(customer)">
                New Device
            </CreateAction>
        </ButtonGroup>
    </ButtonGroup>
</template>
