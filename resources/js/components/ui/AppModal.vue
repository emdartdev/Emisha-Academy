<template>
  <teleport to="body">
    <transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="modelValue"
        class="fixed inset-0 z-[999] flex items-center justify-center p-3 sm:p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto safe-top safe-bottom"
        @click.self="closeOnBackdrop && close()"
      >
        <transition
          enter-active-class="ease-out duration-300"
          enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          enter-to-class="opacity-100 translate-y-0 sm:scale-100"
          leave-active-class="ease-in duration-200"
          leave-from-class="opacity-100 translate-y-0 sm:scale-100"
          leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
          <div
            v-if="modelValue"
            class="relative w-full rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] shadow-2xl overflow-hidden my-auto max-h-[90dvh] flex flex-col"
            :class="sizeClasses"
          >
            <!-- Gold Accent Header Line -->
            <div class="h-0.5 w-full bg-gradient-to-r from-transparent via-[#D4AF37] to-transparent shrink-0"></div>

            <!-- Header -->
            <div v-if="title || $slots.header" class="flex items-center justify-between px-4 sm:px-6 py-3.5 sm:py-4 border-b border-[var(--border-subtle)] shrink-0">
              <slot name="header">
                <h3 class="text-sm sm:text-base font-bold text-[var(--text-primary)] truncate">{{ title }}</h3>
              </slot>
              <button
                @click="close"
                class="p-2 rounded-xl text-[var(--text-muted)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-elevated)] transition-colors touch-target flex items-center justify-center cursor-pointer"
                aria-label="Close modal"
              >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>

            <!-- Body -->
            <div class="px-4 sm:px-6 py-4 sm:py-5 overflow-y-auto flex-1">
              <slot></slot>
            </div>

            <!-- Footer -->
            <div v-if="$slots.footer" class="px-4 sm:px-6 py-3.5 sm:py-4 bg-[var(--bg-surface)] border-t border-[var(--border-subtle)] flex items-center justify-end gap-2.5 sm:gap-3 shrink-0">
              <slot name="footer"></slot>
            </div>
          </div>
        </transition>
      </div>
    </transition>
  </teleport>
</template>

<script setup lang="ts">
import { computed, watch, onMounted, onUnmounted } from 'vue';

const props = withDefaults(
  defineProps<{
    modelValue: boolean;
    title?: string;
    size?: 'sm' | 'md' | 'lg' | 'xl' | 'full';
    closeOnBackdrop?: boolean;
    closeOnEscape?: boolean;
  }>(),
  {
    modelValue: false,
    size: 'md',
    closeOnBackdrop: true,
    closeOnEscape: true,
  }
);

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void;
  (e: 'close'): void;
}>();

const close = () => {
  emit('update:modelValue', false);
  emit('close');
};

const handleKeyDown = (e: KeyboardEvent) => {
  if (props.closeOnEscape && e.key === 'Escape' && props.modelValue) {
    close();
  }
};

onMounted(() => window.addEventListener('keydown', handleKeyDown));
onUnmounted(() => window.removeEventListener('keydown', handleKeyDown));

watch(
  () => props.modelValue,
  (val) => {
    if (val) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = '';
    }
  }
);

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'max-w-md';
    case 'md':
      return 'max-w-lg';
    case 'lg':
      return 'max-w-2xl';
    case 'xl':
      return 'max-w-4xl';
    case 'full':
      return 'max-w-6xl';
    default:
      return 'max-w-lg';
  }
});
</script>
