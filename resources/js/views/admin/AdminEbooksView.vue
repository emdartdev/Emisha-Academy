<template>
  <div class="space-y-8">
    <!-- 1. Header & Action Strip -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">
            {{ themeStore.locale === 'bn' ? 'ই-বুক ও স্টাডি রিসোর্স ম্যানেজমেন্ট' : 'Ebooks & Resource Management' }}
          </h1>
          <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30">
            {{ pagination.total }} {{ themeStore.locale === 'bn' ? 'টি ই-বুক' : 'Ebooks' }}
          </span>
        </div>
        <p class="text-sm text-[var(--text-secondary)] mt-1">
          {{ themeStore.locale === 'bn' ? 'এভিয়েশন, জিডিএস ও ভিসা গাইডবুকের প্রতিটি তথ্য, হাইলাইটস, অধ্যায় ও অডিয়েন্স ব্যাকএন্ড থেকে পরিচালনা করুন।' : 'Full control over study handbooks, chapters, pricing, download metrics, and target personas.' }}
        </p>
      </div>

      <div class="flex flex-wrap items-center gap-3">
        <button
          type="button"
          @click="openCategoriesModal"
          class="px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all flex items-center gap-1.5 cursor-pointer"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'ক্যাটাগরি ম্যানেজমেন্ট' : 'Categories' }}</span>
        </button>

        <router-link
          to="/ebooks"
          target="_blank"
          class="px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all flex items-center gap-1.5"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'পাবলিক পেজ' : 'Public Ebooks' }}</span>
        </router-link>

        <button
          type="button"
          @click="openCreateModal"
          class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all inline-flex items-center gap-2 cursor-pointer touch-target shadow-md"
        >
          <span>+</span>
          <span>{{ themeStore.locale === 'bn' ? 'নতুন ই-বুক যুক্ত করুন' : 'Add New Ebook' }}</span>
        </button>
      </div>
    </div>

    <!-- 2. Search & Filter Strip -->
    <div class="flex flex-col lg:flex-row items-center justify-between gap-4 bg-[var(--bg-surface)] border border-[var(--border-subtle)] p-4 rounded-2xl shadow-xs">
      <div class="w-full lg:w-80">
        <input
          v-model="searchQuery"
          @input="debounceFetch"
          type="text"
          :placeholder="themeStore.locale === 'bn' ? 'শিরোনাম, সারসংক্ষেপ বা লেখক দিয়ে খুঁজুন...' : 'Search by title, summary or author...'"
          class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] transition-colors"
        />
      </div>

      <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
        <!-- Category Dropdown -->
        <select
          v-model="selectedCategoryFilter"
          @change="fetchEbooks(1)"
          class="px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
        >
          <option value="">{{ themeStore.locale === 'bn' ? 'সকল ক্যাটাগরি' : 'All Categories' }}</option>
          <option v-for="cat in categoriesList" :key="cat.id" :value="cat.id">
            {{ themeStore.locale === 'bn' ? (cat.name_bn || cat.name_en) : (cat.name_en || cat.name_bn) }}
          </option>
        </select>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
          <button
            v-for="st in statusOptions"
            :key="st.value"
            type="button"
            @click="statusFilter = st.value; fetchEbooks(1)"
            :class="[
              'px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer',
              statusFilter === st.value
                ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40 shadow-xs'
                : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-elevated)]'
            ]"
          >
            {{ st.label }}
          </button>
        </div>
      </div>
    </div>

    <!-- 3. Loading State -->
    <div v-if="loading" class="py-20 text-center space-y-3">
      <div class="inline-block animate-spin w-8 h-8 border-4 border-[#D4AF37] border-t-transparent rounded-full"></div>
      <p class="text-xs font-semibold text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'ই-বুক লোড হচ্ছে...' : 'Loading ebooks...' }}
      </p>
    </div>

    <!-- 4. Empty State -->
    <div v-else-if="ebooks.length === 0" class="py-16 text-center p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
      <div class="w-14 h-14 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto shadow-sm"><svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div>
      <h3 class="text-base font-bold text-[var(--text-primary)]">
        {{ themeStore.locale === 'bn' ? 'কোনো ই-বুক পাওয়া যায়নি' : 'No ebooks found' }}
      </h3>
      <p class="text-xs text-[var(--text-secondary)] max-w-md mx-auto">
        {{ themeStore.locale === 'bn' ? 'নতুন হ্যান্ডবুক যুক্ত করতে উপরের বাটনে ক্লিক করুন।' : 'Add your first study guide or adjust your search criteria.' }}
      </p>
      <button
        type="button"
        @click="openCreateModal"
        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs shadow-md cursor-pointer"
      >
        + {{ themeStore.locale === 'bn' ? 'ই-বুক তৈরি করুন' : 'Create Ebook' }}
      </button>
    </div>

    <!-- 5. Ebooks Cards Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="item in ebooks"
        :key="item.id"
        class="group flex flex-col justify-between rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden"
      >
        <!-- Card Top & Cover Image -->
        <div>
          <div class="relative h-48 bg-slate-950 overflow-hidden">
            <img
              v-if="item.cover_image"
              :src="item.cover_image"
              :alt="item.title_bn"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90"
            />
            <div v-else class="w-full h-full flex flex-col items-center justify-center gap-1.5 text-slate-500">
              <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/></svg>
              <span class="text-[11px] font-bold">{{ themeStore.locale === 'bn' ? 'কভার ইমেজ নেই' : 'No cover image' }}</span>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

            <!-- Top Badges -->
            <div class="absolute top-3 inset-x-3 flex items-center justify-between pointer-events-none">
              <span class="px-2.5 py-1 rounded-full bg-slate-900/90 backdrop-blur-md text-[10px] font-black text-[#D4AF37] border border-[#D4AF37]/30 shadow-xs">
                {{ item.category?.name_bn || item.category?.name_en || 'গাইডবুক' }}
              </span>
              <span
                :class="[
                  'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider backdrop-blur-md shadow-xs',
                  item.status === 'published' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' :
                  item.status === 'draft' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' :
                  'bg-slate-700/80 text-slate-300 border border-slate-600'
                ]"
              >
                {{ item.status }}
              </span>
            </div>

            <!-- Bottom Floating Specs -->
            <div class="absolute bottom-3 inset-x-3 flex items-center justify-between text-white text-[11px] font-bold">
              <span class="flex items-center gap-1 bg-black/60 backdrop-blur-md px-2 py-0.5 rounded-md">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>{{ item.pages_count }} {{ themeStore.locale === 'bn' ? 'পৃষ্ঠা' : 'Pages' }}</span>
              </span>
              <span class="flex items-center gap-1 bg-black/60 backdrop-blur-md px-2 py-0.5 rounded-md">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>{{ item.file_size }}</span>
              </span>
              <span class="flex items-center gap-1 bg-black/60 backdrop-blur-md px-2 py-0.5 rounded-md text-[#D4AF37]">
                <svg class="w-3 h-3 fill-current text-[#D4AF37]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span>{{ item.rating }} ({{ item.reviews_count || 0 }})</span>
              </span>
            </div>
          </div>

          <!-- Card Content Body -->
          <div class="p-5 space-y-3">
            <h3 class="text-sm sm:text-base font-bold text-[var(--text-primary)] line-clamp-2 leading-snug group-hover:text-[#D4AF37] transition-colors">
              {{ themeStore.locale === 'bn' ? item.title_bn : (item.title_en || item.title_bn) }}
            </h3>

            <p class="text-xs text-[var(--text-secondary)] line-clamp-2 leading-relaxed">
              {{ themeStore.locale === 'bn' ? (item.summary_bn || item.description_bn) : (item.summary_en || item.description_en || item.summary_bn) }}
            </p>

            <!-- Author & Byline Info -->
            <div class="flex items-center gap-2 pt-2 border-t border-[var(--border-subtle)] text-[11px]">
              <span class="w-6 h-6 rounded-full bg-[#D4AF37]/20 text-[#D4AF37] font-bold flex items-center justify-center text-xs">
                <svg class="w-3.5 h-3.5 text-[#D4AF37] inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
              </span>
              <div class="truncate">
                <span class="font-bold text-[var(--text-primary)] block truncate">
                  {{ themeStore.locale === 'bn' ? item.author_name_bn : (item.author_name_en || item.author_name_bn) }}
                </span>
                <span class="text-[10px] text-[var(--text-muted)] truncate block">
                  {{ themeStore.locale === 'bn' ? (item.author_designation_bn || 'এভিয়েশন ফ্যাকাল্টি ও ট্রাভেল অপারেশনস টিম') : (item.author_designation_en || 'Aviation Faculty Team') }}
                </span>
              </div>
            </div>

            <!-- Price & Downloads Stats -->
            <div class="flex items-center justify-between pt-2 border-t border-[var(--border-subtle)] text-xs">
              <div class="flex items-baseline gap-1.5">
                <span class="font-black text-[#D4AF37] text-sm sm:text-base">
                  {{ item.is_free ? (themeStore.locale === 'bn' ? '১০০% ফ্রি' : 'Free') : `৳${item.sale_price || item.regular_price}` }}
                </span>
                <span v-if="!item.is_free && item.regular_price > item.sale_price" class="text-[10px] text-[var(--text-muted)] line-through">
                  ৳{{ item.regular_price }}
                </span>
              </div>

              <div class="flex items-center gap-1 text-[var(--text-secondary)] font-semibold text-[11px]">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>{{ item.download_count || 0 }} {{ themeStore.locale === 'bn' ? 'ডাউনলোড' : 'Downloads' }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Card Footer Actions Strip -->
        <div class="p-4 bg-[var(--bg-elevated)] border-t border-[var(--border-subtle)] flex items-center justify-between gap-2">
          <div class="flex items-center gap-1.5">
            <router-link
              :to="`/ebooks/${item.slug}`"
              target="_blank"
              class="p-2 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--border-subtle)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer"
              title="পাবলিক পেজে দেখুন"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </router-link>

            <button
              type="button"
              @click="openDownloadsModal(item)"
              class="p-2 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--border-subtle)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer text-xs font-bold flex items-center gap-1"
              title="ডাউনলোড হিস্ট্রি"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              <span class="hidden sm:inline">{{ item.downloads_count || item.download_count || 0 }}</span>
            </button>
          </div>

          <div class="flex items-center gap-1.5">
            <button
              type="button"
              @click="openEditModal(item)"
              class="px-3 py-1.5 rounded-xl bg-[#D4AF37]/15 hover:bg-[#D4AF37]/30 text-[#D4AF37] font-bold text-xs border border-[#D4AF37]/30 transition-all cursor-pointer flex items-center gap-1"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'সম্পাদনা' : 'Edit' }}</span>
            </button>

            <button
              type="button"
              @click="confirmDelete(item)"
              class="p-2.5 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 transition-all cursor-pointer text-xs"
              title="মুছে ফেলুন"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- 6. Pagination -->
    <div v-if="pagination.last_page > 1" class="flex items-center justify-center gap-2 pt-4">
      <button
        v-for="p in pagination.last_page"
        :key="p"
        type="button"
        @click="fetchEbooks(p)"
        :class="[
          'w-9 h-9 rounded-xl text-xs font-bold transition-all cursor-pointer',
          pagination.current_page === p
            ? 'bg-[#D4AF37] text-slate-950 shadow-md font-black'
            : 'bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
        ]"
      >
        {{ p }}
      </button>
    </div>

    <!-- ========================================================================= -->
    <!-- 7. COMPLETE MULTI-TAB EBOOK EDIT / CREATE MODAL                           -->
    <!-- ========================================================================= -->
    <div
      v-if="showEditModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/80 backdrop-blur-sm overflow-y-auto"
    >
      <div class="relative w-full max-w-4xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl shadow-2xl overflow-hidden my-8 max-h-[90vh] flex flex-col">
        
        <!-- Modal Top Header -->
        <div class="px-6 py-4 border-b border-[var(--border-subtle)] bg-[var(--bg-elevated)] flex items-center justify-between shrink-0">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <h2 class="text-base sm:text-lg font-black text-[var(--text-primary)]">
              {{ editingEbookId ? (themeStore.locale === 'bn' ? 'ই-বুকের বিস্তারিত সম্পাদনা করুন' : 'Edit Ebook Details') : (themeStore.locale === 'bn' ? 'নতুন ই-বুক যুক্ত করুন' : 'Create New Ebook') }}
            </h2>
          </div>
          <button
            type="button"
            @click="showEditModal = false"
            class="p-2 rounded-xl text-[var(--text-muted)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-surface)] transition-all cursor-pointer"
          ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>

        <!-- Tab Navigation -->
        <div class="px-6 border-b border-[var(--border-subtle)] bg-[var(--bg-surface)] flex items-center gap-2 overflow-x-auto shrink-0">
          <button
            v-for="t in editorTabs"
            :key="t.id"
            type="button"
            @click="activeEditorTab = t.id"
            :class="[
              'py-3 px-4 text-xs font-bold border-b-2 whitespace-nowrap transition-all cursor-pointer flex items-center gap-1.5',
              activeEditorTab === t.id
                ? 'border-[#D4AF37] text-[#D4AF37]'
                : 'border-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
            ]"
          >
            <span>{{ t.icon }}</span>
            <span>{{ t.label }}</span>
          </button>
        </div>

        <!-- Modal Body Scrollable Area -->
        <form @submit.prevent="saveEbook" class="p-6 overflow-y-auto space-y-6 grow">
          
          <!-- TAB 1: BASIC & PRICING -->
          <div v-show="activeEditorTab === 'basic'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">শিরোনাম (বাংলা) *</label>
                <input
                  v-model="form.title_bn"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37] focus:outline-none"
                  placeholder="যেমন: এয়ার টিকেটিং ও GDS (Sabre & Galileo) প্র্যাকটিক্যাল হ্যান্ডবুক"
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">Title (English) *</label>
                <input
                  v-model="form.title_en"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37] focus:outline-none"
                  placeholder="e.g. Practical Air Ticketing & GDS (Sabre & Galileo) Handbook"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">ইউআরএল স্লাগ (Slug)</label>
                <input
                  v-model="form.slug"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37] focus:outline-none"
                  placeholder="practical-air-ticketing-and-gds-handbook"
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">ক্যাটাগরি</label>
                <select
                  v-model="form.category_id"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37] focus:outline-none"
                >
                  <option :value="null">ক্যাটাগরি নির্বাচন করুন</option>
                  <option v-for="c in categoriesList" :key="c.id" :value="c.id">
                    {{ c.name_bn }} ({{ c.name_en }})
                  </option>
                </select>
              </div>
            </div>

            <!-- Price & Free Status -->
            <div class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-3">
              <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-[var(--text-primary)] flex items-center gap-2">
                  <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" y1="6" x2="12" y2="8"/><line x1="12" y1="16" x2="12" y2="18"/></svg>
                  <span>মূল্য নির্ধারণ ও ফ্রি স্ট্যাটাস</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-emerald-400">
                  <input type="checkbox" v-model="form.is_free" class="rounded text-[#D4AF37]" />
                  <span>১০০% ফ্রি ডাউনলোড হিসেবে সেট করুন</span>
                </label>
              </div>

              <div v-if="!form.is_free" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div class="space-y-1">
                  <label class="text-[11px] text-[var(--text-muted)]">রেগুলার মূল্য (৳)</label>
                  <input
                    v-model.number="form.regular_price"
                    type="number"
                    step="0.01"
                    class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="500"
                  />
                </div>
                <div class="space-y-1">
                  <label class="text-[11px] text-[var(--text-muted)]">অফার / সেল মূল্য (৳)</label>
                  <input
                    v-model.number="form.sale_price"
                    type="number"
                    step="0.01"
                    class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="250"
                  />
                </div>
              </div>
            </div>

            <!-- Technical Specs (Pages, Size, Rating, Reviews, Download Count) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div class="space-y-1">
                <label class="text-[11px] font-bold text-[var(--text-secondary)]">পৃষ্ঠা সংখ্যা (Pages)</label>
                <input
                  v-model.number="form.pages_count"
                  type="number"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="95"
                />
              </div>

              <div class="space-y-1">
                <label class="text-[11px] font-bold text-[var(--text-secondary)]">ফাইল সাইজ (Size)</label>
                <input
                  v-model="form.file_size"
                  type="text"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="6.8 MB"
                />
              </div>

              <div class="space-y-1">
                <label class="text-[11px] font-bold text-[var(--text-secondary)]">গড় রেটিং (Rating)</label>
                <input
                  v-model.number="form.rating"
                  type="number"
                  step="0.01"
                  min="0"
                  max="5"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="4.95"
                />
              </div>

              <div class="space-y-1">
                <label class="text-[11px] font-bold text-[var(--text-secondary)]">রিভিউ সংখ্যা (Reviews)</label>
                <input
                  v-model.number="form.reviews_count"
                  type="number"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="84"
                />
              </div>
            </div>

            <div class="space-y-4 pt-1">
              <!-- Cover Image URL -->
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">কভার ইমেজ ইউআরএল (Cover Image URL)</label>
                <input
                  v-model="form.cover_image"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37]"
                  placeholder="https://images.unsplash.com/..."
                />
              </div>

              <!-- Full PDF & Preview PDF Link Boxes -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- 1. Full PDF File Path / Google Drive Link -->
                <div class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-2">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-[var(--text-primary)] flex items-center gap-1.5">
                      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                      <span>ফুল পিডিএফ ডাউনলোড পাথ / GDrive লিংক</span>
                    </label>
                    <span
                      v-if="form.file_path && (form.file_path.includes('drive.google.com') || form.file_path.startsWith('http'))"
                      class="px-2 py-0.5 rounded-full text-[10px] font-black bg-sky-500/20 text-sky-400 border border-sky-500/30"
                    >
                      Google Drive / URL
                    </span>
                    <span
                      v-else-if="form.file_path"
                      class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30"
                    >
                      লোকাল ফাইল পাথ
                    </span>
                  </div>

                  <input
                    v-model="form.file_path"
                    type="text"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37]"
                    placeholder="https://drive.google.com/file/d/... অথবা /downloads/visa-processing-checklist.pdf"
                  />
                  <p class="text-[11px] text-[var(--text-muted)] leading-tight">
                    গুগল ড্রাইভের শেয়ারেবল লিংক (Anyone with link) অথবা সার্ভার ফাইল পাথ (যেমন: <code>/downloads/visa-processing-checklist.pdf</code>) পেস্ট করতে পারেন।
                  </p>
                </div>

                <!-- 2. Sample Preview PDF Path / Google Drive Link -->
                <div class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-2">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-[var(--text-primary)] flex items-center gap-1.5">
                      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                      <span>ফ্রি প্রিভিউ পিডিএফ পাথ / GDrive লিংক</span>
                    </label>
                    <span
                      v-if="form.preview_pdf_path && (form.preview_pdf_path.includes('drive.google.com') || form.preview_pdf_path.startsWith('http'))"
                      class="px-2 py-0.5 rounded-full text-[10px] font-black bg-sky-500/20 text-sky-400 border border-sky-500/30"
                    >
                      Google Drive / URL
                    </span>
                    <span
                      v-else-if="form.preview_pdf_path"
                      class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30"
                    >
                      লোকাল স্যাম্পল পাথ
                    </span>
                  </div>

                  <input
                    v-model="form.preview_pdf_path"
                    type="text"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37]"
                    placeholder="https://drive.google.com/file/d/... অথবা /downloads/preview-visa.pdf"
                  />
                  <p class="text-[11px] text-[var(--text-muted)] leading-tight">
                    বিনামূল্যে রিড প্রিভিউ দেখার জন্য গুগল ড্রাইভ ভিউয়ার লিংক অথবা ডেমো পিডিএফ পাথ (যেমন: <code>/downloads/preview-visa.pdf</code>)।
                  </p>
                </div>

              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">স্ট্যাটাস (Status)</label>
                <select
                  v-model="form.status"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                >
                  <option value="published">Published</option>
                  <option value="draft">Draft</option>
                  <option value="archived">Archived</option>
                </select>
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">ম্যানুয়াল ডাউনলোড কাউন্ট</label>
                <input
                  v-model.number="form.download_count"
                  type="number"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="421"
                />
              </div>

              <div class="flex items-center gap-2 pt-6">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-[var(--text-primary)]">
                  <input type="checkbox" v-model="form.is_featured" class="rounded text-[#D4AF37]" />
                  <span>ফিচার্ড ই-বুক হিসেবে হাইলাইট করুন</span>
                </label>
              </div>
            </div>
          </div>

          <!-- TAB 2: AUTHOR & OVERVIEW -->
          <div v-show="activeEditorTab === 'author'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">লেখক / রিসার্চ টিম (বাংলা) *</label>
                <input
                  v-model="form.author_name_bn"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="ইমিশা একাডেমি রিসার্চ টিম"
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">Author / Research Team (English) *</label>
                <input
                  v-model="form.author_name_en"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="Emisha Academy Research Team"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">লেখকের পদবি / ডিপার্টমেন্ট (বাংলা)</label>
                <input
                  v-model="form.author_designation_bn"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="এভিয়েশন ফ্যাকাল্টি ও ট্রাভেল অপারেশনস টিম"
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">Author Designation (English)</label>
                <input
                  v-model="form.author_designation_en"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="Aviation Faculty & Travel Operations Team"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">লেখক অবতার ইমেজ URL</label>
                <input
                  v-model="form.author_avatar"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="https://images.unsplash.com/photo-..."
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">সংস্করণ ব্যাজ (বাংলা)</label>
                <input
                  v-model="form.edition_badge_bn"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="অফিশিয়াল পিডিএফ ই-বুক সংস্করণ (সর্বশেষ সংস্করণ)"
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">Edition Badge (English)</label>
                <input
                  v-model="form.edition_badge_en"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="Official High-Resolution PDF Handbook (Latest Revision)"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">সংক্ষিপ্ত সারসংক্ষেপ (বাংলা Summary)</label>
                <textarea
                  v-model="form.summary_bn"
                  rows="2"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37]"
                  placeholder="Sabre ও Galileo সিস্টেমের প্রতিটি প্রয়োজনীয় কমান্ড..."
                ></textarea>
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">Short Summary (English)</label>
                <textarea
                  v-model="form.summary_en"
                  rows="2"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37]"
                  placeholder="Comprehensive reference handbook outlining Sabre & Galileo commands..."
                ></textarea>
              </div>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-secondary)]">হ্যান্ডবুক পরিচিতি ও উদ্দেশ্য (বাংলা বিস্তারিত বর্ণনা)</label>
              <textarea
                v-model="form.description_bn"
                rows="4"
                class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37]"
                placeholder="এই হ্যান্ডবুকটিতে Sabre ও Galileo সিস্টেমের প্রতিটি প্রয়োজনীয় কমান্ড, PNR ক্রিয়েশন শর্টকাট..."
              ></textarea>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-secondary)]">Handbook Overview & Objectives (English Detailed Description)</label>
              <textarea
                v-model="form.description_en"
                rows="4"
                class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37]"
                placeholder="Detailed reference handbook outlining Sabre & Galileo commands, fare calculations, and visa documentation..."
              ></textarea>
            </div>
          </div>

          <!-- TAB 3: HIGHLIGHTS & CHAPTERS -->
          <div v-show="activeEditorTab === 'chapters'" class="space-y-6">
            
            <!-- Highlights Checklist ("এই হ্যান্ডবুকে আপনি যা যা শিখবেন") -->
            <div class="p-5 rounded-3xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                  <h3 class="text-xs sm:text-sm font-bold text-[var(--text-primary)] flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>হ্যান্ডবুক হাইলাইটস (Highlights Checklist)</span>
                  </h3>
                  <!-- Language Toggle for Highlights -->
                  <div class="flex items-center bg-[var(--bg-surface)] p-0.5 rounded-lg border border-[var(--border-subtle)] text-[10px]">
                    <button
                      type="button"
                      @click="highlightsLang = 'bn'"
                      :class="['px-2 py-0.5 rounded font-bold transition-all cursor-pointer', highlightsLang === 'bn' ? 'bg-[#D4AF37] text-slate-950' : 'text-[var(--text-muted)]']"
                    >
                      বাংলা ({{ form.highlights_bn?.length || 0 }})
                    </button>
                    <button
                      type="button"
                      @click="highlightsLang = 'en'"
                      :class="['px-2 py-0.5 rounded font-bold transition-all cursor-pointer', highlightsLang === 'en' ? 'bg-[#D4AF37] text-slate-950' : 'text-[var(--text-muted)]']"
                    >
                      English ({{ form.highlights_en?.length || 0 }})
                    </button>
                  </div>
                </div>

                <button
                  type="button"
                  @click="addHighlight(highlightsLang)"
                  class="px-3 py-1.5 rounded-xl bg-[#D4AF37]/15 hover:bg-[#D4AF37]/30 text-[#D4AF37] border border-[#D4AF37]/30 text-xs font-bold cursor-pointer"
                >
                  + {{ highlightsLang === 'bn' ? 'বাংলা পয়েন্ট যুক্ত করুন' : 'Add English Highlight' }}
                </button>
              </div>

              <!-- Bengali Highlights List -->
              <div v-show="highlightsLang === 'bn'" class="space-y-2.5">
                <div v-if="!form.highlights_bn || form.highlights_bn.length === 0" class="text-xs text-[var(--text-muted)] py-2 text-center">
                  কোনো বাংলা হাইলাইটস নেই। উপরের বাটনে ক্লিক করে যুক্ত করুন।
                </div>
                <div
                  v-for="(hl, hIdx) in form.highlights_bn"
                  :key="'hl-bn-' + hIdx"
                  class="flex items-center gap-2"
                >
                  <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  <input
                    v-model="form.highlights_bn[hIdx]"
                    type="text"
                    class="grow px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="পয়েন্ট লিখুন (যেমন: Sabre এবং Galileo সিস্টেমের ১০০+ প্রয়োজনীয় কমান্ড শর্টকাট)"
                  />
                  <button
                    type="button"
                    @click="removeHighlight('bn', hIdx)"
                    class="p-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs cursor-pointer"
                    title="মুছে ফেলুন"
                  ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                </div>
              </div>

              <!-- English Highlights List -->
              <div v-show="highlightsLang === 'en'" class="space-y-2.5">
                <div v-if="!form.highlights_en || form.highlights_en.length === 0" class="text-xs text-[var(--text-muted)] py-2 text-center">
                  No English highlights added yet. Click above to add.
                </div>
                <div
                  v-for="(hl, hIdx) in form.highlights_en"
                  :key="'hl-en-' + hIdx"
                  class="flex items-center gap-2"
                >
                  <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  <input
                    v-model="form.highlights_en[hIdx]"
                    type="text"
                    class="grow px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="Enter highlight in English..."
                  />
                  <button
                    type="button"
                    @click="removeHighlight('en', hIdx)"
                    class="p-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs cursor-pointer"
                    title="Remove"
                  ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                </div>
              </div>
            </div>

            <!-- Chapters / Table of Contents ("অধ্যায় ও বিস্তারিত সূচিপত্র") -->
            <div class="p-5 rounded-3xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                  <h3 class="text-xs sm:text-sm font-bold text-[var(--text-primary)] flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span>অধ্যায় ও বিস্তারিত সূচিপত্র (Table of Contents & Chapters)</span>
                  </h3>
                  <!-- Language Toggle for Chapters -->
                  <div class="flex items-center bg-[var(--bg-surface)] p-0.5 rounded-lg border border-[var(--border-subtle)] text-[10px]">
                    <button
                      type="button"
                      @click="chaptersLang = 'bn'"
                      :class="['px-2 py-0.5 rounded font-bold transition-all cursor-pointer', chaptersLang === 'bn' ? 'bg-[#D4AF37] text-slate-950' : 'text-[var(--text-muted)]']"
                    >
                      বাংলা ({{ form.chapters_bn?.length || 0 }})
                    </button>
                    <button
                      type="button"
                      @click="chaptersLang = 'en'"
                      :class="['px-2 py-0.5 rounded font-bold transition-all cursor-pointer', chaptersLang === 'en' ? 'bg-[#D4AF37] text-slate-950' : 'text-[var(--text-muted)]']"
                    >
                      English ({{ form.chapters_en?.length || 0 }})
                    </button>
                  </div>
                </div>

                <button
                  type="button"
                  @click="addChapter(chaptersLang)"
                  class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 text-xs font-black shadow-sm cursor-pointer"
                >
                  + {{ chaptersLang === 'bn' ? 'নতুন বাংলা অধ্যায়' : 'Add English Chapter' }}
                </button>
              </div>

              <!-- Bengali Chapters -->
              <div v-show="chaptersLang === 'bn'" class="space-y-4">
                <div v-if="!form.chapters_bn || form.chapters_bn.length === 0" class="text-xs text-[var(--text-muted)] py-4 text-center">
                  কোনো বাংলা অধ্যায় যুক্ত নেই।
                </div>
                <div
                  v-for="(ch, cIdx) in (form.chapters_bn || [])"
                  :key="'ch-bn-' + cIdx"
                  class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-3"
                >
                  <div class="flex items-center justify-between gap-2">
                    <span class="px-2.5 py-0.5 rounded-md bg-[#D4AF37]/20 text-[#D4AF37] text-xs font-black">
                      অধ্যায় {{ cIdx + 1 }}
                    </span>
                    <button
                      type="button"
                      @click="removeChapter('bn', cIdx)"
                      class="text-xs text-red-400 hover:text-red-300 font-bold cursor-pointer"
                    >
                      অধ্যায় মুছে ফেলুন
                    </button>
                  </div>

                  <div class="space-y-1.5">
                    <label class="text-[11px] font-bold text-[var(--text-secondary)]">অধ্যায়ের শিরোনাম (Title)</label>
                    <input
                      v-model="ch.title"
                      type="text"
                      class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                      placeholder="যেমন: অধ্যায় ০১: আন্তর্জাতিক এভিয়েশন কাঠামো ও এয়ারলাইন্স কোডস"
                    />
                  </div>

                  <div class="space-y-1.5">
                    <label class="text-[11px] font-bold text-[var(--text-secondary)]">সংক্ষিপ্ত সারসংক্ষেপ (Summary)</label>
                    <input
                      v-model="ch.summary"
                      type="text"
                      class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                      placeholder="যেমন: IATA ও ICAO পরিচিতি, বিশ্বের প্রধান এয়ারলাইন্স ও সিটি কোডস..."
                    />
                  </div>

                  <!-- Subtopics -->
                  <div class="space-y-2 pt-2 border-t border-[var(--border-subtle)]">
                    <div class="flex items-center justify-between">
                      <span class="text-[11px] font-bold text-[var(--text-muted)]">সাব-টপিকস / বুলেট পয়েন্টসমূহ:</span>
                      <button
                        type="button"
                        @click="addSubtopic('bn', cIdx)"
                        class="text-[11px] text-[#D4AF37] font-bold hover:underline cursor-pointer"
                      >
                        + সাব-টপিক যোগ করুন
                      </button>
                    </div>

                    <div
                      v-for="(sub, sIdx) in (ch.subtopics || [])"
                      :key="'sub-bn-' + cIdx + '-' + sIdx"
                      class="flex items-center gap-2"
                    >
                      <span class="text-xs text-[#D4AF37]">•</span>
                      <input
                        v-model="ch.subtopics[sIdx]"
                        type="text"
                        class="grow px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                        placeholder="টপিক লিখুন"
                      />
                      <button
                        type="button"
                        @click="removeSubtopic('bn', cIdx, sIdx)"
                        class="p-1 text-red-400 hover:text-red-300 text-xs cursor-pointer"
                      ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- English Chapters -->
              <div v-show="chaptersLang === 'en'" class="space-y-4">
                <div v-if="!form.chapters_en || form.chapters_en.length === 0" class="text-xs text-[var(--text-muted)] py-4 text-center">
                  No English chapters added yet. Click above to add.
                </div>
                <div
                  v-for="(ch, cIdx) in (form.chapters_en || [])"
                  :key="'ch-en-' + cIdx"
                  class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-3"
                >
                  <div class="flex items-center justify-between gap-2">
                    <span class="px-2.5 py-0.5 rounded-md bg-[#D4AF37]/20 text-[#D4AF37] text-xs font-black">
                      Chapter {{ cIdx + 1 }}
                    </span>
                    <button
                      type="button"
                      @click="removeChapter('en', cIdx)"
                      class="text-xs text-red-400 hover:text-red-300 font-bold cursor-pointer"
                    >
                      Remove Chapter
                    </button>
                  </div>

                  <div class="space-y-1.5">
                    <label class="text-[11px] font-bold text-[var(--text-secondary)]">Chapter Title (English)</label>
                    <input
                      v-model="ch.title"
                      type="text"
                      class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                      placeholder="e.g. Chapter 01: Global Aviation Framework & Airline Codes"
                    />
                  </div>

                  <div class="space-y-1.5">
                    <label class="text-[11px] font-bold text-[var(--text-secondary)]">Short Summary (English)</label>
                    <input
                      v-model="ch.summary"
                      type="text"
                      class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                      placeholder="e.g. Overview of IATA, ICAO and world travel geography..."
                    />
                  </div>

                  <!-- Subtopics -->
                  <div class="space-y-2 pt-2 border-t border-[var(--border-subtle)]">
                    <div class="flex items-center justify-between">
                      <span class="text-[11px] font-bold text-[var(--text-muted)]">Subtopics / Bullet Points:</span>
                      <button
                        type="button"
                        @click="addSubtopic('en', cIdx)"
                        class="text-[11px] text-[#D4AF37] font-bold hover:underline cursor-pointer"
                      >
                        + Add Subtopic
                      </button>
                    </div>

                    <div
                      v-for="(sub, sIdx) in (ch.subtopics || [])"
                      :key="'sub-en-' + cIdx + '-' + sIdx"
                      class="flex items-center gap-2"
                    >
                      <span class="text-xs text-[#D4AF37]">•</span>
                      <input
                        v-model="ch.subtopics[sIdx]"
                        type="text"
                        class="grow px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                        placeholder="Enter subtopic"
                      />
                      <button
                        type="button"
                        @click="removeSubtopic('en', cIdx, sIdx)"
                        class="p-1 text-red-400 hover:text-red-300 text-xs cursor-pointer"
                      ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <!-- TAB 4: AUDIENCE & LAB UPSELL -->
          <div v-show="activeEditorTab === 'audience'" class="space-y-6">
            <!-- Target Audience Personas ("যাদের জন্য এই গাইডবুকটি অপরিহার্য") -->
            <div class="p-5 rounded-3xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                  <h3 class="text-xs sm:text-sm font-bold text-[var(--text-primary)] flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                    <span>টার্গেট অডিয়েন্স / যাদের জন্য অপরিহার্য (Target Personas)</span>
                  </h3>
                  <!-- Language Toggle for Personas -->
                  <div class="flex items-center bg-[var(--bg-surface)] p-0.5 rounded-lg border border-[var(--border-subtle)] text-[10px]">
                    <button
                      type="button"
                      @click="audienceLang = 'bn'"
                      :class="['px-2 py-0.5 rounded font-bold transition-all cursor-pointer', audienceLang === 'bn' ? 'bg-[#D4AF37] text-slate-950' : 'text-[var(--text-muted)]']"
                    >
                      বাংলা ({{ form.target_audience_bn?.length || 0 }})
                    </button>
                    <button
                      type="button"
                      @click="audienceLang = 'en'"
                      :class="['px-2 py-0.5 rounded font-bold transition-all cursor-pointer', audienceLang === 'en' ? 'bg-[#D4AF37] text-slate-950' : 'text-[var(--text-muted)]']"
                    >
                      English ({{ form.target_audience_en?.length || 0 }})
                    </button>
                  </div>
                </div>

                <button
                  type="button"
                  @click="addPersona(audienceLang)"
                  class="px-3 py-1 rounded-xl bg-[#D4AF37]/15 hover:bg-[#D4AF37]/30 text-[#D4AF37] border border-[#D4AF37]/30 text-xs font-bold cursor-pointer"
                >
                  + {{ audienceLang === 'bn' ? 'পারসোনা যুক্ত করুন' : 'Add Persona' }}
                </button>
              </div>

              <!-- Bengali Personas Grid -->
              <div v-show="audienceLang === 'bn'" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div v-if="!form.target_audience_bn || form.target_audience_bn.length === 0" class="col-span-full text-xs text-[var(--text-muted)] py-3 text-center">
                  কোনো বাংলা অডিয়েন্স যুক্ত নেই।
                </div>
                <div
                  v-for="(persona, pIdx) in (form.target_audience_bn || [])"
                  :key="'p-bn-' + pIdx"
                  class="p-3.5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-2 relative"
                >
                  <button
                    type="button"
                    @click="removePersona('bn', pIdx)"
                    class="absolute top-2 right-2 text-xs text-red-400 hover:text-red-300 cursor-pointer"
                    title="মুছে ফেলুন"
                  ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>

                  <div class="flex items-center gap-2">
                    <input
                      v-model="persona.icon"
                      type="text"
                      class="w-10 px-2 py-1 text-center rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs"
                      placeholder="Icon"
                    />
                    <input
                      v-model="persona.title"
                      type="text"
                      class="grow px-2 py-1 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)]"
                      placeholder="টিকেটিং এক্সিকিউটিভ"
                    />
                  </div>

                  <textarea
                    v-model="persona.desc"
                    rows="2"
                    class="w-full px-2 py-1 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[11px] text-[var(--text-secondary)]"
                    placeholder="এয়ারলাইন্স বা ট্রাভেল এজেন্সিতে কর্মরত বা চাকরিপ্রার্থী।"
                  ></textarea>
                </div>
              </div>

              <!-- English Personas Grid -->
              <div v-show="audienceLang === 'en'" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div v-if="!form.target_audience_en || form.target_audience_en.length === 0" class="col-span-full text-xs text-[var(--text-muted)] py-3 text-center">
                  No English personas added yet. Click above to add.
                </div>
                <div
                  v-for="(persona, pIdx) in (form.target_audience_en || [])"
                  :key="'p-en-' + pIdx"
                  class="p-3.5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-2 relative"
                >
                  <button
                    type="button"
                    @click="removePersona('en', pIdx)"
                    class="absolute top-2 right-2 text-xs text-red-400 hover:text-red-300 cursor-pointer"
                    title="Remove"
                  ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>

                  <div class="flex items-center gap-2">
                    <input
                      v-model="persona.icon"
                      type="text"
                      class="w-10 px-2 py-1 text-center rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs"
                      placeholder="Icon"
                    />
                    <input
                      v-model="persona.title"
                      type="text"
                      class="grow px-2 py-1 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)]"
                      placeholder="Ticketing Officers"
                    />
                  </div>

                  <textarea
                    v-model="persona.desc"
                    rows="2"
                    class="w-full px-2 py-1 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[11px] text-[var(--text-secondary)]"
                    placeholder="Working in travel agencies or aspiring airline staff."
                  ></textarea>
                </div>
              </div>
            </div>

            <!-- Mirpur Lab Upsell Banner ("হাতে-কলমে প্র্যাকটিক্যাল ল্যাব সুবিধা") -->
            <div class="p-5 rounded-3xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-4">
              <h3 class="text-xs sm:text-sm font-bold text-[var(--text-primary)] flex items-center gap-2">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                <span>মিরপুর প্র্যাকটিক্যাল ল্যাব আপসেল কার্ড (Practical Lab Callout)</span>
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                  <label class="text-[11px] font-bold text-[var(--text-secondary)]">ল্যাব কার্ডের শিরোনাম (বাংলা)</label>
                  <input
                    v-model="form.lab_upsell_title_bn"
                    type="text"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="শুধু বই পড়ে নয়, কম্পিউটারে সরাসরি লাইভ সফটওয়্যার শিখুন!"
                  />
                </div>

                <div class="space-y-1.5">
                  <label class="text-[11px] font-bold text-[var(--text-secondary)]">Lab Upsell Title (English)</label>
                  <input
                    v-model="form.lab_upsell_title_en"
                    type="text"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="Move Beyond Theory: Learn Live GDS on Dedicated Workstations"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                  <label class="text-[11px] font-bold text-[var(--text-secondary)]">ল্যাব কার্ডের বিস্তারিত বার্তা (বাংলা)</label>
                  <textarea
                    v-model="form.lab_upsell_desc_bn"
                    rows="2"
                    class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="ইমিশা একাডেমির মিরপুর ক্যাম্পাসে প্রতিটি শিক্ষার্থীর জন্য রয়েছে আলাদা কম্পিউটার..."
                  ></textarea>
                </div>

                <div class="space-y-1.5">
                  <label class="text-[11px] font-bold text-[var(--text-secondary)]">Lab Upsell Description (English)</label>
                  <textarea
                    v-model="form.lab_upsell_desc_en"
                    rows="2"
                    class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="Join our physical classroom batches at Mirpur Kazipara..."
                  ></textarea>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="space-y-1">
                  <label class="text-[11px] font-bold text-[var(--text-secondary)]">বাটন টেক্সট (বাংলা)</label>
                  <input
                    v-model="form.lab_upsell_btn_text_bn"
                    type="text"
                    class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
                    placeholder="প্র্যাকটিক্যাল কোর্সসমূহ দেখুন →"
                  />
                </div>

                <div class="space-y-1">
                  <label class="text-[11px] font-bold text-[var(--text-secondary)]">Button Text (English)</label>
                  <input
                    v-model="form.lab_upsell_btn_text_en"
                    type="text"
                    class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
                    placeholder="Explore Flagship Courses →"
                  />
                </div>

                <div class="space-y-1">
                  <label class="text-[11px] font-bold text-[var(--text-secondary)]">বাটন লিংক (Target Link)</label>
                  <input
                    v-model="form.lab_upsell_btn_link"
                    type="text"
                    class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
                    placeholder="/courses"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Action Bottom Bar -->
          <div class="pt-4 border-t border-[var(--border-subtle)] flex items-center justify-end gap-3 shrink-0">
            <button
              type="button"
              @click="showEditModal = false"
              class="px-5 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer"
            >
              {{ themeStore.locale === 'bn' ? 'বাতিল' : 'Cancel' }}
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-7 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg transition-all cursor-pointer flex items-center gap-2 shadow-md"
            >
              <span v-if="saving" class="inline-block animate-spin w-3.5 h-3.5 border-2 border-slate-950 border-t-transparent rounded-full"></span>
              <span>{{ saving ? (themeStore.locale === 'bn' ? 'সংরক্ষণ হচ্ছে...' : 'Saving...') : (editingEbookId ? (themeStore.locale === 'bn' ? 'আপডেট সম্পন্ন করুন' : 'Update Ebook') : (themeStore.locale === 'bn' ? 'ই-বুক তৈরি করুন' : 'Create Ebook')) }}</span>
            </button>
          </div>

        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 8. CATEGORY MANAGEMENT MODAL                                              -->
    <!-- ========================================================================= -->
    <div
      v-if="showCategoriesModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
    >
      <div class="w-full max-w-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl shadow-2xl overflow-hidden space-y-5 p-6">
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
          <h3 class="text-base font-bold text-[var(--text-primary)] flex items-center gap-2">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'ই-বুক ক্যাটাগরি ম্যানেজমেন্ট' : 'Ebook Category Management' }}</span>
          </h3>
          <button type="button" @click="showCategoriesModal = false" class="text-sm text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>

        <!-- Add Category Inline Form -->
        <form @submit.prevent="saveCategory" class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-3">
          <div class="grid grid-cols-2 gap-3">
            <input
              v-model="catForm.name_bn"
              type="text"
              required
              :placeholder="themeStore.locale === 'bn' ? 'ক্যাটাগরি নাম (বাংলা)' : 'Category Name (Bangla)'"
              class="px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
            />
            <input
              v-model="catForm.name_en"
              type="text"
              required
              :placeholder="themeStore.locale === 'bn' ? 'Category Name (English)' : 'Category Name (English)'"
              class="px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
            />
          </div>
          <div class="flex justify-end gap-2">
            <button
              v-if="editingCatId"
              type="button"
              @click="resetCatForm"
              class="px-3 py-1.5 rounded-lg text-xs text-[var(--text-secondary)] cursor-pointer"
            >
              {{ themeStore.locale === 'bn' ? 'বাতিল' : 'Cancel' }}
            </button>
            <button
              type="submit"
              class="px-4 py-1.5 rounded-xl bg-[#D4AF37] text-slate-950 font-bold text-xs shadow-xs cursor-pointer"
            >
              {{ editingCatId ? (themeStore.locale === 'bn' ? 'আপডেট করুন' : 'Update') : (themeStore.locale === 'bn' ? '+ ক্যাটাগরি যোগ করুন' : '+ Add Category') }}
            </button>
          </div>
        </form>

        <!-- Category List -->
        <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
          <div
            v-for="cat in categoriesList"
            :key="cat.id"
            class="p-3 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] flex items-center justify-between gap-3 text-xs"
          >
            <div>
              <span class="font-bold text-[var(--text-primary)] block">{{ cat.name_bn }}</span>
              <span class="text-[11px] text-[var(--text-muted)]">{{ cat.name_en }} ({{ cat.ebooks_count || 0 }} {{ themeStore.locale === 'bn' ? 'টি ই-বুক' : 'Ebooks' }})</span>
            </div>
            <div class="flex items-center gap-1.5">
              <button
                type="button"
                @click="editCategory(cat)"
                class="px-2 py-1 rounded bg-[#D4AF37]/15 text-[#D4AF37] text-xs font-bold cursor-pointer"
              >
                {{ themeStore.locale === 'bn' ? 'এডিট' : 'Edit' }}
              </button>
              <button
                type="button"
                @click="deleteCategory(cat.id)"
                class="p-1 text-red-400 hover:text-red-300 text-xs cursor-pointer"
                title="মুছে ফেলুন"
              ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 9. DOWNLOADS LOG MODAL                                                    -->
    <!-- ========================================================================= -->
    <div
      v-if="showDownloadsModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
    >
      <div class="w-full max-w-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl shadow-2xl overflow-hidden space-y-4 p-6 flex flex-col max-h-[85vh]">
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
          <div>
            <h3 class="text-base font-bold text-[var(--text-primary)] flex items-center gap-2">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'ডাউনলোড লগ ও অডিট হিস্ট্রি' : 'Download Log & Audit History' }}</span>
            </h3>
            <p class="text-xs text-[var(--text-muted)]">{{ selectedEbookForDownloads?.title_bn }}</p>
          </div>
          <button type="button" @click="showDownloadsModal = false" class="text-sm text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>

        <div v-if="loadingDownloads" class="py-12 text-center">
          <div class="inline-block animate-spin w-6 h-6 border-2 border-[#D4AF37] border-t-transparent rounded-full"></div>
        </div>

        <div v-else-if="downloadsList.length === 0" class="py-12 text-center text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'এখনো কোনো ডাউনলোড হিস্ট্রি রেকর্ড নেই।' : 'No download history records found yet.' }}
        </div>

        <div v-else class="overflow-y-auto space-y-2 grow pr-1">
          <table class="w-full text-left text-xs">
            <thead class="text-[var(--text-muted)] border-b border-[var(--border-subtle)]">
              <tr>
                <th class="py-2">{{ themeStore.locale === 'bn' ? 'আইপি অ্যাড্রেস' : 'IP Address' }}</th>
                <th class="py-2">{{ themeStore.locale === 'bn' ? 'ইউজার এজেন্ট / ডিভাইস' : 'User Agent / Device' }}</th>
                <th class="py-2 text-right">{{ themeStore.locale === 'bn' ? 'ডাউনলোড সময়' : 'Download Time' }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border-subtle)] text-[var(--text-secondary)]">
              <tr v-for="d in downloadsList" :key="d.id">
                <td class="py-2.5 font-mono text-xs">{{ d.ip_address || '—' }}</td>
                <td class="py-2.5 truncate max-w-xs text-[11px]">{{ d.user_agent || '—' }}</td>
                <td class="py-2.5 text-right font-semibold text-[11px] text-[var(--text-primary)]">
                  {{ d.downloaded_at ? new Date(d.downloaded_at).toLocaleString() : '—' }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';

interface ChapterItem {
  chapter_num: number;
  title: string;
  summary: string;
  subtopics: string[];
}

interface TargetPersona {
  icon: string;
  title: string;
  desc: string;
}

interface EbookForm {
  title_bn: string;
  title_en: string;
  slug: string;
  category_id: number | null;
  author_name_bn: string;
  author_name_en: string;
  author_designation_bn: string;
  author_designation_en: string;
  author_avatar: string;
  edition_badge_bn: string;
  edition_badge_en: string;
  summary_bn: string;
  summary_en: string;
  description_bn: string;
  description_en: string;
  cover_image: string;
  preview_pdf_path: string;
  file_path: string;
  pages_count: number;
  file_size: string;
  regular_price: number;
  sale_price: number | null;
  is_free: boolean;
  download_count: number;
  rating: number;
  reviews_count: number;
  is_featured: boolean;
  status: string;
  highlights_bn: string[];
  highlights_en: string[];
  chapters_bn: ChapterItem[];
  chapters_en: ChapterItem[];
  target_audience_bn: TargetPersona[];
  target_audience_en: TargetPersona[];
  lab_upsell_title_bn: string;
  lab_upsell_title_en: string;
  lab_upsell_desc_bn: string;
  lab_upsell_desc_en: string;
  lab_upsell_btn_text_bn: string;
  lab_upsell_btn_text_en: string;
  lab_upsell_btn_link: string;
}

const themeStore = useThemeStore();
const toastStore = useToastStore();

const loading = ref(true);
const saving = ref(false);
const ebooks = ref<any[]>([]);
const categoriesList = ref<any[]>([]);
const searchQuery = ref('');
const selectedCategoryFilter = ref('');
const statusFilter = ref('');

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  total: 0,
});

