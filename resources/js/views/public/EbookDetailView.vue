<template>
  <div class="py-8 sm:py-14 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 sm:space-y-16">
    
    <!-- Loading State -->
    <div v-if="loading" class="py-24 text-center space-y-4">
      <div class="inline-block animate-spin w-10 h-10 border-4 border-[#D4AF37] border-t-transparent rounded-full shadow-md"></div>
      <p class="text-xs sm:text-sm font-semibold text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'ই-বুক ও স্টাডি গাইড লোড হচ্ছে...' : 'Loading study handbook details...' }}
      </p>
    </div>

    <!-- Ebook Detail Content -->
    <template v-else-if="currentEbook">
      
      <!-- 1. Breadcrumb Navigation & Top Action Strip -->
      <div class="flex flex-wrap items-center justify-between gap-4 text-xs">
        <nav class="flex items-center gap-2 text-[var(--text-muted)]">
          <router-link to="/" class="hover:text-[var(--text-primary)] transition-colors">
            {{ themeStore.locale === 'bn' ? 'হোম' : 'Home' }}
          </router-link>
          <span>/</span>
          <router-link to="/ebooks" class="hover:text-[var(--text-primary)] transition-colors">
            {{ themeStore.locale === 'bn' ? 'ই-বুক ও রিসোর্স' : 'Ebooks' }}
          </router-link>
          <span>/</span>
          <span class="text-[var(--brand-gold)] font-bold truncate max-w-[200px] sm:max-w-xs">
            {{ categoryName }}
          </span>
        </nav>

        <router-link
          to="/ebooks"
          class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 text-[var(--text-secondary)] hover:text-[var(--text-primary)] font-bold transition-all shadow-xs"
        >
          <span>←</span>
          <span>{{ themeStore.locale === 'bn' ? 'সকল হ্যান্ডবুক দেখুন' : 'Back to Handbooks' }}</span>
        </router-link>
      </div>

      <!-- 2. Flagship Hero Two-Column Grid (Left: 3D Book Showcase / Right: Content Deep-Dive) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-start">
        
        <!-- Left: 3D Perspective Book Showcase (5 cols) -->
        <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24">
          
          <!-- 3D Book Presentation Box -->
          <div class="p-8 sm:p-12 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xl flex flex-col items-center justify-center relative overflow-hidden group">
            <!-- Ambient Backdrop Glow -->
            <div class="absolute inset-0 bg-gradient-to-tr from-[#D4AF37]/10 via-transparent to-blue-500/10 pointer-events-none"></div>

            <!-- Floating Top Badges -->
            <div class="w-full flex items-center justify-between mb-8 z-10">
              <span class="px-3 py-1 rounded-full bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#D4AF37] text-[11px] font-black uppercase tracking-wider">
                {{ categoryName }}
              </span>
              <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 text-[11px] font-black uppercase tracking-wider shadow-xs flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-emerald-400 fill-current" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                <span>{{ currentEbook.is_free ? (themeStore.locale === 'bn' ? '১০০% ফ্রি' : '100% Free') : (themeStore.locale === 'bn' ? 'প্রিমিয়াম গাইড' : 'Premium Guide') }}</span>
              </span>
            </div>

            <!-- 3D Book Cover with Spine & Reflection -->
            <div class="relative perspective-1000 z-10 my-2">
              <div class="relative w-48 sm:w-56 h-68 sm:h-80 rounded-r-2xl rounded-l-sm overflow-hidden shadow-[15px_20px_40px_rgba(0,0,0,0.6)] border-r-4 border-b-4 border-black/40 group-hover:scale-105 group-hover:-rotate-1 transition-all duration-500 bg-slate-950">
                
                <!-- Spine shadow layer on left -->
                <div class="absolute left-0 top-0 bottom-0 w-4 bg-gradient-to-r from-black/80 via-black/40 to-transparent z-20 pointer-events-none"></div>
                <!-- Book pages edge effect on right -->
                <div class="absolute right-0 top-0 bottom-0 w-2 bg-gradient-to-l from-white/30 via-white/10 to-transparent z-20 pointer-events-none"></div>
                
                <img
                  :src="currentEbook.cover_image || getEbookFallbackCover(ebookTitle)"
                  :alt="ebookTitle"
                  class="w-full h-full object-cover"
                  @error="onImageError($event, 'ebook', ebookTitle)"
                />

                <!-- High-contrast glossy reflection overlay -->
                <div class="absolute inset-0 bg-gradient-to-tr from-black/60 via-transparent to-white/15 pointer-events-none"></div>
              </div>
            </div>

            <p class="text-[11px] text-[var(--text-muted)] font-semibold mt-4 z-10 flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              <span>{{ editionBadge }}</span>
            </p>
          </div>

          <!-- Specifications HUD Strip -->
          <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] grid grid-cols-2 sm:grid-cols-4 gap-3 text-center shadow-xs">
            <div class="p-2 rounded-xl bg-[var(--bg-elevated)] space-y-0.5">
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'পৃষ্ঠা সংখ্যা' : 'Pages' }}</span>
              <span class="text-xs sm:text-sm font-black text-[var(--text-primary)]">
                <span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>{{ formatNumber(currentEbook.pages_count || 95, themeStore.locale) }}</span>
              </span>
            </div>

            <div class="p-2 rounded-xl bg-[var(--bg-elevated)] space-y-0.5">
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'ফাইল সাইজ' : 'File Size' }}</span>
              <span class="text-xs sm:text-sm font-black text-[var(--text-primary)]">
                <span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>{{ currentEbook.file_size || '6.8 MB' }}</span>
              </span>
            </div>

            <div class="p-2 rounded-xl bg-[var(--bg-elevated)] space-y-0.5">
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'গড় রেটিং' : 'Rating' }}</span>
              <span class="text-xs sm:text-sm font-black text-[#D4AF37]">
                <span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 fill-current text-[#D4AF37]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>{{ currentEbook.rating || '4.95' }}</span>
              </span>
            </div>

            <div class="p-2 rounded-xl bg-[var(--bg-elevated)] space-y-0.5">
              <span class="text-[10px] text-[var(--text-muted)] block">{{ themeStore.locale === 'bn' ? 'ডাউনলোড' : 'Downloads' }}</span>
              <span class="text-xs sm:text-sm font-black text-[var(--text-primary)]">
                <span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>{{ formatNumber(currentEbook.download_count || 421, themeStore.locale) }}</span>
              </span>
            </div>
          </div>

          <!-- Tactile Download & Action Card -->
          <div class="p-6 rounded-3xl bg-gradient-to-b from-[var(--bg-surface)] to-[var(--bg-elevated)] border border-[var(--border-accent)] shadow-xl space-y-5">
            
            <div class="flex items-center justify-between">
              <div>
                <span class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider block">
                  {{ themeStore.locale === 'bn' ? 'অ্যাক্সেস মূল্য' : 'Access Price' }}
                </span>
                <div class="flex items-baseline gap-2">
                  <span class="text-2xl sm:text-3xl font-black text-[#D4AF37]">
                    {{ currentEbook.is_free ? (themeStore.locale === 'bn' ? '১০০% ফ্রি' : '100% Free') : formatCurrency(currentEbook.sale_price || currentEbook.regular_price, themeStore.locale) }}
                  </span>
                  <span v-if="!currentEbook.is_free && currentEbook.regular_price > currentEbook.sale_price" class="text-xs text-[var(--text-muted)] line-through">
                    {{ formatCurrency(currentEbook.regular_price, themeStore.locale) }}
                  </span>
                </div>
              </div>

              <span class="px-2.5 py-1 rounded-lg bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-[10px] font-bold">
                {{ themeStore.locale === 'bn' ? 'লাইফটাইম অ্যাক্সেস' : 'Lifetime Access' }}
              </span>
            </div>

            <!-- Primary Download / Buy Button -->
            <button
              type="button"
              @click="handleDownload"
              :disabled="isDownloading"
              class="w-full py-4 rounded-2xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black text-xs sm:text-sm flex items-center justify-center gap-2 hover:shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer touch-target shadow-lg"
            >
              <span v-if="isDownloading" class="inline-block animate-spin w-4 h-4 border-2 border-slate-950 border-t-transparent rounded-full"></span>
              <span v-else><svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></span>
              <span>{{ isDownloading ? (themeStore.locale === 'bn' ? 'প্রস্তুত হচ্ছে...' : 'Preparing...') : (currentEbook?.is_free ? (themeStore.locale === 'bn' ? 'ই-বুকটি ফ্রি ডাউনলোড করুন (PDF) →' : 'Download Free Ebook PDF →') : (themeStore.locale === 'bn' ? 'সরাসরি পেমেন্ট করে ই-বুক কিনুন →' : 'Pay & Buy Ebook →')) }}</span>
            </button>

            <!-- Sample Preview PDF Button -->
            <button
              v-if="currentEbook?.preview_pdf_path"
              type="button"
              @click="handlePreview"
              class="w-full py-3 rounded-2xl bg-[var(--bg-elevated)] hover:bg-[var(--border-subtle)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-bold text-xs flex items-center justify-center gap-2 transition-all cursor-pointer touch-target"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'ফ্রি স্যাম্পল প্রিভিউ পড়ুন (Sample Preview)' : 'Read Sample Preview (PDF)' }}</span>
            </button>

            <!-- Device Compatibility Row -->
            <div class="space-y-2 text-[11px] text-[var(--text-secondary)] border-t border-[var(--border-subtle)] pt-3">
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'মোবাইল, ট্যাবলেট ও ল্যাপটপে সহজে পড়ার উপযোগী।' : 'Compatible with Mobile, iPad, Tablet, Mac & PC.' }}</span>
              </div>
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'কোনো হিডেন চার্জ নেই, সম্পূর্ণ নিরাপদ ডাউনলোড।' : 'Instant direct download with zero hidden fees.' }}</span>
              </div>
            </div>

            <!-- Share Buttons Strip -->
            <div class="flex items-center justify-between pt-2 border-t border-[var(--border-subtle)] text-xs text-[var(--text-muted)]">
              <span>{{ themeStore.locale === 'bn' ? 'বন্ধুদের সাথে শেয়ার করুন:' : 'Share with Peers:' }}</span>
              <div class="flex items-center gap-2">
                <button @click="shareFacebook" class="p-2 rounded-xl bg-[#1877F2]/10 hover:bg-[#1877F2] text-[#1877F2] hover:text-white transition-all cursor-pointer" title="Share on Facebook">
                  <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95C18.05 21.45 22 17.19 22 12Z"/></svg>
                </button>
                <button @click="shareWhatsApp" class="p-2 rounded-xl bg-[#25D366]/10 hover:bg-[#25D366] text-[#25D366] hover:text-white transition-all cursor-pointer" title="Share on WhatsApp">
                  <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.04 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 14.99 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.34C9.36 7.34 9.09 7.4 8.87 7.65C8.65 7.89 8.02 8.48 8.02 9.7C8.02 10.92 8.91 12.09 9.03 12.26C9.16 12.42 10.74 14.86 13.17 15.91C13.75 16.16 14.2 16.31 14.55 16.42C15.13 16.61 15.66 16.58 16.08 16.52C16.55 16.45 17.52 15.93 17.72 15.36C17.93 14.79 17.93 14.3 17.87 14.2C17.8 14.1 17.65 14.04 17.43 13.93C17.2 13.82 16.12 13.28 15.92 13.21C15.71 13.13 15.56 13.1 15.41 13.33C15.26 13.56 14.83 14.07 14.7 14.22C14.57 14.37 14.45 14.39 14.22 14.28C14 14.17 13.06 13.86 11.94 12.86C11.07 12.08 10.48 11.12 10.31 10.83C10.14 10.54 10.29 10.38 10.41 10.27C10.51 10.16 10.63 10 10.75 9.87C10.87 9.73 10.91 9.63 10.99 9.47C11.07 9.3 11.03 9.16 10.97 9.04C10.91 8.93 10.46 7.82 10.28 7.37C10.09 6.94 9.9 7 9.76 7C9.62 7 9.47 7 9.53 7.34Z"/></svg>
                </button>
                <button @click="copyEbookLink" class="p-2 rounded-xl bg-[var(--bg-surface)] hover:bg-[#D4AF37] text-[var(--text-secondary)] hover:text-slate-950 transition-all cursor-pointer" title="Copy Link">
                  <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                </button>
              </div>
            </div>

          </div>

        </div>

        <!-- Right: Handbook Overview, Competencies, Chapters & Target Audience (7 cols) -->
        <div class="lg:col-span-7 space-y-8 sm:space-y-10">
          
          <!-- Title & Author Block -->
          <div class="space-y-3 sm:space-y-4">
            <div class="flex items-center gap-2 text-xs">
              <div class="flex items-center gap-0.5 text-[#D4AF37]"><svg v-for="i in 5" :key="i" class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
              <span class="text-[var(--text-muted)] font-medium">
                ({{ currentEbook.rating || '4.95' }} / ৫.০ • {{ currentEbook.reviews_count || 84 }}+ {{ themeStore.locale === 'bn' ? 'ভেরিফাইড রিভিউ' : 'Verified Reviews' }})
              </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-[var(--text-primary)] tracking-tight leading-snug">
              {{ ebookTitle }}
            </h1>

            <div class="flex items-center gap-3 pt-1">
              <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#D4AF37] to-amber-200 text-slate-950 font-black flex items-center justify-center text-xs">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
              </div>
              <div>
                <span class="text-xs font-bold text-[var(--text-primary)]">
                  {{ authorName }}
                </span>
                <span class="text-[11px] text-[var(--text-muted)] block">
                  {{ authorDesignation }}
                </span>
              </div>
            </div>
          </div>

          <!-- Description Narrative -->
          <div class="p-6 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-3 shadow-xs">
            <h3 class="text-xs sm:text-sm font-black text-[#D4AF37] uppercase tracking-wider flex items-center gap-2">
              <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'হ্যান্ডবুক পরিচিতি ও উদ্দেশ্য' : 'Handbook Overview & Objectives' }}</span>
            </h3>
            <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed sm:leading-loose">
              {{ ebookDescription }}
            </p>
          </div>

          <!-- Core Competencies Covered Checklist (2-Columns) -->
          <div class="p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-5 shadow-xs">
            <h3 class="text-sm sm:text-base font-black text-[var(--text-primary)] flex items-center gap-2">
              <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'এই হ্যান্ডবুকে আপনি যা যা শিখবেন' : 'Core Topics & Skills You Will Master' }}</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs sm:text-sm">
              <div
                v-for="(point, idx) in learningPoints"
                :key="idx"
                class="p-3.5 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] flex items-start gap-2.5 transition-all"
              >
                <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span class="text-[var(--text-secondary)] font-medium leading-relaxed">{{ point }}</span>
              </div>
            </div>
          </div>

          <!-- Detailed Chapter Breakdown / Table of Contents Accordion -->
          <div class="p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-5 shadow-xs">
            <div class="flex items-center justify-between">
              <h3 class="text-sm sm:text-base font-black text-[var(--text-primary)] flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'অধ্যায় ও বিস্তারিত সূচিপত্র' : 'Table of Contents & Chapters' }}</span>
              </h3>
              <span class="text-xs text-[var(--brand-gold)] font-bold">
                {{ formatNumber(chaptersList.length, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি মূল অধ্যায়' : 'Chapters' }}
              </span>
            </div>

            <div class="space-y-3">
              <div
                v-for="(chapter, cIdx) in chaptersList"
                :key="cIdx"
                class="rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] overflow-hidden transition-all"
              >
                <button
                  type="button"
                  @click="toggleChapter(cIdx)"
                  class="w-full p-4 text-left flex items-center justify-between gap-4 cursor-pointer hover:bg-[var(--bg-surface)] transition-all"
                >
                  <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-xl bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30 text-xs font-black flex items-center justify-center shrink-0">
                      {{ formatNumber(cIdx + 1, themeStore.locale) }}
                    </span>
                    <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)]">
                      {{ chapter.title }}
                    </h4>
                  </div>
                  <span class="text-xs text-[var(--text-muted)] transition-transform duration-200" :class="{ 'rotate-180': openChapters.includes(cIdx) }">
                    ▼
                  </span>
                </button>

                <div v-if="openChapters.includes(cIdx)" class="px-5 pb-4 pt-1 text-xs text-[var(--text-secondary)] border-t border-[var(--border-subtle)] space-y-1.5 bg-[var(--bg-card)]">
                  <p v-if="chapter.summary" class="leading-relaxed">{{ chapter.summary }}</p>
                  <ul v-if="chapter.subtopics && chapter.subtopics.length > 0" class="pt-1 space-y-1 pl-4 list-disc marker:text-[#D4AF37]">
                    <li v-for="(sub, sIdx) in chapter.subtopics" :key="sIdx">
                      {{ sub }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Target Audience / Who Should Read Card -->
          <div class="p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4 shadow-xs">
            <h3 class="text-sm sm:text-base font-black text-[var(--text-primary)] flex items-center gap-2">
              <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'যাদের জন্য এই গাইডবুকটি অপরিহার্য' : 'Who Should Read This Handbook' }}</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
              <div
                v-for="(persona, pIdx) in targetAudienceList"
                :key="pIdx"
                class="p-3.5 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-1 text-center sm:text-left"
              >
                <span class="text-xl block"><svg class="w-5 h-5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                <h5 class="font-bold text-[var(--text-primary)]">{{ persona.title }}</h5>
                <p class="text-[11px] text-[var(--text-muted)] leading-tight">{{ persona.desc }}</p>
              </div>
            </div>
          </div>

        </div>

      </div>

      <!-- 3. Practical Lab Training Connect Banner -->
      <div class="p-6 sm:p-10 rounded-3xl bg-gradient-to-r from-[var(--brand-gold-subtle)] via-[var(--bg-elevated)] to-blue-500/10 border border-[var(--border-accent)] shadow-lg flex flex-col lg:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center lg:text-left max-w-2xl">
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#D4AF37] text-[10px] font-black uppercase">
            <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'হাতে-কলমে প্র্যাকটিক্যাল ল্যাব সুবিধা' : 'Hands-on Computer Lab Training' }}</span>
          </div>
          <h3 class="text-lg sm:text-2xl font-black text-[var(--text-primary)]">
            {{ labUpsellTitle }}
          </h3>
          <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
            {{ labUpsellDesc }}
          </p>
        </div>

        <router-link
          :to="currentEbook.lab_upsell_btn_link || '/courses'"
          class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black text-xs sm:text-sm hover:shadow-xl transition-all shrink-0 touch-target shadow-md flex items-center gap-2"
        >
          <span>{{ labUpsellBtnText }}</span>
        </router-link>
      </div>

      <!-- 4. Related Study Guides Grid -->
      <div v-if="relatedEbooks.length > 0" class="space-y-6">
        <div class="flex items-center justify-between">
          <div class="space-y-1">
            <h3 class="text-lg sm:text-2xl font-black text-[var(--text-primary)] flex items-center gap-2">
              <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'সম্পর্কিত অন্যান্য হ্যান্ডবুক ও গাইড' : 'Related Study Handbooks' }}</span>
            </h3>
            <p class="text-xs text-[var(--text-secondary)]">
              {{ themeStore.locale === 'bn' ? 'আপনার প্রফেশনাল ক্যারিয়ারকে আরও সমৃদ্ধ করতে সহায়ক রিসোর্স।' : 'Complementary reference guides for travel professionals.' }}
            </p>
          </div>

          <router-link to="/ebooks" class="text-xs font-bold text-[#D4AF37] hover:underline hidden sm:inline-block">
            {{ themeStore.locale === 'bn' ? 'সবগুলো দেখুন →' : 'View All →' }}
          </router-link>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <EbookCard
            v-for="item in relatedEbooks"
            :key="item.id"
            :ebook="item"
          />
        </div>
      </div>

    </template>

    <!-- Empty / Not Found State -->
    <div v-else class="text-center py-24 p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
      <div class="w-14 h-14 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto shadow-sm"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div>
      <h3 class="text-lg font-bold text-[var(--text-primary)]">
        {{ themeStore.locale === 'bn' ? 'ই-বুকটি পাওয়া যায়নি।' : 'Ebook Not Found' }}
      </h3>
      <p class="text-xs text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'ই-বুকটি সরিয়ে নেওয়া হয়ে থাকতে পারে অথবা ভুল লিংকে প্রবেশ করেছেন।' : 'The requested handbook may have been updated or the link is invalid.' }}
      </p>
      <router-link
        to="/ebooks"
        class="inline-block px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-bold text-xs shadow-md"
      >
        {{ themeStore.locale === 'bn' ? 'সকল হ্যান্ডবুক দেখুন' : 'Browse All Handbooks' }}
      </router-link>
    </div>

    <!-- Ebook Download & Lead Capture Modal -->
    <EbookDownloadModal
      v-model="isDownloadModalOpen"
      :ebook="currentEbook"
    />

    <!-- Direct Manual Payment & Checkout Modal (bKash Merchant QR / bKash Personal / BRAC Bank) -->
    <PaymentModal
      v-model="isPaymentModalOpen"
      item-type="ebook"
      :item-id="currentEbook?.id || 0"
      :item-name="ebookTitle"
      :item-price="Number(currentEbook?.sale_price || currentEbook?.regular_price || 0)"
    />

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';
import { useSeo } from '../../composables/useSeo';
import { formatCurrency, formatNumber } from '../../utils/locale';
import EbookCard from '../../components/shared/EbookCard.vue';
import EbookDownloadModal from '../../components/public/EbookDownloadModal.vue';
import PaymentModal from '../../components/public/PaymentModal.vue';
import { onImageError, getEbookFallbackCover } from '../../utils/imageFallback';

