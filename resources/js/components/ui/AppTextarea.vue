<template>
  <div class="w-full space-y-1.5">
    <label v-if="label" :for="id" class="block text-xs font-semibold text-[var(--text-secondary)]">
      {{ label }}
      <span v-if="required" class="text-rose-400">*</span>
    </label>

    <textarea
      :id="id"
      :value="modelValue"
      :rows="rows"
      :placeholder="placeholder"
      :disabled="disabled"
      :required="required"
      class="w-full rounded-xl bg-[var(--bg-elevated)] border text-[var(--text-primary)] text-base sm:text-sm px-3.5 py-2.5 placeholder-[var(--text-muted)] transition-all duration-200 focus:outline-none focus:ring-1"
      :class="[
        error 
          ? 'border-rose-500/80 focus:border-rose-500 focus:ring-rose-500/30' 
          : 'border-[var(--border-subtle)] focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 hover:border-[var(--border-medium)]',
        disabled ? 'opacity-60 cursor-not-allowed bg-[var(--bg-card)]' : ''
      ]"
      @input="$emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
    ></textarea>

    <p v-if="error" class="text-[11px] font-medium text-rose-400 flex items-center gap-1">
      <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
      <span>{{ error }}</span>
    </p>
  </div>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    modelValue?: string | null;
    id?: string;
    label?: string;
    rows?: number;
    placeholder?: string;
    error?: string;
    disabled?: boolean;
    required?: boolean;
  }>(),
  {
    modelValue: '',
    rows: 4,
    disabled: false,
    required: false,
  }
);

defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();
</script>
