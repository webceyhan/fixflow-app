<script setup lang="ts">
import type { Adjustment } from '@/types';

import { DeleteAction } from '@/components/action';
import { ButtonGroup } from '@/components/ui/button-group';
import { destroy } from '@/routes/adjustments';

type Props = {
    adjustment: Adjustment;
};

defineProps<Props>();
</script>

<template>
    <ButtonGroup>
        <ButtonGroup>
            <DeleteAction
                record-name="adjustment"
                :href="destroy(adjustment)"
            />
        </ButtonGroup>
    </ButtonGroup>
</template>
