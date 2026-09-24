<template>
  <div class="py-8 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 sm:space-y-16">
    
    <!-- 1. Atmospheric Hero Header -->
    <div class="text-center space-y-3 sm:space-y-4 max-w-3xl mx-auto">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-xs font-black uppercase tracking-wider shadow-xs">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#D4AF37] opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-[#D4AF37]"></span>
        </span>
        <span>{{ themeStore.locale === 'bn' ? 'ফ্রি লাইভ ক্যারিয়ার সেমিনার ও মাস্টারক্লাস' : 'Free Live Career Webinars & Masterclasses' }}</span>
      </div>

      <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[var(--text-primary)] tracking-tight leading-tight">
        {{ themeStore.locale === 'bn' ? 'আসন্ন এভিয়েশন সেমিনার ও ক্যারিয়ার ওয়ার্কশপ' : 'Upcoming Aviation Seminars & Career Workshops' }}
      </h1>

      <p class="text-xs sm:text-sm lg:text-base text-[var(--text-secondary)] leading-relaxed max-w-2xl mx-auto">
        {{ themeStore.locale === 'bn'
          ? 'আন্তর্জাতিক এয়ার টিকেটিং, GDS সফটওয়্যার ও গ্লোবাল ভিসা প্রসেসিং নিয়ে ইন্ডাস্ট্রি বিশেষজ্ঞদের সাথে সরাসরি প্রশ্নোত্তর ও দিকনির্দেশনামূলক ফ্রি সেমিনার।'
          : 'Interactive free live masterclasses and career roadmap sessions hosted by certified airline and visa industry leaders.' }}
      </p>

      <!-- Search Input Container -->
      <div class="pt-2 max-w-xl mx-auto">
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="themeStore.locale === 'bn' ? 'সেমিনারের বিষয় বা স্পিকার দিয়ে খুঁজুন...' : 'Search webinars by topic or speaker...'"
            class="w-full px-5 py-3.5 pl-12 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 focus:border-[#D4AF37] text-xs sm:text-sm text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none transition-all shadow-sm"
          />
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)] pointer-events-none">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </span>
          <button
            v-if="searchQuery"
            @click="searchQuery = ''"
            type="button"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- 2. Status Navigation Tabs Strip -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-3 sm:p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xs">
      <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto">
        <button
          type="button"
          class="w-full sm:w-auto px-4 py-2.5 rounded-xl text-xs font-bold transition-all touch-target cursor-pointer flex items-center justify-center sm:justify-start gap-2"
          :class="statusTab === 'upcoming' 
            ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black shadow-xs' 
            : 'bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)] font-medium'"
          @click="setStatus('upcoming')"
        >
          <svg class="w-3.5 h-3.5 text-amber-500 fill-current" viewBox="0 0 24 24"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'আসন্ন লাইভ সেমিনার' : 'Upcoming Webinars' }}</span>
          <span class="text-[10px] px-1.5 py-0.5 rounded-md" :class="statusTab === 'upcoming' ? 'bg-black/20 text-slate-950 font-black' : 'bg-[var(--bg-surface)] text-[var(--text-muted)]'">
            {{ formatNumber(upcomingCount, themeStore.locale) }}
          </span>
        </button>

        <button
          type="button"
          class="w-full sm:w-auto px-4 py-2.5 rounded-xl text-xs font-bold transition-all touch-target cursor-pointer flex items-center justify-center sm:justify-start gap-2"
          :class="statusTab === 'past' 
            ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black shadow-xs' 
            : 'bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)] font-medium'"
          @click="setStatus('past')"
        >
          <svg class="w-3.5 h-3.5 text-[var(--brand-gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/><line x1="7" y1="2" x2="7" y2="22"/><line x1="17" y1="2" x2="17" y2="22"/><line x1="2" y1="12" x2="22" y2="12"/><line x1="2" y1="7" x2="7" y2="7"/><line x1="2" y1="17" x2="7" y2="17"/><line x1="17" y1="17" x2="22" y2="17"/><line x1="17" y1="7" x2="22" y2="7"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'পূর্ববর্তী রেকর্ডিং আর্কাইভ' : 'Recorded Archive' }}</span>
          <span class="text-[10px] px-1.5 py-0.5 rounded-md" :class="statusTab === 'past' ? 'bg-black/20 text-slate-950 font-black' : 'bg-[var(--bg-surface)] text-[var(--text-muted)]'">
            {{ formatNumber(pastCount, themeStore.locale) }}
          </span>
        </button>
      </div>

      <div class="text-xs text-[var(--text-muted)] flex items-center gap-1.5 self-start sm:self-auto">
        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
        <span>{{ themeStore.locale === 'bn' ? 'জুম লাইভ ও মিরপুর ক্যাম্পাস' : 'Zoom Live & Campus Lab' }}</span>
      </div>
    </div>

    <!-- 3. Spotlight Hero Masterclass Banner (Shown on Upcoming tab when not searching) -->
    <div v-if="!searchQuery && spotlightWebinar && statusTab === 'upcoming' && !loading" class="group p-6 sm:p-10 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 shadow-xl relative overflow-hidden transition-all">
      <!-- Ambient subtle background glow -->
      <div class="absolute -top-32 -right-32 w-80 h-80 bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-10 items-center relative z-10">
        
        <!-- Big Thumbnail (5 cols) -->
        <div class="lg:col-span-5 relative aspect-[16/10] lg:aspect-[4/3] rounded-2xl overflow-hidden shadow-lg bg-slate-950">
          <img
            :src="spotlightWebinar.thumbnail || getWebinarFallbackThumbnail()"
            :alt="spotlightTitle"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
            loading="lazy"
            @error="onImageError($event, 'webinar')"
          />
          <div class="absolute top-3.5 left-3.5">
            <span class="px-3 py-1 rounded-full bg-slate-950/90 backdrop-blur-md border border-[#D4AF37]/50 text-[#D4AF37] text-xs font-black shadow-md flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
              <span>{{ themeStore.locale === 'bn' ? 'প্রধান লাইভ মাস্টারক্লাস' : 'Featured Masterclass' }}</span>
            </span>
          </div>

          <div class="absolute bottom-3.5 left-3.5 right-3.5 flex items-center justify-between z-10 text-xs">
            <span class="px-3 py-1 rounded-xl bg-slate-950/90 text-white font-bold backdrop-blur-md border border-white/15">
              <span class="inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>{{ formatSpotlightDate(spotlightWebinar.event_datetime) }}</span>
            </span>
            <span class="px-2.5 py-1 rounded-lg bg-emerald-500 text-slate-950 font-black text-[10px] uppercase">
              ১০০% ফ্রি
            </span>
          </div>
        </div>

        <!-- Narrative & CTA (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
          <div class="flex items-center gap-2.5 text-xs text-[var(--text-muted)] font-medium flex-wrap">
            <span class="px-2.5 py-0.5 rounded-lg bg-[var(--brand-gold-subtle)] text-[#D4AF37] border border-[var(--border-accent)] font-extrabold text-[10px] uppercase">
              {{ spotlightWebinar.platform || 'Zoom & Lab Session' }}
            </span>
            <span>•</span>
            <span class="inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>{{ spotlightWebinar.duration_minutes || 90 }} মিনিট লাইভ সেশন</span>
            <span>•</span>
            <span class="text-amber-500 font-bold inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>সীমিত আসন</span>
          </div>

          <router-link :to="`/webinars/${spotlightWebinar.slug}`" class="block">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors leading-snug">
              {{ spotlightTitle }}
            </h2>
          </router-link>

          <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed sm:leading-loose line-clamp-3">
            {{ spotlightSubtitle }}
          </p>

          <div v-if="spotlightSpeaker" class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-[var(--border-subtle)]">
            <div class="flex items-center gap-3">
              <img
                :src="spotlightSpeaker.avatar || getInitialsAvatar(spotlightSpeakerName)"
                :alt="spotlightSpeakerName"
                class="w-10 h-10 rounded-xl object-cover ring-2 ring-[#D4AF37]/30 shadow-xs bg-slate-900"
                @error="onImageError($event, 'avatar', spotlightSpeakerName)"
              />
              <div class="text-xs">
                <p class="font-bold text-[var(--text-primary)]">{{ spotlightSpeakerName }}</p>
                <p class="text-[10px] text-[#D4AF37] font-semibold">{{ spotlightSpeakerDesignation }}</p>
              </div>
            </div>

            <router-link
              :to="`/webinars/${spotlightWebinar.slug}`"
              class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:scale-105 transition-all text-center touch-target shrink-0"
            >
              {{ themeStore.locale === 'bn' ? 'ফ্রি সিট বুক করুন →' : 'Book Free Seat →' }}
            </router-link>
          </div>
          <div v-else class="pt-2 flex justify-end border-t border-[var(--border-subtle)]">
            <router-link
              :to="`/webinars/${spotlightWebinar.slug}`"
              class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:scale-105 transition-all text-center touch-target shrink-0"
            >
              {{ themeStore.locale === 'bn' ? 'ফ্রি সিট বুক করুন →' : 'Book Free Seat →' }}
            </router-link>
          </div>
        </div>

      </div>
    </div>

    <!-- 4. Webinars Grid Section -->
    <div class="space-y-6">
      
      <div class="flex items-center justify-between pb-2 border-b border-[var(--border-subtle)]">
        <h3 class="text-base sm:text-xl font-black text-[var(--text-primary)]">
          {{ statusTab === 'upcoming' ? (themeStore.locale === 'bn' ? 'আসন্ন সকল সেমিনার শিডিউল' : 'All Upcoming Sessions') : (themeStore.locale === 'bn' ? 'পূর্ববর্তী সেমিনার ও রেকর্ডিং' : 'Past Recorded Masterclasses') }}
        </h3>
        <span class="text-xs text-[var(--text-muted)] font-medium">
          {{ formatNumber(filteredWebinars.length, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি সেশন' : 'Sessions' }}
        </span>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div v-for="i in 3" :key="i" class="h-96 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
      </div>

      <!-- Webinars List -->
      <div v-else-if="filteredWebinars.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <WebinarCard
          v-for="webinar in filteredWebinars"
          :key="webinar.id"
          :webinar="webinar"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 sm:py-20 p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
        <div class="w-14 h-14 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto shadow-sm"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div>
        <h4 class="text-base font-bold text-[var(--text-primary)]">
          {{ themeStore.locale === 'bn' ? 'কোনো সেমিনার পাওয়া যায়নি।' : 'No webinars match your search or filter.' }}
        </h4>
        <p class="text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'অন্য কোনো কিওয়ার্ড দিয়ে খুঁজুন অথবা সকল সেমিনার তালিকায় ফিরে যান।' : 'Try another search term or reset your filters.' }}
        </p>
        <button
          type="button"
          @click="searchQuery = ''; statusTab = 'upcoming'"
          class="px-5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-bold text-[var(--text-primary)] transition-all cursor-pointer"
        >
          {{ themeStore.locale === 'bn' ? 'আসন্ন সেমিনার দেখুন' : 'Show Upcoming Webinars' }}
        </button>
      </div>

    </div>

    <!-- 5. Certification & Session Guarantee Banner -->
    <div class="p-6 sm:p-10 rounded-3xl bg-gradient-to-r from-emerald-500/10 via-[var(--bg-elevated)] to-amber-500/10 border border-emerald-500/20 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
      <div class="space-y-1.5 text-center sm:text-left">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-extrabold uppercase tracking-wider">
          <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'অংশগ্রহণকারী সনদ ও লাইভ ইন্টার‍্যাকশন' : 'Digital Certificate & Live Q&A' }}</span>
        </div>
        <h4 class="text-base sm:text-lg font-black text-[var(--text-primary)]">
          {{ themeStore.locale === 'bn' ? 'প্রতিটি ফ্রি সেমিনারে রয়েছে ভেরিফাইড পার্টিসিপেশন সার্টিফিকেট' : 'Receive a Verified Certificate for Every Seminar You Attend' }}
        </h4>
        <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
          {{ themeStore.locale === 'bn' ? 'রেজিস্ট্রেশনের পর আপনার হোয়াটসঅ্যাপ ও ইমেইলে সরাসরি জুম লিংক ও রিমাইন্ডার পাঠানো হবে।' : 'You will receive direct Zoom link reminders on WhatsApp and Email upon free registration.' }}
        </p>
      </div>

      <router-link
        to="/contact"
        class="px-6 py-3 rounded-2xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-bold text-[var(--text-primary)] hover:text-[#D4AF37] transition-all flex items-center gap-2 shrink-0 touch-target shadow-xs"
      >
        <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <span>{{ themeStore.locale === 'bn' ? 'পরামর্শের জন্য যোগাযোগ' : 'Contact Support' }}</span>
      </router-link>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useSeo } from '../../composables/useSeo';
import { formatNumber } from '../../utils/locale';
import WebinarCard from '../../components/shared/WebinarCard.vue';
import { onImageError, getWebinarFallbackThumbnail, getInitialsAvatar } from '../../utils/imageFallback';

const themeStore = useThemeStore();
const { setMeta, buildBreadcrumbSchema } = useSeo();
const loading = ref(true);
const webinars = ref<any[]>([]);
const statusTab = ref('upcoming');

const searchQuery = ref('');

const fetchWebinars = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/public/webinars', {
      params: { status: 'all', per_page: 50 },
    });
    if (res.data?.data?.webinars) {
      webinars.value = res.data.data.webinars;
    }
  } catch (err) {
    console.error('Failed to load webinars from API:', err);
  } finally {
    loading.value = false;
  }
};

