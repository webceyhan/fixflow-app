<script setup lang="ts">
/**
 * DiasyUI classes
 */
interface Props {
  horizontal?: boolean;
}

defineProps<Props>();
</script>

<template>
  <ul :class="['menu bg-base-200 rounded-box', { 'menu-horizontal': horizontal }]">
    <slot />
  </ul>
</template>
