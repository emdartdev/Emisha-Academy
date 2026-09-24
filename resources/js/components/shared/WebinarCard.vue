<template>
  <div class="group flex flex-col rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden relative">
    
    <!-- Thumbnail & Status HUD Overlay -->
    <div class="relative aspect-[16/10] sm:aspect-video w-full overflow-hidden bg-slate-950">
      <img
        :src="webinar.thumbnail || getWebinarFallbackThumbnail()"
        :alt="webinarTitle"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
        loading="lazy"
        @error="onImageError($event, 'webinar')"
      />

      <!-- Dark Gradient Fade -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

      <!-- Top Badges: Event Date & Free Tag -->
      <div class="absolute top-3.5 left-3.5 right-3.5 flex items-center justify-between gap-2 z-10">
        <span class="px-3 py-1 rounded-xl bg-slate-950/90 backdrop-blur-md border border-white/15 text-white text-[11px] font-extrabold flex items-center gap-1.5 shadow-md">
          <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <span>{{ formatEventDate(webinar.event_datetime) }}</span>
        </span>

        <span v-if="webinar.is_free" class="px-2.5 py-0.5 rounded-full bg-emerald-500/90 backdrop-blur-sm text-slate-950 text-[10px] font-black uppercase tracking-wider shadow-md">
          {{ themeStore.locale === 'bn' ? '১০০% ফ্রি' : 'Free Entry' }}
        </span>
        <span v-else class="px-2.5 py-0.5 rounded-full bg-[#D4AF37] text-slate-950 text-[10px] font-black shadow-md">
          {{ formatCurrency(webinar.registration_fee, themeStore.locale) }}
        </span>
      </div>

      <!-- Bottom HUD: Platform & Format Pill -->
      <div class="absolute bottom-3 left-3.5 right-3.5 flex items-center justify-between text-xs z-10">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-950/80 backdrop-blur-md border border-white/10 text-slate-200 text-[10px] font-bold">
          <span class="w-2 h-2 rounded-full" :class="webinar.status === 'past' ? 'bg-slate-400' : 'bg-red-500 animate-ping'"></span>
          <span>{{ webinar.platform || 'Zoom & Mirpur Lab' }}</span>
        </span>

        <span class="text-[10px] text-slate-300 font-medium px-2 py-0.5 rounded-md bg-black/40 inline-flex items-center gap-1">
          <svg class="w-3 h-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span>{{ webinar.duration_minutes || 90 }} {{ themeStore.locale === 'bn' ? 'মিনিট' : 'mins' }}</span>
        </span>
      </div>
    </div>

    <!-- Content Body -->
    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-4">
      
      <!-- Titles & Summary -->
      <div class="space-y-2.5">
        <router-link :to="`/webinars/${webinar.slug}`" class="block">
          <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors line-clamp-2 leading-snug">
            {{ webinarTitle }}
          </h3>
        </router-link>

        <p v-if="webinarSubtitle" class="text-xs sm:text-sm text-[var(--text-secondary)] line-clamp-2 leading-relaxed">
          {{ webinarSubtitle }}
        </p>

        <!-- Speaker Information Strip -->
        <div v-if="leadSpeaker" class="flex items-center gap-3 pt-2">
          <div class="relative shrink-0">
            <img
              :src="leadSpeaker.avatar || getInitialsAvatar(leadSpeakerName)"
              :alt="leadSpeakerName"
              class="w-9 h-9 rounded-xl object-cover ring-2 ring-[#D4AF37]/30 shadow-xs bg-slate-900"
              loading="lazy"
              @error="onImageError($event, 'avatar', leadSpeakerName)"
            />
          </div>
          <div class="space-y-0.5 min-w-0 flex-1">
            <h4 class="text-xs font-bold text-[var(--text-primary)] truncate">
              {{ leadSpeakerName }}
            </h4>
            <p class="text-[10px] text-[#D4AF37] font-semibold truncate">
              {{ leadSpeakerTitle }}
            </p>
          </div>
        </div>
      </div>

      <!-- Key Session Badges -->
      <div class="flex flex-wrap items-center gap-1.5 pt-1">
        <span class="px-2 py-0.5 rounded-md bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[10px] font-bold text-[var(--text-secondary)] inline-flex items-center gap-1">
          <svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'প্রশ্নোত্তর পর্ব' : 'Q&A Session' }}</span>
        </span>
        <span class="px-2 py-0.5 rounded-md bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[10px] font-bold text-[var(--text-secondary)] inline-flex items-center gap-1">
          <svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'পার্টিসিপেশন সনদ' : 'Certificate' }}</span>
        </span>
      </div>

      <!-- Footer CTA Action -->
      <div class="pt-3.5 border-t border-[var(--border-subtle)] flex items-center justify-between gap-3">
        <div class="text-[11px] text-[var(--text-muted)] font-medium">
          <span v-if="webinar.status === 'past'" class="text-slate-400 font-bold inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><polygon points="10 9 15 12 10 15 10 9"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'রেকর্ডিং সংরক্ষিত' : 'Recorded' }}</span>
          </span>
          <span v-else-if="seatsLeft > 0" class="text-amber-500 font-bold flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z"/></svg>
            <span>{{ formatNumber(seatsLeft, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'আসন বাকি' : 'seats left' }}</span>
          </span>
          <span v-else class="text-emerald-500 font-bold inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'রেজিস্ট্রেশন উন্মুক্ত' : 'Open' }}</span>
          </span>
        </div>

        <router-link
          :to="`/webinars/${webinar.slug}`"
          :class="webinar.status === 'past' 
            ? 'bg-[var(--bg-elevated)] hover:bg-[var(--bg-hover)] text-[var(--text-primary)] border border-[var(--border-subtle)]' 
            : 'bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-black hover:shadow-md hover:scale-105'"
          class="px-4 py-2 rounded-xl text-xs transition-all touch-target inline-flex items-center justify-center gap-1 shrink-0"
        >
          <span>{{ webinar.status === 'past' ? (themeStore.locale === 'bn' ? 'রেকর্ডিং দেখুন' : 'Watch Recording') : (themeStore.locale === 'bn' ? 'সিট বুক করুন' : 'Register Free') }}</span>
          <span>→</span>
        </router-link>
      </div>

    </div>

  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useThemeStore } from '../../stores/theme';
