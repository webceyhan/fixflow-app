<script setup lang="ts">
import type { Invoice } from '@/types';

import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/invoices';

type Props = {
    invoice: Invoice;
};

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(invoice)">
        {{ invoice.ref }}
    </TextLink>
</template>
