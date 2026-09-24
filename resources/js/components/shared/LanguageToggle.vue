<template>
  <!-- Variant 1: Capsule Luxury Slider Switch (Default for Desktop Header & Navigation) -->
  <div
    v-if="variant === 'capsule-switch'"
    class="relative inline-flex items-center p-0.5 sm:p-1 rounded-full bg-[var(--bg-elevated)]/90 backdrop-blur-md border border-[var(--border-subtle)] hover:border-[#D4AF37]/60 shadow-xs hover:shadow-[0_0_16px_rgba(212,175,55,0.18)] transition-all duration-300 group select-none touch-target"
    role="group"
    aria-label="Language selection"
  >
    <!-- Luxury Orbiting Globe Icon -->
    <div
      class="pl-2 pr-1.5 flex items-center justify-center text-[#D4AF37] shrink-0 transition-transform duration-500 group-hover:rotate-45"
      aria-hidden="true"
    >
      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <line x1="2" y1="12" x2="22" y2="12"/>
        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
      </svg>
    </div>

    <!-- Bangla Pill Button -->
    <button
      @click="setLanguage('bn')"
      type="button"
      :class="[
        'relative px-2.5 py-1 rounded-full text-[11px] transition-all duration-300 cursor-pointer flex items-center gap-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D4AF37]',
        themeStore.locale === 'bn'
          ? 'bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 font-black shadow-md shadow-[#D4AF37]/30 border border-[#F7E7A9]/40 scale-[1.03]'
          : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-surface)] font-bold'
      ]"
      :aria-pressed="themeStore.locale === 'bn'"
      title="বাংলা ভাষা সক্রিয় করুন (Switch to Bangla)"
    >
      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-xs shrink-0" v-if="themeStore.locale === 'bn'"></span>
      <span>বাং</span>
    </button>

    <!-- English Pill Button -->
    <button
      @click="setLanguage('en')"
      type="button"
      :class="[
        'relative px-2.5 py-1 rounded-full text-[11px] transition-all duration-300 cursor-pointer flex items-center gap-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D4AF37]',
        themeStore.locale === 'en'
          ? 'bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 font-black shadow-md shadow-[#D4AF37]/30 border border-[#F7E7A9]/40 scale-[1.03]'
          : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-surface)] font-bold'
      ]"
      :aria-pressed="themeStore.locale === 'en'"
      title="Switch to English (ইংরেজি ভাষা নির্বাচন করুন)"
    >
      <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-xs shrink-0" v-if="themeStore.locale === 'en'"></span>
      <span>EN</span>
    </button>
  </div>

  <!-- Variant 2: Luxury Floating Dropdown Popover (Dropdown Mode) -->
  <div
    v-else-if="variant === 'dropdown'"
    class="relative inline-block text-left"
    ref="dropdownRef"
  >
    <button
      @click="isOpen = !isOpen"
      type="button"
      :class="[
        'flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/60 text-xs font-bold text-[var(--text-primary)] transition-all cursor-pointer shadow-xs group',
        isOpen ? 'border-[#D4AF37] text-[#D4AF37] shadow-sm bg-[var(--bg-surface)]' : ''
      ]"
      :aria-expanded="isOpen"
      aria-haspopup="true"
    >
      <div class="w-5 h-5 rounded-lg bg-[#D4AF37]/10 border border-[#D4AF37]/25 text-[#D4AF37] flex items-center justify-center shrink-0 group-hover:rotate-12 transition-transform">
        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="12" r="10"/>
          <line x1="2" y1="12" x2="22" y2="12"/>
          <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
      </div>

      <span>{{ themeStore.locale === 'bn' ? 'বাংলা (BN)' : 'English (EN)' }}</span>

      <svg class="w-3.5 h-3.5 text-[var(--text-muted)] group-hover:text-[#D4AF37] transition-transform duration-200" :class="{ 'rotate-180': isOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <polyline points="6 9 12 15 18 9"/>
      </svg>
    </button>

    <!-- Dropdown Menu -->
    <transition
      enter-active-class="transition duration-150 ease-out"
      enter-from-class="transform scale-95 opacity-0 -translate-y-1"
      enter-to-class="transform scale-100 opacity-100 translate-y-0"
      leave-active-class="transition duration-100 ease-in"
      leave-from-class="transform scale-100 opacity-100 translate-y-0"
      leave-to-class="transform scale-95 opacity-0 -translate-y-1"
    >
      <div
        v-if="isOpen"
        class="absolute right-0 mt-2 w-52 rounded-2xl bg-[var(--bg-surface)]/95 border border-[var(--border-subtle)] shadow-2xl z-50 p-1.5 space-y-1 focus:outline-none backdrop-blur-xl ring-1 ring-[var(--border-accent)]/20"
      >
        <div class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-[var(--text-muted)] flex items-center justify-between border-b border-[var(--border-subtle)]/60 mb-1">
          <span>{{ themeStore.locale === 'bn' ? 'ভাষা নির্বাচন' : 'SELECT LANGUAGE' }}</span>
        </div>

        <!-- Option 1: Bangla -->
        <button
          @click="setLanguage('bn')"
          type="button"
          :class="[
            'w-full flex items-center justify-between px-2.5 py-2.5 rounded-xl text-xs font-semibold transition-all cursor-pointer text-left group',
            themeStore.locale === 'bn'
              ? 'bg-[var(--bg-elevated)] text-[var(--brand-gold)] font-bold border border-[var(--border-accent)]/40 shadow-xs'
              : 'text-[var(--text-secondary)] hover:bg-[var(--bg-elevated)]/60 hover:text-[var(--text-primary)]'
          ]"
        >
          <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 flex items-center justify-center shrink-0 font-black text-[11px]">
              বাং
            </div>
            <div class="flex flex-col">
              <span class="text-xs font-bold leading-tight">বাংলা</span>
              <span class="text-[10px] text-[var(--text-muted)]">Native Bengali</span>
            </div>
          </div>

          <svg
            v-if="themeStore.locale === 'bn'"
            class="w-4 h-4 text-[var(--brand-gold)] shrink-0"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
          >
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </button>

        <!-- Option 2: English -->
        <button
          @click="setLanguage('en')"
          type="button"
          :class="[
            'w-full flex items-center justify-between px-2.5 py-2.5 rounded-xl text-xs font-semibold transition-all cursor-pointer text-left group',
            themeStore.locale === 'en'
              ? 'bg-[var(--bg-elevated)] text-[var(--brand-gold)] font-bold border border-[var(--border-accent)]/40 shadow-xs'
              : 'text-[var(--text-secondary)] hover:bg-[var(--bg-elevated)]/60 hover:text-[var(--text-primary)]'
          ]"
        >
          <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-sky-500/10 border border-sky-500/25 text-sky-400 flex items-center justify-center shrink-0 font-black text-[11px]">
              EN
            </div>
            <div class="flex flex-col">
              <span class="text-xs font-bold leading-tight">English</span>
              <span class="text-[10px] text-[var(--text-muted)]">International Standard</span>
            </div>
          </div>

          <svg
            v-if="themeStore.locale === 'en'"
            class="w-4 h-4 text-[var(--brand-gold)] shrink-0"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
          >
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </button>
      </div>
    </transition>
  </div>

  <!-- Variant 3: Segmented Luxury Dual Card (for Settings, Student Portal & Mobile Drawers) -->
  <div
    v-else-if="variant === 'segmented'"
    class="grid grid-cols-2 gap-2.5 p-1.5 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] shadow-xs"
  >
    <button
      @click="setLanguage('bn')"
      type="button"
      :class="[
        'flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer touch-target select-none',
        themeStore.locale === 'bn'
          ? 'bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 font-black shadow-md shadow-[#D4AF37]/25 border border-[#F7E7A9]/40'
          : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-surface)]'
      ]"
    >
      <span class="w-2 h-2 rounded-full bg-emerald-500" v-if="themeStore.locale === 'bn'"></span>
      <span>বাংলা (BN)</span>
      <svg v-if="themeStore.locale === 'bn'" class="w-3.5 h-3.5 text-slate-950 ml-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    </button>

    <button
      @click="setLanguage('en')"
      type="button"
      :class="[
        'flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer touch-target select-none',
        themeStore.locale === 'en'
          ? 'bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 font-black shadow-md shadow-[#D4AF37]/25 border border-[#F7E7A9]/40'
          : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-surface)]'
      ]"
    >
      <span class="w-2 h-2 rounded-full bg-blue-500" v-if="themeStore.locale === 'en'"></span>
      <span>English (EN)</span>
      <svg v-if="themeStore.locale === 'en'" class="w-3.5 h-3.5 text-slate-950 ml-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    </button>
  </div>

  <!-- Variant 4: Compact Button Trigger with Hover Animation -->
  <button
    v-else
    @click="toggleLanguage"
    type="button"
    class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-black text-[var(--text-secondary)] hover:text-[var(--brand-gold)] shadow-xs transition-all duration-300 cursor-pointer touch-target group"
    :title="themeStore.locale === 'bn' ? 'Switch to English' : 'বাংলায় দেখুন'"
  >
    <div class="w-4 h-4 text-[#D4AF37] group-hover:rotate-45 transition-transform duration-300">
      <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="12" cy="12" r="10"/>
        <line x1="2" y1="12" x2="22" y2="12"/>
        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
      </svg>
    </div>
    <span class="font-extrabold tracking-wide">{{ themeStore.locale === 'bn' ? 'EN' : 'বাং' }}</span>
  </button>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { useThemeStore } from '../../stores/theme';
import { useI18n } from 'vue-i18n';

const props = withDefaults(
  defineProps<{
    variant?: 'capsule-switch' | 'dropdown' | 'segmented' | 'compact';
  }>(),
  {
    variant: 'capsule-switch',
  }
);

const themeStore = useThemeStore();
const { locale } = useI18n();
const isOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

function setLanguage(lang: 'bn' | 'en') {
  themeStore.setLocale(lang);
  locale.value = lang;
  isOpen.value = false;
}

function toggleLanguage() {
  const nextLang = themeStore.locale === 'bn' ? 'en' : 'bn';
  setLanguage(nextLang);
}

function handleClickOutside(event: MouseEvent) {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
    isOpen.value = false;
  }
}

function handleKeydown(event: KeyboardEvent) {
  if (event.key === 'Escape') {
    isOpen.value = false;
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
  document.removeEventListener('keydown', handleKeydown);
});
</script>
