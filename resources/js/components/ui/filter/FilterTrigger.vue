<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import type { Icon } from '@/types';
import { FilterIcon } from 'lucide-vue-next';
import FilterFacade from './FilterFacade.vue';

interface Props {
    title: string;
    icon?: Icon;
    facades: string[];
}

defineProps<Props>();
</script>

<template>
    <Button class="border-dashed" variant="outline">
        <!-- icon -->
        <component class="opacity-50" :is="icon ?? FilterIcon" />

        <!-- title -->
        {{ title }}

        <!-- facades -->
        <template v-if="facades.length > 0">
            <Separator class="mx-1 h-4" orientation="vertical" />

            <div class="-mx-1">
                <!-- mobile-only -->
                <FilterFacade class="lg:hidden">
                    {{ facades.length }}
                </FilterFacade>

                <!-- desktop-only -->
                <div class="flex gap-x-1 max-lg:hidden">
                    <FilterFacade v-if="facades.length > 2">
                        {{ facades.length }} selected
                    </FilterFacade>

                    <template v-else>
                        <FilterFacade v-for="facade in facades">
                            {{ facade }}
                        </FilterFacade>
                    </template>
                </div>
            </div>
        </template>
    </Button>
</template>
