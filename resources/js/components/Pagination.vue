<script setup lang="ts">
import { router } from '@inertiajs/vue3';

import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import type { Paginated } from '@/types';

type Props = Paginated<any>;

defineProps<Props>();

const handlePageChange = (page: number) => {
    router.reload({ data: { page } });
};
</script>

<template>
    <Pagination
        v-slot="{ page }"
        :total
        :items-per-page="per_page"
        :default-page="current_page"
        :sibling-count="1"
        show-edges
        @update:page="handlePageChange"
    >
        <PaginationContent v-slot="{ items }">
            <PaginationPrevious />

            <template v-for="(item, index) in items" :key="index">
                <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === page"
                >
                    {{ item.value }}
                </PaginationItem>

                <PaginationEllipsis v-else />
            </template>

            <PaginationNext />
        </PaginationContent>
    </Pagination>
</template>
