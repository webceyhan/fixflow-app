<script setup lang="ts">
/**
 * DiasyUI classes
 */
interface Props {
  name: string;
}

defineProps<Props>();
</script>

<template>
  <svg class="fill-current">
    <use :xlink:href="`/icons.svg#${name}`" />
  </svg>
</template>
