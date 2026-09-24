<template>
  <div class="py-8 sm:py-14 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 sm:space-y-16">
    
    <!-- Loading State -->
    <div v-if="loading" class="py-24 text-center space-y-4">
      <div class="inline-block animate-spin w-10 h-10 border-4 border-[#D4AF37] border-t-transparent rounded-full shadow-md"></div>
      <p class="text-xs sm:text-sm font-semibold text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'সেমিনার ও মাস্টারক্লাসের বিবরণ লোড হচ্ছে...' : 'Loading masterclass details...' }}
      </p>
    </div>

    <!-- Webinar Detail Content -->
    <template v-else-if="currentWebinar">
      
      <!-- 1. Breadcrumb Navigation & Top Action Strip -->
      <div class="flex flex-wrap items-center justify-between gap-4 text-xs">
        <nav class="flex items-center gap-2 text-[var(--text-muted)]">
          <router-link to="/" class="hover:text-[var(--text-primary)] transition-colors">
            {{ themeStore.locale === 'bn' ? 'হোম' : 'Home' }}
          </router-link>
          <span>/</span>
          <router-link to="/webinars" class="hover:text-[var(--text-primary)] transition-colors">
            {{ themeStore.locale === 'bn' ? 'ওয়েবিনার ও কর্মশালা' : 'Webinars' }}
          </router-link>
          <span>/</span>
          <span class="text-[var(--brand-gold)] font-bold truncate max-w-[200px] sm:max-w-xs">
            {{ isUpcoming ? (themeStore.locale === 'bn' ? 'আসন্ন লাইভ সেশন' : 'Upcoming Session') : (themeStore.locale === 'bn' ? 'রেকর্ডিং আর্কাইভ' : 'Recorded Archive') }}
          </span>
        </nav>

        <router-link
          to="/webinars"
          class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 text-[var(--text-secondary)] hover:text-[var(--text-primary)] font-bold transition-all shadow-xs"
        >
          <span>←</span>
          <span>{{ themeStore.locale === 'bn' ? 'সকল সেমিনারে ফিরে যান' : 'Back to Webinars' }}</span>
        </router-link>
      </div>

      <!-- 2. Hero Header Block (Middle Aligned) -->
      <div class="max-w-4xl mx-auto text-center flex flex-col items-center space-y-4 sm:space-y-5">
        
        <!-- Live Status Badges Strip -->
        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
          <span
            class="px-3.5 py-1 rounded-full text-xs font-black tracking-wide shadow-xs flex items-center gap-1.5"
            :class="isUpcoming 
              ? 'bg-rose-500/15 text-rose-500 border border-rose-500/30' 
              : 'bg-slate-800 text-slate-300 border border-slate-700'"
          >
            <span class="w-2 h-2 rounded-full" :class="isUpcoming ? 'bg-rose-500 animate-ping' : 'bg-slate-400'"></span>
            <span>{{ isUpcoming ? (themeStore.locale === 'bn' ? 'লাইভ ক্যারিয়ার মাস্টারক্লাস' : 'Live Masterclass') : (themeStore.locale === 'bn' ? 'রেকর্ডেড আর্কাইভ' : 'Recorded Archive') }}</span>
          </span>

          <span class="px-3 py-1 rounded-full bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[var(--text-secondary)] text-xs font-semibold flex items-center gap-1.5 shadow-xs">
            <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span>{{ formatEventDate(currentWebinar.event_datetime) }}</span>
          </span>

          <span class="px-3 py-1 rounded-full bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[var(--text-secondary)] text-xs font-semibold flex items-center gap-1.5 shadow-xs">
            <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span>{{ currentWebinar.duration_minutes || 90 }} {{ themeStore.locale === 'bn' ? 'মিনিট সেশন' : 'Mins' }}</span>
          </span>

          <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-xs font-black shadow-xs">
            <span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-emerald-500 fill-current" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>{{ currentWebinar.is_free ? (themeStore.locale === 'bn' ? '১০০% ফ্রি এন্ট্রি' : '100% Free') : formatCurrency(currentWebinar.registration_fee, themeStore.locale) }}</span>
          </span>
        </div>

        <!-- Headline -->
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[var(--text-primary)] tracking-tight leading-[1.25] sm:leading-[1.2] max-w-3xl">
          {{ webinarTitle }}
        </h1>

        <!-- Subtitle -->
        <p v-if="webinarSubtitle" class="text-xs sm:text-sm lg:text-base text-[var(--text-secondary)] leading-relaxed font-medium max-w-2xl">
          {{ webinarSubtitle }}
        </p>
      </div>

      <!-- 3. Flagship Two-Column Reading Layout (8 cols Left / 4 cols Right Sticky Sidebar) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-start max-w-7xl mx-auto">
        
        <!-- Left: Main Content & Media (8 cols) -->
        <div class="lg:col-span-8 space-y-8 sm:space-y-10">
          
          <!-- Cinematic Thumbnail / Video Screen -->
          <div class="relative aspect-[16/9] w-full rounded-3xl overflow-hidden bg-slate-950 border border-[var(--border-subtle)] shadow-2xl group">
            <img
              :src="currentWebinar.thumbnail || getWebinarFallbackThumbnail()"
              :alt="webinarTitle"
              class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-700"
              @error="onImageError($event, 'webinar')"
            />
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>

            <!-- Center Play Button for Recorded Web Video -->
            <button
              v-if="!isUpcoming"
              type="button"
              @click="openRecordingModal"
              class="absolute inset-0 m-auto w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-[#D4AF37] text-slate-950 flex items-center justify-center text-2xl sm:text-3xl shadow-2xl hover:scale-110 active:scale-95 transition-all cursor-pointer z-20 hover:bg-[#F7E7A9] border-2 border-white/20"
              :title="themeStore.locale === 'bn' ? 'রেকর্ডিং ভিডিও দেখুন' : 'Watch Recording Video'"
            >
              <span>▶</span>
            </button>

            <!-- Platform & Venue Indicator Overlay -->
            <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between gap-2 z-10">
              <span class="px-3 py-1.5 rounded-xl bg-slate-950/90 backdrop-blur-md border border-white/15 text-white text-xs font-bold flex items-center gap-2 shadow-lg">
                <span class="w-2.5 h-2.5 rounded-full" :class="isUpcoming ? 'bg-emerald-400' : 'bg-[#D4AF37]'"></span>
                <span>{{ currentWebinar.platform || 'Zoom Live & Mirpur Lab' }}</span>
              </span>

              <span v-if="isUpcoming" class="px-3 py-1.5 rounded-xl bg-black/70 backdrop-blur-md text-[#D4AF37] text-xs font-bold border border-[#D4AF37]/30">
                <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>{{ themeStore.locale === 'bn' ? 'সরাসরি প্রশ্নোত্তর পর্ব' : 'Live Interactive Q&A' }}</span>
              </span>
              <span v-else class="px-3 py-1.5 rounded-xl bg-black/70 backdrop-blur-md text-emerald-400 text-xs font-bold border border-emerald-500/30 flex items-center gap-1.5">
                <span>▶</span>
                <span>{{ themeStore.locale === 'bn' ? 'এইচডি রেকর্ডিং প্রস্তুত' : 'HD Stream Ready' }}</span>
              </span>
            </div>
          </div>

          <!-- Description Narrative Card -->
          <div class="p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4 shadow-sm">
            <h3 class="text-sm sm:text-base font-black text-[#D4AF37] uppercase tracking-wider flex items-center gap-2">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'সেমিনার সম্পর্কিত তথ্য ও প্রেক্ষাপট' : 'Masterclass Overview & Scope' }}</span>
            </h3>

            <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed sm:leading-loose">
              {{ webinarDescription }}
            </p>
          </div>

          <!-- What You Will Learn (Key Highlights Checklist) -->
          <div class="p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-5 shadow-sm">
            <h3 class="text-sm sm:text-base font-black text-[var(--text-primary)] flex items-center gap-2">
              <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'এই মাস্টারক্লাসে আপনি যা যা শিখবেন' : 'What You Will Learn in This Masterclass' }}</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs sm:text-sm">
              <div
                v-for="(point, idx) in sessionHighlights"
                :key="idx"
                class="p-3.5 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] flex items-start gap-2.5"
              >
                <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span class="text-[var(--text-secondary)] font-medium leading-relaxed">{{ point }}</span>
              </div>
            </div>
          </div>

          <!-- Session Agenda & Timeline Schedule -->
          <div class="p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-5 shadow-sm">
            <div class="flex items-center justify-between">
              <h3 class="text-sm sm:text-base font-black text-[var(--text-primary)] flex items-center gap-2">
                <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'সেশন সূচিপত্র ও সময়সূচি' : 'Session Agenda & Schedule' }}</span>
              </h3>
              <span class="text-xs text-[var(--brand-gold)] font-bold">
                {{ currentWebinar.duration_minutes || 90 }} {{ themeStore.locale === 'bn' ? 'মিনিটের পূর্ণাঙ্গ সেশন' : 'Mins Full Session' }}
              </span>
            </div>

            <div class="space-y-3">
              <div
                v-for="(item, aIdx) in sessionAgenda"
                :key="aIdx"
                class="p-4 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] flex flex-col sm:flex-row sm:items-center justify-between gap-3"
              >
                <div class="flex items-start gap-3">
                  <span class="w-7 h-7 rounded-xl bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30 text-xs font-black flex items-center justify-center shrink-0 mt-0.5">
                    {{ formatNumber(aIdx + 1, themeStore.locale) }}
                  </span>
                  <div>
                    <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)]">
                      {{ item.title }}
                    </h4>
                    <p class="text-[11px] text-[var(--text-muted)] mt-0.5">
                      {{ item.desc }}
                    </p>
                  </div>
                </div>

                <span class="px-2.5 py-1 rounded-lg bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[10px] font-bold text-[#D4AF37] self-start sm:self-center shrink-0">
                  <span class="inline-flex items-center gap-1 text-[#D4AF37]"><svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>{{ item.time }}</span>
                </span>
              </div>
            </div>
          </div>

          <!-- Keynote Speakers Showcase -->
          <div class="p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-5 shadow-sm">
            <h3 class="text-sm sm:text-base font-black text-[var(--text-primary)] flex items-center gap-2">
              <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'প্রধান স্পিকার ও ট্রেইনার' : 'Keynote Speakers & Mentors' }}</span>
            </h3>

            <div class="space-y-4">
              <div
                v-for="speaker in speakersList"
                :key="speaker.id"
                class="p-5 rounded-2xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left"
              >
                <img
                  :src="speaker.avatar || getInitialsAvatar(speaker.name)"
                  :alt="speaker.name"
                  class="w-16 h-16 rounded-2xl object-cover border-2 border-[#D4AF37] shadow-md shrink-0 bg-slate-900"
                  @error="onImageError($event, 'avatar', speaker.name)"
                />
                <div class="space-y-1 min-w-0 flex-1">
                  <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                    <h4 class="text-sm sm:text-base font-bold text-[var(--text-primary)]">
                      {{ speaker.name }}
                    </h4>
                    <span class="px-2 py-0.5 rounded text-[10px] bg-[#D4AF37]/15 text-[#D4AF37] font-bold border border-[#D4AF37]/30">
                      {{ themeStore.locale === 'bn' ? 'সার্টিফাইড ফ্যাকাল্টি' : 'Certified Faculty' }}
                    </span>
                  </div>
                  <p class="text-xs text-[#D4AF37] font-semibold">
                    {{ speaker.designation }}
                  </p>
                  <p class="text-xs text-[var(--text-secondary)] leading-relaxed pt-1">
                    {{ speaker.bio }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Participation Certificate & Access Guarantee Banner -->
          <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-emerald-500/10 via-[var(--bg-elevated)] to-amber-500/10 border border-emerald-500/20 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center shrink-0">
              <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div class="space-y-1">
              <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)]">
                {{ certificateTitle }}
              </h4>
              <p class="text-[11px] text-[var(--text-secondary)] leading-relaxed">
                {{ certificateNote }}
              </p>
            </div>
          </div>

        </div>

        <!-- Right: Sticky Registration / Action Hub (4 cols) -->
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
          
          <!-- Registration Box -->
          <div id="webinar-register-card" class="p-6 sm:p-7 rounded-3xl bg-gradient-to-b from-[var(--bg-surface)] to-[var(--bg-elevated)] border border-[var(--border-accent)] shadow-2xl space-y-5">
            
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-[#D4AF37]">
                  {{ isUpcoming ? (themeStore.locale === 'bn' ? 'ফ্রি রেজিস্ট্রেশন' : 'Free Registration') : (themeStore.locale === 'bn' ? 'রেকর্ডিং সংরক্ষিত' : 'Recorded Archive') }}
                </span>
                <span v-if="isUpcoming" class="px-2.5 py-0.5 rounded-full bg-rose-500/15 border border-rose-500/30 text-rose-500 text-[10px] font-black animate-pulse">
                  <span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-amber-500 fill-current" viewBox="0 0 24 24"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>{{ formatNumber(seatsRemaining, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'সিট বাকি' : 'seats left' }}</span>
                </span>
              </div>

              <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] leading-snug">
                {{ isUpcoming ? (themeStore.locale === 'bn' ? 'লাইভ সেশনের জন্য সিট বুক করুন' : 'Reserve Your Free Seat') : (themeStore.locale === 'bn' ? 'সেশনের রেকর্ডিং দেখুন' : 'Watch Session Recording') }}
              </h3>
            </div>

            <!-- Seat Progress Bar (for Upcoming) -->
            <div v-if="isUpcoming" class="space-y-1.5 p-3 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)]">
              <div class="flex items-center justify-between text-[10px] text-[var(--text-muted)] font-medium">
                <span>{{ formatNumber(seatFillPercent, themeStore.locale) }}% {{ themeStore.locale === 'bn' ? 'আসন পূর্ণ' : 'Filled' }}</span>
                <span class="text-amber-500 font-bold">{{ themeStore.locale === 'bn' ? 'দ্রুত বুক করুন' : 'Filling Fast' }}</span>
              </div>
              <div class="w-full bg-[var(--bg-surface)] h-2 rounded-full overflow-hidden border border-[var(--border-subtle)]">
                <div class="h-full bg-gradient-to-r from-amber-500 to-[#D4AF37] rounded-full transition-all" :style="{ width: `${seatFillPercent}%` }"></div>
              </div>
            </div>

            <!-- Success State -->
            <div v-if="isRegistered" class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-center space-y-2">
              <div class="w-12 h-12 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center mx-auto"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
              <h4 class="text-xs sm:text-sm font-bold text-emerald-400">
                {{ themeStore.locale === 'bn' ? 'আপনার রেজিস্ট্রেশন সফল হয়েছে!' : 'Seat Confirmed Successfully!' }}
              </h4>
              <p class="text-[11px] text-[var(--text-secondary)] leading-relaxed">
                {{ themeStore.locale === 'bn' ? 'আপনার হোয়াটসঅ্যাপ ও ইমেইলে জুম লিংক ও রিমাইন্ডার পাঠানো হয়েছে।' : 'Zoom link details have been sent to your contact information.' }}
              </p>
            </div>

            <!-- Registration Form (for Upcoming) -->
            <form v-else-if="isUpcoming" @submit.prevent="handleRegister" class="space-y-3.5">
              <AppInput
                v-model="regForm.name"
                :label="themeStore.locale === 'bn' ? 'আপনার পূর্ণ নাম' : 'Full Name'"
                :placeholder="themeStore.locale === 'bn' ? 'যেমন: তানভীর হাসান' : 'e.g. Tanvir Hasan'"
                required
              />
              <AppInput
                v-model="regForm.email"
                type="email"
                :label="themeStore.locale === 'bn' ? 'ইমেইল ঠিকানা' : 'Email Address'"
                placeholder="name@example.com"
                required
              />
              <AppInput
                v-model="regForm.phone"
                :label="themeStore.locale === 'bn' ? 'হোয়াটসঅ্যাপ / ফোন নম্বর' : 'WhatsApp Phone'"
                placeholder="018XXXXXXXX"
                required
              />

              <button
                type="submit"
                :disabled="isSubmitting"
                class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black text-xs sm:text-sm flex items-center justify-center gap-2 hover:shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer touch-target shadow-lg"
              >
                <span v-if="isSubmitting" class="inline-block animate-spin w-4 h-4 border-2 border-slate-950 border-t-transparent rounded-full"></span>
                <span v-else><svg class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg></span>
                <span>{{ isSubmitting ? (themeStore.locale === 'bn' ? 'সিট কনফার্ম হচ্ছে...' : 'Reserving Seat...') : (themeStore.locale === 'bn' ? 'ফ্রি সিট নিশ্চিত করুন →' : 'Confirm Free Seat →') }}</span>
              </button>
            </form>

            <!-- Past Webinar Watch Action -->
            <div v-else class="space-y-3">
              <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                {{ themeStore.locale === 'bn' ? 'এই মাস্টারক্লাসটি সফলভাবে সম্পন্ন হয়েছে। রেকর্ডিং ভিডিওটি দেখতে নিচের বাটনে ক্লিক করুন।' : 'This session has ended. Click below to stream the full video recording.' }}
              </p>
              <button
                type="button"
                @click="openRecordingModal"
                class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black text-xs sm:text-sm flex items-center justify-center gap-2 hover:shadow-lg transition-all cursor-pointer shadow-md"
              >
                <span>▶</span>
                <span>{{ themeStore.locale === 'bn' ? 'রেকর্ডিং ভিডিও দেখুন' : 'Watch Recording Video' }}</span>
              </button>
            </div>

            <!-- Trust Points Strip -->
            <div class="space-y-1.5 text-[11px] text-[var(--text-secondary)] border-t border-[var(--border-subtle)] pt-3">
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'জুম লিংক তাৎক্ষণিক হোয়াটসঅ্যাপে পাঠানো হবে।' : 'Instant Zoom invite via WhatsApp/Email.' }}</span>
              </div>
              <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'কোনো পূর্ববর্তী অভিজ্ঞতার প্রয়োজন নেই।' : 'No prior aviation experience required.' }}</span>
              </div>
            </div>

            <!-- Share Buttons -->
            <div class="flex items-center justify-between pt-2 border-t border-[var(--border-subtle)] text-xs text-[var(--text-muted)]">
              <span>{{ themeStore.locale === 'bn' ? 'বন্ধুদের ইনভাইট করুন:' : 'Invite Peers:' }}</span>
              <div class="flex items-center gap-2">
                <button @click="shareFacebook" class="p-2 rounded-xl bg-[#1877F2]/10 hover:bg-[#1877F2] text-[#1877F2] hover:text-white transition-all cursor-pointer" title="Share on Facebook">
                  <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95C18.05 21.45 22 17.19 22 12Z"/></svg>
                </button>
                <button @click="shareWhatsApp" class="p-2 rounded-xl bg-[#25D366]/10 hover:bg-[#25D366] text-[#25D366] hover:text-white transition-all cursor-pointer" title="Share on WhatsApp">
                  <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.04 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 14.99 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.34C9.36 7.34 9.09 7.4 8.87 7.65C8.65 7.89 8.02 8.48 8.02 9.7C8.02 10.92 8.91 12.09 9.03 12.26C9.16 12.42 10.74 14.86 13.17 15.91C13.75 16.16 14.2 16.31 14.55 16.42C15.13 16.61 15.66 16.58 16.08 16.52C16.55 16.45 17.52 15.93 17.72 15.36C17.93 14.79 17.93 14.3 17.87 14.2C17.8 14.1 17.65 14.04 17.43 13.93C17.2 13.82 16.12 13.28 15.92 13.21C15.71 13.13 15.56 13.1 15.41 13.33C15.26 13.56 14.83 14.07 14.7 14.22C14.57 14.37 14.45 14.39 14.22 14.28C14 14.17 13.06 13.86 11.94 12.86C11.07 12.08 10.48 11.12 10.31 10.83C10.14 10.54 10.29 10.38 10.41 10.27C10.51 10.16 10.63 10 10.75 9.87C10.87 9.73 10.91 9.63 10.99 9.47C11.07 9.3 11.03 9.16 10.97 9.04C10.91 8.93 10.46 7.82 10.28 7.37C10.09 6.94 9.9 7 9.76 7C9.62 7 9.47 7 9.53 7.34Z"/></svg>
                </button>
                <button @click="copyWebinarLink" class="p-2 rounded-xl bg-[var(--bg-surface)] hover:bg-[#D4AF37] text-[var(--text-secondary)] hover:text-slate-950 transition-all cursor-pointer" title="Copy Link">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                </button>
              </div>
            </div>

          </div>

          <!-- Practical Course Upsell Card -->
          <div class="p-6 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-3.5 shadow-sm">
            <span class="text-[10px] font-black uppercase text-[#D4AF37] tracking-wider block">
              {{ themeStore.locale === 'bn' ? 'প্র্যাকটিক্যাল ট্রেনিং' : 'Practical Lab Track' }}
            </span>
            <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)] leading-tight">
              {{ labUpsellTitle }}
            </h4>
            <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
              {{ labUpsellDesc }}
            </p>
            <router-link
              to="/courses"
              class="w-full py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-[var(--text-primary)] hover:text-[#D4AF37] font-bold text-xs flex items-center justify-center gap-2 transition-all"
            >
              <span>{{ themeStore.locale === 'bn' ? 'কোর্সসমূহ দেখুন →' : 'Explore Courses →' }}</span>
            </router-link>
          </div>

        </div>

      </div>

      <!-- 4. Related Masterclasses & Webinars -->
      <div v-if="relatedWebinars.length > 0" class="space-y-6">
        <div class="flex items-center justify-between">
          <div class="space-y-1">
            <h3 class="text-lg sm:text-2xl font-black text-[var(--text-primary)] flex items-center gap-2">
              <svg class="w-5 h-5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'সম্পর্কিত অন্যান্য সেমিনার ও কর্মশালা' : 'Related Webinars & Workshops' }}</span>
            </h3>
            <p class="text-xs text-[var(--text-secondary)]">
              {{ themeStore.locale === 'bn' ? 'এভিয়েশন ও ট্রাভেল ক্যারিয়ারের আরও গুরুত্বপূর্ণ লাইভ সেশন।' : 'Explore other practical workshops conducted by our mentors.' }}
            </p>
          </div>

          <router-link to="/webinars" class="text-xs font-bold text-[#D4AF37] hover:underline hidden sm:inline-block">
            {{ themeStore.locale === 'bn' ? 'সবগুলো দেখুন →' : 'View All →' }}
          </router-link>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <WebinarCard
            v-for="item in relatedWebinars"
            :key="item.id"
            :webinar="item"
          />
        </div>
      </div>

      <!-- Mobile Sticky Bottom Registration / Stream Bar -->
      <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-[var(--bg-surface)]/95 backdrop-blur-xl border-t border-[var(--border-subtle)] p-3 px-4 shadow-[0_-8px_30px_rgba(0,0,0,0.15)] safe-bottom">
        <div class="flex items-center justify-between gap-3 max-w-lg mx-auto">
          <div class="min-w-0 flex-1">
            <span class="text-[10px] font-black uppercase tracking-wider text-[#D4AF37] block">
              {{ isUpcoming ? (themeStore.locale === 'bn' ? 'ফ্রি লাইভ সেশন' : 'Free Live Session') : (themeStore.locale === 'bn' ? 'রেকর্ডেড সেশন' : 'Recorded Session') }}
            </span>
            <div class="text-xs font-bold text-[var(--text-primary)] truncate">
              {{ isUpcoming ? (seatsRemaining > 0 ? `${formatNumber(seatsRemaining, themeStore.locale)} ${themeStore.locale === 'bn' ? 'সিট বাকি' : 'seats left'}` : (themeStore.locale === 'bn' ? 'আসন সীমিত' : 'Seats Limited')) : (themeStore.locale === 'bn' ? 'ভিডিও আনলক' : 'Full HD Video') }}
            </div>
          </div>
          <button
            v-if="isUpcoming"
            type="button"
            @click="scrollToRegister"
            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black text-xs shadow-md shrink-0 active:scale-95 transition-all touch-target flex items-center gap-1.5"
          >
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            <span>{{ isRegistered ? (themeStore.locale === 'bn' ? 'নিশ্চিত হয়েছে' : 'Registered') : (themeStore.locale === 'bn' ? 'সিট বুক করুন' : 'Reserve Seat') }}</span>
          </button>
          <button
            v-else
            type="button"
            @click="openRecordingModal"
            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black text-xs shadow-md shrink-0 active:scale-95 transition-all touch-target flex items-center gap-1.5"
          >
            <span>▶</span>
            <span>{{ themeStore.locale === 'bn' ? 'ভিডিও দেখুন' : 'Watch Video' }}</span>
          </button>
        </div>
      </div>

    </template>

    <!-- Empty State -->
    <div v-else class="text-center py-24 p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4">
      <div class="w-14 h-14 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto shadow-sm"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/></svg></div>
      <h3 class="text-lg font-bold text-[var(--text-primary)]">
        {{ themeStore.locale === 'bn' ? 'ওয়েবিনারটি পাওয়া যায়নি।' : 'Webinar Not Found' }}
      </h3>
      <p class="text-xs text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'ওয়েবিনারটি সমাপ্ত হয়ে থাকতে পারে অথবা ভুল লিংকে প্রবেশ করেছেন।' : 'The requested session may have ended or the link is invalid.' }}
      </p>
      <router-link
        to="/webinars"
        class="inline-block px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-bold text-xs shadow-md"
      >
        {{ themeStore.locale === 'bn' ? 'সকল সেমিনার দেখুন' : 'Browse All Webinars' }}
      </router-link>
    </div>

    <!-- Recorded Webinar Access & Lead Capture Modal (Luxury, Minimal & Eye-Soothing) -->
    <AppModal
      v-model="isRecordingModalOpen"
      :title="recordingUnlocked 
        ? (themeStore.locale === 'bn' ? 'সেশন রেকর্ডিং ভিডিও' : 'Session Video Recording')
        : (themeStore.locale === 'bn' ? 'রেকর্ডেড ভিডিও দেখতে তথ্য দিন' : 'Unlock Video Recording')"
      :size="recordingUnlocked ? 'lg' : 'md'"
    >
      <!-- Mode 1: Video Unlocked & Player Active -->
      <div v-if="recordingUnlocked" class="space-y-5 p-1">
        
        <!-- Video Screen Container -->
        <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black shadow-2xl border border-[var(--border-subtle)] relative">
          <iframe
            class="w-full h-full"
            :src="embedVideoUrl"
            :title="webinarTitle"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
          ></iframe>
        </div>

        <!-- Video Info Strip -->
        <div class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-3">
          <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
            <div class="space-y-1">
              <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/20">
                {{ themeStore.locale === 'bn' ? 'ফুল এইচডি স্ট্রিমিং আনলকড' : 'Full HD Stream Unlocked' }}
              </span>
              <h4 class="text-sm sm:text-base font-bold text-[var(--text-primary)] leading-snug">{{ webinarTitle }}</h4>
              <p class="text-xs text-[var(--text-secondary)]">
                {{ speakersList[0]?.name }} • {{ currentWebinar.duration_minutes || 90 }} {{ themeStore.locale === 'bn' ? 'মিনিট পূর্ণাঙ্গ সেশন' : 'Mins Full Session' }}
              </p>
            </div>

            <a
              :href="whatsAppSupportUrl"
              target="_blank"
              rel="noopener noreferrer"
              class="px-3.5 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-md shrink-0 transition-all touch-target self-start"
            >
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.04 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 14.99 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.34C9.36 7.34 9.09 7.4 8.87 7.65C8.65 7.89 8.02 8.48 8.02 9.7C8.02 10.92 8.91 12.09 9.03 12.26C9.16 12.42 10.74 14.86 13.17 15.91C13.75 16.16 14.2 16.31 14.55 16.42C15.13 16.61 15.66 16.58 16.08 16.52C16.55 16.45 17.52 15.93 17.72 15.36C17.93 14.79 17.93 14.3 17.87 14.2C17.8 14.1 17.65 14.04 17.43 13.93C17.2 13.82 16.12 13.28 15.92 13.21C15.71 13.13 15.56 13.1 15.41 13.33C15.26 13.56 14.83 14.07 14.7 14.22C14.57 14.37 14.45 14.39 14.22 14.28C14 14.17 13.06 13.86 11.94 12.86C11.07 12.08 10.48 11.12 10.31 10.83C10.14 10.54 10.29 10.38 10.41 10.27C10.51 10.16 10.63 10 10.75 9.87C10.87 9.73 10.91 9.63 10.99 9.47C11.07 9.3 11.03 9.16 10.97 9.04C10.91 8.93 10.46 7.82 10.28 7.37C10.09 6.94 9.9 7 9.76 7C9.62 7 9.47 7 9.53 7.34Z"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'WhatsApp সাপোর্ট' : 'WhatsApp Support' }}</span>
            </a>
          </div>

          <!-- Key Chapters Strip -->
          <div class="pt-2 border-t border-[var(--border-subtle)] flex flex-wrap items-center gap-2 text-[11px] text-[var(--text-muted)]">
            <span class="font-bold text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'গুরুত্বপূর্ণ অংশসমূহ:' : 'Key Chapters:' }}</span>
            <span class="px-2 py-0.5 rounded-md bg-[var(--bg-surface)] border border-[var(--border-subtle)]">00:00 ভূমিকা</span>
            <span class="px-2 py-0.5 rounded-md bg-[var(--bg-surface)] border border-[var(--border-subtle)]">15:30 Sabre/Galileo GDS</span>
            <span class="px-2 py-0.5 rounded-md bg-[var(--bg-surface)] border border-[var(--border-subtle)]">50:00 ভিসা ফাইল অডিট</span>
            <span class="px-2 py-0.5 rounded-md bg-[var(--bg-surface)] border border-[var(--border-subtle)]">01:10:00 প্রশ্নোত্তর</span>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
          <router-link
            to="/courses"
            class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-bold text-[var(--text-primary)] hover:text-[#D4AF37] transition-all text-center"
            @click="isRecordingModalOpen = false"
          >
            {{ themeStore.locale === 'bn' ? 'পূর্ণাঙ্গ কোর্স ও ল্যাব ট্রেইনিং দেখুন →' : 'Explore Full Courses & Lab Track →' }}
          </router-link>

          <button
            type="button"
            @click="isRecordingModalOpen = false"
            class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'বন্ধ করুন' : 'Close' }}
          </button>
        </div>

      </div>

      <!-- Mode 2: Lead Capture Form to Unlock Video -->
      <form v-else @submit.prevent="submitRecordingLead" class="space-y-4 p-1">
        
        <!-- Header Banner in Modal -->
        <div class="p-3.5 sm:p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] flex items-center gap-3.5">
          <div class="w-12 h-12 rounded-xl bg-[var(--bg-surface)] border border-[#D4AF37]/40 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
          </div>
          <div class="min-w-0 flex-1 space-y-0.5">
            <span class="text-[10px] font-extrabold uppercase text-[#D4AF37] tracking-wider">
              {{ themeStore.locale === 'bn' ? 'রেকর্ডেড মাস্টারক্লাস আর্কাইভ' : 'Recorded Masterclass Stream' }}
            </span>
            <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)] truncate">{{ webinarTitle }}</h4>
            <div class="flex items-center gap-2 text-[11px] text-[var(--text-muted)]">
              <span class="inline-flex items-center gap-1"><svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>{{ currentWebinar.duration_minutes || 90 }} {{ themeStore.locale === 'bn' ? 'মিনিট সেশন' : 'Mins' }}</span>
              <span class="text-emerald-500 font-medium">• {{ themeStore.locale === 'bn' ? '১০০% ফ্রি স্ট্রিমিং' : '100% Free Access' }}</span>
            </div>
          </div>
        </div>

        <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
          {{ themeStore.locale === 'bn' ? 'রেকর্ডিং ভিডিওটি দেখতে আপনার নাম, ফোন ও হোয়াটসঅ্যাপ নম্বর প্রদান করুন। তাৎক্ষণিকভাবে ভিডিও প্লেয়ারটি আনলক হয়ে যাবে।' : 'Please enter your name, phone, and WhatsApp number to instantly unlock the full HD recording.' }}
        </p>

        <!-- 1. Full Name -->
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-[var(--text-primary)] flex items-center justify-between">
            <span>{{ themeStore.locale === 'bn' ? 'আপনার পূর্ণ নাম' : 'Full Name' }} <span class="text-rose-500">*</span></span>
          </label>
          <input
            v-model="recordingLeadForm.name"
            type="text"
            required
            :placeholder="themeStore.locale === 'bn' ? 'যেমন: মোঃ তানভীর হাসান' : 'e.g. Tanvir Hasan'"
            class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] transition-colors"
          />
        </div>

        <!-- 2. Phone Number -->
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-[var(--text-primary)] flex items-center justify-between">
            <span>{{ themeStore.locale === 'bn' ? 'ফোন নম্বর' : 'Phone Number' }} <span class="text-rose-500">*</span></span>
          </label>
          <input
            v-model="recordingLeadForm.phone"
            type="tel"
            required
            placeholder="018XXXXXXXX"
            class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] transition-colors"
          />
        </div>

        <!-- 3. WhatsApp Number & Toggle -->
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <label class="text-xs font-bold text-[var(--text-primary)] flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'হোয়াটসঅ্যাপ নম্বর' : 'WhatsApp Number' }} <span class="text-rose-500">*</span></span>
            </label>
            <label class="inline-flex items-center gap-1.5 text-[11px] text-[var(--text-secondary)] cursor-pointer select-none">
              <input
                type="checkbox"
                v-model="recordingLeadForm.sameAsPhone"
                class="rounded border-[var(--border-subtle)] text-[#D4AF37] focus:ring-[#D4AF37] accent-[#D4AF37]"
              />
              <span>{{ themeStore.locale === 'bn' ? 'ফোন নম্বরটিই হোয়াটসঅ্যাপ' : 'Same as phone' }}</span>
            </label>
          </div>

          <div v-if="!recordingLeadForm.sameAsPhone" class="transition-all">
            <input
              v-model="recordingLeadForm.whatsapp_number"
              type="tel"
              required
              placeholder="018XXXXXXXX"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-emerald-500 transition-colors"
            />
          </div>
          <div v-else class="px-3.5 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-[11px] text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'ফোন নম্বরটিকে হোয়াটসঅ্যাপ নম্বর হিসেবে ব্যবহার করা হবে' : 'Phone number will be used for WhatsApp updates' }}</span>
          </div>
        </div>

        <!-- Optional Notes -->
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-[var(--text-secondary)]">
            {{ themeStore.locale === 'bn' ? 'কোনো প্রশ্ন বা আগ্রহের বিষয় (ঐচ্ছিক)' : 'Questions or Specific Interests (Optional)' }}
          </label>
          <textarea
            v-model="recordingLeadForm.notes"
            rows="2"
            :placeholder="themeStore.locale === 'bn' ? 'যেমন: Sabre সফটওয়্যার বা ভিসা ফাইল তৈরি সম্পর্কে জানতে চাই...' : 'e.g. Interested in Sabre software or tourist visa filing...'"
            class="w-full px-4 py-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] resize-none"
          ></textarea>
        </div>

        <!-- Privacy Assurance -->
        <p class="text-[10px] text-[var(--text-muted)] text-center leading-relaxed">
          <span class="inline-flex items-center gap-1.5"><svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>{{ themeStore.locale === 'bn' ? 'আপনার যোগাযোগের তথ্য ১০০% নিরাপদ ও সুরক্ষিত থাকবে।' : 'Your contact information is strictly confidential & secured.' }}</span>
        </p>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-2 border-t border-[var(--border-subtle)]">
          <button
            type="button"
            @click="isRecordingModalOpen = false"
            class="px-4 py-2.5 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'বাতিল' : 'Cancel' }}
          </button>

          <button
            type="submit"
            :disabled="isSubmittingRecordingLead"
            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 font-black text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all cursor-pointer disabled:opacity-50 flex items-center gap-2"
          >
            <span v-if="isSubmittingRecordingLead">{{ themeStore.locale === 'bn' ? 'আনলক হচ্ছে...' : 'Unlocking...' }}</span>
            <span v-else>{{ themeStore.locale === 'bn' ? 'ভিডিও আনলক করুন ও দেখুন ▶' : 'Unlock & Watch Video ▶' }}</span>
          </button>
        </div>

      </form>
    </AppModal>

  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';
