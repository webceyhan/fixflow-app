<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { toUUID } from '@/lib/utils';
import { show } from '@/routes/invoices';
import type { Invoice } from '@/types';

interface Props {
    invoice: Invoice;
}

const props = defineProps<Props>();
</script>

<template>
    <TextLink :href="show(invoice)">
        {{ toUUID(invoice.id, 'INV') }}
    </TextLink>
</template>
