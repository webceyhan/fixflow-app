<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { PlusIcon } from 'lucide-vue-next';

import { Button } from '@/components/ui/button';
import type { Href } from '@/types';

interface Props {
    href: Href;
}

defineProps<Props>();
</script>

<template>
    <Button as-child>
        <Link :href>
            <PlusIcon />
            <slot>Create</slot>
        </Link>
    </Button>
</template>
