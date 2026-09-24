<template>
  <div class="space-y-6">
    <div class="text-center space-y-1">
      <h2 class="text-xl font-bold text-[var(--text-primary)]">
        {{ step === 1 ? $t('auth.forgot_title') : (themeStore.locale === 'bn' ? 'নতুন পাসওয়ার্ড সেট করুন' : 'Set New Password') }}
      </h2>
      <p class="text-xs text-[var(--text-secondary)]">
        {{ step === 1 ? $t('auth.forgot_sub') : (themeStore.locale === 'bn' ? 'ইমেইলে প্রাপ্ত সিকিউরিটি টোকেন ও নতুন পাসওয়ার্ড লিখুন' : 'Enter received security token and your new password') }}
      </p>
    </div>

    <!-- Step 1: Request Reset Token -->
    <form v-if="step === 1" class="space-y-4" @submit.prevent="handleRequestReset">
      <AppInput
        v-model="email"
        :label="$t('auth.email_label')"
        type="email"
        :placeholder="$t('auth.email_placeholder')"
        required
        autocomplete="email"
      />

      <AppButton
        type="submit"
        variant="gold"
        fullWidth
        :loading="loading"
        class="touch-target"
      >
        {{ $t('auth.send_reset_link') }}
      </AppButton>
    </form>

    <!-- Step 2: Complete Reset with Token -->
    <form v-else class="space-y-4" @submit.prevent="handleCompleteReset">
      <div class="p-3 rounded-xl bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-xs text-[var(--brand-gold)] font-medium">
        {{ themeStore.locale === 'bn' ? 'আপনার ইমেইলে সিকিউরিটি টোকেন পাঠানো হয়েছে।' : 'Security reset token has been issued for your email.' }}
      </div>

      <AppInput
        v-model="token"
        :label="themeStore.locale === 'bn' ? 'রিসেট টোকেন / কোড' : 'Reset Token / Code'"
        type="text"
        placeholder="টোকেন কোড লিখুন"
        required
      />

      <AppInput
        v-model="password"
        :label="themeStore.locale === 'bn' ? 'নতুন পাসওয়ার্ড' : 'New Password'"
        type="password"
        placeholder="••••••••"
        required
        minlength="8"
      />

      <AppInput
        v-model="passwordConfirmation"
        :label="themeStore.locale === 'bn' ? 'পাসওয়ার্ড নিশ্চিত করুন' : 'Confirm Password'"
        type="password"
        placeholder="••••••••"
        required
        minlength="8"
      />

      <div class="flex gap-3">
        <AppButton
          type="button"
          variant="outline"
          class="w-1/3"
          @click="step = 1"
        >
          {{ themeStore.locale === 'bn' ? 'পেছনে' : 'Back' }}
        </AppButton>

        <AppButton
          type="submit"
          variant="gold"
          class="w-2/3"
          :loading="loading"
        >
          {{ themeStore.locale === 'bn' ? 'পাসওয়ার্ড আপডেট করুন' : 'Update Password' }}
        </AppButton>
      </div>
    </form>

    <p class="text-center text-xs text-[var(--text-secondary)]">
      <router-link to="/login" class="text-[var(--brand-gold)] font-semibold hover:underline">
        ← {{ $t('auth.back_to_login') }}
      </router-link>
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import apiClient from '../../api/client';
import AppInput from '../../components/ui/AppInput.vue';
import AppButton from '../../components/ui/AppButton.vue';

const router = useRouter();
const toastStore = useToastStore();
const themeStore = useThemeStore();

const step = ref<number>(1);
const email = ref<string>('');
const token = ref<string>('');
const password = ref<string>('');
const passwordConfirmation = ref<string>('');
const loading = ref<boolean>(false);

const handleRequestReset = async () => {
  if (!email.value) {
    toastStore.warning(themeStore.locale === 'bn' ? 'ইমেইল প্রদান করুন' : 'Please provide your email address');
    return;
  }

  loading.value = true;
  try {
    const res = await apiClient.post('/auth/forgot-password', {
      email: email.value,
    });

    toastStore.success(res.data?.message || (themeStore.locale === 'bn' ? 'রিসেট নির্দেশাবলী পাঠানো হয়েছে।' : 'Reset instructions sent.'));

    if (res.data?.data?.reset_token) {
      token.value = res.data.data.reset_token;
    }
    step.value = 2;
  } catch (err: any) {
    const msg = err.response?.data?.message || (themeStore.locale === 'bn' ? 'অনুরোধটি সম্পন্ন করা যায়নি।' : 'Failed to request reset.');
    toastStore.error(msg);
  } finally {
    loading.value = false;
  }
};

const handleCompleteReset = async () => {
  if (!token.value || !password.value || !passwordConfirmation.value) {
    toastStore.warning(themeStore.locale === 'bn' ? 'সবগুলো ফিল্ড পূরণ করুন' : 'Please fill all required fields');
    return;
  }

  if (password.value !== passwordConfirmation.value) {
    toastStore.error(themeStore.locale === 'bn' ? 'পাসওয়ার্ড নিশ্চিতকরণ মেলেনি' : 'Passwords do not match');
    return;
  }

  loading.value = true;
  try {
    const res = await apiClient.post('/auth/reset-password', {
      email: email.value,
      token: token.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    });

    toastStore.success(res.data?.message || (themeStore.locale === 'bn' ? 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে।' : 'Password updated successfully.'));
    router.push('/login');
  } catch (err: any) {
    const msg = err.response?.data?.message || (themeStore.locale === 'bn' ? 'পাসওয়ার্ড পরিবর্তন ব্যর্থ হয়েছে।' : 'Failed to reset password.');
    toastStore.error(msg);
  } finally {
    loading.value = false;
  }
};
</script>
