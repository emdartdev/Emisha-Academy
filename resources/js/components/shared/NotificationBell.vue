<template>
  <div ref="rootRef" class="relative">
    <button
      type="button"
      class="relative p-2 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-card)] border border-[var(--border-subtle)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] touch-target flex items-center justify-center cursor-pointer transition-colors"
      :title="t('Notifications', 'নোটিফিকেশন')"
      :aria-label="t('Notifications', 'নোটিফিকেশন')"
      @click="toggle"
    >
      <svg class="w-4 h-4" :class="{ 'text-[#D4AF37]': store.unreadCount > 0 }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
      </svg>
      <span
        v-if="store.unreadCount > 0"
        class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-black flex items-center justify-center shadow"
      >{{ store.unreadCount > 99 ? '99+' : formatNumber(store.unreadCount, themeStore.locale) }}</span>
    </button>

    <transition
      enter-active-class="transition ease-out duration-150"
      enter-from-class="opacity-0 translate-y-1"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="open"
        class="fixed sm:absolute left-3 right-3 sm:left-auto sm:right-0 top-16 sm:top-auto sm:mt-2 sm:w-96 z-50 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] shadow-2xl overflow-hidden"
      >
        <div class="flex items-center justify-between px-4 py-3 border-b border-[var(--border-subtle)]">
          <p class="text-sm font-black text-[var(--text-primary)]">{{ t('Notifications', 'নোটিফিকেশন') }}</p>
          <button
            v-if="store.unreadCount > 0"
            type="button"
            class="text-[11px] font-bold text-[#D4AF37] hover:underline cursor-pointer"
            @click="store.markAllRead()"
          >{{ t('Mark all as read', 'সব পড়া হয়েছে') }}</button>
        </div>

        <div class="max-h-[60vh] overflow-y-auto">
          <div v-if="store.loading && store.items.length === 0" class="p-4 space-y-2">
            <div v-for="i in 3" :key="i" class="h-14 rounded-xl bg-[var(--bg-elevated)] animate-pulse"></div>
          </div>
          <p v-else-if="store.items.length === 0" class="p-8 text-center text-xs text-[var(--text-muted)]">
            {{ t("You're all caught up.", 'কোনো নোটিফিকেশন নেই।') }}
          </p>
          <button
            v-for="n in store.items.slice(0, 8)"
            :key="n.id"
            type="button"
            class="w-full text-left flex gap-3 px-4 py-3 border-b border-[var(--border-subtle)] last:border-b-0 hover:bg-[var(--bg-hover)] transition-colors cursor-pointer"
            :class="{ 'bg-[#D4AF37]/[0.06]': !n.read_at }"
            @click="openNotification(n)"
          >
            <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-sm" :class="iconClass(n.type)">{{ icon(n.type) }}</span>
            <span class="min-w-0 flex-1">
              <span class="block text-xs font-bold text-[var(--text-primary)] line-clamp-2">{{ n.title }}</span>
              <span v-if="n.message" class="block text-[11px] text-[var(--text-secondary)] line-clamp-2 mt-0.5">{{ n.message }}</span>
              <span class="block text-[10px] text-[var(--text-muted)] mt-1">{{ timeAgo(n.created_at) }}</span>
            </span>
            <span v-if="!n.read_at" class="w-2 h-2 rounded-full bg-[#D4AF37] mt-1.5 shrink-0"></span>
          </button>
        </div>

        <router-link
          to="/student/notifications"
          class="block text-center text-xs font-bold text-[#D4AF37] py-2.5 border-t border-[var(--border-subtle)] hover:bg-[var(--bg-hover)]"
          @click="open = false"
        >{{ t('View all notifications', 'সব নোটিফিকেশন দেখুন') }}</router-link>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import { useThemeStore } from '../../stores/theme';
import { useNotificationStore, type StudentNotification } from '../../stores/notifications';
import { formatNumber } from '../../utils/locale';
import { notificationIcon, notificationIconClass, notificationTimeAgo } from '../../utils/notificationFormat';

const store = useNotificationStore();
const themeStore = useThemeStore();
const router = useRouter();
const t = (en: string, bn: string) => (themeStore.locale === 'bn' ? bn : en);

const open = ref(false);
const rootRef = ref<HTMLElement | null>(null);

const icon = notificationIcon;
const iconClass = notificationIconClass;
const timeAgo = (d: string) => notificationTimeAgo(d, themeStore.locale);

function toggle() {
  open.value = !open.value;
  if (open.value) store.fetchLatest(1);
}

async function openNotification(n: StudentNotification) {
  open.value = false;
  store.markRead(n.id);
  if (n.link) router.push(n.link);
}

function onDocClick(e: MouseEvent) {
  if (open.value && rootRef.value && !rootRef.value.contains(e.target as Node)) {
    open.value = false;
  }
}

onMounted(() => {
  store.startPolling();
  document.addEventListener('click', onDocClick);
});

onBeforeUnmount(() => {
  store.stopPolling();
  document.removeEventListener('click', onDocClick);
});
</script>
