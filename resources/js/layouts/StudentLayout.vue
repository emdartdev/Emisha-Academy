<template>
  <div class="min-h-screen flex bg-[var(--bg-deep)] text-[var(--text-primary)] transition-colors">
    <!-- Desktop & Mobile Sidebar Overlay -->
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

    <!-- Student Sidebar (Collapsible on Desktop) -->
    <aside 
      :class="[
        'bg-[var(--bg-surface)] border-r border-[var(--border-subtle)] flex flex-col shrink-0 z-50 fixed inset-y-0 left-0 lg:static transition-all duration-300 ease-in-out safe-bottom',
        sidebarOpen ? 'translate-x-0 shadow-2xl w-72 max-w-[85vw]' : '-translate-x-full lg:translate-x-0 w-72',
        isCollapsed ? 'lg:w-20' : 'lg:w-64'
      ]"
    >
      <!-- Brand Logo & Collapse Trigger -->
      <div class="p-3.5 sm:p-4 border-b border-[var(--border-subtle)] flex items-center justify-between min-h-[64px]">
        <!-- Full Brand (Expanded or Mobile) -->
        <router-link
          v-if="!compact"
          to="/"
          class="flex flex-col gap-0.5 overflow-hidden"
        >
          <img
            :src="isDarkTheme ? '/images/logo-dark.png' : '/images/logo-light.png'"
            alt="Emisha Academy"
            class="h-7 sm:h-8 w-auto max-w-[155px] object-contain"
          />
          <span class="text-[8.5px] text-[var(--brand-gold)] font-bold tracking-wider uppercase pl-0.5 truncate">
            {{ $t('nav.student_portal') }}
          </span>
        </router-link>

        <!-- Compact Monogram Logo (Collapsed on Desktop) -->
        <router-link
          v-else
          to="/student/dashboard"
          class="mx-auto flex items-center justify-center w-10 h-10 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-accent)] text-[#D4AF37] font-black text-base shadow-xs group"
          title="Emisha Student Portal"
        >
          <span class="group-hover:scale-110 transition-transform">EA</span>
        </router-link>

        <!-- Desktop Collapse Button -->
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

        <!-- Mobile Drawer Close Button -->
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
        
        <!-- Section 1: Learning Menu Header -->
        <div v-if="!compact" class="px-3 py-1.5 text-[10px] font-bold text-[var(--text-secondary)] uppercase tracking-wider">
          {{ themeStore.locale === 'bn' ? 'লার্নিং মেনু' : 'Learning Menu' }}
        </div>
        <div v-else class="my-2 border-t border-[var(--border-subtle)]"></div>

        <!-- 1. Dashboard -->
        <router-link 
          to="/student/dashboard" 
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('nav.dashboard')"
        >
          <svg class="w-4 h-4 text-[#D4AF37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="7" height="9" x="3" y="3" rx="1"/>
            <rect width="7" height="5" x="14" y="3" rx="1"/>
            <rect width="7" height="9" x="14" y="12" rx="1"/>
            <rect width="7" height="5" x="3" y="16" rx="1"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('nav.dashboard') }}</span>
        </router-link>

        <!-- 2. My Courses -->
        <router-link 
          to="/student/courses" 
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('nav.my_courses')"
        >
          <svg class="w-4 h-4 text-sky-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('nav.my_courses') }}</span>
        </router-link>

        <!-- My Notes -->
        <router-link
          to="/student/notes"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="themeStore.locale === 'bn' ? 'আমার নোটস' : 'My Notes'"
        >
          <svg class="w-4 h-4 text-violet-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9"/>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ themeStore.locale === 'bn' ? 'আমার নোটস' : 'My Notes' }}</span>
        </router-link>

        <!-- Notifications -->
        <router-link
          to="/student/notifications"
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="themeStore.locale === 'bn' ? 'নোটিফিকেশন' : 'Notifications'"
        >
          <svg class="w-4 h-4 text-rose-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
          </svg>
          <span v-if="!compact" class="truncate flex-1">{{ themeStore.locale === 'bn' ? 'নোটিফিকেশন' : 'Notifications' }}</span>
          <span
            v-if="notificationStore.unreadCount > 0"
            :class="compact ? 'absolute top-1 right-1 w-2 h-2 p-0' : 'px-1.5 py-0.5'"
            class="rounded-full bg-rose-500 text-white text-[10px] font-black"
          ><template v-if="!compact">{{ notificationStore.unreadCount > 99 ? '99+' : notificationStore.unreadCount }}</template></span>
        </router-link>

        <!-- 3. Orders & Invoices -->
        <router-link 
          to="/student/orders" 
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('nav.orders')"
        >
          <svg class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('nav.orders') }}</span>
        </router-link>

        <!-- Section 2: Account Header -->
        <div v-if="!compact" class="pt-3 px-3 py-1.5 text-[10px] font-bold text-[var(--text-secondary)] uppercase tracking-wider">
          {{ themeStore.locale === 'bn' ? 'অ্যাকাউন্ট' : 'Account' }}
        </div>
        <div v-else class="my-2 border-t border-[var(--border-subtle)]"></div>

        <!-- 4. Settings -->
        <router-link 
          to="/student/settings" 
          @click="sidebarOpen = false"
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          active-class="bg-[var(--bg-elevated)] !text-[var(--brand-gold)] font-bold border border-[var(--border-accent)] shadow-xs"
          :title="$t('nav.settings')"
        >
          <svg class="w-4 h-4 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ $t('nav.settings') }}</span>
        </router-link>

        <!-- 5. Main Website -->
        <router-link 
          to="/" 
          :class="[
            'flex items-center rounded-xl transition-all touch-target group relative',
            compact ? 'justify-center p-2.5' : 'gap-3 px-3.5 py-2.5 text-sm font-medium justify-start hover:bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
          :title="themeStore.locale === 'bn' ? 'মেইন ওয়েবসাইট' : 'Main Website'"
        >
          <svg class="w-4 h-4 text-cyan-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="2" y1="12" x2="22" y2="12"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
          </svg>
          <span v-if="!compact" class="truncate">{{ themeStore.locale === 'bn' ? 'মেইন ওয়েবসাইট' : 'Main Website' }}</span>
        </router-link>
      </nav>

      <!-- User Info & Logout Footer -->
      <div class="p-3 sm:p-4 border-t border-[var(--border-subtle)] space-y-2.5">
        <!-- Phone: language switch lives here (header is too narrow) -->
        <div class="sm:hidden flex justify-center">
          <LanguageToggle variant="capsule-switch" />
        </div>
        <!-- Expanded User Profile -->
        <router-link
          v-if="!compact"
          to="/student/settings"
          class="flex items-center gap-3 px-1.5 py-1 rounded-xl hover:bg-[var(--bg-elevated)] transition-colors group"
        >
          <img
            :src="authStore.userAvatar"
            alt="Profile Avatar"
            class="w-9 h-9 rounded-xl border border-[var(--border-accent)] object-cover shrink-0 shadow-xs"
          />
          <div class="min-w-0 flex-1">
            <p class="text-xs font-bold text-[var(--text-primary)] group-hover:text-[var(--brand-gold)] transition-colors truncate">{{ authStore.userName || 'Student' }}</p>
            <p class="text-[10px] text-[var(--text-secondary)] truncate">{{ authStore.user?.email }}</p>
          </div>
        </router-link>

        <!-- Collapsed User Profile (Centered Avatar) -->
        <router-link
          v-else
          to="/student/settings"
          class="flex justify-center"
          :title="authStore.userName + ' (Student Settings)'"
        >
          <img
            :src="authStore.userAvatar"
            alt="Profile Avatar"
            class="w-10 h-10 rounded-xl border border-[var(--border-accent)] object-cover shrink-0 shadow-xs hover:scale-105 transition-transform"
          />
        </router-link>

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
            class="lg:hidden p-2 rounded-xl bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] touch-target cursor-pointer flex items-center justify-center"
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
          <router-link to="/student/dashboard" class="sm:hidden flex items-center min-w-0" aria-label="Emisha Academy">
            <img
              :src="isDarkTheme ? '/images/logo-dark.png' : '/images/logo-light.png'"
              alt="Emisha Academy"
              class="h-7 w-auto max-w-[110px] object-contain"
            />
          </router-link>
          <div class="hidden sm:block min-w-0">
            <h1 class="text-sm sm:text-base font-bold text-[var(--text-primary)] tracking-tight truncate">{{ $t('nav.student_portal') }}</h1>
          </div>
        </div>

        <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
          <!-- Language Switcher Capsule (in the drawer on phones) -->
          <div class="hidden sm:block">
            <LanguageToggle variant="capsule-switch" />
          </div>

          <!-- Theme Toggle Compact -->
          <ThemeToggle variant="compact" />

          <!-- New notice / module / lesson notifications -->
          <NotificationBell />

          <router-link 
            to="/courses" 
            class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--brand-gold)]/10 text-xs font-semibold text-[var(--brand-gold)] border border-[var(--border-accent)] transition-all touch-target"
          >
            <span>{{ $t('student.explore_new') }}</span>
          </router-link>

          <router-link to="/student/settings" class="flex items-center gap-2 pl-1.5 sm:pl-3 border-l border-[var(--border-subtle)] hover:opacity-80 transition-opacity touch-target" :aria-label="authStore.userName || 'Profile'">
            <img
              :src="authStore.userAvatar"
              alt="Profile Avatar"
              class="w-8 h-8 rounded-full border border-[var(--border-accent)] object-cover shrink-0 shadow-xs"
            />
            <span class="text-xs font-medium text-[var(--text-primary)] hidden md:inline truncate max-w-[120px]">{{ authStore.userName }}</span>
          </router-link>
        </div>
      </header>

      <!-- Page Body -->
      <!-- Extra bottom padding keeps the floating support buttons from covering the last actions -->
      <main class="p-4 sm:p-6 lg:p-8 pb-28 sm:pb-28 lg:pb-28 flex-1 overflow-y-auto">
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
import NotificationBell from '../components/shared/NotificationBell.vue';
import { useNotificationStore } from '../stores/notifications';

const notificationStore = useNotificationStore();

const sidebarOpen = ref(false);
const isCollapsed = ref(typeof window !== 'undefined' && localStorage.getItem('student_sidebar_collapsed') === 'true');
const authStore = useAuthStore();
const themeStore = useThemeStore();
const toast = useToastStore();
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
    localStorage.setItem('student_sidebar_collapsed', String(isCollapsed.value));
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
