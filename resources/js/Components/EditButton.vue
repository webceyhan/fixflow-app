<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Icon from "@/Components/Icon.vue";

defineProps<{
  href: string;
}>();
</script>

<template>
  <Link :href="href" class="btn btn-primary" as="button">
    <Icon name="pencil" class="size-8" /> <slot />
  </Link>
</template>
