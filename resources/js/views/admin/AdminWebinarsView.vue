<template>
  <div class="space-y-8">
    <!-- Header & Action Strip -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">
            {{ themeStore.locale === 'bn' ? 'ওয়েবিনার ও ক্যারিয়ার মাস্টারক্লাস ম্যানেজমেন্ট' : 'Webinars & Masterclasses Management' }}
          </h1>
          <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30">
            {{ pagination.total }} {{ themeStore.locale === 'bn' ? 'টি সেশন' : 'Sessions' }}
          </span>
        </div>
        <p class="text-sm text-[var(--text-secondary)] mt-1">
          {{ themeStore.locale === 'bn' ? 'লাইভ সেমিনার, সেশন সূচিপত্র (Agenda), হাইলাইটস, স্পিকার ও রেজিস্ট্রেশন সংক্রান্ত বিস্তারিত তথ্য নিয়ন্ত্রণ করুন।' : 'Create, edit, and manage public masterclasses, agendas, highlights, speakers, and attendee lists.' }}
        </p>
      </div>

      <div class="flex items-center gap-3">
        <a
          href="/webinars"
          target="_blank"
          class="px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all flex items-center gap-1.5"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'পাবলিক পেজ ভিউ' : 'View Public Page' }}</span>
        </a>

        <button
          @click="openCreateModal"
          class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all inline-flex items-center gap-2 cursor-pointer touch-target shadow-md"
        >
          <span>+</span>
          <span>{{ themeStore.locale === 'bn' ? 'নতুন মাস্টারক্লাস যোগ করুন' : 'Create Masterclass' }}</span>
        </button>
      </div>
    </div>

    <!-- Search & Status Filter Strip -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-[var(--bg-surface)] border border-[var(--border-subtle)] p-4 rounded-2xl shadow-xs">
      <div class="w-full sm:w-80">
        <input
          v-model="searchQuery"
          @input="debounceFetch"
          type="text"
          :placeholder="themeStore.locale === 'bn' ? 'শিরোনাম বা বিষয় দিয়ে খুঁজুন...' : 'Search by title or topic...'"
          class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] transition-colors"
        />
      </div>

      <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
        <button
          v-for="tab in statusTabs"
          :key="tab.value"
          @click="statusFilter = tab.value; fetchWebinars()"
          :class="[
            'px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer',
            statusFilter === tab.value
              ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40 shadow-xs'
              : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--bg-elevated)]'
          ]"
        >
          <span>{{ tab.label }}</span>
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="py-20 text-center space-y-3">
      <div class="inline-block animate-spin w-8 h-8 border-4 border-[#D4AF37] border-t-transparent rounded-full"></div>
      <p class="text-xs font-semibold text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'ওয়েবিনার লোড হচ্ছে...' : 'Loading webinars...' }}
      </p>
    </div>

    <!-- Empty State -->
    <div v-else-if="webinars.length === 0" class="py-16 text-center p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
      <div class="w-14 h-14 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto shadow-sm"><svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/></svg></div>
      <h3 class="text-base font-bold text-[var(--text-primary)]">
        {{ themeStore.locale === 'bn' ? 'কোনো মাস্টারক্লাস পাওয়া যায়নি' : 'No masterclasses found' }}
      </h3>
      <p class="text-xs text-[var(--text-secondary)] max-w-md mx-auto">
        {{ themeStore.locale === 'bn' ? 'নতুন মাস্টারক্লাস যোগ করতে উপরের বাটনে ক্লিক করুন অথবা ফিল্টার পরিবর্তন করুন।' : 'Create your first webinar or clear active search filters.' }}
      </p>
      <button
        @click="openCreateModal"
        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs shadow-md"
      >
        + {{ themeStore.locale === 'bn' ? 'মাস্টারক্লাস তৈরি করুন' : 'Create Masterclass' }}
      </button>
    </div>

    <!-- Webinar Cards Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="w in webinars"
        :key="w.id"
        class="rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] overflow-hidden shadow-sm hover:shadow-xl hover:border-[#D4AF37]/40 transition-all flex flex-col justify-between group"
      >
        <!-- Card Top & Thumbnail -->
        <div>
          <div class="relative aspect-video w-full bg-slate-950 overflow-hidden">
            <img
              v-if="w.thumbnail"
              :src="w.thumbnail"
              :alt="w.title_bn"
              class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-[11px] font-bold text-slate-500">
              {{ themeStore.locale === 'bn' ? 'থাম্বনেইল নেই' : 'No thumbnail' }}
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            
            <!-- Badges Overlay -->
            <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2">
              <span
                class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider backdrop-blur-md shadow-md"
                :class="getStatusBadgeClass(w.status)"
              >
                {{ getStatusLabel(w.status) }}
              </span>

              <span v-if="w.is_featured" class="px-2 py-0.5 rounded-lg bg-amber-500 text-slate-950 text-[10px] font-black shadow-md flex items-center gap-1">
                <svg class="w-3 h-3 fill-current text-[#D4AF37]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span>Featured</span>
              </span>
            </div>

            <!-- Platform / Free Tag -->
            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-xs">
              <span class="px-2.5 py-1 rounded-lg bg-slate-950/80 backdrop-blur-md text-white text-[11px] font-semibold border border-white/10 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full" :class="w.status === 'upcoming' ? 'bg-emerald-400' : 'bg-[#D4AF37]'"></span>
                <span>{{ w.platform || 'Zoom Live' }}</span>
              </span>

              <span class="px-2.5 py-1 rounded-lg bg-emerald-500/90 backdrop-blur-md text-white font-black text-[11px] shadow-sm">
                {{ w.is_free ? (themeStore.locale === 'bn' ? 'ফ্রি এন্ট্রি' : 'Free Entry') : `৳${w.registration_fee}` }}
              </span>
            </div>
          </div>

          <!-- Card Body Content -->
          <div class="p-5 space-y-3.5">
            <div class="space-y-1">
              <div class="text-[11px] font-bold text-[#D4AF37] flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span>{{ formatDateTime(w.event_datetime) }}</span>
                <span>•</span>
                <span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>{{ w.duration_minutes || 90 }} {{ themeStore.locale === "bn" ? "মিনিট" : "mins" }}</span>
              </div>

              <h3 class="text-sm sm:text-base font-bold text-[var(--text-primary)] line-clamp-2 leading-snug">
                {{ themeStore.locale === 'bn' ? (w.title_bn || w.title_en) : (w.title_en || w.title_bn) }}
              </h3>

              <p class="text-xs text-[var(--text-secondary)] line-clamp-2 leading-relaxed">
                {{ themeStore.locale === 'bn' ? (w.subtitle_bn || w.description_bn) : (w.subtitle_en || w.description_en) }}
              </p>
            </div>

            <!-- Booking Progress Bar -->
            <div class="p-3 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-1.5">
              <div class="flex items-center justify-between text-[11px]">
                <span class="text-[var(--text-secondary)] font-medium">
                  {{ themeStore.locale === 'bn' ? 'রেজিস্ট্রেশন:' : 'Booked:' }}
                  <b class="text-[var(--text-primary)]">{{ w.registrations_count || w.registered_count || 0 }} / {{ w.max_participants || 100 }}</b>
                </span>
                <span class="text-[#D4AF37] font-bold">
                  {{ Math.round(((w.registrations_count || w.registered_count || 0) / (w.max_participants || 100)) * 100) }}%
                </span>
              </div>
              <div class="w-full bg-[var(--bg-surface)] h-1.5 rounded-full overflow-hidden">
                <div
                  class="h-full bg-gradient-to-r from-amber-500 to-[#D4AF37] rounded-full"
                  :style="{ width: `${Math.min(100, Math.round(((w.registrations_count || w.registered_count || 0) / (w.max_participants || 100)) * 100))}%` }"
                ></div>
              </div>
            </div>

            <!-- Speakers & Details Indicator -->
            <div class="flex items-center justify-between text-[11px] text-[var(--text-muted)] pt-1 border-t border-[var(--border-subtle)]">
              <span class="flex items-center gap-1 font-semibold text-[var(--text-secondary)]">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>{{ (w.speakers || []).length }} {{ themeStore.locale === 'bn' ? 'জন স্পিকার' : 'Speakers' }}</span>
              </span>
              <span class="text-[10px] text-[var(--text-muted)]">
                Slug: /webinars/<b>{{ w.slug }}</b>
              </span>
            </div>
          </div>
        </div>

        <!-- Card Footer Action Buttons -->
        <div class="p-4 pt-0 grid grid-cols-3 gap-2 border-t border-[var(--border-subtle)]/50 mt-2">
          <button
            type="button"
            @click="openEditModal(w)"
            class="py-2 px-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/15 hover:text-[#D4AF37] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] transition-all flex items-center justify-center gap-1 cursor-pointer"
            title="Edit Details"
          >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'এডিট' : 'Edit' }}</span>
          </button>

          <button
            type="button"
            @click="openSpeakersModal(w)"
            class="py-2 px-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-sky-500/15 hover:text-sky-400 border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] transition-all flex items-center justify-center gap-1 cursor-pointer"
            title="Manage Speakers"
          >
            <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'স্পিকার' : 'Speakers' }}</span>
          </button>

          <button
            type="button"
            @click="openRegistrationsModal(w)"
            class="py-2 px-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-emerald-500/15 hover:text-emerald-400 border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] transition-all flex items-center justify-center gap-1 cursor-pointer"
            title="Attendees List"
          >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'অংশগ্রহণকারী' : 'Attendees' }}</span>
          </button>
        </div>

        <!-- Extra Quick Links (Public & Delete) -->
        <div class="px-4 pb-4 flex items-center justify-between text-[11px]">
          <router-link
            :to="`/webinars/${w.slug}`"
            target="_blank"
            class="text-[#D4AF37] hover:underline font-bold flex items-center gap-1"
          >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'পাবলিক পেজ দেখুন' : 'View Public URL' }}</span>
          </router-link>

          <button
            type="button"
            @click="confirmDeleteWebinar(w)"
            class="text-rose-400 hover:text-rose-500 hover:underline font-medium cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'মুছে ফেলুন' : 'Delete' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Pagination Controls -->
    <div v-if="pagination.last_page > 1" class="flex items-center justify-between p-4 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-2xl">
      <span class="text-xs text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? `পৃষ্ঠা ${pagination.current_page} এর ${pagination.last_page}` : `Page ${pagination.current_page} of ${pagination.last_page}` }}
      </span>
      <div class="flex items-center gap-2">
        <button
          :disabled="pagination.current_page === 1"
          @click="changePage(pagination.current_page - 1)"
          class="px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold disabled:opacity-50 cursor-pointer"
        >
          ← {{ themeStore.locale === 'bn' ? 'পূর্ববর্তী' : 'Prev' }}
        </button>
        <button
          :disabled="pagination.current_page === pagination.last_page"
          @click="changePage(pagination.current_page + 1)"
          class="px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold disabled:opacity-50 cursor-pointer"
        >
          {{ themeStore.locale === 'bn' ? 'পরবর্তী' : 'Next' }} →
        </button>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. WEBINAR CREATE & EDIT MODAL (Comprehensive Multi-Tab Editor) -->
    <!-- ========================================================================= -->
    <AppModal
      v-model="isEditModalOpen"
      :title="isCreating ? (themeStore.locale === 'bn' ? 'নতুন মাস্টারক্লাস / সেমিনার তৈরি করুন' : 'Create New Masterclass') : (themeStore.locale === 'bn' ? 'মাস্টারক্লাস তথ্য সম্পাদনা করুন' : 'Edit Masterclass Details')"
      size="xl"
    >
      <form @submit.prevent="saveWebinar" class="space-y-6">
        
        <!-- Editor Tabs Navigation -->
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

        <!-- TAB 1: Basic Info & Schedule -->
        <div v-show="activeEditorTab === 'basic'" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'মাস্টারক্লাস শিরোনাম (বাংলা)' : 'Title (Bangla)' }} <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="webinarForm.title_bn"
                type="text"
                required
                placeholder="যেমন: এভিয়েশন ও ট্রাভেল এজেন্সিতে ক্যারিয়ার ও ভিসা কনসালটেন্সি কর্মশালা"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'শিরোনাম (English)' : 'Title (English)' }} <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="webinarForm.title_en"
                type="text"
                required
                placeholder="e.g. Aviation, Travel Agency Career & Visa Consultancy Workshop"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'সাবটাইটেল / সারসংক্ষেপ (বাংলা)' : 'Subtitle (Bangla)' }}
              </label>
              <input
                v-model="webinarForm.subtitle_bn"
                type="text"
                placeholder="কীভাবে ট্রাভেল ও এয়ার টিকেটিং সেক্টরে দ্রুত প্রফেশনাল ক্যারিয়ার গড়বেন তার সম্পূর্ণ রোডম্যাপ।"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'সাবটাইটেল (English)' : 'Subtitle (English)' }}
              </label>
              <input
                v-model="webinarForm.subtitle_en"
                type="text"
                placeholder="A complete practical roadmap to starting and scaling a career in air ticketing."
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
                v-model="webinarForm.slug"
                type="text"
                placeholder="aviation-and-travel-agency-career-guideline-2026"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'তারিখ ও সময়' : 'Event Date & Time' }} <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="webinarForm.event_datetime"
                type="datetime-local"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'স্থায়িত্ব (মিনিট)' : 'Duration (Minutes)' }}
              </label>
              <input
                v-model.number="webinarForm.duration_minutes"
                type="number"
                min="15"
                placeholder="90"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'প্ল্যাটফর্ম ও ভেন্যু' : 'Platform & Venue' }}
              </label>
              <input
                v-model="webinarForm.platform"
                type="text"
                placeholder="Zoom Live & Mirpur Lab"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'স্ট্যাটাস' : 'Status' }}
              </label>
              <select
                v-model="webinarForm.status"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
              >
                <option value="upcoming">{{ themeStore.locale === 'bn' ? 'আসন্ন লাইভ (Upcoming)' : 'Upcoming' }}</option>
                <option value="live">{{ themeStore.locale === 'bn' ? 'সরাসরি চলমান (Live Now)' : 'Live' }}</option>
                <option value="past">{{ themeStore.locale === 'bn' ? 'সম্পন্ন / রেকর্ডেড আর্কাইভ (Past)' : 'Past' }}</option>
                <option value="cancelled">{{ themeStore.locale === 'bn' ? 'স্থগিত (Cancelled)' : 'Cancelled' }}</option>
              </select>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'সর্বোচ্চ আসন ও বুকিং' : 'Max Seats & Registered' }}
              </label>
              <div class="grid grid-cols-2 gap-2">
                <input
                  v-model.number="webinarForm.max_participants"
                  type="number"
                  placeholder="100"
                  class="w-full px-3 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)]"
                  title="Max Seats"
                />
                <input
                  v-model.number="webinarForm.registered_count"
                  type="number"
                  placeholder="78"
                  class="w-full px-3 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)]"
                  title="Registered Override"
                />
              </div>
            </div>
          </div>

          <!-- Pricing & Links -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)]">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)] flex items-center gap-2">
                <input
                  v-model="webinarForm.is_free"
                  type="checkbox"
                  class="w-4 h-4 rounded text-[#D4AF37] focus:ring-[#D4AF37] accent-[#D4AF37]"
                />
                <span>{{ themeStore.locale === 'bn' ? '১০০% ফ্রি রেজিস্ট্রেশন' : '100% Free Entry' }}</span>
              </label>
              <input
                v-if="!webinarForm.is_free"
                v-model.number="webinarForm.registration_fee"
                type="number"
                placeholder="ফি (যেমন: ৫০০)"
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] mt-2"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'লাইভ জুম / মিট লিংক' : 'Zoom / Meet Link' }}
              </label>
              <input
                v-model="webinarForm.meeting_link"
                type="url"
                placeholder="https://zoom.us/j/emisha-live"
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)]"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'রেকর্ডিং ভিডিও URL (YouTube/Vimeo)' : 'Recording Video URL' }}
              </label>
              <input
                v-model="webinarForm.recording_url"
                type="url"
                placeholder="https://www.youtube.com/watch?v=..."
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)]"
              />
            </div>
          </div>

          <!-- Thumbnail & Featured Toggle -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <div class="md:col-span-2 space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'থাম্বনেইল ইমেজ URL' : 'Thumbnail Image URL' }}
              </label>
              <input
                v-model="webinarForm.thumbnail"
                type="url"
                placeholder="https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1200"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)]"
              />
            </div>

            <div class="pt-4">
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  v-model="webinarForm.is_featured"
                  type="checkbox"
                  class="w-4 h-4 rounded text-[#D4AF37] focus:ring-[#D4AF37] accent-[#D4AF37]"
                />
                <span class="text-xs font-bold text-[var(--text-primary)]">
                  {{ themeStore.locale === "bn" ? "হোমপেজে ফিচার্ড রাখুন" : "Featured Masterclass" }}
                </span>
              </label>
            </div>
          </div>
        </div>

        <!-- TAB 2: Overview narrative & Upsell cards -->
        <div v-show="activeEditorTab === 'overview'" class="space-y-5">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === "bn" ? "সেমিনার সম্পর্কিত তথ্য ও প্রেক্ষাপট (বাংলা)" : "Masterclass Overview (Bangla)" }}
              </label>
              <textarea
                v-model="webinarForm.description_bn"
                rows="4"
                placeholder="এই লাইভ মাস্টারক্লাসে আন্তর্জাতিক এয়ারলাইন্স টিকেটিংয়ে Sabre এবং Galileo সিস্টেমের ব্যবহারিক গুরুত্ব..."
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] resize-none"
              ></textarea>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === "bn" ? "প্রেক্ষাপট ও বিবরণ (English)" : "Overview (English)" }}
              </label>
              <textarea
                v-model="webinarForm.description_en"
                rows="4"
                placeholder="This live session explores how to operate Sabre and Galileo GDS terminals..."
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] resize-none"
              ></textarea>
            </div>
          </div>

          <!-- Certificate Guarantee Card Texts -->
          <div class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-3">
            <h4 class="text-xs font-bold text-emerald-400 flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'ডিজিটাল সার্টিফিকেট নিশ্চয়তা ব্যানার' : 'Certificate Guarantee Banner' }}</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <input
                v-model="webinarForm.certificate_title_bn"
                placeholder="সার্টিফিকেট টাইটেল (বাংলা)"
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
              />
              <input
                v-model="webinarForm.certificate_title_en"
                placeholder="Certificate Title (English)"
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
              />
              <textarea
                v-model="webinarForm.certificate_note_bn"
                rows="2"
                placeholder="সার্টিফিকেট বিবরণ (বাংলা): সম্পূর্ণ সেশনে উপস্থিত থাকলে আপনি একটি ডিজিটাল সার্টিফিকেট..."
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs resize-none"
              ></textarea>
              <textarea
                v-model="webinarForm.certificate_note_en"
                rows="2"
                placeholder="Certificate note (English): Attending the full session entitles you to a verifiable certificate..."
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs resize-none"
              ></textarea>
            </div>
          </div>

          <!-- Practical Lab Track Upsell Card Texts -->
          <div class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-3">
            <h4 class="text-xs font-bold text-[#D4AF37] flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'মিরপুর ল্যাব ও কোর্স আপসেল কার্ড' : 'Practical Lab Track Upsell Card' }}</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <input
                v-model="webinarForm.lab_upsell_title_bn"
                placeholder="আপসেল টাইটেল (বাংলা)"
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
              />
              <input
                v-model="webinarForm.lab_upsell_title_en"
                placeholder="Upsell Title (English)"
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
              />
              <textarea
                v-model="webinarForm.lab_upsell_desc_bn"
                rows="2"
                placeholder="আপসেল বিবরণ (বাংলা): ১ জন শিক্ষার্থী = ১টি কম্পিউটার ল্যাব এবং লাইভ সফটওয়্যার অ্যাক্সেস।"
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs resize-none"
              ></textarea>
              <textarea
                v-model="webinarForm.lab_upsell_desc_en"
                rows="2"
                placeholder="Upsell description (English): 1 Student = 1 Workstation with live airline ticketing software..."
                class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs resize-none"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- TAB 3: Highlights Checklist Builder -->
        <div v-show="activeEditorTab === 'highlights'" class="space-y-4">
          <div class="flex items-center justify-between">
            <div class="space-y-0.5">
              <h4 class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === "bn" ? "এই মাস্টারক্লাসে যা যা শিখবেন (হাইলাইটস)" : "What You Will Learn (Highlights Checklist)" }}
              </h4>
              <p class="text-[11px] text-[var(--text-secondary)]">
                {{ themeStore.locale === 'bn' ? 'পাবলিক পেজে চেকমার্কসহ পয়েন্ট আকারে প্রদর্শিত হবে।' : 'Displayed as checkmark bullet points on the public masterclass page.' }}
              </p>
            </div>

            <button
              type="button"
              @click="addHighlightItem"
              class="px-3 py-1.5 rounded-xl bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30 text-xs font-bold hover:bg-[#D4AF37] hover:text-slate-950 transition-all cursor-pointer"
            >
              + {{ themeStore.locale === 'bn' ? 'নতুন পয়েন্ট যোগ করুন' : 'Add Point' }}
            </button>
          </div>

          <div class="space-y-3">
            <div
              v-for="(h, hIdx) in editableHighlights"
              :key="hIdx"
              class="p-3.5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] flex items-start gap-3"
            >
              <span class="w-6 h-6 rounded-lg bg-emerald-500/15 text-emerald-400 font-bold flex items-center justify-center text-xs shrink-0 mt-2">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-2 flex-1">
                <input
                  v-model="h.bn"
                  placeholder="পয়েন্ট বিবরণ (বাংলা)"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-medium"
                />
                <input
                  v-model="h.en"
                  placeholder="Point details (English)"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-medium"
                />
              </div>
              <button
                type="button"
                @click="removeHighlightItem(hIdx)"
                class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center text-xs transition-all cursor-pointer shrink-0 mt-1"
                title="Remove Item"
              ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
            </div>
          </div>
        </div>

        <!-- TAB 4: Agenda Timeline Builder -->
        <div v-show="activeEditorTab === 'agenda'" class="space-y-4">
          <div class="flex items-center justify-between">
            <div class="space-y-0.5">
              <h4 class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === "bn" ? "সেশন সূচিপত্র ও সময়সূচি (Agenda Timeline)" : "Session Agenda & Timeline" }}
              </h4>
              <p class="text-[11px] text-[var(--text-secondary)]">
                {{ themeStore.locale === 'bn' ? 'মাস্টারক্লাসের বিভিন্ন পর্ব, বিষয় ও সময় বণ্টন তৈরি করুন।' : 'Define parts, topics, and duration allocation for the session.' }}
              </p>
            </div>

            <button
              type="button"
              @click="addAgendaItem"
              class="px-3 py-1.5 rounded-xl bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30 text-xs font-bold hover:bg-[#D4AF37] hover:text-slate-950 transition-all cursor-pointer"
            >
              + {{ themeStore.locale === 'bn' ? 'নতুন পর্ব যোগ করুন' : 'Add Agenda Part' }}
            </button>
          </div>

          <div class="space-y-3.5">
            <div
              v-for="(ag, agIdx) in editableAgenda"
              :key="agIdx"
              class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-3"
            >
              <div class="flex items-center justify-between">
                <span class="px-2.5 py-1 rounded-lg bg-[#D4AF37]/15 text-[#D4AF37] text-xs font-black">
                  {{ themeStore.locale === 'bn' ? `অংশ ${agIdx + 1}` : `Part ${agIdx + 1}` }}
                </span>
                <div class="flex items-center gap-2">
                  <input
                    v-model="ag.time"
                    placeholder="সময় (যেমন: ১৫ মিনিট / 15 Mins)"
                    class="px-3 py-1 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-right w-40"
                  />
                  <button
                    type="button"
                    @click="removeAgendaItem(agIdx)"
                    class="w-7 h-7 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center text-xs transition-all cursor-pointer"
                  ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <input
                  v-model="ag.title_bn"
                  placeholder="পর্বের নাম (বাংলা)"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold"
                />
                <input
                  v-model="ag.title_en"
                  placeholder="Part Title (English)"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold"
                />
                <input
                  v-model="ag.desc_bn"
                  placeholder="আলোচ্য বিষয় সারসংক্ষেপ (বাংলা)"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs"
                />
                <input
                  v-model="ag.desc_en"
                  placeholder="Topics Summary (English)"
                  class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Action Footer -->
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
            <span>{{ isCreating ? (themeStore.locale === 'bn' ? 'মাস্টারক্লাস সংরক্ষণ করুন' : 'Create Webinar') : (themeStore.locale === 'bn' ? 'আপডেট সংরক্ষণ করুন' : 'Save Changes') }}</span>
          </button>
        </div>

      </form>
    </AppModal>

    <!-- ========================================================================= -->
    <!-- 2. SPEAKERS MANAGEMENT MODAL -->
    <!-- ========================================================================= -->
    <AppModal
      v-model="isSpeakersModalOpen"
      :title="`${themeStore.locale === 'bn' ? 'মাস্টারক্লাস স্পিকার ম্যানেজমেন্ট:' : 'Speakers & Mentors:'} ${selectedWebinar?.title_bn || ''}`"
      size="lg"
    >
      <div class="space-y-6">
        <!-- Current Speakers List -->
        <div class="space-y-3">
          <h4 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider text-[#D4AF37]">
            {{ themeStore.locale === 'bn' ? 'বর্তমান স্পিকার তালিকা' : 'Assigned Speakers' }}
          </h4>

          <div v-if="speakersLoading" class="py-6 text-center">
            <div class="inline-block animate-spin w-6 h-6 border-2 border-[#D4AF37] border-t-transparent rounded-full"></div>
          </div>

          <div v-else-if="currentSpeakers.length === 0" class="p-6 text-center rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-secondary)]">
            {{ themeStore.locale === 'bn' ? 'এখনো কোনো স্পিকার যুক্ত করা হয়নি।' : 'No speakers assigned to this webinar yet.' }}
          </div>

          <div
            v-for="sp in currentSpeakers"
            :key="sp.id"
            class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] flex items-start justify-between gap-4 shadow-xs"
          >
            <div class="flex items-start gap-3 min-w-0">
              <img
                :src="sp.avatar || getInitialsAvatar(sp.name_en || sp.name_bn || 'Speaker')"
                :alt="sp.name_bn"
                class="w-12 h-12 rounded-xl object-cover border border-[#D4AF37]"
              />
              <div class="space-y-0.5 min-w-0">
                <h5 class="text-xs sm:text-sm font-bold text-[var(--text-primary)] truncate">
                  {{ sp.name_bn }} <span class="text-[11px] text-[var(--text-muted)]">({{ sp.name_en }})</span>
                </h5>
                <p class="text-xs text-[#D4AF37] font-semibold truncate">{{ sp.designation_bn }} • {{ sp.organization || 'Emisha Academy' }}</p>
                <p class="text-[11px] text-[var(--text-secondary)] line-clamp-2 leading-relaxed pt-0.5">{{ sp.bio_bn }}</p>
              </div>
            </div>

            <div class="flex items-center gap-1.5 shrink-0">
              <button
                type="button"
                @click="populateSpeakerEdit(sp)"
                class="p-2 rounded-xl bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/15 text-[#D4AF37] transition-all cursor-pointer"
                title="Edit Speaker"
              ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
              <button
                type="button"
                @click="deleteSpeaker(sp.id)"
                class="p-2 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all cursor-pointer"
                title="Delete Speaker"
              ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
            </div>
          </div>
        </div>

        <!-- Mentor Panel Import Card -->
        <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-[var(--bg-surface)] to-[var(--bg-elevated)] border border-[#D4AF37]/40 shadow-sm space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-[var(--border-subtle)] pb-3">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl bg-[#D4AF37]/15 text-[#D4AF37] flex items-center justify-center font-bold text-sm border border-[#D4AF37]/30">
                <svg class="w-4 h-4 text-[#D4AF37] inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
              </div>
              <div>
                <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)]">
                  {{ themeStore.locale === 'bn' ? 'সংরক্ষিত মেন্টর প্যানেল থেকে স্পিকার ইমপোর্ট করুন' : 'Import Speaker from Mentors Panel' }}
                </h4>
                <p class="text-[11px] text-[var(--text-muted)]">
                  {{ themeStore.locale === 'bn' ? 'ডাটাবেজে সেভ থাকা ইন্সট্রাক্টর বা ফ্যাকাল্টি মেম্বারদের সরাসরি নির্বাচন করুন' : 'Select faculty or saved mentors to auto-populate or assign instantly' }}
                </p>
              </div>
            </div>

            <div v-if="mentorsLoading" class="flex items-center gap-1.5 text-xs text-[#D4AF37]">
              <span class="inline-block animate-spin w-3.5 h-3.5 border-2 border-[#D4AF37] border-t-transparent rounded-full"></span>
              <span>{{ themeStore.locale === 'bn' ? 'মেন্টর লোড হচ্ছে...' : 'Loading mentors...' }}</span>
            </div>
            <div v-else class="text-[11px] text-[#D4AF37] font-semibold bg-[#D4AF37]/10 px-2.5 py-1 rounded-lg border border-[#D4AF37]/20">
              {{ mentorsList.length }} {{ themeStore.locale === 'bn' ? 'জন মেন্টর সংরক্ষিত' : 'Mentors available' }}
            </div>
          </div>

          <!-- Dropdown & Action Controls -->
          <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <div class="sm:col-span-7">
              <select
                v-model="selectedMentorId"
                class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[#D4AF37]/40 text-xs font-semibold text-[var(--text-primary)] focus:border-[#D4AF37] outline-none"
              >
                <option value="">{{ themeStore.locale === 'bn' ? '-- মেন্টর নির্বাচন করুন --' : '-- Choose a Mentor --' }}</option>
                <option v-for="m in mentorsList" :key="m.id" :value="m.id">
                  {{ m.name_bn || m.name_en }} ({{ m.title_bn || m.title_en || 'ইন্সট্রাক্টর' }})
                </option>
              </select>
            </div>

            <div class="sm:col-span-5 flex items-center gap-2">
              <button
                type="button"
                :disabled="!selectedMentorId"
                @click="selectedMentorId && handleMentorSelection(selectedMentorId)"
                class="flex-1 py-2.5 px-3 rounded-xl bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/20 border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] hover:text-[#D4AF37] transition-all disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer flex items-center justify-center gap-1"
                title="Autofill form fields from selected mentor"
              >
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'ফর্মে লোড' : 'Autofill' }}</span>
              </button>

              <button
                type="button"
                :disabled="!selectedMentorId || isImportingMentor"
                @click="selectedMentorId && quickAddMentorAsSpeaker(mentorsList.find(m => m.id === Number(selectedMentorId)))"
                class="flex-1 py-2.5 px-3 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 text-xs font-black shadow-sm transition-all disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer flex items-center justify-center gap-1"
                title="Instantly add as speaker to this webinar"
              >
                <span v-if="isImportingMentor" class="inline-block animate-spin w-3 h-3 border-2 border-slate-950 border-t-transparent rounded-full"></span>
                <span v-else><svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></span>
                <span>{{ themeStore.locale === 'bn' ? 'যুক্ত করুন' : 'Quick Add' }}</span>
              </button>
            </div>
          </div>

          <!-- Quick Mentor Avatars/Cards Scroll -->
          <div v-if="mentorsList.length > 0" class="pt-2 border-t border-[var(--border-subtle)]/60">
            <p class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider mb-2">
              {{ themeStore.locale === 'bn' ? 'এক ক্লিকে সরাসরি যুক্ত করুন:' : 'One-Click Quick Add Mentors:' }}
            </p>
            <div class="flex items-center gap-2 overflow-x-auto pb-1.5">
              <div
                v-for="m in mentorsList"
                :key="'chip-' + m.id"
                class="shrink-0 flex items-center gap-2 p-1.5 pr-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37] transition-all"
              >
                <img
                  :src="m.avatar || getInitialsAvatar(m.name_en || m.name_bn || 'Mentor')"
                  :alt="m.name_bn"
                  class="w-7 h-7 rounded-lg object-cover border border-[#D4AF37]/50"
                />
                <div class="text-left">
                  <div class="text-[11px] font-bold text-[var(--text-primary)] leading-tight">{{ m.name_bn || m.name_en }}</div>
                  <div class="text-[9px] text-[#D4AF37] truncate max-w-[110px]">{{ m.title_bn || m.title_en || 'Mentor' }}</div>
                </div>
                <div class="flex items-center gap-1 pl-1 border-l border-[var(--border-subtle)]">
                  <button
                    type="button"
                    @click="importMentorToForm(m)"
                    class="p-1 rounded-lg hover:bg-[#D4AF37]/20 text-[#D4AF37] text-[10px] font-bold transition-all cursor-pointer"
                    title="Load into form below"
                  >
                    <svg class="w-4 h-4 text-[#D4AF37] inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                  </button>
                  <button
                    type="button"
                    @click="quickAddMentorAsSpeaker(m)"
                    class="p-1 px-1.5 rounded-lg bg-[#D4AF37]/20 hover:bg-[#D4AF37] text-[#D4AF37] hover:text-slate-950 text-[10px] font-extrabold transition-all cursor-pointer flex items-center gap-0.5"
                    title="Directly add as speaker"
                  >
                    <span>+</span>
                    <span>{{ themeStore.locale === 'bn' ? 'যোগ' : 'Add' }}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Add / Edit Speaker Form -->
        <form @submit.prevent="saveSpeaker" class="p-5 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-4">
          <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold text-[var(--text-primary)]">
              {{ editingSpeakerId ? (themeStore.locale === 'bn' ? 'স্পিকার তথ্য সম্পাদন করুন' : 'Edit Speaker') : (themeStore.locale === 'bn' ? '+ নতুন স্পিকার যুক্ত করুন' : '+ Add New Speaker') }}
            </h4>
            <button
              v-if="editingSpeakerId"
              type="button"
              @click="resetSpeakerForm"
              class="text-[11px] text-rose-400 hover:underline cursor-pointer"
            >
              {{ themeStore.locale === 'bn' ? 'বাতিল' : 'Cancel Edit' }}
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <input
              v-model="speakerForm.name_bn"
              required
              placeholder="স্পিকার নাম (বাংলা)* যেমন: তানভীর রহমান"
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
            />
            <input
              v-model="speakerForm.name_en"
              required
              placeholder="Speaker Name (English)* e.g. Tanvir Rahman"
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
            />
            <input
              v-model="speakerForm.designation_bn"
              required
              placeholder="পদবি (বাংলা)* যেমন: লিড এভিয়েশন ট্রেইনার"
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
            />
            <input
              v-model="speakerForm.designation_en"
              required
              placeholder="Designation (English)* e.g. Lead Aviation Trainer"
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
            />
            <input
              v-model="speakerForm.organization"
              placeholder="প্রতিষ্ঠান (যেমন: Emisha Tours & Travels)"
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
            />
            <input
              v-model="speakerForm.avatar"
              placeholder="ছবির URL (Avatar Image Link)"
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs"
            />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <textarea
              v-model="speakerForm.bio_bn"
              rows="2"
              placeholder="অভিজ্ঞতা ও সংক্ষিপ্ত পরিচিতি (বাংলা): বিগত ১০+ বছর ধরে এভিয়েশন সেক্টরে কর্মরত..."
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs resize-none"
            ></textarea>
            <textarea
              v-model="speakerForm.bio_en"
              rows="2"
              placeholder="Bio & Experience (English): 10+ years in commercial aviation ticketing..."
              class="w-full px-3 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs resize-none"
            ></textarea>
          </div>

          <div class="flex items-center justify-end">
            <button
              type="submit"
              :disabled="isSpeakerSaving"
              class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs shadow-md cursor-pointer disabled:opacity-50"
            >
              {{ editingSpeakerId ? (themeStore.locale === 'bn' ? 'স্পিকার আপডেট করুন' : 'Update Speaker') : (themeStore.locale === 'bn' ? 'স্পিকার সংরক্ষণ করুন' : 'Save Speaker') }}
            </button>
          </div>
        </form>
      </div>
    </AppModal>

    <!-- ========================================================================= -->
    <!-- 3. ATTENDEES / REGISTRATIONS MODAL -->
    <!-- ========================================================================= -->
    <AppModal
      v-model="isRegistrationsModalOpen"
      :title="`${themeStore.locale === 'bn' ? 'রেজিস্ট্রেশন ও অংশগ্রহণকারী তালিকা:' : 'Attendees List:'} ${selectedWebinar?.title_bn || ''}`"
      size="xl"
    >
      <div class="space-y-4">
        <!-- Search within Attendees -->
        <div class="flex items-center justify-between gap-3">
          <input
            v-model="registrationSearch"
            @input="debounceFetchRegistrations"
            type="text"
            :placeholder="themeStore.locale === 'bn' ? 'নাম, ফোন বা টিকেট নম্বর দিয়ে খুঁজুন...' : 'Search by name, phone or ticket...'"
            class="px-4 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs w-full max-w-sm"
          />
          <span class="text-xs font-bold text-[#D4AF37]">
            {{ regPagination.total }} {{ themeStore.locale === 'bn' ? 'জন নিবন্ধিত' : 'Registrations' }}
          </span>
        </div>

        <!-- Attendees Table -->
        <div class="rounded-2xl border border-[var(--border-subtle)] overflow-hidden bg-[var(--bg-surface)]">
          <div v-if="regLoading" class="py-12 text-center">
            <div class="inline-block animate-spin w-6 h-6 border-2 border-[#D4AF37] border-t-transparent rounded-full"></div>
          </div>

          <div v-else-if="registrationsList.length === 0" class="py-12 text-center text-xs text-[var(--text-secondary)]">
            {{ themeStore.locale === 'bn' ? 'কোনো রেজিস্ট্রেশন পাওয়া যায়নি।' : 'No registrations found.' }}
          </div>

          <table v-else class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-[var(--border-subtle)] bg-[var(--bg-elevated)] text-[var(--text-secondary)] font-bold">
                <th class="p-3 pl-4">{{ themeStore.locale === 'bn' ? 'টিকেট নং' : 'Ticket' }}</th>
                <th class="p-3">{{ themeStore.locale === 'bn' ? 'নাম ও ইমেইল' : 'Participant' }}</th>
                <th class="p-3">{{ themeStore.locale === 'bn' ? 'ফোন / WhatsApp' : 'Phone' }}</th>
                <th class="p-3">{{ themeStore.locale === 'bn' ? 'উপস্থিতি' : 'Attended' }}</th>
                <th class="p-3">{{ themeStore.locale === 'bn' ? 'তারিখ' : 'Date' }}</th>
                <th class="p-3 pr-4 text-right">{{ themeStore.locale === 'bn' ? 'অ্যাকশন' : 'Action' }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border-subtle)]">
              <tr v-for="r in registrationsList" :key="r.id" class="hover:bg-[var(--bg-elevated)]/50 transition-colors">
                <td class="p-3 pl-4">
                  <span class="px-2 py-0.5 rounded-md bg-[#D4AF37]/15 text-[#D4AF37] font-black font-mono text-[10px] border border-[#D4AF37]/30">
                    {{ r.ticket_number }}
                  </span>
                </td>
                <td class="p-3">
                  <div class="font-bold text-[var(--text-primary)]">{{ r.name }}</div>
                  <div class="text-[11px] text-[var(--text-muted)]">{{ r.email }}</div>
                </td>
                <td class="p-3">
                  <a :href="`https://wa.me/${r.phone?.replace(/[^0-9]/g, '')}`" target="_blank" class="text-emerald-500 font-bold hover:underline">
                    {{ r.phone || 'N/A' }}
                  </a>
                </td>
                <td class="p-3">
                  <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input
                      type="checkbox"
                      :checked="r.has_attended"
                      @change="toggleAttended(r)"
                      class="rounded text-[#D4AF37] focus:ring-[#D4AF37] accent-[#D4AF37]"
                    />
                    <span class="text-[11px]" :class="r.has_attended ? 'text-emerald-400 font-bold' : 'text-[var(--text-muted)]'">
                      {{ r.has_attended ? (themeStore.locale === 'bn' ? 'উপস্থিত' : 'Present') : (themeStore.locale === 'bn' ? 'অনুপস্থিত' : 'Absent') }}
                    </span>
                  </label>
                </td>
                <td class="p-3 text-[11px] text-[var(--text-muted)]">
                  {{ formatDateShort(r.created_at) }}
                </td>
                <td class="p-3 pr-4 text-right">
                  <button
                    type="button"
                    @click="deleteRegistration(r.id)"
                    class="text-rose-400 hover:text-rose-500 p-1 cursor-pointer"
                    title="Delete registration record"
                  ><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                </td>
              </tr>
            </tbody>
          </table>
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
const webinars = ref<any[]>([]);
const searchQuery = ref('');
const statusFilter = ref('');

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  total: 0,
});

