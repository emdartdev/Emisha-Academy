<template>
  <div class="space-y-8">
    <!-- 1. Header & Action Strip -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">
            {{ themeStore.locale === 'bn' ? 'মেন্টর ও ফ্যাকাল্টি প্যানেল ম্যানেজমেন্ট' : 'Mentors & Faculty Management' }}
          </h1>
          <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30">
            {{ pagination.total }} {{ themeStore.locale === 'bn' ? 'জন মেন্টর' : 'Mentors' }}
          </span>
        </div>
        <p class="text-sm text-[var(--text-secondary)] mt-1">
          {{ themeStore.locale === 'bn' 
            ? 'এভিয়েশন, জিডিএস ও ভিসা কনসালটেন্সি ট্রেইনারদের প্রোফাইল, বায়ো, সোশ্যাল লিংক ও কোর্স অ্যাসাইনমেন্ট পরিচালনা করুন।' 
            : 'Manage aviation, GDS and visa consultant trainer profiles, credentials, assigned courses, and visibility.' }}
        </p>
      </div>

      <div class="flex flex-wrap items-center gap-3">
        <router-link
          to="/about"
          target="_blank"
          class="px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all flex items-center gap-1.5"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'পাবলিক মেন্টর পেজ' : 'Public About Page' }}</span>
        </router-link>

        <button
          type="button"
          @click="openCreateModal"
          class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all inline-flex items-center gap-2 cursor-pointer shadow-md"
        >
          <span>+</span>
          <span>{{ themeStore.locale === 'bn' ? 'নতুন মেন্টর যুক্ত করুন' : 'Add New Mentor' }}</span>
        </button>
      </div>
    </div>

    <!-- 2. Performance & Metric Highlights -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'মোট মেন্টর' : 'Total Mentors' }}</span>
          <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div class="text-2xl font-black text-[var(--text-primary)]">{{ metrics.total_mentors }}</div>
        <div class="text-[11px] text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'নিবন্ধিত ফ্যাকাল্টি সদস্য' : 'Active faculty staff' }}</div>
      </div>

      <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'ফিচার্ড মেন্টর' : 'Featured Mentors' }}</span>
          <svg class="w-4 h-4 fill-current text-[#D4AF37]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <div class="text-2xl font-black text-[#D4AF37]">{{ metrics.featured_mentors }}</div>
        <div class="text-[11px] text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'হোমপেজ ও অ্যাবাউটে প্রদর্শিত' : 'Highlighted on showcase' }}</div>
      </div>

      <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'মোট প্রশিক্ষিত শিক্ষার্থী' : 'Trained Students' }}</span>
          <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 10v6M2 10l10-5 10 5-10 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        </div>
        <div class="text-2xl font-black text-sky-400">{{ metrics.total_students_trained.toLocaleString() }}+</div>
        <div class="text-[11px] text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'সফল ক্যারিয়ার অর্জনকারী' : 'Alumni & ongoing batches' }}</div>
      </div>

      <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'গড় সন্তুষ্টি রেটিং' : 'Average Rating' }}</span>
          <svg class="w-4 h-4 fill-current text-amber-400" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <div class="text-2xl font-black text-amber-400">{{ metrics.average_rating }} / 5.0</div>
        <div class="text-[11px] text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'শিক্ষার্থী মূল্যায়ন স্কোর' : 'Verified review average' }}</div>
      </div>
    </div>

    <!-- 3. Search & Filter Strip -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-[var(--bg-surface)] border border-[var(--border-subtle)] p-4 rounded-2xl shadow-xs">
      <div class="w-full sm:w-80">
        <input
          v-model="searchQuery"
          @input="debounceFetch"
          type="text"
          :placeholder="themeStore.locale === 'bn' ? 'নাম, পদবি বা প্রতিষ্ঠান দিয়ে খুঁজুন...' : 'Search by name, title or institution...'"
          class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] transition-colors"
        />
      </div>

      <div class="flex items-center gap-2 w-full sm:w-auto">
        <button
          v-for="st in featuredFilterOptions"
          :key="st.value"
          type="button"
          @click="featuredFilter = st.value; fetchInstructors(1)"
          :class="[
            'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer',
            featuredFilter === st.value
              ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40 shadow-xs'
              : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-elevated)]'
          ]"
        >
          {{ st.label }}
        </button>
      </div>
    </div>

    <!-- 4. Loading State -->
    <div v-if="loading" class="py-20 text-center space-y-3">
      <div class="inline-block animate-spin w-8 h-8 border-4 border-[#D4AF37] border-t-transparent rounded-full"></div>
      <p class="text-xs font-semibold text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'মেন্টর তথ্য লোড হচ্ছে...' : 'Loading mentors...' }}
      </p>
    </div>

    <!-- 5. Empty State -->
    <div v-else-if="instructors.length === 0" class="py-16 text-center p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
      <div class="w-14 h-14 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto shadow-sm"><svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
      <h3 class="text-base font-bold text-[var(--text-primary)]">
        {{ themeStore.locale === 'bn' ? 'কোনো মেন্টর প্রোফাইল পাওয়া যায়নি' : 'No mentor profiles found' }}
      </h3>
      <p class="text-xs text-[var(--text-secondary)] max-w-md mx-auto">
        {{ themeStore.locale === 'bn' ? 'নতুন এভিয়েশন ট্রেইনার বা ফ্যাকাল্টি যুক্ত করতে উপরের বাটনে ক্লিক করুন।' : 'Add your first instructor or clear your search filters.' }}
      </p>
      <button
        type="button"
        @click="openCreateModal"
        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs shadow-md cursor-pointer"
      >
        + {{ themeStore.locale === 'bn' ? 'মেন্টর যুক্ত করুন' : 'Add Mentor' }}
      </button>
    </div>

    <!-- 6. Mentors Cards Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="item in instructors"
        :key="item.id"
        class="group flex flex-col justify-between rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden"
      >
        <!-- Card Top & Avatar Showcase -->
        <div class="p-6 space-y-4">
          <div class="flex items-start justify-between gap-3">
            <div class="relative w-16 h-16 sm:w-20 sm:h-20 shrink-0">
              <img
                :src="item.avatar || getInitialsAvatar(item.name_en || item.name_bn || 'Mentor')"
                :alt="item.name_bn"
                class="w-full h-full object-cover rounded-2xl ring-2 ring-[var(--border-subtle)] group-hover:ring-[#D4AF37]/60 transition-all shadow-md bg-slate-900"
                @error="onImageError($event, 'avatar', item.name_en || item.name_bn)"
              />
              <span
                v-if="item.is_featured"
                class="absolute -top-2 -right-2 px-1.5 py-0.5 rounded-full bg-[#D4AF37] text-slate-950 text-[10px] font-black shadow-xs flex items-center justify-center"
                title="Featured Mentor"
              >
                <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              </span>
            </div>

            <div class="text-right space-y-1">
              <span
                :class="[
                  'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider',
                  item.is_featured ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30' : 'bg-[var(--bg-elevated)] text-[var(--text-muted)] border border-[var(--border-subtle)]'
                ]"
              >
                {{ item.is_featured ? (themeStore.locale === 'bn' ? 'ফিচার্ড ফ্যাকাল্টি' : 'Featured') : (themeStore.locale === 'bn' ? 'স্ট্যান্ডার্ড' : 'Standard') }}
              </span>

              <div class="flex items-center justify-end gap-1 text-amber-400 text-xs font-black">
                <svg class="w-3 h-3 fill-current text-[#D4AF37]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span>{{ Number(item.rating).toFixed(2) }}</span>
              </div>
            </div>
          </div>

          <!-- Name & Title -->
          <div class="space-y-1">
            <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors line-clamp-1">
              {{ themeStore.locale === 'bn' ? item.name_bn : (item.name_en || item.name_bn) }}
            </h3>
            <p class="text-xs font-bold text-[#D4AF37] line-clamp-1">
              {{ themeStore.locale === 'bn' ? item.title_bn : (item.title_en || item.title_bn) }}
            </p>
            <p class="text-[11px] text-[var(--text-muted)] flex items-center gap-1.5 pt-0.5">
              <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="22" x2="9" y2="22.01"/><line x1="15" y1="22" x2="15" y2="22.01"/></svg>
              <span class="truncate">{{ item.organization || 'ইমিশা একাডেমি ফ্যাকাল্টি' }}</span>
            </p>
          </div>

          <!-- Bio Preview -->
          <p class="text-xs text-[var(--text-secondary)] line-clamp-2 leading-relaxed bg-[var(--bg-elevated)] p-3 rounded-xl border border-[var(--border-subtle)]">
            {{ themeStore.locale === 'bn' ? (item.bio_bn || item.bio_en || 'অভিজ্ঞ ট্রেইনার ও ক্যারিয়ার মেন্টর।') : (item.bio_en || item.bio_bn || 'Experienced faculty & mentor.') }}
          </p>

          <!-- Metrics Row (Experience & Students) -->
          <div class="grid grid-cols-2 gap-2 pt-1 text-[11px]">
            <div class="p-2 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)]">
              <span class="text-[var(--text-muted)] block text-[10px]">{{ themeStore.locale === 'bn' ? 'অভিজ্ঞতা' : 'Experience' }}</span>
              <span class="font-bold text-[var(--text-primary)]">{{ item.experience_years || '—' }}</span>
            </div>
            <div class="p-2 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)]">
              <span class="text-[var(--text-muted)] block text-[10px]">{{ themeStore.locale === 'bn' ? 'প্রশিক্ষিত ছাত্র' : 'Students' }}</span>
              <span class="font-bold text-sky-400">{{ (item.total_students || 0).toLocaleString() }}+ জন</span>
            </div>
          </div>

          <!-- Assigned Courses Chips -->
          <div class="space-y-1.5 pt-1">
            <span class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider block">
              {{ themeStore.locale === 'bn' ? 'অ্যাসাইনকৃত কোর্সসমূহ:' : 'Assigned Courses:' }} ({{ item.assigned_courses?.length || 0 }})
            </span>
            <div class="flex flex-wrap gap-1.5 max-h-16 overflow-y-auto">
              <span
                v-if="!item.assigned_courses || item.assigned_courses.length === 0"
                class="text-[11px] text-[var(--text-muted)] italic"
              >
                {{ themeStore.locale === 'bn' ? 'কোনো কোর্স লিঙ্ক করা নেই' : 'No courses linked yet' }}
              </span>
              <span
                v-for="c in item.assigned_courses"
                :key="c.id"
                class="px-2 py-0.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[10px] font-semibold text-[var(--text-secondary)] truncate max-w-[200px]"
              >
                {{ themeStore.locale === "bn" ? c.title_bn : (c.title_en || c.title_bn) }}
              </span>
            </div>
          </div>

          <!-- Social Links -->
          <div class="flex items-center gap-2 pt-2 border-t border-[var(--border-subtle)] text-xs">
            <a
              v-if="item.linkedin_url"
              :href="item.linkedin_url"
              target="_blank"
              class="p-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/20 text-sky-400 hover:text-[#D4AF37] transition-all"
              title="LinkedIn Profile"
            >
              LinkedIn
            </a>
            <a
              v-if="item.facebook_url"
              :href="item.facebook_url"
              target="_blank"
              class="p-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/20 text-blue-400 hover:text-[#D4AF37] transition-all"
              title="Facebook"
            >
              Facebook
            </a>
            <a
              v-if="item.github_url"
              :href="item.github_url"
              target="_blank"
              class="p-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/20 text-slate-300 hover:text-[#D4AF37] transition-all"
              title="Portfolio / Website"
            >
              Portfolio
            </a>
          </div>
        </div>

        <!-- Card Footer Action Toolbar -->
        <div class="p-4 bg-[var(--bg-elevated)] border-t border-[var(--border-subtle)] flex items-center justify-between gap-2">
          <button
            type="button"
            @click="toggleFeatured(item)"
            :class="[
              'px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5',
              item.is_featured ? 'bg-[#D4AF37]/20 text-[#D4AF37] border border-[#D4AF37]/40' : 'bg-[var(--bg-surface)] text-[var(--text-muted)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'
            ]"
            :title="item.is_featured ? 'ফিচার্ড থেকে আন-ফিচার্ড করুন' : 'ফিচার্ড মেন্টর হিসেবে সেট করুন'"
          >
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <span class="text-[11px]">{{ item.is_featured ? (themeStore.locale === 'bn' ? 'ফিচার্ড' : 'Featured') : (themeStore.locale === 'bn' ? 'ফিচার করুন' : 'Make Featured') }}</span>
          </button>

          <div class="flex items-center gap-1.5">
            <button
              type="button"
              @click="openEditModal(item)"
              class="px-3 py-1.5 rounded-xl bg-[#D4AF37]/15 hover:bg-[#D4AF37]/30 text-[#D4AF37] font-bold text-xs border border-[#D4AF37]/30 transition-all cursor-pointer flex items-center gap-1"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'সম্পাদনা' : 'Edit' }}</span>
            </button>

            <button
              type="button"
              @click="openDeleteModal(item)"
              class="p-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 transition-all cursor-pointer touch-target flex items-center justify-center"
              :title="themeStore.locale === 'bn' ? 'মুছে ফেলুন' : 'Delete'"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- 7. Pagination -->
    <div v-if="pagination.last_page > 1" class="flex items-center justify-center gap-2 pt-4">
      <button
        v-for="p in pagination.last_page"
        :key="p"
        type="button"
        @click="fetchInstructors(p)"
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
    <!-- 8. COMPREHENSIVE MULTI-TAB MENTOR CREATE / EDIT MODAL                     -->
    <!-- ========================================================================= -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/80 backdrop-blur-sm overflow-y-auto"
    >
      <div class="relative w-full max-w-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl shadow-2xl overflow-hidden my-8 max-h-[90vh] flex flex-col">
        
        <!-- Modal Top Header -->
        <div class="px-6 py-4 border-b border-[var(--border-subtle)] bg-[var(--bg-elevated)] flex items-center justify-between shrink-0">
          <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <div>
              <h2 class="text-base sm:text-lg font-black text-[var(--text-primary)]">
                {{ editingId ? (themeStore.locale === 'bn' ? 'মেন্টর প্রোফাইল সম্পাদনা করুন' : 'Edit Mentor Profile') : (themeStore.locale === 'bn' ? 'নতুন মেন্টর যুক্ত করুন' : 'Add New Mentor') }}
              </h2>
              <p class="text-[11px] text-[var(--text-muted)]">
                {{ themeStore.locale === 'bn' ? 'ফ্যাকাল্টির নাম, পদবি, সোশ্যাল লিংক ও কোর্স অ্যাসাইনমেন্ট কনফিগার করুন।' : 'Set trainer credentials, experience, links and course links.' }}
              </p>
            </div>
          </div>
          <button
            type="button"
            @click="showModal = false"
            class="p-2 rounded-xl text-[var(--text-muted)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-surface)] transition-all cursor-pointer"
          ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>

        <!-- Tab Bar -->
        <div class="px-6 border-b border-[var(--border-subtle)] bg-[var(--bg-surface)] flex items-center gap-2 overflow-x-auto shrink-0">
          <button
            v-for="t in editorTabs"
            :key="t.id"
            type="button"
            @click="activeTab = t.id"
            :class="[
              'py-3 px-4 text-xs font-bold border-b-2 whitespace-nowrap transition-all cursor-pointer flex items-center gap-1.5',
              activeTab === t.id
                ? 'border-[#D4AF37] text-[#D4AF37]'
                : 'border-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
            ]"
          >
            <span>{{ t.icon }}</span>
            <span>{{ t.label }}</span>
          </button>
        </div>

        <!-- Form Body Scrollable Area -->
        <form @submit.prevent="saveInstructor" class="p-6 overflow-y-auto space-y-5 grow">
          
          <!-- TAB 1: BASIC INFO & DESIGNATION -->
          <div v-show="activeTab === 'basic'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">মেন্টরের পূর্ণ নাম (বাংলা) *</label>
                <input
                  v-model="form.name_bn"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37] focus:outline-none"
                  placeholder="যেমন: তানভীর রহমান"
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">Mentor Full Name (English) *</label>
                <input
                  v-model="form.name_en"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37] focus:outline-none"
                  placeholder="e.g. Tanvir Rahman"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">পদবি ও স্পেশালাইজেশন (বাংলা) *</label>
                <input
                  v-model="form.title_bn"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37] focus:outline-none"
                  placeholder="যেমন: লিড এভিয়েশন ও GDS স্পেশালিস্ট"
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">Designation / Title (English) *</label>
                <input
                  v-model="form.title_en"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37] focus:outline-none"
                  placeholder="e.g. Lead Aviation & GDS Specialist"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">কর্মস্থল / প্রতিষ্ঠান (Organization)</label>
                <input
                  v-model="form.organization"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="যেমন: ইমিশা একাডেমি ও ট্রাভেল অপারেশনস টিম"
                />
              </div>

              <div class="space-y-1.5">
                <label class="text-xs font-bold text-[var(--text-secondary)]">কাজের অভিজ্ঞতা (Experience)</label>
                <input
                  v-model="form.experience_years"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                  placeholder="যেমন: ১০+ বছর / 10+ Years"
                />
              </div>
            </div>
          </div>

          <!-- TAB 2: AVATAR & SOCIAL LINKS -->
          <div v-show="activeTab === 'avatar'" class="space-y-4">
            <div class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] flex flex-col sm:flex-row items-center gap-5">
              <div class="w-20 h-20 rounded-2xl overflow-hidden ring-2 ring-[#D4AF37]/40 shrink-0 bg-slate-900 flex items-center justify-center">
                <img
                  v-if="form.avatar"
                  :src="form.avatar"
                  alt="Preview"
                  class="w-full h-full object-cover"
                />
                <span v-else class="text-[var(--text-muted)]"><svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
              </div>

              <div class="space-y-1.5 grow w-full">
                <label class="text-xs font-bold text-[var(--text-primary)]">প্রোফাইল ছবি / অবতার ইমেজ URL (Avatar URL)</label>
                <input
                  v-model="form.avatar"
                  type="text"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37]"
                  placeholder="https://images.unsplash.com/photo-..."
                />
                <p class="text-[11px] text-[var(--text-muted)]">সরাসরি যেকোনো ইমেজ URL দিন (উদা: Unsplash বা ক্লাউড স্টোরেজ লিংক)।</p>
              </div>
            </div>

            <div class="space-y-3 pt-2">
              <h3 class="text-xs font-bold text-[var(--text-primary)] flex items-center gap-2">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                <span>সোশ্যাল ও প্রফেশনাল প্রোফাইল লিংকসমূহ</span>
              </h3>

              <div class="space-y-2.5">
                <div class="space-y-1">
                  <label class="text-[11px] font-bold text-[var(--text-secondary)]">LinkedIn Profile URL</label>
                  <input
                    v-model="form.linkedin_url"
                    type="text"
                    class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="https://linkedin.com/in/username"
                  />
                </div>

                <div class="space-y-1">
                  <label class="text-[11px] font-bold text-[var(--text-secondary)]">Facebook Profile / Page URL</label>
                  <input
                    v-model="form.facebook_url"
                    type="text"
                    class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="https://facebook.com/username"
                  />
                </div>

                <div class="space-y-1">
                  <label class="text-[11px] font-bold text-[var(--text-secondary)]">GitHub / Portfolio Website URL</label>
                  <input
                    v-model="form.github_url"
                    type="text"
                    class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]"
                    placeholder="https://github.com/username or https://myportfolio.com"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 3: BIOGRAPHY & DETAILS -->
          <div v-show="activeTab === 'bio'" class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-secondary)]">মেন্টরের বিস্তারিত পরিচিতি ও ব্যাকগ্রাউন্ড (বাংলা বায়ো)</label>
              <textarea
                v-model="form.bio_bn"
                rows="4"
                class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37]"
                placeholder="১০+ বছরের অভিজ্ঞতা সম্বলিত সার্টিফাইড Sabre ও Galileo জিডিএস ট্রেইনার। দেশি ও বিদেশি ট্রাভেল এজেন্সিতে সরাসরি কাজের অভিজ্ঞতা রয়েছে..."
              ></textarea>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-secondary)]">Mentor Professional Biography (English)</label>
              <textarea
                v-model="form.bio_en"
                rows="4"
                class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:border-[#D4AF37]"
                placeholder="Certified Sabre & Galileo trainer with 10+ years of aviation leadership. Expert in fare calculation and visa consultancy..."
              ></textarea>
            </div>
          </div>

          <!-- TAB 4: METRICS & COURSE ASSIGNMENTS -->
          <div v-show="activeTab === 'courses'" class="space-y-5">
            <!-- Metrics (Rating, Students, Featured) -->
            <div class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="space-y-1">
                <label class="text-[11px] font-bold text-[var(--text-secondary)]">গড় রেটিং (Rating)</label>
                <input
                  v-model.number="form.rating"
                  type="number"
                  step="0.01"
                  min="0"
                  max="5"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold"
                  placeholder="5.00"
                />
              </div>

              <div class="space-y-1">
                <label class="text-[11px] font-bold text-[var(--text-secondary)]">প্রশিক্ষিত শিক্ষার্থী সংখ্যা</label>
                <input
                  v-model.number="form.total_students"
                  type="number"
                  min="0"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold"
                  placeholder="500"
                />
              </div>

              <div class="flex items-center gap-2 pt-5">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-[var(--text-primary)]">
                  <input type="checkbox" v-model="form.is_featured" class="rounded text-[#D4AF37]" />
                  <span>ফিচার্ড মেন্টর হিসেবে হাইলাইট করুন</span>
                </label>
              </div>
            </div>

            <!-- Course Multi-Select Assignment -->
            <div class="p-5 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-3">
              <div class="flex items-center justify-between">
                <label class="text-xs font-bold text-[var(--text-primary)] flex items-center gap-2">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                  <span>অ্যাসাইনকৃত কোর্সসমূহ নির্বাচন করুন (Assigned Courses)</span>
                </label>
                <span class="text-[11px] text-[#D4AF37] font-bold">
                  {{ form.course_ids.length }} {{ themeStore.locale === 'bn' ? 'টি নির্বাচিত' : 'Selected' }}
                </span>
              </div>

              <div v-if="availableCourses.length === 0" class="text-xs text-[var(--text-muted)] py-3 text-center">
                কোনো কোর্স পাওয়া যায়নি।
              </div>

              <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-56 overflow-y-auto pr-1">
                <label
                  v-for="crs in availableCourses"
                  :key="crs.id"
                  :class="[
                    'p-2.5 rounded-xl border flex items-center gap-2.5 cursor-pointer transition-all text-xs',
                    form.course_ids.includes(crs.id)
                      ? 'bg-[#D4AF37]/15 border-[#D4AF37]/60 text-[var(--text-primary)] font-bold'
                      : 'bg-[var(--bg-surface)] border-[var(--border-subtle)] text-[var(--text-secondary)] hover:bg-[var(--bg-elevated)]'
                  ]"
                >
                  <input
                    type="checkbox"
                    :value="crs.id"
                    v-model="form.course_ids"
                    class="rounded text-[#D4AF37]"
                  />
                  <span class="truncate">{{ themeStore.locale === 'bn' ? crs.title_bn : (crs.title_en || crs.title_bn) }}</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Modal Action Bottom Bar -->
          <div class="pt-4 border-t border-[var(--border-subtle)] flex items-center justify-end gap-3 shrink-0">
            <button
              type="button"
              @click="showModal = false"
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
              <span>{{ saving ? (themeStore.locale === 'bn' ? 'সংরক্ষণ হচ্ছে...' : 'Saving...') : (editingId ? (themeStore.locale === 'bn' ? 'আপডেট সম্পন্ন করুন' : 'Update Mentor') : (themeStore.locale === 'bn' ? 'মেন্টর তৈরি করুন' : 'Create Mentor')) }}</span>
            </button>
          </div>

        </form>
      </div>
    </div>

    <!-- 9. DELETE CONFIRMATION MODAL -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 z-60 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300"
    >
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl relative overflow-hidden animate-in fade-in zoom-in-95">
        
        <!-- Danger Glow Accent -->
        <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 flex items-center justify-center mx-auto shadow-inner">
          <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>

        <div class="text-center space-y-2">
          <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'মেন্টর প্রোফাইল মুছে ফেলতে চান?' : 'Remove Mentor Profile?' }}
          </h3>
          <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
            <span class="font-bold text-[var(--text-primary)]">"{{ mentorToDelete?.name_bn || mentorToDelete?.name_en }}"</span>
            {{ themeStore.locale === 'bn' ? 'এর প্রোফাইল ও তথ্য স্থায়ীভাবে মুছে ফেলা হবে।' : 'will be permanently removed.' }}
          </p>
          <p v-if="mentorToDelete?.assigned_courses?.length" class="text-[11px] text-amber-500 bg-amber-500/10 border border-amber-500/20 px-3 py-2 rounded-xl mt-2 text-left">
            ⚠️ {{ themeStore.locale === 'bn' ? `এই মেন্টর ${mentorToDelete.assigned_courses.length}টি কোর্সে যুক্ত রয়েছেন। ডিলিট করলে কোর্সগুলো থেকে মেন্টর আন-অ্যাসাইন হয়ে যাবে।` : `This mentor is assigned to ${mentorToDelete.assigned_courses.length} course(s). They will be unassigned automatically.` }}
          </p>
        </div>

        <div class="flex items-center justify-center gap-3 pt-2">
          <button
            type="button"
            :disabled="isDeleting"
            @click="showDeleteModal = false; mentorToDelete = null"
            class="w-1/2 px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer disabled:opacity-50"
          >
            {{ themeStore.locale === 'bn' ? 'বাতিল' : 'Cancel' }}
          </button>
          <button
            type="button"
            :disabled="isDeleting"
            @click="executeDeleteMentor"
            class="w-1/2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/20 transition-all cursor-pointer flex items-center justify-center gap-2 disabled:opacity-50"
          >
            <span v-if="isDeleting" class="inline-block animate-spin w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full"></span>
            <span>{{ isDeleting ? (themeStore.locale === 'bn' ? 'মুছে ফেলা হচ্ছে...' : 'Deleting...') : (themeStore.locale === 'bn' ? 'হ্যাঁ, মুছে ফেলুন' : 'Yes, Delete') }}</span>
          </button>
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
import { onImageError, getInitialsAvatar } from '../../utils/imageFallback';

