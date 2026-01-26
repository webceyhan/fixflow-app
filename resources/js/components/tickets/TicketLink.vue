<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/tickets';
import type { Ticket } from '@/types';

interface Props {
    ticket: Ticket;
}

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(ticket)">
        {{ ticket.title }}
    </TextLink>
</template>
