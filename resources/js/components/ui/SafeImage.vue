<template>
  <div
    :class="[
      'relative overflow-hidden bg-[var(--bg-elevated)] flex items-center justify-center',
      aspectRatio,
      containerClass
    ]"
  >
    <!-- Shimmer Skeleton Loading State -->
    <div
      v-if="isLoading && !hasError"
      class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-pulse"
    ></div>

    <!-- Actual Image -->
    <img
      :src="computedSrc"
      :alt="alt || 'Emisha Academy'"
      :loading="lazy ? 'lazy' : 'eager'"
      :class="[
        'w-full h-full object-cover transition-opacity duration-300',
        isLoading ? 'opacity-0' : 'opacity-100',
        imgClass
      ]"
      @load="handleLoad"
      @error="handleError"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import {
  getInitialsAvatar,
  getCourseFallbackThumbnail,
  getEbookFallbackCover,
  getWebinarFallbackThumbnail,
  getBlogFallbackThumbnail,
} from '../../utils/imageFallback';

const props = withDefaults(
  defineProps<{
    src?: string | null;
    alt?: string;
    type?: 'course' | 'webinar' | 'ebook' | 'blog' | 'avatar' | 'logo' | 'general';
    name?: string;
    aspectRatio?: string;
    containerClass?: string;
    imgClass?: string;
    lazy?: boolean;
  }>(),
  {
    src: '',
    alt: '',
    type: 'course',
    name: 'Emisha',
    aspectRatio: '',
    containerClass: '',
    imgClass: '',
    lazy: true,
  }
);

const isLoading = ref(true);
const hasError = ref(false);

const getFallback = () => {
  switch (props.type) {
    case 'avatar':
      return getInitialsAvatar(props.name || props.alt || 'EA');
    case 'ebook':
      return getEbookFallbackCover(props.name || props.alt);
    case 'webinar':
      return getWebinarFallbackThumbnail();
    case 'blog':
      return getBlogFallbackThumbnail();
    case 'logo':
      return '/favicon.png';
    case 'course':
    case 'general':
    default:
      return getCourseFallbackThumbnail(props.name || props.alt);
  }
};

const computedSrc = computed(() => {
  if (hasError.value || !props.src || typeof props.src !== 'string' || props.src.trim() === '') {
    return getFallback();
  }
  return props.src;
});

const handleLoad = () => {
  isLoading.value = false;
};

const handleError = () => {
  hasError.value = true;
  isLoading.value = false;
};

watch(
  () => props.src,
  (newSrc) => {
    if (newSrc) {
      hasError.value = false;
      isLoading.value = true;
    }
  }
);
</script>
