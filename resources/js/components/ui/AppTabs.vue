<template>
  <div class="flex items-center gap-2 border-b border-[var(--border-subtle)] overflow-x-auto no-scrollbar pb-px">
    <button
      v-for="tab in tabs"
      :key="tab.value"
      type="button"
      class="px-4 py-2.5 text-xs sm:text-sm font-semibold rounded-t-xl transition-all relative shrink-0 cursor-pointer"
      :class="[
        modelValue === tab.value
          ? 'text-[var(--brand-gold)] bg-[var(--bg-elevated)] border-b-2 border-[var(--brand-gold)] shadow-xs'
          : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-hover)]'
      ]"
      @click="$emit('update:modelValue', tab.value)"
    >
      {{ tab.label }}
      <span
        v-if="tab.count !== undefined"
        class="ml-1.5 px-2 py-0.5 text-[10px] rounded-full font-bold"
        :class="modelValue === tab.value ? 'bg-[var(--brand-gold-subtle)] text-[var(--brand-gold-text)]' : 'bg-[var(--bg-surface)] text-[var(--text-muted)]'"
      >
        {{ tab.count }}
      </span>
    </button>
  </div>
</template>

<script setup lang="ts">
export interface TabItem {
  value: string;
  label: string;
  count?: number;
}

defineProps<{
  modelValue: string;
  tabs: TabItem[];
}>();

defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();
</script>