const route = useRoute();
const themeStore = useThemeStore();
const toastStore = useToastStore();
const { setMeta, buildEbookSchema, buildBreadcrumbSchema } = useSeo();

const loading = ref(true);
const ebook = ref<any>(null);
const relatedEbooksFromApi = ref<any[]>([]);
const isDownloading = ref(false);
const isDownloadModalOpen = ref(false);
const isPaymentModalOpen = ref(false);
const openChapters = ref<number[]>([0]);


const currentEbook = computed(() => {
  return ebook.value;
});

const ebookTitle = computed(() => {
  if (!currentEbook.value) return '';
  return themeStore.locale === 'bn'
    ? (currentEbook.value.title_bn || currentEbook.value.title_en)
    : (currentEbook.value.title_en || currentEbook.value.title_bn);
});

const ebookDescription = computed(() => {
  if (!currentEbook.value) return '';
  return themeStore.locale === 'bn'
    ? (currentEbook.value.description_bn || currentEbook.value.summary_bn || currentEbook.value.description_en)
    : (currentEbook.value.description_en || currentEbook.value.summary_en || currentEbook.value.description_bn);
});

const categoryName = computed(() => {
  if (!currentEbook.value?.category) return themeStore.locale === 'bn' ? 'স্টাডি গাইড' : 'Study Guide';
  return themeStore.locale === 'bn'
    ? (currentEbook.value.category.name_bn || currentEbook.value.category.name_en)
    : (currentEbook.value.category.name_en || currentEbook.value.category.name_bn);
});

