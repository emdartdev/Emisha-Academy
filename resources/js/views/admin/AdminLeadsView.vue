<template>
  <div class="space-y-6 sm:space-y-8">
    
    <!-- 1. Header & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-xl sm:text-2xl font-black text-[var(--text-primary)] tracking-tight">
            {{ themeStore.locale === 'bn' ? 'লিড সিআরএম ও কন্টেন্ট ট্র্যাকার' : 'Content-Wise Lead CRM' }}
          </h1>
          <span class="px-2.5 py-0.5 rounded-full bg-[var(--brand-gold-subtle)] text-[#D4AF37] border border-[var(--border-accent)] text-[10px] font-black uppercase tracking-wider">
            Live CRM
          </span>
        </div>
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] mt-1">
          {{ themeStore.locale === 'bn'
            ? 'কোর্স, ওয়েবিনার, সেমিনার এবং ইবুক ডাউনলোড থেকে আসা সকল লিডের কেন্দ্রীভূত সিআরএম ও পাইপলাইন।'
            : 'Centralized CRM pipeline tracking leads generated across courses, webinars, seminars, and ebooks.' }}
        </p>
      </div>

      <div class="flex items-center gap-2.5 self-start sm:self-auto">
        <!-- Refresh Button -->
        <button
          @click="fetchLeads"
          class="px-3.5 py-2 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer touch-target shadow-xs"
          :title="themeStore.locale === 'bn' ? 'রিফ্রেশ করুন' : 'Refresh'"
        >
          <svg class="w-3.5 h-3.5" :class="{ 'animate-spin': loading }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
          <span class="hidden sm:inline">{{ themeStore.locale === 'bn' ? 'রিফ্রেশ' : 'Refresh' }}</span>
        </button>

        <!-- Add Lead Button -->
        <button
          @click="openAddLeadModal"
          class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all flex items-center gap-1.5 cursor-pointer touch-target shadow-md"
        >
          <span class="text-sm">+</span>
          <span>{{ themeStore.locale === 'bn' ? 'নতুন লিড' : 'Add Lead' }}</span>
        </button>
      </div>
    </div>

    <!-- 2. KPI Metrics Overview HUD -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
      
      <!-- Total Leads -->
      <div
        @click="statusFilter = 'all'; fetchLeads()"
        class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 transition-all shadow-xs cursor-pointer group"
      >
        <div class="flex items-center justify-between text-[11px] text-[var(--text-muted)] font-semibold">
          <span>{{ themeStore.locale === 'bn' ? 'মোট লিড' : 'Total Leads' }}</span>
          <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="text-xl sm:text-2xl font-black text-[var(--text-primary)] mt-1.5">
          {{ formatNumber(metrics.total_leads, themeStore.locale) }}
        </div>
      </div>

      <!-- New Leads -->
      <div
        @click="statusFilter = 'new'; fetchLeads()"
        class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-sky-500/50 transition-all shadow-xs cursor-pointer group"
      >
        <div class="flex items-center justify-between text-[11px] text-sky-600 dark:text-sky-400 font-semibold">
          <span>{{ themeStore.locale === 'bn' ? 'নতুন (New)' : 'New' }}</span>
          <span class="w-2 h-2 rounded-full bg-sky-500 animate-ping"></span>
        </div>
        <div class="text-xl sm:text-2xl font-black text-sky-600 dark:text-sky-400 mt-1.5">
          {{ formatNumber(metrics.new_leads, themeStore.locale) }}
        </div>
      </div>

      <!-- Contacted -->
      <div
        @click="statusFilter = 'contacted'; fetchLeads()"
        class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-amber-500/50 transition-all shadow-xs cursor-pointer group"
      >
        <div class="flex items-center justify-between text-[11px] text-amber-600 dark:text-amber-400 font-semibold">
          <span>{{ themeStore.locale === 'bn' ? 'যোগাযোগ হয়েছে' : 'Contacted' }}</span>
          <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        </div>
        <div class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400 mt-1.5">
          {{ formatNumber(metrics.contacted, themeStore.locale) }}
        </div>
      </div>

      <!-- Follow-up Needed (Today / Overdue Alert) -->
      <div
        @click="statusFilter = 'follow_up'; fetchLeads()"
        class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-purple-500/50 transition-all shadow-xs cursor-pointer group"
      >
        <div class="flex items-center justify-between text-[11px] text-purple-600 dark:text-purple-400 font-semibold">
          <span>{{ themeStore.locale === 'bn' ? 'ফলো-আপ প্রয়োজন' : 'Follow-Up' }}</span>
          <span v-if="metrics.follow_up_overdue > 0" class="px-1.5 py-0.5 rounded bg-rose-500/20 text-rose-500 text-[9px] font-black">
            {{ formatNumber(metrics.follow_up_overdue, themeStore.locale) }} Overdue
          </span>
        </div>
        <div class="text-xl sm:text-2xl font-black text-purple-600 dark:text-purple-400 mt-1.5 flex items-baseline gap-2">
          <span>{{ formatNumber(metrics.follow_up, themeStore.locale) }}</span>
          <span v-if="metrics.follow_up_today > 0" class="text-xs font-bold text-amber-500">
            ({{ formatNumber(metrics.follow_up_today, themeStore.locale) }} আজ)
          </span>
        </div>
      </div>

      <!-- Qualified -->
      <div
        @click="statusFilter = 'qualified'; fetchLeads()"
        class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-teal-500/50 transition-all shadow-xs cursor-pointer group"
      >
        <div class="flex items-center justify-between text-[11px] text-teal-600 dark:text-teal-400 font-semibold">
          <span>{{ themeStore.locale === 'bn' ? 'যোগ্য প্রার্থী' : 'Qualified' }}</span>
          <svg class="w-4 h-4 text-teal-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="text-xl sm:text-2xl font-black text-teal-600 dark:text-teal-400 mt-1.5">
          {{ formatNumber(metrics.qualified, themeStore.locale) }}
        </div>
      </div>

      <!-- Converted -->
      <div
        @click="statusFilter = 'converted'; fetchLeads()"
        class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-emerald-500/50 transition-all shadow-xs cursor-pointer group"
      >
        <div class="flex items-center justify-between text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
          <span>{{ themeStore.locale === 'bn' ? 'ভর্তি সম্পন্ন' : 'Converted' }}</span>
          <svg class="w-4 h-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1.5">
          {{ formatNumber(metrics.converted, themeStore.locale) }}
        </div>
      </div>

    </div>

    <!-- 3. Segmented Navigation Tabs (Content Pillars & Pipeline) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[var(--border-subtle)] pb-2">
      <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 md:pb-0 text-xs">
        
        <!-- All Leads Tab -->
        <button
          @click="activeLeadTab = 'all'; selectedContentId = ''; fetchLeads()"
          :class="[
            'px-4 py-2.5 rounded-xl font-bold transition-all whitespace-nowrap cursor-pointer flex items-center gap-2',
            activeLeadTab === 'all'
              ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 shadow-md'
              : 'bg-[var(--bg-surface)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'
          ]"
        >
          <span>{{ themeStore.locale === 'bn' ? 'সকল লিড' : 'All Leads' }}</span>
          <span class="px-1.5 py-0.5 rounded-md text-[10px]" :class="activeLeadTab === 'all' ? 'bg-slate-950/20 text-slate-950 font-black' : 'bg-[var(--bg-elevated)] text-[var(--text-muted)]'">
            {{ formatNumber(metrics.tab_counts.all, themeStore.locale) }}
          </span>
        </button>

        <!-- Course Leads Tab -->
        <button
          @click="activeLeadTab = 'course'; selectedContentId = ''; fetchLeads()"
          :class="[
            'px-4 py-2.5 rounded-xl font-bold transition-all whitespace-nowrap cursor-pointer flex items-center gap-2',
            activeLeadTab === 'course'
              ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 shadow-md'
              : 'bg-[var(--bg-surface)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'
          ]"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'কোর্স লিড' : 'Course Leads' }}</span>
          <span class="px-1.5 py-0.5 rounded-md text-[10px]" :class="activeLeadTab === 'course' ? 'bg-slate-950/20 text-slate-950 font-black' : 'bg-[var(--bg-elevated)] text-[var(--text-muted)]'">
            {{ formatNumber(metrics.tab_counts.course, themeStore.locale) }}
          </span>
        </button>

        <!-- Webinar Leads Tab -->
        <button
          @click="activeLeadTab = 'webinar'; selectedContentId = ''; fetchLeads()"
          :class="[
            'px-4 py-2.5 rounded-xl font-bold transition-all whitespace-nowrap cursor-pointer flex items-center gap-2',
            activeLeadTab === 'webinar'
              ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 shadow-md'
              : 'bg-[var(--bg-surface)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'
          ]"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'ওয়েবিনার' : 'Webinars' }}</span>
          <span class="px-1.5 py-0.5 rounded-md text-[10px]" :class="activeLeadTab === 'webinar' ? 'bg-slate-950/20 text-slate-950 font-black' : 'bg-[var(--bg-elevated)] text-[var(--text-muted)]'">
            {{ formatNumber(metrics.tab_counts.webinar, themeStore.locale) }}
          </span>
        </button>

        <!-- Seminar Leads Tab -->
        <button
          @click="activeLeadTab = 'seminar'; selectedContentId = ''; fetchLeads()"
          :class="[
            'px-4 py-2.5 rounded-xl font-bold transition-all whitespace-nowrap cursor-pointer flex items-center gap-2',
            activeLeadTab === 'seminar'
              ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 shadow-md'
              : 'bg-[var(--bg-surface)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'
          ]"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'সেমিনার' : 'Seminars' }}</span>
          <span class="px-1.5 py-0.5 rounded-md text-[10px]" :class="activeLeadTab === 'seminar' ? 'bg-slate-950/20 text-slate-950 font-black' : 'bg-[var(--bg-elevated)] text-[var(--text-muted)]'">
            {{ formatNumber(metrics.tab_counts.seminar, themeStore.locale) }}
          </span>
        </button>

        <!-- Ebook Leads Tab -->
        <button
          @click="activeLeadTab = 'ebook'; selectedContentId = ''; fetchLeads()"
          :class="[
            'px-4 py-2.5 rounded-xl font-bold transition-all whitespace-nowrap cursor-pointer flex items-center gap-2',
            activeLeadTab === 'ebook'
              ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 shadow-md'
              : 'bg-[var(--bg-surface)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'
          ]"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'ইবুক ডাউনলোড' : 'Ebooks' }}</span>
          <span class="px-1.5 py-0.5 rounded-md text-[10px]" :class="activeLeadTab === 'ebook' ? 'bg-slate-950/20 text-slate-950 font-black' : 'bg-[var(--bg-elevated)] text-[var(--text-muted)]'">
            {{ formatNumber(metrics.tab_counts.ebook, themeStore.locale) }}
          </span>
        </button>

        <!-- Pipeline Kanban View -->
        <button
          @click="viewMode = viewMode === 'table' ? 'kanban' : 'table'"
          :class="[
            'px-3.5 py-2.5 rounded-xl font-bold transition-all whitespace-nowrap cursor-pointer flex items-center gap-1.5 ml-auto border',
            viewMode === 'kanban'
              ? 'bg-purple-500/15 text-purple-600 dark:text-purple-400 border-purple-500/30'
              : 'bg-[var(--bg-surface)] text-[var(--text-secondary)] border-[var(--border-subtle)] hover:text-[var(--text-primary)]'
          ]"
          :title="themeStore.locale === 'bn' ? 'পাইপলাইন কানবান ভিউ' : 'Pipeline Kanban View'"
        >
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="11" rx="1"/></svg>
          <span>{{ viewMode === 'kanban' ? (themeStore.locale === 'bn' ? 'তালিকা ভিউ' : 'Table View') : (themeStore.locale === 'bn' ? 'পাইপলাইন' : 'Kanban') }}</span>
        </button>

      </div>
    </div>

    <!-- 4. Advanced CRM Search & Filter Toolbar -->
    <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-3.5 shadow-xs">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
        
        <!-- Live Search -->
        <div class="lg:col-span-2 relative">
          <input
            v-model="searchQuery"
            @input="debounceSearch"
            type="text"
            :placeholder="themeStore.locale === 'bn' ? 'নাম, ফোন, WhatsApp বা কন্টেন্ট দিয়ে খুঁজুন...' : 'Search by name, phone, WhatsApp or content...'"
            class="w-full px-4 py-2.5 pl-9 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:border-[#D4AF37] transition-colors"
          />
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)]">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </span>
          <button
            v-if="searchQuery"
            @click="searchQuery = ''; fetchLeads()"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer"
          >✕</button>
        </div>

        <!-- Phone: toggle the remaining filters -->
        <button
          type="button"
          class="md:hidden min-h-[44px] px-4 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] flex items-center justify-between cursor-pointer"
          :aria-expanded="showMobileFilters"
          @click="showMobileFilters = !showMobileFilters"
        >
          <span class="inline-flex items-center gap-2">
            <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            {{ themeStore.locale === 'bn' ? 'ফিল্টার' : 'Filters' }}
            <span v-if="activeFilterCount" class="px-1.5 rounded-full bg-[#D4AF37] text-slate-950 text-[10px] font-black">{{ activeFilterCount }}</span>
          </span>
          <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showMobileFilters }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>

        <!-- Dynamic Content Filter (Courses / Webinars / Ebooks) -->
        <div :class="{ 'hidden md:block': !showMobileFilters }">
          <select
            v-model="selectedContentId"
            @change="fetchLeads"
            class="w-full px-3 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-medium focus:outline-none focus:border-[#D4AF37] cursor-pointer"
          >
            <option value="">
              {{ activeLeadTab === 'course' 
                ? (themeStore.locale === 'bn' ? 'সকল কোর্স' : 'All Courses') 
                : (activeLeadTab === 'ebook' ? (themeStore.locale === 'bn' ? 'সকল ইবুক' : 'All Ebooks') : (themeStore.locale === 'bn' ? 'নির্দিষ্ট কন্টেন্ট ফিল্টার' : 'Filter by Content')) }}
            </option>
            <option v-for="item in currentContentOptions" :key="item.id" :value="item.id">
              {{ themeStore.locale === 'bn' ? (item.title_bn || item.title_en) : (item.title_en || item.title_bn) }}
            </option>
          </select>
        </div>

        <!-- Status Filter -->
        <div :class="{ 'hidden md:block': !showMobileFilters }">
          <select
            v-model="statusFilter"
            @change="fetchLeads"
            class="w-full px-3 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-medium focus:outline-none focus:border-[#D4AF37] cursor-pointer"
          >
            <option value="all">{{ themeStore.locale === 'bn' ? 'সকল স্ট্যাটাস' : 'All Statuses' }}</option>
            <option value="new">{{ themeStore.locale === 'bn' ? 'নতুন (New)' : 'New' }}</option>
            <option value="contacted">{{ themeStore.locale === 'bn' ? 'যোগাযোগ হয়েছে (Contacted)' : 'Contacted' }}</option>
            <option value="follow_up">{{ themeStore.locale === 'bn' ? 'ফলো-আপ (Follow-Up)' : 'Follow-Up' }}</option>
            <option value="qualified">{{ themeStore.locale === 'bn' ? 'যোগ্য (Qualified)' : 'Qualified' }}</option>
            <option value="converted">{{ themeStore.locale === 'bn' ? 'ভর্তি সম্পন্ন (Converted)' : 'Converted' }}</option>
            <option value="not_interested">{{ themeStore.locale === 'bn' ? 'আগ্রহী নন (Not Interested)' : 'Not Interested' }}</option>
            <option value="no_response">{{ themeStore.locale === 'bn' ? 'উত্তর মেলেনি (No Response)' : 'No Response' }}</option>
            <option value="lost">{{ themeStore.locale === 'bn' ? 'হারিয়ে গেছে (Lost)' : 'Lost' }}</option>
          </select>
        </div>

        <!-- Priority Filter -->
        <div :class="{ 'hidden md:block': !showMobileFilters }">
          <select
            v-model="priorityFilter"
            @change="fetchLeads"
            class="w-full px-3 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-medium focus:outline-none focus:border-[#D4AF37] cursor-pointer"
          >
            <option value="all">{{ themeStore.locale === 'bn' ? 'সকল প্রায়োরিটি' : 'All Priorities' }}</option>
            <option value="urgent">{{ themeStore.locale === 'bn' ? 'জরুরি (Urgent)' : 'Urgent' }}</option>
            <option value="high">{{ themeStore.locale === 'bn' ? 'উচ্চ (High)' : 'High' }}</option>
            <option value="normal">{{ themeStore.locale === 'bn' ? 'সাধারণ (Normal)' : 'Normal' }}</option>
            <option value="low">{{ themeStore.locale === 'bn' ? 'কম (Low)' : 'Low' }}</option>
          </select>
        </div>

      </div>

      <!-- Quick Follow-up Filters Strip -->
      <div
        class="items-center justify-between pt-2 border-t border-[var(--border-subtle)] text-[11px] flex-wrap gap-2"
        :class="showMobileFilters ? 'flex' : 'hidden md:flex'"
      >
        <div class="flex items-center gap-1.5 flex-wrap [&>button]:min-h-[36px] [&>select]:min-h-[36px]">
          <span class="text-[var(--text-muted)] font-semibold">{{ themeStore.locale === 'bn' ? 'দ্রুত ফিল্টার:' : 'Quick Filters:' }}</span>
          
          <button
            @click="followUpFilter = followUpFilter === 'today' ? '' : 'today'; fetchLeads()"
            :class="[
              'px-2.5 py-1 rounded-lg font-bold transition-all cursor-pointer',
              followUpFilter === 'today'
                ? 'bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/40'
                : 'bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'
            ]"
          >
            {{ themeStore.locale === 'bn' ? "আজকের ফলো-আপ" : "Today's Follow-up" }}
          </button>

          <button
            @click="followUpFilter = followUpFilter === 'overdue' ? '' : 'overdue'; fetchLeads()"
            :class="[
              'px-2.5 py-1 rounded-lg font-bold transition-all cursor-pointer',
              followUpFilter === 'overdue'
                ? 'bg-rose-500/20 text-rose-500 border border-rose-500/40'
                : 'bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'
            ]"
          >
            {{ themeStore.locale === 'bn' ? 'ওভারডিউ ফলো-আপ' : 'Overdue Follow-up' }}
          </button>

          <button
            @click="assignedFilter = assignedFilter === 'unassigned' ? '' : 'unassigned'; fetchLeads()"
            :class="[
              'px-2.5 py-1 rounded-lg font-bold transition-all cursor-pointer',
              assignedFilter === 'unassigned'
                ? 'bg-purple-500/20 text-purple-600 dark:text-purple-400 border border-purple-500/40'
                : 'bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'
            ]"
          >
            {{ themeStore.locale === 'bn' ? 'দায়িত্ব অর্পণহীন' : 'Unassigned Leads' }}
            <span v-if="metrics.unassigned" class="ml-1 px-1.5 rounded-full bg-purple-500 text-white text-[10px]">{{ formatNumber(metrics.unassigned, themeStore.locale) }}</span>
          </button>

          <button
            v-if="!canAssignEmployees"
            @click="assignedFilter = assignedFilter === 'mine' ? '' : 'mine'; fetchLeads()"
            :class="[
              'px-2.5 py-1 rounded-lg font-bold transition-all cursor-pointer',
              assignedFilter === 'mine'
                ? 'bg-[#D4AF37]/20 text-[#B8941F] dark:text-[#D4AF37] border border-[#D4AF37]/40'
                : 'bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'
            ]"
          >
            {{ themeStore.locale === 'bn' ? 'আমার গ্রহণ করা লিড' : 'My accepted leads' }}
          </button>

          <select
            v-model="employeeFilter"
            @change="fetchLeads()"
            class="px-2.5 py-1 rounded-lg font-bold bg-[var(--bg-deep)] text-[var(--text-secondary)] border border-[var(--border-subtle)] focus:outline-none focus:border-[#D4AF37] cursor-pointer"
          >
            <option value="">{{ themeStore.locale === 'bn' ? 'সব কর্মী' : 'All employees' }}</option>
            <option v-for="emp in employeeOptions" :key="emp.id" :value="String(emp.id)">{{ emp.name }}</option>
          </select>
        </div>

        <span class="text-[var(--text-muted)] font-medium">
          {{ formatNumber(pagination.total || leads.length, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি লিড প্রদর্শিত' : 'leads listed' }}
        </span>
      </div>
    </div>

    <!-- 5. Loading Skeleton -->
    <div v-if="loading" class="space-y-3">
      <div v-for="i in 5" :key="i" class="h-20 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
    </div>

    <!-- 6. Empty State -->
    <div v-else-if="leads.length === 0" class="text-center py-16 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-8 space-y-3">
      <div class="w-14 h-14 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto shadow-sm">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      </div>
      <p class="text-sm font-bold text-[var(--text-primary)]">
        {{ themeStore.locale === 'bn' ? 'কোনো লিড রেকর্ড পাওয়া যায়নি।' : 'No lead records found.' }}
      </p>
      <p class="text-xs text-[var(--text-secondary)] max-w-sm mx-auto">
        {{ themeStore.locale === 'bn'
          ? 'নির্বাচিত ফিল্টারে কোনো লিড নেই। ওয়েবসাইট থেকে আবেদন জমা পড়লে এখানে স্বয়ংক্রিয়ভাবে প্রদর্শিত হবে।'
          : 'No leads match the current filters. Submissions from course and ebook pages will appear here.' }}
      </p>
      <button
        @click="resetAllFilters"
        class="px-4 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-bold text-[var(--text-primary)] transition-all cursor-pointer"
      >
        {{ themeStore.locale === 'bn' ? 'সকল ফিল্টার রিসেট করুন' : 'Reset All Filters' }}
      </button>
    </div>

    <!-- 7A. Table View (Clean, Content-Attributed CRM Table) -->
    <div v-else-if="viewMode === 'table'" class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-2xl overflow-hidden shadow-sm">
      <!-- Phone: card list (same data & actions as the table) -->
      <ul class="md:hidden divide-y divide-[var(--border-subtle)]">
        <li v-for="lead in leads" :key="`m-${lead.id}`" class="p-4 space-y-3">
          <!-- Name + time + priority -->
          <div class="flex items-start justify-between gap-3">
            <button type="button" class="min-w-0 text-left cursor-pointer" @click="openDetailDrawer(lead)">
              <p class="text-sm font-black text-[var(--text-primary)] break-words">{{ lead.name }}</p>
              <p v-if="lead.email" class="text-[11px] text-[var(--text-secondary)] break-all">{{ lead.email }}</p>
              <p class="text-[11px] text-[var(--text-muted)] mt-0.5">{{ formatTimeAgo(lead.created_at) }}</p>
            </button>
            <span
              class="shrink-0 px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border inline-flex items-center gap-1"
              :class="getPriorityBadgeClass(lead.priority)"
            >{{ lead.priority || 'normal' }}</span>
          </div>

          <!-- Source content -->
          <div class="flex flex-wrap items-center gap-1.5">
            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider" :class="getLeadTypeBadgeClass(lead.lead_type)">
              {{ getLeadTypeLabel(lead.lead_type) }}
            </span>
            <span class="text-xs font-bold text-[var(--text-primary)] break-words min-w-0">
              {{ lead.source_content_title || lead.interested_topic || (themeStore.locale === 'bn' ? 'সাধারণ অনুসন্ধান' : 'General Inquiry') }}
            </span>
          </div>
          <p v-if="lead.notes" class="text-[11px] text-[var(--text-secondary)] italic line-clamp-2">"{{ lead.notes }}"</p>

          <!-- Call / WhatsApp -->
          <div class="grid grid-cols-2 gap-2">
            <a
              :href="`tel:${lead.phone}`"
              class="min-h-[44px] px-3 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] flex items-center justify-center gap-1.5 min-w-0"
            >
              <svg class="w-4 h-4 text-[#D4AF37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              <span class="font-mono truncate">{{ lead.phone }}</span>
            </a>
            <a
              :href="getWhatsAppUrl(lead)"
              target="_blank"
              rel="noopener noreferrer"
              class="min-h-[44px] px-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center justify-center gap-1.5"
            >WhatsApp ↗</a>
          </div>

          <!-- Status + owner -->
          <div class="grid grid-cols-1 min-[400px]:grid-cols-2 gap-2">
            <select
              :value="lead.status"
              @change="updateStatus(lead.id, ($event.target as HTMLSelectElement).value)"
              class="w-full min-h-[44px] px-3 rounded-xl border text-xs font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer"
              :class="getStatusSelectClass(lead.status)"
              :aria-label="themeStore.locale === 'bn' ? 'স্ট্যাটাস' : 'Status'"
            >
              <option value="new">{{ themeStore.locale === 'bn' ? 'নতুন' : 'New' }}</option>
              <option value="contacted">{{ themeStore.locale === 'bn' ? 'যোগাযোগ হয়েছে' : 'Contacted' }}</option>
              <option value="follow_up">{{ themeStore.locale === 'bn' ? 'ফলো-আপ' : 'Follow-Up' }}</option>
              <option value="qualified">{{ themeStore.locale === 'bn' ? 'যোগ্য' : 'Qualified' }}</option>
              <option value="converted">{{ themeStore.locale === 'bn' ? 'ভর্তি সম্পন্ন' : 'Converted' }}</option>
              <option value="not_interested">{{ themeStore.locale === 'bn' ? 'আগ্রহী নন' : 'Not Interested' }}</option>
              <option value="no_response">{{ themeStore.locale === 'bn' ? 'উত্তর মেলেনি' : 'No Response' }}</option>
              <option value="lost">{{ themeStore.locale === 'bn' ? 'বাতিল' : 'Lost' }}</option>
            </select>
            <div v-if="lead.employee" class="min-h-[44px] px-3 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] flex items-center gap-2 text-xs font-bold text-[var(--text-primary)] min-w-0">
              <span class="w-6 h-6 rounded-full bg-[#D4AF37]/20 text-[#D4AF37] flex items-center justify-center text-[10px] font-black shrink-0">{{ lead.employee.name.charAt(0) }}</span>
              <span class="truncate">{{ lead.employee.name }}</span>
            </div>
            <button
              v-else
              type="button"
              @click="openAcceptModal(lead)"
              class="min-h-[44px] px-3 rounded-xl bg-[#D4AF37]/15 text-[#B8941F] dark:text-[#D4AF37] font-black text-xs border border-[#D4AF37]/40 cursor-pointer"
            >
              ✓ {{ canAssignEmployees ? (themeStore.locale === 'bn' ? 'অ্যাসাইন / গ্রহণ' : 'Assign / Accept') : (themeStore.locale === 'bn' ? 'লিড গ্রহণ করুন' : 'Accept lead') }}
            </button>
          </div>

          <!-- Actions -->
          <div class="flex items-center gap-2">
            <button
              type="button"
              @click="openDetailDrawer(lead)"
              class="flex-1 min-h-[44px] rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] cursor-pointer"
            >{{ themeStore.locale === 'bn' ? 'বিস্তারিত, নোট ও অ্যাক্টিভিটি' : 'Details, notes & activity' }}</button>
            <button
              v-if="lead.status !== 'converted'"
              type="button"
              @click="openEnrollFromLead(lead)"
              class="min-h-[44px] px-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold cursor-pointer"
            >🎓 {{ themeStore.locale === 'bn' ? 'ভর্তি' : 'Enroll' }}</button>
            <button
              type="button"
              @click="deleteLead(lead.id)"
              class="w-11 h-11 rounded-xl text-rose-500 bg-rose-500/10 border border-rose-500/20 flex items-center justify-center cursor-pointer shrink-0"
              :aria-label="themeStore.locale === 'bn' ? 'মুছে ফেলুন' : 'Delete Lead'"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
          </div>
        </li>
      </ul>

      <div class="table-responsive-container hidden md:block">
        <table class="w-full text-left text-xs min-w-[950px]">
          <thead class="bg-[var(--bg-elevated)] border-b border-[var(--border-subtle)] text-[var(--text-secondary)] uppercase tracking-wider font-extrabold text-[10px]">
            <tr>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'গ্রাহক / প্রার্থী' : 'Lead Contact' }}</th>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'ফোন ও WhatsApp' : 'Phone & WhatsApp' }}</th>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'আগ্রহের কনটেন্ট (Attribution)' : 'Source Content' }}</th>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'সিআরএম স্ট্যাটাস' : 'CRM Status' }}</th>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'প্রায়োরিটি' : 'Priority' }}</th>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'কাউন্সেলর' : 'Assigned To' }}</th>
              <th class="py-3.5 px-5 text-right">{{ $t('common.actions') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-subtle)]">
            <tr
              v-for="lead in leads"
              :key="lead.id"
              class="hover:bg-[var(--bg-elevated)]/50 transition-colors group cursor-pointer"
              @click="openDetailDrawer(lead)"
            >
              
              <!-- 1. Person Information -->
              <td class="py-4 px-5">
                <div class="font-bold text-[var(--text-primary)] text-sm group-hover:text-[#D4AF37] transition-colors">
                  {{ lead.name }}
                </div>
                <div v-if="lead.email" class="text-[11px] text-[var(--text-secondary)]">
                  {{ lead.email }}
                </div>
                <div class="text-[10px] text-[var(--text-muted)] mt-0.5 flex items-center gap-1">
                  <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  <span>{{ formatTimeAgo(lead.created_at) }}</span>
                </div>
              </td>

              <!-- 2. Phone & WhatsApp Quick Direct Actions -->
              <td class="py-4 px-5 whitespace-nowrap" @click.stop>
                <div class="space-y-1.5">
                  <!-- Phone Action -->
                  <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <a
                      :href="`tel:${lead.phone}`"
                      class="font-mono font-bold text-[var(--text-primary)] hover:text-[#D4AF37] transition-colors"
                      :title="themeStore.locale === 'bn' ? 'সরাসরি কল করুন' : 'Call directly'"
                    >
                      {{ lead.phone }}
                    </a>
                  </div>

                  <!-- WhatsApp Action -->
                  <div class="flex items-center gap-1.5">
                    <a
                      :href="getWhatsAppUrl(lead)"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-mono font-bold text-[11px] border border-emerald-500/20 transition-all hover:scale-105"
                      :title="themeStore.locale === 'bn' ? 'WhatsApp-এ সরাসরি বার্তা পাঠান' : 'Chat on WhatsApp'"
                    >
                      <span>WhatsApp: {{ lead.whatsapp_number || lead.phone }}</span>
                      <span class="text-[10px]">↗</span>
                    </a>
                  </div>
                </div>
              </td>

              <!-- 3. Permanent Content Attribution -->
              <td class="py-4 px-5">
                <div class="space-y-1">
                  <!-- Type Badge -->
                  <div class="flex items-center gap-1.5">
                    <span
                      class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider"
                      :class="getLeadTypeBadgeClass(lead.lead_type)"
                    >
                      {{ getLeadTypeLabel(lead.lead_type) }}
                    </span>

                    <span v-if="lead.source_url" class="text-[10px] text-[var(--text-muted)] truncate max-w-[140px]" :title="lead.source_url">
                      {{ lead.source_url }}
                    </span>
                  </div>

                  <!-- Content Title -->
                  <div class="font-bold text-[var(--text-primary)] text-xs line-clamp-1 max-w-xs">
                    {{ lead.source_content_title || lead.interested_topic || (themeStore.locale === 'bn' ? 'সাধারণ অনুসন্ধান' : 'General Inquiry') }}
                  </div>

                  <!-- Notes preview -->
                  <div v-if="lead.notes" class="text-[11px] text-[var(--text-secondary)] line-clamp-1 max-w-xs italic">
                    "{{ lead.notes }}"
                  </div>
                </div>
              </td>

              <!-- 4. CRM Pipeline Status -->
              <td class="py-4 px-5 whitespace-nowrap" @click.stop>
                <select
                  :value="lead.status"
                  @change="updateStatus(lead.id, ($event.target as HTMLSelectElement).value)"
                  class="px-2.5 py-1.5 rounded-xl border text-xs font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer transition-colors"
                  :class="getStatusSelectClass(lead.status)"
                >
                  <option value="new">{{ themeStore.locale === 'bn' ? 'নতুন (New)' : 'New' }}</option>
                  <option value="contacted">{{ themeStore.locale === 'bn' ? 'যোগাযোগ হয়েছে (Contacted)' : 'Contacted' }}</option>
                  <option value="follow_up">{{ themeStore.locale === 'bn' ? 'ফলো-আপ (Follow-Up)' : 'Follow-Up' }}</option>
                  <option value="qualified">{{ themeStore.locale === 'bn' ? 'যোগ্য (Qualified)' : 'Qualified' }}</option>
                  <option value="converted">{{ themeStore.locale === 'bn' ? 'ভর্তি সম্পন্ন (Converted)' : 'Converted' }}</option>
                  <option value="not_interested">{{ themeStore.locale === 'bn' ? 'আগ্রহী নন (Not Interested)' : 'Not Interested' }}</option>
                  <option value="no_response">{{ themeStore.locale === 'bn' ? 'উত্তর মেলেনি (No Response)' : 'No Response' }}</option>
                  <option value="lost">{{ themeStore.locale === 'bn' ? 'বাতিল (Lost)' : 'Lost' }}</option>
                </select>
              </td>

              <!-- 5. Priority Pill -->
              <td class="py-4 px-5 whitespace-nowrap" @click.stop>
                <span
                  class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border inline-flex items-center gap-1"
                  :class="getPriorityBadgeClass(lead.priority)"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="lead.priority === 'urgent' ? 'bg-rose-500 animate-ping' : ''"></span>
                  <span>{{ lead.priority || 'normal' }}</span>
                </span>
              </td>

              <!-- 6. Lead owner (employee name) / Accept -->
              <td class="py-4 px-5 whitespace-nowrap" @click.stop>
                <div v-if="lead.employee" class="flex items-center gap-1.5 text-xs text-[var(--text-primary)] font-bold">
                  <div class="w-5 h-5 rounded-full bg-[#D4AF37]/20 text-[#D4AF37] flex items-center justify-center text-[10px] font-black">
                    {{ lead.employee.name.charAt(0) }}
                  </div>
                  <div class="leading-tight">
                    <span>{{ lead.employee.name }}</span>
                    <span v-if="lead.accepted_at" class="block text-[10px] font-normal text-[var(--text-muted)]">{{ formatTimeAgo(lead.accepted_at) }}</span>
                  </div>
                </div>
                <button
                  v-else
                  type="button"
                  @click="openAcceptModal(lead)"
                  class="px-2.5 py-1.5 rounded-lg bg-[#D4AF37]/15 hover:bg-[#D4AF37]/25 text-[#B8941F] dark:text-[#D4AF37] font-black text-[11px] border border-[#D4AF37]/40 transition-colors cursor-pointer inline-flex items-center gap-1"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  {{ canAssignEmployees ? (themeStore.locale === 'bn' ? 'অ্যাসাইন / গ্রহণ' : 'Assign / Accept') : (themeStore.locale === 'bn' ? 'লিড গ্রহণ করুন' : 'Accept lead') }}
                </button>
              </td>

              <!-- 7. Actions -->
              <td class="py-4 px-5 text-right whitespace-nowrap" @click.stop>
                <div class="flex items-center justify-end gap-1.5">
                  <!-- Quick Direct Course Enrollment Action -->
                  <button
                    v-if="lead.status !== 'converted'"
                    @click="openEnrollFromLead(lead)"
                    class="px-2.5 py-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-bold text-[11px] border border-emerald-500/30 transition-colors cursor-pointer inline-flex items-center gap-1"
                    :title="themeStore.locale === 'bn' ? 'কোর্সে সরাসরি ভর্তি করুন' : 'Enroll to Course'"
                  >
                    <span>🎓</span>
                    <span>{{ themeStore.locale === 'bn' ? 'ভর্তি' : 'Enroll' }}</span>
                  </button>

                  <button
                    @click="openDetailDrawer(lead)"
                    class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[var(--border-subtle)] text-[var(--text-primary)] font-bold text-[11px] border border-[var(--border-subtle)] transition-colors cursor-pointer"
                    :title="themeStore.locale === 'bn' ? 'বিস্তারিত দেখুন' : 'View Details'"
                  >
                    {{ themeStore.locale === 'bn' ? 'বিস্তারিত' : 'View' }}
                  </button>

                  <button
                    @click="deleteLead(lead.id)"
                    class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-500/10 transition-colors cursor-pointer"
                    :title="themeStore.locale === 'bn' ? 'মুছে ফেলুন' : 'Delete Lead'"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                  </button>
                </div>
              </td>

            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" class="p-4 border-t border-[var(--border-subtle)] flex flex-wrap items-center justify-between gap-2 text-xs text-[var(--text-secondary)]">
        <span>
          {{ themeStore.locale === 'bn' ? 'পৃষ্ঠা' : 'Page' }} {{ formatNumber(pagination.current_page, themeStore.locale) }} / {{ formatNumber(pagination.last_page, themeStore.locale) }}
        </span>

        <div class="flex items-center gap-2">
          <button
            :disabled="pagination.current_page <= 1"
            @click="changePage(pagination.current_page - 1)"
            class="min-h-[44px] px-4 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] disabled:opacity-50 cursor-pointer font-bold"
          >
            ← {{ themeStore.locale === 'bn' ? 'পূর্ববর্তী' : 'Previous' }}
          </button>
          <button
            :disabled="pagination.current_page >= pagination.last_page"
            @click="changePage(pagination.current_page + 1)"
            class="min-h-[44px] px-4 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] disabled:opacity-50 cursor-pointer font-bold"
          >
            {{ themeStore.locale === 'bn' ? 'পরবর্তী' : 'Next' }} →
          </button>
        </div>
      </div>
    </div>

    <!-- 7B. Kanban Pipeline View -->
    <div v-else class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
      <div
        v-for="col in kanbanColumns"
        :key="col.id"
        class="rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] flex flex-col max-h-[75vh]"
      >
        <!-- Column Header -->
        <div class="p-3.5 border-b border-[var(--border-subtle)] flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full" :class="col.dotClass"></span>
            <h4 class="text-xs font-black text-[var(--text-primary)] uppercase tracking-wider">
              {{ themeStore.locale === 'bn' ? col.label_bn : col.label_en }}
            </h4>
          </div>
          <span class="px-2 py-0.5 rounded-full bg-[var(--bg-elevated)] text-[10px] font-black text-[var(--text-muted)] border border-[var(--border-subtle)]">
            {{ formatNumber(getKanbanColumnLeads(col.id).length, themeStore.locale) }}
          </span>
        </div>

        <!-- Cards Container -->
        <div class="p-3 space-y-3 overflow-y-auto flex-1 min-h-[150px]">
          <div
            v-for="lead in getKanbanColumnLeads(col.id)"
            :key="lead.id"
            @click="openDetailDrawer(lead)"
            class="p-3.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] transition-all cursor-pointer space-y-2 shadow-xs"
          >
            <div class="flex items-start justify-between gap-2">
              <span
                class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider"
                :class="getLeadTypeBadgeClass(lead.lead_type)"
              >
                {{ getLeadTypeLabel(lead.lead_type) }}
              </span>
              <span
                class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase"
                :class="getPriorityBadgeClass(lead.priority)"
              >
                {{ lead.priority }}
              </span>
            </div>

            <div class="font-bold text-xs text-[var(--text-primary)]">
              {{ lead.name }}
            </div>

            <div class="text-[11px] text-[#D4AF37] font-bold truncate">
              {{ lead.source_content_title || lead.interested_topic || 'Lead' }}
            </div>

            <div class="flex items-center justify-between text-[10px] text-[var(--text-muted)] pt-1 border-t border-[var(--border-subtle)]">
              <span class="font-mono">{{ lead.phone }}</span>
              <span>{{ formatTimeAgo(lead.created_at) }}</span>
            </div>
          </div>

          <div v-if="getKanbanColumnLeads(col.id).length === 0" class="text-center py-8 text-[11px] text-[var(--text-muted)] italic">
            {{ themeStore.locale === 'bn' ? 'কোনো লিড নেই' : 'No leads' }}
          </div>
        </div>
      </div>
    </div>

    <!-- 8. Right-Side Slide-Over Lead Detail Drawer -->
    <div v-if="selectedLead" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex justify-end transition-opacity">
      <div class="w-full max-w-xl bg-[var(--bg-surface)] border-l border-[var(--border-subtle)] h-full max-h-dvh overflow-y-auto overscroll-contain p-4 sm:p-8 space-y-6 shadow-2xl flex flex-col justify-between safe-top safe-bottom">
        
        <!-- Drawer Top Navigation & Close -->
        <div class="space-y-6">
          <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-4">
            <div class="flex items-center gap-2">
              <span
                class="px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wider"
                :class="getLeadTypeBadgeClass(selectedLead.lead_type)"
              >
                {{ getLeadTypeLabel(selectedLead.lead_type) }}
              </span>
              <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'লিড বিস্তারিত প্রোফাইল' : 'Lead CRM Profile' }}
              </h3>
            </div>
            
            <button
              @click="selectedLead = null"
              class="w-8 h-8 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] flex items-center justify-center text-sm cursor-pointer"
            >✕</button>
          </div>

          <!-- Section 1: Customer Contact Card -->
          <div class="p-5 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-4">
            <div class="flex items-start justify-between">
              <div>
                <h4 class="text-base sm:text-lg font-black text-[var(--text-primary)]">
                  {{ selectedLead.name }}
                </h4>
                <p v-if="selectedLead.email" class="text-xs text-[var(--text-secondary)] mt-0.5">
                  {{ selectedLead.email }}
                </p>
                <p class="text-[10px] text-[var(--text-muted)] mt-1">
                  {{ themeStore.locale === 'bn' ? 'আবেদনের সময়:' : 'Captured at:' }} {{ formatDateFull(selectedLead.created_at) }}
                </p>
              </div>

              <span
                class="px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wider border"
                :class="getPriorityBadgeClass(selectedLead.priority)"
              >
                {{ selectedLead.priority || 'normal' }}
              </span>
            </div>

            <!-- Direct Quick Action Contact Strip -->
            <div class="grid grid-cols-2 gap-3 pt-2 border-t border-[var(--border-subtle)]">
              <!-- Call -->
              <a
                :href="`tel:${selectedLead.phone}`"
                class="py-2.5 px-3 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-bold text-[var(--text-primary)] hover:text-[#D4AF37] transition-all flex items-center justify-center gap-2"
              >
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <span>{{ selectedLead.phone }}</span>
              </a>

              <!-- WhatsApp -->
              <a
                :href="getWhatsAppUrl(selectedLead)"
                target="_blank"
                rel="noopener noreferrer"
                class="py-2.5 px-3 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-xs"
              >
                <span>WhatsApp চ্যাট</span>
                <span>↗</span>
              </a>
            </div>

            <!-- One-Click Direct Course Enrollment Action Strip -->
            <div v-if="selectedLead.status !== 'converted'" class="p-3.5 rounded-xl bg-gradient-to-r from-[#D4AF37]/10 via-emerald-500/10 to-[#D4AF37]/10 border border-[#D4AF37]/40 flex items-center justify-between gap-3">
              <div>
                <div class="font-bold text-xs text-[var(--text-primary)]">
                  {{ themeStore.locale === 'bn' ? 'শিক্ষার্থীকে কোর্সে সরাসরি ভর্তি করুন' : 'Direct Course Enrollment' }}
                </div>
                <div class="text-[10px] text-[var(--text-secondary)]">
                  {{ themeStore.locale === 'bn' ? 'স্বয়ংক্রিয় স্টুডেন্ট অ্যাকাউন্ট ও ব্যাচ যুক্ত হবে।' : 'Creates student account and enrolls to batch' }}
                </div>
              </div>
              <button
                @click="openEnrollFromLead(selectedLead)"
                class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black text-xs hover:shadow-md transition-all cursor-pointer shrink-0"
              >
                🎓 {{ themeStore.locale === 'bn' ? 'ভর্তি করুন' : 'Enroll Now' }}
              </button>
            </div>
            <div v-else class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-xs text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-2">
              <span>✓</span>
              <span>{{ themeStore.locale === 'bn' ? 'এই শিক্ষার্থীর কোর্স ভর্তি সম্পন্ন হয়েছে (Converted)' : 'Student Enrolled (Converted)' }}</span>
            </div>
          </div>

          <!-- Section 2: Permanent Content Attribution & Source -->
          <div class="p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-accent)] space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-black uppercase text-[#D4AF37] tracking-wider">
                {{ themeStore.locale === 'bn' ? 'স্থায়ী কন্টেন্ট অ্যাট্রিবিউশন' : 'Permanent Content Attribution' }}
              </span>
              <span class="text-[10px] text-[var(--text-muted)] font-mono">
                ID: #{{ selectedLead.source_content_id || 'N/A' }}
              </span>
            </div>

            <div class="space-y-1">
              <div class="text-sm font-black text-[var(--text-primary)]">
                {{ selectedLead.source_content_title || selectedLead.interested_topic || (themeStore.locale === 'bn' ? 'সাধারণ অনুসন্ধান' : 'General') }}
              </div>
              
              <div v-if="selectedLead.source_url" class="text-xs text-[var(--text-secondary)] flex items-center gap-1.5 pt-1">
                <span class="text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'আবেদিত পেজ:' : 'Source URL:' }}</span>
                <a
                  :href="selectedLead.source_url"
                  target="_blank"
                  class="text-[#D4AF37] hover:underline font-mono text-[11px] truncate max-w-xs"
                >
                  {{ selectedLead.source_url }} ↗
                </a>
              </div>
            </div>
          </div>

          <!-- Section 2b: Lead owner (employee) tracking -->
          <div class="p-5 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-3">
            <div class="flex items-center justify-between gap-2">
              <h4 class="text-xs font-black text-[var(--text-primary)] uppercase tracking-wider">
                {{ themeStore.locale === 'bn' ? 'দায়িত্বপ্রাপ্ত কর্মী' : 'Lead Owner' }}
              </h4>
              <span v-if="!selectedLead.employee" class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-500/15 text-purple-500 border border-purple-500/30">
                {{ themeStore.locale === 'bn' ? 'অনির্ধারিত' : 'Unassigned' }}
              </span>
            </div>

            <div v-if="selectedLead.employee" class="flex items-center gap-3 text-xs">
              <div class="w-9 h-9 rounded-full bg-[#D4AF37]/20 text-[#D4AF37] flex items-center justify-center font-black">{{ selectedLead.employee.name.charAt(0) }}</div>
              <div class="min-w-0">
                <p class="font-bold text-[var(--text-primary)]">{{ selectedLead.employee.name }}<span v-if="selectedLead.employee.designation" class="font-normal text-[var(--text-muted)]"> · {{ selectedLead.employee.designation }}</span></p>
                <p class="text-[10px] text-[var(--text-muted)]">
                  <template v-if="selectedLead.accepted_at">{{ themeStore.locale === 'bn' ? 'গ্রহণ করা হয়েছে' : 'Accepted' }}: {{ formatDateFull(selectedLead.accepted_at) }}</template>
                  <template v-if="selectedLead.accepted_by"> · {{ themeStore.locale === 'bn' ? 'অ্যাকাউন্ট' : 'via' }}: {{ selectedLead.accepted_by.name }}</template>
                </p>
              </div>
            </div>

            <!-- Moderator: accept an unassigned lead -->
            <button
              v-if="!selectedLead.employee && !canAssignEmployees"
              type="button"
              @click="openAcceptModal(selectedLead)"
              class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black text-xs cursor-pointer"
            >
              ✓ {{ themeStore.locale === 'bn' ? 'এই লিডটি আমার নামে গ্রহণ করুন' : 'Accept this lead under my name' }}
            </button>

            <!-- Admin / Manager: assign as task or re-assign -->
            <div v-if="canAssignEmployees" class="flex flex-col sm:flex-row gap-2">
              <select
                v-model="drawerForm.employee_id"
                class="flex-1 px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs font-bold focus:outline-none focus:border-[#D4AF37]"
              >
                <option value="">{{ themeStore.locale === 'bn' ? '— অনির্ধারিত রাখুন —' : '— Leave unassigned —' }}</option>
                <option v-for="emp in employeeOptions" :key="emp.id" :value="String(emp.id)">{{ emp.name }}{{ emp.designation ? ` (${emp.designation})` : '' }}</option>
              </select>
              <p v-if="employeeOptions.length === 0" class="text-[10px] text-amber-500 self-center">
                {{ themeStore.locale === 'bn' ? 'কর্মী তালিকা খালি — "কর্মী তালিকা" পেজ থেকে নাম যুক্ত করুন।' : 'No employees yet — add names on the Employees page.' }}
              </p>
            </div>
            <p v-if="canAssignEmployees" class="text-[10px] text-[var(--text-muted)]">
              {{ themeStore.locale === 'bn' ? 'পরিবর্তন "আপডেট সংরক্ষণ করুন" চাপলে কার্যকর হবে।' : 'Applied when you press "Save Changes" below.' }}
            </p>
          </div>

          <!-- Section 3: CRM Controls (Status, Priority, Assigned Staff, Follow-up Date) -->
          <div class="p-5 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-4">
            <h4 class="text-xs font-black text-[var(--text-primary)] uppercase tracking-wider">
              {{ themeStore.locale === 'bn' ? 'সিআরএম পাইপলাইন কন্ট্রোল' : 'Pipeline Controls' }}
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
              <!-- Status -->
              <div>
                <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'পাইপলাইন স্ট্যাটাস' : 'Status' }}
                </label>
                <select
                  v-model="drawerForm.status"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-bold focus:outline-none focus:border-[#D4AF37]"
                >
                  <option value="new">নতুন (New)</option>
                  <option value="contacted">যোগাযোগ হয়েছে (Contacted)</option>
                  <option value="follow_up">ফলো-আপ (Follow-Up)</option>
                  <option value="qualified">যোগ্য প্রার্থী (Qualified)</option>
                  <option value="converted">ভর্তি সম্পন্ন (Converted)</option>
                  <option value="not_interested">আগ্রহী নন (Not Interested)</option>
                  <option value="no_response">উত্তর মেলেনি (No Response)</option>
                  <option value="lost">বাতিল (Lost)</option>
                </select>
              </div>

              <!-- Priority -->
              <div>
                <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'গুরুত্ব (Priority)' : 'Priority' }}
                </label>
                <select
                  v-model="drawerForm.priority"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-bold focus:outline-none focus:border-[#D4AF37]"
                >
                  <option value="urgent">জরুরি (Urgent)</option>
                  <option value="high">উচ্চ (High)</option>
                  <option value="normal">সাধারণ (Normal)</option>
                  <option value="low">কম (Low)</option>
                </select>
              </div>

              <!-- Next Follow-up Date -->
              <div class="sm:col-span-2">
                <label class="block text-[10px] font-bold text-[var(--text-muted)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'পরবর্তী ফলো-আপ তারিখ ও সময়' : 'Next Follow-up Date & Time' }}
                </label>
                <input
                  v-model="drawerForm.next_follow_up_at"
                  type="datetime-local"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                />
              </div>
            </div>

            <div class="flex justify-end pt-2">
              <button
                @click="saveDrawerDetails"
                :disabled="savingDrawer"
                class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black text-xs hover:shadow-md transition-all cursor-pointer disabled:opacity-50"
              >
                {{ savingDrawer ? (themeStore.locale === 'bn' ? 'সংরক্ষণ হচ্ছে...' : 'Saving...') : (themeStore.locale === 'bn' ? 'আপডেট সংরক্ষণ করুন' : 'Save Changes') }}
              </button>
            </div>
          </div>

          <!-- Section 4: Notes & Timeline Activities -->
          <div class="space-y-4">
            <!-- Add Note Form -->
            <div class="space-y-2">
              <label class="text-xs font-bold text-[var(--text-primary)] flex items-center justify-between">
                <span>{{ themeStore.locale === 'bn' ? 'কাউন্সেলিং নোট যোগ করুন' : 'Add Counseling Note' }}</span>
              </label>
              <div class="flex gap-2">
                <textarea
                  v-model="newNoteText"
                  rows="2"
                  :placeholder="themeStore.locale === 'bn' ? 'গ্রাহকের সাথে আলোচনার সারসংক্ষেপ বা পরবর্তী পদক্ষেপ লিখুন...' : 'Add notes regarding student inquiry...'"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] resize-none"
                ></textarea>
                <button
                  @click="submitNote"
                  :disabled="!newNoteText.trim() || addingNote"
                  class="px-4 py-2 rounded-xl bg-[#D4AF37] hover:bg-[#E5C158] text-slate-950 font-black text-xs transition-all cursor-pointer disabled:opacity-50 self-end shrink-0"
                >
                  {{ addingNote ? '...' : (themeStore.locale === 'bn' ? 'যোগ' : 'Post') }}
                </button>
              </div>
            </div>

            <!-- Notes List -->
            <div v-if="selectedLead.lead_notes && selectedLead.lead_notes.length > 0" class="space-y-2.5">
              <h5 class="text-[11px] font-bold text-[var(--text-muted)] uppercase">
                {{ themeStore.locale === 'bn' ? 'পূর্ববর্তী নোটসমূহ' : 'Notes Thread' }}
              </h5>
              <div
                v-for="note in selectedLead.lead_notes"
                :key="note.id"
                class="p-3 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs space-y-1"
              >
                <p class="text-[var(--text-primary)] leading-relaxed">{{ note.note }}</p>
                <div class="text-[10px] text-[var(--text-muted)] flex items-center justify-between pt-1 border-t border-[var(--border-subtle)]">
                  <span>{{ note.user?.name || 'Staff' }}</span>
                  <span>{{ formatTimeAgo(note.created_at) }}</span>
                </div>
              </div>
            </div>

            <!-- Activity Timeline -->
            <div v-if="selectedLead.lead_activities && selectedLead.lead_activities.length > 0" class="space-y-2.5 pt-2">
              <h5 class="text-[11px] font-bold text-[var(--text-muted)] uppercase">
                {{ themeStore.locale === 'bn' ? 'অ্যাক্টিভিটি টাইমলাইন' : 'Activity Timeline' }}
              </h5>
              <div class="space-y-2 pl-2 border-l-2 border-[#D4AF37]/40 text-xs">
                <div
                  v-for="act in selectedLead.lead_activities"
                  :key="act.id"
                  class="relative pl-3 space-y-0.5"
                >
                  <span class="absolute -left-[11px] top-1.5 w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                  <div class="font-bold text-[var(--text-primary)] text-[11px]">
                    {{ act.description }}
                  </div>
                  <div class="text-[10px] text-[var(--text-muted)]">
                    {{ formatTimeAgo(act.created_at) }}
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>

        <!-- Drawer Bottom Action -->
        <div class="pt-4 border-t border-[var(--border-subtle)] flex items-center justify-between text-xs">
          <button
            @click="deleteLead(selectedLead.id)"
            class="text-rose-500 hover:underline cursor-pointer font-semibold"
          >
            {{ themeStore.locale === 'bn' ? 'লিডটি স্থায়ীভাবে মুছে ফেলুন' : 'Delete Lead' }}
          </button>

          <button
            @click="selectedLead = null"
            class="px-4 py-2 rounded-xl bg-[var(--bg-elevated)] text-[var(--text-secondary)] font-bold cursor-pointer hover:text-[var(--text-primary)]"
          >
            {{ themeStore.locale === 'bn' ? 'বন্ধ করুন' : 'Close' }}
          </button>
        </div>

      </div>
    </div>

    <!-- 9. Manual Add Lead Modal -->
    <div v-if="showAddModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-lg w-full max-h-[90dvh] overflow-y-auto p-6 sm:p-8 space-y-5 shadow-2xl">
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-4">
          <h2 class="text-base sm:text-lg font-black text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'ম্যানুয়ালি নতুন লিড যুক্ত করুন' : 'Add Manual Lead' }}
          </h2>
          <button @click="showAddModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] cursor-pointer">✕</button>
        </div>

        <form @submit.prevent="submitAddLead" class="space-y-4 text-xs">
          <!-- Full Name -->
          <div>
            <label class="block font-bold text-[var(--text-secondary)] mb-1">
              {{ $t('student.full_name') }} <span class="text-rose-500">*</span>
            </label>
            <input
              v-model="newLeadForm.name"
              type="text"
              required
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              :placeholder="themeStore.locale === 'bn' ? 'যেমন: তানভীর হাসান' : 'e.g. Tanvir Hasan'"
            />
          </div>

          <!-- Phone & WhatsApp -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-[var(--text-secondary)] mb-1">
                {{ $t('student.phone') }} <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="newLeadForm.phone"
                type="tel"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                placeholder="018XXXXXXXX"
              />
            </div>
            <div>
              <label class="block font-bold text-[var(--text-secondary)] mb-1">
                {{ themeStore.locale === 'bn' ? 'হোয়াটসঅ্যাপ নম্বর' : 'WhatsApp Number' }}
              </label>
              <input
                v-model="newLeadForm.whatsapp_number"
                type="tel"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                :placeholder="newLeadForm.phone || '018XXXXXXXX'"
              />
            </div>
          </div>

          <!-- Email -->
          <div>
            <label class="block font-bold text-[var(--text-secondary)] mb-1">
              {{ $t('student.email') }} ({{ themeStore.locale === 'bn' ? 'ঐচ্ছিক' : 'Optional' }})
            </label>
            <input
              v-model="newLeadForm.email"
              type="email"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              placeholder="lead@example.com"
            />
          </div>

          <!-- Lead Type & Content Selection -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-[var(--text-secondary)] mb-1">
                {{ themeStore.locale === 'bn' ? 'লিডের ধরন' : 'Lead Type' }}
              </label>
              <select
                v-model="newLeadForm.lead_type"
                class="w-full px-3 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              >
                <option value="course">কোর্স লিড (Course)</option>
                <option value="webinar">ওয়েবিনার (Webinar)</option>
                <option value="seminar">সেমিনার (Seminar)</option>
                <option value="ebook">ইবুক (Ebook)</option>
                <option value="general">সাধারণ (General)</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-[var(--text-secondary)] mb-1">
                {{ themeStore.locale === 'bn' ? 'গুরুত্ব (Priority)' : 'Priority' }}
              </label>
              <select
                v-model="newLeadForm.priority"
                class="w-full px-3 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              >
                <option value="normal">সাধারণ (Normal)</option>
                <option value="high">উচ্চ (High)</option>
                <option value="urgent">জরুরি (Urgent)</option>
                <option value="low">কম (Low)</option>
              </select>
            </div>
          </div>

          <!-- Specific Content / Topic -->
          <div>
            <label class="block font-bold text-[var(--text-secondary)] mb-1">
              {{ themeStore.locale === 'bn' ? 'কন্টেন্ট / আগ্রহের কোর্স বা বিষয়' : 'Interested Content or Topic' }}
            </label>
            <input
              v-model="newLeadForm.interested_topic"
              type="text"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              :placeholder="themeStore.locale === 'bn' ? 'যেমন: প্রফেশনাল এয়ার টিকেটিং ও ভিসা প্রসেসিং' : 'e.g. Professional Air Ticketing'"
            />
          </div>

          <!-- Initial Note -->
          <div>
            <label class="block font-bold text-[var(--text-secondary)] mb-1">
              {{ themeStore.locale === 'bn' ? 'প্রাথমিক নোট' : 'Initial Note' }}
            </label>
            <textarea
              v-model="newLeadForm.notes"
              rows="2"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] resize-none"
              :placeholder="themeStore.locale === 'bn' ? 'কাউন্সেলিং বিবরণ...' : 'Initial counseling remark...'"
            ></textarea>
          </div>

          <div class="flex justify-end gap-3 pt-3 border-t border-[var(--border-subtle)]">
            <button
              type="button"
              @click="showAddModal = false"
              class="px-4 py-2 rounded-xl bg-[var(--bg-deep)] text-[var(--text-secondary)] font-bold cursor-pointer"
            >
              {{ $t('common.cancel') }}
            </button>
            <button
              type="submit"
              :disabled="submittingLead"
              class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black cursor-pointer disabled:opacity-50"
            >
              {{ submittingLead ? (themeStore.locale === 'bn' ? 'সংরক্ষণ হচ্ছে...' : 'Saving...') : (themeStore.locale === 'bn' ? 'লিড যুক্ত করুন' : 'Add Lead') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- 10. Convert Lead to Course Enrollment Modal -->
    <AppModal
      v-model="isConvertModalOpen"
      :title="themeStore.locale === 'bn' ? 'লিড থেকে সরাসরি কোর্সে ভর্তি' : 'Convert Lead to Course Enrollment'"
      size="md"
    >
      <form v-if="convertingLead" @submit.prevent="submitConvertToEnrollment" class="space-y-4 text-xs">
        
        <!-- Student Info Preview -->
        <div class="p-3.5 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-accent)] space-y-1">
          <div class="flex items-center justify-between">
            <span class="font-bold text-[var(--text-primary)] text-sm">{{ convertingLead.name }}</span>
            <span class="text-[10px] font-mono text-[#D4AF37]">{{ convertingLead.phone }}</span>
          </div>
          <div v-if="convertingLead.email" class="text-[11px] text-[var(--text-secondary)]">
            {{ convertingLead.email }}
          </div>
        </div>

        <!-- Course Select -->
        <div>
          <label class="block font-bold text-[var(--text-secondary)] mb-1">
            {{ themeStore.locale === 'bn' ? 'ভর্তির কোর্স *' : 'Enrollment Course *' }}
          </label>
          <select
            v-model="convertForm.course_id"
            @change="onConvertCourseChange"
            required
            class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer"
          >
            <option value="" disabled>{{ themeStore.locale === 'bn' ? 'কোর্স নির্বাচন করুন' : 'Select Course' }}</option>
            <option v-for="c in availableCourses" :key="c.id" :value="c.id">
              {{ themeStore.locale === 'bn' ? (c.title_bn || c.title_en) : (c.title_en || c.title_bn) }}
            </option>
          </select>
        </div>

        <!-- Batch Select -->
        <div>
          <label class="block font-bold text-[var(--text-secondary)] mb-1">
            {{ themeStore.locale === 'bn' ? 'কোর্স ব্যাচ নির্ধারণ' : 'Assign Batch' }}
          </label>
          <select
            v-model="convertForm.batch_id"
            class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer"
          >
            <option value="">{{ themeStore.locale === 'bn' ? 'চলতি ব্যাচ (স্বয়ংক্রিয়)' : 'Current Enrolling Batch' }}</option>
            <option v-for="b in convertBatches" :key="b.id" :value="b.id">
              {{ themeStore.locale === 'bn' ? (b.title_bn || b.title_en || b.batch_number) : (b.title_en || b.title_bn || b.batch_number) }}
              ({{ b.enrolled_students || 0 }}/{{ b.seat_capacity || 30 }} সিট)
            </option>
          </select>
        </div>

        <!-- Fee & Payment Method -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block font-bold text-[var(--text-secondary)] mb-1">
              {{ themeStore.locale === 'bn' ? 'ভর্তি ফি (টাকা)' : 'Fee (BDT)' }}
            </label>
            <input
              v-model="convertForm.fee_amount"
              type="number"
              placeholder="e.g. 5000"
              class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
            />
          </div>

          <div>
            <label class="block font-bold text-[var(--text-secondary)] mb-1">
              {{ themeStore.locale === 'bn' ? 'পেমেন্ট মাধ্যম' : 'Payment Method' }}
            </label>
            <select
              v-model="convertForm.payment_method"
              class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer"
            >
              <option value="manual_counselor">অফিস ক্যাশ / সরাসরি (Office Cash)</option>
              <option value="bkash">bKash</option>
              <option value="nagad">Nagad</option>
              <option value="bank_transfer">ব্যাংক ট্রান্সফার (Bank Transfer)</option>
            </select>
          </div>
        </div>

        <!-- Notes -->
        <div>
          <label class="block font-bold text-[var(--text-secondary)] mb-1">
            {{ themeStore.locale === 'bn' ? 'ভর্তি সংক্রান্ত মন্তব্য' : 'Enrollment Remarks' }}
          </label>
          <input
            v-model="convertForm.notes"
            type="text"
            placeholder="e.g. সফল কাউন্সেলিংয়ের পর ভর্তি নিশ্চিত..."
            class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
          />
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[var(--border-subtle)]">
          <button
            type="button"
            @click="isConvertModalOpen = false"
            class="px-4 py-2.5 rounded-xl bg-[var(--bg-deep)] text-[var(--text-secondary)] font-bold cursor-pointer"
          >
            {{ $t('common.cancel') }}
          </button>
          <button
            type="submit"
            :disabled="convertingSubmitting"
            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black cursor-pointer disabled:opacity-50"
          >
            {{ convertingSubmitting ? (themeStore.locale === 'bn' ? 'ভর্তি হচ্ছে...' : 'Enrolling...') : (themeStore.locale === 'bn' ? 'ভর্তি কনফার্ম করুন' : 'Confirm Enrollment') }}
          </button>
        </div>

      </form>
    </AppModal>

    <!-- 11. Accept / Assign Lead Modal (pick employee name) -->
    <AppModal
      v-model="showAcceptModal"
      :title="canAssignEmployees ? (themeStore.locale === 'bn' ? 'লিড অ্যাসাইন করুন' : 'Assign lead') : (themeStore.locale === 'bn' ? 'লিড গ্রহণ করুন' : 'Accept lead')"
      size="sm"
    >
      <div v-if="acceptingLead" class="space-y-4">
        <div class="p-3 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs">
          <p class="font-bold text-[var(--text-primary)]">{{ acceptingLead.name }}</p>
          <p class="text-[var(--text-muted)]">{{ acceptingLead.phone }}<template v-if="acceptingLead.source_content_title"> · {{ acceptingLead.source_content_title }}</template></p>
        </div>

        <div>
          <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1.5">
            {{ canAssignEmployees
              ? (themeStore.locale === 'bn' ? 'কোন কর্মীকে টাস্ক দেবেন?' : 'Assign to which employee?')
              : (themeStore.locale === 'bn' ? 'তালিকা থেকে আপনার নাম নির্বাচন করুন' : 'Select your name from the list') }}
          </label>
          <select
            v-model="acceptEmployeeId"
            class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-sm font-bold text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
          >
            <option value="" disabled>{{ themeStore.locale === 'bn' ? '— নাম নির্বাচন করুন —' : '— Select a name —' }}</option>
            <option v-for="emp in employeeOptions" :key="emp.id" :value="String(emp.id)">{{ emp.name }}{{ emp.designation ? ` (${emp.designation})` : '' }}</option>
          </select>
          <p v-if="employeeOptions.length === 0" class="text-[11px] text-amber-500 mt-1.5">
            {{ themeStore.locale === 'bn' ? 'কোনো কর্মীর নাম নেই। অ্যাডমিনকে "কর্মী তালিকা"-তে আপনার নাম যুক্ত করতে বলুন।' : 'No employee names yet. Ask an Admin to add yours on the Employees page.' }}
          </p>
          <p class="text-[10px] text-[var(--text-muted)] mt-1.5">
            {{ themeStore.locale === 'bn' ? 'নামের তালিকা শুধুমাত্র অ্যাডমিন পরিবর্তন করতে পারেন।' : 'Only Admins can change this list of names.' }}
          </p>
        </div>
      </div>
      <template #footer>
        <button type="button" class="px-4 py-2 rounded-xl text-xs text-[var(--text-secondary)] cursor-pointer" @click="showAcceptModal = false">
          {{ themeStore.locale === 'bn' ? 'বাতিল' : 'Cancel' }}
        </button>
        <button
          type="button"
          :disabled="!acceptEmployeeId || acceptingSubmitting"
          class="px-5 py-2 rounded-xl bg-[#D4AF37] text-slate-950 font-black text-xs cursor-pointer disabled:opacity-50"
          @click="submitAcceptLead"
        >
          {{ acceptingSubmitting ? (themeStore.locale === 'bn' ? 'সংরক্ষণ হচ্ছে...' : 'Saving...') : (themeStore.locale === 'bn' ? 'নিশ্চিত করুন' : 'Confirm') }}
        </button>
      </template>
    </AppModal>

  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue';
import { apiClient } from '../../api/client';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import { formatNumber } from '../../utils/locale';
import AppModal from '../../components/ui/AppModal.vue';
import { useAuthStore } from '../../stores/auth';
import { useRoute } from 'vue-router';

const toast = useToastStore();
const themeStore = useThemeStore();
const authStore = useAuthStore();
const route = useRoute();

// Admin / Manager hand leads out as tasks; moderators accept from the unassigned pool
const canAssignEmployees = computed(() => authStore.isAdmin || authStore.isManager);
const employeeOptions = ref<any[]>([]);
const employeeFilter = ref(route.query.employee_id ? String(route.query.employee_id) : '');
const showAcceptModal = ref(false);
const acceptingLead = ref<any>(null);
const acceptEmployeeId = ref('');
const acceptingSubmitting = ref(false);

// Phone: filters collapse behind a toggle so the lead list stays visible
const showMobileFilters = ref(false);
const activeFilterCount = computed(() =>
  [
    selectedContentId.value,
    statusFilter.value !== 'all' ? statusFilter.value : '',
    priorityFilter.value !== 'all' ? priorityFilter.value : '',
    followUpFilter.value,
    assignedFilter.value,
    employeeFilter.value,
  ].filter(Boolean).length
);

const leads = ref<any[]>([]);
const loading = ref(true);
const viewMode = ref<'table' | 'kanban'>('table');
const activeLeadTab = ref('all');
const searchQuery = ref('');
const statusFilter = ref('all');
const priorityFilter = ref('all');
const followUpFilter = ref('');
const assignedFilter = ref('');
const selectedContentId = ref('');

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  total: 0,
});

