<template>
  <div class="space-y-6">
    <div class="text-center space-y-1">
      <h2 class="text-2xl font-black text-[var(--text-primary)] tracking-tight">{{ $t('auth.login_title') }}</h2>
      <p class="text-xs text-[var(--text-secondary)]">{{ $t('auth.login_sub') }}</p>
    </div>

    <!-- Login Form -->
    <form class="space-y-4" @submit.prevent="handleLogin">
      <AppInput
        v-model="form.email"
        :label="$t('auth.email_label')"
        type="email"
        :placeholder="$t('auth.email_placeholder')"
        autocomplete="email"
        required
      >
        <template #prefix>
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="m4 4 16 0c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
        </template>
      </AppInput>

      <div>
        <div class="flex items-center justify-between mb-1.5">
          <label class="block text-xs font-semibold text-[var(--text-secondary)]">
            {{ $t('auth.password_label') }}
            <span class="text-rose-400">*</span>
          </label>
          <router-link to="/forgot-password" class="text-[11px] text-[var(--brand-gold)] hover:underline font-semibold">
            {{ $t('auth.forgot_password') }}
          </router-link>
        </div>

        <AppInput
          v-model="form.password"
          type="password"
          :placeholder="$t('auth.password_placeholder')"
          autocomplete="current-password"
          required
          @keyup="checkCapsLock"
          @keydown="checkCapsLock"
        >
          <template #prefix>
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </template>
        </AppInput>

        <!-- Caps Lock Warning -->
        <p v-if="isCapsLockOn" class="text-[11px] font-semibold text-amber-400 flex items-center gap-1.5 mt-1.5">
          <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M12 2v20M17 7l-5-5-5 5"/>
          </svg>
          <span>{{ themeStore.locale === 'bn' ? 'সাবধান: ক্যাপস লক (Caps Lock) চালু আছে' : 'Warning: Caps Lock is ON' }}</span>
        </p>
      </div>

      <AppButton
        type="submit"
        variant="gold"
        fullWidth
        :loading="loading"
        class="touch-target"
      >
        {{ $t('auth.login_button') }}
      </AppButton>
    </form>

    <p class="text-center text-xs text-[var(--text-secondary)]">
      {{ $t('auth.no_account') }} 
      <router-link to="/register" class="text-[var(--brand-gold)] font-bold hover:underline">{{ $t('auth.register_now') }}</router-link>
    </p>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import AppInput from '../../components/ui/AppInput.vue';
import AppButton from '../../components/ui/AppButton.vue';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const toastStore = useToastStore();
const themeStore = useThemeStore();

const loading = ref(false);
const isCapsLockOn = ref(false);

const form = reactive({
  email: '',
  password: '',
});

const checkCapsLock = (e: KeyboardEvent) => {
  isCapsLockOn.value = e.getModifierState && e.getModifierState('CapsLock');
};

const handleLogin = async () => {
  if (!form.email || !form.password) {
    toastStore.warning(themeStore.locale === 'bn' ? 'ইমেইল এবং পাসওয়ার্ড দিন' : 'Please provide email and password');
    return;
  }

  loading.value = true;
  try {
    await authStore.login(form.email, form.password);
    toastStore.success(themeStore.locale === 'bn' ? 'সফলভাবে লগইন হয়েছে' : 'Signed in successfully');
    
    // Redirect logic based on target query param or role
    const redirectUrl = route.query.redirect as string;
    if (redirectUrl) {
      router.push(redirectUrl);
    } else if (authStore.isAdmin || authStore.isManager) {
      router.push('/admin/dashboard');
    } else if (authStore.isWorker) {
      router.push('/admin/leads');
    } else {
      router.push('/student/dashboard');
    }
  } catch (err: any) {
    const message = err.response?.data?.message || (themeStore.locale === 'bn' ? 'লগইন ব্যর্থ হয়েছে। তথ্য যাচাই করুন।' : 'Login failed. Please check your credentials.');
    toastStore.error(message);
  } finally {
    loading.value = false;
  }
};
</script>
