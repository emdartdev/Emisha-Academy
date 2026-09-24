<template>
  <div class="space-y-8">
    <!-- Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">
          {{ themeStore.locale === 'bn' ? 'অর্ডার ও পেমেন্ট ভেরিফিকেশন হাব' : 'Orders & Payment Verification Hub' }}
        </h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1">
          {{ themeStore.locale === 'bn' ? 'BRAC Bank, bKash Merchant (QR) ও bKash Personal ট্রানজ্যাকশন পর্যবেক্ষণ, অনুমোদন ও রিজেকশন কিউ।' : 'Monitor, verify, approve, and reject BRAC Bank & bKash manual payment orders.' }}
        </p>
      </div>

      <div class="flex items-center gap-3">
        <button
          type="button"
          @click="fetchOrders"
          class="px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] flex items-center gap-2 transition-all cursor-pointer shadow-xs"
        >
          <svg class="w-3.5 h-3.5" :class="{ 'animate-spin': loading }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'রিফ্রেশ' : 'Refresh' }}</span>
        </button>
      </div>
    </div>

    <!-- Quick Metrics Strip -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xs">
        <span class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider block">
          {{ themeStore.locale === 'bn' ? 'যাচাইাধীন অর্ডার (Pending)' : 'Pending Verification' }}
        </span>
        <div class="text-2xl font-black text-amber-500 mt-1">
          {{ pendingCount }}
        </div>
      </div>

      <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xs">
        <span class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider block">
          {{ themeStore.locale === 'bn' ? 'অনুমোদিত ও সফল অর্ডার' : 'Approved Orders' }}
        </span>
        <div class="text-2xl font-black text-emerald-500 mt-1">
          {{ approvedCount }}
        </div>
      </div>

      <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xs">
        <span class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider block">
          {{ themeStore.locale === 'bn' ? 'মোট সংগৃহীত পেমেন্ট' : 'Total Revenue' }}
        </span>
        <div class="text-2xl font-black text-[#D4AF37] mt-1">
          {{ formatCurrency(totalRevenue, themeStore.locale) }}
        </div>
      </div>

      <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xs">
        <span class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider block">
          {{ themeStore.locale === 'bn' ? 'মোট অর্ডার সংখ্যা' : 'Total Orders' }}
        </span>
        <div class="text-2xl font-black text-[var(--text-primary)] mt-1">
          {{ totalOrdersCount }}
        </div>
      </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-xs">
      
      <!-- Status Tabs -->
      <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 md:pb-0">
        <button
          @click="filterStatus = ''; fetchOrders()"
          :class="[
            'px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors touch-target cursor-pointer',
            filterStatus === '' ? 'bg-[var(--bg-elevated)] text-[var(--brand-gold)] border border-[var(--border-accent)] shadow-xs' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
        >
          {{ $t('common.all') }} ({{ totalOrdersCount }})
        </button>
        <button
          @click="filterStatus = 'pending_verification'; fetchOrders()"
          :class="[
            'px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors flex items-center gap-1.5 touch-target cursor-pointer',
            filterStatus === 'pending_verification' ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 shadow-xs' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
        >
          <span class="w-2 h-2 rounded-full bg-amber-500"></span>
          <span>{{ themeStore.locale === "bn" ? "যাচাইাধীন" : "Pending" }} ({{ pendingCount }})</span>
        </button>
        <button
          @click="filterStatus = 'paid'; fetchOrders()"
          :class="[
            'px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors touch-target cursor-pointer',
            filterStatus === 'paid' ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 shadow-xs' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
        >
          {{ themeStore.locale === 'bn' ? 'অনুমোদিত (Paid)' : 'Paid' }}
        </button>
        <button
          @click="filterStatus = 'rejected'; fetchOrders()"
          :class="[
            'px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors touch-target cursor-pointer',
            filterStatus === 'rejected' ? 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30 shadow-xs' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
        >
          {{ themeStore.locale === 'bn' ? 'বাতিলকৃত' : 'Rejected' }}
        </button>
        <button
          @click="filterStatus = 'refunded'; fetchOrders()"
          :class="[
            'px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors touch-target cursor-pointer',
            filterStatus === 'refunded' ? 'bg-purple-500/15 text-purple-600 dark:text-purple-400 border border-purple-500/30 shadow-xs' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
        >
          {{ themeStore.locale === 'bn' ? 'রিফান্ডেড' : 'Refunded' }}
        </button>
      </div>

      <!-- Search Box -->
      <div class="relative w-full md:w-80">
        <input
          v-model="searchQuery"
          @input="handleSearch"
          type="text"
          :placeholder="themeStore.locale === 'bn' ? 'অর্ডার নং, নাম, TrxID বা ফোন খুঁজুন...' : 'Search Order #, Name, TrxID...'"
          class="w-full pl-9 pr-4 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
        />
        <svg class="w-4 h-4 text-[var(--text-muted)] absolute left-3 top-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </div>

    </div>

    <!-- Loading Skeleton -->
    <div v-if="loading" class="space-y-3">
      <div v-for="i in 4" :key="i" class="h-20 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="orders.length === 0" class="text-center py-16 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-8">
      <p class="text-sm text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'কোনো অর্ডার রেকর্ড পাওয়া যায়নি।' : 'No order records found.' }}
      </p>
    </div>

    <!-- Orders Table -->
    <div v-else class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-2xl overflow-hidden shadow-sm">
      <div class="table-responsive-container">
        <table class="w-full text-left text-xs min-w-[900px]">
          <thead class="bg-[var(--bg-elevated)] border-b border-[var(--border-subtle)] text-[var(--text-secondary)] uppercase tracking-wider">
            <tr>
              <th class="py-4 px-5">{{ $t('admin.order_number') }}</th>
              <th class="py-4 px-5">{{ $t('admin.student_name') }}</th>
              <th class="py-4 px-5">{{ $t('student.item_name') }}</th>
              <th class="py-4 px-5">{{ $t('common.amount') }}</th>
              <th class="py-4 px-5">পেমেন্ট মেথড ও TrxID</th>
              <th class="py-4 px-5">{{ $t('common.status') }}</th>
              <th class="py-4 px-5 text-right">{{ $t('common.actions') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-subtle)]">
            <tr v-for="order in orders" :key="order.id" class="hover:bg-[var(--bg-elevated)]/40 transition-colors">
              
              <!-- Order Number & Date -->
              <td class="py-4 px-5 font-mono whitespace-nowrap">
                <div class="font-bold text-[var(--text-primary)] hover:text-[var(--brand-gold)] cursor-pointer" @click="viewOrderDetails(order)">
                  {{ order.order_number }}
                </div>
                <div class="text-[10px] text-[var(--text-muted)] mt-0.5">
                  {{ order.created_at || 'Recently' }}
                </div>
              </td>

              <!-- Customer Info -->
              <td class="py-4 px-5">
                <div class="font-bold text-[var(--text-primary)]">{{ order.user?.name || order.payment?.gateway_response?.customer_name || 'Learner' }}</div>
                <div class="text-[10px] text-[var(--text-secondary)] flex items-center gap-1 mt-0.5">
                  <span>{{ order.user?.phone || order.payment?.gateway_response?.customer_phone || '-' }}</span>
                  <a
                    v-if="order.user?.phone || order.payment?.gateway_response?.customer_phone"
                    :href="`https://wa.me/88${(order.user?.phone || order.payment?.gateway_response?.customer_phone).replace(/[^0-9]/g, '')}`"
                    target="_blank"
                    class="text-emerald-500 hover:text-emerald-400 ml-0.5 -my-2 p-2 inline-flex items-center justify-center min-w-[36px] min-h-[36px]"
                    title="WhatsApp"
                  >
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.04 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
                  </a>
                </div>
              </td>

              <!-- Item -->
              <td class="py-4 px-5">
                <div class="font-medium text-[var(--text-primary)] max-w-[200px] truncate">
                  {{ order.items?.[0]?.item_name || order.item_name || 'Course Purchase' }}
                </div>
                <div v-if="order.items?.[0]?.batch_id" class="text-[10px] text-[var(--brand-gold)] mt-0.5">
                  ব্যাচ এনরোলমেন্ট
                </div>
              </td>

              <!-- Amount -->
              <td class="py-4 px-5 font-black text-[var(--text-primary)] whitespace-nowrap text-sm text-[#D4AF37]">
                {{ formatCurrency(order.total_amount, themeStore.locale) }}
              </td>

              <!-- Payment Channel & TrxID -->
              <td class="py-4 px-5 whitespace-nowrap">
                <div class="flex items-center gap-1.5">
                  <span :class="getPaymentMethodBadgeClass(order.payment?.payment_method || order.payment_method)">
                    {{ formatPaymentMethod(order.payment?.payment_method || order.payment_method) }}
                  </span>
                </div>
                <div v-if="order.payment?.transaction_id" class="flex items-center gap-1 font-mono text-[11px] text-[var(--text-primary)] font-bold mt-1">
                  <span>Trx: {{ order.payment.transaction_id }}</span>
                  <button
                    type="button"
                    @click="copyTrx(order.payment.transaction_id)"
                    class="text-[var(--text-muted)] hover:text-[#D4AF37] -my-2 p-2 inline-flex items-center justify-center min-w-[36px] min-h-[36px] cursor-pointer"
                    title="Copy TrxID"
                  >
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                  </button>
                </div>
                <div v-if="order.payment?.gateway_response?.sender_number" class="text-[10px] text-[var(--text-muted)]">
                  Sender: {{ order.payment.gateway_response.sender_number }}
                </div>
              </td>

              <!-- Status -->
              <td class="py-4 px-5 whitespace-nowrap">
                <span :class="getStatusBadgeClass(order.payment_status || order.status)">
                  {{ formatStatusText(order.payment_status || order.status) }}
                </span>
              </td>

              <!-- Actions -->
              <td class="py-4 px-5 text-right whitespace-nowrap space-x-1.5">
                
                <!-- View Details / Receipt Button -->
                <button
                  type="button"
                  @click="viewOrderDetails(order)"
                  class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer inline-flex items-center gap-1"
                  title="View Details"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  <span>{{ themeStore.locale === 'bn' ? 'রসিদ' : 'Receipt' }}</span>
                </button>

                <!-- Verify / Approve Button for Pending -->
                <button
                  v-if="isPendingOrder(order)"
                  @click="verifyOrderPayment(order.id)"
                  :disabled="actionLoading === order.id"
                  class="px-3 py-1.5 rounded-lg bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 text-[11px] font-bold transition-all inline-flex items-center gap-1 cursor-pointer"
                >
                  <span v-if="actionLoading === order.id">{{ themeStore.locale === 'bn' ? 'যাচাই হচ্ছে...' : '...' }}</span>
                  <span v-else class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>{{ themeStore.locale === 'bn' ? 'অনুমোদন' : 'Approve' }}</span>
                </button>

                <!-- Reject Button for Pending -->
                <button
                  v-if="isPendingOrder(order)"
                  @click="promptRejectOrder(order)"
                  :disabled="actionLoading === order.id"
                  class="px-2.5 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/25 text-[11px] font-bold transition-all inline-flex items-center gap-1 cursor-pointer"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  <span>{{ themeStore.locale === 'bn' ? 'বাতিল' : 'Reject' }}</span>
                </button>

                <!-- Refund Button for Paid -->
                <button
                  v-if="isPaidOrder(order)"
                  @click="refundOrder(order.id)"
                  :disabled="actionLoading === order.id"
                  class="px-2.5 py-1.5 rounded-lg bg-purple-500/10 hover:bg-purple-500/20 text-purple-600 dark:text-purple-400 border border-purple-500/25 text-[11px] font-medium transition-all inline-flex items-center cursor-pointer"
                >
                  {{ $t('admin.refund') }}
                </button>

              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Rejection Reason Modal -->
    <AppModal v-model="isRejectModalOpen" :title="themeStore.locale === 'bn' ? 'পেমেন্ট বাতিলকরণ (Reject Payment)' : 'Reject Payment'" size="sm">
      <div class="space-y-4 p-1">
        <p class="text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'অর্ডার নম্বর: ' : 'Order: ' }}
          <strong class="text-[var(--text-primary)] font-mono">{{ activeRejectOrder?.order_number }}</strong>
        </p>

        <div class="space-y-1.5">
          <label class="text-xs font-bold text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'বাতিল করার কারণ (Rejection Reason)' : 'Rejection Reason' }}
          </label>
          <textarea
            v-model="rejectReason"
            rows="3"
            :placeholder="themeStore.locale === 'bn' ? 'যেমন: ট্রানজ্যাকশন আইডি ভুল অথবা অ্যাকাউন্টে টাকা জমা হয়নি...' : 'e.g. Invalid TrxID or amount not received...'"
            class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-rose-500 resize-none"
          ></textarea>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-[var(--border-subtle)]">
          <button
            type="button"
            @click="isRejectModalOpen = false"
            class="px-3.5 py-2 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'বাতিল' : 'Cancel' }}
          </button>
          <button
            type="button"
            @click="confirmRejectOrder"
            :disabled="actionLoading === activeRejectOrder?.id"
            class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'নিশ্চিত বাতিল করুন' : 'Confirm Reject' }}
          </button>
        </div>
      </div>
    </AppModal>

    <!-- Order Details & Receipt Modal -->
    <AppModal v-model="isDetailsModalOpen" :title="themeStore.locale === 'bn' ? 'অর্ডার বিস্তারিত ও ইনভয়েস' : 'Order Details & Receipt'" size="md">
      <div v-if="selectedOrder" class="space-y-5 p-1 text-xs">
        
        <!-- Header Banner -->
        <div class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] flex items-center justify-between">
          <div>
            <span class="text-[10px] text-[var(--text-muted)] uppercase block">{{ themeStore.locale === 'bn' ? 'অর্ডার নম্বর' : 'Order #' }}</span>
            <span class="font-mono font-black text-sm text-[var(--brand-gold)]">{{ selectedOrder.order_number }}</span>
          </div>
          <span :class="getStatusBadgeClass(selectedOrder.payment_status || selectedOrder.status)">
            {{ formatStatusText(selectedOrder.payment_status || selectedOrder.status) }}
          </span>
        </div>

        <!-- Student & Contact Details -->
        <div class="space-y-2 p-3.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]">
          <h5 class="font-bold text-[var(--text-primary)] uppercase tracking-wider text-[10px] text-[#D4AF37]">
            {{ themeStore.locale === 'bn' ? 'শিক্ষার্থী / গ্রাহক তথ্য' : 'Customer Details' }}
          </h5>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'নাম:' : 'Name:' }}</span>
              <span class="font-bold text-[var(--text-primary)]">{{ selectedOrder.user?.name || selectedOrder.payment?.gateway_response?.customer_name || 'N/A' }}</span>
            </div>
            <div>
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'মোবাইল নম্বর:' : 'Phone:' }}</span>
              <span class="font-bold text-[var(--text-primary)]">{{ selectedOrder.user?.phone || selectedOrder.payment?.gateway_response?.customer_phone || 'N/A' }}</span>
            </div>
            <div>
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'ইমেইল:' : 'Email:' }}</span>
              <span class="text-[var(--text-secondary)] truncate block">{{ selectedOrder.user?.email || selectedOrder.payment?.gateway_response?.customer_email || 'N/A' }}</span>
            </div>
            <div>
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'অর্ডার সময়:' : 'Date:' }}</span>
              <span class="text-[var(--text-secondary)]">{{ selectedOrder.created_at || 'Recently' }}</span>
            </div>
          </div>
        </div>

        <!-- Payment & Transaction Details -->
        <div class="space-y-2 p-3.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]">
          <h5 class="font-bold text-[var(--text-primary)] uppercase tracking-wider text-[10px] text-[#D4AF37]">
            {{ themeStore.locale === 'bn' ? 'পেমেন্ট ট্রানজ্যাকশন তথ্য' : 'Payment Transaction Details' }}
          </h5>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'পেমেন্ট মাধ্যম:' : 'Channel:' }}</span>
              <span class="font-bold text-[var(--text-primary)] uppercase">{{ formatPaymentMethod(selectedOrder.payment?.payment_method || selectedOrder.payment_method) }}</span>
            </div>
            <div>
              <span class="text-[10px] text-[var(--text-muted)] block">TrxID:</span>
              <span class="font-mono font-bold text-[#D4AF37]">{{ selectedOrder.payment?.transaction_id || 'N/A' }}</span>
            </div>
            <div>
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'প্রেরক একাউন্ট:' : 'Sender Account:' }}</span>
              <span class="font-mono text-[var(--text-primary)]">{{ selectedOrder.payment?.gateway_response?.sender_number || 'N/A' }}</span>
            </div>
            <div>
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'মোট মূল্য:' : 'Total Paid:' }}</span>
              <span class="font-black text-emerald-500 text-sm">{{ formatCurrency(selectedOrder.total_amount, themeStore.locale) }}</span>
            </div>
          </div>

          <div v-if="selectedOrder.payment?.gateway_response?.rejection_reason" class="p-2 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-500 text-[11px] mt-2">
            <strong>{{ themeStore.locale === 'bn' ? 'বাতিলের কারণ:' : 'Rejection Reason:' }}</strong> {{ selectedOrder.payment.gateway_response.rejection_reason }}
          </div>
        </div>

        <!-- Order Items Breakdown -->
        <div class="space-y-2">
          <h5 class="font-bold text-[var(--text-primary)] uppercase tracking-wider text-[10px]">
            {{ themeStore.locale === 'bn' ? 'ক্রয়কৃত আইটেম' : 'Purchased Items' }}
          </h5>
          <div class="p-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] flex items-center justify-between">
            <span class="font-bold text-[var(--text-primary)]">{{ selectedOrder.items?.[0]?.item_name || selectedOrder.item_name }}</span>
            <span class="font-black text-[var(--brand-gold)]">{{ formatCurrency(selectedOrder.total_amount, themeStore.locale) }}</span>
          </div>
        </div>

        <!-- Footer Modal Actions -->
        <div class="flex items-center justify-end gap-2 pt-3 border-t border-[var(--border-subtle)]">
          <button
            v-if="isPendingOrder(selectedOrder)"
            type="button"
            @click="verifyOrderPayment(selectedOrder.id); isDetailsModalOpen = false"
            class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'অনুমোদন করুন (Approve)' : 'Approve Order' }}
          </button>
          <button
            type="button"
            @click="isDetailsModalOpen = false"
            class="px-4 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'বন্ধ করুন' : 'Close' }}
          </button>
        </div>

      </div>
    </AppModal>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { apiClient } from '../../api/client';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import { formatCurrency } from '../../utils/locale';
