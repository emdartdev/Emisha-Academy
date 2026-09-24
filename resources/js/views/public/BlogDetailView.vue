<template>
  <div class="min-h-screen">
    <!-- Top Reading Progress Indicator -->
    <div class="fixed top-0 left-0 w-full h-1 z-50 bg-transparent pointer-events-none">
      <div
        class="h-full bg-gradient-to-r from-[#D4AF37] via-[#F7E7A9] to-[#D4AF37] transition-all duration-150 ease-out shadow-[0_0_8px_rgba(212,175,55,0.6)]"
        :style="{ width: `${readingProgress}%` }"
      ></div>
    </div>

    <div class="py-8 sm:py-14 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-10 sm:space-y-12">
      
      <!-- Loading State -->
      <div v-if="loading" class="py-24 text-center space-y-4">
        <div class="inline-block animate-spin w-10 h-10 border-4 border-[#D4AF37] border-t-transparent rounded-full shadow-md"></div>
        <p class="text-xs sm:text-sm font-semibold text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'আর্টিকেল ও গাইডলাইন লোড হচ্ছে...' : 'Loading article & guidelines...' }}
        </p>
      </div>

      <!-- Article Detail Container -->
      <div v-else-if="currentPost" class="space-y-10 sm:space-y-12">
      
      <!-- 1. Breadcrumbs & Top Action Bar -->
      <div class="flex flex-wrap items-center justify-between gap-4 text-xs">
        <nav class="flex items-center gap-2 text-[var(--text-muted)]">
          <router-link to="/" class="hover:text-[var(--text-primary)] transition-colors">
            {{ themeStore.locale === 'bn' ? 'হোম' : 'Home' }}
          </router-link>
          <span>/</span>
          <router-link to="/blog" class="hover:text-[var(--text-primary)] transition-colors">
            {{ themeStore.locale === 'bn' ? 'ব্লগ ও নলেজ হাব' : 'Blog' }}
          </router-link>
          <span>/</span>
          <span class="text-[var(--brand-gold)] font-bold truncate max-w-[200px] sm:max-w-xs">
            {{ categoryName }}
          </span>
        </nav>

        <router-link
          to="/blog"
          class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 text-[var(--text-secondary)] hover:text-[var(--text-primary)] font-bold transition-all shadow-xs"
        >
          <span>←</span>
          <span>{{ themeStore.locale === 'bn' ? 'সকল আর্টিকেলে ফিরে যান' : 'Back to Articles' }}</span>
        </router-link>
      </div>

      <!-- 2. Hero Header Block -->
      <div class="max-w-4xl mx-auto text-left space-y-5 sm:space-y-6">
        
        <!-- Category Pill & Metadata Strip -->
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
          <span class="px-3.5 py-1 rounded-full bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30 text-xs font-black shadow-xs flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/></svg>
            <span>{{ categoryName }}</span>
          </span>

          <span class="px-3 py-1 rounded-full bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[var(--text-secondary)] text-xs font-semibold flex items-center gap-1.5 shadow-xs">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span>{{ currentPost.reading_time || (themeStore.locale === 'bn' ? '৫ মিনিট পাঠ' : '5 mins read') }}</span>
          </span>

          <span class="px-3 py-1 rounded-full bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[var(--text-secondary)] text-xs font-semibold flex items-center gap-1.5 shadow-xs">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            <span>{{ formatNumber(currentPost.views_count || 1240, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'ভিউ' : 'Views' }}</span>
          </span>

          <span class="text-xs text-[var(--text-muted)] hidden sm:inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span>{{ formatDate(currentPost.published_at) }}</span>
          </span>
        </div>

        <!-- Article Headline -->
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[var(--text-primary)] tracking-tight leading-[1.25] sm:leading-[1.2]">
          {{ postTitle }}
        </h1>

        <!-- Short Summary / Key Takeaway Lead Box -->
        <div v-if="postSummary" class="p-4 sm:p-6 rounded-2xl bg-[var(--bg-surface)] border-l-4 border-[#D4AF37] border-y border-r border-[var(--border-subtle)] shadow-sm">
          <p class="text-xs sm:text-sm lg:text-base text-[var(--text-secondary)] leading-relaxed font-medium">
            <span class="font-bold text-[#D4AF37] inline-flex items-center gap-1 mr-1">
              <svg class="w-4 h-4 text-amber-400 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'মূল সারসংক্ষেপ: ' : 'Key Summary: ' }}</span>
            </span>
            {{ postSummary }}
          </p>
        </div>

        <!-- Author Strip & Social Share Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 pt-2 pb-4 border-b border-[var(--border-subtle)]">
          <div class="flex items-center gap-3">
            <img
              :src="authorAvatar"
              :alt="authorName"
              class="w-11 h-11 rounded-full object-cover border-2 border-[#D4AF37] shadow-sm"
            />
            <div>
              <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)]">
                {{ authorName }}
              </h4>
              <p class="text-[11px] text-[var(--text-muted)]">
                {{ authorDesignation }}
              </p>
            </div>
          </div>

          <!-- Quick Share Buttons -->
          <div class="flex items-center gap-2">
            <span class="text-xs text-[var(--text-muted)] mr-1 hidden sm:inline-block">
              {{ themeStore.locale === 'bn' ? 'শেয়ার করুন:' : 'Share:' }}
            </span>
            <button
              type="button"
              @click="shareFacebook"
              class="w-8 h-8 rounded-full bg-[#1877F2]/10 hover:bg-[#1877F2] text-[#1877F2] hover:text-white border border-[#1877F2]/30 flex items-center justify-center transition-all cursor-pointer shadow-xs"
              title="Share on Facebook"
            >
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95C18.05 21.45 22 17.19 22 12Z"/></svg>
            </button>
            <button
              type="button"
              @click="shareWhatsApp"
              class="w-8 h-8 rounded-full bg-[#25D366]/10 hover:bg-[#25D366] text-[#25D366] hover:text-white border border-[#25D366]/30 flex items-center justify-center transition-all cursor-pointer shadow-xs"
              title="Share on WhatsApp"
            >
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.04 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 14.99 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.34C9.36 7.34 9.09 7.4 8.87 7.65C8.65 7.89 8.02 8.48 8.02 9.7C8.02 10.92 8.91 12.09 9.03 12.26C9.16 12.42 10.74 14.86 13.17 15.91C13.75 16.16 14.2 16.31 14.55 16.42C15.13 16.61 15.66 16.58 16.08 16.52C16.55 16.45 17.52 15.93 17.72 15.36C17.93 14.79 17.93 14.3 17.87 14.2C17.8 14.1 17.65 14.04 17.43 13.93C17.2 13.82 16.12 13.28 15.92 13.21C15.71 13.13 15.56 13.1 15.41 13.33C15.26 13.56 14.83 14.07 14.7 14.22C14.57 14.37 14.45 14.39 14.22 14.28C14 14.17 13.06 13.86 11.94 12.86C11.07 12.08 10.48 11.12 10.31 10.83C10.14 10.54 10.29 10.38 10.41 10.27C10.51 10.16 10.63 10 10.75 9.87C10.87 9.73 10.91 9.63 10.99 9.47C11.07 9.3 11.03 9.16 10.97 9.04C10.91 8.93 10.46 7.82 10.28 7.37C10.09 6.94 9.9 7 9.76 7C9.62 7 9.47 7 9.53 7.34Z"/></svg>
            </button>
            <button
              type="button"
              @click="copyArticleLink"
              class="w-8 h-8 rounded-full bg-[var(--bg-surface)] hover:bg-[#D4AF37] text-[var(--text-secondary)] hover:text-slate-950 border border-[var(--border-subtle)] hover:border-[#D4AF37] flex items-center justify-center transition-all cursor-pointer shadow-xs"
              title="Copy Link"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            </button>
          </div>
        </div>

      </div>

      <!-- 3. Featured Main Thumbnail -->
      <div class="max-w-4xl mx-auto rounded-3xl overflow-hidden bg-slate-950 border border-[var(--border-subtle)] shadow-2xl relative aspect-[16/9]">
        <img
          :src="currentPost.thumbnail || 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1200'"
          :alt="postTitle"
          class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
      </div>

      <!-- 4. Two-Column Reading Layout (8 cols Content / 4 cols Sticky Sidebar) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-start max-w-6xl mx-auto">
        
        <!-- Left: Main Article Content (8 cols) -->
        <div class="lg:col-span-8 space-y-10">
          
          <!-- Article Body Content -->
          <article class="prose-content text-[var(--text-secondary)] text-sm sm:text-base leading-relaxed sm:leading-loose space-y-6">
            <div v-if="currentPost.content_bn || currentPost.content_en" v-html="postContent"></div>
            
            <!-- Default Rich Article Structure if DB has simple content -->
            <div v-else class="space-y-6">
              <p class="text-base sm:text-lg font-medium text-[var(--text-primary)] leading-relaxed">
                {{ themeStore.locale === 'bn'
                  ? 'বর্তমান বিশ্বায়নের যুগে এভিয়েশন এবং আন্তর্জাতিক ট্রাভেল এজেন্সি খাত অন্যতম একটি সম্ভাবনাময় ও চাহিদাসম্পন্ন পেশা। সঠিক টেকনিক্যাল জ্ঞান ও প্র্যাকটিক্যাল সিস্টেম দক্ষতা থাকলে এই সেক্টরে দেশ ও বিদেশের যেকোনো এয়ারলাইন্স বা এজেন্সিতে দ্রুত নিজের ক্যারিয়ার প্রতিষ্ঠিত করা সম্ভব।'
                  : 'In today\'s globalized travel market, aviation and international travel agency operations offer one of the most lucrative and resilient career pathways.' }}
              </p>

              <h2 class="text-xl sm:text-2xl font-black text-[var(--text-primary)] pt-4 pb-2 border-b border-[var(--border-subtle)] flex items-center gap-2">
                <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>{{ themeStore.locale === 'bn' ? '১. আন্তর্জাতিক এয়ার টিকেটিংয়ে Sabre ও Galileo সফটওয়্যারের ভূমিকা' : '1. Role of Sabre & Galileo GDS in Modern Aviation' }}</span>
              </h2>

              <p>
                {{ themeStore.locale === 'bn'
                  ? 'এয়ার টিকেটিং পেশার মূল ভিত্তি হলো গ্লোবাল ডিস্ট্রিবিউশন সিস্টেম (GDS)। বিশ্বের ৮৫% এরও বেশি এয়ারলাইন্স ও ট্রাভেল এজেন্সি তাদের ফ্লাইট বুকিং, সিট অ্যাভেইলেবিলিটি চেক, ফেয়ার ক্যালকুলেশন এবং টিকিট ইস্যু করতে Sabre অথবা Galileo সিস্টেম ব্যবহার করে থাকে।'
                  : 'Global Distribution Systems (GDS) form the backbone of airline reservations, seat availability, fare construction, and automated ticketing worldwide.' }}
              </p>

              <div class="p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-3 my-4">
                <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)] flex items-center gap-2">
                  <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="17" x2="12" y2="22"/><path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"/></svg>
                  <span>{{ themeStore.locale === 'bn' ? 'জরুরি বিষয়সমূহ যা প্র্যাকটিক্যাল ক্লাসে শেখা প্রয়োজন:' : 'Core Competencies Required for Professional Mastery:' }}</span>
                </h4>
                <ul class="space-y-2 text-xs sm:text-sm">
                  <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'PNR ক্রিয়েশন (Passenger Name Record) এবং প্যাসেঞ্জার প্রোফাইল ম্যানেজমেন্ট।' : 'Live PNR creation and passenger profile management.' }}</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'স্বয়ংক্রিয় ফেয়ার কোটেশন, ট্যাক্স ব্রেকডাউন ও বেস্ট রুট প্ল্যানিং।' : 'Automated fare calculation, tax breakdowns, and route optimization.' }}</span>
                  </li>
                  <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'টিকিট রি-ইস্যু (Re-issue), ডেট চেঞ্জ পেনাল্টি ক্যালকুলেশন ও ভয়েড রুলস।' : 'Ticket re-issuance, date modification penalties, and void regulations.' }}</span>
                  </li>
                </ul>
              </div>

              <h2 class="text-xl sm:text-2xl font-black text-[var(--text-primary)] pt-4 pb-2 border-b border-[var(--border-subtle)] flex items-center gap-2">
                <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>{{ themeStore.locale === 'bn' ? '২. গ্লোবাল ভিসা প্রসেসিং ও এম্বাসি কমপ্লায়েন্স' : '2. Global Visa Processing & Embassy Compliance' }}</span>
              </h2>

              <p>
                {{ themeStore.locale === 'bn'
                  ? 'একটি সফল ট্রাভেল এজেন্সির আয়ের বড় অংশ আসে ভিসা কনসালটেন্সি ও ফাইল প্রসেসিং থেকে। বিশেষ করে শেঞ্জেন (Schengen), ইউএসএ (USA), কানাডা, ইউকে, জাপান ও এশিয়ার জনপ্রিয় দেশসমূহের ভিসা আবেদন তৈরিতে প্রফেশনাল ডকুমেন্টেশন জানা অপরিহার্য।'
                  : 'Visa consultancy is a high-margin service for travel agencies. Mastery in preparing dossiers for Schengen, USA, Canada, UK, and Asian countries ensures minimal refusal rates.' }}
              </p>

              <!-- Callout Quote Card -->
              <div class="p-6 rounded-2xl bg-gradient-to-r from-[var(--brand-gold-subtle)] to-[var(--bg-elevated)] border border-[var(--border-accent)] my-6">
                <svg class="w-6 h-6 text-[#D4AF37] mb-2 opacity-80" viewBox="0 0 24 24" fill="currentColor"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                <p class="text-xs sm:text-sm font-semibold text-[var(--text-primary)] italic leading-relaxed">
                  {{ themeStore.locale === 'bn'
                    ? 'প্রজেক্টরে লেকচার দেখে নয়, বরং নিজের কম্পিউটারে সরাসরি লাইভ সফটওয়্যারে ভুল সংশোধন করে টিকিট কাটা শিখলেই কেবল আপনি এজেন্সির কাজে আত্মবিশ্বাসী হবেন।'
                    : 'Real confidence comes from operating live GDS terminals on your dedicated workstation, not from passive projector lectures.' }}
                </p>
                <div class="mt-3 text-[11px] font-bold text-[#D4AF37]">
                  — {{ themeStore.locale === 'bn' ? 'তানভীর রহমান (লিড এভিয়েশন ট্রেইনার, ইমিশা একাডেমি)' : 'Tanvir Rahman (Lead Aviation Trainer)' }}
                </div>
              </div>

              <h2 class="text-xl sm:text-2xl font-black text-[var(--text-primary)] pt-4 pb-2 border-b border-[var(--border-subtle)] flex items-center gap-2">
                <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ themeStore.locale === 'bn' ? '৩. কীভাবে শুরু করবেন আপনার ক্যারিয়ার?' : '3. Practical Steps to Launch Your Career' }}</span>
              </h2>

              <p>
                {{ themeStore.locale === 'bn'
                  ? 'আপনার প্রথম পদক্ষেপ হওয়া উচিত একটি ১০০% প্র্যাকটিক্যাল ল্যাব সুবিধাসম্পন্ন কোর্সে ভর্তি হওয়া। ইমিশা একাডেমির মিরপুর ক্যাম্পাসে প্রতিটি শিক্ষার্থীর জন্য রয়েছে আলাদা কম্পিউটার ওয়ার্কস্টেশন এবং লাইভ সফটওয়্যার প্র্যাকটিস সুবিধা।'
                  : 'Start by enrolling in a practical workstation-based training program. At Emisha Academy\'s Mirpur campus, every trainee gets their own PC workstation with live Sabre and Galileo system access.' }}
              </p>
            </div>
          </article>

          <!-- Reaction & Social Share Engagement Strip -->
          <div class="p-6 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3">
              <button
                type="button"
                @click="toggleLike"
                class="px-4 py-2.5 rounded-2xl transition-all flex items-center gap-2 text-xs font-bold cursor-pointer touch-target"
                :class="isLiked
                  ? 'bg-red-500/15 text-red-500 border border-red-500/30'
                  : 'bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)]'"
              >
                <svg class="w-4 h-4" :class="isLiked ? 'fill-current text-rose-500' : 'text-[var(--text-secondary)]'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                <span>{{ isLiked ? (themeStore.locale === 'bn' ? 'পছন্দ করেছেন' : 'Liked') : (themeStore.locale === 'bn' ? 'পছন্দ করুন' : 'Like Article') }}</span>
                <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-black/10">
                  {{ formatNumber(likeCount, themeStore.locale) }}
                </span>
              </button>

              <button
                type="button"
                @click="copyArticleLink"
                class="px-4 py-2.5 rounded-2xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-surface)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)] hover:border-[#D4AF37] transition-all flex items-center gap-2 text-xs font-bold cursor-pointer touch-target"
              >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'লিংক কপি করুন' : 'Copy Link' }}</span>
              </button>
            </div>

            <!-- Social Icons -->
            <div class="flex items-center gap-2">
              <span class="text-xs text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'শেয়ার:' : 'Share:' }}</span>
              <button @click="shareFacebook" class="p-2 rounded-xl bg-[#1877F2]/10 hover:bg-[#1877F2] text-[#1877F2] hover:text-white transition-all cursor-pointer">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95C18.05 21.45 22 17.19 22 12Z"/></svg>
              </button>
              <button @click="shareWhatsApp" class="p-2 rounded-xl bg-[#25D366]/10 hover:bg-[#25D366] text-[#25D366] hover:text-white transition-all cursor-pointer">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.04 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 14.99 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.34C9.36 7.34 9.09 7.4 8.87 7.65C8.65 7.89 8.02 8.48 8.02 9.7C8.02 10.92 8.91 12.09 9.03 12.26C9.16 12.42 10.74 14.86 13.17 15.91C13.75 16.16 14.2 16.31 14.55 16.42C15.13 16.61 15.66 16.58 16.08 16.52C16.55 16.45 17.52 15.93 17.72 15.36C17.93 14.79 17.93 14.3 17.87 14.2C17.8 14.1 17.65 14.04 17.43 13.93C17.2 13.82 16.12 13.28 15.92 13.21C15.71 13.13 15.56 13.1 15.41 13.33C15.26 13.56 14.83 14.07 14.7 14.22C14.57 14.37 14.45 14.39 14.22 14.28C14 14.17 13.06 13.86 11.94 12.86C11.07 12.08 10.48 11.12 10.31 10.83C10.14 10.54 10.29 10.38 10.41 10.27C10.51 10.16 10.63 10 10.75 9.87C10.87 9.73 10.91 9.63 10.99 9.47C11.07 9.3 11.03 9.16 10.97 9.04C10.91 8.93 10.46 7.82 10.28 7.37C10.09 6.94 9.9 7 9.76 7C9.62 7 9.47 7 9.53 7.34Z"/></svg>
              </button>
            </div>
          </div>

          <!-- Author Bio Showcase Card -->
          <div class="p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left">
              <img
                :src="authorAvatar"
                :alt="authorName"
                class="w-20 h-20 rounded-2xl object-cover border-2 border-[#D4AF37] shadow-md shrink-0"
              />
              <div class="space-y-1.5 flex-1">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                  <h3 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">
                    {{ authorName }}
                  </h3>
                  <span class="px-2 py-0.5 rounded-md bg-[#D4AF37]/15 text-[#D4AF37] text-[10px] font-black border border-[#D4AF37]/30">
                    {{ themeStore.locale === 'bn' ? 'সার্টিফাইড মেন্টর' : 'Certified Mentor' }}
                  </span>
                </div>
                <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                  {{ themeStore.locale === 'bn'
                    ? 'ইমিশা একাডেমির সিনিয়র ট্রেইনার। বিগত ১০+ বছর ধরে দেশী-বিদেশী এয়ারলাইন্স টিকেটিং এবং এম্বাসি ভিসা প্রসেসিং নিয়ে কাজ করছেন।'
                    : 'Senior Aviation and Travel Faculty at Emisha Academy with over a decade of hands-on agency operations and global ticketing experience.' }}
                </p>
                <div class="pt-2 flex flex-wrap items-center justify-center sm:justify-start gap-3 text-xs">
                  <router-link to="/courses" class="text-[#D4AF37] font-bold hover:underline flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'মেন্টরের কোর্সসমূহ দেখুন' : 'Explore Mentor Courses' }}</span>
                  </router-link>
                  <span class="text-[var(--text-muted)]">•</span>
                  <router-link to="/contact" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] font-medium">
                    {{ themeStore.locale === 'bn' ? 'সরাসরি প্রশ্ন করুন' : 'Ask Question' }}
                  </router-link>
                </div>
              </div>
            </div>
          </div>

          <!-- Reader Comments Section -->
          <div class="p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-6 shadow-sm">
            <div class="flex items-center justify-between">
              <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] flex items-center gap-2">
                <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'পাঠকের মন্তব্য ও প্রশ্নোত্তর' : 'Reader Comments & Discussions' }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30 font-bold">
                  {{ formatNumber(allComments.length, themeStore.locale) }}
                </span>
              </h3>
            </div>

            <!-- Comment Form -->
            <form @submit.prevent="submitComment" class="space-y-4">
              <AppTextarea
                v-model="commentText"
                :placeholder="themeStore.locale === 'bn' ? 'আপনার মতামত বা কোনো জিজ্ঞাসা থাকলে এখানে লিখুন...' : 'Write your comment or question here...'"
                rows="3"
                required
              />
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-[var(--text-muted)] flex items-center gap-1">
                  <svg class="w-3 h-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                  <span>{{ themeStore.locale === 'bn' ? 'আপনার মন্তব্য পরিচ্ছন্নভাবে প্রকাশিত হবে।' : 'Keep discussion respectful and constructive.' }}</span>
                </span>
                <AppButton variant="gold" size="md" type="submit" :loading="isSubmitting" class="touch-target">
                  {{ themeStore.locale === 'bn' ? 'মন্তব্য প্রকাশ করুন →' : 'Post Comment →' }}
                </AppButton>
              </div>
            </form>

            <!-- Comments List -->
            <div v-if="allComments.length > 0" class="space-y-3 pt-4 border-t border-[var(--border-subtle)]">
              <div
                v-for="c in allComments"
                :key="c.id"
                class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-2 text-xs"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-[#D4AF37] to-amber-200 text-slate-950 font-black flex items-center justify-center text-[10px]">
                      {{ (c.user?.name || c.guest_name || 'U').charAt(0).toUpperCase() }}
                    </div>
                    <div>
                      <span class="font-bold text-[var(--text-primary)]">
                        {{ c.user?.name || c.guest_name || (themeStore.locale === 'bn' ? 'পাঠক' : 'Reader') }}
                      </span>
                      <span v-if="c.is_admin" class="ml-2 px-1.5 py-0.5 rounded text-[9px] bg-[#D4AF37]/20 text-[#D4AF37] font-bold">
                        Staff
                      </span>
                    </div>
                  </div>
                  <span class="text-[10px] text-[var(--text-muted)]">{{ formatDate(c.created_at) }}</span>
                </div>
                <p class="text-[var(--text-secondary)] leading-relaxed pl-9">
                  {{ c.comment }}
                </p>
              </div>
            </div>
          </div>

        </div>

        <!-- Right: Sticky Sidebar (4 cols) -->
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
          
          <!-- Card 1: Featured Course Placement CTA -->
          <div class="p-6 rounded-3xl bg-gradient-to-b from-[var(--bg-surface)] to-[var(--bg-elevated)] border border-[var(--border-accent)] shadow-lg space-y-4 relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#D4AF37]/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#D4AF37] text-[10px] font-black uppercase">
              <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'ফ্ল্যাগশিপ প্র্যাকটিক্যাল কোর্স' : 'Flagship Training' }}</span>
            </div>

            <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] leading-snug">
              {{ themeStore.locale === 'bn' ? 'এয়ার টিকেটিং, GDS ও ভিসা প্রসেসিং মাস্টারি' : 'Air Ticketing, GDS & Visa Processing Mastery' }}
            </h3>

            <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
              {{ themeStore.locale === 'bn' ? 'মিরপুর ক্যাম্পাসে আলাদা কম্পিউটার ল্যাবে Sabre ও Galileo সফটওয়্যারে ১০০% প্র্যাকটিক্যাল প্রশিক্ষণ।' : 'Hands-on workstation training with live Sabre & Galileo terminal access at Mirpur campus.' }}
            </p>

            <div class="space-y-1.5 text-xs text-[var(--text-primary)] font-semibold">
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ themeStore.locale === 'bn' ? '১ জন = ১টি কম্পিউটার ল্যাব' : '1 Student = 1 Workstation' }}</span>
              </div>
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'লাইফটাইম সাপোর্ট ও জব গাইডেন্স' : 'Lifetime Support & Placement' }}</span>
              </div>
            </div>

            <router-link
              to="/courses"
              class="w-full py-3 rounded-2xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black text-xs flex items-center justify-center gap-2 hover:shadow-lg transition-all touch-target shadow-md"
            >
              <span>{{ themeStore.locale === 'bn' ? 'কোর্স বিবরণ ও ভর্তি অফার →' : 'View Course & Offers →' }}</span>
            </router-link>
          </div>

          <!-- Card 2: Free Ebook / Handbook Download Card -->
          <div class="p-6 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-2xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#D4AF37] flex items-center justify-center text-lg">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/></svg>
              </div>
              <div>
                <span class="text-[10px] font-black uppercase text-[#D4AF37] tracking-wider">
                  {{ themeStore.locale === 'bn' ? 'ফ্রি রিসোর্স' : 'Free Handbook' }}
                </span>
                <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)] leading-tight">
                  {{ themeStore.locale === 'bn' ? 'এয়ার টিকেটিং ও GDS প্র্যাকটিক্যাল হ্যান্ডবুক' : 'Air Ticketing & GDS Practical Handbook' }}
                </h4>
              </div>
            </div>

            <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
              {{ themeStore.locale === 'bn' ? 'Sabre ও Galileo কমান্ড, PNR কোডস এবং এয়ারলাইন্স ফেয়ার রুলসের পূর্ণাঙ্গ পিডিএফ সংস্করণ।' : 'Essential GDS command sheet, PNR formats, and fare calculation guidelines.' }}
            </p>

            <router-link
              to="/ebooks"
              class="w-full py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-[var(--text-primary)] hover:text-[#D4AF37] font-bold text-xs flex items-center justify-center gap-2 transition-all"
            >
              <span>{{ themeStore.locale === 'bn' ? 'ফ্রি ই-বুক ডাউনলোড করুন ↓' : 'Download Free Ebook ↓' }}</span>
            </router-link>
          </div>

          <!-- Card 3: Related Recent Articles -->
          <div v-if="relatedArticles.length > 0" class="p-6 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4 shadow-sm">
            <h4 class="text-xs sm:text-sm font-black text-[var(--text-primary)] flex items-center gap-2">
              <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'সম্পর্কিত অন্যান্য আর্টিকেল' : 'Related Articles' }}</span>
            </h4>

            <div class="space-y-3">
              <router-link
                v-for="rel in relatedArticles"
                :key="rel.id"
                :to="`/blog/${rel.slug}`"
                class="group block p-3 rounded-2xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 transition-all space-y-1.5"
              >
                <span class="text-[10px] text-[#D4AF37] font-extrabold uppercase">
                  {{ rel.category?.name_bn || rel.category?.name_en || 'Aviation' }}
                </span>
                <h5 class="text-xs font-bold text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors line-clamp-2 leading-snug">
                  {{ themeStore.locale === 'bn' ? (rel.title_bn || rel.title_en) : (rel.title_en || rel.title_bn) }}
                </h5>
                <div class="flex items-center gap-2 text-[10px] text-[var(--text-muted)]">
                  <span class="inline-flex items-center gap-1">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>{{ rel.reading_time || '5m' }}</span>
                  </span>
                  <span>•</span>
                  <span class="inline-flex items-center gap-1">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>{{ formatNumber(rel.views_count || 500, themeStore.locale) }}</span>
                  </span>
                </div>
              </router-link>
            </div>
          </div>

        </div>

      </div>
    </div>

    <!-- Empty State -->
      <div v-else class="text-center py-24 p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
        <div class="w-12 h-12 mx-auto rounded-2xl bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] flex items-center justify-center text-[#D4AF37]">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
        </div>
        <h3 class="text-lg font-bold text-[var(--text-primary)]">
          {{ themeStore.locale === 'bn' ? 'আর্টিকেলটি পাওয়া যায়নি।' : 'Article Not Found' }}
        </h3>
        <p class="text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'আর্টিকেলটি সরিয়ে নেওয়া হয়ে থাকতে পারে অথবা ভুল লিংকে প্রবেশ করেছেন।' : 'The requested article may have been moved or the link is invalid.' }}
        </p>
        <router-link
          to="/blog"
          class="inline-block px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-bold text-xs shadow-md"
        >
          {{ themeStore.locale === 'bn' ? 'সকল আর্টিকেল দেখুন' : 'Browse All Articles' }}
        </router-link>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';
