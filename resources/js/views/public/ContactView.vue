<template>
  <div class="py-8 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 sm:space-y-16">
    
    <!-- 1. Atmospheric Hero Header -->
    <div class="text-center space-y-3 sm:space-y-4 max-w-3xl mx-auto">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-xs font-black uppercase tracking-wider shadow-xs">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#D4AF37] opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-[#D4AF37]"></span>
        </span>
        <span>{{ themeStore.locale === 'bn' ? 'সার্বক্ষণিক যোগাযোগ ও ক্যারিয়ার কাউন্সেলিং' : 'Always Here to Help & Guide' }}</span>
      </div>

      <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[var(--text-primary)] tracking-tight leading-tight">
        {{ themeStore.locale === 'bn' ? 'আমাদের ক্যাম্পাসে সরাসরি আসুন অথবা যোগাযোগ করুন' : 'Visit Our Campus or Connect with Us' }}
      </h1>

      <p class="text-xs sm:text-sm lg:text-base text-[var(--text-secondary)] leading-relaxed max-w-2xl mx-auto">
        {{ themeStore.locale === 'bn' 
          ? 'মিরপুর কাজীপাড়া মেট্রো স্টেশনের পাশেই অবস্থিত আমাদের আধুনিক কম্পিউটার ল্যাব। এয়ার টিকেটিং, ভিসা প্রসেসিং ও ক্যারিয়ার সংক্রান্ত যেকোনো তথ্যের জন্য আমাদের টিম সর্বদা প্রস্তুত।' 
          : 'Located right next to Kazipara Metro Station, Mirpur. Our admissions and career counseling specialists are ready to help you take the next step.' }}
      </p>

      <!-- Trust Metrics Strip -->
      <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 pt-2">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)] shadow-xs">
          <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'কাজীপাড়া মেট্রো স্টেশন (পিলার ২৬৭)' : 'Kazipara Metro (Pillar #267)' }}</span>
        </div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)] shadow-xs">
          <span class="text-emerald-500">●</span>
          <span>{{ themeStore.locale === 'bn' ? 'প্রতিদিন খোলা: সকাল ১০:০০ টা – রাত ৬:৩০ টা' : 'Open Daily: 10:00 AM – 6:30 PM' }}</span>
        </div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)] shadow-xs">
          <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'ফ্রি কম্পিউটার ল্যাব ট্যুর সুবিধা' : 'Free Lab Tour & Demo' }}</span>
        </div>
      </div>
    </div>

    <!-- 2. Core 2-Column Interactive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-10 items-start">
      
      <!-- Left: Contact Channels & Campus Logistics (5 cols) -->
      <div class="lg:col-span-5 space-y-4 sm:space-y-5">
        
        <!-- Card 1: Mirpur Campus & Lab Location -->
        <div class="group p-6 sm:p-7 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden space-y-4">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                <circle cx="12" cy="9" r="2.5"/>
              </svg>
            </div>

            <div class="space-y-1 min-w-0 flex-1">
              <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#D4AF37]">
                {{ themeStore.locale === 'bn' ? 'প্রধান ক্যাম্পাস ও প্র্যাকটিক্যাল ল্যাব' : 'Main Campus & Computer Lab' }}
              </span>
              <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] leading-snug">
                {{ themeStore.locale === 'bn' ? 'মিরপুর কাজীপাড়া ক্যাম্পাস' : 'Mirpur Kazipara Campus' }}
              </h3>
            </div>
          </div>

          <div class="space-y-2 text-xs text-[var(--text-secondary)] leading-relaxed pt-1">
            <p class="font-medium text-[var(--text-primary)]">
              {{ $t('footer.address_line1') }}, {{ $t('footer.address_line2') }}
            </p>
            <div class="p-2.5 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] flex items-center gap-2 text-[#D4AF37] font-bold">
              <svg class="w-4 h-4 text-[#D4AF37] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="16" x="4" y="3" rx="2"/><path d="M4 11h16"/><path d="M12 3v8"/><path d="m8 19-2 3"/><path d="m18 22-2-3"/><circle cx="8" cy="15" r="1"/><circle cx="16" cy="15" r="1"/></svg>
              <span>{{ $t('footer.metro_station') }} — মেট্রো থেকে মাত্র ৩০ সেকেন্ড!</span>
            </div>
          </div>

          <div class="pt-2 border-t border-[var(--border-subtle)] flex items-center justify-between">
            <a
              href="https://maps.app.goo.gl/S8HazBnbGhM6H2xo7"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-1.5 text-xs font-extrabold text-[#D4AF37] hover:underline"
            >
              <span>{{ themeStore.locale === 'bn' ? 'গুগল ম্যাপে লোকেশন দেখুন' : 'View on Google Maps' }}</span>
              <span>↗</span>
            </a>
            <span class="text-[10px] text-[var(--text-muted)]">Pillar #267</span>
          </div>
        </div>

        <!-- Card 2: Direct Helpline & Hotline -->
        <div class="group p-6 sm:p-7 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-emerald-500/50 shadow-sm hover:shadow-xl transition-all duration-300 space-y-4">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
              </svg>
            </div>

            <div class="space-y-1 min-w-0 flex-1">
              <div class="flex items-center gap-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                  {{ themeStore.locale === 'bn' ? 'সরাসরি হটলাইন ও অ্যাডমিশন' : 'Direct Helpline & Admissions' }}
                </span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
              </div>
              <h3 class="text-lg sm:text-xl font-black text-[var(--text-primary)]">
                <a href="tel:01805464293" class="hover:text-[#D4AF37] transition-colors">01805464293</a>
              </h3>
            </div>
          </div>

          <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
            {{ themeStore.locale === 'bn' ? 'কোর্স ফি, ডিসকাউন্ট অফার ও ব্যাচ শিডিউল জানতে সরাসরি আমাদের সিনিয়র কাউন্সিলরের সাথে কথা বলুন।' : 'Speak directly with our senior course counselors for fee structure, batch schedules, and current offers.' }}
          </p>

          <div class="pt-2 border-t border-[var(--border-subtle)] flex items-center justify-between gap-3">
            <a
              href="https://wa.me/8801805464293"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-black transition-all"
            >
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.04 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 14.99 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.34C9.36 7.34 9.09 7.4 8.87 7.65C8.65 7.89 8.02 8.48 8.02 9.7C8.02 10.92 8.91 12.09 9.03 12.26C9.16 12.42 10.74 14.86 13.17 15.91C13.75 16.16 14.2 16.31 14.55 16.42C15.13 16.61 15.66 16.58 16.08 16.52C16.55 16.45 17.52 15.93 17.72 15.36C17.93 14.79 17.93 14.3 17.87 14.2C17.8 14.1 17.65 14.04 17.43 13.93C17.2 13.82 16.12 13.28 15.92 13.21C15.71 13.13 15.56 13.1 15.41 13.33C15.26 13.56 14.83 14.07 14.7 14.22C14.57 14.37 14.45 14.39 14.22 14.28C14 14.17 13.06 13.86 11.94 12.86C11.07 12.08 10.48 11.12 10.31 10.83C10.14 10.54 10.29 10.38 10.41 10.27C10.51 10.16 10.63 10 10.75 9.87C10.87 9.73 10.91 9.63 10.99 9.47C11.07 9.3 11.03 9.16 10.97 9.04C10.91 8.93 10.46 7.82 10.28 7.37C10.09 6.94 9.9 7 9.76 7C9.62 7 9.47 7 9.53 7.34Z"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'হোয়াটসঅ্যাপে চ্যাট' : 'WhatsApp Chat' }}</span>
            </a>

            <span class="text-[11px] text-[var(--text-muted)] font-medium">
              {{ themeStore.locale === 'bn' ? 'প্রতিদিন: সকাল ১০:০০ টা – রাত ৬:৩০ টা' : 'Daily: 10:00 AM – 6:30 PM' }}
            </span>
          </div>
        </div>

        <!-- Card 3: Email & Social Connection -->
        <div class="group p-6 sm:p-7 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-blue-500/50 shadow-sm hover:shadow-xl transition-all duration-300 space-y-4">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-500 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </div>

            <div class="space-y-1 min-w-0 flex-1">
              <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-500">
                {{ themeStore.locale === 'bn' ? 'অফিশিয়াল ইমেইল ও সোশ্যাল পেজ' : 'Email & Social Connect' }}
              </span>
              <h3 class="text-sm sm:text-base font-black text-[var(--text-primary)]">
                <a href="mailto:info@emisha.academy" class="hover:text-[#D4AF37] transition-colors">info@emisha.academy</a>
              </h3>
            </div>
          </div>

          <div class="flex items-center justify-between pt-2 border-t border-[var(--border-subtle)]">
            <a
              href="https://www.facebook.com/profile.php?id=61590103572746"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-1.5 text-xs font-bold text-[#1877F2] hover:underline"
            >
              <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95C18.05 21.45 22 17.19 22 12Z"/></svg>
              <span>facebook.com/emishaacademy</span>
            </a>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold inline-flex items-center gap-1">
              <svg class="w-3 h-3 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              <span>২ ঘণ্টার মধ্যে রিপ্লাই</span>
            </span>
          </div>
        </div>

        <!-- Card 4: Free Lab Tour Promise -->
        <div class="p-5 rounded-2xl bg-gradient-to-r from-amber-500/10 via-[var(--bg-elevated)] to-teal-500/10 border border-[var(--border-accent)] text-xs text-[var(--text-secondary)] space-y-1.5">
          <div class="flex items-center gap-1.5 font-bold text-[var(--text-primary)]">
            <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/><path d="M8 14h.01"/><path d="M16 14h.01"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'সরাসরি ফ্রি ল্যাব ভিজিট' : 'Direct Free Lab Visit' }}</span>
          </div>
          <p class="text-[11px] leading-relaxed">
            {{ themeStore.locale === 'bn' 
              ? 'ভর্তির পূর্বে আমাদের প্র্যাকটিক্যাল কম্পিউটার ল্যাব ও ট্রেইনারদের সাথে সরাসরি কথা বলতে যেকোনো দিন চলে আসুন (প্রতিদিন সকাল ১০:০০ টা থেকে রাত ৬:৩০ টা পর্যন্ত খোলা)।' 
              : 'Feel free to walk into our campus to explore our dedicated workstation lab before enrolling (Open daily from 10:00 AM to 6:30 PM).' }}
          </p>
        </div>

      </div>

      <!-- Right: High-End Inquiry & Counseling Booking Form (7 cols) -->
      <div class="lg:col-span-7 p-6 sm:p-8 lg:p-10 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/40 shadow-xl space-y-6 sm:space-y-8 relative overflow-hidden backdrop-blur-sm">
        
        <!-- Top Gold Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#D4AF37] via-amber-400 to-[#F7E7A9]"></div>

        <!-- Form Header -->
        <div class="space-y-2 pt-1">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-[11px] font-extrabold uppercase tracking-wider">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'ইনকোয়ারি ও কাউন্সেলিং ফর্ম' : 'Inquiry & Counseling Form' }}</span>
          </div>

          <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-[var(--text-primary)] tracking-tight">
            {{ themeStore.locale === 'bn' ? 'সরাসরি বার্তা পাঠান বা ফ্রি কাউন্সেলিং বুক করুন' : 'Send an Inquiry or Book Free Counseling' }}
          </h2>

          <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
            {{ themeStore.locale === 'bn' 
              ? 'ফর্মটি পূরণ করে আপনার প্রশ্ন বা আগ্রহ জানান। আমাদের সিনিয়র ক্যারিয়ার কনসালট্যান্ট দ্রুত আপনার সাথে যোগাযোগ করবেন।' 
              : 'Submit your query below. Our senior admissions counselor will get in touch with you shortly.' }}
          </p>
        </div>

        <!-- Quick Subject / Interest Selector Pills -->
        <div class="space-y-2">
          <label class="text-xs font-bold text-[var(--text-primary)] block">
            {{ themeStore.locale === 'bn' ? 'আগ্রহের বিষয় / সার্ভিস নির্বাচন করুন:' : 'Select Area of Interest:' }}
          </label>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="pill in interestPills"
              :key="pill.id"
              type="button"
              @click="form.subject = pill.value"
              :class="form.subject === pill.value
                ? 'bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black shadow-sm'
                : 'bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] border border-[var(--border-subtle)] font-medium'"
              class="px-3 py-1.5 rounded-xl text-xs transition-all touch-target cursor-pointer flex items-center gap-1.5"
            >
              <span>{{ themeStore.locale === 'bn' ? pill.label_bn : pill.label_en }}</span>
            </button>
          </div>
        </div>

        <!-- Form Elements -->
        <form @submit.prevent="submitContact" class="space-y-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'আপনার পূর্ণ নাম *' : 'Your Full Name *' }}
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                :placeholder="themeStore.locale === 'bn' ? 'যেমন: তানভীর আহমেদ' : 'e.g. Tanvir Ahmed'"
                class="w-full px-4 py-3 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] transition-all shadow-xs"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'মোবাইল / হোয়াটসঅ্যাপ নম্বর *' : 'Phone / WhatsApp *' }}
              </label>
              <input
                v-model="form.phone"
                type="tel"
                required
                placeholder="018XXXXXXXX"
                class="w-full px-4 py-3 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] transition-all shadow-xs"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'ইমেইল ঠিকানা *' : 'Email Address *' }}
              </label>
              <input
                v-model="form.email"
                type="email"
                required
                placeholder="name@example.com"
                class="w-full px-4 py-3 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] transition-all shadow-xs"
              />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'বিষয় / কোর্স *' : 'Subject / Course *' }}
              </label>
              <input
                v-model="form.subject"
                type="text"
                required
                :placeholder="themeStore.locale === 'bn' ? 'যেমন: এয়ার টিকেটিং ও ভিসা প্রসেসিং' : 'e.g. Air Ticketing & Visa Course'"
                class="w-full px-4 py-3 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] transition-all shadow-xs"
              />
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'আপনার বার্তা বা বিস্তারিত প্রশ্ন *' : 'Your Message / Detailed Query *' }}
            </label>
            <textarea
              v-model="form.message"
              rows="4"
              required
              :placeholder="themeStore.locale === 'bn' ? 'ব্যাচ শিডিউল, অফলাইন ল্যাব প্র্যাকটিস বা ভর্তি সংক্রান্ত আপনার প্রশ্ন বিস্তারিত লিখুন...' : 'Write your question regarding admission, batch schedule, or lab tour in detail...'"
              class="w-full px-4 py-3 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] transition-all leading-relaxed resize-none shadow-xs"
            ></textarea>
          </div>

          <!-- Submit Button & Security Note -->
          <div class="space-y-3 pt-2">
            <button
              type="submit"
              :disabled="isSubmitting"
              class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 font-black text-sm sm:text-base hover:shadow-xl hover:shadow-[#D4AF37]/30 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2 cursor-pointer touch-target shadow-md disabled:opacity-50"
            >
              <span v-if="isSubmitting">{{ themeStore.locale === 'bn' ? 'বার্তা পাঠানো হচ্ছে...' : 'Sending Message...' }}</span>
              <template v-else>
                <span>{{ themeStore.locale === 'bn' ? 'মেসেজ পাঠান ও কাউন্সেলিং বুক করুন' : 'Send Message & Book Counseling' }}</span>
                <span class="text-lg">→</span>
              </template>
            </button>

            <div class="flex items-center justify-center gap-2 text-[11px] text-[var(--text-muted)] text-center">
              <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'আপনার তথ্য সম্পূর্ণ গোপন ও সুরক্ষিত থাকবে। কোনো স্প্যাম পাঠানো হয় না।' : 'Your information is 100% secure. Zero spam guarantee.' }}</span>
            </div>
          </div>
        </form>

      </div>

    </div>

    <!-- 3. Interactive Embedded Google Map Location Section -->
    <section class="space-y-4">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="space-y-1">
          <div class="inline-flex items-center gap-2 text-xs font-black text-[#D4AF37] uppercase tracking-wider">
            <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-pulse"></span>
            <span>{{ themeStore.locale === 'bn' ? 'ক্যাম্পাস লোকেশন ও ইন্টারেক্টিভ ম্যাপ' : 'Interactive Campus Map Location' }}</span>
          </div>
          <h2 class="text-xl sm:text-2xl font-black text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'গুগল ম্যাপে আমাদের ক্যাম্পাস ও অফিসের অবস্থান' : 'Find Our Campus on Google Maps' }}
          </h2>
          <p class="text-xs text-[var(--text-secondary)]">
            {{ themeStore.locale === 'bn' 
              ? 'গুগল ম্যাপে "Emisha Tours & Travels" নামে আমাদের অফিসটি তালিকাভুক্ত রয়েছে — একই ক্যাম্পাসে ইমিশা একাডেমি ও কম্পিউটার ল্যাব অবস্থিত।' 
              : 'Our verified location on Google Maps is listed as "Emisha Tours & Travels" — both Emisha Academy and the training lab share this exact same campus.' }}
          </p>
        </div>

        <div class="flex items-center gap-2 shrink-0 self-start sm:self-auto flex-wrap">
          <a
            href="https://www.google.com/maps/dir/?api=1&destination=23.8019604,90.370698"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-2xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-accent)] text-xs font-bold text-[#D4AF37] hover:scale-105 transition-all shadow-xs"
          >
            <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'দিকনির্দেশনা (Directions)' : 'Get Directions' }}</span>
          </a>

          <a
            href="https://maps.app.goo.gl/S8HazBnbGhM6H2xo7"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black text-xs hover:brightness-110 active:scale-95 transition-all shadow-md shadow-[#D4AF37]/20 cursor-pointer"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'গুগল ম্যাপে খুলুন' : 'Open Google Maps' }} ↗</span>
          </a>
        </div>
      </div>

      <!-- Map Container with Glassmorphism Overlay Info Badge -->
      <div class="relative rounded-3xl overflow-hidden border border-[var(--border-subtle)] shadow-xl bg-[var(--bg-surface)] h-[400px] sm:h-[480px] group">
        <!-- Google Maps Iframe -->
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.46775369794!2d90.37069799999999!3d23.801960400000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c1e168fbd84f%3A0xa8712af0cfdc4cff!2sEmisha%20Tours%20%26%20Travels!5e0!3m2!1sen!2sbd!4v1790064809636!5m2!1sen!2sbd"
          class="w-full h-full border-0"
          loading="lazy"
          allowfullscreen=""
          referrerpolicy="no-referrer-when-downgrade"
          title="Emisha Academy & Emisha Tours & Travels Google Map Location"
        ></iframe>

        <!-- Floating Campus Info Card -->
        <div class="absolute top-4 left-4 right-4 sm:right-auto sm:max-w-sm p-4.5 rounded-2xl bg-[var(--bg-surface)]/95 backdrop-blur-xl border border-[var(--border-accent)] shadow-2xl space-y-2.5 pointer-events-auto">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#D4AF37] flex items-center justify-center font-black text-sm shrink-0 mt-0.5">
              <svg class="w-5 h-5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
            </div>
            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-1.5 flex-wrap">
                <h4 class="text-sm font-black text-[var(--text-primary)]">
                  {{ themeStore.locale === 'bn' ? 'ইমিশা একাডেমি' : 'Emisha Academy' }}
                </h4>
                <span class="text-[10px] px-1.5 py-0.5 rounded bg-[var(--brand-gold-subtle)] text-[#D4AF37] font-bold">
                  {{ themeStore.locale === 'bn' ? 'একই অফিস' : 'Same Office' }}
                </span>
              </div>
              <p class="text-[11px] text-[#D4AF37] font-bold">
                {{ themeStore.locale === 'bn' ? 'গুগল ম্যাপে: Emisha Tours & Travels' : 'Google Maps: Emisha Tours & Travels' }}
              </p>
            </div>
          </div>

          <div class="p-2.5 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[11px] text-[var(--text-secondary)] space-y-1">
            <p class="font-medium text-[var(--text-primary)]">
              {{ $t('footer.address_line1') }}, {{ $t('footer.address_line2') }}
            </p>
            <p class="text-[10px] text-[#D4AF37] font-bold flex items-center gap-1">
              <span>📍</span>
              <span>মেট্রো পিলার ২৬৭ এর ঠিক বিপরীতে (ইউনিয়ন ব্যাংক বিল্ডিং, ৬ষ্ঠ তলা)</span>
            </p>
          </div>

          <div class="pt-2 border-t border-[var(--border-subtle)] flex items-center justify-between text-[10px]">
            <span class="text-emerald-500 font-bold flex items-center gap-1">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
              {{ themeStore.locale === 'bn' ? 'সকাল ১০:০০ টা – রাত ৬:৩০ টা' : '10:00 AM – 6:30 PM' }}
            </span>
            <a
              href="https://maps.app.goo.gl/S8HazBnbGhM6H2xo7"
              target="_blank"
              rel="noopener noreferrer"
              class="font-black text-[#D4AF37] hover:underline flex items-center gap-1"
            >
              <span>{{ themeStore.locale === 'bn' ? 'ম্যাপে ওপেন করুন' : 'Open in Maps' }}</span>
              <span>↗</span>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. Bottom Metro Route Guide & Directions Banner -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-[var(--bg-surface)] via-[var(--bg-elevated)] to-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-sm space-y-6">
      
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[var(--border-subtle)]">
        <div class="space-y-1">
          <div class="inline-flex items-center gap-1.5 text-xs font-bold text-[#D4AF37] uppercase tracking-wider">
            <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="16" x="4" y="3" rx="2"/><path d="M4 11h16"/><path d="M12 3v8"/><path d="m8 19-2 3"/><path d="m18 22-2-3"/><circle cx="8" cy="15" r="1"/><circle cx="16" cy="15" r="1"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'মেট্রোরেল ও সহজ যাতায়াত নির্দেশিকা' : 'Metro Rail & Transit Directions' }}</span>
          </div>
          <h3 class="text-base sm:text-xl font-black text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'ঢাকা শহরের যেকোনো প্রান্ত থেকে সহজে আসার নিয়ম' : 'How to Reach Emisha Academy Campus Easily' }}
          </h3>
        </div>

        <a
          href="https://maps.app.goo.gl/S8HazBnbGhM6H2xo7"
          target="_blank"
          rel="noopener noreferrer"
          class="px-4 py-2 rounded-xl bg-[var(--bg-deep)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-bold text-[var(--text-primary)] hover:text-[#D4AF37] transition-all flex items-center gap-2 self-start sm:self-auto shrink-0 shadow-xs"
        >
          <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'লাইভ গুগল ম্যাপ ন্যাভিগেশন' : 'Live Google Map Navigation' }}</span>
        </a>
      </div>

      <!-- 3 Steps Strip -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
        <div class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-2">
          <div class="w-8 h-8 rounded-xl bg-[#D4AF37]/15 text-[#D4AF37] flex items-center justify-center font-black text-xs">
            01
          </div>
          <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'কাজীপাড়া মেট্রো স্টেশনে নামুন' : 'Get Down at Kazipara Metro' }}
          </h4>
          <p class="text-[11px] text-[var(--text-secondary)] leading-relaxed">
            {{ themeStore.locale === 'bn' ? 'উত্তরা, মতিঝিল বা মিরপুর ১০ থেকে মেট্রোরেলে কাজীপাড়া স্টেশনে পৌঁছান।' : 'Take the MRT Line-6 from Uttara, Motijheel, or Farmgate to Kazipara station.' }}
          </p>
        </div>

        <div class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-2">
          <div class="w-8 h-8 rounded-xl bg-[#D4AF37]/15 text-[#D4AF37] flex items-center justify-center font-black text-xs">
            02
          </div>
          <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'পিলার নং ২৬৭ লক্ষ্য করুন' : 'Locate Metro Pillar #267' }}
          </h4>
          <p class="text-[11px] text-[var(--text-secondary)] leading-relaxed">
            {{ themeStore.locale === 'bn' ? 'স্টেশন থেকে বের হয়ে ঠিক রাস্তার বিপরীতে ইউনিয়ন ব্যাংক বিল্ডিংটি দেখতে পাবেন।' : 'Exit the station towards East Monipur. The Union Bank Building is directly opposite Pillar #267.' }}
          </p>
        </div>

        <div class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-2">
          <div class="w-8 h-8 rounded-xl bg-[#D4AF37]/15 text-[#D4AF37] flex items-center justify-center font-black text-xs">
            03
          </div>
          <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? '৬ষ্ঠ তলায় ইমিশা একাডেমি' : 'Emisha Academy on 6th Floor' }}
          </h4>
          <p class="text-[11px] text-[var(--text-secondary)] leading-relaxed">
            {{ themeStore.locale === 'bn' ? 'লিফটে ৬ষ্ঠ তলায় উঠে আমাদের ফ্রন্ট ডেস্কে আসুন এবং কম্পিউটার ল্যাব ঘুরে দেখুন।' : 'Take the elevator to the 6th floor where our reception and live computer workstations are situated.' }}
          </p>
        </div>
      </div>

    </div>

  </div>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted, watch } from 'vue';