const authorName = computed(() => {
  if (!currentEbook.value) return '';
  return themeStore.locale === 'bn'
    ? (currentEbook.value.author_name_bn || 'ইমিশা একাডেমি রিসার্চ টিম')
    : (currentEbook.value.author_name_en || 'Emisha Academy Research Team');
});

const authorDesignation = computed(() => {
  if (!currentEbook.value) return '';
  return themeStore.locale === 'bn'
    ? (currentEbook.value.author_designation_bn || 'এভিয়েশন ফ্যাকাল্টি ও ট্রাভেল অপারেশনস টিম')
    : (currentEbook.value.author_designation_en || 'Aviation Faculty & Travel Operations Team');
});

const editionBadge = computed(() => {
  if (!currentEbook.value) return '';
  return themeStore.locale === 'bn'
    ? (currentEbook.value.edition_badge_bn || 'অফিশিয়াল পিডিএফ ই-বুক সংস্করণ (সর্বশেষ সংস্করণ)')
    : (currentEbook.value.edition_badge_en || 'Official High-Resolution PDF Handbook (Latest Revision)');
});

const learningPoints = computed(() => {
  const dynamicBn = currentEbook.value?.highlights_bn;
  const dynamicEn = currentEbook.value?.highlights_en;
  if (themeStore.locale === 'bn' && Array.isArray(dynamicBn) && dynamicBn.length > 0) {
    return dynamicBn;
  }
  if (themeStore.locale !== 'bn' && Array.isArray(dynamicEn) && dynamicEn.length > 0) {
    return dynamicEn;
  }
  if (themeStore.locale === 'bn') {
    return [
      'Sabre এবং Galileo সিস্টেমের ১০০+ প্রয়োজনীয় কমান্ড শর্টকাট।',
      'আন্তর্জাতিক এয়ারলাইন্স ফেয়ার ক্যালকুলেশন ও ট্যাক্স কোটেশন রুলস।',
      'সরাসরি লাইভ PNR ক্রিয়েশন ও প্যাসেঞ্জার হিস্ট্রি ট্র্যাকিং।',
      'টিকিট রি-ইস্যু (Re-issue), ডেট চেঞ্জ এবং রিফান্ড পলিসি কমপ্লায়েন্স।',
      'ট্যুরিস্ট ও বিজনেস ভিসা ফাইল প্রস্তুতকরণের পূর্ণাঙ্গ চেকলিস্ট।',
      'ক্লায়েন্ট হ্যান্ডলিং ও এজেন্সির প্রফিট মার্জিন বাড়ানোর কার্যকরী কৌশল।',
    ];
  }
  return [
    '100+ Essential Sabre & Galileo command cheat codes.',
    'International airline fare construction & tax breakdown rules.',
    'Hands-on live PNR creation & passenger profile management.',
    'Re-issue, date change penalty calculations & refund procedures.',
    'Complete tourist & business visa dossier preparation checklist.',
    'Client counseling & profit optimization strategies for agencies.',
  ];
});

