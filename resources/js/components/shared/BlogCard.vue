<template>
  <div class="group flex flex-col rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden relative">
    
    <!-- Thumbnail Image Container -->
    <div class="relative aspect-[16/10] w-full overflow-hidden bg-slate-950">
      <img
        :src="post.thumbnail || getBlogFallbackThumbnail()"
        :alt="postTitle"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        loading="lazy"
        @error="onImageError($event, 'blog')"
      />
      
      <!-- Gradient Fade Overlay -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>

      <!-- Category Pill Badge -->
      <div v-if="post.category" class="absolute top-3.5 left-3.5 z-10">
        <span class="px-3 py-1 rounded-full bg-slate-950/85 backdrop-blur-md border border-[#D4AF37]/40 text-[#D4AF37] text-[10px] sm:text-xs font-black tracking-wide shadow-md">
          {{ themeStore.locale === 'bn' ? post.category.name_bn : post.category.name_en }}
        </span>
      </div>

      <!-- Featured Marker -->
      <div v-if="post.is_featured" class="absolute top-3.5 right-3.5 z-10">
        <span class="px-2.5 py-0.5 rounded-full bg-amber-500/90 text-slate-950 text-[10px] font-black shadow-md flex items-center gap-1">
          <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'ফিচার্ড' : 'Featured' }}</span>
        </span>
      </div>
    </div>

    <!-- Card Content Body -->
    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-4">
      <div class="space-y-2.5">
        
        <!-- Metadata Strip -->
        <div class="flex items-center gap-2.5 text-[11px] text-[var(--text-muted)] font-medium flex-wrap">
          <span class="inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span>{{ post.reading_time || (themeStore.locale === 'bn' ? '৫ মিনিট পাঠ' : '5 mins read') }}</span>
          </span>
          <span>•</span>
          <span class="inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            <span>{{ formatNumber(post.views_count || 350, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'ভিউ' : 'views' }}</span>
          </span>
        </div>

        <!-- Article Title -->
        <router-link :to="`/blog/${post.slug}`" class="block">
          <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors line-clamp-2 leading-snug">
            {{ postTitle }}
          </h3>
        </router-link>

        <!-- Summary -->
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] line-clamp-2 leading-relaxed">
          {{ postSummary }}
        </p>
      </div>

      <!-- Author Footer & Read Action -->
      <div class="flex items-center justify-between pt-3.5 border-t border-[var(--border-subtle)] text-xs gap-3">
        <div class="flex items-center gap-2 min-w-0">
          <div class="w-6 h-6 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] flex items-center justify-center text-[10px] font-bold shrink-0">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
          </div>
          <span class="text-xs font-bold text-[var(--text-primary)] truncate">
            {{ post.author?.name || (themeStore.locale === 'bn' ? 'ইমিশা রিসার্চ টিম' : 'Emisha Research Team') }}
          </span>
        </div>

        <router-link
          :to="`/blog/${post.slug}`"
          class="inline-flex items-center gap-1 text-xs font-black text-[#D4AF37] hover:underline shrink-0 group-hover:translate-x-0.5 transition-transform"
        >
          <span>{{ themeStore.locale === 'bn' ? 'পড়ুন' : 'Read' }}</span>
          <span>→</span>
        </router-link>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useThemeStore } from '../../stores/theme';
import { formatNumber } from '../../utils/locale';
import { onImageError, getBlogFallbackThumbnail } from '../../utils/imageFallback';

const props = defineProps<{
  post: any;
}>();

const themeStore = useThemeStore();

const postTitle = computed(() => {
  return themeStore.locale === 'bn' ? (props.post.title_bn || props.post.title_en) : (props.post.title_en || props.post.title_bn);
});

const postSummary = computed(() => {
  return themeStore.locale === 'bn' ? (props.post.summary_bn || props.post.summary_en) : (props.post.summary_en || props.post.summary_bn);
});
</script>