const statusOptions = computed(() => [
  { value: '', label: themeStore.locale === 'bn' ? 'সকল' : 'All' },
  { value: 'published', label: themeStore.locale === 'bn' ? 'পাবলিশড' : 'Published' },
  { value: 'draft', label: themeStore.locale === 'bn' ? 'ড্রাফট' : 'Draft' },
  { value: 'archived', label: themeStore.locale === 'bn' ? 'আর্কাইভ' : 'Archived' },
]);

const editorTabs = computed(() => [
  { id: 'basic', label: themeStore.locale === 'bn' ? 'বেসিক ও প্রাইসিং' : 'Basic & Pricing', icon: '' },
  { id: 'author', label: themeStore.locale === 'bn' ? 'লেখক ও পরিচিতি' : 'Author & Overview', icon: '' },
  { id: 'chapters', label: themeStore.locale === 'bn' ? 'হাইলাইটস ও সূচিপত্র' : 'Highlights & Chapters', icon: '' },
  { id: 'audience', label: themeStore.locale === 'bn' ? 'টার্গেট অডিয়েন্স ও ল্যাব' : 'Target Audience & Lab', icon: '' },
]);

const activeEditorTab = ref('basic');
const highlightsLang = ref<'bn' | 'en'>('bn');
const chaptersLang = ref<'bn' | 'en'>('bn');
const audienceLang = ref<'bn' | 'en'>('bn');
const showEditModal = ref(false);
const editingEbookId = ref<number | null>(null);

