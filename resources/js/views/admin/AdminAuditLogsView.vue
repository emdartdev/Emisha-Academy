<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">{{ $t('admin.audit_logs_title') }}</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1">{{ $t('admin.audit_logs_sub') }}</p>
      </div>
    </div>

    <!-- Filter & Event Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-2 border-b border-[var(--border-subtle)]">
      <button
        @click="eventFilter = ''; fetchLogs()"
        :class="[
          'px-4 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors touch-target cursor-pointer',
          eventFilter === '' ? 'bg-[var(--bg-elevated)] text-[var(--brand-gold)] border border-[var(--border-accent)] shadow-xs' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
        ]"
      >
        {{ $t('common.all') }} {{ themeStore.locale === 'bn' ? 'ইভেন্ট' : 'Events' }}
      </button>
      <button
        @click="eventFilter = 'created'; fetchLogs()"
        :class="[
          'px-4 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors touch-target cursor-pointer',
          eventFilter === 'created' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 shadow-xs' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
        ]"
      >
        {{ themeStore.locale === 'bn' ? 'তৈরি (Created)' : 'Created' }}
      </button>
      <button
        @click="eventFilter = 'updated'; fetchLogs()"
        :class="[
          'px-4 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors touch-target cursor-pointer',
          eventFilter === 'updated' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 shadow-xs' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
        ]"
      >
        {{ themeStore.locale === 'bn' ? 'আপডেট (Updated)' : 'Updated' }}
      </button>
      <button
        @click="eventFilter = 'deleted'; fetchLogs()"
        :class="[
          'px-4 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors touch-target cursor-pointer',
          eventFilter === 'deleted' ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 shadow-xs' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
        ]"
      >
        {{ themeStore.locale === 'bn' ? 'মুছে ফেলা (Deleted)' : 'Deleted' }}
      </button>
    </div>

    <!-- Loading Skeleton -->
    <div v-if="loading" class="space-y-3">
      <div v-for="i in 4" :key="i" class="h-16 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="logs.length === 0" class="text-center py-16 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-8">
      <p class="text-sm text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'কোনো অডিট লগ রেকর্ড পাওয়া যায়নি।' : 'No audit log records found.' }}
      </p>
    </div>

    <!-- Logs Table -->
    <div v-else class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-2xl overflow-hidden shadow-sm">
      <div class="table-responsive-container">
        <table class="w-full text-left text-xs min-w-[700px]">
          <thead class="bg-[var(--bg-elevated)] border-b border-[var(--border-subtle)] text-[var(--text-secondary)] uppercase tracking-wider">
            <tr>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'তারিখ ও সময়' : 'Date & Time' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'ইউজার / অ্যাক্টর' : 'User / Actor' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'ইভেন্ট টাইপ' : 'Event Type' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'মডেল অবজেক্ট' : 'Target Entity' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'বিবরণ / পরিবর্তন' : 'Description / Change' }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-subtle)]">
            <tr v-for="log in logs" :key="log.id" class="hover:bg-[var(--bg-elevated)]/40 transition-colors">
              <td class="py-4 px-6 text-[var(--text-secondary)] font-mono whitespace-nowrap">
                {{ formatDateTime(log.created_at) }}
              </td>
              <td class="py-4 px-6">
                <span v-if="log.causer" class="font-bold text-[var(--text-primary)] block">{{ log.causer.name }}</span>
                <span v-else class="text-[var(--text-secondary)] font-mono">
                  {{ themeStore.locale === 'bn' ? 'সিস্টেম / অটোমেটেড' : 'System / Automated' }}
                </span>
              </td>
              <td class="py-4 px-6 whitespace-nowrap">
                <span :class="getEventBadgeClass(log.event)">
                  {{ log.event || 'action' }}
                </span>
              </td>
              <td class="py-4 px-6 font-mono text-[var(--text-primary)] whitespace-nowrap">
                {{ formatSubject(log.subject_type) }} #{{ log.subject_id }}
              </td>
              <td class="py-4 px-6 text-[var(--text-secondary)] max-w-xs truncate">
                {{ log.description || 'System state changed' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { apiClient } from '../../api/client';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';

const logs = ref<any[]>([]);
const loading = ref(true);
const eventFilter = ref('');
const toast = useToastStore();
const themeStore = useThemeStore();

async function fetchLogs() {
  try {
    loading.value = true;
    const res = await apiClient.get('/admin/audit-logs', {
      params: {
        event: eventFilter.value || undefined,
      },
    });
    if (res.data.status === 'success') {
      logs.value = res.data.data.data || res.data.data || [];
    }
  } catch (err) {
    toast.error(themeStore.locale === 'bn' ? 'অডিট লগ লোড করতে ব্যর্থ হয়েছে' : 'Failed to load audit logs');
  } finally {
    loading.value = false;
  }
}

function formatDateTime(dateStr: string) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleString(themeStore.locale === 'bn' ? 'bn-BD' : 'en-US', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

function formatSubject(subjectType: string) {
  if (!subjectType) return 'N/A';
  return subjectType.split('\\').pop() || subjectType;
}

function getEventBadgeClass(event: string) {
  switch (event) {
    case 'created':
      return 'inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 uppercase';
    case 'updated':
      return 'inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 uppercase';
    case 'deleted':
      return 'inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 uppercase';
    default:
      return 'inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-slate-500/10 text-slate-600 dark:text-slate-400 uppercase';
  }
}

onMounted(() => {
  fetchLogs();
});
</script>