import { useAuthStore } from '../../stores/auth';
import { useSeo } from '../../composables/useSeo';
import { formatCurrency, formatNumber } from '../../utils/locale';
import AppInput from '../../components/ui/AppInput.vue';
import AppModal from '../../components/ui/AppModal.vue';
import WebinarCard from '../../components/shared/WebinarCard.vue';
import { onImageError, getWebinarFallbackThumbnail, getInitialsAvatar } from '../../utils/imageFallback';

const route = useRoute();
const themeStore = useThemeStore();
const toastStore = useToastStore();
const authStore = useAuthStore();
const { setMeta, buildWebinarSchema, buildBreadcrumbSchema } = useSeo();

const loading = ref(true);
const webinar = ref<any>(null);
const relatedWebinarsFromApi = ref<any[]>([]);
const isSubmitting = ref(false);
const isRegistered = ref(false);

// Recorded Webinar Lead Capture State
const isRecordingModalOpen = ref(false);
const isSubmittingRecordingLead = ref(false);
const recordingUnlocked = ref(false);

const recordingLeadForm = reactive({
  name: '',
  phone: '',
  whatsapp_number: '',
  sameAsPhone: true,
  notes: '',
});

const whatsAppSupportUrl = computed(() => {
  const msg = themeStore.locale === 'bn'
    ? `হ্যালো ইমিশা একাডেমি, আমি "${webinarTitle.value}" রেকর্ডেড সেমিনারটি দেখেছি এবং কোর্স বা ক্যারিয়ার বিষয়ে কথা বলতে চাই।`
    : `Hello Emisha Academy, I watched the "${webinarTitle.value}" recorded masterclass and would like to learn more about training & admissions.`;
  return `https://wa.me/8801805464293?text=${encodeURIComponent(msg)}`;
});

