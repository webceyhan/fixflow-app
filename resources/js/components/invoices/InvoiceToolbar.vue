<script setup lang="ts">
import { ChangeAction, CreateAction, EditAction } from '@/components/action';
import { ButtonGroup } from '@/components/ui/button-group';
import { INVOICE_STATUS } from '@/constants';
import { toOptions } from '@/lib/utils';
import { edit, update } from '@/routes/invoices';
import { create as createTransaction } from '@/routes/transactions';
import type { Invoice } from '@/types';

interface Props {
    invoice: Invoice;
}

defineProps<Props>();
</script>

<template>
    <ButtonGroup>
        <ButtonGroup>
            <EditAction :href="edit(invoice)" />
        </ButtonGroup>

        <ButtonGroup>
            <ChangeAction
                name="status"
                :data="invoice"
                :href="update(invoice)"
                :options="toOptions(INVOICE_STATUS)"
            />
        </ButtonGroup>

        <ButtonGroup>
            <CreateAction :href="createTransaction(invoice)">
                New Transaction
            </CreateAction>
        </ButtonGroup>
    </ButtonGroup>
</template>