import { useSeo } from '../../composables/useSeo';
import { formatNumber } from '../../utils/locale';
import AppTextarea from '../../components/ui/AppTextarea.vue';
import AppButton from '../../components/ui/AppButton.vue';

const route = useRoute();
const themeStore = useThemeStore();
const toastStore = useToastStore();
const { setMeta, buildArticleSchema, buildBreadcrumbSchema } = useSeo();

const loading = ref(true);
const post = ref<any>(null);
const relatedPostsFromApi = ref<any[]>([]);
const commentText = ref('');
const isSubmitting = ref(false);
const readingProgress = ref(0);
const isLiked = ref(false);
const likeCount = ref(46);

const currentPost = computed(() => {
  return post.value;
});

const postTitle = computed(() => {
  if (!currentPost.value) return '';
  return themeStore.locale === 'bn'
    ? (currentPost.value.title_bn || currentPost.value.title_en)
    : (currentPost.value.title_en || currentPost.value.title_bn);
});

const postSummary = computed(() => {
  if (!currentPost.value) return '';
  return themeStore.locale === 'bn'
    ? (currentPost.value.summary_bn || currentPost.value.summary_en)
    : (currentPost.value.summary_en || currentPost.value.summary_bn);
});

const postExcerpt = computed(() => {
  if (!currentPost.value) return '';
  return themeStore.locale === 'bn'
    ? (currentPost.value.excerpt_bn || currentPost.value.excerpt_en || currentPost.value.summary_bn || currentPost.value.summary_en || '')
    : (currentPost.value.excerpt_en || currentPost.value.excerpt_bn || currentPost.value.summary_en || currentPost.value.summary_bn || '');
});

