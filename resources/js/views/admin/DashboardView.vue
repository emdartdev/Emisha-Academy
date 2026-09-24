<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)] tracking-tight">{{ $t('admin.dashboard_title') }}</h1>
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] mt-1">{{ $t('admin.dashboard_sub') }}</p>
      </div>

      <div class="flex flex-wrap items-center gap-2 sm:gap-3">
        <router-link
          to="/admin/courses"
          class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all inline-flex items-center justify-center gap-2 touch-target w-full sm:w-auto"
        >
          <span>{{ $t('admin.create_course') }}</span>
        </router-link>
        <router-link
          to="/admin/orders"
          class="px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-surface)] text-[var(--text-primary)] border border-[var(--border-subtle)] font-medium text-xs transition-colors inline-flex items-center justify-center gap-2 touch-target w-full sm:w-auto"
        >
          <span class="inline-flex items-center gap-1.5"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>{{ $t('admin.payment_queue') }}</span>
          <span v-if="metrics.pending_verifications > 0" class="px-1.5 py-0.5 rounded-full bg-amber-500 text-slate-950 text-[10px] font-bold">
            {{ formatNumber(metrics.pending_verifications, themeStore.locale) }}
          </span>
        </router-link>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
      <div v-for="i in 4" :key="i" class="h-28 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
    </div>

    <!-- KPI Metric Cards -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
      <div class="p-5 sm:p-6 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[var(--border-accent)] transition-all">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[var(--text-secondary)] uppercase tracking-wider">{{ $t('admin.total_revenue') }}</span>
          <svg class="w-6 h-6 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" y1="6" x2="12" y2="8"/><line x1="12" y1="16" x2="12" y2="18"/></svg>
        </div>
        <p class="text-xl sm:text-2xl font-bold text-[var(--text-primary)] mt-3 font-mono">
          {{ formatCurrency(metrics.total_revenue || 0, themeStore.locale) }}
        </p>
        <p class="text-xs text-emerald-500 dark:text-emerald-400 mt-1">
          {{ $t('admin.this_month') }} {{ formatCurrency(metrics.this_month_revenue || 0, themeStore.locale) }}
        </p>
      </div>

      <div class="p-5 sm:p-6 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[var(--border-accent)] transition-all">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[var(--text-secondary)] uppercase tracking-wider">{{ $t('admin.total_students') }}</span>
          <svg class="w-6 h-6 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        </div>
        <p class="text-xl sm:text-2xl font-bold text-[var(--text-primary)] mt-3 font-mono">
          {{ formatNumber(metrics.total_students || 0, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'জন' : 'Students' }}
        </p>
        <p class="text-xs text-[var(--text-secondary)] mt-1">
          {{ formatNumber(metrics.active_enrollments || 0, themeStore.locale) }} {{ $t('admin.active_enrollments') }}
        </p>
      </div>

      <div class="p-5 sm:p-6 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[var(--border-accent)] transition-all">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[var(--text-secondary)] uppercase tracking-wider">{{ $t('admin.pending_verifications') }}</span>
          <svg class="w-6 h-6 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <p class="text-xl sm:text-2xl font-bold text-[var(--brand-gold)] mt-3 font-mono">
          {{ formatNumber(metrics.pending_verifications || 0, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি' : 'Pending' }}
        </p>
        <p class="text-xs text-amber-500 dark:text-amber-400 mt-1">{{ $t('admin.awaiting_verification') }}</p>
      </div>

      <div class="p-5 sm:p-6 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[var(--border-accent)] transition-all">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[var(--text-secondary)] uppercase tracking-wider">{{ $t('admin.total_leads') }}</span>
          <svg class="w-6 h-6 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
        </div>
        <p class="text-xl sm:text-2xl font-bold text-[var(--text-primary)] mt-3 font-mono">
          {{ formatNumber(metrics.total_leads || 0, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি' : 'Total' }}
        </p>
        <p class="text-xs text-sky-500 dark:text-sky-400 mt-1">
          {{ formatNumber(metrics.new_leads || 0, themeStore.locale) }} {{ $t('admin.new_leads') }}
        </p>
      </div>
    </div>

    <!-- Main Grid: Recent Orders & Recent Leads -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
      <!-- Recent Orders (2 cols) -->
      <div class="lg:col-span-2 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-5 sm:p-6 space-y-5 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-[var(--border-subtle)]">
          <div>
            <h3 class="text-base font-bold text-[var(--text-primary)]">{{ $t('admin.recent_orders') }}</h3>
            <p class="text-xs text-[var(--text-secondary)]">{{ $t('admin.recent_orders_sub') }}</p>
          </div>
          <router-link to="/admin/orders" class="text-xs font-semibold text-[var(--brand-gold)] hover:underline touch-target inline-flex items-center">
            {{ $t('admin.view_all_orders') }}
          </router-link>
        </div>

        <div v-if="recentOrders.length === 0" class="text-center py-8 text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'কোনো সাম্প্রতিক অর্ডার রেকর্ড নেই' : 'No recent orders found' }}
        </div>

        <div v-else class="table-responsive-container">
          <table class="w-full text-left text-xs min-w-[500px]">
            <thead class="text-[var(--text-secondary)] border-b border-[var(--border-subtle)]">
              <tr>
                <th class="pb-3">{{ $t('admin.order_number') }}</th>
                <th class="pb-3">{{ $t('admin.student_name') }}</th>
                <th class="pb-3">{{ $t('common.amount') }}</th>
                <th class="pb-3">{{ $t('student.payment_method') }}</th>
                <th class="pb-3">{{ $t('common.status') }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border-subtle)]">
              <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-[var(--bg-elevated)]/40 transition-colors">
                <td class="py-3 font-mono font-bold text-[var(--text-primary)] whitespace-nowrap">
                  {{ order.order_number }}
                </td>
                <td class="py-3">
                  <span class="font-medium text-[var(--text-primary)] block">{{ order.user?.name || 'Student' }}</span>
                  <span class="text-[10px] text-[var(--text-secondary)]">{{ order.user?.phone }}</span>
                </td>
                <td class="py-3 font-bold text-[var(--text-primary)] whitespace-nowrap">
                  {{ formatCurrency(order.total_amount, themeStore.locale) }}
                </td>
                <td class="py-3 uppercase text-[var(--text-secondary)] whitespace-nowrap">
                  {{ order.payment_method }}
                </td>
                <td class="py-3 whitespace-nowrap">
                  <span :class="getStatusBadgeClass(order.payment_status)">
                    {{ order.payment_status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Leads (1 col) -->
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-6 space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-[var(--border-subtle)]">
          <div>
            <h3 class="text-base font-bold text-[var(--text-primary)]">{{ $t('admin.recent_leads') }}</h3>
            <p class="text-xs text-[var(--text-secondary)]">{{ $t('admin.recent_leads_sub') }}</p>
          </div>
          <router-link to="/admin/leads" class="text-xs font-semibold text-[var(--brand-gold)] hover:underline">
            {{ $t('admin.view_all_leads') }}
          </router-link>
        </div>

        <div v-if="recentLeads.length === 0" class="text-center py-8 text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'কোনো নতুন লিড নেই' : 'No new leads found' }}
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="lead in recentLeads"
            :key="lead.id"
            class="p-3.5 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] flex items-center justify-between gap-3"
          >
            <div class="min-w-0">
              <p class="text-xs font-bold text-[var(--text-primary)] truncate">{{ lead.name }}</p>
              <p class="text-[10px] text-[var(--text-secondary)] font-mono">{{ lead.phone }}</p>
              <p v-if="lead.interested_topic" class="text-[10px] text-[var(--brand-gold)] truncate mt-0.5">{{ lead.interested_topic }}</p>
            </div>
            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-sky-500/10 text-sky-500 dark:text-sky-400 border border-sky-500/20 capitalize shrink-0">
              {{ lead.status }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { apiClient } from '../../api/client';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import { formatCurrency, formatNumber } from '../../utils/locale';

const loading = ref(true);
const metrics = ref<any>({});
const recentOrders = ref<any[]>([]);
const recentLeads = ref<any[]>([]);
const toast = useToastStore();
const themeStore = useThemeStore();

async function fetchDashboardData() {
  try {
    loading.value = true;
    const res = await apiClient.get('/admin/dashboard');
    if (res.data.status === 'success') {
      metrics.value = res.data.data.metrics || {};
      recentOrders.value = res.data.data.recent_orders || [];
      recentLeads.value = res.data.data.recent_leads || [];
    }
  } catch (err) {
    toast.error(themeStore.locale === 'bn' ? 'ড্যাশবোর্ড ডেটা লোড করতে ব্যর্থ হয়েছে' : 'Failed to load dashboard data');
  } finally {
    loading.value = false;
  }
}

function getStatusBadgeClass(status: string) {
  switch (status) {
    case 'paid':
    case 'completed':
      return 'inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20';
    case 'pending_verification':
    case 'pending':
      return 'inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20';
    case 'refunded':
      return 'inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20';
    default:
      return 'inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-slate-500/10 text-slate-600 dark:text-slate-400';
  }
}

onMounted(() => {
  fetchDashboardData();
});
</script>
