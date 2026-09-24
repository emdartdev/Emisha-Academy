<template>
  <div class="space-y-8">
    <!-- Header & Action Strip -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">
            {{ themeStore.locale === 'bn' ? 'ব্লগ ও নলেজ হাব ম্যানেজমেন্ট' : 'Blog & Knowledge Hub Management' }}
          </h1>
          <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30">
            {{ pagination.total }} {{ themeStore.locale === 'bn' ? 'টি আর্টিকেল' : 'Articles' }}
          </span>
        </div>
        <p class="text-sm text-[var(--text-secondary)] mt-1">
          {{ themeStore.locale === 'bn' ? 'এভিয়েশন, ভিসা গাইডলাইন ও ক্যারিয়ার বিষয়ক আর্টিকেল, ক্যাটাগরি এবং মন্তব্য নিয়ন্ত্রণ করুন।' : 'Manage published articles, categories, author bylines, view counts, and comments.' }}
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

        <a
          href="/blog"
          target="_blank"
          class="px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all flex items-center gap-1.5"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'পাবলিক পেজ' : 'Public Blog' }}</span>
        </a>

        <button
          @click="openCreateModal"
          class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all inline-flex items-center gap-2 cursor-pointer touch-target shadow-md"
        >
          <span>+</span>
          <span>{{ themeStore.locale === 'bn' ? 'নতুন আর্টিকেল লিখুন' : 'Create Article' }}</span>
        </button>
      </div>
    </div>

    <!-- Search & Filter Strip -->
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
          @change="fetchPosts(1)"
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
            @click="statusFilter = st.value; fetchPosts(1)"
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

    <!-- Loading State -->
    <div v-if="loading" class="py-20 text-center space-y-3">
      <div class="inline-block animate-spin w-8 h-8 border-4 border-[#D4AF37] border-t-transparent rounded-full"></div>
      <p class="text-xs font-semibold text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'আর্টিকেল লোড হচ্ছে...' : 'Loading articles...' }}
      </p>
    </div>

    <!-- Empty State -->
    <div v-else-if="posts.length === 0" class="py-16 text-center p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
      <div class="w-14 h-14 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto shadow-sm"><svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div>
      <h3 class="text-base font-bold text-[var(--text-primary)]">
        {{ themeStore.locale === 'bn' ? 'কোনো আর্টিকেল পাওয়া যায়নি' : 'No articles found' }}
      </h3>
      <p class="text-xs text-[var(--text-secondary)] max-w-md mx-auto">
        {{ themeStore.locale === 'bn' ? 'নতুন ব্লগ পোস্ট লিখতে উপরের বাটনে ক্লিক করুন।' : 'Create your first article or adjust search criteria.' }}
      </p>
      <button
        @click="openCreateModal"
        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs shadow-md"
      >
        + {{ themeStore.locale === 'bn' ? 'আর্টিকেল তৈরি করুন' : 'Create Article' }}
      </button>
    </div>

    <!-- Blog Posts Table / Cards -->
    <div v-else class="space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="p in posts"
          :key="p.id"
          class="rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] overflow-hidden shadow-sm hover:shadow-xl hover:border-[#D4AF37]/40 transition-all flex flex-col justify-between group"
        >
          <!-- Top Thumbnail & Badges -->
          <div>
            <div class="relative aspect-[16/9] w-full bg-slate-950 overflow-hidden">
              <img
                v-if="p.thumbnail"
                :src="p.thumbnail"
                :alt="p.title_bn"
                class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-[11px] font-bold text-slate-500">
                {{ themeStore.locale === 'bn' ? 'থাম্বনেইল নেই' : 'No thumbnail' }}
              </div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

              <!-- Top Category & Featured Star -->
              <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2">
                <span class="px-2.5 py-1 rounded-lg bg-[#D4AF37]/90 text-slate-950 text-[10px] font-black uppercase tracking-wider backdrop-blur-md shadow-md">
                  {{ themeStore.locale === 'bn' ? (p.category?.name_bn || 'সাধারণ') : (p.category?.name_en || 'General') }}
                </span>

                <span v-if="p.is_featured" class="px-2 py-0.5 rounded-lg bg-amber-500 text-slate-950 text-[10px] font-black shadow-md">
                  Featured
                </span>
              </div>

              <!-- Bottom Stats -->
              <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-xs text-white">
                <span class="px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-md text-[10px] font-medium border border-white/15">
                  <span class="inline-flex items-center gap-1"><svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>{{ p.reading_time || "5 মিনিট" }}</span>
                </span>
                <span class="px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-md text-[10px] font-medium border border-white/15">
                  <span class="inline-flex items-center gap-1"><svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>{{ p.views_count || 0 }} ভিউ</span>
                </span>
              </div>
            </div>

            <!-- Content Details -->
            <div class="p-5 space-y-3">
              <div class="space-y-1">
                <span class="text-[11px] text-[var(--text-muted)] font-medium">
                  <span class="inline-flex items-center gap-1"><svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>{{ formatDate(p.published_at || p.created_at) }}</span>
                </span>
                <h3 class="text-sm sm:text-base font-bold text-[var(--text-primary)] line-clamp-2 leading-snug">
                  {{ themeStore.locale === 'bn' ? (p.title_bn || p.title_en) : (p.title_en || p.title_bn) }}
                </h3>
                <p class="text-xs text-[var(--text-secondary)] line-clamp-2 leading-relaxed">
                  {{ themeStore.locale === 'bn' ? (p.summary_bn || p.summary_en) : (p.summary_en || p.summary_bn) }}
                </p>
              </div>

              <!-- Author Info Byline -->
              <div class="p-3 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] flex items-center gap-2.5">
                <img
                  :src="p.author_avatar || getInitialsAvatar(p.author_name_en || p.author_name_bn || 'Emisha')"
                  :alt="p.author_name_bn"
                  class="w-8 h-8 rounded-full object-cover border border-[#D4AF37]"
                />
                <div class="min-w-0 flex-1">
                  <div class="text-xs font-bold text-[var(--text-primary)] truncate">
                    {{ p.author_name_bn || (p.author?.name || 'ইমিশা অ্যাডমিন') }}
                  </div>
                  <div class="text-[10px] text-[var(--text-muted)] truncate">
                    {{ p.author_designation_bn || 'এভিয়েশন ট্রেইনার ও রিসার্চার' }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Card Actions -->
          <div class="p-4 pt-0 space-y-2.5 border-t border-[var(--border-subtle)]/50 mt-2">
            <div class="grid grid-cols-2 gap-2 pt-2">
              <button
                type="button"
                @click="openEditModal(p)"
                class="py-2 px-3 rounded-xl bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/15 hover:text-[#D4AF37] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] transition-all flex items-center justify-center gap-1 cursor-pointer"
              >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'সম্পাদনা' : 'Edit' }}</span>
              </button>

              <button
                type="button"
                @click="openCommentsModal(p)"
                class="py-2 px-3 rounded-xl bg-[var(--bg-elevated)] hover:bg-sky-500/15 hover:text-sky-400 border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] transition-all flex items-center justify-center gap-1 cursor-pointer"
              >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span>{{ p.comments_count || 0 }} {{ themeStore.locale === 'bn' ? 'মন্তব্য' : 'Comments' }}</span>
              </button>
            </div>

            <div class="flex items-center justify-between text-[11px] pt-1">
              <router-link
                :to="`/blog/${p.slug}`"
                target="_blank"
                class="text-[#D4AF37] hover:underline font-bold flex items-center gap-1"
              >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'পাবলিক ভিউ' : 'Public Link' }}</span>
              </router-link>

              <button
                type="button"
                @click="confirmDeletePost(p)"
                class="text-rose-400 hover:text-rose-500 hover:underline font-medium cursor-pointer"
              >
                {{ themeStore.locale === 'bn' ? 'মুছে ফেলুন' : 'Delete' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="pagination.last_page > 1" class="flex items-center justify-between p-4 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-2xl">
      <span class="text-xs text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? `পৃষ্ঠা ${pagination.current_page} এর ${pagination.last_page}` : `Page ${pagination.current_page} of ${pagination.last_page}` }}
      </span>
      <div class="flex items-center gap-2">
        <button
          :disabled="pagination.current_page === 1"
          @click="fetchPosts(pagination.current_page - 1)"
          class="px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold disabled:opacity-50 cursor-pointer"
        >
          ← {{ themeStore.locale === 'bn' ? 'পূর্ববর্তী' : 'Prev' }}
        </button>
        <button
          :disabled="pagination.current_page === pagination.last_page"
          @click="fetchPosts(pagination.current_page + 1)"
          class="px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold disabled:opacity-50 cursor-pointer"
        >
          {{ themeStore.locale === 'bn' ? 'পরবর্তী' : 'Next' }} →
        </button>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. CREATE & EDIT BLOG POST MODAL -->
    <!-- ========================================================================= -->
    <AppModal
      v-model="isEditModalOpen"
      :title="isCreating ? (themeStore.locale === 'bn' ? 'নতুন ব্লগ আর্টিকেল লিখুন' : 'Create New Article') : (themeStore.locale === 'bn' ? 'আর্টিকেল তথ্য সম্পাদনা' : 'Edit Article Details')"
      size="xl"
    >
      <form @submit.prevent="savePost" class="space-y-6">
        
        <!-- Editor Tabs -->
        <div class="flex items-center gap-2 border-b border-[var(--border-subtle)] pb-2 overflow-x-auto">
          <button
            type="button"
            v-for="t in editorTabs"
            :key="t.id"
            @click="activeEditorTab = t.id"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap cursor-pointer flex items-center gap-1.5',
              activeEditorTab === t.id
                ? 'bg-[#D4AF37] text-slate-950 shadow-md'
                : 'bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
            ]"
          >
            <span>{{ t.icon }}</span>
            <span>{{ t.label }}</span>
          </button>
        </div>

        <!-- TAB 1: Basic & Metadata -->
        <div v-show="activeEditorTab === 'basic'" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'আর্টিকেল শিরোনাম (বাংলা)' : 'Title (Bangla)' }} <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="postForm.title_bn"
                type="text"
                required
                placeholder="যেমন: এয়ার টিকেটিং ও ভিসা প্রসেসিং শিখে কীভাবে দ্রুত জব ও এজেন্সি ব্যবসা শুরু করবেন?"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'শিরোনাম (English)' : 'Title (English)' }} <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="postForm.title_en"
                type="text"
                required
                placeholder="e.g. How to Build a High-Demand Career in Air Ticketing & Visa Processing"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'ইউআরএল স্লাগ (Slug)' : 'URL Slug' }}
              </label>
              <input
                v-model="postForm.slug"
                type="text"
                placeholder="how-to-build-career-in-air-ticketing"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'ক্যাটাগরি' : 'Category' }}
              </label>
              <select
                v-model="postForm.category_id"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              >
                <option :value="null">{{ themeStore.locale === 'bn' ? 'ক্যাটাগরি নির্বাচন করুন' : 'Select Category' }}</option>
                <option v-for="cat in categoriesList" :key="cat.id" :value="cat.id">
                  {{ themeStore.locale === 'bn' ? (cat.name_bn || cat.name_en) : (cat.name_en || cat.name_bn) }}
                </option>
              </select>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'পাঠের সময় (Reading Time)' : 'Reading Time' }}
              </label>
              <input
                v-model="postForm.reading_time"
                type="text"
                placeholder="5 মিনিট"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'ভিউ সংখ্যা (Views Count)' : 'Views Count' }}
              </label>
              <input
                v-model.number="postForm.views_count"
                type="number"
                placeholder="1243"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'স্ট্যাটাস' : 'Status' }}
              </label>
              <select
                v-model="postForm.status"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              >
                <option value="published">{{ themeStore.locale === 'bn' ? 'প্রকাশিত (Published)' : 'Published' }}</option>
                <option value="draft">{{ themeStore.locale === 'bn' ? 'ড্রাফট (Draft)' : 'Draft' }}</option>
                <option value="archived">{{ themeStore.locale === 'bn' ? 'আর্কাইভ (Archived)' : 'Archived' }}</option>
              </select>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'প্রকাশের তারিখ' : 'Published Date' }}
              </label>
              <input
                v-model="postForm.published_at"
                type="datetime-local"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <div class="md:col-span-2 space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'ফিচার্ড থাম্বনেইল ইমেজ URL' : 'Thumbnail Image URL' }}
              </label>
              <input
                v-model="postForm.thumbnail"
                type="url"
                placeholder="https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1200"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)]"
              />
            </div>

            <div class="pt-4">
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  v-model="postForm.is_featured"
                  type="checkbox"
                  class="w-4 h-4 rounded text-[#D4AF37] focus:ring-[#D4AF37] accent-[#D4AF37]"
                />
                <span class="text-xs font-bold text-[var(--text-primary)]">
                  {{ themeStore.locale === "bn" ? "ফিচার্ড আর্টিকেল রাখুন" : "Featured Article" }}
                </span>
              </label>
            </div>
          </div>
        </div>

        <!-- TAB 2: Author Byline -->
        <div v-show="activeEditorTab === 'author'" class="space-y-4">
          <div class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-4">
            <div class="flex items-center gap-3">
              <img
                :src="postForm.author_avatar || getInitialsAvatar(postForm.author_name_en || postForm.author_name_bn || 'Emisha')"
                alt="Author Preview"
                class="w-14 h-14 rounded-full object-cover border-2 border-[#D4AF37] shadow-md"
              />
              <div class="space-y-0.5">
                <h4 class="text-xs font-bold text-[var(--text-primary)]">
                  {{ themeStore.locale === 'bn' ? 'লেখকের পরিচিতি ও বাইলাইন' : 'Author Profile & Byline' }}
                </h4>
                <p class="text-[11px] text-[var(--text-muted)]">
                  {{ themeStore.locale === 'bn' ? 'আর্টিকেলের শুরুতে ও শেষে এই তথ্য প্রদর্শিত হবে।' : 'Displayed on top and bottom byline of the article.' }}
                </p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-primary)]">
                  {{ themeStore.locale === 'bn' ? 'লেখকের নাম (বাংলা)' : 'Author Name (Bangla)' }}
                </label>
                <input
                  v-model="postForm.author_name_bn"
                  placeholder="যেমন: ইমিশা অ্যাডমিন / তানভীর রহমান"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium"
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-primary)]">
                  {{ themeStore.locale === 'bn' ? 'লেখকের নাম (English)' : 'Author Name (English)' }}
                </label>
                <input
                  v-model="postForm.author_name_en"
                  placeholder="e.g. Emisha Admin / Tanvir Rahman"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-primary)]">
                  {{ themeStore.locale === 'bn' ? 'লেখকের পদবি / রোল (বাংলা)' : 'Author Role / Title (Bangla)' }}
                </label>
                <input
                  v-model="postForm.author_designation_bn"
                  placeholder="যেমন: এভিয়েশন ট্রেইনার ও ট্রাভেল রিসার্চার"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium"
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-primary)]">
                  {{ themeStore.locale === 'bn' ? 'পদবি (English)' : 'Author Role (English)' }}
                </label>
                <input
                  v-model="postForm.author_designation_en"
                  placeholder="e.g. Aviation Trainer & Travel Researcher"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium"
                />
              </div>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'লেখকের ছবির URL (Avatar Link)' : 'Author Avatar Link' }}
              </label>
              <input
                v-model="postForm.author_avatar"
                placeholder="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium"
              />
            </div>
          </div>
        </div>

        <!-- TAB 3: Key Summary & Tags -->
        <div v-show="activeEditorTab === 'summary'" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)] flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'মূল সারসংক্ষেপ (বাংলা - Lead Box)' : 'Key Summary (Bangla)' }}</span>
              </label>
              <textarea
                v-model="postForm.summary_bn"
                rows="4"
                placeholder="Sabre ও Galileo সফটওয়্যারে দক্ষতা অর্জন করে এভিয়েশন এবং ট্রাভেল এজেন্সিতে ক্যারিয়ার গড়ার সহজ ও প্র্যাকটিক্যাল গাইড।"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium resize-none"
              ></textarea>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)] flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'মূল সারসংক্ষেপ (English)' : 'Key Summary (English)' }}</span>
              </label>
              <textarea
                v-model="postForm.summary_en"
                rows="4"
                placeholder="A practical roadmap to becoming an in-demand air ticketing officer and tourist visa consultant."
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium resize-none"
              ></textarea>
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === "bn" ? "ট্যাগসমূহ (কমা দিয়ে আলাদা করুন)" : "Tags (Comma-separated)" }}
            </label>
            <input
              v-model="rawTagsInput"
              placeholder="Air Ticketing, Visa Processing, Sabre GDS, Galileo, Career Guide"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium"
            />
          </div>
        </div>

        <!-- TAB 4: Body Content (Rich HTML) -->
        <div v-show="activeEditorTab === 'content'" class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-[var(--text-primary)] flex items-center justify-between">
              <span>{{ themeStore.locale === "bn" ? "পূর্ণাঙ্গ আর্টিকেল কনটেন্ট (বাংলা - HTML Supported)" : "Full Article Content (Bangla)" }}</span>
              <span class="text-[11px] text-[var(--text-muted)]">&lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;b&gt; ব্যবহার করতে পারেন</span>
            </label>
            <textarea
              v-model="postForm.content_bn"
              rows="12"
              placeholder="<p>বর্তমান সময়ে ভ্রমণ ও আন্তর্জাতিক যাতায়াত কয়েকগুণ বৃদ্ধি পেয়েছে...</p>"
              class="w-full px-4 py-3 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-mono resize-y"
            ></textarea>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === "bn" ? "আর্টিকেল কনটেন্ট (English - Optional)" : "Article Content (English - Optional)" }}
            </label>
            <textarea
              v-model="postForm.content_en"
              rows="8"
              placeholder="<p>In today's globalized travel market, aviation operations...</p>"
              class="w-full px-4 py-3 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-mono resize-y"
            ></textarea>
          </div>
        </div>

        <!-- Action Footer -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[var(--border-subtle)]">
          <button
            type="button"
            @click="isEditModalOpen = false"
            class="px-5 py-2.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'বাতিল' : 'Cancel' }}
          </button>

          <button
            type="submit"
            :disabled="isSaving"
            class="px-7 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all cursor-pointer shadow-md disabled:opacity-50 flex items-center gap-2"
          >
            <span v-if="isSaving" class="inline-block animate-spin w-3.5 h-3.5 border-2 border-slate-950 border-t-transparent rounded-full"></span>
            <span>{{ isCreating ? (themeStore.locale === 'bn' ? 'আর্টিকেল প্রকাশ করুন' : 'Publish Article') : (themeStore.locale === 'bn' ? 'আপডেট সংরক্ষণ করুন' : 'Save Changes') }}</span>
          </button>
        </div>

      </form>
    </AppModal>

    <!-- ========================================================================= -->
    <!-- 2. CATEGORIES MANAGEMENT MODAL -->
    <!-- ========================================================================= -->
    <AppModal
      v-model="isCategoriesModalOpen"
      :title="themeStore.locale === 'bn' ? 'ব্লগ ক্যাটাগরি ম্যানেজমেন্ট' : 'Blog Categories Management'"
      size="lg"
    >
      <div class="space-y-6">
        <!-- Categories List -->
        <div class="space-y-2.5">
          <h4 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider text-[#D4AF37]">
            {{ themeStore.locale === 'bn' ? 'বর্তমান ক্যাটাগরিসমূহ' : 'Existing Categories' }}
          </h4>

          <div
            v-for="cat in categoriesList"
            :key="cat.id"
            class="p-3.5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] flex items-center justify-between gap-3"
          >
            <div class="space-y-0.5">
              <div class="text-xs font-bold text-[var(--text-primary)]">
                {{ cat.name_bn }} <span class="text-[11px] text-[var(--text-muted)] font-normal">({{ cat.name_en }})</span>
              </div>
              <div class="text-[10px] text-[var(--text-muted)]">
                Slug: <b>{{ cat.slug }}</b> • {{ cat.posts_count || 0 }} {{ themeStore.locale === 'bn' ? 'টি পোস্ট' : 'posts' }}
              </div>
            </div>

            <div class="flex items-center gap-1.5">
              <button
                type="button"
                @click="populateCategoryEdit(cat)"
                class="p-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/15 text-[#D4AF37] text-xs cursor-pointer"
                title="Edit"
              ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
              <button
                type="button"
                @click="deleteCategory(cat.id)"
                class="p-1.5 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white text-xs cursor-pointer"
                title="Delete"
              ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
            </div>
          </div>
        </div>

        <!-- Add / Edit Category Form -->
        <form @submit.prevent="saveCategory" class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-3">
          <div class="flex items-center justify-between">
            <h5 class="text-xs font-bold text-[var(--text-primary)]">
              {{ editingCategoryId ? (themeStore.locale === 'bn' ? 'ক্যাটাগরি সম্পাদনা' : 'Edit Category') : (themeStore.locale === 'bn' ? '+ নতুন ক্যাটাগরি তৈরি করুন' : '+ Add New Category') }}
            </h5>
            <button
              v-if="editingCategoryId"
              type="button"
              @click="resetCategoryForm"
              class="text-[11px] text-rose-400 hover:underline cursor-pointer"
            >
              {{ themeStore.locale === 'bn' ? 'বাতিল' : 'Cancel' }}
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <input
              v-model="categoryForm.name_bn"
              required
              placeholder="ক্যাটাগরি নাম (বাংলা)* যেমন: ভিসা ও ট্রাভেল গাইডলাইন"
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
            />
            <input
              v-model="categoryForm.name_en"
              required
              placeholder="Category Name (English)* e.g. Visa & Travel Guidelines"
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
            />
            <input
              v-model="categoryForm.slug"
              placeholder="Slug (e.g. travel-visa-guidelines)"
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs md:col-span-2"
            />
          </div>

          <div class="flex items-center justify-end">
            <button
              type="submit"
              class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs cursor-pointer shadow-sm"
            >
              {{ editingCategoryId ? (themeStore.locale === 'bn' ? 'ক্যাটাগরি আপডেট করুন' : 'Update Category') : (themeStore.locale === 'bn' ? 'সংরক্ষণ করুন' : 'Save Category') }}
            </button>
          </div>
        </form>
      </div>
    </AppModal>

    <!-- ========================================================================= -->
    <!-- 3. COMMENTS MODERATION MODAL -->
    <!-- ========================================================================= -->
    <AppModal
      v-model="isCommentsModalOpen"
      :title="`${themeStore.locale === 'bn' ? 'মন্তব্য মডারেশন:' : 'Comments Moderation:'} ${selectedPost?.title_bn || ''}`"
      size="lg"
    >
      <div class="space-y-4">
        <div v-if="commentsLoading" class="py-10 text-center">
          <div class="inline-block animate-spin w-6 h-6 border-2 border-[#D4AF37] border-t-transparent rounded-full"></div>
        </div>

        <div v-else-if="commentsList.length === 0" class="py-10 text-center text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'এই আর্টিকেলে এখনো কোনো মন্তব্য জমা হয়নি।' : 'No comments submitted for this article yet.' }}
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="c in commentsList"
            :key="c.id"
            class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] flex items-start justify-between gap-4"
          >
            <div class="space-y-1 min-w-0">
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-[var(--text-primary)]">{{ c.guest_name || 'পাঠক' }}</span>
                <span class="text-[10px] text-[var(--text-muted)]">{{ formatDate(c.created_at) }}</span>
              </div>
              <p class="text-xs text-[var(--text-secondary)] leading-relaxed">{{ c.comment }}</p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
              <button
                type="button"
                @click="toggleComment(c)"
                class="px-2.5 py-1 rounded-lg text-xs font-bold cursor-pointer"
                :class="c.is_approved ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400'"
              >
                {{ c.is_approved ? (themeStore.locale === 'bn' ? 'অনুমোদিত' : 'Approved') : (themeStore.locale === 'bn' ? 'লুকানো' : 'Hidden') }}
              </button>

              <button
                type="button"
                @click="deleteComment(c.id)"
                class="p-1.5 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all cursor-pointer text-xs"
              ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
            </div>
          </div>
        </div>
      </div>
    </AppModal>

  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';
