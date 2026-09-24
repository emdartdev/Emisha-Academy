<template>
  <div class="min-h-screen flex bg-[var(--bg-deep)] text-[var(--text-primary)] transition-colors">
    <!-- Mobile Sidebar Backdrop -->
    <transition
      enter-active-class="transition-opacity ease-linear duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity ease-linear duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/70 backdrop-blur-xs lg:hidden"
      ></div>
    </transition>

    <!-- Admin Sidebar (Collapsible on Desktop) -->
    <aside
      :class="[
        'bg-[var(--bg-surface)] border-r border-[var(--border-subtle)] flex flex-col shrink-0 z-50 fixed inset-y-0 left-0 lg:static transition-all duration-300 ease-in-out safe-bottom',
        sidebarOpen ? 'translate-x-0 shadow-2xl w-72 max-w-[85vw]' : '-translate-x-full lg:translate-x-0 w-72',
        isCollapsed ? 'lg:w-20' : 'lg:w-64'
      ]"
    >
      <!-- Brand Header & Collapse Toggle Button -->
      <div class="p-3.5 sm:p-4 border-b border-[var(--border-subtle)] flex items-center justify-between min-h-[64px]">
        <!-- Full Logo (When Expanded or on Mobile) -->
        <router-link
          v-if="!compact"
          to="/admin/dashboard"
          class="flex flex-col gap-0.5 overflow-hidden"
        >
          <img
            :src="isDarkTheme ? '/images/logo-dark.png' : '/images/logo-light.png'"
            alt="Emisha Academy Admin"
            class="h-7 sm:h-8 w-auto max-w-[155px] object-contain"
          />
          <span class="text-[8.5px] text-[var(--brand-gold)] font-bold tracking-wider uppercase pl-0.5 truncate">
            {{ $t('admin.management_console') }}
          </span>
        </router-link>

        <!-- Compact Monogram Logo (When Collapsed on Desktop) -->
        <router-link
          v-else
          to="/admin/dashboard"
          class="mx-auto flex items-center justify-center w-10 h-10 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-accent)] text-[#D4AF37] font-black text-base shadow-xs group"
          title="Emisha Academy Dashboard"
        >
          <span class="group-hover:scale-110 transition-transform">EA</span>
        </router-link>

        <!-- Desktop Collapse Toggle Button -->
        <button
          type="button"
          @click="toggleCollapse"
          class="hidden lg:flex items-center justify-center w-8 h-8 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-card)] border border-[var(--border-subtle)] hover:border-[var(--brand-gold)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer touch-target shadow-xs"
          :title="isCollapsed ? (themeStore.locale === 'bn' ? 'সাইডবার প্রসারিত করুন' : 'Expand Sidebar') : (themeStore.locale === 'bn' ? 'সাইডবার সংকুচিত করুন' : 'Collapse Sidebar')"
        >
          <svg
            class="w-4 h-4 transition-transform duration-300"
            :class="isCollapsed ? 'rotate-180' : ''"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <polyline points="11 17 6 12 11 7"/>
            <polyline points="18 17 13 12 18 7"/>
          </svg>
        </button>

        <!-- Mobile Close Drawer Button -->
        <button 
          @click="sidebarOpen = false" 
          class="lg:hidden text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer flex items-center justify-center"
          aria-label="Close sidebar"
        >
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      <!-- Navigation Links -->
      <nav class="p-2.5 sm:p-3 space-y-1.5 flex-1 overflow-y-auto no-scrollbar">
        
        <!-- Section 1: System Management -->
        <div v-if="!compact" class="px-3 py-1.5 text-[10px] font-bold text-[var(--text-secondary)] uppercase tracking-wider">
          {{ $t('admin.system_management') }}
        </div>
        <div v-else class="my-2 border-t border-[var(--border-subtle)]"></div>

        <!-- 1. Dashboard -->
        <router-link
          to="/admin/dashboard"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('admin.nav_dashboard')"
        >
          <svg class="w-4 h-4 text-[#D4AF37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="7" height="9" x="3" y="3" rx="1"/>
            <rect width="7" height="5" x="14" y="3" rx="1"/>
            <rect width="7" height="9" x="14" y="12" rx="1"/>
            <rect width="7" height="5" x="3" y="16" rx="1"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_dashboard') }}</span>
        </router-link>

        <!-- 2. Courses -->
        <router-link
          to="/admin/courses"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('admin.nav_courses')"
        >
          <svg class="w-4 h-4 text-sky-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
            <path d="M6 6h10"/>
            <path d="M6 10h10"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_courses') }}</span>
        </router-link>

        <!-- 2B. Student Enrollments -->
        <router-link
          to="/admin/enrollments"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="themeStore.locale === 'bn' ? 'শিক্ষার্থী এনরোলমেন্টস' : 'Enrollments'"
        >
          <svg class="w-4 h-4 text-emerald-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ themeStore.locale === 'bn' ? 'শিক্ষার্থী এনরোলমেন্টস' : 'Enrollments' }}</span>
        </router-link>

        <!-- 3. Course Categories & Tracks -->
        <router-link
          to="/admin/categories"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('admin.nav_categories')"
        >
          <svg class="w-4 h-4 text-amber-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="7" height="7" x="3" y="3" rx="1"/>
            <rect width="7" height="7" x="14" y="3" rx="1"/>
            <rect width="7" height="7" x="14" y="14" rx="1"/>
            <rect width="7" height="7" x="3" y="14" rx="1"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_categories') }}</span>
        </router-link>

        <!-- 4. Mentors & Faculty -->
        <router-link
          to="/admin/mentors"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('admin.nav_mentors')"
        >
          <svg class="w-4 h-4 text-teal-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_mentors') }}</span>
        </router-link>

        <!-- 5. Webinars & Seminars -->
        <router-link
          to="/admin/webinars"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('admin.nav_webinars')"
        >
          <svg class="w-4 h-4 text-rose-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m16 13 5.223 3.482a.5.5 0 0 0 .777-.416V7.87a.5.5 0 0 0-.752-.432L16 10.5"/>
            <rect x="2" y="6" width="14" height="12" rx="2"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_webinars') }}</span>
        </router-link>

        <!-- 6. Blogs & Articles -->
        <router-link
          to="/admin/blogs"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('admin.nav_blogs')"
        >
          <svg class="w-4 h-4 text-amber-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9"/>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_blogs') }}</span>
        </router-link>

        <!-- 7. E-books & Resources -->
        <router-link
          to="/admin/ebooks"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('admin.nav_ebooks')"
        >
          <svg class="w-4 h-4 text-indigo-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_ebooks') }}</span>
        </router-link>

        <!-- 8. Orders & Payments -->
        <router-link
          to="/admin/orders"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('admin.nav_orders')"
        >
          <svg class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="20" height="14" x="2" y="5" rx="2"/>
            <line x1="2" x2="22" y1="10" y2="10"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_orders') }}</span>
        </router-link>

        <!-- 9. CRM & Leads Pipeline -->
        <router-link
          to="/admin/leads"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('admin.nav_leads')"
        >
          <svg class="w-4 h-4 text-purple-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <circle cx="12" cy="12" r="6"/>
            <circle cx="12" cy="12" r="2"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_leads') }}</span>
        </router-link>

        <!-- 9b. Employee name directory (Admin only) -->
        <router-link
          v-if="authStore.isAdmin"
          to="/admin/employees"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="themeStore.locale === 'bn' ? 'কর্মী তালিকা' : 'Employees'"
        >
          <svg class="w-4 h-4 text-teal-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ themeStore.locale === 'bn' ? 'কর্মী তালিকা' : 'Employees' }}</span>
        </router-link>

        <!-- 10. Notices & Announcements -->
        <router-link
          to="/admin/notices"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="themeStore.locale === 'bn' ? 'নোটিশ ও অ্যানাউন্সমেন্ট' : 'Notices & Board'"
        >
          <svg class="w-4 h-4 text-cyan-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ themeStore.locale === 'bn' ? 'নোটিশ ও অ্যানাউন্সমেন্ট' : 'Notices & Board' }}</span>
        </router-link>

        <!-- 11. Security Audit Logs -->
        <router-link
          to="/admin/audit-logs"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('admin.nav_audit_logs')"
        >
          <svg class="w-4 h-4 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <path d="m9 12 2 2 4-4"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_audit_logs') }}</span>
        </router-link>

        <!-- Section 2: General & Website -->
        <div v-if="!compact" class="pt-3 px-3 py-1.5 text-[10px] font-bold text-[var(--text-secondary)] uppercase tracking-wider">
          {{ $t('admin.nav_other') }}
        </div>
        <div v-else class="my-2 border-t border-[var(--border-subtle)]"></div>

        <!-- Main Website Link -->
        <router-link
          to="/"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          :title="$t('admin.nav_main_website')"
        >
          <svg class="w-4 h-4 text-cyan-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="2" y1="12" x2="22" y2="12"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('admin.nav_main_website') }}</span>
        </router-link>
      </nav>

      <!-- User Info & Logout Footer -->
      <div class="p-3 sm:p-4 border-t border-[var(--border-subtle)] space-y-2.5">
        <!-- Phone: language switch lives here (header is too narrow) -->
        <div class="sm:hidden flex justify-center">
          <LanguageToggle variant="capsule-switch" />
        </div>
        <!-- Expanded Profile View -->
        <div v-if="!compact" class="flex items-center gap-3 px-1.5">
          <div class="w-9 h-9 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-accent)] flex items-center justify-center text-sm font-bold text-[var(--brand-gold)] shrink-0">
            {{ authStore.userName?.charAt(0) || 'A' }}
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-xs font-bold text-[var(--text-primary)] truncate">{{ authStore.userName || 'Administrator' }}</p>
            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-bold bg-amber-500/10 text-[var(--brand-gold)] border border-amber-500/20 uppercase">
              {{ authStore.primaryRole || 'Admin' }}
            </span>
          </div>
        </div>

        <!-- Collapsed Profile View (Centered Avatar) -->
        <div v-else class="flex justify-center" :title="authStore.userName + ' (' + (authStore.primaryRole || 'Admin') + ')'">
          <div class="w-10 h-10 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-accent)] flex items-center justify-center text-xs font-black text-[var(--brand-gold)] shadow-xs">
            {{ authStore.userName?.charAt(0) || 'A' }}
          </div>
        </div>

        <!-- Logout Button -->
        <button
          @click="handleLogout"
          :class="[
            'w-full flex items-center text-rose-500 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 rounded-xl text-xs font-medium transition-all cursor-pointer touch-target',
            compact ? 'justify-center p-2.5' : 'justify-center gap-2 px-3 py-2.5'
          ]"
          :title="$t('nav.logout')"
        >
          <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          <span v-if="!compact">{{ $t('nav.logout') }}</span>
        </button>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Top Navbar -->
      <header class="h-16 bg-[var(--bg-surface)] border-b border-[var(--border-subtle)] px-3 sm:px-6 lg:px-8 flex items-center justify-between gap-2 sticky top-0 z-30 transition-colors shadow-xs safe-top">
        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
          <!-- Mobile Drawer Toggle Button -->
          <button
            @click="sidebarOpen = true"
            class="lg:hidden p-2 rounded-xl bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] touch-target flex items-center justify-center cursor-pointer"
            aria-label="Open sidebar"
          >
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="4" x2="20" y1="12" y2="12"/>
              <line x1="4" x2="20" y1="6" y2="6"/>
              <line x1="4" x2="20" y1="18" y2="18"/>
            </svg>
          </button>

          <!-- Desktop Sidebar Collapse Toggle Shortcut -->
          <button
            @click="toggleCollapse"
            class="hidden lg:flex p-2 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-card)] border border-[var(--border-subtle)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] touch-target items-center justify-center cursor-pointer transition-colors shadow-xs"
            :title="isCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'"
          >
            <svg
              class="w-4 h-4 transition-transform duration-300"
              :class="isCollapsed ? 'rotate-180' : ''"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
              <line x1="9" x2="9" y1="3" y2="21"/>
              <path d="m14 9-3 3 3 3"/>
            </svg>
          </button>

          <!-- Phone: brand logo instead of the long portal title -->
          <router-link to="/admin/dashboard" class="sm:hidden flex items-center min-w-0" aria-label="Emisha Academy">
            <img
              :src="isDarkTheme ? '/images/logo-dark.png' : '/images/logo-light.png'"
              alt="Emisha Academy"
              class="h-7 w-auto max-w-[120px] object-contain"
            />
          </router-link>
          <div class="hidden sm:block min-w-0">
            <h1 class="text-sm sm:text-base font-bold text-[var(--text-primary)] tracking-tight truncate">{{ $t('admin.portal_title') }}</h1>
          </div>
        </div>

        <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
          <!-- Modern Language Switcher Capsule (in the drawer on phones) -->
          <div class="hidden sm:block">
            <LanguageToggle variant="capsule-switch" />
          </div>

          <!-- Modern Theme Toggle -->
          <ThemeToggle variant="compact" />

          <div class="hidden xl:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-secondary)]">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>{{ $t('admin.system_live') }}</span>
          </div>

          <div class="flex items-center gap-2 pl-2 sm:pl-3 border-l border-[var(--border-subtle)]">
            <div class="w-8 h-8 rounded-full bg-[var(--bg-elevated)] border border-[var(--border-subtle)] flex items-center justify-center text-xs font-bold text-[var(--brand-gold)]">
              {{ authStore.userName?.charAt(0) || 'A' }}
            </div>
            <span class="text-xs font-medium text-[var(--text-primary)] hidden md:inline truncate max-w-[120px]">{{ authStore.userName }}</span>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-4 sm:p-6 lg:p-8 flex-1 overflow-y-auto">
        <div class="mx-auto w-full max-w-[1680px] min-w-0">
          <router-view />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { useToastStore } from '../stores/toast';
