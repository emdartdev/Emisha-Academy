<template>
  <div class="py-8 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 sm:space-y-16">
    
    <!-- 1. Atmospheric Hero Header -->
    <div class="text-center space-y-3 sm:space-y-4 max-w-3xl mx-auto">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-xs font-black uppercase tracking-wider shadow-xs">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        <span>{{ themeStore.locale === 'bn' ? 'প্রফেশনাল ট্রাভেল ও ভিসা স্টাডি রিসোর্স' : 'Professional Travel & Visa Study Resources' }}</span>
      </div>

      <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[var(--text-primary)] tracking-tight leading-tight">
        {{ themeStore.locale === 'bn' ? 'এয়ার টিকেটিং, GDS ও ভিসা প্রসেসিং হ্যান্ডবুক' : 'Air Ticketing, GDS & Visa Reference Handbooks' }}
      </h1>

      <p class="text-xs sm:text-sm lg:text-base text-[var(--text-secondary)] leading-relaxed max-w-2xl mx-auto">
        {{ themeStore.locale === 'bn'
          ? 'আন্তর্জাতিক এয়ারলাইন্স নিয়মাবলী, Sabre ও Galileo কমান্ড রেফারেন্স এবং বিশ্বের প্রধান দেশসমূহের ভিসা চেকলিস্টের নির্ভরযোগ্য ডিজিটাল গাইডবুক।'
          : 'Essential digital reference manuals, Sabre & Galileo shortcut commands, and global tourist visa checklists compiled by expert practitioners.' }}
      </p>

      <!-- Search Input Container -->
      <div class="pt-2 max-w-xl mx-auto">
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="themeStore.locale === 'bn' ? 'হ্যান্ডবুকের শিরোনাম বা কিওয়ার্ড দিয়ে খুঁজুন...' : 'Search handbooks by title or keyword...'"
            class="w-full px-5 py-3.5 pl-12 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 focus:border-[#D4AF37] text-xs sm:text-sm text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none transition-all shadow-sm"
          />
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-[var(--text-muted)]">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </span>
          <button
            v-if="searchQuery"
            @click="searchQuery = ''"
            type="button"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer"
          ><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
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
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'সকল হ্যান্ডবুক ও গাইড' : 'All Handbooks' }}</span>
          <span class="text-[10px] px-1.5 py-0.5 rounded-md" :class="activeCat === '' ? 'bg-black/20 text-slate-950 font-black' : 'bg-[var(--bg-surface)] text-[var(--text-muted)]'">
            {{ formatNumber(allCombinedEbooks.length, themeStore.locale) }}
          </span>
        </button>

        <button
          v-for="cat in availableCategories"
          :key="cat.id"
          type="button"
          class="px-4 py-2 rounded-xl transition-all touch-target cursor-pointer flex items-center gap-2 text-xs"
          :class="activeCat === cat.slug 
            ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black shadow-xs' 
            : 'bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)] font-medium'"
          @click="filterCat(cat.slug)"
        >
          <span>{{ getCatIcon(cat.slug) }}</span>
          <span>{{ themeStore.locale === 'bn' ? cat.name_bn : cat.name_en }}</span>
          <span class="text-[10px] px-1.5 py-0.5 rounded-md" :class="activeCat === cat.slug ? 'bg-black/20 text-slate-950 font-black' : 'bg-[var(--bg-surface)] text-[var(--text-muted)]'">
            {{ formatNumber(cat.count, themeStore.locale) }}
          </span>
        </button>
      </div>
    </div>

    <!-- 3. Spotlight Hero Handbook Showcase (Shown when not searching and viewing all) -->
    <div v-if="!searchQuery && spotlightEbook && activeCat === '' && !loading" class="group p-6 sm:p-10 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 shadow-xl relative overflow-hidden transition-all">
      <!-- Ambient subtle background glow -->
      <div class="absolute -top-32 -right-32 w-80 h-80 bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center relative z-10">
        
        <!-- 3D Book Visual (4 cols) -->
        <div class="lg:col-span-4 flex items-center justify-center">
          <div class="relative w-44 h-60 sm:w-52 sm:h-72 rounded-r-2xl rounded-l-xs overflow-hidden shadow-2xl group-hover:scale-105 group-hover:-rotate-1 transition-transform duration-700 border-r-4 border-b-4 border-black/40">
            <img
              :src="spotlightEbook.cover_image || getEbookFallbackCover(spotlightTitle)"
              :alt="spotlightTitle"
              class="w-full h-full object-cover"
              loading="lazy"
              @error="onImageError($event, 'ebook', spotlightTitle)"
            />
            <div class="absolute inset-y-0 left-0 w-4 bg-gradient-to-r from-black/60 via-black/20 to-transparent pointer-events-none"></div>
            
            <div class="absolute top-3 left-3">
              <span class="px-2.5 py-0.5 rounded-full bg-slate-950/90 text-[#D4AF37] border border-[#D4AF37]/40 text-[10px] font-black">
                Spotlight
              </span>
            </div>
          </div>
        </div>

        <!-- Narrative & CTA (8 cols) -->
        <div class="lg:col-span-8 space-y-4">
          <div class="flex items-center gap-3 text-xs text-[var(--text-muted)] font-medium flex-wrap">
            <span class="px-2.5 py-0.5 rounded-lg bg-[var(--brand-gold-subtle)] text-[#D4AF37] border border-[var(--border-accent)] font-extrabold text-[10px] uppercase">
              {{ themeStore.locale === 'bn' ? 'ফ্ল্যাগশিপ রেফারেন্স গাইডবুক' : 'Flagship Reference Manual' }}
            </span>
            <span>•</span>
            <span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>{{ formatNumber(spotlightEbook.pages_count || 95, themeStore.locale) }} পৃষ্ঠা</span>
            <span>•</span>
            <span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>{{ spotlightEbook.file_size || '6.8 MB' }} PDF</span>
            <span>•</span>
            <span class="text-amber-400 font-bold inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 fill-current text-[#D4AF37]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>{{ spotlightEbook.rating || '4.95' }}</span>
          </div>

          <router-link :to="`/ebooks/${spotlightEbook.slug}`" class="block">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors leading-snug">
              {{ spotlightTitle }}
            </h2>
          </router-link>

          <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed sm:leading-loose line-clamp-3">
            {{ spotlightSummary }}
          </p>

          <div class="p-3.5 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] flex flex-wrap items-center gap-3 text-xs">
            <span class="font-bold text-[var(--text-primary)] inline-flex items-center gap-1.5"><svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>Sabre & Galileo কমান্ড শর্টকাট</span>
            <span class="text-[var(--text-muted)]">•</span>
            <span class="font-bold text-[var(--text-primary)] inline-flex items-center gap-1.5"><svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>IATA এয়ারলাইন্স কোডস</span>
            <span class="text-[var(--text-muted)]">•</span>
            <span class="font-bold text-[var(--text-primary)] inline-flex items-center gap-1.5"><svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>গ্লোবাল ভিসা চেকলিস্ট</span>
          </div>

          <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-[var(--border-subtle)]">
            <div class="flex items-center gap-3">
              <span v-if="spotlightEbook.is_free" class="text-emerald-500 font-black text-sm">
                {{ themeStore.locale === 'bn' ? '১০০% ফ্রি ডাউনলোড' : 'Free Download' }}
              </span>
              <div v-else class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-[var(--text-primary)]">{{ formatCurrency(spotlightEbook.sale_price || spotlightEbook.regular_price, themeStore.locale) }}</span>
                <span v-if="spotlightEbook.sale_price && spotlightEbook.sale_price < spotlightEbook.regular_price" class="text-xs text-[var(--text-muted)] line-through">
                  {{ formatCurrency(spotlightEbook.regular_price, themeStore.locale) }}
                </span>
              </div>
            </div>

            <router-link
              :to="`/ebooks/${spotlightEbook.slug}`"
              class="px-6 py-3 rounded-2xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:scale-105 transition-all text-center touch-target shrink-0 flex items-center justify-center gap-2"
            >
              <span>{{ themeStore.locale === 'bn' ? 'ফ্রি প্রিভিউ ও ডাউনলোড' : 'Preview & Download' }}</span>
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </router-link>
          </div>
        </div>

      </div>
    </div>

    <!-- 4. Ebooks Grid -->
    <div class="space-y-6">
      
      <div class="flex items-center justify-between pb-2 border-b border-[var(--border-subtle)]">
        <h3 class="text-base sm:text-xl font-black text-[var(--text-primary)]">
          {{ activeCatName }}
        </h3>
        <span class="text-xs text-[var(--text-muted)] font-medium">
          {{ formatNumber(filteredEbooks.length, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি রিসোর্স পাওয়া গেছে' : 'Resources' }}
        </span>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
        <div v-for="i in 4" :key="i" class="h-96 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
      </div>

      <!-- Ebooks List -->
      <div v-else-if="filteredEbooks.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
        <EbookCard
          v-for="ebook in filteredEbooks"
          :key="ebook.id"
          :ebook="ebook"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 sm:py-20 p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
        <div class="w-14 h-14 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto shadow-sm"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div>
        <h4 class="text-base font-bold text-[var(--text-primary)]">
          {{ themeStore.locale === 'bn' ? 'কোনো হ্যান্ডবুক পাওয়া যায়নি।' : 'No study handbooks match your search or filter.' }}
        </h4>
        <p class="text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'অন্য কোনো কিওয়ার্ড দিয়ে খুঁজুন অথবা সকল তালিকায় ফিরে যান।' : 'Try another search keyword or reset your filter.' }}
        </p>
        <button
          type="button"
          @click="searchQuery = ''; activeCat = ''"
          class="px-5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-bold text-[var(--text-primary)] transition-all cursor-pointer"
        >
          {{ themeStore.locale === 'bn' ? 'সকল হ্যান্ডবুক দেখুন' : 'Show All Handbooks' }}
        </button>
      </div>

    </div>

    <!-- 5. Instant Delivery & Resource Access Guarantee Banner -->
    <div class="p-6 sm:p-10 rounded-3xl bg-gradient-to-r from-blue-500/10 via-[var(--bg-elevated)] to-amber-500/10 border border-[var(--border-subtle)] shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
      <div class="space-y-1.5 text-center sm:text-left">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#D4AF37] text-[10px] font-extrabold uppercase tracking-wider">
          <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'তাৎক্ষণিক PDF ডাউনলোড ও লাইফটাইম অ্যাক্সেস' : 'Instant PDF Delivery & Lifetime Access' }}</span>
        </div>
        <h4 class="text-base sm:text-lg font-black text-[var(--text-primary)]">
          {{ themeStore.locale === 'bn' ? 'মোবাইল, ট্যাবলেট ও কম্পিউটারে সহজে পড়ার উপযোগী এইচডি ফরম্যাট' : 'High-Resolution PDF Compatible with Any Device' }}
        </h4>
        <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
          {{ themeStore.locale === 'bn' ? 'প্রতিটি হ্যান্ডবুকে নিয়মিত এভিয়েশন কমান্ড ও ভিসা পলিসি আপডেট সম্পূর্ণ বিনামূল্যে প্রদান করা হয়।' : 'All study guides receive regular aviation command revisions and embassy compliance updates.' }}
        </p>
      </div>

      <router-link
        to="/courses"
        class="px-6 py-3 rounded-2xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-bold text-[var(--text-primary)] hover:text-[#D4AF37] transition-all flex items-center gap-2 shrink-0 touch-target shadow-xs"
      >
        <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        <span>{{ themeStore.locale === 'bn' ? 'আমাদের প্র্যাকটিক্যাল কোর্সসমূহ' : 'Explore Courses' }}</span>
      </router-link>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useSeo } from '../../composables/useSeo';