const regForm = reactive({
  name: '',
  email: '',
  phone: '',
});

const currentWebinar = computed(() => {
  return webinar.value;
});

const webinarTitle = computed(() => {
  if (!currentWebinar.value) return '';
  return themeStore.locale === 'bn'
    ? (currentWebinar.value.title_bn || currentWebinar.value.title_en)
    : (currentWebinar.value.title_en || currentWebinar.value.title_bn);
});

const webinarSubtitle = computed(() => {
  if (!currentWebinar.value) return '';
  return themeStore.locale === 'bn'
    ? (currentWebinar.value.subtitle_bn || currentWebinar.value.subtitle_en)
    : (currentWebinar.value.subtitle_en || currentWebinar.value.subtitle_bn);
});

const webinarDescription = computed(() => {
  if (!currentWebinar.value) return '';
  return themeStore.locale === 'bn'
    ? (currentWebinar.value.description_bn || currentWebinar.value.subtitle_bn || currentWebinar.value.description_en)
    : (currentWebinar.value.description_en || currentWebinar.value.subtitle_en || currentWebinar.value.description_bn);
});

const isUpcoming = computed(() => {
  return currentWebinar.value?.status !== 'past';
});

const seatsRemaining = computed(() => {
  const max = currentWebinar.value?.max_participants || 100;
  const reg = currentWebinar.value?.registered_count || 78;
  return Math.max(0, max - reg);
});