import AppModal from '../../components/ui/AppModal.vue';
import { getInitialsAvatar } from '../../utils/imageFallback';

const themeStore = useThemeStore();
const toast = useToastStore();

const loading = ref(true);
const posts = ref<any[]>([]);
const categoriesList = ref<any[]>([]);
const searchQuery = ref('');
const selectedCategoryFilter = ref('');
const statusFilter = ref('');

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  total: 0,
});

const statusOptions = [
  { label: themeStore.locale === 'bn' ? 'সকল' : 'All', value: '' },
  { label: themeStore.locale === 'bn' ? 'প্রকাশিত' : 'Published', value: 'published' },
  { label: themeStore.locale === 'bn' ? 'ড্রাফট' : 'Draft', value: 'draft' },
  { label: themeStore.locale === 'bn' ? 'আর্কাইভ' : 'Archived', value: 'archived' },
];

const editorTabs = [
  { id: 'basic', label: themeStore.locale === 'bn' ? 'বেসিক ও মেটা' : 'Basic Info', icon: '' },
  { id: 'author', label: themeStore.locale === 'bn' ? 'লেখক ও পরিচিতি' : 'Author Byline', icon: '' },
  { id: 'summary', label: themeStore.locale === 'bn' ? 'সারসংক্ষেপ ও ট্যাগ' : 'Summary & Tags', icon: '' },
  { id: 'content', label: themeStore.locale === 'bn' ? 'মূল কনটেন্ট (HTML)' : 'Full Content', icon: '' },
];
const activeEditorTab = ref('basic');

