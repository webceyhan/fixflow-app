<script setup lang="ts">
import { Button } from '@/components/ui/button';
import type { Href } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BriefcaseIcon } from 'lucide-vue-next';

interface Props {
    href: Href;
    data: Record<string, any>;
}

defineProps<Props>();
</script>

<template>
    <Button
        v-if="data.assignee_id != $page.props.auth.user.id"
        variant="outline"
        as-child
    >
        <Link
            method="put"
            :href
            :data="{ ...data, assignee_id: $page.props.auth.user.id }"
        >
            <BriefcaseIcon />
            Assign to me
        </Link>
    </Button>

    <Button
        v-if="data.assignee_id === $page.props.auth.user.id"
        variant="outline"
        as-child
    >
        <Link method="put" :href :data="{ ...data, assignee_id: null }">
            <BriefcaseIcon />
            Unassign
        </Link>
    </Button>
</template>