const seatFillPercent = computed(() => {
  const max = currentWebinar.value?.max_participants || 100;
  const reg = currentWebinar.value?.registered_count || 78;
  return Math.min(100, Math.round((reg / max) * 100));
});

const speakersList = computed(() => {
  const list = currentWebinar.value?.speakers || [];
  return list.map((sp: any) => ({
    id: sp.id,
    name: themeStore.locale === 'bn' ? (sp.name_bn || sp.name_en || sp.name) : (sp.name_en || sp.name_bn || sp.name),
    designation: themeStore.locale === 'bn' ? (sp.designation_bn || sp.designation_en || sp.designation) : (sp.designation_en || sp.designation_bn || sp.designation),
    avatar: sp.avatar || 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150',
    bio: themeStore.locale === 'bn' ? (sp.bio_bn || sp.bio_en || sp.designation_bn) : (sp.bio_en || sp.bio_bn || sp.designation_en),
  }));
});

const certificateTitle = computed(() => {
  if (!currentWebinar.value) return themeStore.locale === 'bn' ? 'ডিজিটাল ভেরিফাইড সার্টিফিকেট নিশ্চয়তা' : 'Verified Digital Participation Certificate';
  return themeStore.locale === 'bn'
    ? (currentWebinar.value.certificate_title_bn || currentWebinar.value.certificate_title_en || 'ডিজিটাল ভেরিফাইড সার্টিফিকেট নিশ্চয়তা')
    : (currentWebinar.value.certificate_title_en || currentWebinar.value.certificate_title_bn || 'Verified Digital Participation Certificate');
});

