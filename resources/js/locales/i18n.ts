import { createI18n } from 'vue-i18n';
import bn from './bn.json';
import en from './en.json';

const savedLocale = localStorage.getItem('emisha_locale') || 'bn';

export const i18n = createI18n({
  legacy: false,
  locale: savedLocale,
  fallbackLocale: 'bn',
  messages: {
    bn,
    en,
  },
});

export default i18n;
