<template>
  <!-- Compact network / offline-sync status: a small corner button that expands into a details card -->
  <div
    v-if="isVisible || networkStore.syncSuccessMessage"
    ref="rootRef"
    class="fixed left-3 sm:left-5 z-50 flex flex-col items-start gap-2 transition-[bottom] duration-300"
    :class="networkStore.isUpdateAvailable ? 'bottom-24' : 'bottom-4 sm:bottom-5'"
    style="margin-bottom: env(safe-area-inset-bottom, 0px)"
  >
    <!-- Details card -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 translate-y-2 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-2 scale-95"
    >
      <div
        v-if="isOpen && isVisible"
        id="network-status-panel"
        role="dialog"
        :aria-label="t('Connection status', 'সংযোগের অবস্থা')"
        class="origin-bottom-left w-[min(20rem,calc(100vw-5.5rem))] sm:w-80 rounded-2xl border shadow-2xl backdrop-blur-xl p-4 space-y-3 bg-[var(--bg-surface)]/95 text-[var(--text-primary)]"
        :class="tone.border"
      >
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-start gap-2.5 min-w-0">
            <span class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" :class="tone.soft">
              <svg class="w-4.5 h-4.5" :class="[tone.text, { 'animate-spin': isSyncing }]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-html="tone.icon" />
            </span>
            <div class="min-w-0">
              <p class="text-sm font-black" :class="tone.text">{{ title }}</p>
              <p class="text-xs text-[var(--text-secondary)] mt-0.5 leading-relaxed">{{ description }}</p>
            </div>
          </div>
          <button
            type="button"
            class="w-9 h-9 -mr-1.5 -mt-1.5 rounded-xl flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-elevated)] cursor-pointer shrink-0"
            :aria-label="t('Close', 'বন্ধ করুন')"
            @click="isOpen = false"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <div v-if="networkStore.pendingCount > 0" class="flex items-center justify-between text-xs px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)]">
          <span class="text-[var(--text-secondary)]">{{ t('Waiting to sync', 'সিঙ্কের অপেক্ষায়') }}</span>
          <span class="font-black font-mono">{{ formatNumber(networkStore.pendingCount, themeStore.locale) }}</span>
        </div>

        <button
          v-if="canSync"
          type="button"
          class="w-full min-h-[44px] rounded-xl bg-[var(--brand-gold)] text-slate-950 font-black text-xs hover:opacity-90 active:scale-[0.98] transition-all cursor-pointer disabled:opacity-60"
          :disabled="isSyncing"
          @click="handleManualSync"
        >
          {{ isSyncing ? t('Syncing…', 'সিঙ্ক হচ্ছে…') : t('Sync now', 'এখনই সিঙ্ক করুন') }}
        </button>
        <p v-else-if="networkStore.isOffline" class="text-[11px] text-[var(--text-muted)]">
          {{ t('Saved actions will sync automatically when you are back online.', 'ইন্টারনেট ফিরলে সংরক্ষিত অ্যাকশনগুলো স্বয়ংক্রিয়ভাবে সিঙ্ক হবে।') }}
        </p>
      </div>
    </transition>

    <!-- Sync success toast (small, anchored to the same corner) -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="networkStore.syncSuccessMessage"
        role="status"
        class="max-w-[min(20rem,calc(100vw-5.5rem))] px-3 py-2 rounded-xl bg-emerald-950/95 text-emerald-200 border border-emerald-500/40 shadow-xl flex items-center gap-2 text-xs font-semibold"
      >
        <svg class="w-4 h-4 text-emerald-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>{{ networkStore.syncSuccessMessage }}</span>
      </div>
    </transition>

    <!-- Mini corner button -->
    <button
      v-if="isVisible"
      type="button"
      class="relative w-11 h-11 rounded-full border shadow-lg backdrop-blur-xl flex items-center justify-center cursor-pointer active:scale-95 transition-all bg-[var(--bg-surface)]/95"
      :class="[tone.border, isOpen ? 'ring-2 ring-offset-2 ring-offset-[var(--bg-deep)] ' + tone.ring : '']"
      :aria-expanded="isOpen"
      aria-controls="network-status-panel"
      :aria-label="`${title}. ${description}`"
      :title="title"
      @click="isOpen = !isOpen"
    >
      <svg class="w-5 h-5" :class="[tone.text, { 'animate-spin': isSyncing }]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-html="tone.icon" />

      <!-- Pending count badge -->
      <span
        v-if="networkStore.pendingCount > 0"
        class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full text-[10px] font-black flex items-center justify-center shadow"
        :class="tone.badge"
      >{{ networkStore.pendingCount > 99 ? '99+' : formatNumber(networkStore.pendingCount, themeStore.locale) }}</span>
      <!-- Live dot when offline -->
      <span v-else-if="networkStore.isOffline" class="absolute top-0.5 right-0.5 flex h-2.5 w-2.5">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
      </span>
    </button>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import { useNetworkStore } from '../../stores/network';
