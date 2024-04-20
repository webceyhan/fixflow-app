<script setup lang="ts">
import Icon from "@/Components/Icon.vue";

defineProps<{
  label?: string;
  alignEnd?: boolean;
  contentClasses?: string;
}>();
</script>

<template>
  <div :class="['dropdown', { 'dropdown-end': alignEnd }]">
    <!-- trigger -->
    <slot name="trigger">
      <div tabindex="0" role="button" class="btn">
        {{ label }}
        <Icon name="arrow-down" class="ms-2 -me-0.5 h-4 w-4" />
      </div>
    </slot>

    <!-- content -->
    <ul
      tabindex="0"
      class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52"
      :class="contentClasses"
    >
      <slot />
    </ul>
  </div>
</template>
