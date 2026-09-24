<template>
  <div class="py-8 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 sm:space-y-16">
    
    <!-- 1. Atmospheric Hero Header -->
    <div class="text-center space-y-3 sm:space-y-4 max-w-3xl mx-auto">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-xs font-black uppercase tracking-wider shadow-xs">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
        <span>{{ themeStore.locale === 'bn' ? 'এভিয়েশন, টিকেটিং ও ভিসা ক্যারিয়ার নলেজ হাব' : 'Aviation & Visa Knowledge Hub' }}</span>
      </div>

      <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[var(--text-primary)] tracking-tight leading-tight">
        {{ themeStore.locale === 'bn' ? 'এভিয়েশন, এয়ার টিকেটিং ও ভিসা ক্যারিয়ার ব্লগ' : 'Industry Insights, GDS Tips & Career Articles' }}
      </h1>

      <p class="text-xs sm:text-sm lg:text-base text-[var(--text-secondary)] leading-relaxed max-w-2xl mx-auto">
        {{ themeStore.locale === 'bn'
          ? 'আন্তর্জাতিক এয়ারলাইন্স নিয়মাবলী, Sabre ও Galileo সিস্টেম টিপস এবং বিশ্বের প্রধান দেশসমূহের ভিসা ডকুমেন্টেশন নিয়ে আমাদের ট্রাভেল বিশেষজ্ঞদের বিশ্লেষণ।'
          : 'In-depth analyses, airline ticketing system guides, and global tourist visa checklists prepared by our expert travel faculty.' }}
      </p>

      <!-- Search Input Container -->
      <div class="pt-2 max-w-xl mx-auto">
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="themeStore.locale === 'bn' ? 'আর্টিকেলের শিরোনাম বা বিষয় দিয়ে খুঁজুন...' : 'Search articles by title or keyword...'"
            class="w-full px-5 py-3.5 pl-12 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 focus:border-[#D4AF37] text-xs sm:text-sm text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none transition-all shadow-sm"
          />
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)] flex items-center">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </span>
          <button
            v-if="searchQuery"
            @click="searchQuery = ''"
            type="button"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- 2. Category & Topic Filter Strip -->
    <div class="flex items-center justify-between gap-4 p-3 sm:p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xs overflow-x-auto no-scrollbar">
      <div class="flex items-center gap-2 text-xs min-w-max">
        <button
          type="button"
          class="px-4 py-2 rounded-xl transition-all touch-target cursor-pointer flex items-center gap-2 text-xs"
          :class="activeCat === '' 
            ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black shadow-xs' 
            : 'bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)] font-medium'"
          @click="filterCat('')"
        >
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'সকল আর্টিকেল' : 'All Articles' }}</span>
          <span class="text-[10px] px-1.5 py-0.5 rounded-md" :class="activeCat === '' ? 'bg-black/20 text-slate-950 font-black' : 'bg-[var(--bg-surface)] text-[var(--text-muted)]'">
            {{ formatNumber(totalArticlesCount, themeStore.locale) }}
          </span>
        </button>

        <button
          v-for="cat in categories"
          :key="cat.id"
          type="button"
          class="px-4 py-2 rounded-xl transition-all touch-target cursor-pointer flex items-center gap-2 text-xs"
          :class="activeCat === cat.slug 
            ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black shadow-xs' 
            : 'bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)] font-medium'"
          @click="filterCat(cat.slug)"
        >
          <span>{{ themeStore.locale === 'bn' ? cat.name_bn : cat.name_en }}</span>
          <span class="text-[10px] px-1.5 py-0.5 rounded-md" :class="activeCat === cat.slug ? 'bg-black/20 text-slate-950 font-black' : 'bg-[var(--bg-surface)] text-[var(--text-muted)]'">
            {{ formatNumber(cat.posts_count || 1, themeStore.locale) }}
          </span>
        </button>
      </div>
    </div>

    <!-- 3. Featured Spotlight Article (Shown when no search query and viewing all) -->
    <div v-if="!searchQuery && featuredPost && activeCat === '' && !loading" class="group p-6 sm:p-10 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 shadow-xl relative overflow-hidden transition-all">
      <!-- Ambient subtle background glow -->
      <div class="absolute -top-32 -right-32 w-80 h-80 bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-10 items-center relative z-10">
        
        <!-- Big Thumbnail (5 cols) -->
        <div class="lg:col-span-5 relative aspect-[16/10] lg:aspect-[4/3] rounded-2xl overflow-hidden shadow-lg bg-slate-950">
          <img
            :src="featuredPost.thumbnail || getBlogFallbackThumbnail()"
            :alt="featuredPostTitle"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
            loading="lazy"
            @error="onImageError($event, 'blog')"
          />
          <div class="absolute top-3.5 left-3.5">
            <span class="px-3 py-1 rounded-full bg-slate-950/90 backdrop-blur-md border border-[#D4AF37]/50 text-[#D4AF37] text-xs font-black shadow-md flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'স্পটলাইট আর্টিকেল' : 'Spotlight Story' }}</span>
            </span>
          </div>
        </div>

        <!-- Narrative & CTA (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
          <div class="flex items-center gap-3 text-xs text-[var(--text-muted)] font-medium">
            <span class="px-2.5 py-0.5 rounded-lg bg-[var(--brand-gold-subtle)] text-[#D4AF37] border border-[var(--border-accent)] font-extrabold text-[10px] uppercase">
              {{ themeStore.locale === 'bn' ? (featuredPost.category?.name_bn || 'ক্যারিয়ার গাইড') : (featuredPost.category?.name_en || 'Career Guide') }}
            </span>
            <span>•</span>
            <span class="inline-flex items-center gap-1">
              <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span>{{ featuredPost.reading_time || '5 মিনিট পাঠ' }}</span>
            </span>
            <span>•</span>
            <span class="inline-flex items-center gap-1">
              <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
              <span>{{ formatNumber(featuredPost.views_count || 1240, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'ভিউ' : 'views' }}</span>
            </span>
          </div>

          <router-link :to="`/blog/${featuredPost.slug}`" class="block">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors leading-snug">
              {{ featuredPostTitle }}
            </h2>
          </router-link>

          <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed sm:leading-loose line-clamp-3">
            {{ featuredPostSummary }}
          </p>

          <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-[var(--border-subtle)]">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] flex items-center justify-center text-xs font-bold shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
              </div>
              <div class="text-xs">
                <p class="font-bold text-[var(--text-primary)]">{{ featuredPost.author?.name || (themeStore.locale === 'bn' ? 'ইমিশা একাডেমি রিসার্চ টিম' : 'Emisha Research Team') }}</p>
                <p class="text-[10px] text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'এভিয়েশন ক্যারিয়ার গবেষক' : 'Aviation Career Specialist' }}</p>
              </div>
            </div>

            <router-link
              :to="`/blog/${featuredPost.slug}`"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:scale-105 transition-all text-center touch-target shrink-0"
            >
              {{ themeStore.locale === 'bn' ? 'সম্পূর্ণ আর্টিকেল পড়ুন →' : 'Read Full Article →' }}
            </router-link>
          </div>
        </div>

      </div>
    </div>

    <!-- 4. Articles Grid -->
    <div class="space-y-6">
      
      <!-- Section Sub-Header -->
      <div class="flex items-center justify-between pb-2 border-b border-[var(--border-subtle)]">
        <h3 class="text-base sm:text-xl font-black text-[var(--text-primary)]">
          {{ activeCatName }}
        </h3>
        <span class="text-xs text-[var(--text-muted)] font-medium">
          {{ formatNumber(filteredPosts.length, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি আর্টিকেল পাওয়া গেছে' : 'Articles' }}
        </span>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div v-for="i in 3" :key="i" class="h-96 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
      </div>

      <!-- Posts List -->
      <div v-else-if="filteredPosts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <BlogCard
          v-for="post in filteredPosts"
          :key="post.id"
          :post="post"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 sm:py-20 p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
        <div class="w-12 h-12 mx-auto rounded-2xl bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] flex items-center justify-center text-[#D4AF37]">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <h4 class="text-base font-bold text-[var(--text-primary)]">
          {{ themeStore.locale === 'bn' ? 'কোনো আর্টিকেল পাওয়া যায়নি।' : 'No articles match your search or filter.' }}
        </h4>
        <p class="text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'অন্য কোনো কিওয়ার্ড দিয়ে খুঁজুন অথবা সকল আর্টিকেল ফিল্টারে ফিরে যান।' : 'Try another search term or reset your category filter.' }}
        </p>
        <button
          type="button"
          @click="searchQuery = ''; activeCat = ''"
          class="px-5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-bold text-[var(--text-primary)] transition-all cursor-pointer"
        >
          {{ themeStore.locale === 'bn' ? 'সকল আর্টিকেল দেখুন' : 'Show All Articles' }}
        </button>
      </div>

    </div>

    <!-- 5. Knowledge Newsletter & Seminar Updates Banner -->
    <div class="p-6 sm:p-10 rounded-3xl bg-gradient-to-r from-blue-500/10 via-[var(--bg-elevated)] to-amber-500/10 border border-[var(--border-subtle)] shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
      <div class="space-y-1.5 text-center sm:text-left">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#D4AF37] text-[10px] font-extrabold uppercase tracking-wider">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'ফ্রি নলেজ ও সেমিনার আপডেট' : 'Free Knowledge & Webinar Updates' }}</span>
        </div>
        <h4 class="text-base sm:text-lg font-black text-[var(--text-primary)]">
          {{ themeStore.locale === 'bn' ? 'নতুন ভিসা পলিসি, এয়ারলাইন্স রুলস ও সেমিনারের খবর পেতে চান?' : 'Want Latest Visa Policies and Free Webinar Alerts?' }}
        </h4>
        <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
          {{ themeStore.locale === 'bn' ? 'আমাদের আসন্ন লাইভ সেমিনার ও গুরুত্বপূর্ণ আর্টিকেল সরাসরি দেখতে আমাদের ফেসবুক পেজে যুক্ত থাকুন।' : 'Follow our official community for live notifications and career workshops.' }}
        </p>
      </div>

      <a
        href="https://www.facebook.com/profile.php?id=61590103572746"
        target="_blank"
        rel="noopener noreferrer"
        class="px-6 py-3 rounded-2xl bg-[var(--bg-surface)] hover:bg-[#1877F2]/15 border border-[var(--border-subtle)] hover:border-[#1877F2] text-xs font-bold text-[var(--text-primary)] hover:text-[#1877F2] transition-all flex items-center gap-2 shrink-0 touch-target shadow-xs"
      >
        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95C18.05 21.45 22 17.19 22 12Z"/></svg>
        <span>{{ themeStore.locale === 'bn' ? 'ফেসবুকে ফলো করুন' : 'Follow on Facebook' }}</span>
      </a>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { formatNumber } from '../../utils/locale';
import BlogCard from '../../components/shared/BlogCard.vue';
import { onImageError, getBlogFallbackThumbnail } from '../../utils/imageFallback';

const themeStore = useThemeStore();
const loading = ref(true);
const posts = ref<any[]>([]);
const categories = ref<any[]>([]);
const activeCat = ref('');
const searchQuery = ref('');

const allCombinedPosts = computed(() => {
  return posts.value || [];
});

const featuredPost = computed(() => {
  const list = allCombinedPosts.value;
  return list.find((p) => p.is_featured) || list[0] || null;
});

const featuredPostTitle = computed(() => {
  if (!featuredPost.value) return '';
  return themeStore.locale === 'bn' 
    ? (featuredPost.value.title_bn || featuredPost.value.title_en) 
    : (featuredPost.value.title_en || featuredPost.value.title_bn);
});

const featuredPostSummary = computed(() => {
  if (!featuredPost.value) return '';
  return themeStore.locale === 'bn' 
    ? (featuredPost.value.summary_bn || featuredPost.value.summary_en) 
    : (featuredPost.value.summary_en || featuredPost.value.summary_bn);
});

const filteredPosts = computed(() => {
  let list = allCombinedPosts.value;

  if (activeCat.value) {
    list = list.filter((p) => p.category?.slug === activeCat.value);
  }

  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase().trim();
    list = list.filter((p) => {
      const bnTitle = (p.title_bn || '').toLowerCase();
      const enTitle = (p.title_en || '').toLowerCase();
      const bnSum = (p.summary_bn || '').toLowerCase();
      const enSum = (p.summary_en || '').toLowerCase();
      return bnTitle.includes(q) || enTitle.includes(q) || bnSum.includes(q) || enSum.includes(q);
    });
  }

  return list;
});

const totalArticlesCount = computed(() => {
  return allCombinedPosts.value.length;
});

const activeCatName = computed(() => {
  if (!activeCat.value) {
    return themeStore.locale === 'bn' ? 'সকল সাম্প্রতিক আর্টিকেল ও বিশ্লেষণ' : 'All Recent Articles & Analyses';
  }
  const cat = categories.value.find((c) => c.slug === activeCat.value);
  if (cat) {
    return themeStore.locale === 'bn' ? cat.name_bn : cat.name_en;
  }
  return themeStore.locale === 'bn' ? 'ফিল্টারকৃত আর্টিকেল' : 'Filtered Articles';
});

const filterCat = (slug: string) => {
  activeCat.value = slug;
};

const fetchPosts = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/public/blog');
    posts.value = res.data.data.posts;
    categories.value = res.data.data.categories;
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchPosts();
});
</script>