// Create / Edit Modal State
const isEditModalOpen = ref(false);
const isCreating = ref(false);
const editingPostId = ref<number | null>(null);
const isSaving = ref(false);
const rawTagsInput = ref('');

const postForm = reactive({
  category_id: null as number | null,
  title_bn: '',
  title_en: '',
  slug: '',
  summary_bn: '',
  summary_en: '',
  content_bn: '',
  content_en: '',
  thumbnail: '',
  author_name_bn: 'ইমিশা অ্যাডমিন',
  author_name_en: 'Emisha Admin',
  author_designation_bn: 'এভিয়েশন ট্রেইনার ও ট্রাভেল রিসার্চার',
  author_designation_en: 'Aviation Trainer & Travel Researcher',
  author_avatar: '',
  reading_time: '',
  views_count: 0,
  is_featured: false,
  status: 'published',
  published_at: '',
});

// Categories Modal State
const isCategoriesModalOpen = ref(false);
const editingCategoryId = ref<number | null>(null);
const categoryForm = reactive({
  name_bn: '',
  name_en: '',
  slug: '',
});

// Comments Modal State
const isCommentsModalOpen = ref(false);
const selectedPost = ref<any>(null);
const commentsList = ref<any[]>([]);
const commentsLoading = ref(false);

let debounceTimer: any = null;
const debounceFetch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchPosts(1);
  }, 350);
};

