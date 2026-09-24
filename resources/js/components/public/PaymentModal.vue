<template>
  <AppModal
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    :title="themeStore.locale === 'bn' ? 'নিরাপদ পেমেন্ট ও চেকআউট' : 'Secure Payment & Checkout'"
    size="lg"
  >
    <!-- Success Screen -->
    <div v-if="isSuccess" class="py-6 px-2 text-center space-y-6">
      <div class="w-16 h-16 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-500 flex items-center justify-center mx-auto animate-bounce">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
      </div>

      <div class="space-y-2">
        <h3 class="text-xl font-black text-[var(--text-primary)]">
          {{ themeStore.locale === 'bn' ? 'পেমেন্ট রিকোয়েস্ট সফলভাবে জমা নেওয়া হয়েছে!' : 'Payment Request Received Successfully!' }}
        </h3>
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] max-w-lg mx-auto leading-relaxed">
          {{ themeStore.locale === 'bn'
            ? 'ধন্যবাদ! আপনার ট্রানজেকশন তথ্য আমাদের অ্যাডমিন/হিসাব টিম যাচাই করছে। কিছুক্ষণের মধ্যেই আপনার একাউন্টে কোর্স/রিসোর্স অ্যাক্সেস চালু হয়ে যাবে।'
            : 'Thank you! Our admissions & finance team is verifying your transaction. Access will be activated shortly upon confirmation.' }}
        </p>
      </div>

      <!-- Order Summary Ticket -->
      <div class="p-5 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-left space-y-3 text-xs">
        <div class="flex items-center justify-between pb-2 border-b border-[var(--border-subtle)]">
          <span class="text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'অর্ডার নম্বর:' : 'Order Number:' }}</span>
          <span class="font-mono font-bold text-[var(--brand-gold)] text-sm">{{ orderResult?.order_number || 'ORD-PENDING' }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'আইটেম:' : 'Item:' }}</span>
          <span class="font-bold text-[var(--text-primary)] text-right max-w-[240px] truncate">{{ itemName }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'মোট পরিশোধিত অংক:' : 'Total Amount:' }}</span>
          <span class="font-black text-emerald-500 text-sm">{{ formatCurrency(finalPayableAmount, themeStore.locale) }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'পেমেন্ট মেথড:' : 'Payment Method:' }}</span>
          <span class="font-semibold uppercase text-[var(--text-primary)]">{{ getPaymentMethodLabel(form.payment_method) }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-[var(--text-secondary)]">TrxID:</span>
          <span class="font-mono font-bold text-[var(--text-primary)]">{{ form.transaction_id }}</span>
        </div>
        <div class="flex items-center justify-between pt-1">
          <span class="text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'বর্তমান স্ট্যাটাস:' : 'Status:' }}</span>
          <span class="px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/20 font-bold text-[11px] inline-flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
            {{ themeStore.locale === 'bn' ? 'যাচাইাধীন (Pending Verification)' : 'Pending Verification' }}
          </span>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
        <a
          :href="whatsAppSupportUrl"
          target="_blank"
          rel="noopener noreferrer"
          class="w-full sm:w-auto px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transition-all touch-target"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.04 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'হোয়াটসঅ্যাপে দ্রুত কনফার্মেশন নিন' : 'Instant WhatsApp Confirmation' }}</span>
        </a>
        <button
          type="button"
          @click="closeModal"
          class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] transition-all touch-target cursor-pointer"
        >
          {{ themeStore.locale === 'bn' ? 'সম্পন্ন করুন' : 'Done' }}
        </button>
      </div>
    </div>

    <!-- Main Payment Flow Form -->
    <form v-else @submit.prevent="handlePaymentSubmit" class="space-y-6">
      
      <!-- Item Summary Header Card -->
      <div class="p-4 rounded-2xl bg-gradient-to-r from-[var(--bg-elevated)] to-[var(--bg-surface)] border border-[var(--border-accent)] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5 min-w-0">
          <div class="w-12 h-12 rounded-xl bg-[#D4AF37]/10 border border-[#D4AF37]/30 flex items-center justify-center text-[#D4AF37] shrink-0">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
          </div>
          <div class="min-w-0">
            <span class="text-[10px] font-bold uppercase text-[#D4AF37] tracking-wider block">
              {{ itemType === 'course' ? (themeStore.locale === 'bn' ? 'প্রফেশনাল কোর্স' : 'Professional Course') : (itemType === 'ebook' ? (themeStore.locale === 'bn' ? 'ই-বুক ও রিসোর্স' : 'E-Book / Resource') : 'Webinar') }}
            </span>
            <h4 class="text-sm sm:text-base font-bold text-[var(--text-primary)] truncate">{{ itemName }}</h4>
            <div v-if="selectedBatchTitle" class="text-[11px] text-[var(--text-secondary)] flex items-center gap-1.5 mt-0.5">
              <span>{{ themeStore.locale === 'bn' ? 'ব্যাচ:' : 'Batch:' }}</span>
              <span class="font-medium text-[var(--brand-gold)]">{{ selectedBatchTitle }}</span>
            </div>
          </div>
        </div>

        <div class="text-left sm:text-right border-t sm:border-t-0 pt-2 sm:pt-0 border-[var(--border-subtle)] shrink-0">
          <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'পরিশোধযোগ্য মূল্য' : 'Payable Amount' }}</span>
          <div class="flex items-baseline gap-2">
            <span class="text-xl sm:text-2xl font-black text-[#D4AF37]">{{ formatCurrency(finalPayableAmount, themeStore.locale) }}</span>
            <span v-if="discountAmount > 0" class="text-xs text-[var(--text-muted)] line-through">
              {{ formatCurrency(initialPrice, themeStore.locale) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Step 1: Payment Method Selector Tabs -->
      <div class="space-y-3">
        <label class="text-xs font-bold text-[var(--text-primary)] flex items-center justify-between">
          <span>{{ themeStore.locale === 'bn' ? '১. পেমেন্ট মেথড নির্বাচন করুন:' : '1. Select Payment Method:' }}</span>
          <span class="text-[10px] text-[#D4AF37] font-semibold flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            {{ themeStore.locale === 'bn' ? 'অফিসিয়াল নিরাপদ একাউন্ট' : 'Official Verified Accounts' }}
          </span>
        </label>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <!-- Method 1: bKash Merchant -->
          <button
            type="button"
            @click="form.payment_method = 'bkash_merchant'"
            :class="[
              'p-3.5 rounded-2xl border text-left transition-all cursor-pointer relative flex flex-col justify-between gap-2',
              form.payment_method === 'bkash_merchant'
                ? 'bg-[#E2136E]/10 border-[#E2136E] text-[var(--text-primary)] shadow-sm shadow-[#E2136E]/20'
                : 'bg-[var(--bg-surface)] border-[var(--border-subtle)] hover:border-[#E2136E]/40 text-[var(--text-secondary)]'
            ]"
          >
            <div class="flex items-center justify-between">
              <span class="font-bold text-xs text-[#E2136E]">bKash Merchant</span>
              <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-[#E2136E]/15 text-[#E2136E] uppercase">Make Payment</span>
            </div>
            <div class="font-mono text-xs font-bold text-[var(--text-primary)]">01805464290</div>
            <div class="text-[10px] text-[var(--text-muted)] flex items-center gap-1">
              <svg class="w-3 h-3 text-[#E2136E]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'QR স্ক্যান সুবিধা' : 'QR Scan Supported' }}</span>
            </div>
          </button>

          <!-- Method 2: bKash Personal -->
          <button
            type="button"
            @click="form.payment_method = 'bkash_personal'"
            :class="[
              'p-3.5 rounded-2xl border text-left transition-all cursor-pointer relative flex flex-col justify-between gap-2',
              form.payment_method === 'bkash_personal'
                ? 'bg-[#E2136E]/10 border-[#E2136E] text-[var(--text-primary)] shadow-sm shadow-[#E2136E]/20'
                : 'bg-[var(--bg-surface)] border-[var(--border-subtle)] hover:border-[#E2136E]/40 text-[var(--text-secondary)]'
            ]"
          >
            <div class="flex items-center justify-between">
              <span class="font-bold text-xs text-[#E2136E]">bKash Personal</span>
              <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-[#E2136E]/15 text-[#E2136E] uppercase">Send Money</span>
            </div>
            <div class="font-mono text-xs font-bold text-[var(--text-primary)]">01712-857909</div>
            <div class="text-[10px] text-[var(--text-muted)]">
              {{ themeStore.locale === 'bn' ? 'সেন্ড মানি অপশন' : 'Send Money Option' }}
            </div>
          </button>

          <!-- Method 3: BRAC Bank -->
          <button
            type="button"
            @click="form.payment_method = 'brac_bank'"
            :class="[
              'p-3.5 rounded-2xl border text-left transition-all cursor-pointer relative flex flex-col justify-between gap-2',
              form.payment_method === 'brac_bank'
                ? 'bg-[#00529B]/10 border-[#00529B] text-[var(--text-primary)] shadow-sm shadow-[#00529B]/20'
                : 'bg-[var(--bg-surface)] border-[var(--border-subtle)] hover:border-[#00529B]/40 text-[var(--text-secondary)]'
            ]"
          >
            <div class="flex items-center justify-between">
              <span class="font-bold text-xs text-[#00529B] dark:text-[#3894E6]">BRAC Bank</span>
              <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-[#00529B]/15 text-[#00529B] dark:text-[#3894E6] uppercase">Bank / POS</span>
            </div>
            <div class="text-[11px] font-bold text-[var(--text-primary)] truncate">EMISHA TOURS & TRAVELS</div>
            <div class="text-[10px] text-[var(--text-muted)]">
              MID: 460000000031191
            </div>
          </button>
        </div>
      </div>

      <!-- Payment Instructions Details Box based on selected tab -->
      <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-4">
        
        <!-- Tab 1: bKash Merchant Details -->
        <div v-if="form.payment_method === 'bkash_merchant'" class="space-y-4">
          <div class="flex flex-col md:flex-row items-center gap-5">
            <!-- QR Code preview -->
            <div class="flex flex-col items-center gap-1.5 shrink-0">
              <div class="p-2 rounded-2xl bg-white shadow-md border border-slate-200">
                <img
                  src="/images/payments/bkash-qr.jpg"
                  alt="bKash Merchant QR Code"
                  class="w-32 h-32 object-contain rounded-lg"
                />
              </div>
              <span class="text-[10px] text-[var(--text-muted)] font-medium text-center">
                {{ themeStore.locale === 'bn' ? 'অ্যাপ দিয়ে কিউআর স্ক্যান করুন' : 'Scan QR in bKash App' }}
              </span>
            </div>

            <!-- Instructions & Copyable Number -->
            <div class="space-y-3 flex-1 min-w-0">
              <div class="flex items-center justify-between p-3 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]">
                <div>
                  <span class="text-[10px] text-[var(--text-muted)] uppercase block">{{ themeStore.locale === 'bn' ? 'মার্চেন্ট নম্বর (Make Payment)' : 'bKash Merchant Number' }}</span>
                  <span class="text-base sm:text-lg font-mono font-black text-[#E2136E]">01805464290</span>
                </div>
                <button
                  type="button"
                  @click="copyText('01805464290', 'bKash Merchant Number')"
                  class="px-3.5 py-2 rounded-xl bg-[#E2136E]/15 hover:bg-[#E2136E]/25 text-[#E2136E] font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                  <span>{{ copiedField === '01805464290' ? (themeStore.locale === 'bn' ? 'কপি হয়েছে!' : 'Copied!') : (themeStore.locale === 'bn' ? 'কপি করুন' : 'Copy') }}</span>
                </button>
              </div>

              <!-- Steps List -->
              <ol class="text-[11px] text-[var(--text-secondary)] space-y-1.5 list-decimal list-inside leading-relaxed">
                <li>{{ themeStore.locale === 'bn' ? 'bKash App খুলুন অথবা *247# ডায়াল করুন।' : 'Open bKash app or dial *247#.' }}</li>
                <li>{{ themeStore.locale === 'bn' ? '"Make Payment" অপশনে যান অথবা উপরের QR কোডটি স্ক্যান করুন।' : 'Select "Make Payment" or scan the QR code above.' }}</li>
                <li>{{ themeStore.locale === 'bn' ? 'মার্চেন্ট নম্বর 01805464290 লিখুন এবং টাকার পরিমাণ লিখুন: ' : 'Enter merchant number 01805464290 and amount: ' }} <strong class="text-[var(--brand-gold)]">{{ formatCurrency(finalPayableAmount, themeStore.locale) }}</strong></li>
                <li>{{ themeStore.locale === 'bn' ? 'রেফারেন্সে আপনার নাম বা কোর্সের নাম দিয়ে পেমেন্ট কনফার্ম করুন।' : 'Enter your name/course as reference and confirm with PIN.' }}</li>
                <li>{{ themeStore.locale === 'bn' ? 'পেমেন্ট শেষে পাওয়া Transaction ID (TrxID) নিচে লিখে সাবমিট করুন।' : 'Copy the Transaction ID (TrxID) and submit below.' }}</li>
              </ol>
            </div>
          </div>
        </div>

        <!-- Tab 2: bKash Personal Details -->
        <div v-else-if="form.payment_method === 'bkash_personal'" class="space-y-3">
          <div class="flex items-center justify-between p-3 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]">
            <div>
              <span class="text-[10px] text-[var(--text-muted)] uppercase block">{{ themeStore.locale === 'bn' ? 'পার্সোনাল নম্বর (Send Money)' : 'bKash Personal Number' }}</span>
              <span class="text-base sm:text-lg font-mono font-black text-[#E2136E]">01712-857909</span>
            </div>
            <button
              type="button"
              @click="copyText('01712857909', 'bKash Personal Number')"
              class="px-3.5 py-2 rounded-xl bg-[#E2136E]/15 hover:bg-[#E2136E]/25 text-[#E2136E] font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
              <span>{{ copiedField === '01712857909' ? (themeStore.locale === 'bn' ? 'কপি হয়েছে!' : 'Copied!') : (themeStore.locale === 'bn' ? 'কপি করুন' : 'Copy') }}</span>
            </button>
          </div>

          <ol class="text-[11px] text-[var(--text-secondary)] space-y-1.5 list-decimal list-inside leading-relaxed">
            <li>{{ themeStore.locale === 'bn' ? 'bKash অ্যাপ বা *247# এ গিয়ে "Send Money" নির্বাচন করুন।' : 'Select "Send Money" in bKash app or *247#.' }}</li>
            <li>{{ themeStore.locale === 'bn' ? 'প্রাপক নম্বরে লিখুন: 01712-857909' : 'Enter recipient number: 01712-857909' }}</li>
            <li>{{ themeStore.locale === 'bn' ? 'টাকার পরিমাণ দিন: ' : 'Enter amount: ' }} <strong class="text-[var(--brand-gold)]">{{ formatCurrency(finalPayableAmount, themeStore.locale) }}</strong></li>
            <li>{{ themeStore.locale === 'bn' ? 'সেন্ড মানি সফল হলে প্রাপ্ত TrxID ও যে নম্বর থেকে পাঠিয়েছেন তা নিচে লিখুন।' : 'Copy the Transaction ID and sender number to submit below.' }}</li>
          </ol>
        </div>

        <!-- Tab 3: BRAC Bank Details -->
        <div v-else-if="form.payment_method === 'brac_bank'" class="space-y-3">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
            <!-- Merchant Name -->
            <div class="p-3 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
              <span class="text-[10px] text-[var(--text-muted)] block uppercase">{{ themeStore.locale === 'bn' ? 'মার্চেন্ট / একাউন্ট নাম' : 'Merchant Name' }}</span>
              <div class="flex items-center justify-between">
                <span class="font-bold text-[var(--text-primary)] text-xs">EMISHA TOURS & TRAVELS</span>
                <button
                  type="button"
                  @click="copyText('EMISHA TOURS & TRAVELS', 'Merchant Name')"
                  class="text-[10px] text-[#00529B] dark:text-[#3894E6] font-semibold hover:underline"
                >
                  {{ copiedField === 'EMISHA TOURS & TRAVELS' ? 'Copied' : 'Copy' }}
                </button>
              </div>
            </div>

            <!-- Merchant ID -->
            <div class="p-3 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
              <span class="text-[10px] text-[var(--text-muted)] block uppercase">MERCHANT ID</span>
              <div class="flex items-center justify-between">
                <span class="font-mono font-bold text-[var(--text-primary)] text-xs">460000000031191</span>
                <button
                  type="button"
                  @click="copyText('460000000031191', 'Merchant ID')"
                  class="text-[10px] text-[#00529B] dark:text-[#3894E6] font-semibold hover:underline"
                >
                  {{ copiedField === '460000000031191' ? 'Copied' : 'Copy' }}
                </button>
              </div>
            </div>

            <!-- Terminal ID -->
            <div class="p-3 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
              <span class="text-[10px] text-[var(--text-muted)] block uppercase">TERMINAL ID</span>
              <div class="flex items-center justify-between">
                <span class="font-mono font-bold text-[var(--text-primary)] text-xs">86031191</span>
                <button
                  type="button"
                  @click="copyText('86031191', 'Terminal ID')"
                  class="text-[10px] text-[#00529B] dark:text-[#3894E6] font-semibold hover:underline"
                >
                  {{ copiedField === '86031191' ? 'Copied' : 'Copy' }}
                </button>
              </div>
            </div>
          </div>

          <p class="text-[11px] text-[var(--text-secondary)] leading-relaxed">
            {{ themeStore.locale === 'bn'
              ? 'BRAC Bank Astha App / Internet Banking / POS / যেকোনো ব্র্যাক ব্যাংক ব্রাঞ্চে ডিপোজিট করে প্রাপ্ত ট্রানজ্যাকশন আইডি বা রেফারেন্স নিচে প্রদান করুন।'
              : 'Transfer via BRAC Bank Astha App, Internet Banking, or branch deposit and submit your transaction reference ID below.' }}
          </p>
        </div>
      </div>

      <!-- Step 2: Coupon Code (Optional) -->
      <div class="p-3.5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-2">
        <label class="text-xs font-bold text-[var(--text-primary)] flex items-center justify-between">
          <span>{{ themeStore.locale === 'bn' ? 'ডিসকাউন্ট কুপন আছে কি? (ঐচ্ছিক)' : 'Have a discount coupon? (Optional)' }}</span>
          <span v-if="appliedCoupon" class="text-xs font-bold text-emerald-500">
            - {{ formatCurrency(discountAmount, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'ছাড় প্রযোজ্য হয়েছে' : 'discount applied' }}
          </span>
        </label>
        <div class="flex gap-2">
          <input
            v-model="couponCodeInput"
            type="text"
            placeholder="PROMO2026"
            :disabled="isValidatingCoupon || Boolean(appliedCoupon)"
            class="flex-1 px-4 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs uppercase font-mono text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
          />
          <button
            v-if="!appliedCoupon"
            type="button"
            @click="applyCoupon"
            :disabled="!couponCodeInput || isValidatingCoupon"
            class="px-4 py-2 rounded-xl bg-[#D4AF37] text-slate-950 font-bold text-xs transition-opacity disabled:opacity-50 cursor-pointer"
          >
            {{ isValidatingCoupon ? '...' : (themeStore.locale === 'bn' ? 'প্রয়োগ করুন' : 'Apply') }}
          </button>
          <button
            v-else
            type="button"
            @click="removeCoupon"
            class="px-3 py-2 rounded-xl bg-rose-500/10 text-rose-500 text-xs font-bold hover:bg-rose-500/20 cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'মুছুন' : 'Remove' }}
          </button>
        </div>
      </div>

      <!-- Step 3: Transaction & Learner Information Form -->
      <div class="space-y-4">
        <h4 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider text-[var(--brand-gold)]">
          {{ themeStore.locale === 'bn' ? '২. আপনার পেমেন্ট ও যোগাযোগের তথ্য দিন' : '2. Enter Payment & Learner Details' }}
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Full Name -->
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'আপনার পূর্ণ নাম' : 'Full Name' }} <span class="text-rose-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              :placeholder="themeStore.locale === 'bn' ? 'যেমন: আরিফুল ইসলাম' : 'e.g. Ariful Islam'"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
            />
          </div>

          <!-- Phone Number -->
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'যোগাযোগের মোবাইল নম্বর' : 'Mobile Number' }} <span class="text-rose-500">*</span>
            </label>
            <input
              v-model="form.phone"
              type="tel"
              required
              placeholder="018XXXXXXXX"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
            />
          </div>

          <!-- Transaction ID (TrxID) -->
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-[var(--text-primary)] flex items-center justify-between">
              <span>{{ themeStore.locale === 'bn' ? 'ট্রানজ্যাকশন আইডি (TrxID)' : 'Transaction ID (TrxID)' }} <span class="text-rose-500">*</span></span>
              <span class="text-[10px] text-[var(--text-muted)] font-mono">bKash/Bank TrxID</span>
            </label>
            <input
              v-model="form.transaction_id"
              type="text"
              required
              placeholder="e.g. 9J28DA78Q1"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-mono font-bold text-[var(--brand-gold)] uppercase focus:outline-none focus:border-[#D4AF37]"
            />
          </div>

          <!-- Sender Account / Phone Number -->
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-[var(--text-primary)] flex items-center justify-between">
              <span>{{ themeStore.locale === 'bn' ? 'প্রেরক নম্বর / অ্যাকাউন্ট বিবরণ' : 'Sender Number / Account' }} <span class="text-rose-500">*</span></span>
            </label>
            <input
              v-model="form.sender_number"
              type="text"
              required
              :placeholder="themeStore.locale === 'bn' ? 'যে নম্বর/একাউন্ট থেকে টাকা পাঠিয়েছেন' : 'Number from which you paid'"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
            />
          </div>
        </div>

        <!-- Optional Email & Notes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-[var(--text-secondary)]">
              {{ themeStore.locale === 'bn' ? 'ইমেইল অ্যাড্রেস (ঐচ্ছিক)' : 'Email Address (Optional)' }}
            </label>
            <input
              v-model="form.email"
              type="email"
              placeholder="student@example.com"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
            />
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-bold text-[var(--text-secondary)]">
              {{ themeStore.locale === 'bn' ? 'কোনো মন্তব্য বা অনুরোধ (ঐচ্ছিক)' : 'Remarks / Notes (Optional)' }}
            </label>
            <input
              v-model="form.notes"
              type="text"
              :placeholder="themeStore.locale === 'bn' ? 'যেমন: সকালের ব্যাচ প্রেফারেন্স' : 'e.g. Morning batch preferred'"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
            />
          </div>
        </div>
      </div>

      <!-- Submit Bar -->
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3 border-t border-[var(--border-subtle)]">
        <div class="text-xs text-[var(--text-secondary)] text-center sm:text-left">
          {{ themeStore.locale === 'bn' ? 'পরিশোধযোগ্য সর্বমোট:' : 'Total Payable:' }}
          <strong class="text-base text-[#D4AF37] ml-1">{{ formatCurrency(finalPayableAmount, themeStore.locale) }}</strong>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
          <button
            type="button"
            @click="closeModal"
            class="w-1/2 sm:w-auto px-4 py-2.5 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'বাতিল' : 'Cancel' }}
          </button>

          <button
            type="submit"
            :disabled="isSubmitting"
            class="w-1/2 sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all cursor-pointer disabled:opacity-50 flex items-center justify-center gap-2 touch-target"
          >
            <span v-if="isSubmitting" class="inline-block animate-spin w-3.5 h-3.5 border-2 border-slate-950 border-t-transparent rounded-full"></span>
            <span>{{ isSubmitting ? (themeStore.locale === 'bn' ? 'যাচাই হচ্ছে...' : 'Submitting...') : (themeStore.locale === 'bn' ? 'পেমেন্ট তথ্য নিশ্চিত করুন →' : 'Confirm Payment →') }}</span>
          </button>
        </div>
      </div>

    </form>
  </AppModal>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue';
import AppModal from '../ui/AppModal.vue';
import { useAuthStore } from '../../stores/auth';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';
import { formatCurrency } from '../../utils/locale';
import { apiClient } from '../../api/client';

const props = defineProps<{
  modelValue: boolean;
  itemType: 'course' | 'ebook' | 'webinar';
  itemId: number;
  itemName: string;
  itemPrice: number;
  batchId?: number | null;
  batchTitle?: string | null;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void;
  (e: 'success', data: any): void;
}>();

const authStore = useAuthStore();
const themeStore = useThemeStore();
const toastStore = useToastStore();

const isSubmitting = ref(false);
const isSuccess = ref(false);
const orderResult = ref<any>(null);
const copiedField = ref<string | null>(null);

const couponCodeInput = ref('');
const appliedCoupon = ref<string | null>(null);
const discountAmount = ref(0);
const isValidatingCoupon = ref(false);

const initialPrice = computed(() => Number(props.itemPrice) || 0);
const finalPayableAmount = computed(() => Math.max(0, initialPrice.value - discountAmount.value));
const selectedBatchTitle = computed(() => props.batchTitle || '');

const form = reactive({
  payment_method: 'bkash_merchant' as 'bkash_merchant' | 'bkash_personal' | 'brac_bank',
  transaction_id: '',
  sender_number: '',
  name: '',
  phone: '',
  email: '',
  notes: '',
});

// Auto-fill user information when modal opens or user auth changes
watch(
  () => props.modelValue,
  (open) => {
    if (open) {
      isSuccess.value = false;
      orderResult.value = null;
      discountAmount.value = 0;
      appliedCoupon.value = null;
      couponCodeInput.value = '';

      if (authStore.user) {
        form.name = authStore.user.name || '';
        form.phone = authStore.user.phone || '';
        form.email = authStore.user.email || '';
      }
    }
  },
  { immediate: true }
);

function copyText(text: string, label: string) {
  if (navigator.clipboard) {
    navigator.clipboard.writeText(text);
    copiedField.value = text;
    toastStore.success(`${label} ${themeStore.locale === 'bn' ? 'ক্লিপবোর্ডে কপি হয়েছে!' : 'copied to clipboard!'}`);
    setTimeout(() => {
      if (copiedField.value === text) {
        copiedField.value = null;
      }
    }, 2500);
  }
}

async function applyCoupon() {
  if (!couponCodeInput.value.trim()) return;
  isValidatingCoupon.value = true;
  try {
    const res = await apiClient.post('/public/checkout/validate-coupon', {
      coupon_code: couponCodeInput.value.trim(),
      subtotal: initialPrice.value,
    });
    if (res.data.status === 'success') {
      appliedCoupon.value = res.data.data.coupon_code;
      discountAmount.value = Number(res.data.data.discount_amount) || 0;
      toastStore.success(themeStore.locale === 'bn' ? 'কুপন ডিসকাউন্ট প্রয়োগ করা হয়েছে!' : 'Coupon applied successfully!');
    }
  } catch (err: any) {
    toastStore.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'অবৈধ বা মেয়াদোত্তীর্ণ কুপন।' : 'Invalid coupon code.'));
  } finally {
    isValidatingCoupon.value = false;
  }
}

