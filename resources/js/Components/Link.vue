<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

/**
 * DiasyUI classes
 */
interface Props {
  href: string;
  label?: string;
  native?: boolean;
}

defineProps<Props>();
</script>

<template>
  <component :is="native ? 'a' : Link" :href="href" class="link link-hover">
    <slot> {{ label }} </slot>
  </component>
</template>