import { formatCurrency, formatNumber } from '../../utils/locale';
import EbookCard from '../../components/shared/EbookCard.vue';
import { onImageError, getEbookFallbackCover } from '../../utils/imageFallback';

const themeStore = useThemeStore();
const { setMeta, buildBreadcrumbSchema } = useSeo();
const loading = ref(true);
const ebooks = ref<any[]>([]);
const activeCat = ref('');
const searchQuery = ref('');

const allCombinedEbooks = computed(() => {
  return ebooks.value || [];
});

const availableCategories = computed(() => {
  const map = new Map();
  for (const item of allCombinedEbooks.value) {
    if (item.category) {
      const slug = item.category.slug;
      if (!map.has(slug)) {
        map.set(slug, {
          id: slug,
          slug: slug,
          name_bn: item.category.name_bn,
          name_en: item.category.name_en,
          count: 0,
        });
      }
      map.get(slug).count += 1;
    }
  }
  return Array.from(map.values());
});

const filteredEbooks = computed(() => {
  let list = allCombinedEbooks.value;

  if (activeCat.value) {
    list = list.filter((e) => e.category?.slug === activeCat.value);
  }

  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase().trim();
    list = list.filter((e) => {
      const bnTitle = (e.title_bn || '').toLowerCase();
      const enTitle = (e.title_en || '').toLowerCase();
      const bnSum = (e.summary_bn || '').toLowerCase();
      const enSum = (e.summary_en || '').toLowerCase();
      const author = (e.author_name_bn || e.author_name_en || '').toLowerCase();
      return bnTitle.includes(q) || enTitle.includes(q) || bnSum.includes(q) || enSum.includes(q) || author.includes(q);
    });
  }

  return list;
});