const fetchPosts = async (page = 1) => {
  loading.value = true;
  try {
    const res = await apiClient.get('/admin/blogs', {
      params: {
        page,
        search: searchQuery.value,
        category_id: selectedCategoryFilter.value,
        status: statusFilter.value,
      },
    });
    posts.value = res.data.data.posts;
    pagination.current_page = res.data.data.pagination.current_page;
    pagination.last_page = res.data.data.pagination.last_page;
    pagination.total = res.data.data.pagination.total;
  } catch {
    toast.error('আর্টিকেল তালিকা লোড করতে সমস্যা হয়েছে।');
  } finally {
    loading.value = false;
  }
};

const fetchCategories = async () => {
  try {
    const res = await apiClient.get('/admin/blogs/categories');
    categoriesList.value = res.data.data;
  } catch {
    // Graceful fallback
  }
};

const openCreateModal = () => {
  isCreating.value = true;
  editingPostId.value = null;
  activeEditorTab.value = 'basic';
  rawTagsInput.value = 'Air Ticketing, Visa Processing, Sabre GDS, Galileo, Career Guide';

  Object.assign(postForm, {
    category_id: categoriesList.value[0]?.id || null,
    title_bn: '',
    title_en: '',
    slug: '',
    summary_bn: '',
    summary_en: '',
    content_bn: '',
    content_en: '',
    thumbnail: '',
    author_name_bn: 'ইমিশা অ্যাডমিন',
    author_name_en: 'Emisha Admin',
    author_designation_bn: 'এভিয়েশন ট্রেইনার ও ট্রাভেল রিসার্চার',
    author_designation_en: 'Aviation Trainer & Travel Researcher',
    author_avatar: '',
    reading_time: '',
    views_count: 0,
    is_featured: false,
    status: 'published',
    published_at: new Date().toISOString().slice(0, 16),
  });

  isEditModalOpen.value = true;
};

