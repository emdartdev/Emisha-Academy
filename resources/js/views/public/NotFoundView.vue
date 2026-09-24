<template>
  <div class="min-h-[75vh] flex items-center justify-center px-4 py-16 sm:py-24">
    <div class="max-w-xl w-full text-center space-y-8 p-8 sm:p-12 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-2xl relative overflow-hidden">
      
      <!-- Background Ambient Glow -->
      <div class="absolute -top-24 -right-24 w-64 h-64 bg-[#D4AF37]/10 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

      <!-- 404 Badge & Graphic -->
      <div class="relative space-y-4">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-xs font-black tracking-widest uppercase shadow-xs">
          <span>Error 404</span>
        </div>

        <div class="text-7xl sm:text-9xl font-black tracking-tighter bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#997A15] bg-clip-text text-transparent select-none drop-shadow-sm">
          404
        </div>
      </div>

      <!-- Error Narrative -->
      <div class="space-y-2 relative">
        <h1 class="text-2xl sm:text-3xl font-black text-[var(--text-primary)] tracking-tight">
          {{ themeStore.locale === 'bn' ? 'কাঙ্ক্ষিত পেজটি পাওয়া যায়নি' : 'Page Not Found' }}
        </h1>
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] max-w-md mx-auto leading-relaxed">
          {{ themeStore.locale === 'bn'
            ? 'আপনি যে ওয়েব পেজটি খুঁজছেন তা স্থানান্তরিত হয়েছে, মুছে ফেলা হয়েছে অথবা লিংকটি ভুল প্রদান করা হয়েছে।'
            : 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.' }}
        </p>
      </div>

      <!-- Direct Helpful Navigation Shortcuts -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-2">
        <router-link
          to="/courses"
          class="p-3 rounded-2xl bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/15 border border-[var(--border-subtle)] hover:border-[#D4AF37] transition-all text-center group cursor-pointer"
        >
          <div class="text-base sm:text-lg mb-1 group-hover:scale-110 transition-transform">🎓</div>
          <div class="text-xs font-bold text-[var(--text-primary)] group-hover:text-[#D4AF37]">
            {{ themeStore.locale === 'bn' ? 'কোর্সসমূহ' : 'Courses' }}
          </div>
        </router-link>

        <router-link
          to="/webinars"
          class="p-3 rounded-2xl bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/15 border border-[var(--border-subtle)] hover:border-[#D4AF37] transition-all text-center group cursor-pointer"
        >
          <div class="text-base sm:text-lg mb-1 group-hover:scale-110 transition-transform">🎙️</div>
          <div class="text-xs font-bold text-[var(--text-primary)] group-hover:text-[#D4AF37]">
            {{ themeStore.locale === 'bn' ? 'ওয়েবিনার' : 'Webinars' }}
          </div>
        </router-link>

        <router-link
          to="/ebooks"
          class="p-3 rounded-2xl bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/15 border border-[var(--border-subtle)] hover:border-[#D4AF37] transition-all text-center group cursor-pointer"
        >
          <div class="text-base sm:text-lg mb-1 group-hover:scale-110 transition-transform">📚</div>
          <div class="text-xs font-bold text-[var(--text-primary)] group-hover:text-[#D4AF37]">
            {{ themeStore.locale === 'bn' ? 'ই-বুক' : 'Ebooks' }}
          </div>
        </router-link>

        <router-link
          to="/contact"
          class="p-3 rounded-2xl bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/15 border border-[var(--border-subtle)] hover:border-[#D4AF37] transition-all text-center group cursor-pointer"
        >
          <div class="text-base sm:text-lg mb-1 group-hover:scale-110 transition-transform">📍</div>
          <div class="text-xs font-bold text-[var(--text-primary)] group-hover:text-[#D4AF37]">
            {{ themeStore.locale === 'bn' ? 'যোগাযোগ' : 'Contact' }}
          </div>
        </router-link>
      </div>

      <!-- Action Buttons -->
      <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
        <router-link
          to="/"
          class="w-full sm:w-auto px-8 py-3 rounded-2xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black text-xs hover:shadow-xl hover:shadow-[#D4AF37]/20 transition-all flex items-center justify-center gap-2 cursor-pointer shadow-md"
        >
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'হোমপেজে ফিরে যান' : 'Back to Home' }}</span>
        </router-link>

        <button
          @click="goBack"
          class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] hover:border-[var(--border-accent)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all flex items-center justify-center gap-2 cursor-pointer"
        >
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'পূর্ববর্তী পেজ' : 'Go Back' }}</span>
        </button>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { useThemeStore } from '../../stores/theme';

const router = useRouter();
const themeStore = useThemeStore();

const goBack = () => {
  if (window.history.length > 1) {
    router.back();
  } else {
    router.push('/');
  }
};
</script>
