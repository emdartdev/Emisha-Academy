<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">{{ $t('student.orders_title') }}</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1">{{ $t('student.orders_sub') }}</p>
      </div>
    </div>

    <!-- Loading Skeleton -->
    <div v-if="loading" class="space-y-4">
      <div v-for="i in 3" :key="i" class="h-20 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-2xl animate-pulse"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="orders.length === 0" class="text-center py-16 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-8">
      <div class="w-16 h-16 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      </div>
      <h3 class="text-lg font-bold text-[var(--text-primary)] mb-2">{{ $t('student.no_orders') }}</h3>
      <p class="text-sm text-[var(--text-secondary)] max-w-sm mx-auto mb-6">{{ $t('student.no_orders_sub') }}</p>
      <router-link to="/courses" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all">
        <span>{{ $t('nav.explore_courses') }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </router-link>
    </div>

    <!-- Orders Table / Cards -->
    <div v-else class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-2xl overflow-hidden shadow-sm">
      <div class="table-responsive-container">
        <table class="w-full text-left text-sm min-w-[650px]">
          <thead class="bg-[var(--bg-elevated)] border-b border-[var(--border-subtle)] text-xs text-[var(--text-secondary)] uppercase tracking-wider">
            <tr>
              <th class="py-4 px-6">{{ $t('student.order_no') }}</th>
              <th class="py-4 px-6">{{ $t('student.item_name') }}</th>
              <th class="py-4 px-6">{{ $t('common.date') }}</th>
              <th class="py-4 px-6">{{ $t('common.amount') }}</th>
              <th class="py-4 px-6">{{ $t('student.payment_method') }}</th>
              <th class="py-4 px-6">{{ $t('common.status') }}</th>
              <th class="py-4 px-6 text-right">{{ $t('common.actions') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-subtle)]">
            <tr v-for="order in orders" :key="order.id" class="hover:bg-[var(--bg-elevated)]/50 transition-colors">
              <td class="py-4 px-6 font-mono font-bold text-[var(--text-primary)] whitespace-nowrap">
                {{ order.order_number }}
              </td>
              <td class="py-4 px-6">
                <div class="font-medium text-[var(--text-primary)]">{{ order.items?.[0]?.item_name || 'Course Purchase' }}</div>
                <div v-if="order.items?.length > 1" class="text-xs text-[var(--text-secondary)]">
                  +{{ order.items.length - 1 }} {{ themeStore.locale === 'bn' ? 'আরও আইটেম' : 'more items' }}
                </div>
              </td>
              <td class="py-4 px-6 text-[var(--text-secondary)] whitespace-nowrap">
                {{ formatOrderDate(order.created_at) }}
              </td>
              <td class="py-4 px-6 font-bold text-[var(--text-primary)] whitespace-nowrap">
                {{ formatCurrency(order.total_amount, themeStore.locale) }}
              </td>
              <td class="py-4 px-6 whitespace-nowrap">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-[var(--bg-elevated)] text-[var(--text-secondary)] border border-[var(--border-subtle)] uppercase">
                  {{ order.payment_method || 'bKash/Nagad' }}
                </span>
              </td>
              <td class="py-4 px-6 whitespace-nowrap">
                <span :class="getStatusBadgeClass(order.payment_status)">
                  {{ getStatusText(order.payment_status) }}
                </span>
              </td>
              <td class="py-4 px-6 text-right whitespace-nowrap">
                <button
                  @click="openInvoiceModal(order)"
                  class="px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/10 text-[var(--text-primary)] hover:text-[#D4AF37] border border-[var(--border-subtle)] hover:border-[var(--border-accent)] transition-all text-xs font-medium inline-flex items-center gap-1.5 touch-target cursor-pointer"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  <span>{{ $t('student.invoice') }}</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Invoice Modal -->
    <div v-if="selectedOrder" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-2xl w-full max-h-[90dvh] overflow-y-auto p-5 sm:p-8 relative shadow-2xl safe-bottom">
        <!-- Close Button -->
        <button 
          @click="selectedOrder = null" 
          class="absolute top-4 right-4 sm:top-6 sm:right-6 w-10 h-10 rounded-full bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] flex items-center justify-center transition-colors touch-target cursor-pointer"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Invoice Header -->
        <div class="border-b border-[var(--border-subtle)] pb-5 mb-5 sm:pb-6 sm:mb-6 pr-8 sm:pr-0">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F7E7A9] flex items-center justify-center font-bold text-slate-950 shrink-0">
                EA
              </div>
              <div>
                <h2 class="text-lg sm:text-xl font-bold text-[var(--text-primary)] tracking-tight">Emisha Academy</h2>
                <p class="text-xs text-[var(--text-secondary)]">{{ $t('student.official_receipt') }}</p>
              </div>
            </div>
            <div class="sm:text-right">
              <span class="text-xs text-[var(--text-secondary)] block">{{ $t('student.invoice_id') }}</span>
              <span class="text-sm font-mono font-bold text-[#D4AF37]">INV-{{ selectedOrder.order_number }}</span>
            </div>
          </div>
        </div>

        <!-- Invoice Meta Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mb-6 p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs">
          <div>
            <span class="text-[var(--text-secondary)] block">{{ $t('student.customer_name') }}</span>
            <span class="font-bold text-[var(--text-primary)]">{{ selectedOrder.user?.name || 'Student' }}</span>
          </div>
          <div>
            <span class="text-[var(--text-secondary)] block">{{ $t('student.email') }}</span>
            <span class="font-bold text-[var(--text-primary)] truncate block">{{ selectedOrder.user?.email || 'N/A' }}</span>
          </div>
          <div>
            <span class="text-[var(--text-secondary)] block">{{ $t('common.date') }}:</span>
            <span class="font-bold text-[var(--text-primary)]">{{ formatOrderDate(selectedOrder.created_at) }}</span>
          </div>
          <div>
            <span class="text-[var(--text-secondary)] block">{{ themeStore.locale === 'bn' ? 'পেমেন্ট গেটওয়ে:' : 'Payment Gateway:' }}</span>
            <span class="font-bold text-[var(--text-primary)] uppercase">{{ selectedOrder.payment_method || 'Online' }}</span>
          </div>
          <div>
            <span class="text-[var(--text-secondary)] block">{{ $t('student.trx_id') }}</span>
            <span class="font-mono font-bold text-[var(--text-primary)]">{{ selectedOrder.payment?.transaction_id || 'TRX-VERIFIED' }}</span>
          </div>
          <div>
            <span class="text-[var(--text-secondary)] block">{{ $t('common.status') }}:</span>
            <span class="font-bold text-emerald-500 capitalize">{{ selectedOrder.payment_status }}</span>
          </div>
        </div>

        <!-- Line Items -->
        <div class="space-y-3 mb-6">
          <h4 class="text-xs font-bold text-[var(--text-secondary)] uppercase tracking-wider">
            {{ themeStore.locale === 'bn' ? 'কোর্স ও আইটেম তালিকা' : 'Course & Items List' }}
          </h4>
          <div class="border border-[var(--border-subtle)] rounded-xl divide-y divide-[var(--border-subtle)] overflow-hidden">
            <div 
              v-for="(item, idx) in selectedOrder.items" 
              :key="idx" 
              class="p-3 sm:p-4 flex items-center justify-between text-sm bg-[var(--bg-elevated)]/30 gap-3"
            >
              <div class="min-w-0">
                <p class="font-bold text-[var(--text-primary)] truncate">{{ item.item_name }}</p>
                <p class="text-xs text-[var(--text-secondary)]">
                  {{ themeStore.locale === 'bn' ? 'ব্যাচ:' : 'Batch:' }} {{ item.batch_name || (themeStore.locale === 'bn' ? 'রেগুলার ব্যাচ' : 'Regular Batch') }}
                </p>
              </div>
              <span class="font-bold text-[var(--text-primary)] shrink-0">{{ formatCurrency(item.unit_price, themeStore.locale) }}</span>
            </div>
          </div>
        </div>

        <!-- Financial Summary -->
        <div class="space-y-2 border-t border-[var(--border-subtle)] pt-4 text-sm max-w-xs ml-auto">
          <div class="flex justify-between text-[var(--text-secondary)]">
            <span>{{ $t('common.subtotal') }}:</span>
            <span>{{ formatCurrency(selectedOrder.subtotal || selectedOrder.total_amount, themeStore.locale) }}</span>
          </div>
          <div v-if="selectedOrder.discount_amount > 0" class="flex justify-between text-emerald-500">
            <span>{{ $t('common.discount') }}:</span>
            <span>- {{ formatCurrency(selectedOrder.discount_amount, themeStore.locale) }}</span>
          </div>
          <div class="flex justify-between text-base font-bold text-[var(--text-primary)] pt-2 border-t border-[var(--border-subtle)]">
            <span>{{ $t('common.total') }}:</span>
            <span class="text-[#D4AF37]">{{ formatCurrency(selectedOrder.total_amount, themeStore.locale) }}</span>
          </div>
        </div>

        <!-- Modal Actions -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 sm:mt-8 pt-5 sm:pt-6 border-t border-[var(--border-subtle)]">
          <button 
            @click="selectedOrder = null" 
            class="px-5 py-2.5 rounded-xl border border-[var(--border-subtle)] text-sm font-semibold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors touch-target text-center"
          >
            {{ $t('common.close') }}
          </button>
          <button 
            @click="printInvoice" 
            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-sm hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all flex items-center justify-center gap-2 touch-target"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>{{ $t('student.print_invoice') }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import { formatCurrency, formatDate } from '../../utils/locale';

