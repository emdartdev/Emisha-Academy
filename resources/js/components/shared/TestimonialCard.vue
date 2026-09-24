<template>
  <div class="group relative flex flex-col justify-between rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 p-6 sm:p-7 shadow-lg hover:shadow-2xl hover:shadow-[#D4AF37]/10 transition-all duration-300 space-y-5 overflow-hidden hover:-translate-y-1">
    
    <!-- Top Gold Accent Glow -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#D4AF37] via-amber-300 to-[#F7E7A9] opacity-70 group-hover:opacity-100 transition-opacity"></div>

    <!-- Header: Stars Rating & Course Tag -->
    <div class="flex items-center justify-between gap-2">
      <div class="flex items-center gap-1 text-[#D4AF37]">
        <svg v-for="i in (testimonial.rating || 5)" :key="i" class="w-4 h-4 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      </div>
      <span v-if="courseName" class="px-3 py-1 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[10px] sm:text-[11px] font-bold text-[#D4AF37] truncate max-w-[200px]">
        {{ courseName }}
      </span>
    </div>

    <!-- Quote Body -->
    <div class="relative space-y-2">
      <span class="text-3xl text-[#D4AF37]/30 font-serif leading-none select-none">“</span>
      <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed font-medium">
        {{ quoteText }}
      </p>
    </div>

    <!-- Student Author Info & Verified Placement Badge -->
    <div class="flex items-center justify-between gap-3 pt-4 border-t border-[var(--border-subtle)]">
      <div class="flex items-center gap-3 min-w-0">
        <img
          :src="testimonial.avatar || getInitialsAvatar(studentName)"
          :alt="studentName"
          class="w-11 h-11 rounded-2xl object-cover border-2 border-[#D4AF37]/40 shadow-xs shrink-0 bg-slate-900"
          loading="lazy"
          @error="onImageError($event, 'avatar', studentName)"
        />
        <div class="min-w-0">
          <h4 class="text-xs sm:text-sm font-extrabold text-[var(--text-primary)] truncate">{{ studentName }}</h4>
          <p class="text-[11px] text-[var(--text-muted)] font-semibold truncate">{{ studentRole }}</p>
        </div>
      </div>

      <span class="shrink-0 text-emerald-500 bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20 text-[10px] font-bold flex items-center gap-1.5" title="Verified Graduate">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span>{{ themeStore.locale === 'bn' ? 'সফল শিক্ষার্থী' : 'Verified' }}</span>
      </span>
    </div>

  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useThemeStore } from '../../stores/theme';
import { onImageError, getInitialsAvatar } from '../../utils/imageFallback';

const props = defineProps<{
  testimonial: any;
}>();

const themeStore = useThemeStore();

const studentName = computed(() => {
  return themeStore.locale === 'bn' ? (props.testimonial.student_name_bn || props.testimonial.student_name_en) : (props.testimonial.student_name_en || props.testimonial.student_name_bn);
});

const studentRole = computed(() => {
  return themeStore.locale === 'bn' ? (props.testimonial.student_role_bn || props.testimonial.student_role_en) : (props.testimonial.student_role_en || props.testimonial.student_role_bn);
});

const quoteText = computed(() => {
  return themeStore.locale === 'bn' ? (props.testimonial.quote_bn || props.testimonial.quote_en) : (props.testimonial.quote_en || props.testimonial.quote_bn);
});

const courseName = computed(() => {
  return themeStore.locale === 'bn' ? (props.testimonial.course_name_bn || props.testimonial.course_name_en) : (props.testimonial.course_name_en || props.testimonial.course_name_bn);
});
</script>
