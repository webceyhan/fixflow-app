<script setup lang="ts">
import {
    AssignAction,
    ChangeAction,
    DeleteAction,
    EditAction,
} from '@/components/action';
import { ButtonGroup } from '@/components/ui/button-group';
import { ORDER_STATUS } from '@/constants';
import { toOptions } from '@/lib/utils';
import { destroy, edit, update } from '@/routes/orders';
import type { Order } from '@/types';

interface Props {
    order: Order;
}

defineProps<Props>();
</script>

<template>
    <ButtonGroup>
        <ButtonGroup>
            <EditAction :href="edit(order)" />
            <DeleteAction record-name="order" :href="destroy(order)" />
        </ButtonGroup>

        <ButtonGroup>
            <AssignAction :href="update(order)" :data="order" />
        </ButtonGroup>

        <ButtonGroup>
            <ChangeAction
                name="status"
                :data="order"
                :href="update(order)"
                :options="toOptions(ORDER_STATUS)"
            />
        </ButtonGroup>
    </ButtonGroup>
</template>