const statusTabs = [
  { label: themeStore.locale === 'bn' ? 'সকল সেশন' : 'All Sessions', value: '' },
  { label: themeStore.locale === 'bn' ? 'আসন্ন (Upcoming)' : 'Upcoming', value: 'upcoming' },
  { label: themeStore.locale === 'bn' ? 'লাইভ (Live)' : 'Live', value: 'live' },
  { label: themeStore.locale === 'bn' ? 'রেকর্ডেড (Past)' : 'Past', value: 'past' },
];

const editorTabs = [
  { id: 'basic', label: themeStore.locale === 'bn' ? 'বেসিক ও সময়সূচি' : 'Basic & Schedule', icon: '' },
  { id: 'overview', label: themeStore.locale === 'bn' ? 'প্রেক্ষাপট ও কার্ডস' : 'Overview & Cards', icon: '' },
  { id: 'highlights', label: themeStore.locale === 'bn' ? 'হাইলাইটস চেকলিস্ট' : 'Highlights', icon: '' },
  { id: 'agenda', label: themeStore.locale === 'bn' ? 'সেশন সূচিপত্র (Agenda)' : 'Agenda Timeline', icon: '' },
];
const activeEditorTab = ref('basic');

// Create / Edit Modal State
const isEditModalOpen = ref(false);
const isCreating = ref(false);
const editingWebinarId = ref<number | null>(null);
const isSaving = ref(false);

