<script setup lang="ts">
defineProps<{
  name: string;
}>();
</script>

<template>
  <svg class="fill-current">
    <use :xlink:href="`/icons.svg#${name}`" />
  </svg>
</template>
