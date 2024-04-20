<script setup lang="ts">
defineProps({
  id: String,
});
</script>

<template>
  <div class="drawer lg:drawer-open">
    <!-- internal toggle -->
    <input :id="id" type="checkbox" class="drawer-toggle" />

    <div class="drawer-content">
      <!-- Page content here -->
      <slot />
    </div>

    <aside class="drawer-side">
      <!-- backdrop toggle -->
      <label :for="id" aria-label="close sidebar" class="drawer-overlay" />

      <div class="p-4 w-80 min-h-full bg-base-200 text-base-content flex flex-col">
        <slot name="aside" />
      </div>
    </aside>
  </div>
</template>