const chaptersList = computed(() => {
  const dynamicBn = currentEbook.value?.chapters_bn;
  const dynamicEn = currentEbook.value?.chapters_en;
  if (themeStore.locale === 'bn' && Array.isArray(dynamicBn) && dynamicBn.length > 0) {
    return dynamicBn;
  }
  if (themeStore.locale !== 'bn' && Array.isArray(dynamicEn) && dynamicEn.length > 0) {
    return dynamicEn;
  }
  if (themeStore.locale === 'bn') {
    return [
      {
        title: 'অধ্যায় ০১: আন্তর্জাতিক এভিয়েশন কাঠামো ও এয়ারলাইন্স কোডস',
        summary: 'IATA ও ICAO পরিচিতি, বিশ্বের প্রধান এয়ারলাইন্স ও সিটি কোডস এবং ট্রাভেল জিওগ্রাফি কনসেপ্ট।',
        subtopics: ['IATA এরিয়া ১, ২, ৩ এবং টাইম জোন গণনা', '২-লেটার ও ৩-লেটার এয়ারলাইন্স ও এয়ারপোর্ট কোডস', 'ফ্লাইট টাইপস: নন-স্টপ, ডাইরেক্ট ও কানেক্টিং ফ্লাইট'],
      },
      {
        title: 'অধ্যায় ০২: Sabre ও Galileo GDS সিস্টেম এনভায়রনমেন্ট',
        summary: 'GDS সিস্টেমে সাইন-ইন, ওয়ার্ক এরিয়া এবং টার্মিনাল কমান্ডের বেসিক স্ট্রাকচার।',
        subtopics: ['Agent Sign-In, Sign-Out ও Area Switch', 'Encode & Decode Cities, Airlines ও Equipment', 'Flight Availability Display ও সিট স্ট্যাটাস কোড'],
      },
      {
        title: 'অধ্যায় ০৩: লাইভ PNR ক্রিয়েশন ও প্যাসেঞ্জার ম্যানেজমেন্ট',
        summary: 'বাস্তবসম্মত PNR এর ৫টি বাধ্যতামূলক উপাদান এবং সার্ভিস রিকোয়েস্ট যোগ করার নিয়ম।',
        subtopics: ['PRINT এলিমেন্টস (Phone, Received, Itinerary, Name, Ticketing)', 'Special Service Request (SSR) ও OSI মেসেজ', 'Split PNR ও প্যাসেঞ্জার হিস্ট্রি চেক'],
      },
      {
        title: 'অধ্যায় ০৪: ফেয়ার কোটেশন, টিকিট ইস্যু ও রি-ইস্যু প্রসেসিং',
        summary: 'স্বয়ংক্রিয় ফেয়ার ডিসপ্লে, ফেয়ার রুলস এবং ডেট চেঞ্জ পেনাল্টি ক্যালকুলেশন।',
        subtopics: ['Lowest Fare Search (WPNCB / FQ)', 'Electronic Ticket Issuance ও ইনভয়েস জেনারেশন', 'Re-issue, Date Change ও Penalty Calculation'],
      },
      {
        title: 'অধ্যায় ০৫: এম্বাসি ভিসা ফাইলিং ও এজেন্সি বিজনেস গাইডলাইন',
        summary: 'ভিসা ফাইল প্রসেসিং এবং ট্রাভেল এজেন্সির বিটুবি টিকেটিং বিজনেস সেটআপ।',
        subtopics: ['Schengen ও USA ভিসা কভার লেটার ড্রাফট', 'এয়ার টিকেটিং পোর্টাল ও B2B অপারেশনস', 'এজেন্সি লাভজনক করার কৌশল ও কাস্টমার সার্ভিস'],
      },
    ];
  }
  return [
    {
      title: 'Chapter 01: Global Aviation Framework & Airline Codes',
      summary: 'IATA & ICAO overview, major world airport city codes, and global travel geography.',
      subtopics: ['IATA Areas 1, 2, 3 and timezone calculations', '2-letter airline codes & 3-letter airport codes', 'Flight routing: Non-stop, Direct & Connecting'],
    },
    {
      title: 'Chapter 02: Sabre & Galileo GDS System Environment',
      summary: 'Terminal sign-in workflows, command syntaxes, and availability displays.',
      subtopics: ['Agent Sign-in & Work Area Navigation', 'Encode & Decode commands for cities & carriers', 'Live Availability Displays & status codes'],
    },
    {
      title: 'Chapter 03: Live PNR Creation & Itinerary Management',
      summary: '5 Mandatory PNR elements, auxiliary SSR/OSI services, and booking modifications.',
      subtopics: ['Mandatory PRINT elements breakdown', 'SSR special meal, wheelchair & passport data', 'Split PNR and booking modification rules'],
    },
    {
      title: 'Chapter 04: Fare Quotation, Ticketing & Re-Issuance',
      summary: 'Automated fare construction, tax breakdowns, and ticket re-issue calculations.',
      subtopics: ['Best Buy & lowest fare search commands', 'Electronic ticket issuance and billing', 'Date change re-issue penalty computations'],
    },
    {
      title: 'Chapter 05: Embassy Visa Filing & Agency Operations',
      summary: 'Visa dossier drafting and B2B travel agency business operations.',
      subtopics: ['Compliant cover letter & itinerary templates', 'B2B flight booking portals & ticketing setup', 'Agency profit scaling & operational best practices'],
    },
  ];
});

