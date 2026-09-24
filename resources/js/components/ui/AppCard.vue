<template>
  <div
    class="relative rounded-2xl border transition-all duration-300 overflow-hidden"
    :class="[
      surfaceClasses,
      hoverable ? 'hover:-translate-y-1 hover:border-[#D4AF37]/50 hover:shadow-2xl hover:shadow-black/50' : '',
      padded ? 'p-5 sm:p-6' : '',
      goldTopBorder ? 'before:absolute before:top-0 before:left-0 before:right-0 before:h-0.5 before:bg-gradient-to-r before:from-transparent before:via-[#D4AF37] before:to-transparent' : ''
    ]"
  >
    <slot></slot>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    surface?: 'card' | 'elevated' | 'surface' | 'glass';
    hoverable?: boolean;
    padded?: boolean;
    goldTopBorder?: boolean;
  }>(),
  {
    surface: 'card',
    hoverable: false,
    padded: true,
    goldTopBorder: false,
  }
);

const surfaceClasses = computed(() => {
  switch (props.surface) {
    case 'card':
      return 'bg-[var(--bg-card)] border-[var(--border-subtle)] shadow-lg';
    case 'elevated':
      return 'bg-[var(--bg-elevated)] border-[var(--border-subtle)] shadow-xl';
    case 'surface':
      return 'bg-[var(--bg-surface)] border-[var(--border-subtle)]';
    case 'glass':
      return 'bg-[var(--bg-card)]/80 backdrop-blur-xl border-[var(--border-subtle)] shadow-2xl';
    default:
      return 'bg-[var(--bg-card)] border-[var(--border-subtle)]';
  }
});
</script>
