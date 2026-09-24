<template>
  <div class="min-h-screen bg-[var(--bg-deep)] text-[var(--text-primary)]">
    <router-view />
    <AppToast />
    <!-- Public support widget; hidden in the staff console where it covered table/card actions -->
    <QuickSupportFloating v-if="!route.path.startsWith('/admin')" />
    <OfflineIndicator />
    <PwaInstallPrompt />
    <PwaUpdatePrompt />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useThemeStore } from './stores/theme';
import AppToast from './components/ui/AppToast.vue';
import QuickSupportFloating from './components/shared/QuickSupportFloating.vue';
import OfflineIndicator from './components/pwa/OfflineIndicator.vue';
import PwaInstallPrompt from './components/pwa/PwaInstallPrompt.vue';
import PwaUpdatePrompt from './components/pwa/PwaUpdatePrompt.vue';

const themeStore = useThemeStore();
const route = useRoute();

onMounted(() => {
  themeStore.initTheme();
});
</script>
