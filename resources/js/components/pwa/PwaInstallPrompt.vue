<template>
  <aside aria-label="App Installation Prompt">
    <!-- Install Modal / Floating Drawer Banner -->
    <transition
      enter-active-class="transition-all ease-out duration-400"
      enter-from-class="translate-y-12 sm:translate-y-8 opacity-0 scale-95"
      enter-to-class="translate-y-0 opacity-100 scale-100"
      leave-active-class="transition-all ease-in duration-250"
      leave-from-class="translate-y-0 opacity-100 scale-100"
      leave-to-class="translate-y-12 sm:translate-y-8 opacity-0 scale-95"
    >
      <div
        v-if="showPrompt"
        class="fixed bottom-4 sm:bottom-6 right-3 sm:right-6 left-3 sm:left-auto z-50 sm:max-w-md w-auto rounded-3xl bg-[var(--bg-surface)]/90 backdrop-blur-2xl border border-[var(--border-accent)] shadow-2xl shadow-black/60 text-[var(--text-primary)] p-4 sm:p-5 overflow-hidden transition-all duration-300 group"
      >
        <!-- Ambient Decorative Golden Radial Glow -->
        <div class="absolute -top-14 -right-14 w-36 h-36 bg-[#D4AF37]/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-28 h-28 bg-sky-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Top Header & Close Button -->
        <div class="relative flex items-start gap-3.5 sm:gap-4">
          <!-- App Logo with Active Ring -->
          <div class="relative shrink-0">
            <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-[#1E2D4A] to-[#0A101D] p-1 border border-[#D4AF37]/40 shadow-lg shadow-[#D4AF37]/15 flex items-center justify-center overflow-hidden">
              <img
                src="/android-chrome-192x192.png"
                alt="Emisha Academy App Icon"
                class="w-full h-full object-contain rounded-xl"
              />
            </div>
            <!-- Live App Status Dot -->
            <span class="absolute -bottom-0.5 -right-0.5 flex h-3.5 w-3.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#D4AF37] opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-[#D4AF37] border-2 border-[var(--bg-surface)]"></span>
            </span>
          </div>

          <!-- Content Details -->
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-1.5 flex-wrap">
                <h4 class="text-sm sm:text-base font-black text-[var(--text-primary)] tracking-tight truncate">
                  {{ isBn ? 'ইমিশা একাডেমি মোবাইল অ্যাপ' : 'Emisha Academy Mobile App' }}
                </h4>
                <span class="px-1.5 py-0.5 rounded-md bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[9px] font-black text-[#D4AF37] tracking-wider uppercase">
                  PWA
                </span>
              </div>

              <!-- Close button -->
              <button
                type="button"
                @click="dismissPrompt"
                class="w-7 h-7 rounded-full bg-[var(--bg-elevated)] hover:bg-[var(--bg-hover)] text-[var(--text-muted)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)] flex items-center justify-center transition-all cursor-pointer shrink-0"
                :title="isBn ? 'বন্ধ করুন' : 'Dismiss'"
                aria-label="Dismiss app install banner"
              >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"></line>
                  <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
              </button>
            </div>

            <!-- Value Proposition Text -->
            <p class="text-xs text-[var(--text-secondary)] mt-1 leading-relaxed">
              {{ isBn 
                ? 'এক ক্লিকে ফুল-স্ক্রিনে ক্লাস করুন, অফলাইনে রিসোর্স পড়ুন এবং লাইভ নোটিফিকেশন পান।' 
                : 'Experience lightning-fast classes, offline materials, and instant live batch notifications.' 
              }}
            </p>

            <!-- 3 Quick Value Badges -->
            <div class="flex flex-wrap items-center gap-1.5 mt-2.5">
              <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-[var(--bg-elevated)]/90 border border-[var(--border-subtle)] text-[10px] sm:text-[11px] font-semibold text-[var(--text-secondary)]">
                <svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                <span>{{ isBn ? 'সুপার ফাস্ট' : '10x Faster' }}</span>
              </span>
              <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-[var(--bg-elevated)]/90 border border-[var(--border-subtle)] text-[10px] sm:text-[11px] font-semibold text-[var(--text-secondary)]">
                <svg class="w-3 h-3 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                <span>{{ isBn ? 'অফলাইন লার্নিং' : 'Offline Access' }}</span>
              </span>
              <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-[var(--bg-elevated)]/90 border border-[var(--border-subtle)] text-[10px] sm:text-[11px] font-semibold text-[var(--text-secondary)]">
                <svg class="w-3 h-3 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                <span>{{ isBn ? 'ক্লাস আপডেট' : 'Live Alerts' }}</span>
              </span>
            </div>

            <!-- Action CTA Buttons -->
            <div class="flex items-center gap-2.5 mt-4">
              <!-- Primary Install Action -->
              <button
                type="button"
                @click="installApp"
                class="flex-1 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black text-xs sm:text-sm hover:brightness-110 active:scale-95 transition-all shadow-lg shadow-[#D4AF37]/25 cursor-pointer flex items-center justify-center gap-2"
              >
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                  <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                </svg>
                <span>{{ isBn ? 'এখনই ইনস্টল করুন' : 'Install App' }}</span>
              </button>

              <!-- Secondary Dismiss Action -->
              <button
                type="button"
                @click="dismissPrompt"
                class="px-3.5 py-2.5 rounded-2xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-hover)] border border-[var(--border-subtle)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs font-bold active:scale-95 transition-all cursor-pointer whitespace-nowrap"
              >
                {{ isBn ? 'পরে' : 'Maybe Later' }}
              </button>
            </div>
          </div>
        </div>

        <!-- iOS Step-by-Step Interactive Guide Drawer -->
        <transition
          enter-active-class="transition-all ease-out duration-300"
          enter-from-class="opacity-0 max-h-0"
          enter-to-class="opacity-100 max-h-48"
          leave-active-class="transition-all ease-in duration-200"
          leave-from-class="opacity-100 max-h-48"
          leave-to-class="opacity-0 max-h-0"
        >
          <div
            v-if="isIos && showIosHelp"
            class="mt-3.5 pt-3.5 border-t border-[var(--border-subtle)] text-xs space-y-2 bg-[var(--bg-elevated)]/60 rounded-2xl p-3"
          >
            <p class="font-bold text-[#D4AF37] flex items-center gap-1.5">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"/><path d="M10 2c1 .5 2 2 2 5"/></svg>
              <span>{{ isBn ? 'iPhone বা iPad-এ ইনস্টল করার সহজ নিয়ম:' : 'How to install on iPhone/iPad:' }}</span>
            </p>
            <ol class="list-decimal list-inside space-y-1 text-[var(--text-secondary)] text-[11px] leading-relaxed">
              <li>
                {{ isBn ? 'Safari ব্রাউজারের নিচে' : 'Tap Safari' }}
                <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-[var(--bg-surface)] border border-[var(--border-subtle)] font-bold text-[var(--text-primary)]">
                  <svg class="w-3 h-3 inline mr-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                    <polyline points="16 6 12 2 8 6"/>
                    <line x1="12" y1="2" x2="12" y2="15"/>
                  </svg>
                  Share (শেয়ার)
                </span> 
                {{ isBn ? 'আইকনে চাপ দিন।' : 'icon.' }}
              </li>
              <li>
                {{ isBn ? 'নিচে স্ক্রোল করে' : 'Scroll down and tap' }}
                <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-[var(--bg-surface)] border border-[var(--border-subtle)] font-bold text-[#D4AF37]">
                  ＋ Add to Home Screen
                </span> 
                {{ isBn ? 'সিলেক্ট করুন।' : 'option.' }}
              </li>
            </ol>
          </div>
        </transition>
      </div>
    </transition>
  </aside>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useNetworkStore } from '../../stores/network';
import { useThemeStore } from '../../stores/theme';

const networkStore = useNetworkStore();
const themeStore = useThemeStore();
const isDismissed = ref(false);
const showIosHelp = ref(false);

const isBn = computed(() => themeStore.locale === 'bn');

const isIos = computed(() => {
  if (typeof window === 'undefined') return false;
  const userAgent = window.navigator.userAgent.toLowerCase();
  return /iphone|ipad|ipod/.test(userAgent);
});

const isStandalone = computed(() => {
  if (typeof window === 'undefined') return false;
  return (
    window.matchMedia('(display-mode: standalone)').matches ||
    (window.navigator as any).standalone === true
  );
});

const showPrompt = computed(() => {
  if (isStandalone.value || isDismissed.value) return false;
  return networkStore.isInstallPromptAvailable || (isIos.value && !isDismissed.value);
});

onMounted(() => {
  const dismissedTime = localStorage.getItem('emisha_pwa_dismissed');
  if (dismissedTime) {
    const elapsed = Date.now() - parseInt(dismissedTime, 10);
    // Suppress for 2 days if dismissed
    if (elapsed < 2 * 24 * 60 * 60 * 1000) {
      isDismissed.value = true;
    }
  }
});

const installApp = async () => {
  if (networkStore.deferredPrompt) {
    networkStore.deferredPrompt.prompt();
    const choiceResult = await networkStore.deferredPrompt.userChoice;
    if (choiceResult.outcome === 'accepted') {
      networkStore.clearInstallPrompt();
      isDismissed.value = true;
    }
  } else if (isIos.value) {
    showIosHelp.value = !showIosHelp.value;
  }
};

const dismissPrompt = () => {
  isDismissed.value = true;
  localStorage.setItem('emisha_pwa_dismissed', Date.now().toString());
};
</script>