const metrics = reactive({
  total_leads: 0,
  new_leads: 0,
  contacted: 0,
  follow_up: 0,
  qualified: 0,
  converted: 0,
  follow_up_today: 0,
  follow_up_overdue: 0,
  unassigned: 0,
  tab_counts: {
    all: 0,
    course: 0,
    webinar: 0,
    seminar: 0,
    ebook: 0,
    general: 0,
  },
});

// Dropdown options
const availableCourses = ref<any[]>([]);
const availableWebinars = ref<any[]>([]);
const availableEbooks = ref<any[]>([]);

// Drawer & Modal State
const selectedLead = ref<any>(null);
const savingDrawer = ref(false);
const showAddModal = ref(false);
const submittingLead = ref(false);
const newNoteText = ref('');
const addingNote = ref(false);

// Convert to Enrollment Modal State
const isConvertModalOpen = ref(false);
const convertingLead = ref<any>(null);
const convertBatches = ref<any[]>([]);
const convertingSubmitting = ref(false);
const convertForm = reactive({
  course_id: '' as string | number,
  batch_id: '' as string | number,
  fee_amount: '',
  payment_method: 'manual_counselor',
  notes: '',
});

const drawerForm = reactive({
  status: 'new',
  priority: 'normal',
  next_follow_up_at: '',
  employee_id: '' as string,
});

