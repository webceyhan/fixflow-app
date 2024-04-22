<script setup lang="ts">
import { onUnmounted, watch } from "vue";
import { onEscaped } from "@/Composables/onEscaped";
import Icon from "@/Components/Icon.vue";
import BaseButton from "./Button/BaseButton.vue";

/**
 * DiasyUI classes
 */
interface Props {
  wide?: boolean;
  closeable?: boolean;
}

withDefaults(defineProps<Props>(), {
  closeable: true,
});

const open = defineModel<boolean>("open", { default: false });

const toggleOverflow = (on: boolean) => {
  document.body.style.overflow = on ? "visible" : "hidden";
};

watch(
  () => open.value,
  () => toggleOverflow(!open.value)
);

onEscaped(() => (open.value = false));

onUnmounted(() => toggleOverflow(true));
</script>

<template>
  <Teleport to="body">
    <dialog class="modal max-sm:modal-bottom" :open="open">
      <div
        :class="[
          'modal-box bg-base-content/5',
          'backdrop-blur-lg drop-shadow-lg',
          { 'w-10/12 max-w-5xl': wide },
        ]"
      >
        <!-- close button -->
        <form v-if="closeable" method="dialog">
          <BaseButton
            class="absolute right-2 top-2"
            @click="open = false"
            small
            circle
            ghost
          >
            <Icon name="close" class="size-6" />
          </BaseButton>
        </form>

        <!-- title -->
        <h3 v-if="$slots.title" class="font-bold text-lg">
          <slot name="title" />
        </h3>

        <!-- content -->
        <div class="space-y-4 py-4">
          <slot />
        </div>

        <!-- actions -->
        <div v-if="$slots.actions" class="modal-action">
          <slot name="actions" />
        </div>
      </div>
    </dialog>
  </Teleport>
</template>