import AppModal from '../../components/ui/AppModal.vue';

const orders = ref<any[]>([]);
const loading = ref(true);
const filterStatus = ref('');
const searchQuery = ref('');
const actionLoading = ref<number | null>(null);

const isDetailsModalOpen = ref(false);
const selectedOrder = ref<any>(null);

const isRejectModalOpen = ref(false);
const activeRejectOrder = ref<any>(null);
const rejectReason = ref('');

const toast = useToastStore();
const themeStore = useThemeStore();

let searchDebounceTimeout: any = null;

const pendingCount = computed(() => {
  return orders.value.filter((o) => isPendingOrder(o)).length;
});

const approvedCount = computed(() => {
  return orders.value.filter((o) => isPaidOrder(o)).length;
});

const totalOrdersCount = computed(() => {
  return orders.value.length;
});

const totalRevenue = computed(() => {
  return orders.value
    .filter((o) => isPaidOrder(o))
    .reduce((sum, o) => sum + Number(o.total_amount || 0), 0);
});

function isPendingOrder(order: any): boolean {
  const st = order.payment_status || order.status;
  return ['pending_verification', 'pending', 'processing'].includes(st);
}

function isPaidOrder(order: any): boolean {
  const st = order.payment_status || order.status;
  return ['paid', 'completed'].includes(st);
}

