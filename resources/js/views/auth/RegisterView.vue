<template>
  <div class="space-y-6">
    <div class="text-center space-y-1">
      <h2 class="text-2xl font-black text-[var(--text-primary)] tracking-tight">{{ $t('auth.register_title') }}</h2>
      <p class="text-xs text-[var(--text-secondary)]">{{ $t('auth.register_sub') }}</p>
    </div>

    <form class="space-y-4" @submit.prevent="handleRegister">
      <!-- 1. Full Name -->
      <AppInput
        v-model="form.name"
        :label="$t('auth.name_label')"
        :placeholder="$t('auth.name_placeholder')"
        autocomplete="name"
        required
      >
        <template #prefix>
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </template>
      </AppInput>

      <!-- 2. Email Address -->
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

      <!-- 3. Phone Number -->
      <AppInput
        v-model="form.phone"
        :label="$t('auth.phone_label')"
        type="tel"
        :placeholder="$t('auth.phone_placeholder')"
        autocomplete="tel"
      >
        <template #prefix>
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
          </svg>
        </template>
      </AppInput>

      <!-- 4. Password with Strength Meter -->
      <div class="space-y-1.5">
        <AppInput
          v-model="form.password"
          :label="$t('auth.password_label')"
          type="password"
          :placeholder="themeStore.locale === 'bn' ? 'কমপক্ষে ৮ অক্ষর' : 'At least 8 characters'"
          autocomplete="new-password"
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

        <!-- Password Strength Meter (When typing) -->
        <div v-if="form.password" class="space-y-1.5 pt-1">
          <div class="flex items-center justify-between text-[10.5px]">
            <span class="text-[var(--text-muted)] font-medium">
              {{ themeStore.locale === 'bn' ? 'পাসওয়ার্ডের শক্তি:' : 'Password Strength:' }}
            </span>
            <span :class="['font-bold', passwordStrengthColorClass]">
              {{ passwordStrengthLabel }}
            </span>
          </div>

          <!-- 4-segment visual progress bar -->
          <div class="grid grid-cols-4 gap-1.5 h-1.5">
            <div
              v-for="idx in 4"
              :key="idx"
              :class="[
                'rounded-full transition-all duration-300',
                idx <= passwordStrengthScore ? passwordStrengthBgClass : 'bg-[var(--bg-elevated)] border border-[var(--border-subtle)]'
              ]"
            ></div>
          </div>

          <!-- Live Requirements Checklist -->
          <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] pt-0.5">
            <span :class="['flex items-center gap-1 font-medium transition-colors', form.password.length >= 8 ? 'text-emerald-500' : 'text-[var(--text-muted)]']">
              <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              {{ themeStore.locale === 'bn' ? 'কমপক্ষে ৮ অক্ষর' : '8+ characters' }}
            </span>
            <span :class="['flex items-center gap-1 font-medium transition-colors', /\d/.test(form.password) ? 'text-emerald-500' : 'text-[var(--text-muted)]']">
              <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              {{ themeStore.locale === 'bn' ? 'কমপক্ষে ১টি সংখ্যা' : '1+ number' }}
            </span>
            <span :class="['flex items-center gap-1 font-medium transition-colors', /[a-zA-Z]/.test(form.password) ? 'text-emerald-500' : 'text-[var(--text-muted)]']">
              <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              {{ themeStore.locale === 'bn' ? 'ইংরেজি অক্ষর' : 'Letters' }}
            </span>
          </div>
        </div>
      </div>

      <!-- 5. Confirm Password with Real-time Match Indicator -->
      <div class="space-y-1.5">
        <AppInput
          v-model="form.password_confirmation"
          :label="$t('auth.password_confirm_label')"
          type="password"
          :placeholder="$t('auth.password_confirm_placeholder')"
          autocomplete="new-password"
          required
          @keyup="checkCapsLock"
          @keydown="checkCapsLock"
        >
          <template #prefix>
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
              <path d="m9 12 2 2 4-4"/>
            </svg>
          </template>
        </AppInput>

        <!-- Live Password Match Status Alert -->
        <div v-if="form.password_confirmation" class="pt-0.5">
          <!-- Matching State -->
          <div
            v-if="isPasswordMatching"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-500 text-xs font-bold transition-all animate-fadeIn"
          >
            <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span>{{ themeStore.locale === 'bn' ? '✓ পাসওয়ার্ড দুটি হুবহু মিলেছে' : '✓ Passwords match perfectly' }}</span>
          </div>

          <!-- Non-Matching State -->
          <div
            v-else
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/25 text-amber-500 text-xs font-semibold transition-all animate-fadeIn"
          >
            <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ themeStore.locale === 'bn' ? '✕ পাসওয়ার্ড দুটি এখনও মিলছে না' : '✕ Passwords do not match yet' }}</span>
          </div>
        </div>

        <!-- Caps Lock Alert -->
        <p v-if="isCapsLockOn" class="text-[11px] font-semibold text-amber-400 flex items-center gap-1.5 mt-1">
          <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M12 2v20M17 7l-5-5-5 5"/>
          </svg>
          <span>{{ themeStore.locale === 'bn' ? 'সাবধান: ক্যাপস লক (Caps Lock) চালু আছে' : 'Warning: Caps Lock is ON' }}</span>
        </p>
      </div>

      <!-- Submit Button -->
      <AppButton
        type="submit"
        variant="gold"
        fullWidth
        :loading="loading"
        class="touch-target mt-2"
      >
        {{ $t('auth.register_button') }}
      </AppButton>
    </form>

    <p class="text-center text-xs text-[var(--text-secondary)]">
      {{ $t('auth.have_account') }} 
      <router-link to="/login" class="text-[var(--brand-gold)] font-bold hover:underline">{{ $t('nav.login') }}</router-link>
    </p>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import AppInput from '../../components/ui/AppInput.vue';
