import { defineStore } from 'pinia';
import i18n from '../locales/i18n';

export type ThemeMode = 'light' | 'dark' | 'system';

export const useThemeStore = defineStore('theme', {
  state: () => ({
    mode: ((typeof window !== 'undefined' ? localStorage.getItem('emisha_theme') : null) || 'light') as ThemeMode,
    isDark: typeof window !== 'undefined' ? localStorage.getItem('emisha_theme') === 'dark' : false,
    locale: ((typeof window !== 'undefined' ? localStorage.getItem('emisha_locale') : null) || 'bn') as 'bn' | 'en',
    systemListenerAttached: false,
  }),

  actions: {
    initTheme() {
      const savedTheme = localStorage.getItem('emisha_theme') as ThemeMode | null;
      if (savedTheme && ['light', 'dark', 'system'].includes(savedTheme)) {
        this.mode = savedTheme;
      } else {
        // Default to light theme
        this.mode = 'light';
      }

      this.resolveAndApply();
      this.attachSystemListener();
      this.initLocale();
    },

    setTheme(newMode: ThemeMode) {
      this.mode = newMode;
      localStorage.setItem('emisha_theme', newMode);
      this.resolveAndApply();
    },

    toggleTheme() {
      // Toggle between light and dark if user clicks simple icon
      const nextMode: ThemeMode = this.isDark ? 'light' : 'dark';
      this.setTheme(nextMode);
    },

    resolveAndApply() {
      const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      
      if (this.mode === 'system') {
        this.isDark = prefersDark;
      } else {
        this.isDark = this.mode === 'dark';
      }

      const root = document.documentElement;
      if (this.isDark) {
        root.classList.add('dark');
        root.classList.remove('light');
        root.setAttribute('data-theme', 'dark');
      } else {
        root.classList.remove('dark');
        root.classList.add('light');
        root.setAttribute('data-theme', 'light');
      }

      // Update browser theme-color meta tag
      let metaThemeColor = document.querySelector('meta[name="theme-color"]');
      if (!metaThemeColor) {
        metaThemeColor = document.createElement('meta');
        metaThemeColor.setAttribute('name', 'theme-color');
        document.head.appendChild(metaThemeColor);
      }
      metaThemeColor.setAttribute('content', this.isDark ? '#080D1A' : '#FFFFFF');
    },

    attachSystemListener() {
      if (this.systemListenerAttached || typeof window === 'undefined') return;

      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
      const listener = () => {
        if (this.mode === 'system') {
          this.resolveAndApply();
        }
      };

      if (mediaQuery.addEventListener) {
        mediaQuery.addEventListener('change', listener);
      } else if (mediaQuery.addListener) {
        mediaQuery.addListener(listener);
      }

      this.systemListenerAttached = true;
    },

    initLocale() {
      const savedLocale = (localStorage.getItem('emisha_locale') || 'bn') as 'bn' | 'en';
      this.setLocale(savedLocale);
    },

    setLocale(newLocale: 'bn' | 'en') {
      this.locale = newLocale;
      if (typeof window !== 'undefined') {
        localStorage.setItem('emisha_locale', newLocale);
      }
      try {
        if (i18n && i18n.global) {
          if ('value' in i18n.global.locale) {
            (i18n.global.locale as any).value = newLocale;
          } else {
            (i18n.global as any).locale = newLocale;
          }
        }
      } catch (e) {
        // Ignore locale switch error
      }
      if (typeof document !== 'undefined' && document.body) {
        document.body.style.fontFamily = newLocale === 'bn' ? 'var(--font-bangla)' : 'var(--font-english)';
      }
    },
  },
});