const newLeadForm = reactive({
  name: '',
  phone: '',
  whatsapp_number: '',
  email: '',
  lead_type: 'course',
  priority: 'normal',
  interested_topic: '',
  notes: '',
});

let debounceTimer: any = null;
function debounceSearch() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchLeads();
  }, 300);
}

const kanbanColumns = [
  { id: 'new', label_bn: 'নতুন', label_en: 'New', dotClass: 'bg-sky-500' },
  { id: 'contacted', label_bn: 'যোগাযোগ হয়েছে', label_en: 'Contacted', dotClass: 'bg-amber-500' },
  { id: 'follow_up', label_bn: 'ফলো-আপ', label_en: 'Follow-Up', dotClass: 'bg-purple-500' },
  { id: 'qualified', label_bn: 'যোগ্য প্রার্থী', label_en: 'Qualified', dotClass: 'bg-teal-500' },
  { id: 'converted', label_bn: 'ভর্তি সম্পন্ন', label_en: 'Converted', dotClass: 'bg-emerald-500' },
];

const currentContentOptions = computed(() => {
  if (activeLeadTab.value === 'course') return availableCourses.value;
  if (activeLeadTab.value === 'webinar') return availableWebinars.value;
  if (activeLeadTab.value === 'ebook') return availableEbooks.value;
  return [...availableCourses.value, ...availableWebinars.value, ...availableEbooks.value];
});

