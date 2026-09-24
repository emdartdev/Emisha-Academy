<template>
  <div class="space-y-8">
    
    <!-- 1. WELCOME BANNER -->
    <div class="p-5 sm:p-8 rounded-3xl bg-gradient-to-r from-[var(--bg-elevated)] via-[var(--bg-card)] to-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-6">
      <div class="flex items-center gap-3 sm:gap-5">
        <div class="relative group cursor-pointer" @click="showAvatarModal = true" title="অবতার পরিবর্তন করতে ক্লিক করুন">
          <img
            :src="authStore.userAvatar"
            alt="User Avatar"
            class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover border-2 border-[#D4AF37]/60 shadow-lg shrink-0 transition-transform group-hover:scale-105"
          />
          <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-[#D4AF37] text-slate-950 flex items-center justify-center text-[10px] font-black border-2 border-[var(--bg-card)] shadow-xs">
            ✎
          </div>
        </div>
        <div class="space-y-0.5 sm:space-y-1 min-w-0">
          <div class="flex items-center gap-2">
            <h2 class="text-lg sm:text-2xl font-black text-[var(--text-primary)] truncate">
              {{ $t('student.welcome') }}, {{ authStore.userName }}!
            </h2>
          </div>
          <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
            {{ $t('student.welcome_sub') }}
          </p>
          <button
            type="button"
            @click="showAvatarModal = true"
            class="text-[11px] font-bold text-[var(--brand-gold)] hover:underline inline-flex items-center gap-1 mt-1 cursor-pointer"
          >
            <span>✨ {{ themeStore.locale === 'bn' ? 'অবতার পরিবর্তন করুন' : 'Change Avatar' }}</span>
          </button>
        </div>
      </div>

      <div class="flex items-center gap-2.5 w-full sm:w-auto">
        <button
          type="button"
          @click="showAvatarModal = true"
          class="hidden md:inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] text-xs font-bold text-[var(--brand-gold)] border border-[var(--border-accent)] transition-all touch-target cursor-pointer"
        >
          <span>🎨 {{ themeStore.locale === 'bn' ? 'অবতার গ্যালারি' : 'Avatar Gallery' }}</span>
        </button>

        <router-link
          to="/courses"
          class="px-5 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[#D4AF37] text-[var(--text-primary)] hover:text-slate-950 text-xs font-bold border border-[var(--border-subtle)] transition-all shadow-sm touch-target inline-flex items-center justify-center shrink-0 w-full sm:w-auto"
        >
          {{ $t('student.explore_new') }}
        </router-link>
      </div>
    </div>

    <!-- 2. STATS CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
      <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] space-y-1">
        <p class="text-xs text-[var(--text-muted)]">{{ $t('student.total_courses') }}</p>
        <h3 class="text-xl sm:text-2xl font-black text-[var(--text-primary)]">{{ formatNumber(stats.total_enrolled, themeStore.locale) }}</h3>
      </div>

      <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] space-y-1">
        <p class="text-xs text-[var(--text-muted)]">{{ $t('student.in_progress') }}</p>
        <h3 class="text-xl sm:text-2xl font-black text-[#D4AF37]">{{ formatNumber(stats.in_progress, themeStore.locale) }}</h3>
      </div>

      <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] space-y-1">
        <p class="text-xs text-[var(--text-muted)]">{{ $t('student.completed_courses') }}</p>
        <h3 class="text-xl sm:text-2xl font-black text-emerald-500">{{ formatNumber(stats.completed, themeStore.locale) }}</h3>
      </div>

      <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] space-y-1">
        <p class="text-xs text-[var(--text-muted)]">{{ $t('student.certificates') }}</p>
        <h3 class="text-xl sm:text-2xl font-black text-sky-400">{{ formatNumber(stats.certificates_earned, themeStore.locale) }}</h3>
      </div>
    </div>

    <!-- 3. STUDENT NOTICE BOARD (Dynamic & Targeted) -->
    <section class="space-y-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-xl bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30 flex items-center justify-center">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          </div>
          <div>
            <h3 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'অফিসিয়াল নোটিশ বোর্ড' : 'Student Notice Board' }}
            </h3>
            <p class="text-[11px] text-[var(--text-muted)]">
              {{ themeStore.locale === 'bn' ? 'আপনার কোর্স ও একাডেমির গুরুত্বপূর্ণ আপডেটসমূহ' : 'Important announcements for your courses & academy' }}
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <span
            v-if="unreadNoticesCount > 0"
            class="px-2.5 py-1 rounded-full bg-rose-500/15 text-rose-500 border border-rose-500/30 text-[11px] font-bold"
          >
            {{ formatNumber(unreadNoticesCount, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি নতুন' : 'new' }}
          </span>

          <button
            v-if="unreadNoticesCount > 0"
            type="button"
            @click="markAllNoticesRead"
            class="text-[11px] text-[#D4AF37] font-semibold hover:underline cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'সব পড়া হয়েছে' : 'Mark all read' }}
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="noticesLoading" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div v-for="i in 2" :key="i" class="h-32 rounded-2xl bg-[var(--bg-elevated)] animate-pulse"></div>
      </div>

      <!-- Notices Cards Grid -->
      <div v-else-if="notices.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
          v-for="notice in notices"
          :key="notice.id"
          @click="openNoticeDetail(notice)"
          :class="[
            'p-4 sm:p-5 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between space-y-3 relative overflow-hidden',
            notice.is_read
              ? 'bg-[var(--bg-card)] border-[var(--border-subtle)] hover:border-[var(--border-accent)]'
              : 'bg-[var(--bg-surface)] border-[var(--brand-gold)]/60 ring-1 ring-[#D4AF37]/20 shadow-md'
          ]"
        >
          <!-- Left accent line for unread -->
          <div
            v-if="!notice.is_read"
            class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-[#D4AF37] to-amber-500"
          ></div>

          <div class="space-y-2">
            <!-- Badges Row -->
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-1.5 flex-wrap">
                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase" :class="getTypeBadgeClass(notice.notice_type)">
                  {{ notice.notice_type }}
                </span>

                <span v-if="notice.priority === 'urgent'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-500 border border-rose-500/30 uppercase">
                  <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                  <span>URGENT</span>
                </span>
                <span v-else-if="notice.priority === 'important'" class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-500 border border-amber-500/30 uppercase">
                  IMPORTANT
                </span>
              </div>

              <span v-if="!notice.is_read" class="px-2 py-0.5 rounded text-[10px] font-black bg-rose-500 text-white uppercase tracking-wider">
                NEW
              </span>
              <span v-else class="text-[10px] text-[var(--text-muted)] font-medium">
                Read
              </span>
            </div>

            <!-- Title & Excerpt -->
            <h4 class="text-sm font-bold text-[var(--text-primary)] line-clamp-1 group-hover:text-[#D4AF37] transition-colors">
              {{ notice.title }}
            </h4>
            <p class="text-xs text-[var(--text-secondary)] line-clamp-2 leading-relaxed">
              {{ notice.excerpt || notice.content }}
            </p>
          </div>

          <!-- Bottom Meta & Course Target -->
          <div class="flex items-center justify-between pt-2 border-t border-[var(--border-subtle)] text-[11px] text-[var(--text-muted)]">
            <span v-if="notice.visibility === 'all_students'" class="font-semibold text-[#D4AF37]">
              {{ themeStore.locale === 'bn' ? 'সকল শিক্ষার্থী' : 'All Students' }}
            </span>
            <span v-else-if="notice.courses && notice.courses.length > 0" class="font-semibold text-sky-400 truncate max-w-[180px]">
              {{ themeStore.locale === 'bn' ? notice.courses[0].title_bn : notice.courses[0].title_en }}
            </span>
            <span v-else>
              {{ themeStore.locale === 'bn' ? 'কোর্স নোটিশ' : 'Course Notice' }}
            </span>

            <span>{{ formatDate(notice.published_at || notice.created_at) }}</span>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="p-6 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] text-center space-y-1.5">
        <p class="text-xs font-bold text-[var(--text-primary)]">{{ themeStore.locale === 'bn' ? 'কোনো নতুন নোটিশ নেই' : 'No new notices' }}</p>
        <p class="text-[11px] text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'আপনি সব নোটিশ পড়েছেন।' : "You're all caught up." }}</p>
      </div>
    </section>

    <!-- 4. ENROLLED COURSES -->
    <div class="space-y-4">
      <div class="flex items-center justify-between">
        <h3 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">{{ $t('student.my_enrolled_courses') }}</h3>
        <router-link to="/student/courses" class="text-xs text-[#D4AF37] font-semibold hover:underline">
          {{ $t('student.view_all') }}
        </router-link>
      </div>

      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <div v-for="i in 2" :key="i" class="h-48 rounded-2xl bg-[var(--bg-elevated)] animate-pulse"></div>
      </div>

      <div v-else-if="enrollments.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <div
          v-for="enr in enrollments"
          :key="enr.id"
          class="p-5 sm:p-6 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] space-y-4 shadow-lg hover:border-[#D4AF37]/40 transition-all flex flex-col justify-between"
        >
          <div class="space-y-2">
            <div class="flex items-center justify-between text-xs">
              <span class="px-2.5 py-0.5 rounded-full bg-[#D4AF37]/10 text-[#D4AF37] font-bold">
                {{ themeStore.locale === 'bn' ? (enr.batch?.title_bn || enr.batch?.batch_number || 'Live Batch') : (enr.batch?.title_en || enr.batch?.batch_number || 'Live Batch') }}
              </span>
              <span class="text-emerald-500 font-bold">{{ formatNumber(Number(enr.progress_percentage).toFixed(0), themeStore.locale) }}% {{ $t('common.completed') }}</span>
            </div>

            <h4 class="text-base font-bold text-[var(--text-primary)] line-clamp-1">
              {{ themeStore.locale === 'bn' ? (enr.course?.title_bn || enr.course?.title_en) : (enr.course?.title_en || enr.course?.title_bn) }}
            </h4>
            <p class="text-xs text-[var(--text-secondary)]">
              {{ $t('common.instructor') }}: {{ themeStore.locale === 'bn' ? (enr.course?.instructor?.name_bn || 'ইমিশা মেন্টর') : (enr.course?.instructor?.name_en || 'Emisha Mentor') }}
            </p>
          </div>

          <!-- Progress Bar -->
          <div class="space-y-1.5">
            <div class="w-full bg-[var(--bg-surface)] h-2 rounded-full overflow-hidden">
              <div
                class="h-full bg-gradient-to-r from-[#D4AF37] to-emerald-400 rounded-full transition-all duration-500"
                :style="{ width: `${enr.progress_percentage}%` }"
              ></div>
            </div>
          </div>

          <router-link
            :to="`/student/courses/${enr.course_id}/learn`"
            class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 text-xs font-bold text-center hover:brightness-110 shadow-md transition-all touch-target inline-flex items-center justify-center"
          >
            {{ $t('student.enter_classroom') }}
          </router-link>
        </div>
      </div>

      <div v-else class="text-center py-12 p-6 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] space-y-3">
        <p class="text-xs text-[var(--text-muted)]">{{ $t('student.no_enrolled') }}</p>
        <router-link to="/courses" class="inline-block text-xs font-bold text-[#D4AF37] hover:underline">
          {{ $t('student.browse_courses') }}
        </router-link>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- NOTICE DETAIL MODAL -->
    <!-- ========================================================================= -->
    <div v-if="selectedNotice" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-2xl w-full max-h-[90dvh] overflow-y-auto p-6 sm:p-8 relative shadow-2xl space-y-5 safe-bottom">
        <div class="flex items-start justify-between border-b border-[var(--border-subtle)] pb-4">
          <div class="space-y-1 min-w-0 flex-1 pr-4">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase" :class="getTypeBadgeClass(selectedNotice.notice_type)">
                {{ selectedNotice.notice_type }}
              </span>
              <span v-if="selectedNotice.priority === 'urgent'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-500 border border-rose-500/30 uppercase">
                URGENT
              </span>
              <span v-if="selectedNotice.visibility === 'all_students'" class="text-[10px] text-[#D4AF37] font-bold">
                • {{ themeStore.locale === 'bn' ? 'সকল শিক্ষার্থী' : 'All Students' }}
              </span>
              <span v-else-if="selectedNotice.courses && selectedNotice.courses.length > 0" class="text-[10px] text-sky-400 font-bold">
                • {{ themeStore.locale === 'bn' ? selectedNotice.courses[0].title_bn : selectedNotice.courses[0].title_en }}
              </span>
            </div>
            <h3 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">{{ selectedNotice.title }}</h3>
            <p class="text-[11px] text-[var(--text-muted)]">Published: {{ formatDate(selectedNotice.published_at || selectedNotice.created_at) }}</p>
          </div>
          <button @click="selectedNotice = null" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>

        <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs sm:text-sm text-[var(--text-primary)] leading-relaxed whitespace-pre-line">
          {{ selectedNotice.content }}
        </div>

        <div class="flex justify-end pt-2">
          <button
            type="button"
            @click="selectedNotice = null"
            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'ঠিক আছে (Close)' : 'Got it' }}
          </button>
        </div>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- QUICK PROFILE AVATAR SELECTION MODAL -->
    <!-- ========================================================================= -->
    <div v-if="showAvatarModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-2xl w-full max-h-[90dvh] overflow-y-auto p-6 sm:p-8 relative shadow-2xl space-y-6 safe-bottom">
        <div class="flex items-start justify-between border-b border-[var(--border-subtle)] pb-4">
          <div>
            <h3 class="text-lg sm:text-xl font-black text-[var(--text-primary)] flex items-center gap-2">
              <span>🎨</span>
              <span>{{ themeStore.locale === 'bn' ? 'প্রোফাইল অবতার নির্বাচন করুন' : 'Choose Your Profile Avatar' }}</span>
            </h3>
            <p class="text-xs text-[var(--text-secondary)] mt-1">
              {{ themeStore.locale === 'bn' ? 'আপনার অ্যাকাউন্টের জন্য প্রি-বিল্ট ভেক্টর অবতার সিলেক্ট করুন (১০০% ক্লিন ও ভেক্টর)' : 'Select any pre-built vector avatar for your student profile' }}
            </p>
          </div>
          <button @click="showAvatarModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <!-- Avatars Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
          <button
            v-for="avatar in PREBUILT_AVATARS"
            :key="avatar.id"
            type="button"
            @click="selectAndSaveAvatar(avatar.id)"
            :disabled="savingAvatar"
            :class="[
              'p-3.5 rounded-2xl border transition-all text-left flex flex-col items-center gap-2.5 cursor-pointer relative group disabled:opacity-50',
              (authStore.user?.avatar === avatar.id || (!authStore.user?.avatar && avatar.id === 'gold_crest'))
                ? 'bg-[#D4AF37]/15 border-[#D4AF37] ring-2 ring-[#D4AF37]/40 shadow-lg'
                : 'bg-[var(--bg-deep)] border-[var(--border-subtle)] hover:border-[#D4AF37]/70 hover:bg-[var(--bg-elevated)]'
            ]"
          >
            <!-- Checkmark badge if active -->
            <div
              v-if="authStore.user?.avatar === avatar.id || (!authStore.user?.avatar && avatar.id === 'gold_crest')"
              class="absolute top-2 right-2 w-5 h-5 rounded-full bg-[#D4AF37] text-slate-950 flex items-center justify-center text-xs font-black shadow-sm"
            >
              ✓
            </div>

            <!-- Avatar Svg Icon -->
            <img
              :src="avatar.svgDataUri"
              :alt="avatar.name_en"
              class="w-16 h-16 rounded-xl object-contain drop-shadow-sm transition-transform group-hover:scale-110"
            />

            <!-- Name -->
            <div class="text-center w-full">
              <p :class="['text-xs font-bold truncate', (authStore.user?.avatar === avatar.id) ? 'text-[var(--brand-gold)]' : 'text-[var(--text-primary)]']">
                {{ themeStore.locale === 'bn' ? avatar.name_bn : avatar.name_en }}
              </p>
              <p class="text-[10px] text-[var(--text-muted)] capitalize truncate mt-0.5">
                {{ avatar.category }}
              </p>
            </div>
          </button>
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-[var(--border-subtle)]">
          <router-link
            to="/student/settings"
            class="text-xs text-[#D4AF37] hover:underline font-semibold"
          >
            {{ themeStore.locale === 'bn' ? 'সকল সেটিংস দেখতে যান →' : 'Go to full account settings →' }}
          </router-link>

          <button
            type="button"
            @click="showAvatarModal = false"
            class="px-5 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-card)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-bold text-xs cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'বন্ধ করুন (Close)' : 'Close' }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import apiClient from '../../api/client';