import apiClient from '../../api/client';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import { useSeo } from '../../composables/useSeo';

const toastStore = useToastStore();
const themeStore = useThemeStore();
const { setMeta, buildOrganizationSchema, buildBreadcrumbSchema } = useSeo();
const isSubmitting = ref(false);

const interestPills = [
  { id: 'ticketing', value: 'এয়ার টিকেটিং ও Sabre/Galileo GDS কোর্স', label_bn: 'এয়ার টিকেটিং ও GDS', label_en: 'Air Ticketing & GDS' },
  { id: 'visa', value: 'গ্লোবাল ট্যুরিস্ট ভিসা প্রসেসিং কোর্স', label_bn: 'ভিসা প্রসেসিং', label_en: 'Visa Processing' },
  { id: 'tour', value: 'ক্যাম্পাস ও কম্পিউটার ল্যাব ভিজিট বুকিং', label_bn: 'ল্যাব ভিজিট বুকিং', label_en: 'Lab Tour Booking' },
  { id: 'umrah', value: 'ওমরাহ পোর্টাল ও হোটেল রিজার্ভেশন কোর্স', label_bn: 'ওমরাহ ও ট্যুরিজম', label_en: 'Umrah & Tourism' },
  { id: 'general', value: 'সাধারণ ক্যারিয়ার কাউন্সেলিং ও জিজ্ঞাসা', label_bn: 'সাধারণ জিজ্ঞাসা', label_en: 'General Inquiry' },
];

