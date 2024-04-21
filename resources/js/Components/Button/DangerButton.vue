<script setup lang="ts">
import BaseButton from "@/Components/Button/BaseButton.vue";

/**
 * DiasyUI classes
 */
interface Props {}
</script>

<template>
  <BaseButton class="btn-error">
    <slot />
  </BaseButton>
</template>