const spotlightEbook = computed(() => {
  const list = allCombinedEbooks.value;
  return list.find((e) => e.is_featured) || list[0] || null;
});

const spotlightTitle = computed(() => {
  if (!spotlightEbook.value) return '';
  return themeStore.locale === 'bn' 
    ? (spotlightEbook.value.title_bn || spotlightEbook.value.title_en) 
    : (spotlightEbook.value.title_en || spotlightEbook.value.title_bn);
});

const spotlightSummary = computed(() => {
  if (!spotlightEbook.value) return '';
  return themeStore.locale === 'bn' 
    ? (spotlightEbook.value.summary_bn || spotlightEbook.value.summary_en) 
    : (spotlightEbook.value.summary_en || spotlightEbook.value.summary_bn);
});

const activeCatName = computed(() => {
  if (!activeCat.value) {
    return themeStore.locale === 'bn' ? 'সকল প্রফেশনাল হ্যান্ডবুক ও স্টাডি গাইড' : 'All Professional Handbooks & Guides';
  }
  const cat = availableCategories.value.find((c) => c.slug === activeCat.value);
  if (cat) {
    return themeStore.locale === 'bn' ? cat.name_bn : cat.name_en;
  }
  return themeStore.locale === 'bn' ? 'ফিল্টারকৃত হ্যান্ডবুক' : 'Filtered Handbooks';
});