const form = reactive({
  name: '',
  email: '',
  phone: '',
  subject: 'এয়ার টিকেটিং ও Sabre/Galileo GDS কোর্স',
  message: '',
});

const updateContactSeo = () => {
  const isBn = themeStore.locale === 'bn';
  const orgSchema = buildOrganizationSchema();
  const breadcrumbSchema = buildBreadcrumbSchema([
    { name: isBn ? 'হোম' : 'Home', url: '/' },
    { name: isBn ? 'যোগাযোগ' : 'Contact Us', url: '/contact' },
  ]);

  setMeta({
    title: isBn ? 'যোগাযোগ ও মিরপুর ক্যাম্পাস লোকেশন' : 'Contact & Mirpur Campus Location',
    description: isBn
      ? 'মিরপুর-১০ কাজীপাড়া মেট্রো স্টেশন (পিলার ২৬৭) সংলগ্ন আধুনিক কম্পিউটার ল্যাবে সরাসরি আসুন অথবা হটলাইনে যোগাযোগ করুন: ০১৮০৫৪৬৪২৯৩।'
      : 'Visit our modern aviation lab in Mirpur, Dhaka (near Kazipara Metro Pillar #267) or call our admissions hotline at +8801805464293.',
    keywords: 'contact emisha academy, aviation academy address dhaka, mirpur kazipara computer lab, emisha phone number',
    type: 'website',
    schema: [orgSchema, breadcrumbSchema],
  });
};

watch(
  () => themeStore.locale,
  () => {
    updateContactSeo();
  }
);

onMounted(() => {
  updateContactSeo();
});

const submitContact = async () => {
  if (!form.name || !form.email || !form.phone || !form.subject || !form.message) {
    toastStore.warning(themeStore.locale === 'bn' ? 'অনুগ্রহ করে প্রয়োজনীয় সকল তথ্য পূরণ করুন।' : 'Please fill in all required fields.');
    return;
  }

  isSubmitting.value = true;
  try {
    const res = await apiClient.post('/public/contact', form);
    toastStore.success(res.data.message || (themeStore.locale === 'bn' ? 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে। আমাদের টিম দ্রুত যোগাযোগ করবে।' : 'Message sent successfully. We will reach out shortly.'));
    form.name = '';
    form.email = '';
    form.phone = '';
    form.message = '';
  } catch (err: any) {
    toastStore.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'বার্তা পাঠানো সম্ভব হয়নি। অনুগ্রহ করে পুনরায় চেষ্টা করুন।' : 'Failed to send message.'));
  } finally {
    isSubmitting.value = false;
  }
};
</script>
