<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">
          {{ themeStore.locale === 'bn' ? 'নোটিশ বোর্ড ও অ্যানাউন্সমেন্ট ম্যানেজমেন্ট' : 'Notice Board & Announcements Management' }}
        </h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1">
          {{ themeStore.locale === 'bn' ? 'সকল শিক্ষার্থী বা নির্দিষ্ট কোর্সের শিক্ষার্থীদের জন্য নোটিশ প্রকাশ, শিডিউল ও অডিয়েন্স টার্গেট করুন।' : 'Publish, schedule, and target announcements to all students or specific enrolled courses.' }}
        </p>
      </div>

      <div class="flex items-center gap-3">
        <button
          type="button"
          @click="openCreateModal"
          class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all inline-flex items-center gap-2 cursor-pointer touch-target shadow-xs"
        >
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
          <span>{{ themeStore.locale === 'bn' ? '+ নতুন নোটিশ তৈরি করুন' : '+ Create Announcement' }}</span>
        </button>
      </div>
    </div>

    <!-- Metrics Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xs">
      <div class="space-y-1 p-2 text-center sm:text-left">
        <span class="text-[11px] text-[var(--text-muted)] font-medium">{{ themeStore.locale === 'bn' ? 'মোট নোটিশ' : 'Total Notices' }}</span>
        <p class="text-xl sm:text-2xl font-black text-[var(--text-primary)]">{{ formatNumber(noticesData?.total || 0, themeStore.locale) }}</p>
      </div>
      <div class="space-y-1 p-2 text-center sm:text-left border-l border-[var(--border-subtle)]">
        <span class="text-[11px] text-emerald-500 font-medium">{{ themeStore.locale === 'bn' ? 'প্রকাশিত (Published)' : 'Published' }}</span>
        <p class="text-xl sm:text-2xl font-black text-emerald-500">{{ formatNumber(publishedCount, themeStore.locale) }}</p>
      </div>
      <div class="space-y-1 p-2 text-center sm:text-left border-t sm:border-t-0 sm:border-l border-[var(--border-subtle)]">
        <span class="text-[11px] text-amber-500 font-medium">{{ themeStore.locale === 'bn' ? 'ড্রাফট (Draft)' : 'Drafts' }}</span>
        <p class="text-xl sm:text-2xl font-black text-amber-500">{{ formatNumber(draftCount, themeStore.locale) }}</p>
      </div>
      <div class="space-y-1 p-2 text-center sm:text-left border-t sm:border-t-0 border-l border-[var(--border-subtle)]">
        <span class="text-[11px] text-sky-400 font-medium">{{ themeStore.locale === 'bn' ? 'কোর্স টার্গেটেড' : 'Course Targeted' }}</span>
        <p class="text-xl sm:text-2xl font-black text-sky-400">{{ formatNumber(courseTargetedCount, themeStore.locale) }}</p>
      </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-[var(--bg-surface)] border border-[var(--border-subtle)] p-4 rounded-2xl shadow-xs">
      <div class="w-full sm:w-80">
        <input
          v-model="searchQuery"
          @input="debounceFetchNotices"
          type="text"
          :placeholder="themeStore.locale === 'bn' ? 'নোটিশ শিরোনাম বা বিবরণ খুঁজুন...' : 'Search notices...'"
          class="w-full px-4 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] transition-colors"
        />
      </div>

      <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
        <select
          v-model="statusFilter"
          @change="fetchNotices"
          class="px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
        >
          <option value="">{{ themeStore.locale === 'bn' ? 'সকল স্ট্যাটাস' : 'All Status' }}</option>
          <option value="published">{{ themeStore.locale === 'bn' ? 'প্রকাশিত' : 'Published' }}</option>
          <option value="draft">{{ themeStore.locale === 'bn' ? 'ড্রাফট' : 'Draft' }}</option>
          <option value="scheduled">{{ themeStore.locale === 'bn' ? 'শিডিউলড' : 'Scheduled' }}</option>
        </select>

        <select
          v-model="priorityFilter"
          @change="fetchNotices"
          class="px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
        >
          <option value="">{{ themeStore.locale === 'bn' ? 'সকল প্রায়োরিটি' : 'All Priorities' }}</option>
          <option value="normal">{{ themeStore.locale === 'bn' ? 'সাধারণ (Normal)' : 'Normal' }}</option>
          <option value="important">{{ themeStore.locale === 'bn' ? 'জরুরি (Important)' : 'Important' }}</option>
          <option value="urgent">{{ themeStore.locale === 'bn' ? 'অতি জরুরি (Urgent)' : 'Urgent' }}</option>
        </select>

        <select
          v-model="visibilityFilter"
          @change="fetchNotices"
          class="px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
        >
          <option value="">{{ themeStore.locale === 'bn' ? 'সকল অডিয়েন্স' : 'All Audiences' }}</option>
          <option value="all_students">{{ themeStore.locale === 'bn' ? 'সকল শিক্ষার্থী' : 'All Students' }}</option>
          <option value="course">{{ themeStore.locale === 'bn' ? 'কোর্স নির্দিষ্ট' : 'Course Specific' }}</option>
        </select>
      </div>
    </div>

    <!-- Loading Skeleton -->
    <div v-if="loading" class="space-y-3">
      <div v-for="i in 4" :key="i" class="h-24 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="notices.length === 0" class="text-center py-16 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-8 space-y-3">
      <div class="w-12 h-12 rounded-2xl bg-[var(--bg-elevated)] text-[var(--brand-gold)] mx-auto flex items-center justify-center">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      </div>
      <p class="text-sm font-bold text-[var(--text-primary)]">{{ themeStore.locale === 'bn' ? 'কোনো নোটিশ পাওয়া যায়নি' : 'No notices found' }}</p>
      <p class="text-xs text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'নতুন নোটিশ তৈরি করতে "+ নতুন নোটিশ তৈরি করুন" বাটনে ক্লিক করুন।' : 'Click "+ Create Announcement" to publish your first notice.' }}</p>
    </div>

    <!-- Notices Table -->
    <div v-else class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-2xl overflow-hidden shadow-sm">
      <div class="table-responsive-container">
        <table class="w-full text-left text-xs min-w-[950px]">
          <thead class="bg-[var(--bg-elevated)] border-b border-[var(--border-subtle)] text-[var(--text-secondary)] uppercase tracking-wider">
            <tr>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'নোটিশ শিরোনাম' : 'Notice Details' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'ধরণ ও প্রায়োরিটি' : 'Type & Priority' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'টার্গেট অডিয়েন্স' : 'Audience / Courses' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'পড়া হয়েছে (Reads)' : 'Reads' }}</th>
              <th class="py-4 px-6">{{ $t('common.status') }}</th>
              <th class="py-4 px-6 text-right">{{ $t('common.actions') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-subtle)]">
            <tr v-for="notice in notices" :key="notice.id" class="hover:bg-[var(--bg-elevated)]/40 transition-colors">
              <!-- Title & Excerpt -->
              <td class="py-4 px-6 max-w-xs">
                <div class="font-bold text-[var(--text-primary)] text-sm line-clamp-1">
                  {{ notice.title }}
                </div>
                <div class="text-[11px] text-[var(--text-secondary)] line-clamp-1 mt-0.5">
                  {{ notice.excerpt || notice.content }}
                </div>
                <div class="text-[10px] text-[var(--text-muted)] mt-1 flex items-center gap-1.5">
                  <span>{{ formatDate(notice.published_at || notice.created_at) }}</span>
                  <span v-if="notice.expires_at" class="text-rose-400">• Exp: {{ formatDate(notice.expires_at) }}</span>
                </div>
              </td>

              <!-- Type & Priority -->
              <td class="py-4 px-6 whitespace-nowrap">
                <div class="space-y-1">
                  <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase" :class="getTypeBadgeClass(notice.notice_type)">
                    {{ notice.notice_type }}
                  </span>
                  <div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold" :class="getPriorityBadgeClass(notice.priority)">
                      <span class="w-1.5 h-1.5 rounded-full" :class="getPriorityDotClass(notice.priority)"></span>
                      <span class="uppercase">{{ notice.priority }}</span>
                    </span>
                  </div>
                </div>
              </td>

              <!-- Audience & Courses -->
              <td class="py-4 px-6">
                <div v-if="notice.visibility === 'all_students'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)]">
                  <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                  <span>{{ themeStore.locale === 'bn' ? 'সকল শিক্ষার্থী' : 'All Students' }}</span>
                </div>
                <div v-else-if="notice.courses && notice.courses.length > 0" class="space-y-1">
                  <span class="text-[10px] text-[var(--text-muted)] font-medium">{{ notice.courses.length }} {{ themeStore.locale === 'bn' ? 'টি কোর্স টার্গেটেড' : 'Courses Targeted' }}:</span>
                  <div class="flex flex-wrap gap-1">
                    <span
                      v-for="c in notice.courses.slice(0, 2)"
                      :key="c.id"
                      class="px-2 py-0.5 rounded-md bg-sky-500/10 border border-sky-500/20 text-sky-600 dark:text-sky-400 text-[10px] font-semibold truncate max-w-[150px]"
                    >
                      {{ themeStore.locale === 'bn' ? c.title_bn : c.title_en }}
                    </span>
                    <span v-if="notice.courses.length > 2" class="text-[10px] text-[var(--text-muted)] self-center font-bold">
                      +{{ notice.courses.length - 2 }} more
                    </span>
                  </div>
                </div>
                <div v-else class="text-[11px] text-[var(--text-muted)] italic">
                  {{ themeStore.locale === 'bn' ? 'কোর্স নির্দিষ্ট' : 'Course specific' }}
                </div>
              </td>

              <!-- Reads Count -->
              <td class="py-4 px-6 whitespace-nowrap">
                <span class="px-2.5 py-1 rounded-md bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-mono text-[11px] font-bold">
                  {{ formatNumber(notice.reads_count || 0, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'জন পড়েছেন' : 'reads' }}
                </span>
              </td>

              <!-- Status -->
              <td class="py-4 px-6 whitespace-nowrap">
                <span v-if="notice.status === 'published'" class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                  {{ themeStore.locale === 'bn' ? 'প্রকাশিত' : 'Published' }}
                </span>
                <span v-else-if="notice.status === 'scheduled'" class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500/10 text-sky-500 border border-sky-500/20">
                  {{ themeStore.locale === 'bn' ? 'শিডিউলড' : 'Scheduled' }}
                </span>
                <span v-else class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                  {{ themeStore.locale === 'bn' ? 'ড্রাফট' : 'Draft' }}
                </span>
              </td>

              <!-- Actions -->
              <td class="py-4 px-6 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-1.5">
                  <!-- Toggle Publish Button -->
                  <button
                    type="button"
                    @click="togglePublishNotice(notice)"
                    :class="[
                      'px-2.5 py-1.5 rounded-lg text-[11px] font-bold border transition-colors cursor-pointer',
                      notice.status === 'published' ? 'bg-amber-500/10 hover:bg-amber-500/20 text-amber-500 border-amber-500/20' : 'bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-500 border-emerald-500/20'
                    ]"
                  >
                    {{ notice.status === 'published' ? (themeStore.locale === 'bn' ? 'আনপাবলিশ' : 'Unpublish') : (themeStore.locale === 'bn' ? 'পাবলিশ' : 'Publish') }}
                  </button>

                  <!-- Preview Button -->
                  <button
                    type="button"
                    @click="openPreviewModal(notice)"
                    class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-[11px] font-medium border border-[var(--border-subtle)] transition-colors cursor-pointer"
                    title="Preview Notice"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>

                  <!-- Edit Button -->
                  <button
                    type="button"
                    @click="openEditModal(notice)"
                    class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/15 hover:border-[#D4AF37]/50 text-[var(--brand-gold)] text-[11px] font-bold border border-[var(--border-subtle)] transition-colors cursor-pointer"
                    title="Edit Notice"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                  </button>

                  <!-- Delete Button -->
                  <button
                    type="button"
                    @click="confirmDeleteNotice(notice)"
                    class="px-2 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 text-[11px] font-bold border border-rose-500/20 transition-colors cursor-pointer"
                    title="Delete Notice"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- CREATE / EDIT NOTICE MODAL -->
    <!-- ========================================================================= -->
    <div v-if="showNoticeModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-3xl w-full max-h-[92dvh] overflow-y-auto p-5 sm:p-8 relative shadow-2xl space-y-6 safe-bottom">
        
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-4">
          <div>
            <h2 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">
              {{ isEditing ? (themeStore.locale === 'bn' ? 'নোটিশ তথ্য আপডেট করুন' : 'Edit Notice') : (themeStore.locale === 'bn' ? 'নতুন নোটিশ তৈরি করুন' : 'Create New Notice') }}
            </h2>
            <p class="text-xs text-[var(--text-secondary)] mt-0.5">
              {{ themeStore.locale === 'bn' ? 'শিরোনাম, বিবরণ, প্রায়োরিটি ও অডিয়েন্স টার্গেটিং নির্ধারণ করুন।' : 'Configure title, content, priority, and course targeting.' }}
            </p>
          </div>
          <button @click="showNoticeModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>

        <form @submit.prevent="submitNoticeForm" class="space-y-4">
          <!-- Title -->
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
              {{ themeStore.locale === 'bn' ? 'নোটিশ শিরোনাম *' : 'Notice Title *' }}
            </label>
            <input
              v-model="noticeForm.title"
              type="text"
              required
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              :placeholder="themeStore.locale === 'bn' ? 'যেমন: আগামী শুক্রবারে বিশেষ Sabre GDS প্র্যাকটিক্যাল ল্যাব ক্লাস' : 'e.g. Special Sabre GDS Practical Lab Class on Friday'"
            />
          </div>

          <!-- Type & Priority & Status -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'নোটিশ ধরণ (Type) *' : 'Notice Type *' }}
              </label>
              <select
                v-model="noticeForm.notice_type"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              >
                <option value="general">General (সাধারণ)</option>
                <option value="course">Course Update (কোর্স আপডেট)</option>
                <option value="important">Important (জরুরি)</option>
                <option value="class">Class Schedule (ক্লাস শিডিউল)</option>
                <option value="exam">Exam & Assessment (পরীক্ষা)</option>
                <option value="assignment">Assignment (অ্যাসাইনমেন্ট)</option>
                <option value="webinar">Webinar & Workshop (ওয়েবিনার)</option>
                <option value="live_session">Live Session (লাইভ সেশন)</option>
                <option value="system">System Notice (সিস্টেম নোটিশ)</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'গুরুত্ব (Priority) *' : 'Priority *' }}
              </label>
              <select
                v-model="noticeForm.priority"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              >
                <option value="normal">Normal (সাধারণ)</option>
                <option value="important">Important (গুরুত্বপূর্ণ)</option>
                <option value="urgent">Urgent (অতি জরুরি)</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'প্রকাশনার স্ট্যাটাস *' : 'Status *' }}
              </label>
              <select
                v-model="noticeForm.status"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              >
                <option value="published">Published (সরাসরি প্রকাশ)</option>
                <option value="draft">Draft (ড্রাফট রাখুন)</option>
                <option value="scheduled">Scheduled (নির্দিষ্ট সময়ে প্রকাশ)</option>
              </select>
            </div>
          </div>

          <!-- Audience Selection -->
          <div class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-3">
            <label class="block text-xs font-bold text-[var(--text-primary)] uppercase">
              {{ themeStore.locale === 'bn' ? 'টার্গেট অডিয়েন্স (কারা দেখতে পাবেন?) *' : 'Target Audience *' }}
            </label>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <label
                :class="[
                  'flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all',
                  noticeForm.visibility === 'all_students' ? 'bg-[#D4AF37]/15 border-[#D4AF37] text-[var(--text-primary)] font-bold' : 'bg-[var(--bg-surface)] border-[var(--border-subtle)] text-[var(--text-secondary)]'
                ]"
              >
                <input
                  type="radio"
                  value="all_students"
                  v-model="noticeForm.visibility"
                  class="accent-[#D4AF37]"
                />
                <span class="text-xs">{{ themeStore.locale === 'bn' ? 'সকল শিক্ষার্থী (All Students)' : 'All Enrolled Students' }}</span>
              </label>

              <label
                :class="[
                  'flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all',
                  noticeForm.visibility === 'multiple_courses' || noticeForm.visibility === 'course' ? 'bg-sky-500/15 border-sky-500 text-[var(--text-primary)] font-bold' : 'bg-[var(--bg-surface)] border-[var(--border-subtle)] text-[var(--text-secondary)]'
                ]"
              >
                <input
                  type="radio"
                  value="multiple_courses"
                  v-model="noticeForm.visibility"
                  class="accent-sky-500"
                />
                <span class="text-xs">{{ themeStore.locale === 'bn' ? 'নির্দিষ্ট কোর্স(সমূহ) এর শিক্ষার্থী' : 'Specific Course(s) Only' }}</span>
              </label>
            </div>

            <!-- Course Multi-Selector if Course Specific -->
            <div v-if="noticeForm.visibility === 'multiple_courses' || noticeForm.visibility === 'course'" class="pt-2 space-y-2">
              <p class="text-[11px] text-[var(--text-muted)] font-medium">
                {{ themeStore.locale === 'bn' ? 'যেসব কোর্সের শিক্ষার্থীরা এই নোটিশটি পাবেন তাদের সিলেক্ট করুন:' : 'Select which courses should receive this notice:' }}
              </p>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]">
                <label
                  v-for="course in availableCourses"
                  :key="course.id"
                  class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-[var(--bg-elevated)] text-xs cursor-pointer"
                >
                  <input
                    type="checkbox"
                    :value="course.id"
                    v-model="noticeForm.course_ids"
                    class="w-4 h-4 rounded accent-[#D4AF37]"
                  />
                  <span class="truncate text-[var(--text-primary)]">{{ themeStore.locale === 'bn' ? course.title_bn : course.title_en }}</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Content (Rich Text / Long Form) -->
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
              {{ themeStore.locale === 'bn' ? 'নোটিশের পূর্ণাঙ্গ বিবরণ *' : 'Notice Content *' }}
            </label>
            <textarea
              v-model="noticeForm.content"
              rows="6"
              required
              class="w-full px-4 py-3 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] leading-relaxed"
              :placeholder="themeStore.locale === 'bn' ? 'নোটিশের বিস্তারিত বিবরণ এখানে লিখুন...' : 'Write complete notice content here...'"
            ></textarea>
          </div>

          <!-- Schedule & Expiry Timestamps -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'প্রকাশনার তারিখ ও সময় (Publish Date)' : 'Publish At' }}
              </label>
              <input
                v-model="noticeForm.publish_at"
                type="datetime-local"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'মেয়াদোত্তীর্ণের তারিখ (Expiry Date - Optional)' : 'Expires At (Optional)' }}
              </label>
              <input
                v-model="noticeForm.expires_at"
                type="datetime-local"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              />
            </div>
          </div>

          <!-- Modal Action Buttons -->
          <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-[var(--border-subtle)]">
            <button
              type="button"
              @click="showNoticeModal = false"
              class="px-4 py-2.5 rounded-xl text-xs font-semibold text-[var(--text-secondary)] hover:text-[var(--text-primary)] touch-target text-center cursor-pointer"
            >
              {{ $t('common.cancel') }}
            </button>
            <button
              type="submit"
              :disabled="submitting"
              class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg transition-all disabled:opacity-50 touch-target cursor-pointer"
            >
              <span v-if="submitting">{{ $t('student.updating') }}</span>
              <span v-else>{{ isEditing ? (themeStore.locale === 'bn' ? 'আপডেট সংরক্ষণ করুন' : 'Save Changes') : (themeStore.locale === 'bn' ? 'নোটিশ প্রকাশ করুন' : 'Publish Notice') }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- PREVIEW NOTICE MODAL -->
    <!-- ========================================================================= -->
    <div v-if="showPreviewModal && previewNotice" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-2xl w-full max-h-[90dvh] overflow-y-auto p-6 sm:p-8 relative shadow-2xl space-y-5 safe-bottom">
        <div class="flex items-start justify-between border-b border-[var(--border-subtle)] pb-4">
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase" :class="getTypeBadgeClass(previewNotice.notice_type)">
                {{ previewNotice.notice_type }}
              </span>
              <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase" :class="getPriorityBadgeClass(previewNotice.priority)">
                {{ previewNotice.priority }}
              </span>
            </div>
            <h2 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">{{ previewNotice.title }}</h2>
            <p class="text-[11px] text-[var(--text-muted)]">Published: {{ formatDate(previewNotice.published_at || previewNotice.created_at) }}</p>
          </div>
          <button @click="showPreviewModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>

        <div class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-4">
          <p class="text-xs sm:text-sm text-[var(--text-primary)] leading-relaxed whitespace-pre-line">
            {{ previewNotice.content }}
          </p>
        </div>

        <div class="flex justify-end pt-2">
          <button
            type="button"
            @click="showPreviewModal = false"
            class="px-5 py-2 rounded-xl bg-[var(--bg-elevated)] text-[var(--text-primary)] font-bold text-xs"
          >
            {{ themeStore.locale === 'bn' ? 'বন্ধ করুন' : 'Close Preview' }}
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
import { formatNumber } from '../../utils/locale';

const themeStore = useThemeStore();
const toastStore = useToastStore();

const loading = ref(true);
const submitting = ref(false);
const notices = ref<any[]>([]);
const noticesData = ref<any>(null);
const availableCourses = ref<any[]>([]);

const searchQuery = ref('');
const statusFilter = ref('');
const priorityFilter = ref('');
const visibilityFilter = ref('');

const showNoticeModal = ref(false);
const showPreviewModal = ref(false);
const isEditing = ref(false);
const selectedNoticeId = ref<number | null>(null);
const previewNotice = ref<any>(null);

const noticeForm = reactive({
  title: '',
  excerpt: '',
  content: '',
  notice_type: 'general',
  priority: 'normal',
  visibility: 'all_students',
  status: 'published',
  course_ids: [] as number[],
  publish_at: '',
  expires_at: '',
});

const publishedCount = computed(() => notices.value.filter(n => n.status === 'published').length);
const draftCount = computed(() => notices.value.filter(n => n.status === 'draft').length);
const courseTargetedCount = computed(() => notices.value.filter(n => n.visibility !== 'all_students').length);

let debounceTimer: any = null;
const debounceFetchNotices = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchNotices();
  }, 300);
};