const allCombinedWebinars = computed(() => {
  return webinars.value || [];
});

const upcomingCount = computed(() => {
  return allCombinedWebinars.value.filter((w) => w.status === 'upcoming' || w.status === 'live').length;
});

const pastCount = computed(() => {
  return allCombinedWebinars.value.filter((w) => w.status === 'past').length;
});

const filteredWebinars = computed(() => {
  let list = allCombinedWebinars.value.filter((w) => {
    if (statusTab.value === 'upcoming') {
      return w.status === 'upcoming' || w.status === 'live';
    }
    return w.status === 'past';
  });

  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase().trim();
    list = list.filter((w) => {
      const bnTitle = (w.title_bn || '').toLowerCase();
      const enTitle = (w.title_en || '').toLowerCase();
      const bnSub = (w.subtitle_bn || '').toLowerCase();
      const enSub = (w.subtitle_en || '').toLowerCase();
      const speakerName = (w.speakers?.[0]?.name_bn || w.speakers?.[0]?.name_en || '').toLowerCase();
      return bnTitle.includes(q) || enTitle.includes(q) || bnSub.includes(q) || enSub.includes(q) || speakerName.includes(q);
    });
  }

  return list;
});

const spotlightWebinar = computed(() => {
  const upcomingList = allCombinedWebinars.value.filter((w) => w.status === 'upcoming' || w.status === 'live');
  return upcomingList.find((w) => w.is_featured) || upcomingList[0] || null;
});

