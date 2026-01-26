<script setup lang="ts">
import {
    AssignAction,
    ChangeAction,
    DeleteAction,
    EditAction,
} from '@/components/action';
import { ButtonGroup } from '@/components/ui/button-group';
import { TICKET_STATUS } from '@/constants';
import { toOptions } from '@/lib/utils';
import { destroy, edit, update } from '@/routes/tickets';
import type { Ticket } from '@/types';

interface Props {
    ticket: Ticket;
}

defineProps<Props>();
</script>

<template>
    <ButtonGroup>
        <ButtonGroup>
            <EditAction :href="edit(ticket)" />
            <DeleteAction record-name="ticket" :href="destroy(ticket)" />
        </ButtonGroup>

        <ButtonGroup>
            <AssignAction :href="update(ticket)" :data="ticket" />
        </ButtonGroup>

        <ButtonGroup>
            <ChangeAction
                name="status"
                :data="ticket"
                :href="update(ticket)"
                :options="toOptions(TICKET_STATUS)"
            />
        </ButtonGroup>
    </ButtonGroup>
</template>
