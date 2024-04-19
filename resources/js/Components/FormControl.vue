<script setup lang="ts">
defineProps<{
  label?: string;
  error?: string;
}>();
</script>

<template>
  <label class="form-control w-full">
    <!-- label -->
    <div v-if="label" class="label">
      <span class="label-text">{{ label }}</span>
    </div>

    <!-- input -->
    <slot />

    <!-- error -->
    <div v-if="error" class="label">
      <span class="label-text-alt text-error">{{ error }}</span>
    </div>
  </label>
</template>