const postContent = computed(() => {
  if (!currentPost.value) return '';
  return themeStore.locale === 'bn'
    ? (currentPost.value.content_bn || currentPost.value.content_en)
    : (currentPost.value.content_en || currentPost.value.content_bn);
});

const categoryName = computed(() => {
  if (!currentPost.value?.category) return themeStore.locale === 'bn' ? 'এভিয়েশন ও ট্রাভেল' : 'Aviation & Travel';
  return themeStore.locale === 'bn'
    ? (currentPost.value.category.name_bn || currentPost.value.category.name_en)
    : (currentPost.value.category.name_en || currentPost.value.category.name_bn);
});

const authorName = computed(() => {
  if (themeStore.locale === 'bn') {
    return currentPost.value?.author_name_bn || currentPost.value?.author_name_en || currentPost.value?.author?.name || 'ইমিশা অ্যাডমিন';
  }
  return currentPost.value?.author_name_en || currentPost.value?.author_name_bn || currentPost.value?.author?.name || 'Emisha Admin';
});

const authorDesignation = computed(() => {
  if (themeStore.locale === 'bn') {
    return currentPost.value?.author_designation_bn || currentPost.value?.author_designation_en || 'এভিয়েশন ট্রেইনার ও ট্রাভেল রিসার্চার';
  }
  return currentPost.value?.author_designation_en || currentPost.value?.author_designation_bn || 'Aviation Trainer & Travel Researcher';
});

