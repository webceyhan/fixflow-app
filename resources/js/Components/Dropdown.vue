<script setup lang="ts">
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
      <div tabindex="0" role="button" class="btn m-1">
        {{ label }}
        <svg
          class="ms-2 -me-0.5 h-4 w-4"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path
            fill-rule="evenodd"
            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
            clip-rule="evenodd"
          />
        </svg>
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
