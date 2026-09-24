<template>
  <div class="group relative flex flex-col rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[var(--brand-gold)]/60 shadow-md hover:shadow-2xl hover:shadow-[var(--brand-gold)]/10 transition-all duration-300 overflow-hidden hover:-translate-y-2 flex-1">
    
    <!-- Top Luxury Gradient Accent Line -->
    <div class="h-1 w-full bg-gradient-to-r from-amber-500 via-[#D4AF37] to-yellow-300 opacity-70 group-hover:opacity-100 transition-opacity"></div>

    <!-- 1. THUMBNAIL & FLOATING HUD BADGES -->
    <div class="relative aspect-[16/10] w-full overflow-hidden bg-slate-950">
      <img
        :src="course.thumbnail || getCourseFallbackThumbnail(categoryName)"
        :alt="courseTitle"
        class="w-full h-full object-cover group-hover:scale-106 transition-transform duration-700 ease-out"
        loading="lazy"
        @error="onImageError($event, 'course', categoryName)"
      />
      
      <!-- Gradient Scrim for Contrast & Badge Readability -->
      <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/25 to-black/40 pointer-events-none"></div>

      <!-- Top Floating Badges (Category & Discount) -->
      <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 z-10">
        <!-- Category Pill -->
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-950/80 backdrop-blur-md border border-white/15 text-amber-300 text-xs font-bold shadow-md">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
          <span>{{ categoryName }}</span>
        </span>

        <!-- Discount / Offer Badge -->
        <span
          v-if="discountPercent > 0"
          class="inline-flex items-center px-2.5 py-1 rounded-full bg-rose-600/95 backdrop-blur-sm text-white text-[11px] font-black tracking-wider uppercase shadow-lg shadow-rose-600/30"
        >
          -{{ discountPercent }}% OFF
        </span>
      </div>

      <!-- Bottom Floating Overlay (Learning Mode & Star Rating) -->
      <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between gap-2 z-10">
        <!-- Learning Format Badge -->
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-950/85 backdrop-blur-md border border-white/15 text-white text-[11px] font-bold shadow-sm">
          <span class="relative flex h-2 w-2">
            <span :class="course.format === 'live' ? 'bg-emerald-400' : 'bg-sky-400'" class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"></span>
            <span :class="course.format === 'live' ? 'bg-emerald-500' : 'bg-sky-500'" class="relative inline-flex rounded-full h-2 w-2"></span>
          </span>
          <span>{{ course.format === 'live' ? (themeStore.locale === 'bn' ? 'অফলাইন ল্যাব + লাইভ' : 'Offline Lab & Live') : (themeStore.locale === 'bn' ? 'অনলাইন ও সেলফ-পেসড' : 'Self-Paced') }}</span>
        </span>

        <!-- Star Rating Pill -->
        <div class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-950/85 backdrop-blur-md border border-amber-400/30 text-amber-400 text-xs font-bold shadow-sm">
          <svg class="w-3 h-3 fill-current text-[#D4AF37]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <span class="text-white">{{ course.average_rating || '4.9' }}</span>
          <span class="text-[10px] text-slate-300 font-medium">({{ formatNumber(course.total_reviews || 64, themeStore.locale) }})</span>
        </div>
      </div>
    </div>

    <!-- 2. CARD CONTENT BODY -->
    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-4">
      
      <div class="space-y-3">
        <!-- Micro-Meta Row (Duration, Level, Workstation) -->
        <div class="flex items-center justify-between gap-2 text-xs text-[var(--text-muted)] font-medium">
          <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span>{{ formatNumber(course.duration_weeks || 8, themeStore.locale) }} {{ $t('common.weeks') }}</span>
            <span class="text-[var(--border-subtle)]">•</span>
            <span>{{ formatLevel(course.level) }}</span>
          </div>

          <div v-if="course.is_featured" class="flex items-center gap-1 text-[#D4AF37] font-bold text-[11px]">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <span>{{ $t('common.featured') }}</span>
          </div>
        </div>

        <!-- Course Title -->
        <router-link :to="`/courses/${course.slug}`" class="block group/title">
          <h3 class="text-base sm:text-lg font-extrabold text-[var(--text-primary)] group-hover/title:text-[var(--brand-gold)] transition-colors line-clamp-2 leading-snug">
            {{ courseTitle }}
          </h3>
        </router-link>

        <!-- Course Short Description -->
        <p v-if="courseDescription" class="text-xs text-[var(--text-secondary)] line-clamp-2 leading-relaxed font-normal">
          {{ courseDescription }}
        </p>

        <!-- Key Feature Highlights (Clean & Modern Chips) -->
        <div class="grid grid-cols-1 min-[420px]:grid-cols-2 gap-1.5 sm:gap-2 pt-1">
          <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[11px] font-semibold text-[var(--text-primary)]">
            <svg class="w-3.5 h-3.5 text-[#D4AF37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="truncate">Sabre & Galileo GDS</span>
          </div>
          <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[11px] font-semibold text-[var(--text-primary)]">
            <svg class="w-3.5 h-3.5 text-[#D4AF37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="truncate">{{ themeStore.locale === 'bn' ? 'ল্যাব প্র্যাকটিস ও সার্টিফিকেট' : 'Lab Workstation & Cert' }}</span>
          </div>
        </div>
      </div>

      <!-- 2.5 BATCH SCHEDULE & LIVE URGENCY HUB (Animated Modern EdTech Module) -->
      <div
        v-if="activeBatch"
        class="relative overflow-hidden rounded-2xl p-3 sm:p-3.5 bg-gradient-to-br from-amber-500/10 via-[var(--bg-elevated)] to-rose-500/5 border border-amber-500/30 hover:border-amber-500/60 shadow-xs transition-all duration-300 space-y-2.5 group/batch"
      >
        <!-- Ambient Corner Glow Beacon -->
        <div class="absolute -top-6 -right-6 w-16 h-16 rounded-full bg-amber-500/20 blur-xl pointer-events-none group-hover/batch:bg-rose-500/25 transition-colors"></div>

        <!-- Top Header: Live Status & Seat Urgency Badge -->
        <div class="flex items-center justify-between gap-2 relative z-10">
          <!-- Live Enrollment Status Indicator -->
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[var(--bg-surface)]/90 backdrop-blur-md border border-[var(--border-subtle)] text-[10px] font-bold text-[var(--text-primary)]">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold uppercase tracking-wide">
              {{ themeStore.locale === 'bn' ? 'ভর্তি চলছে' : 'Enrolling Now' }}
            </span>
          </div>

          <!-- Burning Urgency Pill with Animated Flame -->
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-500/15 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-[11px] font-black tracking-wide shadow-xs shrink-0 animate-pulse">
            <svg class="w-3.5 h-3.5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z"/></svg>
            <span>{{ formatNumber(remainingSeats, themeStore.locale) }} {{ $t('common.seats_left') }}</span>
          </div>
        </div>

        <!-- Batch Title & Timing -->
        <div class="flex items-center gap-2 text-xs font-bold text-[var(--text-primary)] relative z-10">
          <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <span class="truncate leading-tight">{{ batchTitle }}</span>
        </div>

        <!-- Live Animated Progress Bar with Shimmer Streak -->
        <div class="space-y-1 relative z-10">
          <div class="w-full bg-[var(--bg-deep)] h-2 rounded-full overflow-hidden border border-[var(--border-subtle)]/70 p-0.5 relative">
            <div
              class="h-full bg-gradient-to-r from-amber-500 via-[#D4AF37] to-emerald-500 rounded-full transition-all duration-700 relative overflow-hidden"
              :style="{ width: `${enrollmentPercent}%` }"
            >
              <!-- Shimmer light sweep animation across the progress bar -->
              <div class="shimmer-bar absolute inset-0 bg-gradient-to-r from-transparent via-white/50 to-transparent"></div>
            </div>
          </div>

          <div class="flex items-center justify-between text-[10px] text-[var(--text-muted)] font-medium px-0.5">
            <span>{{ formatNumber(enrollmentPercent, themeStore.locale) }}% {{ themeStore.locale === 'bn' ? 'আসন পূর্ণ' : 'Seats Filled' }}</span>
            <span class="text-amber-500 font-semibold">{{ themeStore.locale === 'bn' ? 'দ্রুত বুক করুন' : 'Fast Filling' }}</span>
          </div>
        </div>
      </div>

      <!-- 3. PRICING & ACTION FOOTER -->
      <div class="pt-3.5 border-t border-[var(--border-subtle)] flex flex-wrap sm:flex-nowrap items-center justify-between gap-3">
        <!-- Price Column -->
        <div class="min-w-0">
          <div v-if="course.is_free" class="text-emerald-500 font-black text-xl">
            {{ $t('common.free') }}
          </div>
          <div v-else class="space-y-0.5">
            <div class="flex items-baseline gap-1.5 flex-wrap">
              <span class="text-lg sm:text-xl font-black text-[var(--text-primary)] tracking-tight">
                {{ formatCurrency(course.sale_price || course.regular_price, themeStore.locale) }}
              </span>
              <span v-if="course.sale_price && course.sale_price < course.regular_price" class="text-xs text-[var(--text-muted)] line-through">
                {{ formatCurrency(course.regular_price, themeStore.locale) }}
              </span>
            </div>
            <p v-if="savingsAmount > 0" class="text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400">
              {{ themeStore.locale === 'bn' ? `সাশ্রয় ${formatCurrency(savingsAmount, themeStore.locale)}` : `Save ${formatCurrency(savingsAmount, themeStore.locale)}` }}
            </p>
          </div>
        </div>

        <!-- CTA Button -->
        <router-link
          :to="`/courses/${course.slug}`"
          class="px-4 sm:px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] hover:brightness-110 text-slate-950 font-extrabold text-xs sm:text-sm shadow-md hover:shadow-lg hover:shadow-[#D4AF37]/25 transition-all transform active:scale-95 flex items-center justify-center gap-1.5 touch-target shrink-0 w-full sm:w-auto"
        >
          <span>{{ $t('common.view_details') }}</span>
          <span class="text-sm leading-none transition-transform group-hover:translate-x-1">→</span>
        </router-link>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { useThemeStore } from '../../stores/theme';