const certificateNote = computed(() => {
  if (!currentWebinar.value) return themeStore.locale === 'bn' ? 'সম্পূর্ণ সেশনে উপস্থিত থাকলে আপনি একটি ডিজিটাল সার্টিফিকেট ও প্র্যাকটিস শিট সম্পূর্ণ ফ্রিতে পাবেন।' : 'Attending the full session entitles you to a verifiable certificate and digital cheat sheet.';
  return themeStore.locale === 'bn'
    ? (currentWebinar.value.certificate_note_bn || currentWebinar.value.certificate_note_en || 'সম্পূর্ণ সেশনে উপস্থিত থাকলে আপনি একটি ডিজিটাল সার্টিফিকেট ও প্র্যাকটিস শিট সম্পূর্ণ ফ্রিতে পাবেন।')
    : (currentWebinar.value.certificate_note_en || currentWebinar.value.certificate_note_bn || 'Attending the full session entitles you to a verifiable certificate and digital cheat sheet.');
});

const labUpsellTitle = computed(() => {
  if (!currentWebinar.value) return themeStore.locale === 'bn' ? 'মিরপুর ক্যাম্পাসে আলাদা কম্পিউটার ল্যাবে পূর্ণাঙ্গ কোর্স শিখুন' : 'Learn on Dedicated PC Workstations at Mirpur Campus';
  return themeStore.locale === 'bn'
    ? (currentWebinar.value.lab_upsell_title_bn || currentWebinar.value.lab_upsell_title_en || 'মিরপুর ক্যাম্পাসে আলাদা কম্পিউটার ল্যাবে পূর্ণাঙ্গ কোর্স শিখুন')
    : (currentWebinar.value.lab_upsell_title_en || currentWebinar.value.lab_upsell_title_bn || 'Learn on Dedicated PC Workstations at Mirpur Campus');
});