const getDefaultForm = (): EbookForm => ({
  title_bn: '',
  title_en: '',
  slug: '',
  category_id: categoriesList.value[0]?.id || null,
  author_name_bn: 'ইমিশা একাডেমি রিসার্চ টিম',
  author_name_en: 'Emisha Academy Research Team',
  author_designation_bn: 'এভিয়েশন ফ্যাকাল্টি ও ট্রাভেল অপারেশনস টিম',
  author_designation_en: 'Aviation Faculty & Travel Operations Team',
  author_avatar: '',
  edition_badge_bn: 'অফিশিয়াল পিডিএফ ই-বুক সংস্করণ (সর্বশেষ সংস্করণ)',
  edition_badge_en: 'Official High-Resolution PDF Handbook (Latest Revision)',
  summary_bn: '',
  summary_en: '',
  description_bn: '',
  description_en: '',
  cover_image: '',
  preview_pdf_path: '',
  file_path: '',
  pages_count: 0,
  file_size: '',
  regular_price: 0,
  sale_price: null,
  is_free: false,
  download_count: 0,
  rating: 5,
  reviews_count: 0,
  is_featured: false,
  status: 'published',
  highlights_bn: [],
  highlights_en: [],
  chapters_bn: [],
  chapters_en: [],
  target_audience_bn: [],
  target_audience_en: [],
  lab_upsell_title_bn: 'শুধু বই পড়ে নয়, কম্পিউটারে সরাসরি লাইভ সফটওয়্যার শিখুন!',
  lab_upsell_title_en: 'Move Beyond Theory: Learn Live GDS on Dedicated Workstations',
  lab_upsell_desc_bn: 'ইমিশা একাডেমির মিরপুর ক্যাম্পাসে প্রতিটি শিক্ষার্থীর জন্য রয়েছে আলাদা কম্পিউটার এবং Sabre ও Galileo সিস্টেমের লাইভ সফটওয়্যার অ্যাক্সেস।',
  lab_upsell_desc_en: 'Join our physical classroom batches at Mirpur Kazipara. 1 Student = 1 Workstation with unlimited lab practice guarantee.',
  lab_upsell_btn_text_bn: 'প্র্যাকটিক্যাল কোর্সসমূহ দেখুন →',
  lab_upsell_btn_text_en: 'Explore Flagship Courses →',
  lab_upsell_btn_link: '/courses',
});

