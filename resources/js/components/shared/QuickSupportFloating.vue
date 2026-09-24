<template>
  <div class="fixed bottom-5 right-4 sm:bottom-6 sm:right-6 z-40 flex flex-col items-end gap-2.5 sm:gap-3 pointer-events-none safe-bottom">
    
    <!-- 1. WhatsApp Live Customer Support Button -->
    <a
      :href="whatsappUrl"
      target="_blank"
      rel="noopener noreferrer"
      class="pointer-events-auto group relative flex items-center justify-center w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-gradient-to-tr from-[#25D366] to-[#128C7E] text-white shadow-lg shadow-emerald-600/30 hover:shadow-xl hover:shadow-emerald-600/50 hover:scale-105 active:scale-95 transition-all duration-300 touch-target cursor-pointer"
      :title="themeStore.locale === 'bn' ? 'হোয়াটসঅ্যাপে সরাসরি মেসেজ করুন' : 'Chat on WhatsApp (+8801805464293)'"
      aria-label="WhatsApp Customer Support"
    >
      <!-- Live Online Pulsing Beacon -->
      <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-80"></span>
        <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-400 border-2 border-[var(--bg-deep)]"></span>
      </span>

      <!-- WhatsApp SVG Icon -->
      <svg class="w-6 h-6 sm:w-6.5 sm:h-6.5 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24">
        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
      </svg>

      <!-- Desktop Hover Tooltip Label -->
      <span class="hidden md:block absolute right-full mr-3 px-3 py-1.5 rounded-xl bg-slate-950/90 text-white text-xs font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none shadow-xl border border-white/10 translate-x-2 group-hover:translate-x-0">
        {{ themeStore.locale === 'bn' ? 'হোয়াটসঅ্যাপে চ্যাট করুন' : 'Chat on WhatsApp' }}
      </span>
    </a>

    <!-- 2. Direct Call Customer Support Hotline Button -->
    <a
      href="tel:01805464293"
      class="pointer-events-auto group relative flex items-center justify-center w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-gradient-to-tr from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 shadow-lg shadow-[#D4AF37]/30 hover:shadow-xl hover:shadow-[#D4AF37]/50 hover:scale-105 active:scale-95 transition-all duration-300 touch-target cursor-pointer"
      :title="themeStore.locale === 'bn' ? 'সরাসরি হেল্পলাইনে কল করুন (প্রতিদিন সকাল ১০:০০ - রাত ৬:৩০) | 01805464293' : 'Call Support Helpline: Daily 10:00 AM – 6:30 PM (01805464293)'"
      aria-label="Direct Phone Hotline Call Support"
    >
      <!-- Ambient Pulse Dot -->
      <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-80"></span>
        <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-500 border-2 border-[var(--bg-deep)]"></span>
      </span>

      <!-- Phone SVG Icon -->
      <svg class="w-5.5 h-5.5 sm:w-6 sm:h-6 fill-current transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12" viewBox="0 0 24 24">
        <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
      </svg>

      <!-- Desktop Hover Tooltip Label -->
      <span class="hidden md:block absolute right-full mr-3 px-3 py-1.5 rounded-xl bg-slate-950/90 text-white text-xs font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none shadow-xl border border-white/10 translate-x-2 group-hover:translate-x-0">
        {{ themeStore.locale === 'bn' ? 'সরাসরি কল (প্রতিদিন ১০:০০ - ৬:৩০)' : 'Call Support: Daily 10AM – 6:30PM' }}
      </span>
    </a>

  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useThemeStore } from '../../stores/theme';

const themeStore = useThemeStore();

const whatsappUrl = computed(() => {
  const message = themeStore.locale === 'bn'
    ? encodeURIComponent('আসসালামু আলাইকুম, আমি ইমিশা একাডেমি সম্পর্কে বিস্তারিত জানতে চাই।')
    : encodeURIComponent('Hello, I would like to inquire about Emisha Academy courses.');
  return `https://wa.me/8801805464293?text=${message}`;
});
</script>
