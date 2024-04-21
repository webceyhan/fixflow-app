<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

/**
 * DiasyUI classes
 */
interface Props {
  href?: string;
  label?: string;
  active?: boolean;
  outline?: boolean;
  ghost?: boolean;
}

const props = defineProps<Props>();

const classes = computed(() => [
  "btn",
  "text-xs uppercase tracking-wide",
  {
    active: props.active,
    "btn-outline": props.outline,
    "btn-ghost": props.ghost,
  },
]);
</script>

<template>
  <Link v-if="href" as="button" :href="href" :class="classes">
    <slot>{{ label }}</slot>
  </Link>

  <button v-else :class="classes">
    <slot>{{ label }}</slot>
  </button>
</template>