const targetAudienceList = computed(() => {
  const dynamicBn = currentEbook.value?.target_audience_bn;
  const dynamicEn = currentEbook.value?.target_audience_en;
  if (themeStore.locale === 'bn' && Array.isArray(dynamicBn) && dynamicBn.length > 0) {
    return dynamicBn;
  }
  if (themeStore.locale !== 'bn' && Array.isArray(dynamicEn) && dynamicEn.length > 0) {
    return dynamicEn;
  }
  if (themeStore.locale === 'bn') {
    return [
      { icon: 'plane', title: 'টিকেটিং এক্সিকিউটিভ', desc: 'এয়ারলাইন্স বা ট্রাভেল এজেন্সিতে কর্মরত বা চাকরিপ্রার্থী।' },
      { icon: 'briefcase', title: 'এজেন্সি উদ্যোক্তা', desc: 'নতুন ট্রাভেল ব্যবসা বা ভিসা কনসালটেন্সি শুরু করতে চান।' },
      { icon: 'cap', title: 'শিক্ষার্থী ও প্রফেশনাল', desc: 'প্র্যাকটিক্যাল স্কিল অর্জন করে ক্যারিয়ার শুরু করতে চান।' },
    ];
  }
  return [
    { icon: 'plane', title: 'Ticketing Officers', desc: 'Working in travel agencies or aspiring airline ticketing staff.' },
    { icon: 'briefcase', title: 'Agency Entrepreneurs', desc: 'Planning to launch a travel agency or visa consultancy.' },
    { icon: 'cap', title: 'Students & Professionals', desc: 'Looking for high-demand, skill-based international career.' },
  ];
});