interface OrderItem {
  item_name: string;
  batch_name?: string;
  unit_price: number;
}

interface Order {
  id: number;
  order_number: string;
  total_amount: number;
  subtotal?: number;
  discount_amount?: number;
  payment_status: string;
  payment_method: string;
  created_at: string;
  items?: OrderItem[];
  user?: { name: string; email: string };
  payment?: { transaction_id: string };
}

const orders = ref<Order[]>([]);
const loading = ref(true);
const selectedOrder = ref<Order | null>(null);
const toast = useToastStore();
const themeStore = useThemeStore();

async function fetchOrders() {
  try {
    loading.value = true;
    const res = await axios.get('/api/v1/student/orders');
    if (res.data.status === 'success') {
      orders.value = res.data.data.orders || [];
    }
  } catch (err) {
    toast.error(themeStore.locale === 'bn' ? 'অর্ডার হিস্ট্রি লোড করতে ব্যর্থ হয়েছে' : 'Failed to load order history');
  } finally {
    loading.value = false;
  }
}

function formatOrderDate(dateStr: string) {
  if (!dateStr) return '';
  return formatDate(dateStr, themeStore.locale);
}

function getStatusBadgeClass(status: string) {
  switch (status) {
    case 'paid':
    case 'completed':
      return 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20';
    case 'pending':
    case 'pending_verification':
      return 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20';
    case 'refunded':
      return 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20';
    default:
      return 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20';
  }
}

function getStatusText(status: string) {
  if (themeStore.locale === 'bn') {
    switch (status) {
      case 'paid':
      case 'completed':
        return 'সফল (Paid)';
      case 'pending':
      case 'pending_verification':
        return 'যাচাইাধীন (Pending)';
      case 'refunded':
        return 'রিফান্ডেড (Refunded)';
      default:
        return status;
    }
  } else {
    switch (status) {
      case 'paid':
      case 'completed':
        return 'Paid';
      case 'pending':
      case 'pending_verification':
        return 'Pending Verification';
      case 'refunded':
        return 'Refunded';
      default:
        return status;
    }
  }
}

function openInvoiceModal(order: Order) {
  selectedOrder.value = order;
}

function printInvoice() {
  window.print();
}

onMounted(() => {
  fetchOrders();
});
</script>