async function fetchOrders() {
  try {
    loading.value = true;
    const res = await apiClient.get('/admin/orders', {
      params: {
        payment_status: filterStatus.value || undefined,
        search: searchQuery.value.trim() || undefined,
      },
    });
    if (res.data.status === 'success') {
      orders.value = res.data.data.data || res.data.data || [];
    }
  } catch (err) {
    toast.error(themeStore.locale === 'bn' ? 'অর্ডার তালিকা লোড করতে ব্যর্থ হয়েছে' : 'Failed to load order records');
  } finally {
    loading.value = false;
  }
}

function handleSearch() {
  clearTimeout(searchDebounceTimeout);
  searchDebounceTimeout = setTimeout(() => {
    fetchOrders();
  }, 400);
}

function copyTrx(trx: string) {
  if (navigator.clipboard) {
    navigator.clipboard.writeText(trx);
    toast.success(themeStore.locale === 'bn' ? 'TrxID কপি হয়েছে!' : 'TrxID copied!');
  }
}

function viewOrderDetails(order: any) {
  selectedOrder.value = order;
  isDetailsModalOpen.value = true;
}

function promptRejectOrder(order: any) {
  activeRejectOrder.value = order;
  rejectReason.value = '';
  isRejectModalOpen.value = true;
}

async function confirmRejectOrder() {
  if (!activeRejectOrder.value) return;
  const orderId = activeRejectOrder.value.id;
  try {
    actionLoading.value = orderId;
    const res = await apiClient.put(`/admin/orders/${orderId}/reject`, {
      reason: rejectReason.value.trim() || 'Payment verification rejected by admin/worker',
    });
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'অর্ডার সফলভাবে বাতিল (Reject) করা হয়েছে!' : 'Order rejected successfully!');
      isRejectModalOpen.value = false;
      fetchOrders();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'বাতিলকরণ ব্যর্থ হয়েছে' : 'Failed to reject order'));
  } finally {
    actionLoading.value = null;
  }
}