function getKanbanColumnLeads(status: string) {
  return leads.value.filter((l) => l.status === status);
}

function getLeadTypeLabel(type: string) {
  switch (type) {
    case 'course': return themeStore.locale === 'bn' ? 'কোর্স' : 'Course';
    case 'webinar': return themeStore.locale === 'bn' ? 'ওয়েবিনার' : 'Webinar';
    case 'seminar': return themeStore.locale === 'bn' ? 'সেমিনার' : 'Seminar';
    case 'ebook': return themeStore.locale === 'bn' ? 'ইবুক' : 'Ebook';
    default: return themeStore.locale === 'bn' ? 'সাধারণ' : 'General';
  }
}

function getLeadTypeBadgeClass(type: string) {
  switch (type) {
    case 'course': return 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30';
    case 'webinar': return 'bg-sky-500/15 text-sky-600 dark:text-sky-400 border border-sky-500/30';
    case 'seminar': return 'bg-purple-500/15 text-purple-600 dark:text-purple-400 border border-purple-500/30';
    case 'ebook': return 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30';
    default: return 'bg-[var(--bg-elevated)] text-[var(--text-secondary)] border border-[var(--border-subtle)]';
  }
}

function getPriorityBadgeClass(pri: string) {
  switch (pri) {
    case 'urgent': return 'bg-rose-500/15 text-rose-500 border-rose-500/30 font-black';
    case 'high': return 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/30 font-bold';
    case 'low': return 'bg-slate-500/15 text-slate-400 border-slate-500/30 font-medium';
    default: return 'bg-blue-500/15 text-blue-500 border-blue-500/30 font-medium';
  }
}