import { useAuthStore } from '../../stores/auth';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';
import { formatNumber } from '../../utils/locale';
import { PREBUILT_AVATARS } from '../../utils/avatars';

const route = useRoute();
const authStore = useAuthStore();
const themeStore = useThemeStore();
const toast = useToastStore();
const loading = ref(true);
const noticesLoading = ref(true);
const enrollments = ref<any[]>([]);
const notices = ref<any[]>([]);
const unreadNoticesCount = ref(0);
const selectedNotice = ref<any>(null);
const showAvatarModal = ref(false);
const savingAvatar = ref(false);

const selectAndSaveAvatar = async (avatarId: string) => {
  try {
    savingAvatar.value = true;
    const res = await apiClient.put('/auth/profile', {
      avatar: avatarId,
    });
    if (res.data.status === 'success') {
      const updated = res.data.data?.user || res.data.data;
      if (authStore.user) {
        authStore.user.avatar = updated?.avatar || avatarId;
      }
      toast.success(themeStore.locale === 'bn' ? 'প্রোফাইল অবতার সফলভাবে আপডেট করা হয়েছে!' : 'Profile avatar updated successfully!');
      showAvatarModal.value = false;
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'অবতার আপডেট ব্যর্থ হয়েছে' : 'Failed to update avatar'));
  } finally {
    savingAvatar.value = false;
  }
};

