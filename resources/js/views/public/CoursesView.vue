<template>
  <div class="min-h-screen py-8 sm:py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 sm:space-y-16">
    
    <!-- 1. HERO SHOWCASE & TRUST BANNER -->
    <section class="relative rounded-3xl overflow-hidden p-6 sm:p-10 lg:p-12 bg-gradient-to-br from-[var(--bg-surface)] via-[var(--bg-elevated)] to-[var(--bg-deep)] border border-[var(--border-subtle)] shadow-xl">
      <!-- Ambient Glow Orbs -->
      <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-[#D4AF37]/15 blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-3xl space-y-4 sm:space-y-5">
        <!-- Top Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#D4AF37] text-xs font-extrabold tracking-wider uppercase shadow-xs">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
          <span>{{ $t('courses.catalog_badge') }}</span>
        </div>

        <!-- Headline -->
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[var(--text-primary)] tracking-tight leading-tight">
          {{ $t('courses.catalog_title') }}
        </h1>

        <!-- Subtitle -->
        <p class="text-sm sm:text-base text-[var(--text-secondary)] leading-relaxed">
          {{ $t('courses.catalog_subtitle') }}
        </p>

        <!-- Trust Badges Row -->
        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3 pt-2">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[var(--bg-surface)]/80 backdrop-blur-md border border-[var(--border-subtle)] text-xs font-semibold text-[var(--text-primary)]">
            <svg class="w-3.5 h-3.5 text-[#D4AF37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'প্রত্যেক শিক্ষার্থীর জন্য আলাদা Computer' : 'Dedicated Computer per Student' }}</span>
          </span>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[var(--bg-surface)]/80 backdrop-blur-md border border-[var(--border-subtle)] text-xs font-semibold text-[var(--text-primary)]">
            <svg class="w-3.5 h-3.5 text-[#D4AF37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'Sabre ও Galileo GDS লাইভ প্র্যাকটিস' : 'Live Sabre & Galileo GDS' }}</span>
          </span>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[var(--bg-surface)]/80 backdrop-blur-md border border-[var(--border-subtle)] text-xs font-semibold text-[var(--text-primary)]">
            <svg class="w-3.5 h-3.5 text-[#D4AF37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ themeStore.locale === 'bn' ? '৪৫% স্পেশাল ছাড় (৳১৬,৫০০)' : '45% Offline Special Discount' }}</span>
          </span>
        </div>
      </div>
    </section>

    <!-- 2. HIGH-END FILTER, SEARCH & CATEGORIES COMMAND CENTER -->
    <section class="p-4 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-6 sm:space-y-8 shadow-xl relative overflow-hidden">
      
      <!-- Ambient Glow in Filter Container -->
      <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-[#D4AF37]/10 blur-2xl pointer-events-none"></div>

      <!-- Top Row: Modern Search Bar & Dual Dropdown Selectors -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 relative z-10">
        
        <!-- Search Input with Live Clear -->
        <div class="lg:col-span-6 relative">
          <input
            v-model="filters.search"
            @input="debouncedFetch"
            type="text"
            :placeholder="$t('common.search_placeholder')"
            class="w-full pl-12 pr-10 py-3.5 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-sm focus:outline-none focus:border-[var(--brand-gold)] focus:ring-2 focus:ring-[var(--brand-gold)]/20 transition-all shadow-inner"
          />
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--brand-gold)] pointer-events-none flex items-center">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </span>
          <button
            v-if="filters.search"
            @click="filters.search = ''; fetchCourses()"
            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-[var(--text-muted)] hover:text-[var(--text-primary)] bg-[var(--bg-elevated)] hover:bg-[var(--bg-card)] p-1.5 rounded-full transition-colors cursor-pointer touch-target flex items-center justify-center"
            title="Clear Search"
          >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <!-- Format Selector (Offline Lab vs Online Live) -->
        <div class="lg:col-span-3 relative">
          <select
            v-model="filters.format"
            @change="fetchCourses"
            class="w-full appearance-none pl-4 pr-10 py-3.5 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs sm:text-sm font-semibold focus:outline-none focus:border-[var(--brand-gold)] transition-colors cursor-pointer shadow-xs"
          >
            <option value="all">{{ themeStore.locale === 'bn' ? 'সকল ক্লাস মাধ্যম (All Formats)' : 'All Learning Formats' }}</option>
            <option value="live">{{ themeStore.locale === 'bn' ? 'অফলাইন ল্যাব ও লাইভ Sabre/GDS' : 'Offline Lab & Live Classes' }}</option>
            <option value="recorded">{{ themeStore.locale === 'bn' ? 'অনলাইন ও সেলফ-পেসড কোর্স' : 'Online & Self-Paced' }}</option>
          </select>
          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-[var(--text-muted)] pointer-events-none">▼</span>
        </div>

        <!-- Sort Selector -->
        <div class="lg:col-span-3 relative">
          <select
            v-model="filters.sort"
            @change="fetchCourses"
            class="w-full appearance-none pl-4 pr-10 py-3.5 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs sm:text-sm font-semibold focus:outline-none focus:border-[var(--brand-gold)] transition-colors cursor-pointer shadow-xs"
          >
            <option v-for="opt in sortOptions" :key="opt.value" :value="opt.value">
              {{ opt.label }}
            </option>
          </select>
          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-[var(--text-muted)] pointer-events-none">▼</span>
        </div>
      </div>

      <!-- Categories Navigation Grid / Cards -->
      <div class="space-y-3 pt-2 relative z-10">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="text-xs font-extrabold uppercase tracking-wider text-[var(--brand-gold)]">
              {{ themeStore.locale === 'bn' ? 'কোর্স ক্যাটাগরি ও ক্যারিয়ার ট্র্যাক' : 'Career Tracks & Categories' }}
            </span>
          </div>

          <span class="text-xs text-[var(--text-muted)] font-medium">
            {{ formatNumber(categories.length + 1, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি ক্যাটাগরি' : 'Categories Available' }}
          </span>
        </div>

        <!-- Interactive Domain Category Chips / Cards Grid -->
        <div class="grid grid-cols-1 min-[380px]:grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3">
          
          <!-- All Categories Card -->
          <button
            type="button"
            @click="setCategory('')"
            :class="[
              'p-3.5 rounded-2xl border text-left transition-all duration-200 cursor-pointer flex flex-col justify-between gap-2.5 relative overflow-hidden group',
              filters.category === ''
                ? 'bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 border-[#D4AF37] shadow-lg shadow-[#D4AF37]/25 scale-[1.02]'
                : 'bg-[var(--bg-deep)] hover:bg-[var(--bg-elevated)] text-[var(--text-primary)] border-[var(--border-subtle)] hover:border-[var(--brand-gold)]/60'
            ]"
          >
            <div class="flex items-center justify-between gap-1 w-full">
              <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
              <span
                :class="filters.category === '' ? 'bg-slate-950/15 text-slate-950' : 'bg-[var(--bg-surface)] text-[var(--text-muted)] border border-[var(--border-subtle)]'"
                class="px-2 py-0.5 rounded-full text-[10px] font-extrabold"
              >
                {{ formatNumber(totalAllCourses, themeStore.locale) }}
              </span>
            </div>
            <div>
              <p :class="filters.category === '' ? 'text-slate-950 font-black' : 'text-[var(--text-primary)] font-bold'" class="text-xs sm:text-sm tracking-tight leading-snug">
                {{ $t('common.all_categories') }}
              </p>
              <p :class="filters.category === '' ? 'text-slate-950/80' : 'text-[var(--text-muted)]'" class="text-[10px] mt-0.5 font-medium">
                {{ themeStore.locale === 'bn' ? 'সকল ক্যারিয়ার ট্র্যাক' : 'All Career Tracks' }}
              </p>
            </div>
          </button>

          <!-- Dynamic Category Cards -->
          <button
            v-for="cat in categories"
            :key="cat.id"
            type="button"
            @click="setCategory(cat.slug)"
            :class="[
              'p-3.5 rounded-2xl border text-left transition-all duration-200 cursor-pointer flex flex-col justify-between gap-2.5 relative overflow-hidden group',
              filters.category === cat.slug
                ? 'bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 border-[#D4AF37] shadow-lg shadow-[#D4AF37]/25 scale-[1.02]'
                : 'bg-[var(--bg-deep)] hover:bg-[var(--bg-elevated)] text-[var(--text-primary)] border-[var(--border-subtle)] hover:border-[var(--brand-gold)]/60'
            ]"
          >
            <div class="flex items-center justify-between gap-1 w-full">
              <span class="text-lg group-hover:scale-110 transition-transform">
                {{ getCategoryEmoji(cat.icon || cat.slug) }}
              </span>
              <span
                :class="filters.category === cat.slug ? 'bg-slate-950/15 text-slate-950' : (cat.courses_count > 0 ? 'bg-[var(--bg-surface)] text-[var(--brand-gold)] border border-[var(--border-accent)]' : 'bg-amber-500/10 text-amber-500 border border-amber-500/20')"
                class="px-2 py-0.5 rounded-full text-[10px] font-extrabold"
              >
                {{ getCategoryBadge(cat) }}
              </span>
            </div>
            <div>
              <p :class="filters.category === cat.slug ? 'text-slate-950 font-black' : 'text-[var(--text-primary)] font-bold'" class="text-xs sm:text-sm tracking-tight leading-snug line-clamp-1">
                {{ getCategoryTitle(cat) }}
              </p>
              <p :class="filters.category === cat.slug ? 'text-slate-950/80' : 'text-[var(--text-muted)]'" class="text-[10px] mt-0.5 font-medium truncate">
                {{ getCategorySub(cat) }}
              </p>
            </div>
          </button>

        </div>
      </div>

      <!-- Active Filters Summary Strip (Shown when filter is applied) -->
      <div
        v-if="hasActiveFilters"
        class="pt-4 border-t border-[var(--border-subtle)] flex flex-wrap items-center justify-between gap-2.5 text-xs"
      >
        <div class="flex flex-wrap items-center gap-2">
          <span class="text-[var(--text-muted)] font-semibold">{{ themeStore.locale === 'bn' ? 'সক্রিয় ফিল্টার:' : 'Active Filters:' }}</span>
          
          <!-- Category Tag -->
          <span
            v-if="filters.category"
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[var(--brand-gold)] font-bold"
          >
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
            <span>{{ activeCategoryName }}</span>
            <button @click="setCategory('')" class="hover:text-rose-500 cursor-pointer flex items-center justify-center">
              <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </span>

          <!-- Search Query Tag -->
          <span
            v-if="filters.search"
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-bold"
          >
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <span>"{{ filters.search }}"</span>
            <button @click="filters.search = ''; fetchCourses()" class="hover:text-rose-500 cursor-pointer flex items-center justify-center">
              <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </span>

          <!-- Format Tag -->
          <span
            v-if="filters.format !== 'all'"
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-bold"
          >
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            <span>{{ filters.format === 'live' ? 'Offline/Live' : 'Self-Paced' }}</span>
            <button @click="filters.format = 'all'; fetchCourses()" class="hover:text-rose-500 cursor-pointer flex items-center justify-center">
              <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </span>
        </div>

        <!-- Reset All Action Button -->
        <button
          @click="resetFilters"
          class="text-xs font-bold text-rose-500 hover:text-rose-400 flex items-center gap-1 hover:underline cursor-pointer touch-target"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'সকল ফিল্টার মুছুন' : 'Clear All Filters' }}</span>
        </button>
      </div>

    </section>

    <!-- 3. COURSES GRID & RESULTS -->
    <div>
      <!-- Results Counter -->
      <div class="flex items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
          <h2 class="text-xl sm:text-2xl font-black text-[var(--text-primary)] tracking-tight">
            {{ themeStore.locale === 'bn' ? 'উপলব্ধ ক্যারিয়ার প্রোগ্রাম' : 'Available Career Programs' }}
          </h2>
          <p class="text-xs sm:text-sm text-[var(--text-secondary)] mt-0.5">
            {{ formatNumber(courses.length, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি কোর্স পাওয়া গেছে' : 'courses ready for enrollment' }}
          </p>
        </div>
      </div>

      <!-- Loading Skeleton -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div v-for="i in 6" :key="i" class="h-96 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
      </div>

      <!-- Course Cards Grid -->
      <div v-else-if="courses.length > 0" class="space-y-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
          <CourseCard
            v-for="course in courses"
            :key="course.id"
            :course="course"
          />
        </div>

        <!-- Pagination Bar -->
        <AppPagination
          v-if="pagination.last_page > 1"
          :current-page="pagination.current_page"
          :total-pages="pagination.last_page"
          @change="handlePageChange"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4 max-w-lg mx-auto shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto mb-2">
          <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-[var(--text-primary)]">{{ $t('courses.no_courses_found') }}</h3>
        <p class="text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'আপনার অনুসন্ধান বা ফিল্টার পরিবর্তন করে পুনরায় চেষ্টা করুন।' : 'Try searching with different keywords or clearing active filters.' }}
        </p>
        <button
          @click="resetFilters"
          class="px-6 py-3 rounded-2xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-bold text-xs hover:brightness-110 shadow-md transition-all cursor-pointer touch-target"
        >
          {{ themeStore.locale === 'bn' ? 'সকল কোর্স দেখুন' : 'View All Courses' }}
        </button>
      </div>
    </div>

    <!-- 4. COUNSELING & LAB VISIT CTA BANNER -->
    <section class="relative rounded-3xl overflow-hidden p-6 sm:p-10 bg-gradient-to-r from-[var(--bg-surface)] via-[var(--bg-elevated)] to-[var(--bg-surface)] border border-[var(--border-accent)] shadow-lg">
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="max-w-2xl space-y-2">
          <span class="text-xs font-bold text-[var(--brand-gold)] uppercase tracking-wider">
            {{ themeStore.locale === 'bn' ? 'ফ্রি ক্যারিয়ার গাইডলাইন' : 'Free Career Guidance' }}
          </span>
          <h3 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'কোন কোর্সটি আপনার জন্য উপযুক্ত বুঝতে পারছেন না?' : 'Unsure which career track is best for you?' }}
          </h3>
          <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
            {{ themeStore.locale === 'bn' ? 'আমাদের সিনিয়র এভিয়েশন ট্রেইনার ও ক্যারিয়ার কাউন্সিলরের সাথে সরাসরি কথা বলুন এবং ল্যাব ভিজিট করুন।' : 'Speak directly with our senior travel admissions counselor and schedule a free computer lab tour.' }}
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
          <a
            href="tel:01805464293"
            class="px-6 py-3 rounded-2xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-extrabold text-xs sm:text-sm hover:shadow-lg hover:shadow-[#D4AF37]/30 transition-all flex items-center gap-2 touch-target"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <span>01805464293</span>
          </a>
          <router-link
            to="/contact"
            class="px-5 py-3 rounded-2xl bg-[var(--bg-deep)] hover:bg-[var(--bg-elevated)] text-[var(--text-primary)] border border-[var(--border-subtle)] font-bold text-xs sm:text-sm transition-colors touch-target"
          >
            {{ themeStore.locale === 'bn' ? 'ক্যাম্পাস লোকেশন দেখুন' : 'Visit Campus' }}
          </router-link>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useSeo } from '../../composables/useSeo';