const labUpsellDesc = computed(() => {
  if (!currentWebinar.value) return themeStore.locale === 'bn' ? '১ জন শিক্ষার্থী = ১টি কম্পিউটার ল্যাব এবং লাইভ Sabre ও Galileo সফটওয়্যার অ্যাক্সেস।' : '1 Student = 1 Workstation with live airline ticketing software access.';
  return themeStore.locale === 'bn'
    ? (currentWebinar.value.lab_upsell_desc_bn || currentWebinar.value.lab_upsell_desc_en || '১ জন শিক্ষার্থী = ১টি কম্পিউটার ল্যাব এবং লাইভ Sabre ও Galileo সফটওয়্যার অ্যাক্সেস।')
    : (currentWebinar.value.lab_upsell_desc_en || currentWebinar.value.lab_upsell_desc_bn || '1 Student = 1 Workstation with live airline ticketing software access.');
});

const embedVideoUrl = computed(() => {
  const rawUrl = currentWebinar.value?.recording_url;
  if (!rawUrl) return 'https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1&rel=0';

  if (rawUrl.includes('youtube.com/embed/')) {
    return rawUrl.includes('?') ? `${rawUrl}&autoplay=1` : `${rawUrl}?autoplay=1`;
  }
  if (rawUrl.includes('youtube.com/watch?v=')) {
    const videoId = rawUrl.split('v=')[1]?.split('&')[0];
    return `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
  }
  if (rawUrl.includes('youtu.be/')) {
    const videoId = rawUrl.split('youtu.be/')[1]?.split('?')[0];
    return `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
  }
  return rawUrl;
});