const themeStore = useThemeStore();
const toastStore = useToastStore();

const loading = ref(true);
const saving = ref(false);
const instructors = ref<any[]>([]);
const availableCourses = ref<any[]>([]);
const searchQuery = ref('');
const featuredFilter = ref('');

const metrics = reactive({
  total_mentors: 0,
  featured_mentors: 0,
  total_students_trained: 0,
  average_rating: 5.0,
});

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 12,
  total: 0,
});

const featuredFilterOptions = computed(() => [
  { value: '', label: themeStore.locale === 'bn' ? 'সকল মেন্টর' : 'All Mentors' },
  { value: 'true', label: themeStore.locale === 'bn' ? 'ফিচার্ড ফ্যাকাল্টি' : 'Featured Only' },
  { value: 'false', label: themeStore.locale === 'bn' ? 'স্ট্যান্ডার্ড' : 'Standard' },
]);

const editorTabs = computed(() => [
  { id: 'basic', label: themeStore.locale === 'bn' ? 'নাম ও পদবি' : 'Basic & Designation', icon: '' },
  { id: 'avatar', label: themeStore.locale === 'bn' ? 'ছবি ও সোশ্যাল লিংক' : 'Avatar & Social', icon: '' },
  { id: 'bio', label: themeStore.locale === 'bn' ? 'বিস্তারিত পরিচিতি' : 'Biography', icon: '' },
  { id: 'courses', label: themeStore.locale === 'bn' ? 'কোর্স ও মেট্রিক্স' : 'Courses & Metrics', icon: '' },
]);

