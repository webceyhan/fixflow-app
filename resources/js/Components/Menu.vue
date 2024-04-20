<script setup lang="ts">
defineProps<{
  horizontal?: boolean;
}>();
</script>

<template>
  <ul :class="['menu px-1', { 'menu-horizontal': horizontal }]">
    <slot />
  </ul>
</template>
