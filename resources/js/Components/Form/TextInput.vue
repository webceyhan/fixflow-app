<script setup lang="ts">
import { onMounted, ref } from "vue";

/**
 * DiasyUI classes
 */
interface Props {}

const model = defineModel<string>({ required: true });

const input = ref<HTMLInputElement | null>(null);

defineExpose({ focus: () => input.value?.focus() });

onMounted(() => {
  if (input.value?.hasAttribute("autofocus")) {
    input.value?.focus();
  }
});
</script>

<template>
  <input ref="input" type="text" class="input input-bordered w-full" v-model="model" />
</template>