const webinarForm = reactive({
  title_bn: '',
  title_en: '',
  slug: '',
  subtitle_bn: '',
  subtitle_en: '',
  description_bn: '',
  description_en: '',
  thumbnail: '',
  banner_image: '',
  event_datetime: '',
  duration_minutes: 90,
  platform: 'Zoom Live & Mirpur Lab',
  meeting_link: '',
  recording_url: '',
  registration_fee: 0,
  is_free: true,
  max_participants: 100,
  registered_count: 0,
  status: 'upcoming',
  is_featured: false,
  certificate_title_bn: 'ডিজিটাল ভেরিফাইড সার্টিফিকেট নিশ্চয়তা',
  certificate_title_en: 'Verified Digital Participation Certificate',
  certificate_note_bn: 'সম্পূর্ণ সেশনে উপস্থিত থাকলে আপনি একটি ডিজিটাল সার্টিফিকেট ও প্র্যাকটিস শিট সম্পূর্ণ ফ্রিতে পাবেন।',
  certificate_note_en: 'Attending the full session entitles you to a verifiable certificate and digital cheat sheet.',
  lab_upsell_title_bn: 'মিরপুর ক্যাম্পাসে আলাদা কম্পিউটার ল্যাবে পূর্ণাঙ্গ কোর্স শিখুন',
  lab_upsell_title_en: 'Learn on Dedicated PC Workstations at Mirpur Campus',
  lab_upsell_desc_bn: '১ জন শিক্ষার্থী = ১টি কম্পিউটার ল্যাব এবং লাইভ Sabre ও Galileo সফটওয়্যার অ্যাক্সেস।',
  lab_upsell_desc_en: '1 Student = 1 Workstation with live airline ticketing software access.',
});

