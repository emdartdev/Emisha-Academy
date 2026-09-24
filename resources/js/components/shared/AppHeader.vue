<template>
  <header class="sticky top-0 z-50 bg-[var(--bg-surface)]/95 backdrop-blur-xl border-b border-[var(--border-subtle)] transition-colors shadow-xs safe-top">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 h-15 sm:h-20 flex items-center justify-between gap-2 sm:gap-4">
      
      <!-- 1. BRAND LOGO (Theme-Aware & Responsive) -->
      <router-link
        to="/"
        class="flex items-center shrink-0 group py-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand-gold)] rounded-xl"
        @click="isMobileMenuOpen = false"
      >
        <!-- Dark Theme Logo -->
        <img
          v-if="isDarkTheme"
          src="/images/logo-dark.png"
          alt="Emisha Academy - Aviation & Skill Development"
          class="h-7 min-[360px]:h-8 sm:h-11 md:h-12 lg:h-13 w-auto max-w-[125px] min-[360px]:max-w-[155px] min-[400px]:max-w-[185px] sm:max-w-[240px] md:max-w-[280px] lg:max-w-[310px] object-contain transition-transform group-hover:scale-102"
        />
        <!-- Light Theme Logo -->
        <img
          v-else
          src="/images/logo-light.png"
          alt="Emisha Academy - Aviation & Skill Development"
          class="h-7 min-[360px]:h-8 sm:h-11 md:h-12 lg:h-13 w-auto max-w-[125px] min-[360px]:max-w-[155px] min-[400px]:max-w-[185px] sm:max-w-[240px] md:max-w-[280px] lg:max-w-[310px] object-contain transition-transform group-hover:scale-102"
        />
      </router-link>

      <!-- 2. DESKTOP & LAPTOP NAVIGATION (lg: 1024px+) -->
      <nav class="hidden lg:flex items-center gap-1 xl:gap-2 text-xs xl:text-sm font-semibold text-[var(--text-secondary)]">
        <router-link
          to="/"
          class="px-2.5 xl:px-3 py-2 rounded-xl hover:text-[var(--brand-gold)] hover:bg-[var(--bg-elevated)] transition-all whitespace-nowrap"
          active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] font-bold shadow-xs"
        >
          {{ $t('nav.home') }}
        </router-link>

        <router-link
          to="/courses"
          class="px-2.5 xl:px-3 py-2 rounded-xl hover:text-[var(--brand-gold)] hover:bg-[var(--bg-elevated)] transition-all whitespace-nowrap"
          active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] font-bold shadow-xs"
        >
          {{ $t('nav.courses') }}
        </router-link>

        <!-- Resources Dropdown (Webinars + Ebooks) -->
        <div class="relative" ref="resourcesMenuRef" @mouseenter="isResourcesOpen = true" @mouseleave="isResourcesOpen = false">
          <button
            type="button"
            @click="isResourcesOpen = !isResourcesOpen"
            :class="[
              'px-2.5 xl:px-3 py-2 rounded-xl hover:text-[var(--brand-gold)] hover:bg-[var(--bg-elevated)] transition-all flex items-center gap-1.5 cursor-pointer whitespace-nowrap',
              isResourcesActive ? '!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] font-bold shadow-xs' : ''
            ]"
            :aria-expanded="isResourcesOpen"
            aria-haspopup="true"
          >
            <span>{{ $t('nav.resources') }}</span>
            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': isResourcesOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </button>

          <!-- Dropdown Menu -->
          <transition
            enter-active-class="transition ease-out duration-150 transform"
            enter-from-class="opacity-0 translate-y-1 scale-98"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition ease-in duration-100 transform"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-1 scale-98"
          >
            <div
              v-show="isResourcesOpen"
              class="absolute top-full left-0 mt-1.5 w-64 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-2xl p-2 z-50 space-y-1 backdrop-blur-xl"
            >
              <router-link
                to="/webinars"
                @click="isResourcesOpen = false"
                class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[var(--bg-elevated)] transition-colors group"
                active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)]"
              >
                <div class="w-8 h-8 rounded-lg bg-[#D4AF37]/10 text-[#D4AF37] flex items-center justify-center shrink-0 border border-[#D4AF37]/20 group-hover:scale-105 transition-transform">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
                    <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                    <line x1="12" x2="12" y1="19" y2="22"/>
                  </svg>
                </div>
                <div>
                  <div class="font-bold text-xs xl:text-sm text-[var(--text-primary)] group-hover:text-[var(--brand-gold)] transition-colors">
                    {{ $t('nav.webinars') }}
                  </div>
                  <div class="text-[11px] text-[var(--text-muted)] leading-tight mt-0.5">
                    {{ themeStore.locale === 'bn' ? 'ফ্রি ও লাইভ ক্যারিয়ার সেমিনার' : 'Live seminars & workshops' }}
                  </div>
                </div>
              </router-link>

              <router-link
                to="/ebooks"
                @click="isResourcesOpen = false"
                class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[var(--bg-elevated)] transition-colors group"
                active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)]"
              >
                <div class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-400 flex items-center justify-center shrink-0 border border-sky-500/20 group-hover:scale-105 transition-transform">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                    <path d="M6 6h10"/>
                    <path d="M6 10h10"/>
                  </svg>
                </div>
                <div>
                  <div class="font-bold text-xs xl:text-sm text-[var(--text-primary)] group-hover:text-[var(--brand-gold)] transition-colors">
                    {{ $t('nav.ebooks') }}
                  </div>
                  <div class="text-[11px] text-[var(--text-muted)] leading-tight mt-0.5">
                    {{ themeStore.locale === 'bn' ? 'গাইডবুক ও শর্টকাট শিট' : 'Career guides & roadmaps' }}
                  </div>
                </div>
              </router-link>
            </div>
          </transition>
        </div>

        <router-link
          to="/blog"
          class="px-2.5 xl:px-3 py-2 rounded-xl hover:text-[var(--brand-gold)] hover:bg-[var(--bg-elevated)] transition-all whitespace-nowrap"
          active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] font-bold shadow-xs"
        >
          {{ $t('nav.blog') }}
        </router-link>

        <router-link
          to="/about"
          class="px-2.5 xl:px-3 py-2 rounded-xl hover:text-[var(--brand-gold)] hover:bg-[var(--bg-elevated)] transition-all whitespace-nowrap"
          active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] font-bold shadow-xs"
        >
          {{ $t('nav.about') }}
        </router-link>

        <router-link
          to="/contact"
          class="px-2.5 xl:px-3 py-2 rounded-xl hover:text-[var(--brand-gold)] hover:bg-[var(--bg-elevated)] transition-all whitespace-nowrap"
          active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] font-bold shadow-xs"
        >
          {{ $t('nav.contact') }}
        </router-link>
      </nav>

      <!-- 3. RIGHT ACTIONS & CONTROLS -->
      <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
        <!-- Modern Creative Language Switcher (inside the drawer on phones to keep the bar uncluttered) -->
        <div class="hidden sm:block">
          <LanguageToggle variant="capsule-switch" />
        </div>

        <!-- Modern Theme Toggle -->
        <ThemeToggle variant="compact" />

        <!-- Desktop & Tablet Auth Buttons -->
        <div class="hidden sm:flex items-center gap-2 pl-1">
          <div v-if="authStore.isAuthenticated" class="flex items-center">
            <router-link
              :to="dashboardLink"
              class="px-3.5 sm:px-4 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-extrabold text-xs hover:brightness-110 shadow-md shadow-[#D4AF37]/20 flex items-center gap-1.5 touch-target transition-all"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              <span>{{ $t('nav.dashboard') }}</span>
            </router-link>
          </div>

          <div v-else class="flex items-center gap-2">
            <router-link
              to="/login"
              class="px-3 py-2 rounded-xl bg-[var(--bg-deep)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] hover:border-[var(--brand-gold)] font-bold text-xs transition-all touch-target"
            >
              {{ $t('nav.login') }}
            </router-link>
            <router-link
              to="/register"
              class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-extrabold text-xs hover:brightness-110 shadow-md shadow-[#D4AF37]/20 transition-all touch-target"
            >
              {{ $t('nav.register') }}
            </router-link>
          </div>
        </div>

        <!-- Phone: one-tap account shortcut (dashboard when signed in, login otherwise) -->
        <router-link
          :to="authStore.isAuthenticated ? dashboardLink : '/login'"
          class="sm:hidden w-11 h-11 rounded-xl flex items-center justify-center shrink-0 active:scale-95 transition-all"
          :class="authStore.isAuthenticated
            ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 shadow-md shadow-[#D4AF37]/20'
            : 'bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)]'"
          :aria-label="authStore.isAuthenticated ? $t('nav.dashboard') : $t('nav.login')"
          :title="authStore.isAuthenticated ? $t('nav.dashboard') : $t('nav.login')"
        >
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </router-link>

        <!-- Mobile / Tablet Hamburger Toggle Button (Shown on < lg) -->
        <button
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          class="lg:hidden w-11 h-11 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[var(--brand-gold)] text-[var(--text-primary)] transition-all cursor-pointer flex items-center justify-center active:scale-95 shadow-xs shrink-0"
          :aria-label="isMobileMenuOpen ? 'Close navigation menu' : 'Open navigation menu'"
          :aria-expanded="isMobileMenuOpen"
          aria-controls="mobile-nav-drawer"
        >
          <svg v-if="!isMobileMenuOpen" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="3" x2="21" y1="12" y2="12"/>
            <line x1="3" x2="21" y1="6" y2="6"/>
            <line x1="3" x2="21" y1="18" y2="18"/>
          </svg>
          <svg v-else class="w-5 h-5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="18" x2="6" y1="6" y2="18"/>
            <line x1="6" x2="18" y1="6" y2="18"/>
          </svg>
        </button>
      </div>

    </div>

    <!-- 4. MOBILE & TABLET SLIDE-OVER DRAWER (< lg)
         Teleported to <body>: the header's backdrop-blur creates a containing block for `fixed`
         children, which trapped the drawer inside the 60px header bar. -->
    <Teleport to="body">
    <transition
      enter-active-class="transition-opacity ease-linear duration-200"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity ease-linear duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isMobileMenuOpen"
        @click="isMobileMenuOpen = false"
        class="fixed inset-0 z-55 bg-black/75 backdrop-blur-xs lg:hidden"
      ></div>
    </transition>

    <transition
      enter-active-class="transition ease-out duration-300 transform"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transition ease-in duration-200 transform"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div
        v-if="isMobileMenuOpen"
        id="mobile-nav-drawer"
        role="dialog"
        aria-modal="true"
        :aria-label="$t('nav.home')"
        class="fixed inset-y-0 right-0 z-60 w-[300px] max-w-[88vw] sm:w-[340px] h-dvh bg-[var(--bg-surface)] border-l border-[var(--border-subtle)] shadow-2xl flex flex-col justify-between gap-4 p-4 sm:p-6 overflow-y-auto overscroll-contain safe-top safe-bottom lg:hidden"
      >
        <div class="space-y-4">
          <!-- Drawer Header -->
          <div class="flex items-center justify-between pb-3.5 border-b border-[var(--border-subtle)]">
            <img
              :src="isDarkTheme ? '/images/logo-dark.png' : '/images/logo-light.png'"
              alt="Emisha Academy"
              class="h-8 sm:h-9 w-auto max-w-[160px] object-contain"
            />
            <button
              @click="isMobileMenuOpen = false"
              class="w-11 h-11 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] flex items-center justify-center cursor-pointer active:scale-95 shrink-0"
              aria-label="Close navigation"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" x2="6" y1="6" y2="18"/>
                <line x1="6" x2="18" y1="6" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Admission Alert Capsule in Drawer -->
          <div class="p-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37]/15 via-amber-500/10 to-transparent border border-[#D4AF37]/30 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              <span class="text-[11px] font-bold text-[#D4AF37]">
                {{ themeStore.locale === 'bn' ? 'ভর্তি চলছে — নতুন ব্যাচ' : 'Admissions Open 2026' }}
              </span>
            </div>
            <router-link
              to="/courses"
              @click="isMobileMenuOpen = false"
              class="text-[10px] font-extrabold text-[#D4AF37] hover:underline"
            >
              {{ themeStore.locale === 'bn' ? 'দেখুন →' : 'Apply →' }}
            </router-link>
          </div>

          <!-- Navigation Links -->
          <nav class="space-y-1 text-sm font-bold text-[var(--text-secondary)]">
            <router-link
              to="/"
              @click="isMobileMenuOpen = false"
              class="flex items-center justify-start gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--bg-elevated)] hover:text-[var(--brand-gold)] transition-colors touch-target"
              active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)]"
            >
              <svg class="w-4 h-4 text-[var(--brand-gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
              </svg>
              <span>{{ $t('nav.home') }}</span>
            </router-link>

            <router-link
              to="/courses"
              @click="isMobileMenuOpen = false"
              class="flex items-center justify-start gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--bg-elevated)] hover:text-[var(--brand-gold)] transition-colors touch-target"
              active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)]"
            >
              <svg class="w-4 h-4 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                <path d="M6 6h10"/>
                <path d="M6 10h10"/>
              </svg>
              <span>{{ $t('nav.courses') }}</span>
            </router-link>

            <!-- Resources Group with Sub-links -->
            <div class="rounded-xl bg-[var(--bg-elevated)]/50 border border-[var(--border-subtle)] p-1.5 space-y-1">
              <div class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-[var(--brand-gold)] flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[var(--brand-gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>
                </svg>
                <span>{{ $t('nav.resources') }}</span>
              </div>
              <router-link
                to="/webinars"
                @click="isMobileMenuOpen = false"
                class="flex items-center gap-2.5 px-3 py-2 min-h-[44px] rounded-lg hover:bg-[var(--bg-elevated)] hover:text-[var(--brand-gold)] transition-colors text-sm font-bold"
                active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] font-bold"
              >
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
                  <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                  <line x1="12" x2="12" y1="19" y2="22"/>
                </svg>
                <span>{{ $t('nav.webinars') }}</span>
              </router-link>
              <router-link
                to="/ebooks"
                @click="isMobileMenuOpen = false"
                class="flex items-center gap-2.5 px-3 py-2 min-h-[44px] rounded-lg hover:bg-[var(--bg-elevated)] hover:text-[var(--brand-gold)] transition-colors text-sm font-bold"
                active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] font-bold"
              >
                <svg class="w-3.5 h-3.5 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                </svg>
                <span>{{ $t('nav.ebooks') }}</span>
              </router-link>
            </div>

            <router-link
              to="/blog"
              @click="isMobileMenuOpen = false"
              class="flex items-center justify-start gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--bg-elevated)] hover:text-[var(--brand-gold)] transition-colors touch-target"
              active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)]"
            >
              <svg class="w-4 h-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                <path d="M18 14h-8"/>
                <path d="M15 18h-5"/>
                <path d="M10 6h8v4h-8V6Z"/>
              </svg>
              <span>{{ $t('nav.blog') }}</span>
            </router-link>

            <router-link
              to="/about"
              @click="isMobileMenuOpen = false"
              class="flex items-center justify-start gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--bg-elevated)] hover:text-[var(--brand-gold)] transition-colors touch-target"
              active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)]"
            >
              <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="3" y1="22" x2="21" y2="22"/>
                <line x1="6" y1="18" x2="6" y2="11"/>
                <line x1="10" y1="18" x2="10" y2="11"/>
                <line x1="14" y1="18" x2="14" y2="11"/>
                <line x1="18" y1="18" x2="18" y2="11"/>
                <polygon points="12 2 20 7 4 7"/>
              </svg>
              <span>{{ $t('nav.about') }}</span>
            </router-link>

            <router-link
              to="/contact"
              @click="isMobileMenuOpen = false"
              class="flex items-center justify-start gap-3 px-3 py-2.5 rounded-xl hover:bg-[var(--bg-elevated)] hover:text-[var(--brand-gold)] transition-colors touch-target"
              active-class="!text-[var(--brand-gold)] !bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)]"
            >
              <svg class="w-4 h-4 text-purple-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
              </svg>
              <span>{{ $t('nav.contact') }}</span>
            </router-link>
          </nav>
        </div>

        <!-- Drawer Footer: Auth, Helpline & Controls -->
        <div class="pt-4 border-t border-[var(--border-subtle)] space-y-3">
          <!-- Language (moved out of the phone header bar) -->
          <div class="sm:hidden flex items-center justify-between gap-2">
            <span class="text-[11px] font-bold text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'ভাষা' : 'Language' }}</span>
            <LanguageToggle variant="capsule-switch" />
          </div>
          <!-- Auth Actions -->
          <div class="space-y-2">
            <template v-if="!authStore.isAuthenticated">
              <router-link
                to="/login"
                @click="isMobileMenuOpen = false"
                class="w-full text-center py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[var(--brand-gold)] text-[var(--text-primary)] font-bold text-xs shadow-xs touch-target block active:scale-98 transition-all"
              >
                {{ $t('nav.login') }}
              </router-link>
              <router-link
                to="/register"
                @click="isMobileMenuOpen = false"
                class="w-full text-center py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-extrabold text-xs shadow-md shadow-[#D4AF37]/20 touch-target block active:scale-98 transition-all"
              >
                {{ $t('nav.register') }}
              </router-link>
            </template>
            <router-link
              v-else
              :to="dashboardLink"
              @click="isMobileMenuOpen = false"
              class="w-full text-center py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-extrabold text-xs shadow-md shadow-[#D4AF37]/20 touch-target flex items-center justify-center gap-2 active:scale-98 transition-all"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              <span>{{ $t('nav.dashboard') }}</span>
            </router-link>
          </div>

          <!-- Quick Hotline Contact in Drawer -->
          <a
            href="tel:+8801805464293"
            class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold touch-target hover:bg-emerald-500/20 transition-all"
          >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <span>০১৮০৫৪৬৪২৯৩</span>
          </a>
        </div>
      </div>
    </transition>
    </Teleport>
  </header>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import { useThemeStore } from '../../stores/theme';