function removeCoupon() {
  appliedCoupon.value = null;
  discountAmount.value = 0;
  couponCodeInput.value = '';
}

function getPaymentMethodLabel(method: string) {
  switch (method) {
    case 'bkash_merchant': return 'bKash Merchant (01805464290)';
    case 'bkash_personal': return 'bKash Personal (01712-857909)';
    case 'brac_bank': return 'BRAC Bank (EMISHA TOURS & TRAVELS)';
    default: return method;
  }
}

const whatsAppSupportUrl = computed(() => {
  const orderNum = orderResult.value?.order_number || '';
  const msg = themeStore.locale === 'bn'
    ? `হ্যালো ইমিশা একাডেমি! আমি "${props.itemName}" এর জন্য ${getPaymentMethodLabel(form.payment_method)} এর মাধ্যমে পেমেন্ট করেছি। TrxID: ${form.transaction_id}, অর্ডার নং: ${orderNum}। অনুগ্রহ করে ভেরিফাই করুন।`
    : `Hello Emisha Academy! I completed payment for "${props.itemName}" via ${getPaymentMethodLabel(form.payment_method)}. TrxID: ${form.transaction_id}, Order: ${orderNum}. Please verify.`;
  return `https://wa.me/8801805464290?text=${encodeURIComponent(msg)}`;
});