function getStatusSelectClass(status: string) {
  switch (status) {
    case 'new': return 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border-sky-500/30';
    case 'contacted': return 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/30';
    case 'follow_up': return 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/30';
    case 'qualified': return 'bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-500/30';
    case 'converted': return 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/30 font-black';
    default: return 'bg-[var(--bg-elevated)] text-[var(--text-secondary)] border-[var(--border-subtle)]';
  }
}

function getWhatsAppUrl(lead: any) {
  const rawNumber = lead.whatsapp_number || lead.phone || '';
  let cleanNumber = rawNumber.replace(/[^0-9]/g, '');
  if (cleanNumber.startsWith('01')) {
    cleanNumber = '880' + cleanNumber.substring(1);
  } else if (!cleanNumber.startsWith('880') && cleanNumber.length === 10) {
    cleanNumber = '880' + cleanNumber;
  }
  const contentTitle = lead.source_content_title || lead.interested_topic || 'আমাদের প্রোগ্রাম';
  const message = themeStore.locale === 'bn'
    ? `হ্যালো ${lead.name}, ইমিশা একাডেমি থেকে আপনার "${contentTitle}" সংক্রান্ত আগ্রহের বিষয়ে যোগাযোগ করছি। আপনার কোনো প্রশ্ন আছে কি?`
    : `Hello ${lead.name}, reaching out from Emisha Academy regarding your inquiry for "${contentTitle}". How can we help you?`;
  return `https://wa.me/${cleanNumber}?text=${encodeURIComponent(message)}`;
}