const labUpsellTitle = computed(() => {
  if (!currentEbook.value) return '';
  return themeStore.locale === 'bn'
    ? (currentEbook.value.lab_upsell_title_bn || 'শুধু বই পড়ে নয়, কম্পিউটারে সরাসরি লাইভ সফটওয়্যার শিখুন!')
    : (currentEbook.value.lab_upsell_title_en || 'Move Beyond Theory: Learn Live GDS on Dedicated Workstations');
});

const labUpsellDesc = computed(() => {
  if (!currentEbook.value) return '';
  return themeStore.locale === 'bn'
    ? (currentEbook.value.lab_upsell_desc_bn || 'ইমিশা একাডেমির মিরপুর ক্যাম্পাসে প্রতিটি শিক্ষার্থীর জন্য রয়েছে আলাদা কম্পিউটার এবং Sabre ও Galileo সিস্টেমের লাইভ সফটওয়্যার অ্যাক্সেস।')
    : (currentEbook.value.lab_upsell_desc_en || 'Join our physical classroom batches at Mirpur Kazipara. 1 Student = 1 Workstation with unlimited lab practice guarantee.');
});

const labUpsellBtnText = computed(() => {
  if (!currentEbook.value) return '';
  return themeStore.locale === 'bn'
    ? (currentEbook.value.lab_upsell_btn_text_bn || 'প্র্যাকটিক্যাল কোর্সসমূহ দেখুন →')
    : (currentEbook.value.lab_upsell_btn_text_en || 'Explore Flagship Courses →');
});

