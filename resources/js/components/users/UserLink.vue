<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/users';
import type { User } from '@/types';

interface Props {
    user?: User;
    placeholder?: string;
}

defineProps<Props>();
</script>

<template>
    <TextLink v-if="user" :href="show(user)">
        {{ user.name }}
    </TextLink>
    <span v-else class="text-muted-foreground">
        {{ placeholder ?? '-' }}
    </span>
</template>
