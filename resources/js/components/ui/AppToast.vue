<template>
  <div class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-4">
    <transition-group
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-4 opacity-0 scale-95"
      enter-to-class="translate-y-0 opacity-100 scale-100"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-for="toast in toastStore.toasts"
        :key="toast.id"
        class="pointer-events-auto flex items-start gap-3 p-4 rounded-2xl border shadow-xl backdrop-blur-md transition-all"
        :class="toastClasses(toast.type)"
      >
        <!-- Icon -->
        <div class="shrink-0 mt-0.5">
          <svg v-if="toast.type === 'success'" class="w-4 h-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          <svg v-else-if="toast.type === 'error'" class="w-4 h-4 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          <svg v-else-if="toast.type === 'warning'" class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          <svg v-else class="w-4 h-4 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        </div>

        <div class="flex-1 min-w-0">
          <h4 v-if="toast.title" class="text-xs font-bold text-[var(--text-primary)] mb-0.5">{{ toast.title }}</h4>
          <p class="text-xs text-[var(--text-secondary)] leading-relaxed">{{ toast.message }}</p>
        </div>

        <button
          @click="toastStore.remove(toast.id)"
          class="shrink-0 text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xs p-1 -mr-1 rounded-lg transition-colors cursor-pointer flex items-center justify-center"
          aria-label="Close notification"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </transition-group>
  </div>
</template>

<script setup lang="ts">
import { useToastStore } from '../../stores/toast';

const toastStore = useToastStore();

const toastClasses = (type: string) => {
  switch (type) {
    case 'success':
      return 'bg-[var(--bg-surface)] border-emerald-500/40 text-[var(--text-primary)] shadow-emerald-500/10';
    case 'error':
      return 'bg-[var(--bg-surface)] border-rose-500/40 text-[var(--text-primary)] shadow-rose-500/10';
    case 'warning':
      return 'bg-[var(--bg-surface)] border-amber-500/40 text-[var(--text-primary)] shadow-amber-500/10';
    default:
      return 'bg-[var(--bg-surface)] border-[var(--border-subtle)] text-[var(--text-primary)] shadow-md';
  }
};
</script>