const spotlightSpeaker = computed(() => {
  if (!spotlightWebinar.value?.speakers || spotlightWebinar.value.speakers.length === 0) return null;
  return spotlightWebinar.value.speakers[0];
});

const spotlightSpeakerName = computed(() => {
  if (!spotlightSpeaker.value) return '';
  return themeStore.locale === 'bn'
    ? (spotlightSpeaker.value.name_bn || spotlightSpeaker.value.name_en || spotlightSpeaker.value.name)
    : (spotlightSpeaker.value.name_en || spotlightSpeaker.value.name_bn || spotlightSpeaker.value.name);
});

const spotlightSpeakerDesignation = computed(() => {
  if (!spotlightSpeaker.value) return '';
  return themeStore.locale === 'bn'
    ? (spotlightSpeaker.value.designation_bn || spotlightSpeaker.value.designation_en || spotlightSpeaker.value.designation)
    : (spotlightSpeaker.value.designation_en || spotlightSpeaker.value.designation_bn || spotlightSpeaker.value.designation);
});

const spotlightTitle = computed(() => {
  if (!spotlightWebinar.value) return '';
  return themeStore.locale === 'bn' 
    ? (spotlightWebinar.value.title_bn || spotlightWebinar.value.title_en) 
    : (spotlightWebinar.value.title_en || spotlightWebinar.value.title_bn);
});

