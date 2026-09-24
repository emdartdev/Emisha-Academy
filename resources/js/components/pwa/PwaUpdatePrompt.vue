<template>
  <div>
    <!-- New Version Available Notification -->
    <transition
      enter-active-class="transition ease-out duration-300 transform"
      enter-from-class="translate-y-full opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition ease-in duration-200 transform"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="translate-y-full opacity-0"
    >
      <div
        v-if="networkStore.isUpdateAvailable"
        class="fixed bottom-4 left-4 z-50 p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--brand-gold)] shadow-2xl shadow-black/50 text-[var(--text-primary)] max-w-sm flex items-center justify-between gap-3"
      >
        <div class="space-y-0.5">
          <p class="text-xs font-bold text-[var(--brand-gold)] flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-[var(--brand-gold)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4"/><path d="M12 18v4"/><path d="m4.93 4.93 2.83 2.83"/><path d="m16.24 16.24 2.83 2.83"/><path d="M2 12h4"/><path d="M18 12h4"/><path d="m4.93 19.07 2.83-2.83"/><path d="m16.24 7.76 2.83-2.83"/></svg>
            <span>নতুন সংস্করণ উপলব্ধ!</span>
          </p>
          <p class="text-[11px] text-[var(--text-secondary)]">নতুন ফিচার ও পারফরম্যান্স আপডেটের জন্য অ্যাপ রিলোড করুন।</p>
        </div>
        <button
          @click="networkStore.applyUpdate()"
          class="shrink-0 px-3 py-1.5 rounded-xl bg-[var(--brand-gold)] text-slate-950 font-bold text-xs hover:opacity-90 active:scale-95 transition-all shadow-xs cursor-pointer"
        >
          আপডেট
        </button>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { useNetworkStore } from '../../stores/network';

const networkStore = useNetworkStore();
</script>