const fetchNotices = async () => {
  loading.value = true;
  try {
    const params: any = {};
    if (searchQuery.value) params.search = searchQuery.value;
    if (statusFilter.value) params.status = statusFilter.value;
    if (priorityFilter.value) params.priority = priorityFilter.value;
    if (visibilityFilter.value) params.visibility = visibilityFilter.value;

    const res = await apiClient.get('/admin/notices', { params });
    noticesData.value = res.data.data;
    notices.value = res.data.data.data || [];
  } catch (err: any) {
    toastStore.error(themeStore.locale === 'bn' ? 'নোটিশ লোড করা সম্ভব হয়নি।' : 'Failed to fetch notices.');
  } finally {
    loading.value = false;
  }
};

const fetchCourses = async () => {
  try {
    const res = await apiClient.get('/admin/courses');
    if (res.data.status === 'success') {
      const d = res.data.data;
      availableCourses.value = d?.courses || d?.data || (Array.isArray(d) ? d : []);
    }
  } catch (err) {
    console.error(err);
  }
};

const openCreateModal = () => {
  isEditing.value = false;
  selectedNoticeId.value = null;
  noticeForm.title = '';
  noticeForm.excerpt = '';
  noticeForm.content = '';
  noticeForm.notice_type = 'general';
  noticeForm.priority = 'normal';
  noticeForm.visibility = 'all_students';
  noticeForm.status = 'published';
  noticeForm.course_ids = [];
  noticeForm.publish_at = new Date().toISOString().slice(0, 16);
  noticeForm.expires_at = '';
  showNoticeModal.value = true;
};

