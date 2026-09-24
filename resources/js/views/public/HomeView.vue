<template>
  <div class="space-y-20 pb-20 overflow-hidden">
    
    <!-- 1. HERO SECTION (Redesigned for Ultra Mobile & Desktop Excellence) -->
    <section class="relative pt-6 sm:pt-16 pb-10 sm:pb-16 px-3 sm:px-6 lg:px-8 max-w-7xl mx-auto overflow-hidden">
      <!-- Ambient Glow Orbs -->
      <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[280px] min-[380px]:w-[340px] sm:w-[650px] h-[220px] sm:h-[380px] bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
      <div class="absolute top-1/3 -left-10 sm:left-10 w-40 sm:w-80 h-40 sm:h-80 bg-sky-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
      <div class="absolute bottom-10 right-0 sm:right-10 w-48 sm:w-80 h-48 sm:h-80 bg-emerald-500/8 rounded-full blur-3xl pointer-events-none -z-10"></div>

      <div class="text-center space-y-4 sm:space-y-6 max-w-4xl mx-auto">
        <!-- Top Highlight Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full bg-[var(--bg-elevated)]/90 border border-[var(--border-accent)] shadow-sm backdrop-blur-md max-w-full">
          <span class="w-2 h-2 rounded-full bg-[#D4AF37] shrink-0 animate-pulse"></span>
          <span class="text-[11px] sm:text-xs font-bold text-[#D4AF37] tracking-wide truncate">
            {{ $t('hero.badge') }}
          </span>
        </div>

        <!-- Master Hero Headline -->
        <h1 class="text-2xl min-[360px]:text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-[var(--text-primary)] tracking-tight leading-[1.2] px-1">
          {{ $t('hero.title_prefix') }} <br class="hidden sm:inline" />
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#D4AF37] via-[#FFF3B0] to-[#E5C158] drop-shadow-xs">
            {{ $t('hero.title_highlight') }}
          </span>
        </h1>

        <!-- Subtitle -->
        <p class="text-xs sm:text-base md:text-lg text-[var(--text-secondary)] leading-relaxed max-w-2xl mx-auto px-2">
          {{ $t('hero.description') }}
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 pt-2 w-full max-w-md sm:max-w-none mx-auto">
          <router-link
            to="/courses"
            class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black text-xs sm:text-sm md:text-base hover:brightness-110 shadow-xl shadow-[#D4AF37]/25 transition-all active:scale-95 text-center touch-target flex items-center justify-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
            <span>{{ $t('hero.explore_courses') }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </router-link>

          <button
            type="button"
            @click="isDemoModalOpen = true"
            class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] text-[var(--text-primary)] hover:border-[#D4AF37] font-bold text-xs sm:text-sm md:text-base transition-all flex items-center justify-center gap-2 shadow-lg touch-target cursor-pointer active:scale-95"
          >
            <svg class="w-4 h-4 text-[#D4AF37] fill-current" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <span>{{ $t('hero.free_class') }}</span>
          </button>
        </div>

        <!-- 4 Interactive Feature Highlights (Mobile & Desktop Cards) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5 pt-4 sm:pt-6 text-left max-w-4xl mx-auto">
          <div class="p-3 sm:p-3.5 rounded-2xl bg-[var(--bg-card)]/80 border border-[var(--border-subtle)] backdrop-blur-md space-y-1 hover:border-[var(--border-accent)] transition-all">
            <div class="text-[#D4AF37]">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </div>
            <div class="text-xs sm:text-sm font-bold text-[var(--text-primary)] leading-tight">
              {{ themeStore.locale === 'bn' ? '১ শিক্ষার্থী = ১ পিসি' : '1 Student = 1 PC' }}
            </div>
            <div class="text-[10px] sm:text-[11px] text-[var(--text-muted)]">
              {{ themeStore.locale === 'bn' ? '১০০% হ্যান্ডস-অন ল্যাব ক্লাস' : '100% Practical Lab' }}
            </div>
          </div>

          <div class="p-3 sm:p-3.5 rounded-2xl bg-[var(--bg-card)]/80 border border-[var(--border-subtle)] backdrop-blur-md space-y-1 hover:border-[var(--border-accent)] transition-all">
            <div class="text-blue-400">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
            <div class="text-xs sm:text-sm font-bold text-[var(--text-primary)] leading-tight">
              {{ themeStore.locale === 'bn' ? 'Sabre & Galileo' : 'Sabre & Galileo' }}
            </div>
            <div class="text-[10px] sm:text-[11px] text-[var(--text-muted)]">
              {{ themeStore.locale === 'bn' ? 'লাইভ টার্মিনাল অ্যাক্সেস' : 'Live GDS System' }}
            </div>
          </div>

          <div class="p-3 sm:p-3.5 rounded-2xl bg-[var(--bg-card)]/80 border border-[var(--border-subtle)] backdrop-blur-md space-y-1 hover:border-[var(--border-accent)] transition-all">
            <div class="text-emerald-400">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 16l2 2 4-4"/></svg>
            </div>
            <div class="text-xs sm:text-sm font-bold text-[var(--text-primary)] leading-tight">
              {{ themeStore.locale === 'bn' ? 'ভিসা প্রসেসিং' : 'Visa Processing' }}
            </div>
            <div class="text-[10px] sm:text-[11px] text-[var(--text-muted)]">
              {{ themeStore.locale === 'bn' ? 'এম্বাসি ফাইল অডিট' : 'Embassy File Audit' }}
            </div>
          </div>

          <div class="p-3 sm:p-3.5 rounded-2xl bg-[var(--bg-card)]/80 border border-[var(--border-subtle)] backdrop-blur-md space-y-1 hover:border-[var(--border-accent)] transition-all">
            <div class="text-purple-400">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 10v6M2 10l10-5 10 5-10 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>
            <div class="text-xs sm:text-sm font-bold text-[var(--text-primary)] leading-tight">
              {{ themeStore.locale === 'bn' ? 'ভেরিফাইড সার্টিফিকেট' : 'Verified Certificate' }}
            </div>
            <div class="text-[10px] sm:text-[11px] text-[var(--text-muted)]">
              {{ themeStore.locale === 'bn' ? 'জব রেফারেল সাপোর্ট' : 'Job Referral Support' }}
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- 2. TRUST STATS BAR (Responsive Grid) -->
    <section class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
      <!-- Phone: 2×2 stat tiles (icon badge + big number + readable label). md+: one bordered strip with dividers. -->
      <ul
        class="grid grid-cols-2 gap-2.5 sm:gap-3 md:grid-cols-4 md:gap-0 md:p-6 lg:p-8 md:rounded-3xl md:bg-[var(--bg-card)] md:border md:border-[var(--border-subtle)] md:shadow-xl md:backdrop-blur-md md:divide-x md:divide-[var(--border-subtle)]"
        :aria-label="themeStore.locale === 'bn' ? 'ইমিশা একাডেমির পরিসংখ্যান' : 'Emisha Academy in numbers'"
      >
        <li
          v-for="stat in heroStats"
          :key="stat.key"
          class="relative overflow-hidden flex flex-col gap-2 p-3.5 min-[400px]:p-4 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] shadow-sm min-w-0 md:items-center md:text-center md:gap-1.5 md:px-3 md:py-1 md:rounded-none md:bg-transparent md:border-0 md:shadow-none md:overflow-visible"
        >
          <!-- Phone accent bar -->
          <span class="md:hidden absolute inset-x-0 top-0 h-1" :class="stat.bar" aria-hidden="true"></span>
          <span class="md:hidden w-9 h-9 rounded-xl flex items-center justify-center border" :class="stat.badge" aria-hidden="true">
            <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" :fill="stat.filled ? 'currentColor' : 'none'" :stroke="stat.filled ? 'none' : 'currentColor'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-html="stat.icon" />
          </span>
          <span class="block text-2xl min-[380px]:text-[1.7rem] sm:text-3xl lg:text-4xl font-black leading-none tracking-tight" :class="stat.valueClass">
            {{ $t(stat.valueKey) }}
          </span>
          <span class="block text-xs sm:text-sm text-[var(--text-secondary)] font-semibold leading-snug">
            {{ $t(stat.labelKey) }}
          </span>
        </li>
      </ul>
    </section>

    <!-- 3. FEATURED COURSES SECTION -->
    <section class="max-w-7xl mx-auto px-4 space-y-6 sm:space-y-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
        <div class="space-y-1.5 sm:space-y-2">
          <span class="text-xs font-bold text-[#D4AF37] uppercase tracking-wider">{{ $t('home.courses_badge') }}</span>
          <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold text-[var(--text-primary)] tracking-tight">
            {{ $t('home.courses_title') }}
          </h2>
          <p class="text-xs sm:text-sm text-[var(--text-secondary)]">
            {{ $t('home.courses_subtitle') }}
          </p>
        </div>

        <router-link
          to="/courses"
          class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#D4AF37] hover:underline shrink-0"
        >
          <span>{{ $t('home.view_all_courses') }}</span>
        </router-link>
      </div>

      <!-- Course Cards Grid -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div v-for="i in 3" :key="i" class="h-96 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
      </div>

      <div v-else-if="homeData?.courses && homeData.courses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <CourseCard
          v-for="course in homeData.courses"
          :key="course.id"
          :course="course"
        />
      </div>
    </section>

    <!-- 4. WHY EMISHA ACADEMY VALUE PROPOSITIONS (Flagship 4-Pillar Showcase) -->
    <section class="relative max-w-7xl mx-auto px-4 py-8 sm:py-12 space-y-10 sm:space-y-14">
      
      <!-- Ambient Background Glow Orbs -->
      <div class="absolute top-1/2 left-1/4 -translate-y-1/2 w-96 h-96 rounded-full bg-[#D4AF37]/10 blur-3xl pointer-events-none"></div>
      <div class="absolute top-1/2 right-1/4 -translate-y-1/2 w-96 h-96 rounded-full bg-blue-500/8 blur-3xl pointer-events-none"></div>

      <!-- Section Header -->
      <div class="text-center space-y-3.5 sm:space-y-4 max-w-3xl mx-auto relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#D4AF37] text-xs font-extrabold tracking-wider uppercase shadow-xs">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
          <span>{{ $t('home.why_badge') }}</span>
        </div>
        <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[var(--text-primary)] tracking-tight leading-tight">
          {{ $t('home.why_title') }}
        </h2>
        <p class="text-xs sm:text-base text-[var(--text-secondary)] leading-relaxed">
          {{ $t('home.why_subtitle') }}
        </p>
      </div>

      <!-- 4 High-End Value Proposition Pillar Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 relative z-10">
        
        <!-- Pillar 01: Computer Lab Workstations -->
        <div class="group relative p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/70 shadow-lg hover:shadow-2xl hover:shadow-[#D4AF37]/10 transition-all duration-300 overflow-hidden flex flex-col justify-between space-y-6 hover:-translate-y-1.5">
          <!-- Top Accent Border -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-[#D4AF37] opacity-60 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="space-y-4">
            <!-- Header Row: Icon & Step Index -->
            <div class="flex items-center justify-between">
              <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-500 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
              </div>
              <span class="text-2xl sm:text-3xl font-black text-[var(--text-muted)]/30 font-mono tracking-widest">
                01
              </span>
            </div>

            <!-- Title & Description -->
            <div class="space-y-2">
              <h3 class="text-lg sm:text-xl font-extrabold text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors leading-snug">
                {{ $t('home.feature_1_title') }}
              </h3>
              <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
                {{ $t('home.feature_1_desc') }}
              </p>
            </div>
          </div>

          <!-- Feature Bullets / Mini-Pills -->
          <div class="pt-4 border-t border-[var(--border-subtle)] space-y-2">
            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-primary)]">
              <span class="w-4 h-4 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>
              <span>{{ themeStore.locale === 'bn' ? '১ জন শিক্ষার্থী = ১টি নিজস্ব আধুনিক কম্পিউটার' : '1 Dedicated Workstation per Student' }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-primary)]">
              <span class="w-4 h-4 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>
              <span>{{ themeStore.locale === 'bn' ? 'শীতাতপ নিয়ন্ত্রিত (AC) ক্লাসরুম ও কম্পিউটার ল্যাব' : 'Air Conditioning Classroom & Computer Lab' }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-primary)]">
              <span class="w-4 h-4 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>
              <span>{{ themeStore.locale === 'bn' ? 'সরাসরি প্র্যাকটিক্যাল সফটওয়্যার সিমুলেশন' : 'Direct Hands-On Software Simulation' }}</span>
            </div>
          </div>
        </div>

        <!-- Pillar 02: Sabre & Galileo GDS Systems -->
        <div class="group relative p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-blue-500/70 shadow-lg hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-300 overflow-hidden flex flex-col justify-between space-y-6 hover:-translate-y-1.5">
          <!-- Top Accent Border -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-cyan-400 opacity-60 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="space-y-4">
            <!-- Header Row: Icon & Step Index -->
            <div class="flex items-center justify-between">
              <div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/30 text-blue-400 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
              </div>
              <span class="text-2xl sm:text-3xl font-black text-[var(--text-muted)]/30 font-mono tracking-widest">
                02
              </span>
            </div>

            <!-- Title & Description -->
            <div class="space-y-2">
              <h3 class="text-lg sm:text-xl font-extrabold text-[var(--text-primary)] group-hover:text-blue-400 transition-colors leading-snug">
                {{ $t('home.feature_2_title') }}
              </h3>
              <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
                {{ $t('home.feature_2_desc') }}
              </p>
            </div>
          </div>

          <!-- Feature Bullets / Mini-Pills -->
          <div class="pt-4 border-t border-[var(--border-subtle)] space-y-2">
            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-primary)]">
              <span class="w-4 h-4 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>
              <span>{{ themeStore.locale === 'bn' ? 'Sabre ও Travelport Galileo অফিসিয়াল টিকেটিং' : 'Official Sabre & Travelport Galileo GDS' }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-primary)]">
              <span class="w-4 h-4 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center"><svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>
              <span>{{ themeStore.locale === 'bn' ? 'লাইভ PNR ক্রিয়েশন ও রিয়েল ফেয়ার ক্যালকুলেশন' : 'Live PNR Creation & Airline Fare Quotations' }}</span>
            </div>
          </div>
        </div>

        <!-- Pillar 03: Unlimited Practice & Backup Support -->
        <div class="group relative p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-emerald-500/70 shadow-lg hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-300 overflow-hidden flex flex-col justify-between space-y-6 hover:-translate-y-1.5">
          <!-- Top Accent Border -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-400 opacity-60 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="space-y-4">
            <!-- Header Row: Icon & Step Index -->
            <div class="flex items-center justify-between">
              <div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
              </div>
              <span class="text-2xl sm:text-3xl font-black text-[var(--text-muted)]/30 font-mono tracking-widest">
                03
              </span>
            </div>

            <!-- Title & Description -->
            <div class="space-y-2">
              <h3 class="text-lg sm:text-xl font-extrabold text-[var(--text-primary)] group-hover:text-emerald-400 transition-colors leading-snug">
                {{ $t('home.feature_3_title') }}
              </h3>
              <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
                {{ $t('home.feature_3_desc') }}
              </p>
            </div>
          </div>

          <!-- Feature Bullets / Mini-Pills -->
          <div class="pt-4 border-t border-[var(--border-subtle)] space-y-2">
            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-primary)]">
              <span class="w-4 h-4 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
              <span>{{ themeStore.locale === 'bn' ? 'ক্লাস ছাড়াও অতিরিক্ত সময়ে ফ্রি ল্যাব ব্যবহারের সুযোগ' : 'Free Open Lab Access Beyond Regular Class Hours' }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-primary)]">
              <span class="w-4 h-4 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
              <span>{{ themeStore.locale === 'bn' ? 'প্র্যাকটিস সেশনে ইন্সট্রাক্টরদের সার্বক্ষণিক গাইডেন্স ও সাপোর্ট' : 'Get Support & Instructors Guidance on Practice Sessions' }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-primary)]">
              <span class="w-4 h-4 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
              <span>{{ themeStore.locale === 'bn' ? 'অনুপস্থিত থাকলে স্পেশাল ব্যাকআপ ক্লাস সুবিধা' : 'Guaranteed Backup Class for Missed Sessions' }}</span>
            </div>
          </div>
        </div>

        <!-- Pillar 04: Industry Veteran Mentorship & Real Case Studies -->
        <div class="group relative p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-purple-500/70 shadow-lg hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-300 overflow-hidden flex flex-col justify-between space-y-6 hover:-translate-y-1.5">
          <!-- Top Accent Border -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-indigo-400 opacity-60 group-hover:opacity-100 transition-opacity"></div>
          
          <div class="space-y-4">
            <!-- Header Row: Icon & Step Index -->
            <div class="flex items-center justify-between">
              <div class="w-14 h-14 rounded-2xl bg-purple-500/15 border border-purple-500/30 text-purple-400 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              </div>
              <span class="text-2xl sm:text-3xl font-black text-[var(--text-muted)]/30 font-mono tracking-widest">
                04
              </span>
            </div>

            <!-- Title & Description -->
            <div class="space-y-2">
              <h3 class="text-lg sm:text-xl font-extrabold text-[var(--text-primary)] group-hover:text-purple-400 transition-colors leading-snug">
                {{ $t('home.feature_4_title') }}
              </h3>
              <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
                {{ $t('home.feature_4_desc') }}
              </p>
            </div>
          </div>

          <!-- Feature Bullets / Mini-Pills -->
          <div class="pt-4 border-t border-[var(--border-subtle)] space-y-2">
            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-primary)]">
              <span class="w-4 h-4 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
              <span>{{ themeStore.locale === 'bn' ? '১০+ বছরের অভিজ্ঞ এভিয়েশন প্রফেশনাল মেন্টর' : '10+ Years Seasoned Aviation Operations Mentors' }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-[var(--text-primary)]">
              <span class="w-4 h-4 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
              <span>{{ themeStore.locale === 'bn' ? 'রিয়েল এজেন্সি কেস স্টাডি ও ইন্টারভিউ প্রস্তুতি' : 'Real-World Agency Case Studies & Job Preparation' }}</span>
            </div>
          </div>
        </div>

      </div>

      <!-- Trust Bar Strip -->
      <!-- Phone: 2×2 tiles (icon badge + wrapped text). md+: single 4-column strip with dividers. -->
      <ul
        class="relative z-10 grid grid-cols-2 gap-2.5 sm:gap-3 md:grid-cols-4 md:gap-0 md:p-5 md:rounded-2xl md:bg-[var(--bg-surface)] md:border md:border-[var(--border-subtle)] md:shadow-xs md:divide-x md:divide-[var(--border-subtle)]"
        :aria-label="themeStore.locale === 'bn' ? 'কেন ইমিশা একাডেমি' : 'Why Emisha Academy'"
      >
        <li
          v-for="item in trustItems"
          :key="item.key"
          class="flex flex-col gap-2.5 p-3.5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xs min-w-0 md:gap-2 lg:flex-row lg:items-center lg:gap-3 md:px-5 md:py-1 md:rounded-none md:bg-transparent md:border-0 md:shadow-none"
        >
          <span class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border" :class="item.badge" aria-hidden="true">
            <svg
              class="w-5 h-5"
              viewBox="0 0 24 24"
              :fill="item.filled ? 'currentColor' : 'none'"
              :stroke="item.filled ? 'none' : 'currentColor'"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              v-html="item.icon"
            />
          </span>
          <span class="min-w-0 space-y-0.5">
            <span class="block text-[13px] sm:text-sm lg:text-base font-black leading-snug" :class="item.valueClass">
              {{ themeStore.locale === 'bn' ? item.value_bn : item.value_en }}
            </span>
            <span class="block text-[11px] sm:text-xs text-[var(--text-muted)] font-medium leading-snug">
              {{ themeStore.locale === 'bn' ? item.label_bn : item.label_en }}
            </span>
          </span>
        </li>
      </ul>

    </section>

    <!-- 5. UPCOMING WEBINARS -->
    <section v-if="homeData?.webinars && homeData.webinars.length > 0" class="max-w-7xl mx-auto px-4 space-y-6 sm:space-y-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
        <div class="space-y-1.5 sm:space-y-2">
          <span class="text-xs font-bold text-[#D4AF37] uppercase tracking-wider">{{ $t('home.webinars_badge') }}</span>
          <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold text-[var(--text-primary)] tracking-tight">
            {{ $t('home.webinars_title') }}
          </h2>
          <p class="text-xs sm:text-sm text-[var(--text-secondary)]">
            {{ $t('home.webinars_sub') }}
          </p>
        </div>

        <router-link to="/webinars" class="text-xs sm:text-sm font-bold text-[#D4AF37] hover:underline shrink-0">
          {{ $t('home.view_all_webinars') }}
        </router-link>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <WebinarCard
          v-for="webinar in homeData.webinars"
          :key="webinar.id"
          :webinar="webinar"
        />
      </div>
    </section>

    <!-- 6. EBOOKS SHOWCASE -->
    <section v-if="homeData?.ebooks && homeData.ebooks.length > 0" class="max-w-7xl mx-auto px-4 space-y-6 sm:space-y-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
        <div class="space-y-1.5 sm:space-y-2">
          <span class="text-xs font-bold text-[#D4AF37] uppercase tracking-wider">{{ $t('home.ebooks_badge') }}</span>
          <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold text-[var(--text-primary)] tracking-tight">
            {{ $t('home.ebooks_title') }}
          </h2>
          <p class="text-xs sm:text-sm text-[var(--text-secondary)]">
            {{ $t('home.ebooks_sub') }}
          </p>
        </div>

        <router-link to="/ebooks" class="text-xs sm:text-sm font-bold text-[#D4AF37] hover:underline shrink-0">
          {{ $t('home.view_all_ebooks') }}
        </router-link>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <EbookCard
          v-for="ebook in homeData.ebooks"
          :key="ebook.id"
          :ebook="ebook"
        />
      </div>
    </section>

    <!-- 7. STUDENT TESTIMONIALS -->
    <section v-if="homeData?.testimonials && homeData.testimonials.length > 0" class="max-w-7xl mx-auto px-4 space-y-6 sm:space-y-8">
      <div class="text-center space-y-2.5 sm:space-y-3 max-w-2xl mx-auto">
        <span class="text-xs font-bold text-[#D4AF37] uppercase tracking-wider">{{ $t('home.testimonials_badge') }}</span>
        <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold text-[var(--text-primary)] tracking-tight">
          {{ $t('home.testimonials_title') }}
        </h2>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <TestimonialCard
          v-for="item in homeData.testimonials"
          :key="item.id"
          :testimonial="item"
        />
      </div>
    </section>

    <!-- 8. LATEST BLOG POSTS -->
    <section v-if="homeData?.posts && homeData.posts.length > 0" class="max-w-7xl mx-auto px-4 space-y-6 sm:space-y-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
        <div class="space-y-1.5 sm:space-y-2">
          <span class="text-xs font-bold text-[#D4AF37] uppercase tracking-wider">{{ $t('home.blog_badge') }}</span>
          <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold text-[var(--text-primary)] tracking-tight">
            {{ $t('home.blog_title') }}
          </h2>
        </div>
        <router-link to="/blog" class="text-xs sm:text-sm font-bold text-[#D4AF37] hover:underline shrink-0">
          {{ $t('home.view_all_blog') }}
        </router-link>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <BlogCard
          v-for="post in homeData.posts"
          :key="post.id"
          :post="post"
        />
      </div>
    </section>

    <!-- 9. ADMISSION LEAD / CONSULTATION SECTION -->
    <section class="max-w-7xl mx-auto px-4">
      <div class="p-6 sm:p-12 rounded-3xl bg-gradient-to-br from-[var(--bg-elevated)] via-[var(--bg-card)] to-[var(--bg-surface)] border border-[var(--border-accent)] shadow-2xl relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center relative z-10">
          
          <div class="space-y-3 sm:space-y-4">
            <span class="px-3 py-1 rounded-full bg-[#D4AF37]/20 border border-[#D4AF37]/30 text-xs font-bold text-[#D4AF37] inline-block">
              {{ $t('home.lead_badge') }}
            </span>
            <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold text-[var(--text-primary)] leading-tight">
              {{ $t('home.lead_title') }}
            </h2>
            <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
              {{ $t('home.lead_sub') }} <strong class="text-[var(--text-primary)]">01805464293</strong>
            </p>
          </div>

          <!-- Lead Capture Form -->
          <form @submit.prevent="submitLead" class="p-5 sm:p-6 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] space-y-4 shadow-xl">
            <AppInput
              v-model="leadForm.name"
              :label="$t('home.lead_name')"
              :placeholder="$t('home.lead_name_placeholder')"
              required
            />
            <AppInput
              v-model="leadForm.phone"
              :label="$t('home.lead_phone')"
              placeholder="018XXXXXXXX"
              required
            />
            <AppSelect
              v-model="leadForm.course_id"
              :label="$t('home.lead_course')"
              :placeholder="$t('home.lead_select_course')"
              :options="courseOptions"
            />

            <AppButton
              type="submit"
              variant="gold"
              fullWidth
              :loading="isSubmittingLead"
            >
              {{ $t('home.lead_submit') }}
            </AppButton>
          </form>

        </div>
      </div>
    </section>

    <!-- Free Demo Class Modal -->
    <AppModal v-model="isDemoModalOpen" :title="$t('home.demo_modal_title')" size="lg">
      <div class="space-y-4">
        <div class="aspect-video w-full rounded-xl overflow-hidden bg-black">
          <iframe
            class="w-full h-full"
            src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1"
            title="Free Demo Class"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
          ></iframe>
        </div>
        <p class="text-xs text-[var(--text-secondary)]">
          {{ $t('home.demo_modal_desc') }}
        </p>
      </div>
    </AppModal>

  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import apiClient from '../../api/client';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import { useSeo } from '../../composables/useSeo';
import CourseCard from '../../components/shared/CourseCard.vue';
import WebinarCard from '../../components/shared/WebinarCard.vue';
import EbookCard from '../../components/shared/EbookCard.vue';
import BlogCard from '../../components/shared/BlogCard.vue';
import TestimonialCard from '../../components/shared/TestimonialCard.vue';
import AppButton from '../../components/ui/AppButton.vue';
import AppInput from '../../components/ui/AppInput.vue';
import AppSelect from '../../components/ui/AppSelect.vue';
import AppModal from '../../components/ui/AppModal.vue';

const { t } = useI18n();
const toastStore = useToastStore();
const themeStore = useThemeStore();
const { setMeta, buildOrganizationSchema, buildFAQSchema } = useSeo();

// Stats bar below the hero (2×2 tiles on phones, one strip on tablet/desktop)
const heroStats = [
  {
    key: 'trainees',
    valueKey: 'home.stats_trainees_val',
    labelKey: 'home.stats_trainees_label',
    valueClass: 'text-transparent bg-clip-text bg-gradient-to-r from-[#D4AF37] to-[#E5C158]',
    bar: 'bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9]',
    badge: 'bg-[#D4AF37]/12 border-[#D4AF37]/30 text-[#D4AF37]',
    filled: false,
    icon: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
  },
  {
    key: 'rating',
    valueKey: 'home.stats_rating_val',
    labelKey: 'home.stats_rating_label',
    valueClass: 'text-[var(--text-primary)]',
    bar: 'bg-amber-400',
    badge: 'bg-amber-500/10 border-amber-500/25 text-amber-500',
    filled: true,
    icon: '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
  },
  {
    key: 'lab',
    valueKey: 'home.stats_lab_val',
    labelKey: 'home.stats_lab_label',
    valueClass: 'text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-blue-500',
    bar: 'bg-gradient-to-r from-sky-400 to-blue-500',
    badge: 'bg-sky-500/10 border-sky-500/25 text-sky-500',
    filled: false,
    icon: '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
  },
  {
    key: 'experience',
    valueKey: 'home.stats_exp_val',
    labelKey: 'home.stats_exp_label',
    valueClass: 'text-[var(--text-primary)]',
    bar: 'bg-emerald-500',
    badge: 'bg-emerald-500/10 border-emerald-500/25 text-emerald-500',
    filled: false,
    icon: '<path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>',
  },
];

// Trust strip under "Why Emisha" (2×2 tiles on phones, one row on tablet/desktop)
const trustItems = [
  {
    key: 'rating',
    value_bn: '৪.৯৬ / ৫.০',
    value_en: '4.96 / 5.0',
    label_bn: 'শিক্ষার্থীদের গড় রেটিং',
    label_en: 'Average student rating',
    valueClass: 'text-[var(--brand-gold)]',
    badge: 'bg-[#D4AF37]/12 border-[#D4AF37]/30 text-[#D4AF37]',
    filled: true,
    icon: '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
  },
  {
    key: 'lab',
    value_bn: '১০০% AC ল্যাব ও ক্লাসরুম',
    value_en: '100% AC lab & classrooms',
    label_bn: 'মিরপুর-১০, ঢাকা মেট্রো স্টেশন সংলগ্ন',
    label_en: 'Mirpur-10, next to Dhaka Metro station',
    valueClass: 'text-[var(--text-primary)]',
    badge: 'bg-blue-500/10 border-blue-500/25 text-blue-500',
    filled: false,
    icon: '<path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
  },
  {
    key: 'certificate',
    value_bn: 'ভেরিফাইড সার্টিফিকেট',
    value_en: 'Verified certificate',
    label_bn: 'আন্তর্জাতিক ভ্যালু ও অনলাইন ভেরিফিকেশন',
    label_en: 'International value & online verification',
    valueClass: 'text-[var(--text-primary)]',
    badge: 'bg-purple-500/10 border-purple-500/25 text-purple-500',
    filled: false,
    icon: '<path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
  },
  {
    key: 'jobs',
    value_bn: 'জব সাপোর্ট ও গাইডেন্স',
    value_en: 'Job support & guidance',
    label_bn: 'সিভি রিভিউ ও ইন্সট্রাক্টর সাপোর্ট',
    label_en: 'CV review & instructor support',
    valueClass: 'text-emerald-600 dark:text-emerald-400',
    badge: 'bg-emerald-500/10 border-emerald-500/25 text-emerald-500',
    filled: false,
    icon: '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>',
  },
];

const loading = ref(true);
const homeData = ref<any>(null);
const isDemoModalOpen = ref(false);
const isSubmittingLead = ref(false);

const leadForm = reactive({
  name: '',
  phone: '',
  course_id: '',
});

const courseOptions = computed(() => {
  if (!homeData.value?.courses) return [];
  return homeData.value.courses.map((c: any) => ({
    value: c.id,
    label: themeStore.locale === 'bn' ? (c.title_bn || c.title_en) : (c.title_en || c.title_bn),
  }));
});

const fetchHomeData = async () => {
  try {
    const res = await apiClient.get('/public/home');
    homeData.value = res.data.data;
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const submitLead = async () => {
  if (!leadForm.name || !leadForm.phone) {
    toastStore.warning(themeStore.locale === 'bn' ? 'অনুগ্রহ করে আপনার নাম ও ফোন নম্বর দিন।' : 'Please provide your name and phone number.');
    return;
  }

  isSubmittingLead.value = true;
  try {
    const res = await apiClient.post('/public/leads', {
      name: leadForm.name,
      phone: leadForm.phone,
      course_id: leadForm.course_id || null,
      source: 'home_counseling_box',
    });
    toastStore.success(res.data.message || (themeStore.locale === 'bn' ? 'কাউন্সেলিং বুকিং সফল হয়েছে।' : 'Counseling booked successfully.'));
    leadForm.name = '';
    leadForm.phone = '';
    leadForm.course_id = '';
  } catch (err: any) {
    toastStore.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'কাউন্সেলিং রিকোয়েস্ট পাঠানো যায়নি।' : 'Failed to submit counseling request.'));
  } finally {
    isSubmittingLead.value = false;
  }
};

const updateHomeSeo = () => {
  const isBn = themeStore.locale === 'bn';
  const orgSchema = buildOrganizationSchema();
  const faqSchema = buildFAQSchema([
    {
      question: isBn ? 'ইমিশা একাডেমিতে কীভাবে এয়ার টিকেটিং ও GDS কোর্স শেখানো হয়?' : 'How is Air Ticketing and GDS taught at Emisha Academy?',
      answer: isBn
        ? 'মিরপুর ক্যাম্পাসে প্রতিটি শিক্ষার্থীর জন্য নিজস্ব কম্পিউটার ল্যাবে Sabre এবং Galileo সফটওয়্যারে লাইভ প্র্যাকটিক্যাল অনুশীলনের মাধ্যমে শেখানো হয়।'
        : 'Classes are conducted with 1 student = 1 PC in our dedicated computer lab with hands-on practice on live Sabre and Galileo GDS terminals.',
    },
    {
      question: isBn ? 'কোর্স শেষে চাকরির কোনো সহায়তা দেওয়া হয়?' : 'Is there job placement support after completing the course?',
      answer: isBn
        ? 'হ্যাঁ, কোর্স সফলভাবে সম্পন্নকারী শিক্ষার্থীদের জন্য রিজিউমি মেকিং, মক ইন্টারভিউ এবং বাংলাদেশের শীর্ষস্থানীয় ট্রাভেল এজেন্সিতে ইন্টার্নশিপ ও জব রেফারেল সাপোর্ট প্রদান করা হয়।'
        : 'Yes, certified graduates receive resume optimization, mock interviews, and direct referral opportunities to leading travel agencies and airlines in Bangladesh.',
    },
    {
      question: isBn ? 'ভিসা প্রসেসিং ও এম্বাসি ফাইলিং কীভাবে শেখানো হয়?' : 'How is visa processing and embassy dossier filing covered?',
      answer: isBn
        ? 'শেঞ্জেন, ইউএসএ, কানাডা, যুক্তরাজ্য, তুরস্ক ও এশিয়ার বিভিন্ন দেশের রিয়েল কেস স্টাডি, এম্বাসি কভার লেটার ড্রাফটিং ও ব্যাংক ফাইলিংয়ের সরাসরি প্রশিক্ষণ দেওয়া হয়।'
        : 'Students work on real-world case studies for Schengen, US, Canada, UK, and Asian visas with practical dossier auditing and embassy cover letter drafting.',
    },
  ]);

  setMeta({
    title: isBn ? 'এভিয়েশন, এয়ার টিকেটিং ও ভিসা প্রসেসিং ট্রেনিং' : 'Aviation, Air Ticketing & Visa Processing Academy',
    description: isBn
      ? 'বাংলাদেশ ও আন্তর্জাতিক এভিয়েশন ও ট্রাভেল এজেন্সিতে ক্যারিয়ার গড়তে Sabre/Galileo GDS এয়ার টিকেটিং ও ভিসা প্রসেসিং প্র্যাকটিক্যাল ট্রেনিং নিন ইমিশা একাডেমিতে।'
      : 'Launch your career in international aviation and travel agencies with hands-on Sabre & Galileo GDS ticketing and visa processing training at Emisha Academy.',
    keywords: 'air ticketing course bangladesh, sabre gds training dhaka, galileo gds course mirpur, visa processing course, travel agency business training, aviation academy bangladesh, emisha academy',
    type: 'website',
    schema: [orgSchema, faqSchema],
  });
};

watch(
  () => themeStore.locale,
  () => {
    updateHomeSeo();
  }
);

onMounted(() => {
  updateHomeSeo();
  fetchHomeData();
});
</script>
