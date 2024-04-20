<script setup lang="ts">
import { computed, onMounted, onUnmounted, watch } from "vue";
import Icon from "@/Components/Icon.vue";

const emit = defineEmits(["update:open"]);

const props = withDefaults(
  defineProps<{
    open?: boolean;
    closeable?: boolean;
  }>(),
  {
    closeable: true,
  }
);

const proxyOpen = computed({
  get: () => props.open,
  set: (value: boolean) => emit("update:open", value),
});

const closeOnEscape = (e: KeyboardEvent) => {
  if (e.key === "Escape" && props.open) {
    proxyOpen.value = false;
  }
};

const toggleOverflow = (on: boolean) => {
  document.body.style.overflow = on ? "visible" : "hidden";
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));

onUnmounted(() => {
  document.removeEventListener("keydown", closeOnEscape);
  toggleOverflow(true);
});

watch(
  () => props.open,
  () => toggleOverflow(!props.open)
);
</script>

<template>
  <Teleport to="body">
    <dialog class="modal modal-bottom sm:modal-middle" :open="proxyOpen">
      <div class="modal-box">
        <!-- close button -->
        <form v-if="closeable" method="dialog">
          <button
            class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
            @click="emit('update:open', false)"
          >
            <Icon name="close" class="w-6 h-6" />
          </button>
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