const openEditModal = (notice: any) => {
  isEditing.value = true;
  selectedNoticeId.value = notice.id;
  noticeForm.title = notice.title;
  noticeForm.excerpt = notice.excerpt || '';
  noticeForm.content = notice.content;
  noticeForm.notice_type = notice.notice_type;
  noticeForm.priority = notice.priority;
  noticeForm.visibility = notice.visibility;
  noticeForm.status = notice.status;
  noticeForm.course_ids = notice.courses ? notice.courses.map((c: any) => c.id) : [];
  noticeForm.publish_at = notice.publish_at ? new Date(notice.publish_at).toISOString().slice(0, 16) : '';
  noticeForm.expires_at = notice.expires_at ? new Date(notice.expires_at).toISOString().slice(0, 16) : '';
  showNoticeModal.value = true;
};

const openPreviewModal = (notice: any) => {
  previewNotice.value = notice;
  showPreviewModal.value = true;
};

const submitNoticeForm = async () => {
  if (!noticeForm.title || !noticeForm.content) {
    toastStore.warning(themeStore.locale === 'bn' ? 'শিরোনাম ও বিবরণ আবশ্যক।' : 'Title and content are required.');
    return;
  }

  submitting.value = true;
  try {
    const payload = { ...noticeForm };
    if (!payload.publish_at) delete (payload as any).publish_at;
    if (!payload.expires_at) delete (payload as any).expires_at;

    if (isEditing.value && selectedNoticeId.value) {
      await apiClient.put(`/admin/notices/${selectedNoticeId.value}`, payload);
      toastStore.success(themeStore.locale === 'bn' ? 'নোটিশ সফলভাবে আপডেট হয়েছে।' : 'Notice updated successfully.');
    } else {
      await apiClient.post('/admin/notices', payload);
      toastStore.success(themeStore.locale === 'bn' ? 'নোটিশ সফলভাবে তৈরি হয়েছে।' : 'Notice created successfully.');
    }

    showNoticeModal.value = false;
    fetchNotices();
  } catch (err: any) {
    toastStore.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'নোটিশ সংরক্ষণ করা যায়নি।' : 'Failed to save notice.'));
  } finally {
    submitting.value = false;
  }
};