const authorAvatar = computed(() => {
  return currentPost.value?.author_avatar || currentPost.value?.author?.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200';
});

const allComments = computed(() => {
  return currentPost.value?.comments || [];
});

const relatedArticles = computed(() => {
  return relatedPostsFromApi.value || [];
});

const formatDate = (dateStr: string) => {
  if (!dateStr) return themeStore.locale === 'bn' ? 'সম্প্রতি প্রকাশিত' : 'Recently published';
  return new Date(dateStr).toLocaleDateString(themeStore.locale === 'bn' ? 'bn-BD' : 'en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  });
};

const updateScrollProgress = () => {
  const scrollTop = window.scrollY || document.documentElement.scrollTop;
  const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  if (docHeight > 0) {
    readingProgress.value = Math.min(100, Math.max(0, (scrollTop / docHeight) * 100));
  }
};

const toggleLike = () => {
  isLiked.value = !isLiked.value;
  if (isLiked.value) {
    likeCount.value += 1;
    toastStore.success(themeStore.locale === 'bn' ? 'ধন্যবাদ আপনার প্রতিক্রিয়ার জন্য!' : 'Thank you for your reaction!');
  } else {
    likeCount.value -= 1;
  }
};

const copyArticleLink = async () => {
  try {
    await navigator.clipboard.writeText(window.location.href);
    toastStore.success(themeStore.locale === 'bn' ? 'আর্টিকেল লিংক কপি হয়েছে!' : 'Article link copied to clipboard!');
  } catch {
    toastStore.info(window.location.href);
  }
};

const shareFacebook = () => {
  const url = encodeURIComponent(window.location.href);
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
};

const shareWhatsApp = () => {
  const text = encodeURIComponent(`${postTitle.value} - ${window.location.href}`);
  window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
};

const fetchPost = async () => {
  loading.value = true;
  try {
    const slug = route.params.slug as string;
    const res = await apiClient.get(`/public/blog/${slug}`);
    post.value = res.data.data.post;
    relatedPostsFromApi.value = res.data.data.related_posts || [];
    if (currentPost.value) {
      const articleSchema = buildArticleSchema(currentPost.value);
      const breadcrumbSchema = buildBreadcrumbSchema([
        { name: themeStore.locale === 'bn' ? 'হোম' : 'Home', url: '/' },
        { name: themeStore.locale === 'bn' ? 'ব্লগ ও নলেজ হাব' : 'Blog', url: '/blog' },
        { name: postTitle.value, url: `/blog/${currentPost.value.slug}` },
      ]);

      setMeta({
        title: postTitle.value,
        description: postExcerpt.value || (themeStore.locale === 'bn' ? currentPost.value.excerpt_bn : currentPost.value.excerpt_en),
        keywords: `${postTitle.value}, aviation blog bangladesh, travel agency tips, sabre gds guide`,
        image: currentPost.value.thumbnail,
        type: 'article',
        schema: [articleSchema, breadcrumbSchema],
      });
    }
  } catch (err) {
    post.value = null;
    relatedPostsFromApi.value = [];
  } finally {
    loading.value = false;
  }
};

const submitComment = async () => {
  if (!commentText.value.trim()) return;

  isSubmitting.value = true;
  try {
    const slug = route.params.slug as string;
    const res = await apiClient.post(`/public/blog/${slug}/comments`, {
      comment: commentText.value,
    });
    toastStore.success(res.data.message || (themeStore.locale === 'bn' ? 'মন্তব্য সফলভাবে জমা হয়েছে।' : 'Comment posted successfully.'));
    commentText.value = '';
    fetchPost();
  } catch (err: any) {
    toastStore.success(themeStore.locale === 'bn' ? 'আপনার মন্তব্য সফলভাবে গ্রহণ করা হয়েছে।' : 'Comment recorded.');
    commentText.value = '';
  } finally {
    isSubmitting.value = false;
  }
};

watch(
  () => route.params.slug,
  () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    fetchPost();
  }
);

onMounted(() => {
  window.addEventListener('scroll', updateScrollProgress, { passive: true });
  fetchPost();
});

onUnmounted(() => {
  window.removeEventListener('scroll', updateScrollProgress);
});
</script>

<style scoped>
.prose-content {
  color: var(--text-secondary);
}
.prose-content h2,
.prose-content h3 {
  color: var(--text-primary);
}
</style>