const getCatIcon = (slug: string) => {
  return '';
  return '';
};

const filterCat = (slug: string) => {
  activeCat.value = slug;
};

const updateEbooksSeo = () => {
  const isBn = themeStore.locale === 'bn';
  const catPrefix = activeCat.value ? `${activeCatName.value} - ` : '';
  const title = isBn
    ? `${catPrefix}ফ্রি প্রফেশনাল ই-বুক ও রিসোর্স`
    : `${catPrefix}Free Professional E-books & Study Guides`;
  const description = isBn
    ? 'Sabre ও Galileo GDS শর্টকাট শিট, গ্লোবাল ভিসা চেকলিস্ট ও এভিয়েশন ক্যারিয়ার ফ্রি গাইডবুক ডাউনলোড করুন।'
    : 'Download high-resolution Sabre/Galileo GDS command manuals, global visa checklists, and practical aviation handbooks.';

  const breadcrumbs = [
    { name: isBn ? 'হোম' : 'Home', url: '/' },
    { name: isBn ? 'ই-বুক ও রিসোর্স' : 'E-books', url: '/ebooks' },
  ];

  const breadcrumbSchema = buildBreadcrumbSchema(breadcrumbs);
  const itemListSchema = {
    '@type': 'ItemList',
    'itemListElement': filteredEbooks.value.slice(0, 10).map((e, idx) => ({
      '@type': 'ListItem',
      'position': idx + 1,
      'name': isBn ? (e.title_bn || e.title_en) : (e.title_en || e.title_bn),
      'url': `https://emisha.academy/ebooks/${e.slug}`,
    })),
  };

  setMeta({
    title,
    description,
    keywords: 'sabre gds cheat sheet pdf, visa checklist bangladesh, aviation career guidebook pdf, emisha academy ebooks',
    type: 'website',
    schema: [breadcrumbSchema, itemListSchema],
  });
};

watch(
  () => [themeStore.locale, activeCat.value],
  () => {
    updateEbooksSeo();
  }
);

const fetchEbooks = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/public/ebooks');
    ebooks.value = res.data.data.ebooks || [];
  } catch (err) {
    console.error(err);
    ebooks.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchEbooks().then(() => {
    updateEbooksSeo();
  });
});
</script>