import { formatNumber, getLocalized } from '../../utils/locale';
import CourseCard from '../../components/shared/CourseCard.vue';
import AppPagination from '../../components/ui/AppPagination.vue';

const route = useRoute();
const themeStore = useThemeStore();
const { setMeta, buildBreadcrumbSchema } = useSeo();

const loading = ref(true);
const courses = ref<any[]>([]);
const categories = ref<any[]>([]);

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  total: 0,
});


const filters = reactive({
  search: '',
  category: (route.query.category as string) || '',
  format: 'all',
  sort: 'popular',
});

const hasActiveFilters = computed(() => {
  return !!filters.search || !!filters.category || filters.format !== 'all';
});

const activeCategoryName = computed(() => {
  const cat = categories.value.find(c => c.slug === filters.category);
  if (!cat) return filters.category;
  return getLocalized(cat, 'name', themeStore.locale) || getLocalized(cat, 'title', themeStore.locale) || cat.slug;
});

const totalAllCourses = computed(() => {
  return categories.value.reduce((acc, cat) => acc + (cat.courses_count || 0), 0) || courses.value.length || 3;
});

const sortOptions = computed(() => {
  if (themeStore.locale === 'bn') {
    return [
      { value: 'popular', label: 'সবচেয়ে জনপ্রিয় (Most Popular)' },
      { value: 'newest', label: 'সর্বশেষ কোর্স (Newest)' },
      { value: 'rating', label: 'সর্বোচ্চ রেটিং (Highest Rated)' },
      { value: 'price_low', label: 'ফি: কম থেকে বেশি' },
      { value: 'price_high', label: 'ফি: বেশি থেকে কম' },
    ];
  }
  return [
    { value: 'popular', label: 'Most Popular' },
    { value: 'newest', label: 'Newest Releases' },
    { value: 'rating', label: 'Highest Rated' },
    { value: 'price_low', label: 'Fee: Low to High' },
    { value: 'price_high', label: 'Fee: High to Low' },
  ];
});

