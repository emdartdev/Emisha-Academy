<template>
  <div class="min-h-screen flex items-center justify-center bg-[var(--bg-deep)] text-[var(--text-primary)] px-4 py-12 relative overflow-hidden transition-colors">
    <!-- Top Bar with Language and Theme Switcher -->
    <div class="absolute top-4 right-4 z-20 flex items-center gap-2">
      <LanguageToggle variant="capsule-switch" />
      <ThemeToggle variant="compact" />
    </div>

    <!-- Back to Home Link (Top Left) -->
    <div class="absolute top-4 left-4 z-20">
      <router-link
        to="/"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[var(--bg-elevated)]/80 hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-semibold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all backdrop-blur-md"
      >
        <span>← {{ themeStore.locale === 'bn' ? 'হোমপেজ' : 'Home' }}</span>
      </router-link>
    </div>

    <!-- Ambient glow circles -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-[#D4AF37]/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
      <div class="text-center mb-6">
        <router-link to="/" class="inline-block transition-transform hover:scale-105">
          <img
            :src="isDarkTheme ? '/images/logo-dark.png' : '/images/logo-light.png'"
            alt="Emisha Academy"
            class="h-10 sm:h-12 w-auto max-w-[220px] object-contain mx-auto"
          />
        </router-link>
      </div>

      <div class="bg-[var(--bg-card)] border border-[var(--border-subtle)] rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl transition-colors">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useThemeStore } from '../stores/theme';
import ThemeToggle from '../components/shared/ThemeToggle.vue';
import LanguageToggle from '../components/shared/LanguageToggle.vue';

const themeStore = useThemeStore();

const isDarkTheme = computed(() => {
  if (themeStore.mode === 'system') {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }
  return themeStore.mode === 'dark';
});
</script>
