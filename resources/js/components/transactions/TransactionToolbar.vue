<script setup lang="ts">
import type { Transaction } from '@/types';

import { DeleteAction } from '@/components/action';
import { ButtonGroup } from '@/components/ui/button-group';
import { destroy } from '@/routes/transactions';

type Props = {
    transaction: Transaction;
};

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