function getCategoryEmoji(iconOrSlug: string) {
  if (!iconOrSlug) return '📁';
  const val = iconOrSlug.toLowerCase();
  if (val.includes('plane') || val.includes('air') || val.includes('aviation')) return '✈️';
  if (val.includes('passport') || val.includes('visa') || val.includes('tourism')) return '🛂';
  if (val.includes('palette') || val.includes('graphic') || val.includes('design')) return '🎨';
  if (val.includes('trend') || val.includes('marketing') || val.includes('digital')) return '📈';
  if (val.includes('laptop') || val.includes('tech')) return '💻';
  if (val.includes('book') || val.includes('academy')) return '📖';
  if (val.includes('sparkle')) return '✨';
  if (val.includes('globe')) return '🌍';
  return '📁';
}

function getCategoryBadge(cat: any) {
  if (themeStore.locale === 'bn') {
    if (cat.badge_text_bn) return cat.badge_text_bn;
    if (cat.courses_count > 0) return formatNumber(cat.courses_count, 'bn');
    return 'আসন্ন';
  } else {
    if (cat.badge_text_en) return cat.badge_text_en;
    if (cat.courses_count > 0) return formatNumber(cat.courses_count, 'en');
    return 'Upcoming';
  }
}

function getCategoryTitle(cat: any) {
  return getLocalized(cat, 'name', themeStore.locale) || getLocalized(cat, 'title', themeStore.locale) || cat.slug;
}

