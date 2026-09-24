<template>
  <footer class="bg-[var(--bg-surface)] border-t border-[var(--border-subtle)] text-[var(--text-secondary)] pt-12 sm:pt-16 pb-8 sm:pb-12 transition-colors safe-bottom">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 sm:space-y-12">
      
      <!-- 4 Column Main Footer -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
        
        <!-- Column 1: Brand & Promise -->
        <div class="space-y-4">
          <router-link to="/" class="inline-block py-1 group">
            <img
              :src="isDarkTheme ? '/images/logo-dark.png' : '/images/logo-light.png'"
              alt="Emisha Academy"
              class="h-11 min-[380px]:h-12 sm:h-14 md:h-15 w-auto max-w-[210px] min-[380px]:max-w-[250px] sm:max-w-[290px] object-contain transition-transform group-hover:scale-103"
            />
          </router-link>
          
          <p class="text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed">
            {{ $t('footer.desc') }}
          </p>

          <!-- Live Animated Social & Quick Contact Icons -->
          <div class="flex items-center gap-3 pt-2">
            <!-- Facebook Button -->
            <a
              href="https://www.facebook.com/profile.php?id=61590103572746"
              target="_blank"
              rel="noopener noreferrer"
              class="group relative w-10 h-10 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#1877F2]/60 hover:bg-[#1877F2]/10 flex items-center justify-center text-[var(--text-secondary)] hover:text-[#1877F2] transition-all duration-300 shadow-xs hover:shadow-lg hover:shadow-[#1877F2]/20 hover:-translate-y-1 touch-target cursor-pointer"
              aria-label="Official Facebook Page"
              title="Emisha Academy on Facebook"
            >
              <!-- Live ambient pulse indicator -->
              <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-[#1877F2] opacity-0 group-hover:opacity-100 group-hover:animate-ping transition-opacity"></span>
              <svg class="w-4 h-4 fill-current transition-transform duration-300 group-hover:scale-115" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>

            <!-- Google Map / Campus Location Button -->
            <a
              href="https://maps.app.goo.gl/S8HazBnbGhM6H2xo7"
              target="_blank"
              rel="noopener noreferrer"
              class="group relative w-10 h-10 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-rose-500/60 hover:bg-rose-500/10 flex items-center justify-center text-[var(--text-secondary)] hover:text-rose-500 transition-all duration-300 shadow-xs hover:shadow-lg hover:shadow-rose-500/20 hover:-translate-y-1 touch-target cursor-pointer"
              aria-label="Campus Location on Google Maps"
              title="Mirpur-10 Campus on Google Maps"
            >
              <!-- Live radar ping -->
              <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-rose-500 opacity-0 group-hover:opacity-100 group-hover:animate-ping transition-opacity"></span>
              <svg class="w-4 h-4 fill-current transition-transform duration-300 group-hover:scale-115 group-hover:-translate-y-0.5" viewBox="0 0 24 24">
                <path d="M12 0C7.58 0 4 3.58 4 8c0 5.25 7 13 8 14 1-1 8-8.75 8-14 0-4.42-3.58-8-8-8zm0 11c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/>
              </svg>
            </a>

            <!-- WhatsApp Direct Hotline Button -->
            <a
              href="https://wa.me/8801805464293"
              target="_blank"
              rel="noopener noreferrer"
              class="group relative w-10 h-10 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-emerald-500/60 hover:bg-emerald-500/10 flex items-center justify-center text-[var(--text-secondary)] hover:text-emerald-500 transition-all duration-300 shadow-xs hover:shadow-lg hover:shadow-emerald-500/20 hover:-translate-y-1 touch-target cursor-pointer"
              aria-label="Direct WhatsApp Message"
              title="Chat on WhatsApp (+8801805464293)"
            >
              <!-- Live online beacon -->
              <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-500 opacity-0 group-hover:opacity-100 group-hover:animate-ping transition-opacity"></span>
              <svg class="w-4 h-4 fill-current transition-transform duration-300 group-hover:scale-115" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
              </svg>
            </a>

            <!-- Phone Call Hotline Button -->
            <a
              href="tel:01805464293"
              class="group relative w-10 h-10 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/70 hover:bg-[#D4AF37]/10 flex items-center justify-center text-[var(--text-secondary)] hover:text-[#D4AF37] transition-all duration-300 shadow-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 hover:-translate-y-1 touch-target cursor-pointer"
              aria-label="Direct Phone Hotline Call"
              title="Call Helpline: 01805464293"
            >
              <!-- Live pulse beacon -->
              <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-[#D4AF37] opacity-0 group-hover:opacity-100 group-hover:animate-ping transition-opacity"></span>
              <svg class="w-4 h-4 fill-current transition-transform duration-300 group-hover:scale-115 group-hover:rotate-12" viewBox="0 0 24 24">
                <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Column 2: Quick Links -->
        <div class="space-y-3">
          <h4 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">{{ $t('footer.quick_links') }}</h4>
          <ul class="space-y-2 text-xs sm:text-sm">
            <li><router-link to="/courses" class="hover:text-[var(--brand-gold)] transition-colors py-0.5 inline-block">{{ $t('nav.courses') }}</router-link></li>
            <li><router-link to="/webinars" class="hover:text-[var(--brand-gold)] transition-colors py-0.5 inline-block">{{ $t('nav.webinars') }}</router-link></li>
            <li><router-link to="/ebooks" class="hover:text-[var(--brand-gold)] transition-colors py-0.5 inline-block">{{ $t('nav.ebooks') }}</router-link></li>
            <li><router-link to="/blog" class="hover:text-[var(--brand-gold)] transition-colors py-0.5 inline-block">{{ $t('nav.blog') }}</router-link></li>
            <li><router-link to="/about" class="hover:text-[var(--brand-gold)] transition-colors py-0.5 inline-block">{{ $t('nav.about') }}</router-link></li>
          </ul>
        </div>

        <!-- Column 3: Course Categories -->
        <div class="space-y-3">
          <h4 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">{{ $t('footer.training_courses') }}</h4>
          <ul class="space-y-2 text-xs sm:text-sm">
            <li><router-link to="/courses?category=air-ticketing-aviation" class="hover:text-[var(--brand-gold)] transition-colors py-0.5 inline-block">{{ themeStore.locale === 'bn' ? 'এয়ার টিকেটিং ও এভিয়েশন (Sabre/Galileo)' : 'Air Ticketing & Aviation (Sabre/Galileo)' }}</router-link></li>
            <li><router-link to="/courses?category=visa-processing-tourism" class="hover:text-[var(--brand-gold)] transition-colors py-0.5 inline-block">{{ themeStore.locale === 'bn' ? 'ভিসা প্রসেসিং ও গ্লোবাল ট্যুরিজম' : 'Visa Processing & Global Tourism' }}</router-link></li>
            <li><router-link to="/courses?category=graphic-design" class="hover:text-[var(--brand-gold)] transition-colors py-0.5 inline-block">{{ themeStore.locale === 'bn' ? 'গ্রাফিক ডিজাইন (আসন্ন)' : 'Graphic Design (Upcoming)' }}</router-link></li>
            <li><router-link to="/courses?category=digital-marketing" class="hover:text-[var(--brand-gold)] transition-colors py-0.5 inline-block">{{ themeStore.locale === 'bn' ? 'ডিজিটাল মার্কেটিং (আসন্ন)' : 'Digital Marketing (Upcoming)' }}</router-link></li>
          </ul>
        </div>

        <!-- Column 4: Contact & Hotline -->
        <div class="space-y-3">
          <h4 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">{{ $t('footer.contact_title') }}</h4>
          <div class="space-y-3 text-xs sm:text-sm">
            <!-- Location Pin Item -->
            <div class="flex items-start gap-3 group">
              <div class="w-7 h-7 rounded-lg bg-[var(--brand-gold-subtle)] text-[#D4AF37] border border-[var(--border-accent)] flex items-center justify-center shrink-0 mt-0.5 shadow-xs group-hover:border-[#D4AF37] group-hover:scale-105 transition-all">
                <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c-4.418-4.418-8-8.582-8-12a8 8 0 1116 0c0 3.418-3.582 7.582-8 12z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a3 3 0 100-6 3 3 0 000 6z"/>
                </svg>
              </div>
              <span class="leading-relaxed">{{ $t('footer.address_line1') }}, {{ $t('footer.address_line2') }} ({{ $t('footer.metro_station') }})</span>
            </div>

            <!-- Phone Hotline Item -->
            <div class="flex items-center gap-3 group">
              <div class="w-7 h-7 rounded-lg bg-[var(--brand-gold-subtle)] text-[#D4AF37] border border-[var(--border-accent)] flex items-center justify-center shrink-0 shadow-xs group-hover:border-[#D4AF37] group-hover:scale-105 transition-all">
                <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
              </div>
              <a href="tel:01805464293" class="hover:text-[var(--brand-gold)] font-bold transition-colors">01805464293</a>
            </div>

            <!-- Office Hours Item -->
            <div class="flex items-start gap-3 group">
              <div class="w-7 h-7 rounded-lg bg-[var(--brand-gold-subtle)] text-[#D4AF37] border border-[var(--border-accent)] flex items-center justify-center shrink-0 mt-0.5 shadow-xs group-hover:border-[#D4AF37] group-hover:scale-105 transition-all">
                <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2">
                  <circle cx="12" cy="12" r="10"/>
                  <polyline points="12 6 12 12 16 14"/>
                </svg>
              </div>
              <span class="leading-relaxed">{{ $t('footer.office_hours') }}</span>
            </div>

            <!-- Email Item -->
            <div class="flex items-center gap-3 group">
              <div class="w-7 h-7 rounded-lg bg-[var(--brand-gold-subtle)] text-[#D4AF37] border border-[var(--border-accent)] flex items-center justify-center shrink-0 shadow-xs group-hover:border-[#D4AF37] group-hover:scale-105 transition-all">
                <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <a href="mailto:info@emisha.academy" class="break-all hover:text-[var(--brand-gold)] transition-colors">info@emisha.academy</a>
            </div>
          </div>
        </div>

      </div>

      <!-- Bottom Divider & Copyright -->
      <div class="pt-6 sm:pt-8 border-t border-[var(--border-subtle)] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[var(--text-muted)] text-center sm:text-left">
        <p>{{ $t('footer.copyright') }}</p>
        <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6">
          <a href="https://www.facebook.com/profile.php?id=61590103572746" target="_blank" rel="noopener noreferrer" class="hover:text-[var(--brand-gold)] transition-colors">{{ $t('footer.facebook') }}</a>
          <a href="https://maps.app.goo.gl/yVBQmUfCS57Esag76" target="_blank" rel="noopener noreferrer" class="hover:text-[var(--brand-gold)] transition-colors">{{ $t('footer.google_map') }}</a>
          <router-link to="/contact" class="hover:text-[var(--brand-gold)] transition-colors">{{ $t('nav.contact') }}</router-link>
        </div>
      </div>

    </div>
  </footer>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useThemeStore } from '../../stores/theme';

const themeStore = useThemeStore();

const isDarkTheme = computed(() => themeStore.isDark);
</script>
