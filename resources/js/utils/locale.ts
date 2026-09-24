/**
 * Centralized Locale & Formatting Utilities for Emisha Academy
 */

const banglaDigits: { [key: string]: string } = {
  '0': '০',
  '1': '১',
  '2': '২',
  '3': '৩',
  '4': '৪',
  '5': '৫',
  '6': '৬',
  '7': '৭',
  '8': '৮',
  '9': '৯',
};

export function toBanglaNumber(num: number | string): string {
  return String(num).replace(/[0-9]/g, (w) => banglaDigits[w] || w);
}

export function formatCurrency(amount: number | string | undefined | null, locale = 'bn'): string {
  const num = Number(amount || 0);
  if (locale === 'bn') {
    const formatted = num.toLocaleString('en-US');
    return `৳${toBanglaNumber(formatted)}`;
  }
  return `৳${num.toLocaleString('en-US')}`;
}

export function formatNumber(num: number | string | undefined | null, locale = 'bn'): string {
  const n = Number(num || 0);
  if (locale === 'bn') {
    return toBanglaNumber(n.toLocaleString('en-US'));
  }
  return n.toLocaleString('en-US');
}

export function formatDate(dateStr: string | Date | undefined | null, locale = 'bn'): string {
  if (!dateStr) return '';
  const date = typeof dateStr === 'string' ? new Date(dateStr) : dateStr;
  
  if (locale === 'bn') {
    return date.toLocaleDateString('bn-BD', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    });
  }
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}

export function getLocalized(item: any, field: string, locale = 'bn'): string {
  if (!item) return '';
  if (locale === 'bn') {
    return item[`${field}_bn`] || item[`${field}_en`] || item[field] || '';
  }
  return item[`${field}_en`] || item[`${field}_bn`] || item[field] || '';
}