const editableHighlights = ref<{ bn: string; en: string }[]>([]);
const editableAgenda = ref<{ time: string; title_bn: string; title_en: string; desc_bn: string; desc_en: string }[]>([]);

// Speakers Modal State
const isSpeakersModalOpen = ref(false);
const selectedWebinar = ref<any>(null);
const currentSpeakers = ref<any[]>([]);
const speakersLoading = ref(false);
const isSpeakerSaving = ref(false);
const editingSpeakerId = ref<number | null>(null);

// Mentor Panel Import State
const mentorsList = ref<any[]>([]);
const mentorsLoading = ref(false);
const selectedMentorId = ref<number | ''>('');
const isImportingMentor = ref(false);

const speakerForm = reactive({
  name_bn: '',
  name_en: '',
  designation_bn: '',
  designation_en: '',
  organization: 'Emisha Tours & Travels',
  avatar: '',
  bio_bn: '',
  bio_en: '',
  linkedin_url: '',
});

// Registrations Modal State
const isRegistrationsModalOpen = ref(false);
const registrationsList = ref<any[]>([]);
const regLoading = ref(false);
const registrationSearch = ref('');
const regPagination = reactive({
  current_page: 1,
  last_page: 1,
  total: 0,
});

let debounceTimer: any = null;
const debounceFetch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchWebinars();
  }, 350);
};

