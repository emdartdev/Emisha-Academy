<template>
  <div class="w-full space-y-1.5">
    <label v-if="label" :for="id" class="block text-xs font-semibold text-[var(--text-secondary)]">
      {{ label }}
      <span v-if="required" class="text-rose-400">*</span>
    </label>

    <div class="relative rounded-xl">
      <select
        :id="id"
        :value="modelValue"
        :disabled="disabled"
        :required="required"
        class="w-full rounded-xl bg-[var(--bg-elevated)] border text-[var(--text-primary)] text-base sm:text-sm px-3.5 py-2.5 appearance-none cursor-pointer transition-all duration-200 focus:outline-none focus:ring-1 min-h-[44px]"
        :class="[
          error 
            ? 'border-rose-500/80 focus:border-rose-500 focus:ring-rose-500/30' 
            : 'border-[var(--border-subtle)] focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 hover:border-[var(--border-medium)]',
          disabled ? 'opacity-60 cursor-not-allowed bg-[var(--bg-card)]' : ''
        ]"
        @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
      >
        <option v-if="placeholder" value="" disabled selected class="bg-[var(--bg-surface)] text-[var(--text-muted)]">
          {{ placeholder }}
        </option>
        <option
          v-for="opt in options"
          :key="opt.value"
          :value="opt.value"
          class="bg-[var(--bg-surface)] text-[var(--text-primary)] py-2"
        >
          {{ opt.label }}
        </option>
      </select>

      <!-- Dropdown Chevron -->
      <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
      </div>
    </div>

    <p v-if="error" class="text-[11px] font-medium text-rose-400 flex items-center gap-1">
      <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
      <span>{{ error }}</span>
    </p>
  </div>
</template>

<script setup lang="ts">
export interface SelectOption {
  value: string | number;
  label: string;
}

withDefaults(
  defineProps<{
    modelValue?: string | number | null;
    id?: string;
    label?: string;
    placeholder?: string;
    options: SelectOption[];
    error?: string;
    disabled?: boolean;
    required?: boolean;
  }>(),
  {
    modelValue: '',
    disabled: false,
    required: false,
  }
);

defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();
</script>