const relatedEbooks = computed(() => {
  return relatedEbooksFromApi.value || [];
});

const toggleChapter = (idx: number) => {
  if (openChapters.value.includes(idx)) {
    openChapters.value = openChapters.value.filter((i) => i !== idx);
  } else {
    openChapters.value.push(idx);
  }
};

const resolveGoogleDriveDownloadUrl = (url: string) => {
  if (!url) return '';
  const trimmed = url.trim();
  const match = trimmed.match(/\/d\/([a-zA-Z0-9_-]+)/) || trimmed.match(/id=([a-zA-Z0-9_-]+)/);
  if (match && match[1]) {
    return `https://drive.google.com/uc?export=download&id=${match[1]}`;
  }
  return trimmed;
};

const resolveGoogleDrivePreviewUrl = (url: string) => {
  if (!url) return '';
  const trimmed = url.trim();
  const match = trimmed.match(/\/d\/([a-zA-Z0-9_-]+)/) || trimmed.match(/id=([a-zA-Z0-9_-]+)/);
  if (match && match[1]) {
    return `https://drive.google.com/file/d/${match[1]}/preview`;
  }
  return trimmed;
};

const handleDownload = () => {
  if (currentEbook.value && !currentEbook.value.is_free) {
    isPaymentModalOpen.value = true;
  } else {
    isDownloadModalOpen.value = true;
  }
};