const sessionHighlights = computed(() => {
  const dbHighlights = themeStore.locale === 'bn'
    ? (currentWebinar.value?.highlights_bn || currentWebinar.value?.highlights_en)
    : (currentWebinar.value?.highlights_en || currentWebinar.value?.highlights_bn);

  if (Array.isArray(dbHighlights) && dbHighlights.length > 0) {
    return dbHighlights.filter((h: any) => typeof h === 'string' && h.trim().length > 0);
  }

  if (themeStore.locale === 'bn') {
    return [
      'Sabre ও Galileo GDS সফটওয়্যারে লাইভ স্ক্রিন অপারেশন।',
      'আন্তর্জাতিক এয়ারলাইন্স কোডস ও ফেয়ার ক্যালকুলেশন রুলস।',
      'সরাসরি লাইভ PNR ক্রিয়েশন ও প্যাসেঞ্জার ফাইল প্রসেসিং।',
      'শেঞ্জেন ও ইউএসএ ভিসা ফাইল প্রস্তুতকরণের সঠিক নির্দেশিকা।',
      'এয়ার টিকেটিং শিখে ট্রাভেল এজেন্সিতে জব ও ক্যারিয়ার অপরচুনিটি।',
      'সরাসরি স্পিকারদের সাথে উন্মুক্ত লাইভ প্রশ্নোত্তর পর্ব (Q&A)।',
    ];
  }
  return [
    'Live software screen demonstration of Sabre & Galileo GDS.',
    'International airline codes, routing, and fare construction rules.',
    'Hands-on live PNR creation and passenger profile workflows.',
    'Step-by-step compliant tourist visa dossier preparation.',
    'Career guidance on landing agency jobs and scaling agency profits.',
    'Open interactive live Q&A session with senior mentors.',
  ];
});

const sessionAgenda = computed(() => {
  const dbAgenda = themeStore.locale === 'bn'
    ? (currentWebinar.value?.agenda_bn || currentWebinar.value?.agenda_en)
    : (currentWebinar.value?.agenda_en || currentWebinar.value?.agenda_bn);

  if (Array.isArray(dbAgenda) && dbAgenda.length > 0) {
    return dbAgenda;
  }

  if (themeStore.locale === 'bn') {
    return [
      {
        title: 'অংশ ১: এভিয়েশন ও ট্রাভেল এজেন্সির বর্তমান গ্লোবাল মার্কেট',
        desc: 'চাহিদাসম্পন্ন স্কিলস ও এয়ারলাইন্স টিকেটিং ক্যারিয়ারের সুযোগসমূহ।',
        time: '১৫ মিনিট',
      },
      {
        title: 'অংশ ২: Sabre ও Galileo সিস্টেমে লাইভ সফটওয়্যার ডেমো',
        desc: 'কমান্ড লাইন, ফ্লাইট সার্চ, সিট বুকিং ও ফেয়ার কোটেশন প্র্যাকটিস।',
        time: '৪৫ মিনিট',
      },
      {
        title: 'অংশ ৩: এম্বাসি ভিসা ফাইলিং ও রিজেকশন এড়ানোর উপায়',
        desc: 'কভার লেটার, ট্রাভেল আইটিনারি ও ব্যাংক স্টেটমেন্ট প্রস্তুতকরণ।',
        time: '১৫ মিনিট',
      },
      {
        title: 'অংশ ৪: উন্মুক্ত প্রশ্নোত্তর পর্ব ও সার্টিফিকেট বিতরণ গাইড',
        desc: 'শিক্ষার্থীদের সরাসরি প্রশ্নের উত্তর ও প্র্যাকটিস শিট অ্যাক্সেস।',
        time: '১৫ মিনিট',
      },
    ];
  }
  return [
    {
      title: 'Part 1: Global Aviation & Travel Industry Outlook',
      desc: 'In-demand competencies and high-growth agency roles.',
      time: '15 Mins',
    },
    {
      title: 'Part 2: Live Sabre & Galileo Software Screen Demo',
      desc: 'Terminal commands, flight availability, and live PNR creation.',
      time: '45 Mins',
    },
    {
      title: 'Part 3: Embassy Visa Dossier Audit & Best Practices',
      desc: 'Cover letter templates, travel plans, and financial compliance.',
      time: '15 Mins',
    },
    {
      title: 'Part 4: Interactive Live Q&A & Certificate Distribution',
      desc: 'Direct participant questions and downloadable practice materials.',
      time: '15 Mins',
    },
  ];
});

