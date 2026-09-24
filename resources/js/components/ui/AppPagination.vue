<template>
  <div v-if="totalPages > 1" class="flex items-center justify-between py-4">
    <p class="text-xs text-[var(--text-secondary)]">
      পৃষ্ঠা <span class="text-[var(--text-primary)] font-semibold">{{ currentPage }}</span> / <span class="text-[var(--text-primary)] font-semibold">{{ totalPages }}</span>
    </p>

    <div class="flex items-center gap-1.5">
      <button
        :disabled="currentPage <= 1"
        class="px-3 py-1.5 rounded-xl border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] hover:bg-[var(--bg-elevated)] disabled:opacity-40 disabled:cursor-not-allowed transition-colors cursor-pointer"
        @click="$emit('change', currentPage - 1)"
      >
        পূর্ববর্তী
      </button>

      <button
        v-for="page in visiblePages"
        :key="page"
        class="w-8 h-8 rounded-xl text-xs font-semibold flex items-center justify-center transition-all cursor-pointer"
        :class="[
          page === currentPage
            ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 shadow-md font-bold'
            : 'border border-[var(--border-subtle)] text-[var(--text-primary)] hover:bg-[var(--bg-elevated)]'
        ]"
        @click="$emit('change', page)"
      >
        {{ page }}
      </button>

      <button
        :disabled="currentPage >= totalPages"
        class="px-3 py-1.5 rounded-xl border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] hover:bg-[var(--bg-elevated)] disabled:opacity-40 disabled:cursor-not-allowed transition-colors cursor-pointer"
        @click="$emit('change', currentPage + 1)"
      >
        পরবর্তী
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  currentPage: number;
  totalPages: number;
}>();

defineEmits<{
  (e: 'change', page: number): void;
}>();

const visiblePages = computed(() => {
  const pages: number[] = [];
  const start = Math.max(1, props.currentPage - 2);
  const end = Math.min(props.totalPages, start + 4);

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  return pages;
});
</script>