import { useAuthStore } from '../../stores/auth';
import ThemeToggle from './ThemeToggle.vue';
import LanguageToggle from './LanguageToggle.vue';

const route = useRoute();
const themeStore = useThemeStore();
const authStore = useAuthStore();

const isMobileMenuOpen = ref(false);
const isResourcesOpen = ref(false);
const resourcesMenuRef = ref<HTMLElement | null>(null);

const isDarkTheme = computed(() => {
  if (themeStore.mode === 'system') {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }
  return themeStore.mode === 'dark';
});

const isResourcesActive = computed(() => {
  return ['/webinars', '/ebooks'].some(p => route.path.startsWith(p));
});

// Auto-close dropdown / menu on outside click or route change
watch(() => route.path, () => {
  isMobileMenuOpen.value = false;
  isResourcesOpen.value = false;
});

function handleClickOutside(e: MouseEvent) {
  if (resourcesMenuRef.value && !resourcesMenuRef.value.contains(e.target as Node)) {
    isResourcesOpen.value = false;
  }
}

// Esc closes the drawer / dropdown
function handleKeydown(e: KeyboardEvent) {
  if (e.key !== 'Escape') return;
  isMobileMenuOpen.value = false;
  isResourcesOpen.value = false;
}

// Lock page scroll behind the open drawer; close it if the screen grows to desktop width
watch(isMobileMenuOpen, (open) => {
  document.body.style.overflow = open ? 'hidden' : '';
});
const desktopQuery = typeof window !== 'undefined' ? window.matchMedia('(min-width: 64rem)') : null;
function handleDesktopChange(e: MediaQueryListEvent) {
  if (e.matches) isMobileMenuOpen.value = false;
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  document.addEventListener('keydown', handleKeydown);
  desktopQuery?.addEventListener('change', handleDesktopChange);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
  document.removeEventListener('keydown', handleKeydown);
  desktopQuery?.removeEventListener('change', handleDesktopChange);
  document.body.style.overflow = '';
});

const dashboardLink = computed(() => {
  if (authStore.isAdmin) return '/admin/dashboard';
  if (authStore.isManager) return '/admin/dashboard';
  if (authStore.isWorker) return '/admin/leads';
  return '/student/dashboard';
});
</script>