const relatedWebinars = computed(() => {
  return relatedWebinarsFromApi.value || [];
});

const formatEventDate = (dateStr: string) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  if (isNaN(date.getTime())) return dateStr;
  
  if (themeStore.locale === 'bn') {
    const months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
    const day = formatNumber(date.getDate(), 'bn');
    const month = months[date.getMonth()];
    const year = formatNumber(date.getFullYear(), 'bn');
    return `${day} ${month}, ${year} | সন্ধ্যা ৭:০০ টা`;
  }
  return date.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' }) + ' at 07:00 PM';
};

const handleRegister = async () => {
  if (!regForm.name || !regForm.email || !regForm.phone) {
    toastStore.warning(themeStore.locale === 'bn' ? 'অনুগ্রহ করে প্রয়োজনীয় তথ্য পূরণ করুন।' : 'Please fill in all required fields.');
    return;
  }

  isSubmitting.value = true;
  try {
    const slug = route.params.slug as string;
    const currentUrl = typeof window !== 'undefined' ? window.location.href : `/webinars/${slug}`;
    const leadType = currentWebinar.value?.is_seminar ? 'seminar' : 'webinar';

    // 1. Register for Webinar ticket
    await apiClient.post(`/public/webinars/${slug}/register`, regForm);

    // 2. Also record exact lead attribution in CRM
    try {
      await apiClient.post('/public/leads', {
        name: regForm.name.trim(),
        email: regForm.email.trim(),
        phone: regForm.phone.trim(),
        whatsapp_number: regForm.phone.trim(),
        lead_type: leadType,
        source_content_type: 'webinar',
        source_content_id: currentWebinar.value?.id,
        source_content_slug: currentWebinar.value?.slug,
        source_url: currentUrl,
        source: 'webinar_live_registration',
      });
    } catch {}

    isRegistered.value = true;
    toastStore.success(themeStore.locale === 'bn' ? 'সিট নিশ্চিত হয়েছে! হোয়াটসঅ্যাপে জুম লিংক পাঠানো হয়েছে।' : 'Seat confirmed! Zoom invite sent.');
  } finally {
    isSubmitting.value = false;
  }
};

const scrollToRegister = () => {
  const target = document.getElementById('webinar-register-card');
  if (target) {
    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
};

const openRecordingModal = () => {
  if (authStore.user) {
    recordingLeadForm.name = authStore.user.name || '';
    recordingLeadForm.phone = authStore.user.phone || '';
  }
  isRecordingModalOpen.value = true;
};

const submitRecordingLead = async () => {
  if (!recordingLeadForm.name || !recordingLeadForm.phone) {
    toastStore.warning(themeStore.locale === 'bn' ? 'অনুগ্রহ করে আপনার নাম ও ফোন নম্বর প্রদান করুন।' : 'Please enter your name and phone number.');
    return;
  }
  isSubmittingRecordingLead.value = true;
  try {
    const finalWhatsApp = recordingLeadForm.sameAsPhone 
      ? recordingLeadForm.phone 
      : (recordingLeadForm.whatsapp_number || recordingLeadForm.phone);

    const currentUrl = typeof window !== 'undefined' ? window.location.href : `/webinars/${currentWebinar.value?.slug}`;
    const leadType = currentWebinar.value?.is_seminar ? 'seminar' : 'webinar';

    await apiClient.post('/public/leads', {
      name: recordingLeadForm.name.trim(),
      phone: recordingLeadForm.phone.trim(),
      whatsapp_number: finalWhatsApp.trim(),
      lead_type: leadType,
      source_content_type: 'webinar',
      source_content_id: currentWebinar.value?.id,
      source_content_slug: currentWebinar.value?.slug,
      source_url: currentUrl,
      source: 'webinar_recording_access',
      notes: `রেকর্ডেড সেমিনার ভিডিও অ্যাক্সেস অনুরোধ | সেশন: ${webinarTitle.value}${recordingLeadForm.notes ? ' | নোট: ' + recordingLeadForm.notes : ''}`,
    });

    recordingUnlocked.value = true;
    toastStore.success(themeStore.locale === 'bn' ? 'ভিডিও সফলভাবে আনলক হয়েছে! উপভোগ করুন।' : 'Video unlocked successfully! Enjoy watching.');
  } catch (err: any) {
    recordingUnlocked.value = true;
    toastStore.success(themeStore.locale === 'bn' ? 'ভিডিওটি লোড করা হয়েছে।' : 'Video recording loaded.');
  } finally {
    isSubmittingRecordingLead.value = false;
  }
};

const copyWebinarLink = async () => {
  try {
    await navigator.clipboard.writeText(window.location.href);
    toastStore.success(themeStore.locale === 'bn' ? 'সেমিনার লিংক কপি হয়েছে!' : 'Webinar link copied to clipboard!');
  } catch {
    toastStore.info(window.location.href);
  }
};

const shareFacebook = () => {
  const url = encodeURIComponent(window.location.href);
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
};

const shareWhatsApp = () => {
  const text = encodeURIComponent(`${webinarTitle.value} - ${window.location.href}`);
  window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
};

const fetchWebinar = async () => {
  loading.value = true;
  try {
    const slug = route.params.slug as string;
    const res = await apiClient.get(`/public/webinars/${slug}`);
    webinar.value = res.data.data.webinar;
    relatedWebinarsFromApi.value = res.data.data.related_webinars || [];
    if (currentWebinar.value) {
      const eventSchema = buildWebinarSchema(currentWebinar.value);
      const breadcrumbSchema = buildBreadcrumbSchema([
        { name: themeStore.locale === 'bn' ? 'হোম' : 'Home', url: '/' },
        { name: themeStore.locale === 'bn' ? 'ওয়েবিনার ও মাস্টারক্লাস' : 'Webinars', url: '/webinars' },
        { name: webinarTitle.value, url: `/webinars/${currentWebinar.value.slug}` },
      ]);

      setMeta({
        title: webinarTitle.value,
        description: webinarSubtitle.value || (themeStore.locale === 'bn' ? currentWebinar.value.description_bn : currentWebinar.value.description_en),
        keywords: `${webinarTitle.value}, free aviation webinar bangladesh, air ticketing masterclass, live workshop`,
        image: currentWebinar.value.thumbnail,
        type: 'event',
        schema: [eventSchema, breadcrumbSchema],
      });
    }
  } catch (err) {
    webinar.value = null;
    relatedWebinarsFromApi.value = [];
  } finally {
    loading.value = false;
  }
};

watch(
  () => route.params.slug,
  () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    isRegistered.value = false;
    fetchWebinar();
  }
);

onMounted(() => {
  fetchWebinar();
});
</script>