import { formatCurrency, formatNumber, getLocalized } from '../../utils/locale';
import { onImageError, getCourseFallbackThumbnail } from '../../utils/imageFallback';

const props = defineProps<{
  course: any;
}>();

const { t } = useI18n();
const themeStore = useThemeStore();

const courseTitle = computed(() => {
  return getLocalized(props.course, 'title', themeStore.locale) || props.course.title || 'Professional Course';
});

const courseDescription = computed(() => {
  return getLocalized(props.course, 'short_description', themeStore.locale) || 
         getLocalized(props.course, 'description', themeStore.locale) || '';
});

const categoryName = computed(() => {
  if (!props.course.category) return themeStore.locale === 'bn' ? 'এয়ার টিকেটিং ও এভিয়েশন' : 'Air Ticketing & Aviation';
  return getLocalized(props.course.category, 'name', themeStore.locale) || 
         getLocalized(props.course.category, 'title', themeStore.locale) || 
         (themeStore.locale === 'bn' ? 'এয়ার টিকেটিং ও এভিয়েশন' : 'Air Ticketing & Aviation');
});

const activeBatch = computed(() => {
  if (props.course.batches && props.course.batches.length > 0) {
    return props.course.batches[0];
  }
  return null;
});

const batchTitle = computed(() => {
  if (!activeBatch.value) return '';
  return getLocalized(activeBatch.value, 'title', themeStore.locale) || 
         (themeStore.locale === 'bn' ? `ব্যাচ #${activeBatch.value.batch_number}` : `Batch #${activeBatch.value.batch_number}`);
});

