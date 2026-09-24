<template>
  <span
    class="inline-flex items-center gap-1.5 font-medium rounded-full border tracking-wide select-none"
    :class="[variantClasses, sizeClasses]"
  >
    <span v-if="dot" class="w-1.5 h-1.5 rounded-full" :class="[dotColorClass, pulse ? 'animate-pulse' : '']"></span>
    <slot></slot>
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    variant?: 'gold' | 'blue' | 'green' | 'rose' | 'amber' | 'purple' | 'slate' | 'success' | 'warning' | 'error' | 'info';
    size?: 'sm' | 'md';
    dot?: boolean;
    pulse?: boolean;
  }>(),
  {
    variant: 'gold',
    size: 'sm',
    dot: false,
    pulse: false,
  }
);

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'gold':
      return 'bg-[var(--badge-bg)] text-[var(--brand-gold-text)] border-[var(--badge-border)]';
    case 'blue':
    case 'info':
      return 'bg-[var(--status-info-bg)] text-[var(--status-info-text)] border-[var(--status-info-border)]';
    case 'green':
    case 'success':
      return 'bg-[var(--status-success-bg)] text-[var(--status-success-text)] border-[var(--status-success-border)]';
    case 'rose':
    case 'error':
      return 'bg-[var(--status-error-bg)] text-[var(--status-error-text)] border-[var(--status-error-border)]';
    case 'amber':
    case 'warning':
      return 'bg-[var(--status-warning-bg)] text-[var(--status-warning-text)] border-[var(--status-warning-border)]';
    case 'purple':
      return 'bg-purple-500/10 text-purple-500 dark:text-purple-300 border-purple-500/25';
    case 'slate':
      return 'bg-[var(--bg-elevated)] text-[var(--text-secondary)] border-[var(--border-subtle)]';
    default:
      return 'bg-[var(--badge-bg)] text-[var(--brand-gold-text)] border-[var(--badge-border)]';
  }
});

const dotColorClass = computed(() => {
  switch (props.variant) {
    case 'gold':
      return 'bg-[var(--brand-gold)]';
    case 'blue':
    case 'info':
      return 'bg-[var(--status-info)]';
    case 'green':
    case 'success':
      return 'bg-[var(--status-success)]';
    case 'rose':
    case 'error':
      return 'bg-[var(--status-error)]';
    case 'amber':
    case 'warning':
      return 'bg-[var(--status-warning)]';
    case 'purple':
      return 'bg-purple-500';
    default:
      return 'bg-[var(--text-muted)]';
  }
});

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'px-2.5 py-0.5 text-[11px]';
    case 'md':
      return 'px-3 py-1 text-xs';
    default:
      return 'px-2.5 py-0.5 text-[11px]';
  }
});
</script>
