<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { cn } from "@/lib/utils"

const props = defineProps<{
  class?: HTMLAttributes["class"]
  variant?: "legend" | "label"
}>()
</script>

<template>
  <legend
    data-slot="field-legend"
    :data-variant="variant ?? 'legend'"
    :class="cn(
      'mb-3 font-medium',
      'data-[variant=legend]:text-lg',
      'data-[variant=label]:text-sm',
      props.class,
    )"
  >
    <slot />
  </legend>
</template>
