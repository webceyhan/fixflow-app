<script setup lang="ts">
import { DeleteAction } from '@/components/action';
import { ButtonGroup } from '@/components/ui/button-group';
import { destroy } from '@/routes/transactions';
import type { Transaction } from '@/types';

interface Props {
    transaction: Transaction;
}

defineProps<Props>();
</script>

<template>
    <ButtonGroup>
        <ButtonGroup>
            <DeleteAction
                record-name="transaction"
                :href="destroy(transaction)"
            />
        </ButtonGroup>
    </ButtonGroup>
</template>
