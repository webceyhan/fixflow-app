<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Icon from "@/Components/Icon.vue";

defineProps<{
  href: string;
}>();
</script>

<template>
  <Link :href="href" class="btn btn-accent" method="delete" as="button">
    <Icon name="trash" class="size-8" /> <slot />
  </Link>
</template>