async function verifyOrderPayment(orderId: number) {
  try {
    actionLoading.value = orderId;
    const res = await apiClient.put(`/admin/orders/${orderId}/verify-payment`);
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'পেমেন্ট সফলভাবে অনুমোদিত এবং শিক্ষার্থীর এনরোলমেন্ট কার্যকর হয়েছে!' : 'Payment verified and enrollment activated!');
      fetchOrders();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'পেমেন্ট অনুমোদন ব্যর্থ হয়েছে' : 'Payment verification failed'));
  } finally {
    actionLoading.value = null;
  }
}

async function refundOrder(orderId: number) {
  const confirmMsg = themeStore.locale === 'bn' ? 'আপনি কি নিশ্চিত যে এই অর্ডারটি রিফান্ড করতে চান?' : 'Are you sure you want to refund this order?';
  if (!confirm(confirmMsg)) return;

  try {
    actionLoading.value = orderId;
    const res = await apiClient.put(`/admin/orders/${orderId}/refund`, {
      reason: 'Admin triggered refund',
    });
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'অর্ডার রিফান্ড সফল হয়েছে!' : 'Order refunded successfully!');
      fetchOrders();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'রিফান্ড ব্যর্থ হয়েছে' : 'Refund failed'));
  } finally {
    actionLoading.value = null;
  }
}

