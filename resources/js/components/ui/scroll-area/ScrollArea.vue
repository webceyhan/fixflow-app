<script setup lang="ts">
import type { ScrollAreaRootProps } from "reka-ui"
import type { HTMLAttributes } from "vue"
import { reactiveOmit } from "@vueuse/core"
import {
  ScrollAreaCorner,
  ScrollAreaRoot,
  ScrollAreaScrollbar,
  ScrollAreaThumb,
  ScrollAreaViewport,
} from "reka-ui"
import { cn } from "@/lib/utils"

const props = defineProps<ScrollAreaRootProps & {
  class?: HTMLAttributes["class"]
  viewportClass?: HTMLAttributes["class"]
}>()

const delegatedProps = reactiveOmit(props, "class", "viewportClass")
</script>

<template>
  <ScrollAreaRoot
    data-slot="scroll-area"
    v-bind="delegatedProps"
    :class="cn('relative overflow-hidden', props.class)"
  >
    <ScrollAreaViewport
      data-slot="scroll-area-viewport"
      :class="cn('size-full rounded-[inherit]', props.viewportClass)"
    >
      <slot />
    </ScrollAreaViewport>

    <ScrollAreaScrollbar
      data-slot="scroll-area-scrollbar"
      orientation="vertical"
      class="flex touch-none select-none border-l border-l-transparent p-px transition-colors data-[orientation=vertical]:h-full data-[orientation=vertical]:w-2.5"
    >
      <ScrollAreaThumb
        data-slot="scroll-area-thumb"
        class="relative flex-1 rounded-full bg-border"
      />
    </ScrollAreaScrollbar>

    <ScrollAreaCorner
      data-slot="scroll-area-corner"
      class="bg-border"
    />
  </ScrollAreaRoot>
</template>
