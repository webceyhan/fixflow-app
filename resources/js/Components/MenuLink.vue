<script lang="ts">
export default {
  inheritAttrs: false,
};
</script>

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

defineProps<{
  href: string;
  active?: boolean;
}>();
</script>

<template>
  <li>
    <Link :href="href" :class="{ active }" v-bind="$attrs">
      <slot />
    </Link>
  </li>
</template>