function getCategorySub(cat: any) {
  const dynamicTrack = getLocalized(cat, 'track_title', themeStore.locale);
  if (dynamicTrack) return dynamicTrack;

  const slug = (cat.slug || '').toLowerCase();
  if (slug.includes('air-ticketing') || slug.includes('aviation')) {
    return 'Sabre & Galileo GDS';
  }
  if (slug.includes('visa') || slug.includes('tourism')) {
    return themeStore.locale === 'bn' ? 'গ্লোবাল ভিসা প্রসেসিং' : 'Global Visa Processing';
  }
  if (slug.includes('graphic') || slug.includes('design')) {
    return themeStore.locale === 'bn' ? 'ক্রিয়েটিভ ডিজাইন' : 'Creative Design';
  }
  if (slug.includes('marketing') || slug.includes('digital')) {
    return themeStore.locale === 'bn' ? 'ডিজিটাল গ্রোথ' : 'Digital Growth';
  }
  return themeStore.locale === 'bn' ? 'প্রফেশনাল স্কিল' : 'Professional Track';
}

let debounceTimer: any = null;
const debouncedFetch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    pagination.current_page = 1;
    fetchCourses();
  }, 300);
};

const setCategory = (catSlug: string) => {
  filters.category = catSlug;
  pagination.current_page = 1;
  fetchCourses();
};