const debounceFetchRegistrations = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    if (selectedWebinar.value) {
      fetchRegistrations(selectedWebinar.value.id);
    }
  }, 350);
};

const fetchWebinars = async (page = 1) => {
  loading.value = true;
  try {
    const res = await apiClient.get('/admin/webinars', {
      params: {
        page,
        search: searchQuery.value,
        status: statusFilter.value,
      },
    });
    webinars.value = res.data.data.webinars;
    pagination.current_page = res.data.data.pagination.current_page;
    pagination.last_page = res.data.data.pagination.last_page;
    pagination.total = res.data.data.pagination.total;
  } catch (err: any) {
    toast.error('ওয়েবিনার তালিকা লোড করতে সমস্যা হয়েছে।');
  } finally {
    loading.value = false;
  }
};

const changePage = (page: number) => {
  if (page >= 1 && page <= pagination.last_page) {
    fetchWebinars(page);
  }
};

const openCreateModal = () => {
  isCreating.value = true;
  editingWebinarId.value = null;
  activeEditorTab.value = 'basic';

  Object.assign(webinarForm, {
    title_bn: '',
    title_en: '',
    slug: '',
    subtitle_bn: '',
    subtitle_en: '',
    description_bn: '',
    description_en: '',
    thumbnail: '',
    banner_image: '',
    event_datetime: new Date(Date.now() + 86400000 * 5).toISOString().slice(0, 16),
    duration_minutes: 90,
    platform: 'Zoom Live & Mirpur Lab',
    meeting_link: '',
    recording_url: '',
    registration_fee: 0,
    is_free: true,
    max_participants: 100,
    registered_count: 0,
    status: 'upcoming',
    is_featured: false,
    certificate_title_bn: 'ডিজিটাল ভেরিফাইড সার্টিফিকেট নিশ্চয়তা',
    certificate_title_en: 'Verified Digital Participation Certificate',
    certificate_note_bn: 'সম্পূর্ণ সেশনে উপস্থিত থাকলে আপনি একটি ডিজিটাল সার্টিফিকেট ও প্র্যাকটিস শিট সম্পূর্ণ ফ্রিতে পাবেন।',
    certificate_note_en: 'Attending the full session entitles you to a verifiable certificate and digital cheat sheet.',
    lab_upsell_title_bn: 'মিরপুর ক্যাম্পাসে আলাদা কম্পিউটার ল্যাবে পূর্ণাঙ্গ কোর্স শিখুন',
    lab_upsell_title_en: 'Learn on Dedicated PC Workstations at Mirpur Campus',
    lab_upsell_desc_bn: '১ জন শিক্ষার্থী = ১টি কম্পিউটার ল্যাব এবং লাইভ Sabre ও Galileo সফটওয়্যার অ্যাক্সেস।',
    lab_upsell_desc_en: '1 Student = 1 Workstation with live airline ticketing software access.',
  });

  editableHighlights.value = [{ bn: '', en: '' }];

  editableAgenda.value = [{ time: '', title_bn: '', title_en: '', desc_bn: '', desc_en: '' }];

  isEditModalOpen.value = true;
};