import { formatCurrency, formatNumber } from '../../utils/locale';
import { onImageError, getWebinarFallbackThumbnail, getInitialsAvatar } from '../../utils/imageFallback';

const props = defineProps<{
  webinar: any;
}>();

const themeStore = useThemeStore();

const webinarTitle = computed(() => {
  return themeStore.locale === 'bn' ? (props.webinar.title_bn || props.webinar.title_en) : (props.webinar.title_en || props.webinar.title_bn);
});

const webinarSubtitle = computed(() => {
  return themeStore.locale === 'bn' ? (props.webinar.subtitle_bn || props.webinar.subtitle_en) : (props.webinar.subtitle_en || props.webinar.subtitle_bn);
});

const leadSpeaker = computed(() => {
  if (props.webinar.speakers && props.webinar.speakers.length > 0) {
    return props.webinar.speakers[0];
  }
  return null;
});

const leadSpeakerName = computed(() => {
  if (!leadSpeaker.value) return themeStore.locale === 'bn' ? 'তানভীর রহমান' : 'Tanvir Rahman';
  return themeStore.locale === 'bn' ? (leadSpeaker.value.name_bn || leadSpeaker.value.name_en) : (leadSpeaker.value.name_en || leadSpeaker.value.name_bn);
});

const leadSpeakerTitle = computed(() => {
  if (!leadSpeaker.value) return themeStore.locale === 'bn' ? 'লিড এভিয়েশন ট্রেইনার' : 'Lead Aviation Trainer';
  return themeStore.locale === 'bn' ? (leadSpeaker.value.designation_bn || leadSpeaker.value.designation_en) : (leadSpeaker.value.designation_en || leadSpeaker.value.designation_bn);
});

const seatsLeft = computed(() => {
  const max = Number(props.webinar.max_participants || 100);
  const enrolled = Number(props.webinar.registered_count || 78);
  return Math.max(0, max - enrolled);
});

const formatEventDate = (dateStr: string) => {
  if (!dateStr) return themeStore.locale === 'bn' ? 'আসন্ন শিডিউল' : 'Upcoming Schedule';
  try {
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;

    if (themeStore.locale === 'bn') {
      const months = ['জানু', 'ফেব্রু', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টে', 'অক্টো', 'নভে', 'ডিসে'];
      const day = formatNumber(d.getDate(), 'bn');
      const month = months[d.getMonth()];
      const hours = d.getHours();
      const minutes = d.getMinutes().toString().padStart(2, '0');
      const ampm = hours >= 12 ? 'রাত/বিকাল' : 'সকাল';
      const formattedHour = formatNumber(hours > 12 ? hours - 12 : hours, 'bn');
      const formattedMin = formatNumber(minutes, 'bn');
      return `${day} ${month} | ${ampm} ${formattedHour}:${formattedMin}`;
    }

    return d.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  } catch (e) {
    return dateStr;
  }
};
</script>