function formatTimeAgo(dateStr: string) {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  const now = new Date();
  const diffSec = Math.floor((now.getTime() - date.getTime()) / 1000);
  if (diffSec < 60) return themeStore.locale === 'bn' ? 'এইমাত্র' : 'Just now';
  const diffMin = Math.floor(diffSec / 60);
  if (diffMin < 60) return themeStore.locale === 'bn' ? `${diffMin} মিনিট আগে` : `${diffMin}m ago`;
  const diffHours = Math.floor(diffMin / 60);
  if (diffHours < 24) return themeStore.locale === 'bn' ? `${diffHours} ঘণ্টা আগে` : `${diffHours}h ago`;
  const diffDays = Math.floor(diffHours / 24);
  return themeStore.locale === 'bn' ? `${diffDays} দিন আগে` : `${diffDays}d ago`;
}

function formatDateFull(dateStr: string) {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleDateString(themeStore.locale === 'bn' ? 'bn-BD' : 'en-US', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

async function fetchLeads(page = 1) {
  try {
    loading.value = true;
    const res = await apiClient.get('/admin/leads', {
      params: {
        page,
        lead_type: activeLeadTab.value !== 'all' ? activeLeadTab.value : undefined,
        source_content_id: selectedContentId.value || undefined,
        status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
        priority: priorityFilter.value !== 'all' ? priorityFilter.value : undefined,
        follow_up_filter: followUpFilter.value || undefined,
        assigned_to: assignedFilter.value || undefined,
        employee_id: employeeFilter.value || undefined,
        search: searchQuery.value || undefined,
      },
    });

    if (res.data.status === 'success') {
      const data = res.data.data;
      leads.value = data.data || [];
      pagination.current_page = data.current_page || 1;
      pagination.last_page = data.last_page || 1;
      pagination.total = data.total || leads.value.length;

      if (res.data.metrics) {
        Object.assign(metrics, res.data.metrics);
      }
    }
  } catch (err) {
    toast.error(themeStore.locale === 'bn' ? 'লিড লোড করতে সমস্যা হয়েছে' : 'Failed to load leads');
  } finally {
    loading.value = false;
  }
}

function changePage(page: number) {
  fetchLeads(page);
}

function resetAllFilters() {
  activeLeadTab.value = 'all';
  selectedContentId.value = '';
  statusFilter.value = 'all';
  priorityFilter.value = 'all';
  followUpFilter.value = '';
  assignedFilter.value = '';
  employeeFilter.value = '';
  searchQuery.value = '';
  fetchLeads(1);
}

async function openDetailDrawer(lead: any) {
  try {
    const res = await apiClient.get(`/admin/leads/${lead.id}`);
    if (res.data.status === 'success') {
      selectedLead.value = res.data.data;
      drawerForm.status = selectedLead.value.status || 'new';
      drawerForm.priority = selectedLead.value.priority || 'normal';
      drawerForm.next_follow_up_at = selectedLead.value.next_follow_up_at ? selectedLead.value.next_follow_up_at.substring(0, 16) : '';
      drawerForm.employee_id = selectedLead.value.employee_id ? String(selectedLead.value.employee_id) : '';
      newNoteText.value = '';
    }
  } catch (e) {
    selectedLead.value = lead;
  }
}

async function saveDrawerDetails() {
  if (!selectedLead.value) return;
  savingDrawer.value = true;
  try {
    const payload: any = {
      status: drawerForm.status,
      priority: drawerForm.priority,
    };
    if (drawerForm.next_follow_up_at) {
      payload.next_follow_up_at = drawerForm.next_follow_up_at;
    } else {
      payload.next_follow_up_at = null;
    }
    if (canAssignEmployees.value) {
      payload.employee_id = drawerForm.employee_id ? Number(drawerForm.employee_id) : null;
    }

    const res = await apiClient.put(`/admin/leads/${selectedLead.value.id}`, payload);
    if (res.data.status === 'success') {
      selectedLead.value = res.data.data;
      toast.success(themeStore.locale === 'bn' ? 'লিডের তথ্য আপডেট হয়েছে' : 'Lead updated successfully');
      const found = leads.value.find((l) => l.id === selectedLead.value.id);
      if (found) {
        found.status = drawerForm.status;
        found.priority = drawerForm.priority;
      }
      fetchLeads(pagination.current_page);
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'তথ্য আপডেট করতে সমস্যা হয়েছে' : 'Update failed'));
  } finally {
    savingDrawer.value = false;
  }
}

async function submitNote() {
  if (!selectedLead.value || !newNoteText.value.trim()) return;
  addingNote.value = true;
  try {
    const res = await apiClient.post(`/admin/leads/${selectedLead.value.id}/notes`, {
      note: newNoteText.value.trim(),
    });
    if (res.data.status === 'success') {
      if (!selectedLead.value.lead_notes) selectedLead.value.lead_notes = [];
      selectedLead.value.lead_notes.unshift(res.data.data);
      newNoteText.value = '';
      toast.success(themeStore.locale === 'bn' ? 'নোট যুক্ত হয়েছে' : 'Note added');
      // Refresh lead details for updated timeline
      openDetailDrawer(selectedLead.value);
    }
  } catch (err: any) {
    toast.error('Failed to add note');
  } finally {
    addingNote.value = false;
  }
}

async function updateStatus(leadId: number, status: string) {
  try {
    const res = await apiClient.put(`/admin/leads/${leadId}`, { status });
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'স্ট্যাটাস পরিবর্তিত হয়েছে' : 'Status updated');
      const found = leads.value.find((l) => l.id === leadId);
      if (found) {
        found.status = status;
      }
      if (selectedLead.value && selectedLead.value.id === leadId) {
        selectedLead.value.status = status;
        drawerForm.status = status;
      }
      fetchLeads(pagination.current_page);
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'স্ট্যাটাস আপডেট করতে সমস্যা হয়েছে' : 'Failed to update status'));
  }
}

