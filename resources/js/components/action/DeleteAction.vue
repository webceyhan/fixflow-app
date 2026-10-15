<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { TrashIcon } from 'lucide-vue-next';

import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import type { Href } from '@/types';

interface Props {
    href: Href;
    recordName?: string;
}

const props = defineProps<Props>();

function handleDelete() {
    router.delete(props.href);
}
</script>

<template>
    <AlertDialog>
        <AlertDialogTrigger as-child>
            <Button variant="outline">
                <TrashIcon />
                Delete
            </Button>
        </AlertDialogTrigger>

        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle> Are you absolutely sure? </AlertDialogTitle>
                <AlertDialogDescription>
                    This action cannot be undone. This will permanently delete
                    this {{ recordName ?? 'record' }}.
                </AlertDialogDescription>
            </AlertDialogHeader>

            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <AlertDialogAction @click="handleDelete">
                    Delete
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
