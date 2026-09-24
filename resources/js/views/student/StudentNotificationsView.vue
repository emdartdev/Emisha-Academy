<template>
  <div class="max-w-3xl mx-auto space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <h1 class="text-lg sm:text-xl font-black text-[var(--text-primary)]">{{ t('Notifications', 'নোটিফিকেশন') }}</h1>
        <p class="text-xs text-[var(--text-secondary)] mt-0.5">
          {{ t('New notices, modules and lessons from your courses.', 'আপনার কোর্সের নতুন নোটিশ, মডিউল ও লেসনের আপডেট।') }}
        </p>
      </div>
      <button
        v-if="store.unreadCount > 0"
        type="button"
        class="px-4 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] hover:border-[#D4AF37] cursor-pointer"
        @click="store.markAllRead()"
      >{{ t('Mark all as read', 'সব পড়া হয়েছে হিসেবে চিহ্নিত করুন') }}</button>
    </div>

    <div class="rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] overflow-hidden">
      <div v-if="store.loading && store.items.length === 0" class="p-4 space-y-2">
        <div v-for="i in 5" :key="i" class="h-16 rounded-xl bg-[var(--bg-elevated)] animate-pulse"></div>
      </div>
      <p v-else-if="store.items.length === 0" class="p-12 text-center text-xs text-[var(--text-muted)]">
        {{ t('No notifications yet.', 'এখনও কোনো নোটিফিকেশন নেই।') }}
      </p>
      <button
        v-for="n in store.items"
        :key="n.id"
        type="button"
        class="w-full text-left flex gap-3 px-4 sm:px-5 py-4 border-b border-[var(--border-subtle)] last:border-b-0 hover:bg-[var(--bg-hover)] transition-colors cursor-pointer"
        :class="{ 'bg-[#D4AF37]/[0.06]': !n.read_at }"
        @click="open(n)"
      >
        <span class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" :class="notificationIconClass(n.type)">{{ notificationIcon(n.type) }}</span>
        <span class="min-w-0 flex-1">
          <span class="block text-sm font-bold text-[var(--text-primary)]">{{ n.title }}</span>
          <span v-if="n.message" class="block text-xs text-[var(--text-secondary)] mt-0.5">{{ n.message }}</span>
          <span class="block text-[11px] text-[var(--text-muted)] mt-1">{{ notificationTimeAgo(n.created_at, themeStore.locale) }}</span>
        </span>
        <span v-if="!n.read_at" class="w-2.5 h-2.5 rounded-full bg-[#D4AF37] mt-1.5 shrink-0"></span>
      </button>
    </div>

    <div v-if="store.hasMore" class="text-center">
      <button
        type="button"
        class="px-5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] cursor-pointer disabled:opacity-50"
        :disabled="store.loading"
        @click="store.fetchLatest(store.page + 1)"
      >{{ store.loading ? t('Loading…', 'লোড হচ্ছে…') : t('Load more', 'আরও দেখুন') }}</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useThemeStore } from '../../stores/theme';
import { useNotificationStore, type StudentNotification } from '../../stores/notifications';
import { notificationIcon, notificationIconClass, notificationTimeAgo } from '../../utils/notificationFormat';

const store = useNotificationStore();
const themeStore = useThemeStore();
const router = useRouter();
const t = (en: string, bn: string) => (themeStore.locale === 'bn' ? bn : en);

function open(n: StudentNotification) {
  store.markRead(n.id);
  if (n.link) router.push(n.link);
}

onMounted(() => store.fetchLatest(1));
</script>
