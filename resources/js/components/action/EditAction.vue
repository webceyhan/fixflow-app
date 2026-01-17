<script setup lang="ts">
import { Button } from '@/components/ui/button';
import type { Href } from '@/types';
import { Link } from '@inertiajs/vue3';
import { PenIcon } from 'lucide-vue-next';

interface Props {
    href: Href;
}

defineProps<Props>();
</script>

<template>
    <Button variant="outline" as-child>
        <Link :href>
            <PenIcon />
            Edit
        </Link>
    </Button>
</template>