const spotlightSubtitle = computed(() => {
  if (!spotlightWebinar.value) return '';
  return themeStore.locale === 'bn' 
    ? (spotlightWebinar.value.subtitle_bn || spotlightWebinar.value.subtitle_en) 
    : (spotlightWebinar.value.subtitle_en || spotlightWebinar.value.subtitle_bn);
});

const formatSpotlightDate = (dateStr: string) => {
  if (!dateStr) return '';
  try {
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    if (themeStore.locale === 'bn') {
      const months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
      const day = formatNumber(d.getDate(), 'bn');
      const month = months[d.getMonth()];
      const year = formatNumber(d.getFullYear(), 'bn');
      return `${day} ${month}, ${year} • সন্ধ্যা ৭:০০ টা`;
    }
    return d.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
  } catch (e) {
    return dateStr;
  }
};

const setStatus = (status: string) => {
  statusTab.value = status;
};

const updateWebinarsSeo = () => {
  const isBn = themeStore.locale === 'bn';
  const title = isBn
    ? 'ফ্রি লাইভ মাস্টারক্লাস ও ওয়েবিনার'
    : 'Free Live Aviation Masterclasses & Webinars';
  const description = isBn
    ? 'এভিয়েশন ক্যারিয়ার, এয়ার টিকেটিং বিজনেস এবং ভিসা কনসালটেন্সি বিষয়ক ফ্রি লাইভ সেমিনার ও মাস্টারক্লাসে অংশ নিন।'
    : 'Join free live interactive masterclasses on airline ticketing, Galileo/Sabre GDS, and global visa consultancy.';

  const breadcrumbs = [
    { name: isBn ? 'হোম' : 'Home', url: '/' },
    { name: isBn ? 'ওয়েবিনার ও মাস্টারক্লাস' : 'Webinars', url: '/webinars' },
  ];

  const breadcrumbSchema = buildBreadcrumbSchema(breadcrumbs);
  const itemListSchema = {
    '@type': 'ItemList',
    'itemListElement': filteredWebinars.value.slice(0, 10).map((w, idx) => ({
      '@type': 'ListItem',
      'position': idx + 1,
      'name': isBn ? (w.title_bn || w.title_en) : (w.title_en || w.title_bn),
      'url': `https://emisha.academy/webinars/${w.slug}`,
    })),
  };

  setMeta({
    title,
    description,
    keywords: 'free aviation webinar, travel agency career workshop, visa consultancy seminar, emisha academy webinars',
    type: 'website',
    schema: [breadcrumbSchema, itemListSchema],
  });
};

watch(
  () => [themeStore.locale, statusTab.value],
  () => {
    updateWebinarsSeo();
  }
);

onMounted(() => {
  fetchWebinars().then(() => {
    updateWebinarsSeo();
  });
});
</script>
