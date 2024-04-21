<script setup lang="ts">
/**
 * DiasyUI classes
 */
interface Props {
  title?: string;
  subtitle?: string;
}

defineProps<Props>();
</script>

<template>
  <li>
    <h2 class="menu-title">
      <p v-if="title" class="uppercase text-base-content">
        {{ title }}
      </p>

      {{ subtitle }}
    </h2>

    <ul>
      <slot />
    </ul>
  </li>
</template>