// Main Ebook Form
const form = reactive<EbookForm>(getDefaultForm());

// Category Modal state
const showCategoriesModal = ref(false);
const editingCatId = ref<number | null>(null);
const catForm = reactive({
  name_bn: '',
  name_en: '',
});

// Downloads Modal state
const showDownloadsModal = ref(false);
const loadingDownloads = ref(false);
const selectedEbookForDownloads = ref<any>(null);
const downloadsList = ref<any[]>([]);

let debounceTimer: ReturnType<typeof setTimeout> | null = null;
const debounceFetch = () => {
  if (debounceTimer) clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchEbooks(1);
  }, 350);
};

const safeArray = (val: any): any[] => {
  if (!val) return [];
  if (Array.isArray(val)) return JSON.parse(JSON.stringify(val));
  if (typeof val === 'string') {
    try {
      const parsed = JSON.parse(val);
      return Array.isArray(parsed) ? parsed : [];
    } catch {
      return [];
    }
  }
  return [];
};

const safeChapters = (val: any): ChapterItem[] => {
  const arr = safeArray(val);
  return arr.map((c: any, i: number) => ({
    chapter_num: Number(c?.chapter_num) || i + 1,
    title: String(c?.title || ''),
    summary: String(c?.summary || ''),
    subtopics: Array.isArray(c?.subtopics)
      ? c.subtopics.map((s: any) => String(s || ''))
      : (typeof c?.subtopics === 'string' ? [c.subtopics] : []),
  }));
};

