<template>
  <div class="relative inline-block text-left" ref="dropdownRef">
    
    <!-- 1. Compact Mode (Floating Glass Trigger) -->
    <button
      v-if="variant === 'compact'"
      @click="isOpen = !isOpen"
      type="button"
      :class="[
        'p-2 rounded-full sm:rounded-xl bg-[var(--bg-elevated)]/90 backdrop-blur-md border border-[var(--border-subtle)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:border-[#D4AF37]/70 hover:shadow-[0_0_16px_rgba(212,175,55,0.18)] transition-all duration-300 cursor-pointer flex items-center justify-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/40 shadow-xs touch-target group',
        isOpen ? 'border-[#D4AF37] text-[#D4AF37] shadow-sm bg-[var(--bg-surface)]' : ''
      ]"
      :aria-expanded="isOpen"
      aria-haspopup="true"
      :title="currentTitle"
    >
      <!-- Light Mode Sun SVG -->
      <svg
        v-if="themeStore.mode === 'light'"
        class="w-4 h-4 text-amber-500 group-hover:rotate-45 transition-transform duration-500"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
      >
        <circle cx="12" cy="12" r="4"/>
        <path d="M12 2v2"/>
        <path d="M12 20v2"/>
        <path d="m4.93 4.93 1.41 1.41"/>
        <path d="m17.66 17.66 1.41 1.41"/>
        <path d="M2 12h2"/>
        <path d="M20 12h2"/>
        <path d="m6.34 17.66-1.41 1.41"/>
        <path d="m19.07 4.93-1.41 1.41"/>
      </svg>

      <!-- Dark Mode Moon SVG -->
      <svg
        v-else-if="themeStore.mode === 'dark'"
        class="w-4 h-4 text-[#D4AF37] group-hover:-rotate-12 transition-transform duration-300"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
      >
        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
      </svg>

      <!-- System Mode Monitor SVG -->
      <svg
        v-else
        class="w-4 h-4 text-cyan-400 group-hover:scale-105 transition-transform duration-300"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
      >
        <rect width="20" height="14" x="2" y="3" rx="2"/>
        <line x1="8" x2="16" y1="21" y2="21"/>
        <line x1="12" x2="12" y1="17" y2="21"/>
      </svg>

      <span class="sr-only">Toggle theme</span>
    </button>

    <!-- 2. Segmented Pill Variant (for Settings or Toolbars) -->
    <div
      v-else-if="variant === 'segmented'"
      class="inline-flex items-center p-1 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] gap-1 w-full sm:w-auto"
      role="radiogroup"
      aria-label="Theme mode selection"
    >
      <button
        v-for="opt in options"
        :key="opt.value"
        @click="selectTheme(opt.value)"
        type="button"
        :class="[
          'flex-1 sm:flex-initial px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer touch-target',
          themeStore.mode === opt.value
            ? 'bg-[var(--bg-surface)] text-[var(--brand-gold)] shadow-sm border border-[var(--border-accent)]'
            : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-surface)]/50'
        ]"
        :aria-checked="themeStore.mode === opt.value"
        role="radio"
      >
        <!-- Dynamic Icon Component -->
        <component :is="opt.iconComponent" class="w-3.5 h-3.5" :class="opt.colorClass" />
        <span>{{ themeStore.locale === 'bn' ? opt.labelBn : opt.labelEn }}</span>
      </button>
    </div>

    <!-- 3. Dropdown Menu for Compact Mode -->
    <transition
      enter-active-class="transition duration-150 ease-out"
      enter-from-class="transform scale-95 opacity-0 -translate-y-1"
      enter-to-class="transform scale-100 opacity-100 translate-y-0"
      leave-active-class="transition duration-100 ease-in"
      leave-from-class="transform scale-100 opacity-100 translate-y-0"
      leave-to-class="transform scale-95 opacity-0 -translate-y-1"
    >
      <div
        v-if="variant === 'compact' && isOpen"
        class="absolute right-0 mt-2 w-44 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-2xl z-50 p-1.5 space-y-1 focus:outline-none backdrop-blur-md ring-1 ring-[var(--border-accent)]/20"
      >
        <div class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-[var(--text-muted)] flex items-center justify-between border-b border-[var(--border-subtle)]/60 mb-1">
          <span>{{ themeStore.locale === 'bn' ? 'থিম নির্বাচন' : 'SELECT THEME' }}</span>
        </div>

        <button
          v-for="opt in options"
          :key="opt.value"
          @click="selectTheme(opt.value)"
          type="button"
          :class="[
            'w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs font-semibold transition-all cursor-pointer text-left group',
            themeStore.mode === opt.value
              ? 'bg-[var(--bg-elevated)] text-[var(--brand-gold)] font-bold border border-[var(--border-accent)]/40 shadow-xs'
              : 'text-[var(--text-secondary)] hover:bg-[var(--bg-elevated)]/60 hover:text-[var(--text-primary)]'
          ]"
        >
          <div class="flex items-center gap-2.5">
            <div
              :class="[
                'w-7 h-7 rounded-lg flex items-center justify-center transition-colors',
                opt.bgClass
              ]"
            >
              <component :is="opt.iconComponent" class="w-4 h-4" :class="opt.colorClass" />
            </div>
            <div class="flex flex-col">
              <span class="text-xs font-bold leading-tight">{{ themeStore.locale === 'bn' ? opt.labelBn : opt.labelEn }}</span>
              <span class="text-[10px] text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? opt.descBn : opt.descEn }}</span>
            </div>
          </div>

          <!-- Active checkmark -->
          <svg
            v-if="themeStore.mode === opt.value"
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
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, h } from 'vue';
import { useThemeStore, ThemeMode } from '../../stores/theme';