import AppButton from '../../components/ui/AppButton.vue';

const router = useRouter();
const authStore = useAuthStore();
const toastStore = useToastStore();
const themeStore = useThemeStore();

const loading = ref(false);
const isCapsLockOn = ref(false);

const form = reactive({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
});

const checkCapsLock = (e: KeyboardEvent) => {
  isCapsLockOn.value = e.getModifierState && e.getModifierState('CapsLock');
};

const isPasswordMatching = computed(() => {
  if (!form.password || !form.password_confirmation) return false;
  return form.password === form.password_confirmation;
});

const passwordStrengthScore = computed(() => {
  const p = form.password;
  if (!p) return 0;
  let score = 0;
  if (p.length >= 6) score += 1;
  if (p.length >= 8) score += 1;
  if (/\d/.test(p)) score += 1;
  if (/[A-Z]/.test(p) || /[^A-Za-z0-9]/.test(p)) score += 1;
  return Math.min(score, 4);
});

const passwordStrengthLabel = computed(() => {
  const score = passwordStrengthScore.value;
  if (score <= 1) return themeStore.locale === 'bn' ? 'দুর্বল (Weak)' : 'Weak';
  if (score === 2) return themeStore.locale === 'bn' ? 'মোটামুটি (Fair)' : 'Fair';
  if (score === 3) return themeStore.locale === 'bn' ? 'ভালো (Good)' : 'Good';
  return themeStore.locale === 'bn' ? 'অত্যন্ত শক্তিশালী (Strong)' : 'Strong';
});

const passwordStrengthColorClass = computed(() => {
  const score = passwordStrengthScore.value;
  if (score <= 1) return 'text-rose-500';
  if (score === 2) return 'text-amber-500';
  if (score === 3) return 'text-sky-400';
  return 'text-emerald-500';
});

const passwordStrengthBgClass = computed(() => {
  const score = passwordStrengthScore.value;
  if (score <= 1) return 'bg-rose-500';
  if (score === 2) return 'bg-amber-500';
  if (score === 3) return 'bg-sky-400';
  return 'bg-emerald-500';
});

const handleRegister = async () => {
  if (!form.name || !form.email || !form.password) {
    toastStore.warning(themeStore.locale === 'bn' ? 'অনুগ্রহ করে প্রয়োজনীয় তথ্য পূরণ করুন' : 'Please fill in all required fields');
    return;
  }

  if (form.password.length < 8) {
    toastStore.warning(themeStore.locale === 'bn' ? 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে' : 'Password must be at least 8 characters');
    return;
  }

  if (form.password !== form.password_confirmation) {
    toastStore.warning(themeStore.locale === 'bn' ? 'দুইটি পাসওয়ার্ড মিলছে না, যাচাই করুন' : 'Passwords do not match');
    return;
  }

  loading.value = true;
  try {
    await authStore.register({
      name: form.name,
      email: form.email,
      phone: form.phone,
      password: form.password,
      password_confirmation: form.password_confirmation,
    });
    toastStore.success(themeStore.locale === 'bn' ? 'রেজিস্ট্রেশন সফল হয়েছে! স্বাগতম।' : 'Registration successful! Welcome.');
    router.push('/student/dashboard');
  } catch (err: any) {
    const errorMsg = err.response?.data?.message || (themeStore.locale === 'bn' ? 'রেজিস্ট্রেশন ব্যর্থ হয়েছে।' : 'Registration failed.');
    toastStore.error(errorMsg);
  } finally {
    loading.value = false;
  }
};
</script>
