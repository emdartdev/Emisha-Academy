<template>
  <div class="group flex flex-col rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden relative">
    
    <!-- 3D Luxury Book Perspective Showcase Stage -->
    <div class="p-6 sm:p-7 bg-gradient-to-b from-[var(--bg-deep)] to-[var(--bg-surface)] flex items-center justify-center relative overflow-hidden border-b border-[var(--border-subtle)]">
      
      <!-- Subtle Ambient Glow -->
      <div class="absolute inset-0 bg-gradient-to-tr from-[#D4AF37]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>

      <!-- Format & Free Tag -->
      <div class="absolute top-3.5 left-3.5 right-3.5 flex items-center justify-between gap-2 z-10">
        <span class="px-2.5 py-0.5 rounded-full bg-slate-950/85 backdrop-blur-md border border-white/15 text-[#D4AF37] text-[10px] font-black uppercase tracking-wider shadow-xs">
          PDF Guide
        </span>

        <span v-if="ebook.is_free" class="px-2.5 py-0.5 rounded-full bg-emerald-500/90 text-slate-950 text-[10px] font-black uppercase tracking-wider shadow-xs">
          {{ themeStore.locale === 'bn' ? 'ফ্রি' : 'Free' }}
        </span>
        <span v-else-if="discountPercent > 0" class="px-2 py-0.5 rounded-full bg-red-500/90 text-white text-[10px] font-black">
          -{{ formatNumber(discountPercent, themeStore.locale) }}% OFF
        </span>
      </div>

      <!-- 3D Realistic Book with Spine and Shadows -->
      <div class="relative w-32 h-44 sm:w-36 sm:h-48 rounded-r-xl rounded-l-xs overflow-hidden shadow-2xl group-hover:scale-105 group-hover:-rotate-1 transition-transform duration-500 border-r-2 border-b-2 border-black/40 mt-3 bg-slate-900">
        <img
          :src="ebook.cover_image || getEbookFallbackCover(ebookTitle)"
          :alt="ebookTitle"
          class="w-full h-full object-cover"
          loading="lazy"
          @error="onImageError($event, 'ebook', ebookTitle)"
        />
        <!-- 3D Book Spine Gradient Shadow -->
        <div class="absolute inset-y-0 left-0 w-3.5 bg-gradient-to-r from-black/50 via-black/20 to-transparent pointer-events-none"></div>
        <!-- Gloss Shine Reflection -->
        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
      </div>
    </div>

    <!-- Content Body (Spacious & Clean Alignment) -->
    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-4">
      
      <div class="space-y-2">
        <!-- Specs Bar -->
        <div class="flex items-center gap-2 text-[11px] text-[var(--text-muted)] font-medium">
          <span class="inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span>{{ formatNumber(ebook.pages_count || 95, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'পৃষ্ঠা' : 'Pages' }}</span>
          </span>
          <span>•</span>
          <span class="inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <span>{{ ebook.file_size || '6.8 MB' }}</span>
          </span>
          <span>•</span>
          <span class="text-amber-400 font-bold inline-flex items-center gap-1">
            <svg class="w-3 h-3 fill-current text-[#D4AF37]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <span>{{ ebook.rating || '4.95' }}</span>
          </span>
        </div>

        <!-- Title -->
        <router-link :to="`/ebooks/${ebook.slug}`" class="block">
          <h3 class="text-sm sm:text-base font-black text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors line-clamp-2 leading-snug">
            {{ ebookTitle }}
          </h3>
        </router-link>

        <!-- Author / Research Team -->
        <p class="text-[11px] text-[var(--text-secondary)] flex items-center gap-1.5">
          <span class="text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'সংকলন:' : 'By:' }}</span>
          <span class="font-bold text-[var(--text-primary)] truncate">{{ authorName }}</span>
        </p>

        <!-- Summary -->
        <p v-if="ebookSummary" class="text-xs text-[var(--text-secondary)] line-clamp-2 leading-relaxed pt-0.5">
          {{ ebookSummary }}
        </p>
      </div>

      <!-- Price & Download CTA Footer -->
      <div class="pt-3.5 border-t border-[var(--border-subtle)] flex items-center justify-between gap-3">
        <div>
          <span v-if="ebook.is_free" class="text-emerald-500 font-black text-xs block">
            {{ themeStore.locale === 'bn' ? '১০০% ফ্রি ডাউনলোড' : 'Free Download' }}
          </span>
          <div v-else class="flex items-baseline gap-1.5 flex-wrap">
            <span class="text-sm sm:text-base font-black text-[var(--text-primary)]">
              {{ formatCurrency(ebook.sale_price || ebook.regular_price, themeStore.locale) }}
            </span>
            <span v-if="ebook.sale_price && ebook.sale_price < ebook.regular_price" class="text-[11px] text-[var(--text-muted)] line-through">
              {{ formatCurrency(ebook.regular_price, themeStore.locale) }}
            </span>
          </div>
        </div>

        <router-link
          :to="`/ebooks/${ebook.slug}`"
          class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-md hover:scale-105 transition-all touch-target inline-flex items-center justify-center gap-1.5 shrink-0"
        >
          <span>{{ themeStore.locale === 'bn' ? 'ডাউনলোড' : 'Download' }}</span>
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        </router-link>
      </div>

    </div>

  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useThemeStore } from '../../stores/theme';
import { formatCurrency, formatNumber } from '../../utils/locale';
import { onImageError, getEbookFallbackCover } from '../../utils/imageFallback';

const props = defineProps<{
  ebook: any;
}>();

const themeStore = useThemeStore();

const ebookTitle = computed(() => {
  return themeStore.locale === 'bn' ? (props.ebook.title_bn || props.ebook.title_en) : (props.ebook.title_en || props.ebook.title_bn);
});

const authorName = computed(() => {
  return themeStore.locale === 'bn' 
    ? (props.ebook.author_name_bn || props.ebook.author_name_en || 'ইমিশা একাডেমি রিসার্চ টিম') 
    : (props.ebook.author_name_en || props.ebook.author_name_bn || 'Emisha Academy Research Team');
});

const ebookSummary = computed(() => {
  return themeStore.locale === 'bn' ? (props.ebook.summary_bn || props.ebook.summary_en) : (props.ebook.summary_en || props.ebook.summary_bn);
});

const discountPercent = computed(() => {
  if (!props.ebook.regular_price || !props.ebook.sale_price) return 0;
  const reg = Number(props.ebook.regular_price);
  const sale = Number(props.ebook.sale_price);
  if (reg <= sale || reg <= 0) return 0;
  return Math.round(((reg - sale) / reg) * 100);
});
</script>
