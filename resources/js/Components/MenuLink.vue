<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

/**
 * DiasyUI classes
 */
interface Props {
  href: string;
  label?: string;
  active?: boolean;
}

defineOptions({ inheritAttrs: false });

defineProps<Props>();
</script>

<template>
  <li class="w-full">
    <Link
      :href="href"
      :class="{ active }"
      :as="$attrs.method ? 'button' : 'a'"
      v-bind="$attrs"
    >
      <slot> {{ label }} </slot>
    </Link>
  </li>
</template>
