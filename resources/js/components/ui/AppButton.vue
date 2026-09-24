<template>
  <component
    :is="to ? 'router-link' : href ? 'a' : 'button'"
    :to="to"
    :href="href"
    :type="to || href ? undefined : type"
    :disabled="disabled || loading"
    class="inline-flex items-center justify-center font-semibold transition-all duration-200 select-none cursor-pointer disabled:cursor-not-allowed disabled:opacity-50 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[var(--border-focus)]/50 focus:ring-offset-1 touch-target"
    :class="[
      variantClasses,
      sizeClasses,
      fullWidth ? 'w-full' : '',
      roundedClass
    ]"
    @click="$emit('click', $event)"
  >
    <!-- Loading Spinner -->
    <svg
      v-if="loading"
      class="animate-spin -ml-1 mr-2 h-4 w-4 text-current"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>

    <!-- Icon Prefix Slot -->
    <span v-if="$slots.prefix && !loading" class="mr-2 inline-flex items-center">
      <slot name="prefix"></slot>
    </span>

    <!-- Content Slot -->
    <slot></slot>

    <!-- Icon Suffix Slot -->
    <span v-if="$slots.suffix" class="ml-2 inline-flex items-center">
      <slot name="suffix"></slot>
    </span>
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    variant?: 'gold' | 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger' | 'destructive' | 'success';
    size?: 'sm' | 'md' | 'lg' | 'xl';
    type?: 'button' | 'submit' | 'reset';
    disabled?: boolean;
    loading?: boolean;
    fullWidth?: boolean;
    rounded?: 'sm' | 'md' | 'lg' | 'xl' | 'full';
    to?: string | object;
    href?: string;
  }>(),
  {
    variant: 'gold',
    size: 'md',
    type: 'button',
    disabled: false,
    loading: false,
    fullWidth: false,
    rounded: 'xl',
  }
);

defineEmits<{
  (e: 'click', event: MouseEvent): void;
}>();

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'gold':
      return 'bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-bold hover:brightness-105 shadow-md shadow-[#D4AF37]/20 border border-[#F7E7A9]/40';
    case 'primary':
      return 'bg-[var(--primary-base)] hover:bg-[var(--primary-hover)] text-white shadow-md shadow-blue-500/20';
    case 'secondary':
      return 'bg-[var(--bg-elevated)] hover:bg-[var(--bg-hover)] text-[var(--text-primary)] border border-[var(--border-subtle)] hover:border-[var(--brand-gold)]/50';
    case 'outline':
      return 'bg-transparent text-[var(--brand-gold)] border border-[var(--brand-gold)]/60 hover:bg-[var(--brand-gold-subtle)]';
    case 'ghost':
      return 'bg-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-elevated)]';
    case 'danger':
    case 'destructive':
      return 'bg-[var(--status-error)] hover:opacity-90 text-white shadow-md shadow-rose-500/20';
    case 'success':
      return 'bg-[var(--status-success)] hover:opacity-90 text-white shadow-md shadow-emerald-500/20';
    default:
      return 'bg-[var(--bg-elevated)] text-[var(--text-primary)]';
  }
});

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'px-3 py-1.5 text-xs min-h-[36px]';
    case 'md':
      return 'px-4 py-2.5 text-sm min-h-[44px]';
    case 'lg':
      return 'px-6 py-3 text-base min-h-[48px]';
    case 'xl':
      return 'px-8 py-3.5 text-lg min-h-[52px]';
    default:
      return 'px-4 py-2.5 text-sm min-h-[44px]';
  }
});

const roundedClass = computed(() => {
  switch (props.rounded) {
    case 'sm':
      return 'rounded-md';
    case 'md':
      return 'rounded-lg';
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
