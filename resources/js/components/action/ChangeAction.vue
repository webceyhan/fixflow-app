<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { RefreshCwIcon } from 'lucide-vue-next';

import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { Href, Option } from '@/types';

interface Props {
    name: string;
    href: Href;
    data: Record<string, any>;
    options: Option<any>[];
}

defineProps<Props>();
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="outline" class="capitalize">
                <RefreshCwIcon />
                {{ `Change ${name}` }}
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent>
            <DropdownMenuItem
                v-for="{ value, label } in options"
                :disabled="data[name] === value"
                :key="value"
                as-child
            >
                <Link
                    class="w-full"
                    method="put"
                    :href
                    :data="{ ...data, [name]: value }"
                    preserve-state
                >
                    {{ label }}
                </Link>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
