<template>
  <AppModal
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    :title="themeStore.locale === 'bn' ? 'ফ্রি ই-বুক ডাউনলোড করুন' : 'Download Free Ebook'"
    size="md"
  >
    <div v-if="ebook" class="space-y-5 p-1">
      
      <!-- Top Ebook Mini Showcase Header -->
      <div class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] flex items-center gap-4">
        <!-- Cover Thumbnail -->
        <div class="w-14 h-18 rounded-lg overflow-hidden bg-slate-900 border border-[var(--border-subtle)] shrink-0 shadow-md">
          <img
            :src="ebook.cover_image || getEbookFallbackCover(ebookTitle)"
            :alt="ebookTitle"
            class="w-full h-full object-cover"
            @error="onImageError($event, 'ebook', ebookTitle)"
          />
        </div>

        <div class="min-w-0 flex-1 space-y-1">
          <div class="flex items-center gap-2">
            <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase">
              {{ ebook.is_free ? (themeStore.locale === 'bn' ? '১০০% ফ্রি' : '100% Free') : (themeStore.locale === 'bn' ? 'পিডিএফ গাইড' : 'PDF Guide') }}
            </span>
            <span class="text-[11px] text-[var(--text-muted)] font-medium">
              {{ formatNumber(ebook.pages_count || 95, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'পৃষ্ঠা' : 'Pages' }} • {{ ebook.file_size || '6.8 MB' }}
            </span>
          </div>

          <h4 class="text-xs sm:text-sm font-bold text-[var(--text-primary)] leading-snug line-clamp-2">
            {{ ebookTitle }}
          </h4>
        </div>
      </div>

      <!-- Instruction Note -->
      <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
        {{ themeStore.locale === 'bn'
          ? 'নিচের সংক্ষিপ্ত ফর্মে আপনার নাম ও নম্বর প্রদান করুন। তাৎক্ষণিকভাবে হাই-রেজ্যুলেশন পিডিএফ ডাউনলোড শুরু হবে।'
          : 'Please provide your name and phone number to instantly initiate the high-resolution PDF download.' }}
      </p>

      <!-- Lead Capture Form -->
      <form @submit.prevent="submitEbookLead" class="space-y-4">
        
        <!-- Full Name -->
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-[var(--text-primary)] flex items-center justify-between">
            <span>{{ $t('student.full_name') }} <span class="text-rose-500">*</span></span>
          </label>
          <input
            v-model="form.name"
            type="text"
            required
            :placeholder="themeStore.locale === 'bn' ? 'যেমন: মোঃ তানভীর হাসান' : 'e.g. Tanvir Hasan'"
            class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] transition-colors"
          />
        </div>

        <!-- Phone Number -->
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-[var(--text-primary)] flex items-center justify-between">
            <span>{{ $t('student.phone') }} <span class="text-rose-500">*</span></span>
          </label>
          <input
            v-model="form.phone"
            type="tel"
            required
            placeholder="018XXXXXXXX"
            class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] transition-colors"
          />
        </div>

        <!-- WhatsApp Number & Same as Phone Toggle -->
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <label class="text-xs font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'হোয়াটসঅ্যাপ নম্বর' : 'WhatsApp Number' }}
            </label>
            <label class="flex items-center gap-1.5 text-[11px] text-[var(--text-muted)] cursor-pointer">
              <input
                type="checkbox"
                v-model="form.sameAsPhone"
                class="rounded border-[var(--border-subtle)] text-[#D4AF37] focus:ring-0"
              />
              <span>{{ themeStore.locale === 'bn' ? 'ফোনের মতোই' : 'Same as phone' }}</span>
            </label>
          </div>

          <input
            v-if="!form.sameAsPhone"
            v-model="form.whatsapp_number"
            type="tel"
            :placeholder="form.phone || '018XXXXXXXX'"
            class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-medium text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37] transition-colors"
          />
        </div>

        <!-- Guarantee Strip -->
        <div class="p-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] flex items-center gap-2 text-[11px] text-[var(--text-muted)]">
          <svg class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          <span>{{ themeStore.locale === 'bn' ? '১০০% নিরাপদ ডাউনলোড। আপনার তথ্য সম্পূর্ণ গোপন রাখা হবে।' : '100% secure download. Your contact data remains confidential.' }}</span>
        </div>

        <!-- Submit & Download Button -->
        <div class="flex items-center justify-end gap-3 pt-2 border-t border-[var(--border-subtle)]">
          <button
            type="button"
            @click="$emit('update:modelValue', false)"
            class="px-4 py-2.5 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all cursor-pointer"
          >
            {{ $t('common.cancel') }}
          </button>

          <button
            type="submit"
            :disabled="isSubmitting"
            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black text-xs hover:shadow-lg hover:shadow-[#D4AF37]/30 transition-all cursor-pointer disabled:opacity-50 flex items-center gap-2"
          >
            <span v-if="isSubmitting" class="inline-block animate-spin w-4 h-4 border-2 border-slate-950 border-t-transparent rounded-full"></span>
            <span v-else><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></span>
            <span>{{ isSubmitting ? (themeStore.locale === 'bn' ? 'ডাউনলোড প্রস্তুত হচ্ছে...' : 'Preparing Download...') : (themeStore.locale === 'bn' ? 'পিডিএফ ডাউনলোড শুরু করুন →' : 'Download PDF Now →') }}</span>
          </button>
        </div>

      </form>

    </div>
  </AppModal>
</template>

<script setup lang="ts">
import { reactive, ref, computed, watch } from 'vue';
import AppModal from '../ui/AppModal.vue';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';
import { useAuthStore } from '../../stores/auth';
import { formatNumber } from '../../utils/locale';
import { onImageError, getEbookFallbackCover } from '../../utils/imageFallback';
import apiClient from '../../api/client';

const props = defineProps<{
  modelValue: boolean;
  ebook: any;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void;
  (e: 'download-success', payload: any): void;
}>();

const themeStore = useThemeStore();
const toastStore = useToastStore();
const authStore = useAuthStore();

const isSubmitting = ref(false);

const form = reactive({
  name: '',
  phone: '',
  whatsapp_number: '',
  sameAsPhone: true,
});

// Auto-fill user information if logged in
watch(
  () => props.modelValue,
  (val) => {
    if (val && authStore.user) {
      if (!form.name) form.name = authStore.user.name || '';
      if (!form.phone) form.phone = authStore.user.phone || '';
    }
  }
);

const ebookTitle = computed(() => {
  if (!props.ebook) return '';
  return themeStore.locale === 'bn'
    ? (props.ebook.title_bn || props.ebook.title_en)
    : (props.ebook.title_en || props.ebook.title_bn);
});

const resolveGoogleDriveDownloadUrl = (url: string) => {
  if (!url) return '';
  const trimmed = url.trim();
  const match = trimmed.match(/\/d\/([a-zA-Z0-9_-]+)/) || trimmed.match(/id=([a-zA-Z0-9_-]+)/);
  if (match && match[1]) {
    return `https://drive.google.com/uc?export=download&id=${match[1]}`;
  }
  return trimmed;
};

const triggerFileDownload = (rawPath: string) => {
  if (!rawPath) return;

  if (rawPath.startsWith('http://') || rawPath.startsWith('https://')) {
    // Cloud URL / Google Drive
    const downloadUrl = resolveGoogleDriveDownloadUrl(rawPath);
    window.open(downloadUrl, '_blank');
  } else {
    // Local File Server URL
    const cleanPath = rawPath.startsWith('/') ? rawPath : `/${rawPath}`;
    const link = document.createElement('a');
    link.href = cleanPath;
    link.download = `${props.ebook?.slug || 'ebook'}.pdf`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }
};

const submitEbookLead = async () => {
  if (!form.name.trim() || !form.phone.trim()) {
    toastStore.error(themeStore.locale === 'bn' ? 'অনুগ্রহ করে আপনার নাম ও ফোন নম্বর লিখুন' : 'Please provide your name and phone number');
    return;
  }

  isSubmitting.value = true;
  try {
    const finalWhatsApp = form.sameAsPhone ? form.phone : (form.whatsapp_number || form.phone);
    const currentUrl = typeof window !== 'undefined' ? window.location.href : `/ebooks/${props.ebook?.slug}`;

    const res = await apiClient.post('/public/leads', {
      name: form.name.trim(),
      phone: form.phone.trim(),
      whatsapp_number: finalWhatsApp.trim(),
      lead_type: 'ebook',
      source_content_type: 'ebook',
      source_content_id: props.ebook?.id,
      source_content_slug: props.ebook?.slug,
      source_url: currentUrl,
      source: 'ebook_download_modal',
    });

    const rawPath = res.data?.data?.download?.file_path || props.ebook?.file_path;

    if (rawPath) {
      triggerFileDownload(rawPath);
    }

    // Save download state locally
    try {
      localStorage.setItem(`ebook_downloaded_${props.ebook?.id}`, 'true');
    } catch {}

    toastStore.success(
      themeStore.locale === 'bn'
        ? 'ধন্যবাদ! ই-বুকটি সফলভাবে ডাউনলোড হচ্ছে।'
        : 'Thank you! Ebook download has started successfully.'
    );

    emit('update:modelValue', false);
    emit('download-success', { ebook: props.ebook });
  } catch (err: any) {
    toastStore.error(
      err.response?.data?.message || (themeStore.locale === 'bn' ? 'ডাউনলোড শুরু করতে ব্যর্থ হয়েছে।' : 'Failed to initiate download.')
    );
  } finally {
    isSubmitting.value = false;
  }
};
</script>
