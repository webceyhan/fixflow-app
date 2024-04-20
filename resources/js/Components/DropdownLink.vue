<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

withDefaults(
  defineProps<{
    href: string;
    method?: "post" | "delete";
  }>(),
  {}
);
</script>

<template>
  <li>
    <Link :href="href" :method="method" as="button">
      <slot />
    </Link>
  </li>
</template>
