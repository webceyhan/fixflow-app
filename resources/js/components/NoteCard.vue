<script setup lang="ts">
import {
    InputGroup,
    InputGroupAddon,
    InputGroupButton,
    InputGroupTextarea,
} from '@/components/ui/input-group';
import { toUrl } from '@/lib/utils';
import type { Href } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { ArrowUpIcon } from 'lucide-vue-next';

interface Props {
    href: Href;
    data: Record<string, any>;
}

const props = defineProps<Props>();

const form = useForm({
    ...props.data,
    note: props.data.note ?? '',
});
</script>

<template>
    <InputGroup>
        <InputGroupTextarea
            placeholder="Add some notes..."
            v-model="form.note"
        />
        <InputGroupAddon align="block-end">
            <InputGroupButton
                variant="default"
                class="ml-auto rounded-full"
                size="icon-sm"
                :disabled="!form.isDirty"
                @click="form.put(toUrl(href))"
            >
                <ArrowUpIcon />
            </InputGroupButton>
        </InputGroupAddon>
    </InputGroup>
</template>
