<template>
  <div class="w-full space-y-1.5">
    <div v-if="label" class="flex items-center justify-between">
      <label :for="id" class="block text-xs font-semibold text-[var(--text-secondary)]">
        {{ label }}
        <span v-if="required" class="text-rose-400">*</span>
      </label>
      <slot name="label-right"></slot>
    </div>

    <div class="relative rounded-xl transition-all">
      <!-- Prefix icon -->
      <div v-if="$slots.prefix" class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[var(--text-muted)]">
        <slot name="prefix"></slot>
      </div>

      <input
        :id="id"
        :type="effectiveType"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :readonly="readonly"
        :required="required"
        :autocomplete="autocomplete"
        class="w-full rounded-xl bg-[var(--bg-elevated)] border text-[var(--text-primary)] text-base sm:text-sm placeholder-[var(--text-muted)] transition-all duration-200 focus:outline-none focus:ring-1 min-h-[44px]"
        :class="[
          $slots.prefix ? 'pl-10' : 'pl-3.5',
          ($slots.suffix || hasPasswordToggle) ? 'pr-11' : 'pr-3.5',
          'py-2.5',
          error 
            ? 'border-rose-500/80 focus:border-rose-500 focus:ring-rose-500/30' 
            : 'border-[var(--border-subtle)] focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 hover:border-[var(--border-medium)]',
          disabled ? 'opacity-60 cursor-not-allowed bg-[var(--bg-card)]' : ''
        ]"
        @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        @blur="$emit('blur', $event)"
        @focus="$emit('focus', $event)"
        @keydown="$emit('keydown', $event)"
        @keyup="$emit('keyup', $event)"
      />

      <!-- Custom Suffix Slot -->
      <div v-if="$slots.suffix" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[var(--text-muted)]">
        <slot name="suffix"></slot>
      </div>

      <!-- Built-in Password Eye Toggle Button -->
      <button
        v-else-if="hasPasswordToggle"
        type="button"
        tabindex="-1"
        @click="togglePasswordVisibility"
        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[var(--text-secondary)] hover:text-[#D4AF37] transition-colors cursor-pointer touch-target"
        :title="isPasswordVisible ? 'পাসওয়ার্ড লুকান (Hide Password)' : 'পাসওয়ার্ড দেখুন (Show Password)'"
        :aria-label="isPasswordVisible ? 'Hide password' : 'Show password'"
      >
        <!-- Eye Open Icon (Visible) -->
        <svg
          v-if="isPasswordVisible"
          class="w-4 h-4"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
          <circle cx="12" cy="12" r="3" />
        </svg>

        <!-- Eye Slash Icon (Hidden) -->
        <svg
          v-else
          class="w-4 h-4"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
          <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
          <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
          <line x1="2" x2="22" y1="2" y2="22" />
        </svg>
      </button>
    </div>

    <!-- Error / Helper text -->
    <p v-if="error" class="text-[11px] font-medium text-rose-400 flex items-center gap-1">
      <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
      <span>{{ error }}</span>
    </p>
    <p v-else-if="hint" class="text-[11px] text-[var(--text-muted)]">
      {{ hint }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

const props = withDefaults(
  defineProps<{
    modelValue?: string | number | null;
    id?: string;
    label?: string;
    type?: string;
    placeholder?: string;
    error?: string;
    hint?: string;
    disabled?: boolean;
    readonly?: boolean;
    required?: boolean;
    showPasswordToggle?: boolean;
    autocomplete?: string;
  }>(),
  {
    modelValue: '',
    type: 'text',
    disabled: false,
    readonly: false,
    required: false,
    showPasswordToggle: true,
    autocomplete: 'off',
  }
);

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
  (e: 'blur', event: FocusEvent): void;
  (e: 'focus', event: FocusEvent): void;
  (e: 'keydown', event: KeyboardEvent): void;
  (e: 'keyup', event: KeyboardEvent): void;
}>();

const isPasswordVisible = ref(false);

const hasPasswordToggle = computed(() => {
  return props.type === 'password' && props.showPasswordToggle !== false;
});

const effectiveType = computed(() => {
  if (props.type === 'password') {
    return isPasswordVisible.value ? 'text' : 'password';
  }
  return props.type;
});

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value;
};
</script>