async function deleteLead(leadId: number) {
  const confirmMsg = themeStore.locale === 'bn' ? 'আপনি কি নিশ্চিত যে এই লিডটি মুছে ফেলতে চান?' : 'Are you sure you want to delete this lead?';
  if (!confirm(confirmMsg)) return;

  try {
    const res = await apiClient.delete(`/admin/leads/${leadId}`);
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'লিড মুছে ফেলা হয়েছে' : 'Lead deleted');
      if (selectedLead.value?.id === leadId) {
        selectedLead.value = null;
      }
      fetchLeads(pagination.current_page);
    }
  } catch {
    toast.error('Failed to delete lead');
  }
}

function openAddLeadModal() {
  newLeadForm.name = '';
  newLeadForm.phone = '';
  newLeadForm.whatsapp_number = '';
  newLeadForm.email = '';
  newLeadForm.lead_type = activeLeadTab.value !== 'all' ? activeLeadTab.value : 'course';
  newLeadForm.priority = 'normal';
  newLeadForm.interested_topic = '';
  newLeadForm.notes = '';
  showAddModal.value = true;
}

async function submitAddLead() {
  submittingLead.value = true;
  try {
    const res = await apiClient.post('/admin/leads', newLeadForm);
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'লিড সফলভাবে যুক্ত হয়েছে' : 'Lead added successfully');
      showAddModal.value = false;
      fetchLeads();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'Failed to add lead');
  } finally {
    submittingLead.value = false;
  }
}