const openEditModal = (w: any) => {
  isCreating.value = false;
  editingWebinarId.value = w.id;
  activeEditorTab.value = 'basic';

  let formattedDate = '';
  if (w.event_datetime) {
    const d = new Date(w.event_datetime);
    if (!isNaN(d.getTime())) {
      const pad = (n: number) => n.toString().padStart(2, '0');
      formattedDate = `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }
  }

  Object.assign(webinarForm, {
    title_bn: w.title_bn || '',
    title_en: w.title_en || '',
    slug: w.slug || '',
    subtitle_bn: w.subtitle_bn || '',
    subtitle_en: w.subtitle_en || '',
    description_bn: w.description_bn || '',
    description_en: w.description_en || '',
    thumbnail: w.thumbnail || '',
    banner_image: w.banner_image || '',
    event_datetime: formattedDate,
    duration_minutes: w.duration_minutes || 90,
    platform: w.platform || 'Zoom Live & Mirpur Lab',
    meeting_link: w.meeting_link || '',
    recording_url: w.recording_url || '',
    registration_fee: w.registration_fee || 0,
    is_free: !!w.is_free,
    max_participants: w.max_participants || 100,
    registered_count: w.registered_count || 0,
    status: w.status || 'upcoming',
    is_featured: !!w.is_featured,
    certificate_title_bn: w.certificate_title_bn || 'ডিজিটাল ভেরিফাইড সার্টিফিকেট নিশ্চয়তা',
    certificate_title_en: w.certificate_title_en || 'Verified Digital Participation Certificate',
    certificate_note_bn: w.certificate_note_bn || 'সম্পূর্ণ সেশনে উপস্থিত থাকলে আপনি একটি ডিজিটাল সার্টিফিকেট ও প্র্যাকটিস শিট সম্পূর্ণ ফ্রিতে পাবেন।',
    certificate_note_en: w.certificate_note_en || 'Attending the full session entitles you to a verifiable certificate and digital cheat sheet.',
    lab_upsell_title_bn: w.lab_upsell_title_bn || 'মিরপুর ক্যাম্পাসে আলাদা কম্পিউটার ল্যাবে পূর্ণাঙ্গ কোর্স শিখুন',
    lab_upsell_title_en: w.lab_upsell_title_en || 'Learn on Dedicated PC Workstations at Mirpur Campus',
    lab_upsell_desc_bn: w.lab_upsell_desc_bn || '১ জন শিক্ষার্থী = ১টি কম্পিউটার ল্যাব এবং লাইভ Sabre ও Galileo সফটওয়্যার অ্যাক্সেস।',
    lab_upsell_desc_en: w.lab_upsell_desc_en || '1 Student = 1 Workstation with live airline ticketing software access.',
  });

  // Populate Highlights
  const hBn = Array.isArray(w.highlights_bn) ? w.highlights_bn : [];
  const hEn = Array.isArray(w.highlights_en) ? w.highlights_en : [];
  const maxH = Math.max(hBn.length, hEn.length);
  if (maxH > 0) {
    editableHighlights.value = Array.from({ length: maxH }, (_, i) => ({
      bn: hBn[i] || '',
      en: hEn[i] || '',
    }));
  } else {
    editableHighlights.value = [{ bn: '', en: '' }];
  }

  // Populate Agenda
  const agBn = Array.isArray(w.agenda_bn) ? w.agenda_bn : [];
  const agEn = Array.isArray(w.agenda_en) ? w.agenda_en : [];
  const maxAg = Math.max(agBn.length, agEn.length);
  if (maxAg > 0) {
    editableAgenda.value = Array.from({ length: maxAg }, (_, i) => ({
      time: agBn[i]?.time || agEn[i]?.time || '',
      title_bn: agBn[i]?.title || '',
      title_en: agEn[i]?.title || '',
      desc_bn: agBn[i]?.desc || '',
      desc_en: agEn[i]?.desc || '',
    }));
  } else {
    editableAgenda.value = [{ time: '', title_bn: '', title_en: '', desc_bn: '', desc_en: '' }];
  }

  isEditModalOpen.value = true;
};

const addHighlightItem = () => {
  editableHighlights.value.push({ bn: '', en: '' });
};

const removeHighlightItem = (idx: number) => {
  editableHighlights.value.splice(idx, 1);
};

const addAgendaItem = () => {
  editableAgenda.value.push({
    time: '',
    title_bn: '',
    title_en: '',
    desc_bn: '',
    desc_en: '',
  });
};

const removeAgendaItem = (idx: number) => {
  editableAgenda.value.splice(idx, 1);
};

const saveWebinar = async () => {
  isSaving.value = true;
  try {
    const highlights_bn = editableHighlights.value.map((h) => h.bn).filter((b) => b.trim().length > 0);
    const highlights_en = editableHighlights.value.map((h) => h.en).filter((e) => e.trim().length > 0);

    const agenda_bn = editableAgenda.value.map((ag, idx) => ({
      part: idx + 1,
      title: ag.title_bn || ag.title_en,
      desc: ag.desc_bn || ag.desc_en,
      time: ag.time,
    }));

    const agenda_en = editableAgenda.value.map((ag, idx) => ({
      part: idx + 1,
      title: ag.title_en || ag.title_bn,
      desc: ag.desc_en || ag.desc_bn,
      time: ag.time,
    }));

    const payload = {
      ...webinarForm,
      highlights_bn,
      highlights_en,
      agenda_bn,
      agenda_en,
    };

    if (isCreating.value) {
      await apiClient.post('/admin/webinars', payload);
      toast.success(themeStore.locale === 'bn' ? 'মাস্টারক্লাস সফলভাবে তৈরি হয়েছে।' : 'Webinar created successfully.');
    } else {
      await apiClient.put(`/admin/webinars/${editingWebinarId.value}`, payload);
      toast.success(themeStore.locale === 'bn' ? 'মাস্টারক্লাসের তথ্য সফলভাবে আপডেট হয়েছে।' : 'Webinar updated successfully.');
    }

    isEditModalOpen.value = false;
    fetchWebinars();
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'তথ্য সংরক্ষণ করতে ত্রুটি হয়েছে।');
  } finally {
    isSaving.value = false;
  }
};

const confirmDeleteWebinar = async (w: any) => {
  if (confirm(`আপনি কি নিশ্চিত যে "${w.title_bn || w.title_en}" ওয়েবিনারটি মুছে ফেলতে চান?`)) {
    try {
      await apiClient.delete(`/admin/webinars/${w.id}`);
      toast.success(themeStore.locale === 'bn' ? 'ওয়েবিনার মুছে ফেলা হয়েছে।' : 'Webinar deleted.');
      fetchWebinars();
    } catch {
      toast.error('ওয়েবিনার মুছতে সমস্যা হয়েছে।');
    }
  }
};

// Speaker Manager Actions
const openSpeakersModal = async (w: any) => {
  selectedWebinar.value = w;
  selectedMentorId.value = '';
  resetSpeakerForm();
  isSpeakersModalOpen.value = true;
  await Promise.all([
    fetchSpeakers(w.id),
    fetchMentors(),
  ]);
};

const fetchMentors = async () => {
  mentorsLoading.value = true;
  try {
    const res = await apiClient.get('/admin/instructors', { params: { all: 1 } });
    mentorsList.value = res.data.data || [];
  } catch {
    console.error('Failed to load mentors');
  } finally {
    mentorsLoading.value = false;
  }
};

const handleMentorSelection = (mentorId: number | '') => {
  if (!mentorId) return;
  const mentor = mentorsList.value.find((m: any) => m.id === Number(mentorId));
  if (mentor) {
    importMentorToForm(mentor);
  }
};

const importMentorToForm = (mentor: any) => {
  editingSpeakerId.value = null;
  Object.assign(speakerForm, {
    name_bn: mentor.name_bn || '',
    name_en: mentor.name_en || '',
    designation_bn: mentor.title_bn || '',
    designation_en: mentor.title_en || '',
    organization: mentor.organization || 'Emisha Tours & Travels',
    avatar: mentor.avatar || '',
    bio_bn: mentor.bio_bn || '',
    bio_en: mentor.bio_en || '',
    linkedin_url: mentor.linkedin_url || '',
  });
  toast.success(
    themeStore.locale === 'bn'
      ? `"${mentor.name_bn || mentor.name_en}" এর তথ্য ফর্মে লোড করা হয়েছে!`
      : `Loaded "${mentor.name_en || mentor.name_bn}" into form!`
  );
};

const quickAddMentorAsSpeaker = async (mentor: any) => {
  if (!selectedWebinar.value) return;
  isImportingMentor.value = true;
  try {
    const payload = {
      name_bn: mentor.name_bn || mentor.name_en || '',
      name_en: mentor.name_en || mentor.name_bn || '',
      designation_bn: mentor.title_bn || mentor.title_en || 'মেন্টর',
      designation_en: mentor.title_en || mentor.title_bn || 'Mentor',
      organization: mentor.organization || 'Emisha Tours & Travels',
      avatar: mentor.avatar || '',
      bio_bn: mentor.bio_bn || '',
      bio_en: mentor.bio_en || '',
      linkedin_url: mentor.linkedin_url || '',
      order_index: currentSpeakers.value.length,
    };
    await apiClient.post(`/admin/webinars/${selectedWebinar.value.id}/speakers`, payload);
    toast.success(
      themeStore.locale === 'bn'
        ? `"${mentor.name_bn || mentor.name_en}" সরাসরি স্পিকার হিসেবে যুক্ত হয়েছেন!`
        : `Added "${mentor.name_en || mentor.name_bn}" as speaker!`
    );
    await fetchSpeakers(selectedWebinar.value.id);
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'মেন্টরকে স্পিকার হিসেবে যুক্ত করতে সমস্যা হয়েছে।');
  } finally {
    isImportingMentor.value = false;
  }
};

const fetchSpeakers = async (webinarId: number) => {
  speakersLoading.value = true;
  try {
    const res = await apiClient.get(`/admin/webinars/${webinarId}/speakers`);
    currentSpeakers.value = res.data.data;
  } finally {
    speakersLoading.value = false;
  }
};

const resetSpeakerForm = () => {
  editingSpeakerId.value = null;
  Object.assign(speakerForm, {
    name_bn: '',
    name_en: '',
    designation_bn: '',
    designation_en: '',
    organization: 'Emisha Tours & Travels',
    avatar: '',
    bio_bn: '',
    bio_en: '',
    linkedin_url: '',
  });
};

const populateSpeakerEdit = (sp: any) => {
  editingSpeakerId.value = sp.id;
  Object.assign(speakerForm, {
    name_bn: sp.name_bn || '',
    name_en: sp.name_en || '',
    designation_bn: sp.designation_bn || '',
    designation_en: sp.designation_en || '',
    organization: sp.organization || 'Emisha Tours & Travels',
    avatar: sp.avatar || '',
    bio_bn: sp.bio_bn || '',
    bio_en: sp.bio_en || '',
    linkedin_url: sp.linkedin_url || '',
  });
};

const saveSpeaker = async () => {
  if (!selectedWebinar.value) return;
  isSpeakerSaving.value = true;
  try {
    if (editingSpeakerId.value) {
      await apiClient.put(`/admin/webinars/speakers/${editingSpeakerId.value}`, speakerForm);
      toast.success(themeStore.locale === 'bn' ? 'স্পিকার আপডেট হয়েছে।' : 'Speaker updated.');
    } else {
      await apiClient.post(`/admin/webinars/${selectedWebinar.value.id}/speakers`, speakerForm);
      toast.success(themeStore.locale === 'bn' ? 'স্পিকার যুক্ত হয়েছে।' : 'Speaker added.');
    }
    resetSpeakerForm();
    await fetchSpeakers(selectedWebinar.value.id);
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'স্পিকার তথ্য সংরক্ষণে সমস্যা হয়েছে।');
  } finally {
    isSpeakerSaving.value = false;
  }
};

const deleteSpeaker = async (speakerId: number) => {
  if (confirm('আপনি কি এই স্পিকারকে তালিকা থেকে মুছে ফেলতে চান?')) {
    try {
      await apiClient.delete(`/admin/webinars/speakers/${speakerId}`);
      toast.success(themeStore.locale === 'bn' ? 'স্পিকার মুছে ফেলা হয়েছে।' : 'Speaker deleted.');
      if (selectedWebinar.value) {
        await fetchSpeakers(selectedWebinar.value.id);
      }
    } catch {
      toast.error('স্পিকার মুছতে সমস্যা হয়েছে।');
    }
  }
};

// Registrations / Attendees Actions
const openRegistrationsModal = async (w: any) => {
  selectedWebinar.value = w;
  registrationSearch.value = '';
  isRegistrationsModalOpen.value = true;
  await fetchRegistrations(w.id);
};

const fetchRegistrations = async (webinarId: number, page = 1) => {
  regLoading.value = true;
  try {
    const res = await apiClient.get(`/admin/webinars/${webinarId}/registrations`, {
      params: {
        page,
        search: registrationSearch.value,
      },
    });
    registrationsList.value = res.data.data.registrations;
    regPagination.current_page = res.data.data.pagination.current_page;
    regPagination.last_page = res.data.data.pagination.last_page;
    regPagination.total = res.data.data.pagination.total;
  } finally {
    regLoading.value = false;
  }
};

const toggleAttended = async (r: any) => {
  try {
    const nextState = !r.has_attended;
    await apiClient.put(`/admin/webinars/registrations/${r.id}`, {
      has_attended: nextState,
    });
    r.has_attended = nextState;
    toast.success(themeStore.locale === 'bn' ? 'উপস্থিতি স্ট্যাটাস আপডেট হয়েছে।' : 'Attendance status updated.');
  } catch {
    toast.error('স্ট্যাটাস আপডেট করতে সমস্যা হয়েছে।');
  }
};

const deleteRegistration = async (registrationId: number) => {
  if (confirm('আপনি কি এই রেজিস্ট্রেশন রেকর্ডটি মুছে ফেলতে চান?')) {
    try {
      await apiClient.delete(`/admin/webinars/registrations/${registrationId}`);
      toast.success(themeStore.locale === 'bn' ? 'রেজিস্ট্রেশন মুছে ফেলা হয়েছে।' : 'Registration deleted.');
      if (selectedWebinar.value) {
        await fetchRegistrations(selectedWebinar.value.id);
      }
    } catch {
      toast.error('রেকর্ড মুছতে সমস্যা হয়েছে।');
    }
  }
};

const getStatusBadgeClass = (status: string) => {
  switch (status) {
    case 'upcoming':
      return 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
    case 'live':
      return 'bg-rose-500/20 text-rose-400 border border-rose-500/30 animate-pulse';
    case 'past':
      return 'bg-slate-700/60 text-slate-300 border border-slate-600';
    case 'cancelled':
      return 'bg-rose-900/40 text-rose-300 border border-rose-800';
    default:
      return 'bg-slate-700 text-slate-300';
  }
};

const getStatusLabel = (status: string) => {
  if (themeStore.locale === 'bn') {
    switch (status) {
      case 'upcoming': return 'আসন্ন লাইভ';
      case 'live': return 'লাইভ সেশন';
      case 'past': return 'রেকর্ডেড আর্কাইভ';
      case 'cancelled': return 'স্থগিত';
      default: return status;
    }
  }
  return status.toUpperCase();
};

const formatDateTime = (dateStr: string) => {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString('bn-BD', { day: 'numeric', month: 'short', year: 'numeric' });
};

const formatDateShort = (dateStr: string) => {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString();
};

onMounted(() => {
  fetchWebinars();
});
</script>
