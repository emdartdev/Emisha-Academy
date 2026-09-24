<template>
  <div class="min-h-screen flex flex-col bg-[var(--bg-deep)] text-[var(--text-primary)] selection:bg-[#D4AF37] selection:text-slate-950">
    <!-- Main Public Sticky Header -->
    <AppHeader />

    <!-- Page Content View -->
    <main class="flex-grow">
      <router-view :key="$route.fullPath" />
    </main>

    <!-- Global Public Footer -->
    <AppFooter />
  </div>
</template>

<script setup lang="ts">
import AppHeader from '../components/shared/AppHeader.vue';
import AppFooter from '../components/shared/AppFooter.vue';
</script>