import { useThemeStore } from '../stores/theme';
import ThemeToggle from '../components/shared/ThemeToggle.vue';
import LanguageToggle from '../components/shared/LanguageToggle.vue';

const sidebarOpen = ref(false);
const isCollapsed = ref(typeof window !== 'undefined' && localStorage.getItem('admin_sidebar_collapsed') === 'true');
const authStore = useAuthStore();
const toast = useToastStore();
const themeStore = useThemeStore();
const router = useRouter();

const isDarkTheme = computed(() => {
  if (themeStore.mode === 'system') {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }
  return themeStore.mode === 'dark';
});

const toggleCollapse = () => {
  isCollapsed.value = !isCollapsed.value;
  if (typeof window !== 'undefined') {
    localStorage.setItem('admin_sidebar_collapsed', String(isCollapsed.value));
  }
};

// The desktop "collapsed" preference must never turn the mobile drawer into an icon strip
const compact = computed(() => isCollapsed.value && !sidebarOpen.value);

// Mobile drawer: close on navigation / Esc, and lock page scroll behind it
const route = useRoute();
watch(() => route.fullPath, () => (sidebarOpen.value = false));
watch(sidebarOpen, (open) => {
  document.body.style.overflow = open ? 'hidden' : '';
});
const onKeydown = (e: KeyboardEvent) => {
  if (e.key === 'Escape' && sidebarOpen.value) sidebarOpen.value = false;
};
onMounted(() => window.addEventListener('keydown', onKeydown));
onBeforeUnmount(() => {
  window.removeEventListener('keydown', onKeydown);
  document.body.style.overflow = '';
});

async function handleLogout() {
  await authStore.logout();
  toast.success(themeStore.locale === 'bn' ? 'সফলভাবে লগআউট হয়েছে' : 'Logged out successfully');
  router.push('/login');
}
</script>