const resetFilters = () => {
  filters.search = '';
  filters.category = '';
  filters.format = 'all';
  filters.sort = 'popular';
  pagination.current_page = 1;
  fetchCourses();
};

const handlePageChange = (page: number) => {
  pagination.current_page = page;
  fetchCourses();
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const fetchCourses = async () => {
  loading.value = true;
  try {
    const params: any = {
      page: pagination.current_page,
      search: filters.search || undefined,
      category: filters.category || undefined,
      format: filters.format !== 'all' ? filters.format : undefined,
      sort: filters.sort,
    };

    const res = await apiClient.get('/public/courses', { params });
    courses.value = res.data.data.courses || [];
    categories.value = res.data.data.categories || [];
    if (res.data.data.pagination) {
      pagination.current_page = res.data.data.pagination.current_page || 1;
      pagination.last_page = res.data.data.pagination.last_page || 1;
      pagination.total = res.data.data.pagination.total || courses.value.length;
    }
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const updateCoursesSeo = () => {
  const isBn = themeStore.locale === 'bn';
  const categoryPrefix = activeCategoryName.value && filters.category ? `${activeCategoryName.value} - ` : '';
  const title = isBn
    ? `${categoryPrefix}প্রফেশনাল ক্যারিয়ার কোর্সসমূহ`
    : `${categoryPrefix}Professional Career Courses`;
  const description = isBn
    ? 'এয়ার টিকেটিং (Sabre/Galileo GDS), ভিসা প্রসেসিং ও গ্লোবাল ট্রাভেল এজেন্সি ক্যারিয়ার কোর্সসমূহ দেখুন এবং বিশেষ ছাড়ে ভর্তি হোন।'
    : 'Browse practical hands-on courses in Sabre & Galileo GDS air ticketing, visa documentation, and aviation agency management.';

  const breadcrumbs = [
    { name: isBn ? 'হোম' : 'Home', url: '/' },
    { name: isBn ? 'কোর্সসমূহ' : 'Courses', url: '/courses' },
  ];
  if (filters.category) {
    breadcrumbs.push({
      name: activeCategoryName.value,
      url: `/courses?category=${filters.category}`,
    });
  }

  const breadcrumbSchema = buildBreadcrumbSchema(breadcrumbs);
  const itemListSchema = {
    '@type': 'ItemList',
    'itemListElement': courses.value.slice(0, 10).map((c, idx) => ({
      '@type': 'ListItem',
      'position': idx + 1,
      'name': isBn ? (c.title_bn || c.title_en) : (c.title_en || c.title_bn),
      'url': `https://emisha.academy/courses/${c.slug}`,
    })),
  };

  setMeta({
    title,
    description,
    keywords: 'air ticketing course fee, sabre gds training, galileo gds course dhaka, visa processing training, emisha academy courses',
    type: 'website',
    schema: [breadcrumbSchema, itemListSchema],
  });
};

watch(
  () => [themeStore.locale, filters.category],
  () => {
    updateCoursesSeo();
  }
);

onMounted(() => {
  fetchCourses().then(() => {
    updateCoursesSeo();
  });
});
</script>