import { useThemeStore } from '../../stores/theme';
import { offlineSync } from '../../services/offlineSync';
import { formatNumber } from '../../utils/locale';

const networkStore = useNetworkStore();
const themeStore = useThemeStore();
const t = (en: string, bn: string) => (themeStore.locale === 'bn' ? bn : en);

const isOpen = ref(false);
const rootRef = ref<HTMLElement | null>(null);

const isSyncing = computed(() => networkStore.syncStatus === 'syncing');
const isVisible = computed(
  () => networkStore.isOffline || isSyncing.value || networkStore.pendingCount > 0 || networkStore.syncStatus === 'failed'
);
const canSync = computed(
  () => networkStore.isOnline && (networkStore.pendingCount > 0 || networkStore.syncStatus === 'failed')
);

const ICONS = {
  offline: '<path d="M12 20h.01"/><path d="M8.5 16.43a5 5 0 0 1 7 0"/><path d="M5 12.86a10 10 0 0 1 5.17-2.69"/><path d="M19 12.86a10 10 0 0 0-2-1.3"/><path d="M2 8.82a15 15 0 0 1 4.18-2.64"/><path d="M22 8.82a15 15 0 0 0-11.29-3.76"/><line x1="2" y1="2" x2="22" y2="22"/>',
  syncing: '<path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/>',
  pending: '<path d="M4 14.9A7 7 0 1 1 15.7 8h1.8a4.5 4.5 0 0 1 2.5 8.2"/><path d="M12 12v9"/><path d="m16 16-4-4-4 4"/>',
  failed: '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
};

const state = computed<'offline' | 'syncing' | 'failed' | 'pending'>(() => {
  if (networkStore.isOffline) return 'offline';
  if (isSyncing.value) return 'syncing';
  if (networkStore.syncStatus === 'failed') return 'failed';
  return 'pending';
});

const tone = computed(() => ({
  offline: { text: 'text-amber-500', soft: 'bg-amber-500/15', border: 'border-amber-500/40', ring: 'ring-amber-500/60', badge: 'bg-amber-500 text-slate-950', icon: ICONS.offline },
  syncing: { text: 'text-sky-500', soft: 'bg-sky-500/15', border: 'border-sky-500/40', ring: 'ring-sky-500/60', badge: 'bg-sky-500 text-white', icon: ICONS.syncing },
  failed: { text: 'text-rose-500', soft: 'bg-rose-500/15', border: 'border-rose-500/40', ring: 'ring-rose-500/60', badge: 'bg-rose-500 text-white', icon: ICONS.failed },
  pending: { text: 'text-emerald-500', soft: 'bg-emerald-500/15', border: 'border-emerald-500/40', ring: 'ring-emerald-500/60', badge: 'bg-[var(--brand-gold)] text-slate-950', icon: ICONS.pending },
}[state.value]));

const title = computed(() => ({
  offline: t('Offline mode', 'অফলাইন মোড'),
  syncing: t('Syncing', 'সিঙ্ক হচ্ছে'),
  failed: t('Sync failed', 'সিঙ্ক ব্যর্থ হয়েছে'),
  pending: t('Online', 'অনলাইন'),
}[state.value]));

const description = computed(() => {
  const n = formatNumber(networkStore.pendingCount, themeStore.locale);
  return {
    offline: t('Showing cached data. Your actions are saved on this device.', 'লোকাল ক্যাশড ডেটা দেখানো হচ্ছে। আপনার অ্যাকশন এই ডিভাইসে সংরক্ষিত থাকছে।'),
    syncing: t('Sending saved data to the server…', 'সার্ভারে ডেটা পাঠানো হচ্ছে…'),
    failed: t('Some actions could not be sent. Try again.', 'কিছু অ্যাকশন পাঠানো যায়নি। আবার চেষ্টা করুন।'),
    pending: t(`${n} action(s) waiting to sync.`, `${n}টি অ্যাকশন সিঙ্কের অপেক্ষায়।`),
  }[state.value];
});

const handleManualSync = () => {
  offlineSync.syncNow();
};

// Collapse automatically once everything is synced
watch(isVisible, (visible) => {
  if (!visible) isOpen.value = false;
});

function onDocClick(e: MouseEvent) {
  if (isOpen.value && rootRef.value && !rootRef.value.contains(e.target as Node)) isOpen.value = false;
}
function onKeydown(e: KeyboardEvent) {
  if (e.key === 'Escape') isOpen.value = false;
}

onMounted(() => {
  document.addEventListener('click', onDocClick);
  document.addEventListener('keydown', onKeydown);
});
onBeforeUnmount(() => {
  document.removeEventListener('click', onDocClick);
  document.removeEventListener('keydown', onKeydown);
});
</script>