async function openEnrollFromLead(lead: any) {
  convertingLead.value = lead;
  
  if (!availableCourses.value || availableCourses.value.length === 0) {
    await loadDropdowns();
  }

  // Try to match course from lead's source_content_id or course_id
  let matchedCourseId = (lead.source_content_type === 'course' && lead.source_content_id) ? lead.source_content_id : (lead.course_id || '');
  if (!matchedCourseId && availableCourses.value.length > 0) {
    if (lead.interested_topic || lead.source_content_title) {
      const topic = (lead.source_content_title || lead.interested_topic).toLowerCase();
      const matched = availableCourses.value.find((c: any) => 
        (c.title_bn && topic.includes(c.title_bn.toLowerCase())) || 
        (c.title_en && topic.includes(c.title_en.toLowerCase())) ||
        (c.title_bn && c.title_bn.toLowerCase().includes(topic)) ||
        (c.title_en && c.title_en.toLowerCase().includes(topic))
      );
      if (matched) {
        matchedCourseId = matched.id;
      }
    }
    if (!matchedCourseId) {
      matchedCourseId = availableCourses.value[0].id;
    }
  }

  convertForm.course_id = matchedCourseId;
  convertForm.batch_id = '';
  convertForm.fee_amount = '';
  convertForm.payment_method = 'manual_counselor';
  convertForm.notes = '';

  if (convertForm.course_id) {
    await onConvertCourseChange();
  }

  isConvertModalOpen.value = true;
}

async function onConvertCourseChange() {
  if (!convertForm.course_id) {
    convertBatches.value = [];
    return;
  }

  const selCourse = availableCourses.value.find((c) => c.id == convertForm.course_id);
  if (selCourse && !convertForm.fee_amount) {
    convertForm.fee_amount = selCourse.sale_price !== null && selCourse.sale_price !== undefined 
      ? String(selCourse.sale_price) 
      : (selCourse.regular_price ? String(selCourse.regular_price) : '');
  }

  try {
    const res = await apiClient.get(`/admin/courses/${convertForm.course_id}/batches`);
    if (res.data.status === 'success') {
      convertBatches.value = res.data.data || [];
      const enrollingBatch = convertBatches.value.find((b) => b.status === 'enrolling') || convertBatches.value[0];
      if (enrollingBatch) {
        convertForm.batch_id = enrollingBatch.id;
      } else {
        convertForm.batch_id = '';
      }
    }
  } catch (e) {
    convertBatches.value = [];
  }
}

async function submitConvertToEnrollment() {
  if (!convertingLead.value) return;
  try {
    convertingSubmitting.value = true;
    const res = await apiClient.post(`/admin/leads/${convertingLead.value.id}/convert-to-enrollment`, convertForm);

    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'লিড সফলভাবে কোর্সে ভর্তি ও Converted করা হয়েছে!' : 'Lead converted and enrolled in course!');
      isConvertModalOpen.value = false;
      if (selectedLead.value?.id === convertingLead.value.id) {
        openDetailDrawer(convertingLead.value);
      }
      fetchLeads(pagination.current_page);
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'ভর্তি সম্পন্ন করতে সমস্যা হয়েছে' : 'Enrollment failed'));
  } finally {
    convertingSubmitting.value = false;
  }
}

async function loadEmployeeOptions() {
  try {
    const res = await apiClient.get('/admin/employees/options');
    employeeOptions.value = res.data.data || [];
  } catch {
    employeeOptions.value = [];
  }
}

function openAcceptModal(lead: any) {
  acceptingLead.value = lead;
  acceptEmployeeId.value = lead.employee_id ? String(lead.employee_id) : '';
  showAcceptModal.value = true;
  loadEmployeeOptions();
}

async function submitAcceptLead() {
  if (!acceptingLead.value || !acceptEmployeeId.value) return;
  acceptingSubmitting.value = true;
  try {
    const res = await apiClient.post(`/admin/leads/${acceptingLead.value.id}/accept`, {
      employee_id: Number(acceptEmployeeId.value),
    });
    toast.success(res.data.message);
    showAcceptModal.value = false;
    if (selectedLead.value?.id === acceptingLead.value.id) {
      selectedLead.value = res.data.data;
      drawerForm.employee_id = String(res.data.data.employee_id || '');
    }
    fetchLeads(pagination.current_page);
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'লিড গ্রহণ করা যায়নি' : 'Could not accept lead'));
    if (err.response?.status === 409) fetchLeads(pagination.current_page);
  } finally {
    acceptingSubmitting.value = false;
  }
}

async function loadDropdowns() {
  try {
    const [coursesRes, webinarsRes, ebooksRes] = await Promise.allSettled([
      apiClient.get('/admin/courses'),
      apiClient.get('/admin/webinars'),
      apiClient.get('/admin/ebooks'),
    ]);

    if (coursesRes.status === 'fulfilled' && coursesRes.value.data) {
      const d = coursesRes.value.data.data;
      availableCourses.value = d?.courses || d?.data || (Array.isArray(d) ? d : []);
    }
    if (webinarsRes.status === 'fulfilled' && webinarsRes.value.data) {
      const d = webinarsRes.value.data.data;
      availableWebinars.value = d?.webinars || d?.data || (Array.isArray(d) ? d : []);
    }
    if (ebooksRes.status === 'fulfilled' && ebooksRes.value.data) {
      const d = ebooksRes.value.data.data;
      availableEbooks.value = d?.ebooks || d?.data || (Array.isArray(d) ? d : []);
    }
  } catch (e) {
    // Non-blocking
  }
}

onMounted(() => {
  fetchLeads();
  loadDropdowns();
  loadEmployeeOptions();
});
</script>