const togglePublishNotice = async (notice: any) => {
  try {
    const res = await apiClient.put(`/admin/notices/${notice.id}/toggle-publish`);
    toastStore.success(res.data.message);
    fetchNotices();
  } catch (err: any) {
    toastStore.error(err.response?.data?.message || 'Error updating status');
  }
};

const confirmDeleteNotice = async (notice: any) => {
  const isBn = themeStore.locale === 'bn';
  if (!confirm(isBn ? 'আপনি কি নিশ্চিতভাবে এই নোটিশটি মুছে ফেলতে চান?' : 'Are you sure you want to delete this notice?')) {
    return;
  }

  try {
    await apiClient.delete(`/admin/notices/${notice.id}`);
    toastStore.success(isBn ? 'নোটিশ সফলভাবে মুছে ফেলা হয়েছে।' : 'Notice deleted.');
    fetchNotices();
  } catch (err: any) {
    toastStore.error(err.response?.data?.message || 'Failed to delete notice');
  }
};

const getTypeBadgeClass = (type: string) => {
  switch (type) {
    case 'important':
    case 'urgent':
      return 'bg-rose-500/15 text-rose-500 border border-rose-500/30';
    case 'class':
      return 'bg-teal-500/15 text-teal-400 border border-teal-500/30';
    case 'exam':
      return 'bg-purple-500/15 text-purple-400 border border-purple-500/30';
    case 'webinar':
    case 'live_session':
      return 'bg-indigo-500/15 text-indigo-400 border border-indigo-500/30';
    case 'course':
      return 'bg-sky-500/15 text-sky-400 border border-sky-500/30';
    default:
      return 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30';
  }
};

const getPriorityBadgeClass = (priority: string) => {
  switch (priority) {
    case 'urgent':
      return 'bg-rose-500/20 text-rose-500 border border-rose-500/30';
    case 'important':
      return 'bg-amber-500/20 text-amber-500 border border-amber-500/30';
    default:
      return 'bg-slate-500/15 text-[var(--text-secondary)] border border-[var(--border-subtle)]';
  }
};

const getPriorityDotClass = (priority: string) => {
  switch (priority) {
    case 'urgent':
      return 'bg-rose-500 animate-pulse';
    case 'important':
      return 'bg-amber-500';
    default:
      return 'bg-slate-400';
  }
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleDateString(themeStore.locale === 'bn' ? 'bn-BD' : 'en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

onMounted(() => {
  fetchNotices();
  fetchCourses();
});
</script>
