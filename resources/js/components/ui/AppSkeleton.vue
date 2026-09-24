<template>
  <div
    class="animate-pulse bg-[var(--bg-elevated)]"
    :class="[
      roundedClass,
      heightClass,
      widthClass
    ]"
  ></div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    width?: string;
    height?: string;
    rounded?: 'sm' | 'md' | 'lg' | 'xl' | 'full';
  }>(),
  {
    rounded: 'lg',
  }
);

const widthClass = computed(() => props.width || 'w-full');
const heightClass = computed(() => props.height || 'h-4');

const roundedClass = computed(() => {
  switch (props.rounded) {
    case 'sm':
      return 'rounded-sm';
    case 'md':
      return 'rounded-md';
    case 'lg':
      return 'rounded-xl';
    case 'xl':
      return 'rounded-2xl';
    case 'full':
      return 'rounded-full';
    default:
      return 'rounded-xl';
  }
});
</script>