async function handlePaymentSubmit() {
  if (!form.name || !form.phone || !form.transaction_id || !form.sender_number) {
    toastStore.error(themeStore.locale === 'bn' ? 'অনুগ্রহ করে সকল আবশ্যকীয় তথ্য (নাম, ফোন, TrxID, প্রেরক নম্বর) পূরণ করুন।' : 'Please fill all required fields.');
    return;
  }

  isSubmitting.value = true;
  try {
    const res = await apiClient.post('/public/checkout/direct-manual', {
      item_type: props.itemType,
      item_id: props.itemId,
      batch_id: props.batchId || undefined,
      coupon_code: appliedCoupon.value || undefined,
      payment_method: form.payment_method,
      transaction_id: form.transaction_id.trim(),
      sender_number: form.sender_number.trim(),
      name: form.name.trim(),
      phone: form.phone.trim(),
      email: form.email ? form.email.trim() : undefined,
      notes: form.notes ? form.notes.trim() : undefined,
    });

    if (res.data.status === 'success') {
      orderResult.value = res.data.data.order;
      isSuccess.value = true;
      toastStore.success(themeStore.locale === 'bn' ? 'পেমেন্ট রিকোয়েস্ট সফলভাবে গৃহীত হয়েছে!' : 'Payment submitted successfully!');
      emit('success', res.data.data);
    }
  } catch (err: any) {
    toastStore.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'পেমেন্ট রিকোয়েস্ট জমা দিতে ব্যর্থ হয়েছে।' : 'Failed to submit payment.'));
  } finally {
    isSubmitting.value = false;
  }
}

function closeModal() {
  emit('update:modelValue', false);
  setTimeout(() => {
    isSuccess.value = false;
    orderResult.value = null;
  }, 300);
}
</script>