const fetchCategories = async () => {
  try {
    const res = await apiClient.get('/admin/ebooks/categories');
    if (res.data?.data?.categories) {
      categoriesList.value = res.data.data.categories;
    }
  } catch (err) {
    console.error('Failed to load ebook categories', err);
  }
};

const fetchEbooks = async (page = 1) => {
  loading.value = true;
  try {
    const params: any = { page };
    if (searchQuery.value) params.search = searchQuery.value;
    if (selectedCategoryFilter.value) params.category_id = selectedCategoryFilter.value;
    if (statusFilter.value) params.status = statusFilter.value;

    const res = await apiClient.get('/admin/ebooks', { params });
    if (res.data?.data) {
      ebooks.value = res.data.data.ebooks || [];
      if (res.data.data.pagination) {
        pagination.current_page = res.data.data.pagination.current_page;
        pagination.last_page = res.data.data.pagination.last_page;
        pagination.total = res.data.data.pagination.total;
      }
    }
  } catch (err) {
    toastStore.error(themeStore.locale === 'bn' ? 'ই-বুক তালিকা লোড করা যায়নি' : 'Failed to fetch ebooks');
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  editingEbookId.value = null;
  activeEditorTab.value = 'basic';
  highlightsLang.value = 'bn';
  chaptersLang.value = 'bn';
  audienceLang.value = 'bn';
  Object.assign(form, getDefaultForm());
  showEditModal.value = true;
};

const openEditModal = (item: any) => {
  editingEbookId.value = item.id;
  activeEditorTab.value = 'basic';
  highlightsLang.value = 'bn';
  chaptersLang.value = 'bn';
  audienceLang.value = 'bn';

  const defaultValues = getDefaultForm();
  Object.assign(form, defaultValues, {
    title_bn: item.title_bn || '',
    title_en: item.title_en || '',
    slug: item.slug || '',
    category_id: item.category_id ? Number(item.category_id) : (categoriesList.value[0]?.id || null),
    author_name_bn: item.author_name_bn || 'ইমিশা একাডেমি রিসার্চ টিম',
    author_name_en: item.author_name_en || 'Emisha Academy Research Team',
    author_designation_bn: item.author_designation_bn || 'এভিয়েশন ফ্যাকাল্টি ও ট্রাভেল অপারেশনস টিম',
    author_designation_en: item.author_designation_en || 'Aviation Faculty & Travel Operations Team',
    author_avatar: item.author_avatar || '',
    edition_badge_bn: item.edition_badge_bn || 'অফিশিয়াল পিডিএফ ই-বুক সংস্করণ (সর্বশেষ সংস্করণ)',
    edition_badge_en: item.edition_badge_en || 'Official High-Resolution PDF Handbook (Latest Revision)',
    summary_bn: item.summary_bn || '',
    summary_en: item.summary_en || '',
    description_bn: item.description_bn || '',
    description_en: item.description_en || '',
    cover_image: item.cover_image || '',
    preview_pdf_path: item.preview_pdf_path || '',
    file_path: item.file_path || '',
    pages_count: Number(item.pages_count) || 0,
    file_size: item.file_size || '',
    regular_price: Number(item.regular_price) || 0,
    sale_price: item.sale_price !== null && item.sale_price !== undefined ? Number(item.sale_price) : null,
    is_free: Boolean(item.is_free),
    download_count: Number(item.download_count) || 0,
    rating: Number(item.rating) || 5.0,
    reviews_count: Number(item.reviews_count) || 0,
    is_featured: Boolean(item.is_featured),
    status: item.status || 'published',
    highlights_bn: safeArray(item.highlights_bn),
    highlights_en: safeArray(item.highlights_en),
    chapters_bn: safeChapters(item.chapters_bn),
    chapters_en: safeChapters(item.chapters_en),
    target_audience_bn: safeArray(item.target_audience_bn),
    target_audience_en: safeArray(item.target_audience_en),
    lab_upsell_title_bn: item.lab_upsell_title_bn || 'শুধু বই পড়ে নয়, কম্পিউটারে সরাসরি লাইভ সফটওয়্যার শিখুন!',
    lab_upsell_title_en: item.lab_upsell_title_en || 'Move Beyond Theory: Learn Live GDS on Dedicated Workstations',
    lab_upsell_desc_bn: item.lab_upsell_desc_bn || 'ইমিশা একাডেমির মিরপুর ক্যাম্পাসে প্রতিটি শিক্ষার্থীর জন্য রয়েছে আলাদা কম্পিউটার এবং Sabre ও Galileo সিস্টেমের লাইভ সফটওয়্যার অ্যাক্সেস।',
    lab_upsell_desc_en: item.lab_upsell_desc_en || 'Join our physical classroom batches at Mirpur Kazipara. 1 Student = 1 Workstation with unlimited lab practice guarantee.',
    lab_upsell_btn_text_bn: item.lab_upsell_btn_text_bn || 'প্র্যাকটিক্যাল কোর্সসমূহ দেখুন →',
    lab_upsell_btn_text_en: item.lab_upsell_btn_text_en || 'Explore Flagship Courses →',
    lab_upsell_btn_link: item.lab_upsell_btn_link || '/courses',
  });

  showEditModal.value = true;
};

// Multilingual Highlights helpers
const addHighlight = (lang: 'bn' | 'en') => {
  if (lang === 'bn') {
    if (!form.highlights_bn) form.highlights_bn = [];
    form.highlights_bn.push('');
  } else {
    if (!form.highlights_en) form.highlights_en = [];
    form.highlights_en.push('');
  }
};
const removeHighlight = (lang: 'bn' | 'en', idx: number) => {
  if (lang === 'bn') {
    if (form.highlights_bn) form.highlights_bn.splice(idx, 1);
  } else {
    if (form.highlights_en) form.highlights_en.splice(idx, 1);
  }
};

// Multilingual Chapters helpers
const addChapter = (lang: 'bn' | 'en') => {
  if (lang === 'bn') {
    if (!form.chapters_bn) form.chapters_bn = [];
    form.chapters_bn.push({
      chapter_num: form.chapters_bn.length + 1,
      title: `অধ্যায় ০${form.chapters_bn.length + 1}: নতুন অধ্যায়`,
      summary: '',
      subtopics: ['টপিক ১', 'টপিক ২'],
    });
  } else {
    if (!form.chapters_en) form.chapters_en = [];
    form.chapters_en.push({
      chapter_num: form.chapters_en.length + 1,
      title: `Chapter 0${form.chapters_en.length + 1}: New Chapter Title`,
      summary: '',
      subtopics: ['Topic 1', 'Topic 2'],
    });
  }
};
const removeChapter = (lang: 'bn' | 'en', idx: number) => {
  if (lang === 'bn') {
    if (form.chapters_bn) form.chapters_bn.splice(idx, 1);
  } else {
    if (form.chapters_en) form.chapters_en.splice(idx, 1);
  }
};
const addSubtopic = (lang: 'bn' | 'en', cIdx: number) => {
  if (lang === 'bn') {
    if (!form.chapters_bn[cIdx]) return;
    if (!form.chapters_bn[cIdx].subtopics) form.chapters_bn[cIdx].subtopics = [];
    form.chapters_bn[cIdx].subtopics.push('');
  } else {
    if (!form.chapters_en[cIdx]) return;
    if (!form.chapters_en[cIdx].subtopics) form.chapters_en[cIdx].subtopics = [];
    form.chapters_en[cIdx].subtopics.push('');
  }
};
const removeSubtopic = (lang: 'bn' | 'en', cIdx: number, sIdx: number) => {
  if (lang === 'bn') {
    if (form.chapters_bn[cIdx]?.subtopics) {
      form.chapters_bn[cIdx].subtopics.splice(sIdx, 1);
    }
  } else {
    if (form.chapters_en[cIdx]?.subtopics) {
      form.chapters_en[cIdx].subtopics.splice(sIdx, 1);
    }
  }
};

// Multilingual Target Persona helpers
const addPersona = (lang: 'bn' | 'en') => {
  if (lang === 'bn') {
    if (!form.target_audience_bn) form.target_audience_bn = [];
    form.target_audience_bn.push({
      icon: '',
      title: 'নতুন অডিয়েন্স গ্রুপ',
      desc: 'বিস্তারিত বিবরণ লিখুন।',
    });
  } else {
    if (!form.target_audience_en) form.target_audience_en = [];
    form.target_audience_en.push({
      icon: '',
      title: 'Target Audience Group',
      desc: 'Enter detailed description.',
    });
  }
};
const removePersona = (lang: 'bn' | 'en', idx: number) => {
  if (lang === 'bn') {
    if (form.target_audience_bn) form.target_audience_bn.splice(idx, 1);
  } else {
    if (form.target_audience_en) form.target_audience_en.splice(idx, 1);
  }
};

const saveEbook = async () => {
  saving.value = true;
  try {
    const payload = {
      title_bn: form.title_bn?.trim() || '',
      title_en: form.title_en?.trim() || '',
      slug: form.slug?.trim() || undefined,
      category_id: form.category_id ? Number(form.category_id) : null,
      author_name_bn: form.author_name_bn?.trim() || '',
      author_name_en: form.author_name_en?.trim() || '',
      author_designation_bn: form.author_designation_bn?.trim() || null,
      author_designation_en: form.author_designation_en?.trim() || null,
      author_avatar: form.author_avatar?.trim() || null,
      edition_badge_bn: form.edition_badge_bn?.trim() || null,
      edition_badge_en: form.edition_badge_en?.trim() || null,
      summary_bn: form.summary_bn?.trim() || null,
      summary_en: form.summary_en?.trim() || null,
      description_bn: form.description_bn?.trim() || null,
      description_en: form.description_en?.trim() || null,
      cover_image: form.cover_image?.trim() || null,
      preview_pdf_path: form.preview_pdf_path?.trim() || null,
      file_path: form.file_path?.trim() || null,
      pages_count: Number(form.pages_count) || null,
      file_size: form.file_size?.trim() || null,
      regular_price: Number(form.regular_price) || 0,
      sale_price: !form.is_free && form.sale_price !== null && form.sale_price !== undefined ? Number(form.sale_price) : null,
      is_free: Boolean(form.is_free),
      download_count: Number(form.download_count) || 0,
      rating: Number(form.rating) || 5.0,
      reviews_count: Number(form.reviews_count) || 0,
      is_featured: Boolean(form.is_featured),
      status: form.status || 'published',
      highlights_bn: Array.isArray(form.highlights_bn) ? form.highlights_bn.filter((h: string) => h && h.trim() !== '') : [],
      highlights_en: Array.isArray(form.highlights_en) ? form.highlights_en.filter((h: string) => h && h.trim() !== '') : [],
      chapters_bn: Array.isArray(form.chapters_bn)
        ? form.chapters_bn
            .filter((c: ChapterItem) => c && c.title && c.title.trim() !== '')
            .map((c: ChapterItem, idx: number) => ({
              chapter_num: idx + 1,
              title: c.title.trim(),
              summary: c.summary?.trim() || '',
              subtopics: Array.isArray(c.subtopics) ? c.subtopics.filter((s: string) => s && s.trim() !== '') : [],
            }))
        : [],
      chapters_en: Array.isArray(form.chapters_en)
        ? form.chapters_en
            .filter((c: ChapterItem) => c && c.title && c.title.trim() !== '')
            .map((c: ChapterItem, idx: number) => ({
              chapter_num: idx + 1,
              title: c.title.trim(),
              summary: c.summary?.trim() || '',
              subtopics: Array.isArray(c.subtopics) ? c.subtopics.filter((s: string) => s && s.trim() !== '') : [],
            }))
        : [],
      target_audience_bn: Array.isArray(form.target_audience_bn) ? form.target_audience_bn.filter((p: TargetPersona) => p && p.title && p.title.trim() !== '') : [],
      target_audience_en: Array.isArray(form.target_audience_en) ? form.target_audience_en.filter((p: TargetPersona) => p && p.title && p.title.trim() !== '') : [],
      lab_upsell_title_bn: form.lab_upsell_title_bn?.trim() || null,
      lab_upsell_title_en: form.lab_upsell_title_en?.trim() || null,
      lab_upsell_desc_bn: form.lab_upsell_desc_bn?.trim() || null,
      lab_upsell_desc_en: form.lab_upsell_desc_en?.trim() || null,
      lab_upsell_btn_text_bn: form.lab_upsell_btn_text_bn?.trim() || null,
      lab_upsell_btn_text_en: form.lab_upsell_btn_text_en?.trim() || null,
      lab_upsell_btn_link: form.lab_upsell_btn_link?.trim() || null,
    };

    if (editingEbookId.value) {
      await apiClient.put(`/admin/ebooks/${editingEbookId.value}`, payload);
      toastStore.success(themeStore.locale === 'bn' ? 'ই-বুক সফলভাবে আপডেট করা হয়েছে' : 'Ebook updated successfully');
    } else {
      await apiClient.post('/admin/ebooks', payload);
      toastStore.success(themeStore.locale === 'bn' ? 'নতুন ই-বুক সফলভাবে যুক্ত হয়েছে' : 'Ebook created successfully');
    }
    showEditModal.value = false;
    fetchEbooks(pagination.current_page);
  } catch (err: any) {
    const msg = err.response?.data?.message || (themeStore.locale === 'bn' ? 'সংরক্ষণ ব্যর্থ হয়েছে' : 'Failed to save ebook');
    toastStore.error(msg);
  } finally {
    saving.value = false;
  }
};

const confirmDelete = async (item: any) => {
  const confirmMsg = themeStore.locale === 'bn'
    ? `আপনি কি নিশ্চিতভাবে "${item.title_bn}" ই-বুকটি মুছে ফেলতে চান?`
    : `Are you sure you want to delete "${item.title_en || item.title_bn}"?`;

  if (window.confirm(confirmMsg)) {
    try {
      await apiClient.delete(`/admin/ebooks/${item.id}`);
      toastStore.success(themeStore.locale === 'bn' ? 'ই-বুক সফলভাবে মুছে ফেলা হয়েছে' : 'Ebook deleted successfully');
      fetchEbooks(pagination.current_page);
    } catch (err) {
      toastStore.error(themeStore.locale === 'bn' ? 'মুছে ফেলা ব্যর্থ হয়েছে' : 'Failed to delete');
    }
  }
};

// Categories Management
const openCategoriesModal = () => {
  resetCatForm();
  showCategoriesModal.value = true;
};
const resetCatForm = () => {
  editingCatId.value = null;
  catForm.name_bn = '';
  catForm.name_en = '';
};
const editCategory = (cat: any) => {
  editingCatId.value = cat.id;
  catForm.name_bn = cat.name_bn;
  catForm.name_en = cat.name_en;
};
const saveCategory = async () => {
  try {
    if (editingCatId.value) {
      await apiClient.put(`/admin/ebooks/categories/${editingCatId.value}`, catForm);
      toastStore.success(themeStore.locale === 'bn' ? 'ক্যাটাগরি আপডেট হয়েছে' : 'Category updated successfully');
    } else {
      await apiClient.post('/admin/ebooks/categories', catForm);
      toastStore.success(themeStore.locale === 'bn' ? 'ক্যাটাগরি যুক্ত হয়েছে' : 'Category added successfully');
    }
    resetCatForm();
    fetchCategories();
  } catch (err: any) {
    const msg = err.response?.data?.message || (themeStore.locale === 'bn' ? 'ক্যাটাগরি সংরক্ষণ ব্যর্থ হয়েছে' : 'Failed to save category');
    toastStore.error(msg);
  }
};
const deleteCategory = async (id: number) => {
  const confirmMsg = themeStore.locale === 'bn' ? 'ক্যাটাগরি মুছে ফেলতে চান?' : 'Are you sure you want to delete this category?';
  if (window.confirm(confirmMsg)) {
    try {
      await apiClient.delete(`/admin/ebooks/categories/${id}`);
      toastStore.success(themeStore.locale === 'bn' ? 'ক্যাটাগরি মুছে ফেলা হয়েছে' : 'Category deleted successfully');
      fetchCategories();
    } catch (err: any) {
      const msg = err.response?.data?.message || (themeStore.locale === 'bn' ? 'ক্যাটাগরি মোছা সম্ভব হয়নি' : 'Failed to delete category');
      toastStore.error(msg);
    }
  }
};

// Downloads Log Modal
const openDownloadsModal = async (item: any) => {
  selectedEbookForDownloads.value = item;
  showDownloadsModal.value = true;
  loadingDownloads.value = true;
  try {
    const res = await apiClient.get(`/admin/ebooks/${item.id}/downloads`);
    downloadsList.value = res.data?.data?.downloads || [];
  } catch (err) {
    downloadsList.value = [];
  } finally {
    loadingDownloads.value = false;
  }
};

onMounted(() => {
  fetchCategories();
  fetchEbooks(1);
});
</script>