const remainingSeats = computed(() => {
  if (!activeBatch.value) return 4;
  const capacity = activeBatch.value.seat_capacity || 20;
  const enrolled = activeBatch.value.enrolled_students || 0;
  return Math.max(1, capacity - enrolled);
});

const enrollmentPercent = computed(() => {
  if (!activeBatch.value) return 80;
  const capacity = activeBatch.value.seat_capacity || 20;
  const enrolled = activeBatch.value.enrolled_students || 0;
  return Math.min(95, Math.max(25, Math.round((enrolled / capacity) * 100)));
});

const discountPercent = computed(() => {
  const reg = Number(props.course.regular_price || 0);
  const sale = Number(props.course.sale_price || 0);
  if (reg > 0 && sale > 0 && sale < reg) {
    return Math.round(((reg - sale) / reg) * 100);
  }
  return 0;
});

const savingsAmount = computed(() => {
  const reg = Number(props.course.regular_price || 0);
  const sale = Number(props.course.sale_price || 0);
  if (reg > 0 && sale > 0 && sale < reg) {
    return reg - sale;
  }
  return 0;
});

const formatLevel = (level: string) => {
  if (themeStore.locale === 'bn') {
    switch (level) {
      case 'beginner': return 'বিগিনার';
      case 'intermediate': return 'ইন্টারমিডিয়েট';
      case 'advanced': return 'অ্যাডভান্সড';
      default: return 'প্রফেশনাল';
    }
  }
  switch (level) {
    case 'beginner': return 'Beginner';
    case 'intermediate': return 'Intermediate';
    case 'advanced': return 'Advanced';
    default: return 'Professional';
  }
};
</script>

<style scoped>
.shimmer-bar {
  animation: shimmerSweep 2.2s infinite ease-in-out;
}

@keyframes shimmerSweep {
  0% {
    transform: translateX(-100%);
  }
  50%, 100% {
    transform: translateX(200%);
  }
}
</style>
