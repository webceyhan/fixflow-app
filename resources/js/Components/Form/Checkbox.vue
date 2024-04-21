<script setup lang="ts">
/**
 * DiasyUI classes
 */
interface Props {}

const model = defineModel<boolean>({ required: true });
</script>

<template>
  <input type="checkbox" class="checkbox" v-model="model" />
</template>