const stats = reactive({
  total_enrolled: 0,
  in_progress: 0,
  completed: 0,
  certificates_earned: 0,
});

const fetchDashboard = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/student/dashboard');
    enrollments.value = res.data.data.enrollments;
    Object.assign(stats, res.data.data.stats);
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const fetchStudentNotices = async () => {
  noticesLoading.value = true;
  try {
    const res = await apiClient.get('/student/notices');
    notices.value = res.data.data.notices.data || [];
    unreadNoticesCount.value = res.data.data.unread_count || 0;
  } catch (err) {
    console.error(err);
  } finally {
    noticesLoading.value = false;
  }
};

const openNoticeDetail = async (notice: any) => {
  selectedNotice.value = notice;
  if (!notice.is_read) {
    try {
      const res = await apiClient.post(`/student/notices/${notice.id}/read`);
      notice.is_read = true;
      unreadNoticesCount.value = res.data.data.unread_count;
    } catch (err) {
      console.error(err);
    }
  }
};

const markAllNoticesRead = async () => {
  try {
    await apiClient.post('/student/notices/read-all');
    notices.value.forEach(n => n.is_read = true);
    unreadNoticesCount.value = 0;
  } catch (err) {
    console.error(err);
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
    case 'course':
      return 'bg-sky-500/15 text-sky-400 border border-sky-500/30';
    default:
      return 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30';
  }
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleDateString(themeStore.locale === 'bn' ? 'bn-BD' : 'en-US', {
    month: 'short',
    day: 'numeric',
  });
};

// Notification deep-link: /student/dashboard?notice=ID opens that notice
const openNoticeFromQuery = async () => {
  const id = Number(route.query.notice);
  if (!id) return;
  const existing = notices.value.find((n) => n.id === id);
  if (existing) {
    openNoticeDetail(existing);
    return;
  }
  try {
    const res = await apiClient.get(`/student/notices/${id}`);
    selectedNotice.value = res.data.data;
    fetchStudentNotices();
  } catch {
    // notice expired or not targeted to this student
  }
};

watch(() => route.query.notice, () => openNoticeFromQuery());

onMounted(async () => {
  fetchDashboard();
  await fetchStudentNotices();
  openNoticeFromQuery();
});
</script>