const activeTab = ref('basic');
const showModal = ref(false);
const editingId = ref<number | null>(null);

// Delete Modal State
const showDeleteModal = ref(false);
const mentorToDelete = ref<any>(null);
const isDeleting = ref(false);

const getDefaultForm = () => ({
  name_bn: '',
  name_en: '',
  title_bn: '',
  title_en: '',
  organization: 'ইমিশা একাডেমি ও ট্রাভেল অপারেশনস টিম',
  experience_years: '',
  bio_bn: '',
  bio_en: '',
  avatar: '',
  linkedin_url: '',
  facebook_url: '',
  github_url: '',
  rating: 5.0,
  total_students: 0,
  is_featured: false,
  course_ids: [] as number[],
});

const form = reactive(getDefaultForm());

let debounceTimer: any = null;
const debounceFetch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchInstructors(1);
  }, 350);
};

const fetchInstructors = async (page = 1) => {
  loading.value = true;
  try {
    const params: any = { page };
    if (searchQuery.value) params.search = searchQuery.value;
    if (featuredFilter.value !== '') params.is_featured = featuredFilter.value;

    const res = await apiClient.get('/admin/instructors', { params });
    if (res.data?.data) {
      instructors.value = res.data.data.instructors || [];
      if (res.data.data.pagination) {
        pagination.current_page = res.data.data.pagination.current_page;
        pagination.last_page = res.data.data.pagination.last_page;
        pagination.per_page = res.data.data.pagination.per_page;
        pagination.total = res.data.data.pagination.total;
      }
      if (res.data.data.metrics) {
        Object.assign(metrics, res.data.data.metrics);
      }
      if (res.data.data.available_courses) {
        availableCourses.value = res.data.data.available_courses;
      }
    }
  } catch (err) {
    toastStore.error(themeStore.locale === 'bn' ? 'মেন্টর তালিকা লোড করা যায়নি' : 'Failed to fetch instructors');
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  editingId.value = null;
  activeTab.value = 'basic';
  Object.assign(form, getDefaultForm());
  showModal.value = true;
};

const openEditModal = (item: any) => {
  editingId.value = item.id;
  activeTab.value = 'basic';

  const assignedCourseIds = Array.isArray(item.assigned_courses)
    ? item.assigned_courses.map((c: any) => c.id)
    : [];

  Object.assign(form, {
    name_bn: item.name_bn || '',
    name_en: item.name_en || '',
    title_bn: item.title_bn || '',
    title_en: item.title_en || '',
    organization: item.organization || 'ইমিশা একাডেমি ও ট্রাভেল অপারেশনস টিম',
    experience_years: item.experience_years || '',
    bio_bn: item.bio_bn || '',
    bio_en: item.bio_en || '',
    avatar: item.avatar || '',
    linkedin_url: item.linkedin_url || '',
    facebook_url: item.facebook_url || '',
    github_url: item.github_url || '',
    rating: Number(item.rating) || 5.0,
    total_students: Number(item.total_students) || 0,
    is_featured: Boolean(item.is_featured),
    course_ids: assignedCourseIds,
  });

  showModal.value = true;
};

const toggleFeatured = async (item: any) => {
  try {
    const newStatus = !item.is_featured;
    await apiClient.put(`/admin/instructors/${item.id}`, {
      is_featured: newStatus,
    });
    item.is_featured = newStatus;
    if (newStatus) {
      metrics.featured_mentors++;
    } else {
      metrics.featured_mentors = Math.max(0, metrics.featured_mentors - 1);
    }
    toastStore.success(
      themeStore.locale === 'bn'
        ? (newStatus ? 'ফিচার্ড মেন্টর হিসেবে তালিকাভুক্ত হয়েছে' : 'আন-ফিচার্ড করা হয়েছে')
        : (newStatus ? 'Marked as featured' : 'Unmarked from featured')
    );
  } catch (err) {
    toastStore.error(themeStore.locale === 'bn' ? 'স্ট্যাটাস পরিবর্তন ব্যর্থ হয়েছে' : 'Failed to update status');
  }
};

const saveInstructor = async () => {
  saving.value = true;
  try {
    const payload = {
      name_bn: form.name_bn?.trim() || '',
      name_en: form.name_en?.trim() || '',
      title_bn: form.title_bn?.trim() || '',
      title_en: form.title_en?.trim() || '',
      organization: form.organization?.trim() || 'ইমিশা একাডেমি ও ট্রাভেল অপারেশনস টিম',
      experience_years: form.experience_years?.trim() || '',
      bio_bn: form.bio_bn?.trim() || null,
      bio_en: form.bio_en?.trim() || null,
      avatar: form.avatar?.trim() || null,
      linkedin_url: form.linkedin_url?.trim() || null,
      facebook_url: form.facebook_url?.trim() || null,
      github_url: form.github_url?.trim() || null,
      rating: Number(form.rating) || 5.0,
      total_students: Number(form.total_students) || 0,
      is_featured: Boolean(form.is_featured),
      course_ids: form.course_ids || [],
    };

    if (editingId.value) {
      await apiClient.put(`/admin/instructors/${editingId.value}`, payload);
      toastStore.success(themeStore.locale === 'bn' ? 'মেন্টর প্রোফাইল সফলভাবে আপডেট করা হয়েছে' : 'Mentor profile updated successfully');
    } else {
      await apiClient.post('/admin/instructors', payload);
      toastStore.success(themeStore.locale === 'bn' ? 'নতুন মেন্টর সফলভাবে যুক্ত হয়েছে' : 'Mentor profile created successfully');
    }

    showModal.value = false;
    fetchInstructors(pagination.current_page);
  } catch (err: any) {
    const msg = err.response?.data?.message || (themeStore.locale === 'bn' ? 'সংরক্ষণ ব্যর্থ হয়েছে' : 'Failed to save mentor');
    toastStore.error(msg);
  } finally {
    saving.value = false;
  }
};

const openDeleteModal = (item: any) => {
  mentorToDelete.value = item;
  showDeleteModal.value = true;
};

const executeDeleteMentor = async () => {
  if (!mentorToDelete.value) return;
  const target = mentorToDelete.value;
  isDeleting.value = true;

  try {
    const res = await apiClient.delete(`/admin/instructors/${target.id}`);
    if (res.data?.status === 'success' || res.status === 200) {
      toastStore.success(
        themeStore.locale === 'bn'
          ? `"${target.name_bn || target.name_en}" মেন্টর সফলভাবে মুছে ফেলা হয়েছে`
          : `Mentor "${target.name_en || target.name_bn}" removed successfully`
      );

      // Optimistic UI update
      instructors.value = instructors.value.filter((i) => i.id !== target.id);
      showDeleteModal.value = false;
      mentorToDelete.value = null;

      // Handle pagination fallback if page became empty
      const targetPage = instructors.value.length === 0 && pagination.current_page > 1
        ? pagination.current_page - 1
        : pagination.current_page;

      fetchInstructors(targetPage);
    } else {
      throw new Error(res.data?.message || 'Failed to delete');
    }
  } catch (err: any) {
    const errorMsg = err.response?.data?.message || (themeStore.locale === 'bn' ? 'মেন্টর মুছে ফেলা ব্যর্থ হয়েছে' : 'Failed to remove mentor');
    toastStore.error(errorMsg);
  } finally {
    isDeleting.value = false;
  }
};

onMounted(() => {
  fetchInstructors(1);
});
</script>