const props = withDefaults(
  defineProps<{
    variant?: 'compact' | 'segmented';
  }>(),
  {
    variant: 'compact',
  }
);

const themeStore = useThemeStore();
const isOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

// Inline SVG Functional Components for Zero Emojis & Maximum Performance
const SunIcon = (props: any) => h('svg', {
  ...props,
  viewBox: '0 0 24 24',
  fill: 'none',
  stroke: 'currentColor',
  'stroke-width': '2',
  'stroke-linecap': 'round',
  'stroke-linejoin': 'round',
  'aria-hidden': 'true',
}, [
  h('circle', { cx: '12', cy: '12', r: '4' }),
  h('path', { d: 'M12 2v2' }),
  h('path', { d: 'M12 20v2' }),
  h('path', { d: 'm4.93 4.93 1.41 1.41' }),
  h('path', { d: 'm17.66 17.66 1.41 1.41' }),
  h('path', { d: 'M2 12h2' }),
  h('path', { d: 'M20 12h2' }),
  h('path', { d: 'm6.34 17.66-1.41 1.41' }),
  h('path', { d: 'm19.07 4.93-1.41 1.41' }),
]);

const MoonIcon = (props: any) => h('svg', {
  ...props,
  viewBox: '0 0 24 24',
  fill: 'none',
  stroke: 'currentColor',
  'stroke-width': '2',
  'stroke-linecap': 'round',
  'stroke-linejoin': 'round',
  'aria-hidden': 'true',
}, [
  h('path', { d: 'M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z' }),
]);

const MonitorIcon = (props: any) => h('svg', {
  ...props,
  viewBox: '0 0 24 24',
  fill: 'none',
  stroke: 'currentColor',
  'stroke-width': '2',
  'stroke-linecap': 'round',
  'stroke-linejoin': 'round',
  'aria-hidden': 'true',
}, [
  h('rect', { width: '20', height: '14', x: '2', y: '3', rx: '2' }),
  h('line', { x1: '8', x2: '16', y1: '21', y2: '21' }),
  h('line', { x1: '12', x2: '12', y1: '17', y2: '21' }),
]);

const options = [
  {
    value: 'light' as ThemeMode,
    labelBn: 'লাইট থিম',
    labelEn: 'Light Mode',
    descBn: 'উজ্জ্বল ব্যাকগ্রাউন্ড',
    descEn: 'Clean & crisp view',
    iconComponent: SunIcon,
    colorClass: 'text-amber-500',
    bgClass: 'bg-amber-500/10 border border-amber-500/20',
  },
  {
    value: 'dark' as ThemeMode,
    labelBn: 'ডার্ক থিম',
    labelEn: 'Dark Mode',
    descBn: 'চোখের জন্য আরামদায়ক',
    descEn: 'Deep contrast view',
    iconComponent: MoonIcon,
    colorClass: 'text-[#D4AF37]',
    bgClass: 'bg-[#D4AF37]/10 border border-[#D4AF37]/20',
  },
  {
    value: 'system' as ThemeMode,
    labelBn: 'অটো / সিস্টেম',
    labelEn: 'System Auto',
    descBn: 'ডিভাইস সেটিংস অনুযায়ী',
    descEn: 'Syncs with device',
    iconComponent: MonitorIcon,
    colorClass: 'text-cyan-400',
    bgClass: 'bg-cyan-500/10 border border-cyan-500/20',
  },
];

const currentTitle = computed(() => {
  if (themeStore.mode === 'light') return 'লাইট থিম সক্রিয় (Light Mode)';
  if (themeStore.mode === 'dark') return 'ডার্ক থিম সক্রিয় (Dark Mode)';
  return 'সিস্টেম থিম সক্রিয় (System Mode)';
});

function selectTheme(mode: ThemeMode) {
  themeStore.setTheme(mode);
  isOpen.value = false;
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
