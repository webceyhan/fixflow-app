<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { X } from 'lucide-vue-next';
</script>

<template>
    <div class="flex flex-wrap items-center gap-3">
        <slot />

        <Button v-if="$page.props?.filter" variant="ghost" size="icon" as-child>
            <Link
                :href="$page.url"
                :data="{
                    page: undefined,
                    filter: undefined,
                }"
            >
                <X />
            </Link>
        </Button>
    </div>
</template>