const handlePreview = () => {
  const previewPath = currentEbook.value?.preview_pdf_path;
  if (!previewPath) {
    toastStore.info(themeStore.locale === 'bn' ? 'কোনো স্যাম্পল প্রিভিউ সংযুক্ত নেই।' : 'No preview available.');
    return;
  }

  if (previewPath.startsWith('http://') || previewPath.startsWith('https://')) {
    const previewUrl = resolveGoogleDrivePreviewUrl(previewPath);
    window.open(previewUrl, '_blank');
  } else {
    const cleanPath = previewPath.startsWith('/') ? previewPath : `/${previewPath}`;
    window.open(cleanPath, '_blank');
  }
};

const copyEbookLink = async () => {
  try {
    await navigator.clipboard.writeText(window.location.href);
    toastStore.success(themeStore.locale === 'bn' ? 'ই-বুক লিংক কপি হয়েছে!' : 'Ebook link copied to clipboard!');
  } catch {
    toastStore.info(window.location.href);
  }
};

const shareFacebook = () => {
  const url = encodeURIComponent(window.location.href);
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
};

const shareWhatsApp = () => {
  const text = encodeURIComponent(`${ebookTitle.value} - ${window.location.href}`);
  window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
};

const updateSeoMetadata = () => {
  if (!currentEbook.value) return;
  const currentTitle = ebookTitle.value;
  const currentDesc = ebookDescription.value;
  const currentCover = currentEbook.value.cover_image;
  
  const bookSchema = buildEbookSchema(currentEbook.value);
  const breadcrumbSchema = buildBreadcrumbSchema([
    { name: themeStore.locale === 'bn' ? 'হোম' : 'Home', url: '/' },
    { name: themeStore.locale === 'bn' ? 'ই-বুক ও রিসোর্স' : 'E-books', url: '/ebooks' },
    { name: currentTitle, url: `/ebooks/${currentEbook.value.slug}` },
  ]);

  setMeta({
    title: currentTitle,
    description: currentDesc,
    keywords: `${currentTitle}, air ticketing ebook pdf, sabre gds manual bangladesh, visa processing guide`,
    image: currentCover,
    type: 'article',
    schema: [bookSchema, breadcrumbSchema],
  });
};

const fetchEbook = async () => {
  loading.value = true;
  try {
    const slug = route.params.slug as string;
    const res = await apiClient.get(`/public/ebooks/${slug}`);
    ebook.value = res.data.data.ebook;
    relatedEbooksFromApi.value = res.data.data.related_ebooks || [];
  } catch (err) {
    ebook.value = null;
    relatedEbooksFromApi.value = [];
  } finally {
    loading.value = false;
    updateSeoMetadata();
  }
};

watch(
  () => [route.params.slug, themeStore.locale],
  () => {
    updateSeoMetadata();
  }
);

watch(
  () => route.params.slug,
  () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    fetchEbook();
  }
);

onMounted(() => {
  fetchEbook();
});
</script>

<style scoped>
.perspective-1000 {
  perspective: 1000px;
}
</style>