const openEditModal = (p: any) => {
  isCreating.value = false;
  editingPostId.value = p.id;
  activeEditorTab.value = 'basic';

  let formattedDate = '';
  if (p.published_at) {
    const d = new Date(p.published_at);
    if (!isNaN(d.getTime())) {
      const pad = (n: number) => n.toString().padStart(2, '0');
      formattedDate = `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }
  }

  rawTagsInput.value = Array.isArray(p.tags) ? p.tags.join(', ') : '';

  Object.assign(postForm, {
    category_id: p.category_id,
    title_bn: p.title_bn || '',
    title_en: p.title_en || '',
    slug: p.slug || '',
    summary_bn: p.summary_bn || '',
    summary_en: p.summary_en || '',
    content_bn: p.content_bn || '',
    content_en: p.content_en || '',
    thumbnail: p.thumbnail || '',
    author_name_bn: p.author_name_bn || 'ইমিশা অ্যাডমিন',
    author_name_en: p.author_name_en || 'Emisha Admin',
    author_designation_bn: p.author_designation_bn || 'এভিয়েশন ট্রেইনার ও ট্রাভেল রিসার্চার',
    author_designation_en: p.author_designation_en || 'Aviation Trainer & Travel Researcher',
    author_avatar: p.author_avatar || '',
    reading_time: p.reading_time || '',
    views_count: p.views_count || 0,
    is_featured: !!p.is_featured,
    status: p.status || 'published',
    published_at: formattedDate,
  });

  isEditModalOpen.value = true;
};

const savePost = async () => {
  isSaving.value = true;
  try {
    const tags = rawTagsInput.value
      .split(',')
      .map((t) => t.trim())
      .filter((t) => t.length > 0);

    const payload = {
      ...postForm,
      tags,
    };

    if (isCreating.value) {
      await apiClient.post('/admin/blogs', payload);
      toast.success(themeStore.locale === 'bn' ? 'আর্টিকেল সফলভাবে তৈরি ও প্রকাশিত হয়েছে।' : 'Article published successfully.');
    } else {
      await apiClient.put(`/admin/blogs/${editingPostId.value}`, payload);
      toast.success(themeStore.locale === 'bn' ? 'আর্টিকেল সফলভাবে আপডেট হয়েছে।' : 'Article updated successfully.');
    }

    isEditModalOpen.value = false;
    fetchPosts(pagination.current_page);
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'তথ্য সংরক্ষণে ত্রুটি হয়েছে।');
  } finally {
    isSaving.value = false;
  }
};

const confirmDeletePost = async (p: any) => {
  if (confirm(`আপনি কি নিশ্চিত যে "${p.title_bn || p.title_en}" আর্টিকেলটি মুছে ফেলতে চান?`)) {
    try {
      await apiClient.delete(`/admin/blogs/${p.id}`);
      toast.success(themeStore.locale === 'bn' ? 'আর্টিকেল মুছে ফেলা হয়েছে।' : 'Article deleted.');
      fetchPosts(pagination.current_page);
    } catch {
      toast.error('আর্টিকেল মুছতে সমস্যা হয়েছে।');
    }
  }
};

// Categories Management Actions
const openCategoriesModal = async () => {
  resetCategoryForm();
  isCategoriesModalOpen.value = true;
  await fetchCategories();
};

const resetCategoryForm = () => {
  editingCategoryId.value = null;
  Object.assign(categoryForm, {
    name_bn: '',
    name_en: '',
    slug: '',
  });
};

const populateCategoryEdit = (cat: any) => {
  editingCategoryId.value = cat.id;
  Object.assign(categoryForm, {
    name_bn: cat.name_bn || '',
    name_en: cat.name_en || '',
    slug: cat.slug || '',
  });
};

const saveCategory = async () => {
  try {
    if (editingCategoryId.value) {
      await apiClient.put(`/admin/blogs/categories/${editingCategoryId.value}`, categoryForm);
      toast.success(themeStore.locale === 'bn' ? 'ক্যাটাগরি আপডেট হয়েছে।' : 'Category updated.');
    } else {
      await apiClient.post('/admin/blogs/categories', categoryForm);
      toast.success(themeStore.locale === 'bn' ? 'ক্যাটাগরি তৈরি হয়েছে।' : 'Category created.');
    }
    resetCategoryForm();
    await fetchCategories();
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'ক্যাটাগরি সংরক্ষণে ত্রুটি হয়েছে।');
  }
};

const deleteCategory = async (catId: number) => {
  if (confirm('আপনি কি এই ক্যাটাগরিটি মুছে ফেলতে চান?')) {
    try {
      await apiClient.delete(`/admin/blogs/categories/${catId}`);
      toast.success(themeStore.locale === 'bn' ? 'ক্যাটাগরি মুছে ফেলা হয়েছে।' : 'Category deleted.');
      await fetchCategories();
    } catch {
      toast.error('ক্যাটাগরি মুছতে সমস্যা হয়েছে।');
    }
  }
};

// Comments Management Actions
const openCommentsModal = async (p: any) => {
  selectedPost.value = p;
  isCommentsModalOpen.value = true;
  commentsLoading.value = true;
  try {
    const res = await apiClient.get(`/admin/blogs/${p.id}/comments`);
    commentsList.value = res.data.data;
  } finally {
    commentsLoading.value = false;
  }
};

const toggleComment = async (c: any) => {
  try {
    const res = await apiClient.put(`/admin/blogs/comments/${c.id}/toggle`);
    c.is_approved = res.data.data.is_approved;
    toast.success(res.data.message);
  } catch {
    toast.error('মন্তব্য স্ট্যাটাস পরিবর্তনে সমস্যা হয়েছে।');
  }
};

const deleteComment = async (commentId: number) => {
  if (confirm('আপনি কি এই মন্তব্যটি মুছে ফেলতে চান?')) {
    try {
      await apiClient.delete(`/admin/blogs/comments/${commentId}`);
      toast.success(themeStore.locale === 'bn' ? 'মন্তব্য মুছে ফেলা হয়েছে।' : 'Comment deleted.');
      if (selectedPost.value) {
        const res = await apiClient.get(`/admin/blogs/${selectedPost.value.id}/comments`);
        commentsList.value = res.data.data;
      }
    } catch {
      toast.error('মন্তব্য মুছতে সমস্যা হয়েছে।');
    }
  }
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString(themeStore.locale === 'bn' ? 'bn-BD' : 'en-US', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  });
};

onMounted(async () => {
  await fetchCategories();
  await fetchPosts();
});
</script>
