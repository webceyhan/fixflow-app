<script setup lang="ts">
defineProps<{
  label?: string;
  error?: string;
  inline?: boolean;
}>();
</script>

<template>
  <div v-if="inline" class="form-control">
    <label class="label cursor-pointer justify-start gap-3">
      <slot />
      <span class="label-text">{{ label }}</span>
    </label>
  </div>

  <label v-else class="form-control w-full">
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
