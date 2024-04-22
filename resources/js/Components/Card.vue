<script setup lang="ts">
/**
 * DiasyUI classes
 */
interface Props {}
</script>

<template>
  <div class="card ring-1 ring-primary/50 shadow-xl">
    <div class="card-body">
      <!-- title -->
      <h2 v-if="$slots.title" class="card-title">
        <slot name="title" />
      </h2>

      <!-- content -->
      <slot />

      <!-- actions -->
      <div v-if="$slots.actions" class="card-actions justify-end">
        <slot name="actions" />
      </div>
    </div>
  </div>
</template>
