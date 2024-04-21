<script setup lang="ts">
import { onEscaped } from "@/Composables/onEscaped";
import Menu from "@/Components/Menu/Menu.vue";
import Icon from "@/Components/Icon.vue";
import BaseButton from "@/Components/Button/BaseButton.vue";

/**
 * DiasyUI classes
 */
interface Props {
  label?: string;
  ghost?: boolean;
  alignEnd?: boolean;
}

defineProps<Props>();

onEscaped(() => {
  (document.activeElement as HTMLElement)?.blur();
});
</script>

<template>
  <div :class="['dropdown', { 'dropdown-end': alignEnd }]">
    <!-- trigger -->
    <slot name="trigger">
      <BaseButton tabindex="0" v-bind="{ ghost }">
        {{ label }}
        <Icon name="arrow-down" class="-me-0.5 size-4" />
      </BaseButton>
    </slot>

    <!-- content -->
    <Menu tabindex="0" class="dropdown-content z-[1] w-52 shadow">
      <slot />
    </Menu>
  </div>
</template>
