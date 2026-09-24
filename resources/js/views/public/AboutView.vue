<template>
  <div class="py-8 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-16 sm:space-y-24">
    
    <!-- 1. Atmospheric Hero Header -->
    <div class="text-center space-y-4 sm:space-y-5 max-w-4xl mx-auto">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-xs font-black uppercase tracking-wider shadow-xs">
        <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.3c.4-.2.6-.6.5-1.1z"/>
        </svg>
        <span>{{ themeStore.locale === 'bn' ? 'আমাদের পরিচিতি ও রূপকল্প' : 'About Emisha Academy' }}</span>
      </div>

      <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-[var(--text-primary)] tracking-tight leading-tight">
        {{ themeStore.locale === 'bn' 
          ? 'এভিয়েশন ও ট্রাভেল এজেন্সিতে ক্যারিয়ার গড়ার শীর্ষ প্র্যাকটিক্যাল ট্রেনিং ইনস্টিটিউট' 
          : 'Pioneering Hands-on Aviation & Travel Career Training in Bangladesh' }}
      </h1>

      <p class="text-xs sm:text-base lg:text-lg text-[var(--text-secondary)] leading-relaxed max-w-3xl mx-auto">
        {{ themeStore.locale === 'bn'
          ? 'ইমিশা ট্যুরস অ্যান্ড ট্রাভেলসের বাস্তব অভিজ্ঞতার আলোকে প্রতিষ্ঠিত — যেখানে তাত্ত্বিক লেকচারের চেয়ে প্রত্যেক শিক্ষার্থীর জন্য আলাদা কম্পিউটার ল্যাব ও লাইভ Sabre/Galileo GDS সফটওয়্যারে কাজ শেখানোকে সর্বোচ্চ অগ্রাধিকার দেওয়া হয়।'
          : 'Built on years of real travel agency leadership by Emisha Tours & Travels. We replace passive slide lectures with dedicated computer workstations and live Sabre & Galileo GDS software practice.' }}
      </p>

      <!-- Key Credibility Stats Strip -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 pt-4 max-w-3xl mx-auto">
        <div class="p-3.5 sm:p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-center shadow-xs">
          <div class="text-xl sm:text-2xl font-black text-[var(--text-primary)]">৫০০+</div>
          <p class="text-[11px] text-[var(--text-muted)] font-medium mt-0.5">{{ themeStore.locale === 'bn' ? 'সফল গ্র্যাজুয়েট' : 'Certified Alumni' }}</p>
        </div>

        <div class="p-3.5 sm:p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-center shadow-xs">
          <div class="text-xl sm:text-2xl font-black text-[#D4AF37]">১ : ১</div>
          <p class="text-[11px] text-[var(--text-muted)] font-medium mt-0.5">{{ themeStore.locale === 'bn' ? 'আলাদা পিসি ল্যাব' : 'Dedicated PC Ratio' }}</p>
        </div>

        <div class="p-3.5 sm:p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-center shadow-xs">
          <div class="flex items-center justify-center gap-1 text-xl sm:text-2xl font-black text-amber-400">
            <svg class="w-4 h-4 fill-amber-400 text-amber-400" viewBox="0 0 24 24" aria-hidden="true">
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            <span>৪.৯৬</span>
          </div>
          <p class="text-[11px] text-[var(--text-muted)] font-medium mt-0.5">{{ themeStore.locale === 'bn' ? 'গড় রেটিং' : 'Average Rating' }}</p>
        </div>

        <div class="p-3.5 sm:p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-center shadow-xs">
          <div class="text-xl sm:text-2xl font-black text-emerald-500">১০০%</div>
          <p class="text-[11px] text-[var(--text-muted)] font-medium mt-0.5">{{ themeStore.locale === 'bn' ? 'হাতে-কলমে ল্যাব' : 'Hands-on Lab' }}</p>
        </div>
      </div>
    </div>

    <!-- 2. The Emisha Story & Real Agency Heritage -->
    <div class="p-6 sm:p-10 lg:p-12 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xl relative overflow-hidden">
      <!-- Ambient subtle background glow -->
      <div class="absolute -top-32 -right-32 w-80 h-80 bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center relative z-10">
        
        <!-- Story Text (7 cols) -->
        <div class="lg:col-span-7 space-y-4 sm:space-y-5">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-[11px] font-extrabold uppercase tracking-wider">
            <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="3" y1="22" x2="21" y2="22"></line>
              <line x1="6" y1="18" x2="6" y2="11"></line>
              <line x1="10" y1="18" x2="10" y2="11"></line>
              <line x1="14" y1="18" x2="14" y2="11"></line>
              <line x1="18" y1="18" x2="18" y2="11"></line>
              <polygon points="12 2 20 7 4 7"></polygon>
            </svg>
            <span>{{ themeStore.locale === 'bn' ? 'আমাদের গল্প ও পটভূমি' : 'Our Story & Heritage' }}</span>
          </div>

          <h2 class="text-xl sm:text-3xl font-black text-[var(--text-primary)] tracking-tight leading-snug">
            {{ themeStore.locale === 'bn'
              ? 'কেন আমরা সাধারণ ট্র্যাডিশনাল ক্লাসরুমের পরিবর্তে ডেডিকেটেড কম্পিউটার ল্যাব তৈরি করেছি?'
              : 'Why We Replaced Traditional Classrooms with Real Computer Workstations' }}
          </h2>

          <div class="space-y-3.5 text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed sm:leading-loose">
            <p>
              {{ themeStore.locale === 'bn'
                ? 'বাংলাদেশের এয়ার টিকেটিং ও ভিসা প্রসেসিং ইন্ডাস্ট্রিতে হাজারো শিক্ষার্থী কোর্স শেষ করেও বাস্তব কাজের সময় আটকে যান, কারণ বেশিরভাগ ট্রেনিং সেন্টারে কেবল প্রজেক্টরে স্লাইড দেখানো হয়—সরাসরি সফটওয়্যার ব্যবহারের সুযোগ থাকে না।'
                : 'In Bangladesh, thousands of aviation trainees struggle on the job because most training centers rely merely on projector slides without letting students use real GDS terminals.' }}
            </p>
            <p>
              {{ themeStore.locale === 'bn'
                ? 'এই সমস্যার সমাধানে ইমিশা একাডেমি মিরপুর ক্যাম্পাসে প্রতিটি শিক্ষার্থীর জন্য নিজস্ব কম্পিউটার টার্মিনাল নিশ্চিত করেছে। এখানে আপনি Sabre ও Galileo সিস্টেমে সরাসরি লাইভ পিএনআর ক্রিয়েশন, ফেয়ার রুলস এবং শেঞ্জেন/ইউএসএ এম্বাসি ফাইল প্রস্তুত করতে পারেন।'
                : 'To solve this critical gap, Emisha Academy established individual computer workstations at our Mirpur campus. Students directly book live airline PNRs on Sabre/Galileo and prepare genuine tourist visa dossiers.' }}
            </p>
          </div>

          <div class="pt-2 flex flex-wrap items-center gap-3">
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)]">
              <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>{{ themeStore.locale === 'bn' ? 'ইমিশা ট্যুরস অ্যান্ড ট্রাভেলস দ্বারা পরিচালিত' : 'Backed by Emisha Tours & Travels' }}</span>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)]">
              <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>{{ themeStore.locale === 'bn' ? 'কাজীপাড়া মেট্রো স্টেশনের পাশে' : 'Next to Kazipara Metro' }}</span>
            </div>
          </div>
        </div>

        <!-- Visual Highlights Container (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
          <div class="p-6 rounded-3xl bg-[var(--bg-deep)] border border-[var(--border-accent)] space-y-4 shadow-md">
            <span class="text-xs font-black text-[#D4AF37] uppercase tracking-wider flex items-center gap-2">
              <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/>
                <path d="M9 18h6"/>
                <path d="M10 22h4"/>
              </svg>
              <span>{{ themeStore.locale === 'bn' ? 'আমাদের শিক্ষাদান পদ্ধতি' : 'Our Teaching Methodology' }}</span>
            </span>

            <div class="space-y-3">
              <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/40 transition-colors">
                <div class="w-9 h-9 rounded-xl bg-[#D4AF37]/10 text-[#D4AF37] flex items-center justify-center shrink-0 border border-[#D4AF37]/20">
                  <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect width="20" height="14" x="2" y="3" rx="2"/>
                    <line x1="8" x2="16" y1="21" y2="21"/>
                    <line x1="12" x2="12" y1="17" y2="21"/>
                  </svg>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-[var(--text-primary)]">{{ themeStore.locale === 'bn' ? '১ শিক্ষার্থী = ১টি কম্পিউটার' : '1 Student = 1 Computer' }}</h4>
                  <p class="text-[11px] text-[var(--text-muted)] mt-0.5 leading-snug">{{ themeStore.locale === 'bn' ? 'শেয়ারিং ছাড়া সম্পূর্ণ নিজস্ব পিসিতে অনুশীলন' : 'No screen sharing, individual hands-on practice' }}</p>
                </div>
              </div>

              <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-sky-500/40 transition-colors">
                <div class="w-9 h-9 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center shrink-0 border border-sky-500/20">
                  <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.3c.4-.2.6-.6.5-1.1z"/>
                  </svg>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-[var(--text-primary)]">{{ themeStore.locale === 'bn' ? 'লাইভ GDS সফটওয়্যার সাইন-ইন' : 'Live GDS Software Sign-in' }}</h4>
                  <p class="text-[11px] text-[var(--text-muted)] mt-0.5 leading-snug">{{ themeStore.locale === 'bn' ? 'Sabre ও Galileo সিস্টেমে রিয়েল টিকিট ইস্যু' : 'Real ticket issuance on Sabre & Galileo' }}</p>
                </div>
              </div>

              <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-emerald-500/40 transition-colors">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/20">
                  <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                    <path d="M3 3v5h5"/>
                    <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                    <path d="M16 21h5v-5"/>
                  </svg>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-[var(--text-primary)]">{{ themeStore.locale === 'bn' ? 'আনলিমিটেড ল্যাব প্র্যাকটিস' : 'Unlimited Lab Access' }}</h4>
                  <p class="text-[11px] text-[var(--text-muted)] mt-0.5 leading-snug">{{ themeStore.locale === 'bn' ? 'ক্লাসের বাইরেও যেকোনো দিন ল্যাবে বসে অনুশীলনের সুযোগ' : 'Practice in our lab anytime even after class' }}</p>
                </div>
              </div>

              <div class="flex items-start gap-3.5 p-3.5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/40 transition-colors">
                <div class="w-9 h-9 rounded-xl bg-[#D4AF37]/10 text-[#D4AF37] flex items-center justify-center shrink-0 border border-[#D4AF37]/20">
                  <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v18M3 12h18M5.6 5.6l12.8 12.8M18.4 5.6 5.6 18.4"/>
                  </svg>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-[var(--text-primary)]">{{ themeStore.locale === 'bn' ? 'AC ক্লাসরুম ও ট্রেইনার গাইডেন্স' : 'AC Classroom & Practice Guidance' }}</h4>
                  <p class="text-[11px] text-[var(--text-muted)] mt-0.5 leading-snug">{{ themeStore.locale === 'bn' ? 'শীতাতপ নিয়ন্ত্রিত ল্যাব ও প্র্যাকটিসে সার্বক্ষণিক ইন্সট্রাক্টর সাপোর্ট' : 'Air-conditioned lab with dedicated instructor practice support' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- 3. Mission, Vision, Core Values & Career Placement (Redesigned Creative Minimal Architectural Cards) -->
    <div class="space-y-10 sm:space-y-12">
      <div class="text-center space-y-3 max-w-2xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-xs font-black uppercase tracking-wider shadow-xs">
          <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/>
            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
          </svg>
          <span>{{ themeStore.locale === 'bn' ? 'আমাদের মূল স্তম্ভ' : 'Our Core Foundations' }}</span>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-[var(--text-primary)] tracking-tight">
          {{ themeStore.locale === 'bn' ? 'মিশন, ভিশন ও প্রাতিষ্ঠানিক মূল স্তম্ভ' : 'Mission, Vision & Core Values' }}
        </h2>
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
          {{ themeStore.locale === 'bn' 
            ? 'দক্ষ এভিয়েশন মানবসম্পদ তৈরি এবং ক্যারিয়ার বিনির্মাণে আমাদের সুনির্দিষ্ট রূপকল্প ও অঙ্গীকার।' 
            : 'Our guiding principles, strategic direction, and uncompromising commitment to student career success.' }}
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
        
        <!-- 01. Mission -->
        <div class="group relative p-6 sm:p-7 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/60 shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
          <!-- Ambient card backdrop accent glow -->
          <div class="absolute -top-12 -right-12 w-28 h-28 bg-[#D4AF37]/10 rounded-full blur-2xl group-hover:bg-[#D4AF37]/20 transition-all pointer-events-none"></div>
          
          <div class="space-y-4 relative z-10">
            <!-- Top bar: Number badge + Icon -->
            <div class="flex items-center justify-between">
              <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[10px] font-black uppercase tracking-wider text-[#D4AF37]">
                01. {{ themeStore.locale === 'bn' ? 'মিশন' : 'Mission' }}
              </span>
              <div class="w-11 h-11 rounded-2xl bg-[#D4AF37]/10 border border-[#D4AF37]/25 text-[#D4AF37] flex items-center justify-center group-hover:scale-110 group-hover:bg-[#D4AF37] group-hover:text-slate-950 transition-all duration-300 shadow-sm">
                <!-- Target Icon -->
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="12" cy="12" r="10"/>
                  <circle cx="12" cy="12" r="6"/>
                  <circle cx="12" cy="12" r="2"/>
                </svg>
              </div>
            </div>

            <!-- Content -->
            <div class="space-y-2 pt-1">
              <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors">
                {{ themeStore.locale === 'bn' ? 'আমাদের মিশন' : 'Our Mission' }}
              </h3>
              <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                {{ themeStore.locale === 'bn' 
                  ? 'বাংলাদেশের যুবসমাজকে বিশ্বমানের এয়ার টিকেটিং, ভিসা প্রসেসিং এবং ট্যুরিজম অপারেশন্স স্কিলে দক্ষ করে দেশ ও বিদেশের জব মার্কেটে স্বাবলম্বী করা।' 
                  : 'To empower youth with world-class air ticketing, visa processing, and tourism operations skills for local and global job markets.' }}
              </p>
            </div>
          </div>

          <!-- Bottom micro-indicator -->
          <div class="pt-5 mt-4 border-t border-[var(--border-subtle)] flex items-center justify-between text-[11px] font-bold text-[var(--text-muted)]">
            <span class="text-[#D4AF37]">{{ themeStore.locale === 'bn' ? 'গ্লোবাল স্কিল ডেভেলপমেন্ট' : 'Global Skill Building' }}</span>
            <svg class="w-3.5 h-3.5 text-[#D4AF37] group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </div>
        </div>

        <!-- 02. Vision -->
        <div class="group relative p-6 sm:p-7 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-sky-500/60 shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
          <div class="absolute -top-12 -right-12 w-28 h-28 bg-sky-500/10 rounded-full blur-2xl group-hover:bg-sky-500/20 transition-all pointer-events-none"></div>

          <div class="space-y-4 relative z-10">
            <!-- Top bar -->
            <div class="flex items-center justify-between">
              <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[10px] font-black uppercase tracking-wider text-sky-400">
                02. {{ themeStore.locale === 'bn' ? 'ভিশন' : 'Vision' }}
              </span>
              <div class="w-11 h-11 rounded-2xl bg-sky-500/10 border border-sky-500/25 text-sky-400 flex items-center justify-center group-hover:scale-110 group-hover:bg-sky-500 group-hover:text-slate-950 transition-all duration-300 shadow-sm">
                <!-- Vision / Eye Horizon Icon -->
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </div>
            </div>

            <!-- Content -->
            <div class="space-y-2 pt-1">
              <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] group-hover:text-sky-400 transition-colors">
                {{ themeStore.locale === 'bn' ? 'আমাদের ভিশন' : 'Our Vision' }}
              </h3>
              <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                {{ themeStore.locale === 'bn'
                  ? 'ব্যবহারিক ও কর্মমুখী প্রশিক্ষণের মাধ্যমে দেশের শীর্ষস্থানীয় এভিয়েশন ট্রেনিং একাডেমি হিসেবে প্রতিষ্ঠিত হওয়া।'
                  : 'To become the nation\'s premier aviation training academy through practical, job-oriented excellence.' }}
              </p>
            </div>
          </div>

          <!-- Bottom micro-indicator -->
          <div class="pt-5 mt-4 border-t border-[var(--border-subtle)] flex items-center justify-between text-[11px] font-bold text-[var(--text-muted)]">
            <span class="text-sky-400">{{ themeStore.locale === 'bn' ? 'ইন্ডাস্ট্রি লিডারশিপ' : 'Industry Leadership' }}</span>
            <svg class="w-3.5 h-3.5 text-sky-400 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </div>
        </div>

        <!-- 03. Core Values -->
        <div class="group relative p-6 sm:p-7 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-emerald-500/60 shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
          <div class="absolute -top-12 -right-12 w-28 h-28 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all pointer-events-none"></div>

          <div class="space-y-4 relative z-10">
            <!-- Top bar -->
            <div class="flex items-center justify-between">
              <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[10px] font-black uppercase tracking-wider text-emerald-500">
                03. {{ themeStore.locale === 'bn' ? 'মূলনীতি' : 'Values' }}
              </span>
              <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-slate-950 transition-all duration-300 shadow-sm">
                <!-- Shield Check / Integrity Icon -->
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                  <polyline points="9 12 11 14 15 10"/>
                </svg>
              </div>
            </div>

            <!-- Content -->
            <div class="space-y-2 pt-1">
              <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] group-hover:text-emerald-400 transition-colors">
                {{ themeStore.locale === 'bn' ? 'প্রাতিষ্ঠানিক মূল্যবোধ' : 'Core Values' }}
              </h3>
              <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                {{ themeStore.locale === 'bn'
                  ? '১০০% বাস্তবমুখী কাজের সততা ও স্বচ্ছতা, শিক্ষার্থীদের প্রতি ব্যক্তিগত মেন্টরশিপ এবং রিয়েল-টাইম এভিয়েশন সফটওয়্যারে নিখুঁত দক্ষতা।'
                  : 'Uncompromising integrity, individual student mentorship, and real-time aviation software competency.' }}
              </p>
            </div>
          </div>

          <!-- Bottom micro-indicator -->
          <div class="pt-5 mt-4 border-t border-[var(--border-subtle)] flex items-center justify-between text-[11px] font-bold text-[var(--text-muted)]">
            <span class="text-emerald-400">{{ themeStore.locale === 'bn' ? 'সততা ও মেন্টরিং' : 'Integrity & Mentorship' }}</span>
            <svg class="w-3.5 h-3.5 text-emerald-400 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </div>
        </div>

        <!-- 04. Career Placement -->
        <div class="group relative p-6 sm:p-7 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-purple-500/60 shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
          <div class="absolute -top-12 -right-12 w-28 h-28 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all pointer-events-none"></div>

          <div class="space-y-4 relative z-10">
            <!-- Top bar -->
            <div class="flex items-center justify-between">
              <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[10px] font-black uppercase tracking-wider text-purple-400">
                04. {{ themeStore.locale === 'bn' ? 'প্লেসমেন্ট' : 'Placement' }}
              </span>
              <div class="w-11 h-11 rounded-2xl bg-purple-500/10 border border-purple-500/25 text-purple-400 flex items-center justify-center group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300 shadow-sm">
                <!-- Briefcase / Career Network Icon -->
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                  <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
              </div>
            </div>

            <!-- Content -->
            <div class="space-y-2 pt-1">
              <h3 class="text-base sm:text-lg font-black text-[var(--text-primary)] group-hover:text-purple-400 transition-colors">
                {{ themeStore.locale === 'bn' ? 'ক্যারিয়ার প্লেসমেন্ট' : 'Career Placement' }}
              </h3>
              <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                {{ themeStore.locale === 'bn'
                  ? 'কোর্স সমাপ্তির পর প্রফেশনাল সিভি তৈরি, মক ইন্টারভিউ কোচিং এবং দেশব্যাপী স্বনামধন্য ট্রাভেল এজেন্সিতে রেফারেল নেটওয়ার্ক সহায়তা।'
                  : 'Dedicated CV building, mock interview coaching, and agency referral network across Bangladesh.' }}
              </p>
            </div>
          </div>

          <!-- Bottom micro-indicator -->
          <div class="pt-5 mt-4 border-t border-[var(--border-subtle)] flex items-center justify-between text-[11px] font-bold text-[var(--text-muted)]">
            <span class="text-purple-400">{{ themeStore.locale === 'bn' ? 'এজেন্সি রেফারেল' : 'Agency Referral Network' }}</span>
            <svg class="w-3.5 h-3.5 text-purple-400 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </div>
        </div>

      </div>
    </div>

    <!-- 4. Why Choose Us: Our Core Training Specializations (Redesigned Lucrative Minimal UI) -->
    <div class="space-y-10 sm:space-y-12">
      <div class="text-center space-y-3 max-w-2xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-xs font-black uppercase tracking-wider shadow-xs">
          <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
          <span>{{ themeStore.locale === 'bn' ? 'কেন আমরা অন্যদের চেয়ে আলাদা' : 'Why Choose Us' }}</span>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-[var(--text-primary)] tracking-tight">
          {{ themeStore.locale === 'bn' ? 'আমাদের প্রধান স্পেশালাইজেশন ও ল্যাব সুবিধা' : 'Our Core Training Specializations' }}
        </h2>
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
          {{ themeStore.locale === 'bn'
            ? 'সরাসরি প্র্যাকটিক্যাল দক্ষতা অর্জনে আমাদের আন্তর্জাতিক মানের আধুনিক অবকাঠামো ও সফটওয়্যার সুবিধা।'
            : 'Enterprise-grade training infrastructure designed to turn passionate beginners into job-ready aviation professionals.' }}
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
        
        <!-- Feature 1: State-of-the-Art Computer Lab -->
        <div class="group p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 shadow-sm hover:shadow-2xl transition-all duration-300 space-y-6 relative overflow-hidden flex flex-col justify-between">
          <div class="absolute -top-16 -right-16 w-36 h-36 bg-[#D4AF37]/10 rounded-full blur-2xl group-hover:bg-[#D4AF37]/20 transition-all pointer-events-none"></div>

          <div class="space-y-4 relative z-10">
            <!-- Icon Box -->
            <div class="w-14 h-14 rounded-2xl bg-[#D4AF37]/10 border border-[#D4AF37]/25 text-[#D4AF37] flex items-center justify-center group-hover:scale-105 group-hover:bg-[#D4AF37] group-hover:text-slate-950 transition-all duration-300 shadow-sm">
              <!-- Monitor / Workstation Vector -->
              <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect width="20" height="14" x="2" y="3" rx="2"/>
                <line x1="8" x2="16" y1="21" y2="21"/>
                <line x1="12" x2="12" y1="17" y2="21"/>
              </svg>
            </div>

            <div class="space-y-2">
              <span class="text-[10px] font-black uppercase tracking-wider text-[#D4AF37]">
                {{ themeStore.locale === 'bn' ? '১:১ ল্যাব অবকাঠামো' : 'Dedicated Workstations' }}
              </span>
              <h3 class="text-lg sm:text-xl font-black text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors">
                {{ themeStore.locale === 'bn' ? 'আধুনিক কম্পিউটার ল্যাব' : 'State-of-the-Art Computer Lab' }}
              </h3>
              <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
                {{ themeStore.locale === 'bn' 
                  ? 'প্রতিটি শিক্ষার্থীর জন্য রয়েছে নিজস্ব ডেডিকেটেড কম্পিউটার টার্মিনাল, যেখানে অফিশিয়াল Sabre ও Galileo GDS সফটওয়্যারে সরাসরি কাজ শেখানো হয়।' 
                  : 'Every student gets a dedicated workstation with hands-on practice on official Sabre and Galileo GDS software.' }}
              </p>
            </div>
          </div>

          <!-- Feature micro-tags -->
          <div class="pt-4 border-t border-[var(--border-subtle)] flex flex-wrap gap-2 relative z-10">
            <span class="px-2.5 py-1 rounded-lg bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? '১ শিক্ষার্থী = ১ পিসি' : '1:1 Student to PC' }}
            </span>
            <span class="px-2.5 py-1 rounded-lg bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'আনলিমিটেড ল্যাব টাইম' : 'Unlimited Lab Time' }}
            </span>
          </div>
        </div>

        <!-- Feature 2: Live Sabre & Galileo GDS -->
        <div class="group p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-sky-500/50 shadow-sm hover:shadow-2xl transition-all duration-300 space-y-6 relative overflow-hidden flex flex-col justify-between">
          <div class="absolute -top-16 -right-16 w-36 h-36 bg-sky-500/10 rounded-full blur-2xl group-hover:bg-sky-500/20 transition-all pointer-events-none"></div>

          <div class="space-y-4 relative z-10">
            <!-- Icon Box -->
            <div class="w-14 h-14 rounded-2xl bg-sky-500/10 border border-sky-500/25 text-sky-400 flex items-center justify-center group-hover:scale-105 group-hover:bg-sky-500 group-hover:text-slate-950 transition-all duration-300 shadow-sm">
              <!-- Flight Departure / Global GDS Vector -->
              <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.3c.4-.2.6-.6.5-1.1z"/>
              </svg>
            </div>

            <div class="space-y-2">
              <span class="text-[10px] font-black uppercase tracking-wider text-sky-400">
                {{ themeStore.locale === 'bn' ? 'রিয়েল সফটওয়্যার সাইন-ইন' : 'Official Software Sign-in' }}
              </span>
              <h3 class="text-lg sm:text-xl font-black text-[var(--text-primary)] group-hover:text-sky-400 transition-colors">
                {{ themeStore.locale === 'bn' ? 'লাইভ Sabre ও Galileo GDS' : 'Live Sabre & Galileo GDS' }}
              </h3>
              <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
                {{ themeStore.locale === 'bn' 
                  ? 'আন্তর্জাতিক গ্লোবাল ডিস্ট্রিবিউশন সিস্টেমে (Sabre ও Galileo GDS) সরাসরি লাইভ ফ্লাইট সার্চ, ফেয়ার রুলস, টিকিট ইস্যু ও রিফান্ড ক্যালকুলেশন।' 
                  : 'Hands-on live practice on official Global Distribution Systems (Sabre & Galileo GDS) for flight booking, fare calculation, and ticketing.' }}
              </p>
            </div>
          </div>

          <!-- Feature micro-tags -->
          <div class="pt-4 border-t border-[var(--border-subtle)] flex flex-wrap gap-2 relative z-10">
            <span class="px-2.5 py-1 rounded-lg bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'লাইভ PNR তৈরি' : 'Live PNR Creation' }}
            </span>
            <span class="px-2.5 py-1 rounded-lg bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'ফেয়ার ও ট্যাক্স রুলস' : 'Fare & Tax Rules' }}
            </span>
          </div>
        </div>

        <!-- Feature 3: Global Visa Processing -->
        <div class="group p-6 sm:p-8 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-emerald-500/50 shadow-sm hover:shadow-2xl transition-all duration-300 space-y-6 relative overflow-hidden flex flex-col justify-between">
          <div class="absolute -top-16 -right-16 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all pointer-events-none"></div>

          <div class="space-y-4 relative z-10">
            <!-- Icon Box -->
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 flex items-center justify-center group-hover:scale-105 group-hover:bg-emerald-500 group-hover:text-slate-950 transition-all duration-300 shadow-sm">
              <!-- World Globe / Embassy Passport Vector -->
              <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/>
                <line x1="2" x2="22" y1="12" y2="12"/>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
              </svg>
            </div>

            <div class="space-y-2">
              <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                {{ themeStore.locale === 'bn' ? 'আন্তর্জাতিক এম্বাসি স্ট্যান্ডার্ড' : 'International Embassy Standards' }}
              </span>
              <h3 class="text-lg sm:text-xl font-black text-[var(--text-primary)] group-hover:text-emerald-400 transition-colors">
                {{ themeStore.locale === 'bn' ? 'বিশ্বব্যাপী ভিসা প্রসেসিং' : 'Global Visa Processing' }}
              </h3>
              <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
                {{ themeStore.locale === 'bn' 
                  ? 'এশিয়া, শেঞ্জেন, ইউএসএ, কানাডা, ইউকে ও অস্ট্রেলিয়ার এম্বাসি নিয়মাবলী, ফাইল প্রস্তুতকরণ, কভার লেটার ও বুকিং তৈরি হাতে-কলমে শেখানো হয়।' 
                  : 'Practical documentation, cover letter writing, and embassy submission guidelines for Asia, Schengen, USA, Canada, UK, and Australia.' }}
              </p>
            </div>
          </div>

          <!-- Feature micro-tags -->
          <div class="pt-4 border-t border-[var(--border-subtle)] flex flex-wrap gap-2 relative z-10">
            <span class="px-2.5 py-1 rounded-lg bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'শেঞ্জেন ও ইউএসএ ফাইল' : 'Schengen & USA Files' }}
            </span>
            <span class="px-2.5 py-1 rounded-lg bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'কভার লেটার ড্রাফট' : 'Cover Letter Drafting' }}
            </span>
          </div>
        </div>

      </div>
    </div>

    <!-- 5. Mentorship Board & Leadership Showcase (Elevated Design-Centric Realtime Cards) -->
    <div class="space-y-10 sm:space-y-12">
      <div class="text-center space-y-3 max-w-2xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-[#D4AF37] text-xs font-black uppercase tracking-wider shadow-xs">
          <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          <span>{{ themeStore.locale === 'bn' ? 'মেন্টর প্যানেল' : 'Mentorship Board' }}</span>
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-[var(--text-primary)] tracking-tight">
          {{ themeStore.locale === 'bn' ? 'অভিজ্ঞ এভিয়েশন ও ট্রাভেল স্পেশালিস্টবৃন্দ' : 'Experienced Aviation & Travel Specialists' }}
        </h2>
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
          {{ themeStore.locale === 'bn' ? 'যাঁদের প্রত্যক্ষ তত্ত্বাবধানে আপনি আন্তর্জাতিক মানের প্রফেশনাল স্কিল অর্জন করবেন।' : 'Learn under the direct guidance of seasoned airline and travel agency veterans.' }}
        </p>
      </div>

      <!-- Loading Skeleton -->
      <div v-if="loadingMentors" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div v-for="i in 3" :key="i" class="h-96 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
      </div>

      <!-- Real Dynamic Mentors Grid -->
      <div v-else-if="mentors.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div
          v-for="mentor in mentors"
          :key="mentor.id"
          class="group relative rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/60 shadow-md hover:shadow-2xl hover:shadow-[#D4AF37]/10 transition-all duration-300 overflow-hidden flex flex-col justify-between hover:-translate-y-1.5"
        >
          <!-- Top Decorative Header Banner -->
          <div class="h-28 sm:h-32 bg-gradient-to-br from-[#D4AF37]/20 via-[var(--bg-elevated)] to-sky-500/15 relative p-4 flex items-start justify-between border-b border-[var(--border-subtle)] overflow-hidden">
            <!-- Background glow -->
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-[#D4AF37]/20 rounded-full blur-xl pointer-events-none"></div>

            <!-- Top Left: Experience Pill -->
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[var(--bg-surface)]/90 backdrop-blur-md border border-[#D4AF37]/40 text-[#D4AF37] text-[11px] font-black shadow-xs">
              <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] animate-pulse"></span>
              {{ mentor.experience_years ? `${mentor.experience_years} ${themeStore.locale === 'bn' ? 'অভিজ্ঞতা' : 'Experience'}` : (themeStore.locale === 'bn' ? 'অভিজ্ঞ মেন্টর' : 'Experienced Mentor') }}
            </span>

            <!-- Top Right: Rating Pill -->
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-950/90 backdrop-blur-md text-amber-400 border border-amber-500/40 text-[11px] font-black shadow-xs">
              <svg class="w-3.5 h-3.5 fill-amber-400 text-amber-400" viewBox="0 0 24 24" aria-hidden="true">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
              </svg>
              <span>{{ mentor.rating ? parseFloat(mentor.rating).toFixed(2) : '5.00' }} / 5.0</span>
            </span>
          </div>

          <!-- Main Body Content -->
          <div class="px-6 sm:px-7 pb-6 sm:pb-7 pt-0 relative flex flex-col flex-1 justify-between space-y-5">
            
            <!-- Avatar Row with Verified Badge & LinkedIn Button -->
            <div class="flex items-end justify-between -mt-14 sm:-mt-16">
              <div class="relative">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl sm:rounded-3xl p-1 bg-gradient-to-tr from-[#D4AF37] via-amber-200 to-amber-600 shadow-xl group-hover:scale-105 transition-transform duration-300 bg-slate-900">
                  <img
                    :src="mentor.avatar || (mentor.user?.avatar) || getInitialsAvatar(mentor.name_en || mentor.name_bn)"
                    class="w-full h-full rounded-[14px] sm:rounded-[22px] object-cover bg-slate-900"
                    :alt="themeStore.locale === 'bn' ? (mentor.name_bn || mentor.name_en) : (mentor.name_en || mentor.name_bn)"
                    loading="lazy"
                    @error="onImageError($event, 'avatar', mentor.name_en || mentor.name_bn)"
                  />
                </div>
                <!-- Verified Checkmark Badge -->
                <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center border-2 border-[var(--bg-surface)] shadow-md" :title="themeStore.locale === 'bn' ? 'ভেরিফাইড মেন্টর' : 'Verified Mentor'">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                  </svg>
                </span>
              </div>

              <!-- LinkedIn Profile Button -->
              <div class="flex items-center gap-2 pb-1">
                <a
                  v-if="mentor.linkedin_url"
                  :href="mentor.linkedin_url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="w-10 h-10 rounded-2xl bg-blue-600/10 hover:bg-blue-600 border border-blue-600/30 hover:border-blue-600 text-blue-600 hover:text-white flex items-center justify-center transition-all duration-300 shadow-xs group/btn"
                  :title="themeStore.locale === 'bn' ? 'লিংকডইন প্রোফাইল' : 'LinkedIn Profile'"
                >
                  <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.6a1.64 1.64 0 1 0 0 3.28 1.64 1.64 0 0 0 0-3.28Z"/>
                  </svg>
                </a>
              </div>
            </div>

            <!-- Mentor Name, Designation & Organization -->
            <div class="space-y-1.5 text-left">
              <h4 class="text-lg sm:text-xl font-black text-[var(--text-primary)] group-hover:text-[#D4AF37] transition-colors tracking-tight">
                {{ themeStore.locale === 'bn' ? (mentor.name_bn || mentor.name_en) : (mentor.name_en || mentor.name_bn) }}
              </h4>
              <p class="text-xs sm:text-sm font-bold text-[#D4AF37] leading-snug">
                {{ themeStore.locale === 'bn' ? (mentor.title_bn || mentor.title_en) : (mentor.title_en || mentor.title_bn) }}
              </p>
              <div v-if="mentor.organization" class="flex items-center gap-1.5 text-[11px] font-semibold text-[var(--text-muted)] pt-0.5">
                <svg class="w-3.5 h-3.5 text-[var(--text-muted)] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                  <path d="M9 22v-4h6v4"/>
                  <path d="M8 6h.01M16 6h.01M8 10h.01M16 10h.01M8 14h.01M16 14h.01"/>
                </svg>
                <span class="truncate">{{ mentor.organization }}</span>
              </div>
            </div>

            <!-- Structured Bio Box -->
            <div class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs text-[var(--text-secondary)] leading-relaxed text-left relative">
              <p :class="[expandedBio[mentor.id] ? '' : 'line-clamp-4']">
                {{ themeStore.locale === 'bn' 
                  ? (mentor.bio_bn || mentor.bio_en || 'অভিজ্ঞ এভিয়েশন প্রফেশনাল ও সার্টিফাইড ট্রেইনার।') 
                  : (mentor.bio_en || mentor.bio_bn || 'Experienced aviation professional and certified trainer.') }}
              </p>
              <button
                v-if="(themeStore.locale === 'bn' ? (mentor.bio_bn || mentor.bio_en) : (mentor.bio_en || mentor.bio_bn))?.length > 180"
                @click="toggleBio(mentor.id)"
                type="button"
                class="mt-2 text-[11px] font-bold text-[#D4AF37] hover:underline flex items-center gap-1 cursor-pointer transition-colors"
              >
                <span>{{ expandedBio[mentor.id] ? (themeStore.locale === 'bn' ? 'সংক্ষেপ করুন ▲' : 'Show Less ▲') : (themeStore.locale === 'bn' ? 'সম্পূর্ণ পড়ুন ▼' : 'Read Full Bio ▼') }}</span>
              </button>
            </div>

            <!-- Assigned Courses / Specializations -->
            <div class="pt-3 border-t border-[var(--border-subtle)] text-left space-y-2">
              <div class="text-[10px] font-black uppercase tracking-wider text-[var(--text-muted)]">
                {{ themeStore.locale === 'bn' ? 'ট্রেনিং স্পেশালাইজেশন ও কোর্স' : 'Training & Specialization' }}
              </div>
              <div class="flex flex-wrap gap-1.5">
                <template v-if="mentor.assigned_courses && mentor.assigned_courses.length > 0">
                  <router-link
                    v-for="c in mentor.assigned_courses"
                    :key="c.id"
                    :to="`/courses/${c.slug}`"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[var(--brand-gold-subtle)] hover:bg-[#D4AF37]/25 border border-[var(--border-accent)] text-[11px] font-bold text-[#D4AF37] transition-all hover:scale-102 shadow-2xs"
                  >
                    <svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                    </svg>
                    <span>{{ themeStore.locale === 'bn' ? (c.title_bn || c.title_en) : (c.title_en || c.title_bn) }}</span>
                  </router-link>
                </template>
                <template v-else>
                  <router-link
                    to="/courses"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[var(--brand-gold-subtle)] hover:bg-[#D4AF37]/25 border border-[var(--border-accent)] text-[11px] font-bold text-[#D4AF37] transition-all hover:scale-102 shadow-2xs"
                  >
                    <svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'এয়ার টিকেটিং ও ভিসা প্রসেসিং প্রফেশনাল কোর্স' : 'Air Ticketing & Visa Processing Professional Course' }}</span>
                  </router-link>
                </template>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- 6. Bottom Campus Visit & Admission CTA Banner -->
    <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-amber-500/15 via-[var(--bg-elevated)] to-blue-500/15 border border-[var(--border-accent)] shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-8 relative overflow-hidden">
      <!-- Ambient decorative glow -->
      <div class="absolute -bottom-24 -left-24 w-60 h-60 bg-[#D4AF37]/10 rounded-full blur-3xl pointer-events-none"></div>

      <div class="space-y-3 text-center lg:text-left max-w-2xl relative z-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#D4AF37] text-xs font-black uppercase tracking-wider">
          <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>
          <span>{{ themeStore.locale === 'bn' ? 'সরাসরি ক্যাম্পাস ও ল্যাব পরিদর্শন' : 'Walk In for a Free Lab Tour' }}</span>
        </div>
        <h3 class="text-xl sm:text-3xl font-black text-[var(--text-primary)] tracking-tight">
          {{ themeStore.locale === 'bn' ? 'আমাদের মিরপুর ক্যাম্পাসে এসে সরাসরি ল্যাব ঘুরে দেখুন' : 'Visit Our Mirpur Campus to Experience the Lab Firsthand' }}
        </h3>
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
          {{ themeStore.locale === 'bn' 
            ? 'কাজীপাড়া মেট্রো স্টেশনের ঠিক বিপরীতে পিলার নং ২৬৭-এ আমাদের আধুনিক কম্পিউটার ল্যাব ও ট্রেইনারদের সাথে সরাসরি পরামর্শের জন্য প্রতিদিন সকাল ১০:০০ টা থেকে রাত ৬:৩০ টার মধ্যে চলে আসুন।' 
            : 'Opposite Kazipara Metro Station Pillar #267. Meet our senior instructors and explore your dedicated workstation (Open daily: 10:00 AM – 6:30 PM).' }}
        </p>
      </div>

      <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0 relative z-10 w-full lg:w-auto">
        <router-link
          to="/courses"
          class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 font-black text-xs sm:text-sm hover:shadow-xl hover:shadow-[#D4AF37]/30 hover:scale-[1.02] active:scale-[0.98] transition-all text-center touch-target shadow-md flex items-center justify-center gap-2"
        >
          <span>{{ themeStore.locale === 'bn' ? 'সকল কোর্সসমূহ দেখুন' : 'Explore All Courses' }}</span>
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </router-link>

        <router-link
          to="/contact"
          class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs sm:text-sm font-bold text-[var(--text-primary)] hover:text-[#D4AF37] transition-all text-center touch-target shadow-xs flex items-center justify-center gap-2"
        >
          <span>{{ themeStore.locale === 'bn' ? 'লোকেশন ও যোগাযোগ' : 'Location & Contact' }}</span>
          <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>
        </router-link>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useSeo } from '../../composables/useSeo';
import { onImageError, getInitialsAvatar } from '../../utils/imageFallback';

const themeStore = useThemeStore();
const { setMeta, buildOrganizationSchema, buildBreadcrumbSchema } = useSeo();

const loadingMentors = ref(true);
const mentors = ref<any[]>([]);
const expandedBio = ref<Record<number, boolean>>({});

const toggleBio = (id: number) => {
  expandedBio.value[id] = !expandedBio.value[id];
};

const fetchMentors = async () => {
  loadingMentors.value = true;
  try {
    const res = await apiClient.get('/public/instructors');
    mentors.value = res.data.data.instructors || [];
  } catch (err) {
    console.error(err);
    mentors.value = [];
  } finally {
    loadingMentors.value = false;
  }
};

const updateAboutSeo = () => {
  const isBn = themeStore.locale === 'bn';
  const orgSchema = buildOrganizationSchema();
  const breadcrumbSchema = buildBreadcrumbSchema([
    { name: isBn ? 'হোম' : 'Home', url: '/' },
    { name: isBn ? 'আমাদের সম্পর্কে' : 'About Us', url: '/about' },
  ]);

  setMeta({
    title: isBn ? 'আমাদের সম্পর্কে ও রূপকল্প' : 'About Us & Mission',
    description: isBn
      ? 'ইমিশা একাডেমি — আধুনিক কম্পিউটার ল্যাব ও অভিজ্ঞ এভিয়েশন ফ্যাকাল্টির তত্ত্বাবধানে Sabre/Galileo GDS ও ভিসা প্রসেসিং ট্রেনিং।'
      : 'Learn about Emisha Academy, our dedicated workstation labs, and industry mentors pioneering practical aviation training in Dhaka, Bangladesh.',
    keywords: 'about emisha academy, aviation academy mirpur, travel training institute dhaka, sabre gds instructors',
    type: 'website',
    schema: [orgSchema, breadcrumbSchema],
  });
};

watch(
  () => themeStore.locale,
  () => {
    updateAboutSeo();
  }
);

onMounted(() => {
  updateAboutSeo();
  fetchMentors();
});
</script>