function formatPaymentMethod(method: string) {
  if (!method) return 'Manual';
  switch (method) {
    case 'bkash_merchant': return 'bKash Merchant (QR)';
    case 'bkash_personal': return 'bKash Personal';
    case 'brac_bank': return 'BRAC Bank';
    case 'bank_transfer': return 'Bank Transfer';
    case 'nagad_manual': return 'Nagad Manual';
    default: return method;
  }
}

function getPaymentMethodBadgeClass(method: string) {
  if (method === 'bkash_merchant' || method === 'bkash_personal') {
    return 'px-2 py-0.5 rounded text-[10px] font-bold bg-[#E2136E]/10 text-[#E2136E] border border-[#E2136E]/20';
  }
  if (method === 'brac_bank' || method === 'bank_transfer') {
    return 'px-2 py-0.5 rounded text-[10px] font-bold bg-[#00529B]/10 text-[#00529B] dark:text-[#3894E6] border border-[#00529B]/20';
  }
  return 'px-2 py-0.5 rounded text-[10px] font-semibold bg-[var(--bg-elevated)] text-[var(--text-secondary)] border border-[var(--border-subtle)]';
}

function formatStatusText(status: string) {
  switch (status) {
    case 'paid':
    case 'completed':
      return themeStore.locale === 'bn' ? 'অনুমোদিত (Paid)' : 'Paid';
    case 'pending_verification':
    case 'pending':
    case 'processing':
      return themeStore.locale === 'bn' ? 'যাচাইাধীন' : 'Pending';
    case 'rejected':
    case 'cancelled':
    case 'failed':
      return themeStore.locale === 'bn' ? 'বাতিলকৃত' : 'Rejected';
    case 'refunded':
      return themeStore.locale === 'bn' ? 'রিফান্ডেড' : 'Refunded';
    default:
      return status;
  }
}

function getStatusBadgeClass(status: string) {
  switch (status) {
    case 'paid':
    case 'completed':
      return 'inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20';
    case 'pending_verification':
    case 'pending':
    case 'processing':
      return 'inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20';
    case 'rejected':
    case 'cancelled':
    case 'failed':
      return 'inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20';
    case 'refunded':
      return 'inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20';
    default:
      return 'inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-slate-500/10 text-slate-600 dark:text-slate-400';
  }
}

onMounted(() => {
  fetchOrders();
});
</script>
