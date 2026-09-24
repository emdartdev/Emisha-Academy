<template>
  <div class="space-y-8 max-w-4xl">
    <!-- Header -->
    <div>
      <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">{{ $t('student.account_settings') }}</h1>
      <p class="text-sm text-[var(--text-secondary)] mt-1">{{ $t('student.account_settings_sub') }}</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex border-b border-[var(--border-subtle)] gap-4 sm:gap-8 overflow-x-auto no-scrollbar">
      <button
        @click="activeTab = 'profile'"
        :class="[
          'pb-4 text-xs sm:text-sm font-semibold transition-all relative whitespace-nowrap cursor-pointer touch-target flex items-center gap-2',
          activeTab === 'profile' ? 'text-[var(--brand-gold)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
        ]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
          <circle cx="12" cy="7" r="4"/>
        </svg>
        <span>{{ $t('student.tab_profile') }}</span>
        <div v-if="activeTab === 'profile'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-[var(--brand-gold)]"></div>
      </button>

      <button
        @click="activeTab = 'password'"
        :class="[
          'pb-4 text-xs sm:text-sm font-semibold transition-all relative whitespace-nowrap cursor-pointer touch-target flex items-center gap-2',
          activeTab === 'password' ? 'text-[var(--brand-gold)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
        ]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
        <span>{{ $t('student.tab_security') }}</span>
        <div v-if="activeTab === 'password'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-[var(--brand-gold)]"></div>
      </button>

      <button
        @click="activeTab = 'devices'; loadSessions()"
        :class="[
          'pb-4 text-xs sm:text-sm font-semibold transition-all relative whitespace-nowrap cursor-pointer touch-target flex items-center gap-2',
          activeTab === 'devices' ? 'text-[var(--brand-gold)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
        ]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <rect width="20" height="14" x="2" y="3" rx="2"/>
          <line x1="8" x2="16" y1="21" y2="21"/>
          <line x1="12" x2="12" y1="17" y2="21"/>
        </svg>
        <span>{{ themeStore.locale === 'bn' ? 'সংযুক্ত ডিভাইস ও সেশন' : 'Connected Devices' }}</span>
        <span v-if="sessionsData.total_active" class="px-1.5 py-0.2 text-[10px] rounded-full bg-[var(--brand-gold-subtle)] text-[var(--brand-gold)] font-bold">
          {{ sessionsData.total_active }}/{{ sessionsData.max_allowed }}
        </span>
        <div v-if="activeTab === 'devices'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-[var(--brand-gold)]"></div>
      </button>

      <button
        @click="activeTab = 'appearance'"
        :class="[
          'pb-4 text-xs sm:text-sm font-semibold transition-all relative whitespace-nowrap cursor-pointer touch-target flex items-center gap-2',
          activeTab === 'appearance' ? 'text-[var(--brand-gold)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
        ]"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 2a7 7 0 0 0 0 14v6"/>
          <circle cx="12" cy="8" r="2"/>
        </svg>
        <span>{{ $t('student.tab_appearance') }}</span>
        <div v-if="activeTab === 'appearance'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-[var(--brand-gold)]"></div>
      </button>
    </div>

    <!-- Tab 1: Profile Form -->
    <div v-if="activeTab === 'profile'" class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-5 sm:p-8 space-y-6 shadow-xs">
      <!-- Profile Header / Live Avatar Preview -->
      <div class="flex items-center gap-4 sm:gap-6 pb-6 border-b border-[var(--border-subtle)]">
        <div class="relative group">
          <img
            :src="getAvatarUriById(profileForm.avatar, profileForm.name)"
            alt="Current Profile Avatar"
            class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[var(--bg-elevated)] border-2 border-[#D4AF37]/60 shadow-lg object-cover shrink-0"
          />
          <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-[var(--bg-surface)] flex items-center justify-center text-white text-[10px]">
            ✓
          </div>
        </div>
        <div class="min-w-0 flex-1">
          <h3 class="text-base sm:text-lg font-bold text-[var(--text-primary)] truncate">{{ profileForm.name || 'Student' }}</h3>
          <p class="text-xs text-[var(--text-secondary)] mt-0.5 truncate">{{ profileForm.email }}</p>
          <div class="flex flex-wrap items-center gap-2 mt-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[var(--brand-gold-subtle)] text-[var(--brand-gold)] border border-[var(--border-accent)]">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
              </svg>
              <span>{{ $t('common.verified') }} {{ themeStore.locale === 'bn' ? 'শিক্ষার্থী' : 'Student' }}</span>
            </span>
            <span class="text-[11px] text-[var(--text-muted)]">
              {{ themeStore.locale === 'bn' ? 'নিচের অবতারগুলো থেকে আপনার পছন্দের অবতারটি সিলেক্ট করুন' : 'Choose your favorite avatar from below' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Pre-built Profile Avatar Selection Section -->
      <div class="space-y-3 pb-6 border-b border-[var(--border-subtle)]">
        <div>
          <label class="block text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider mb-1">
            {{ themeStore.locale === 'bn' ? 'প্রোফাইল অবতার নির্বাচন করুন' : 'Select Profile Avatar' }}
          </label>
          <p class="text-xs text-[var(--text-secondary)]">
            {{ themeStore.locale === 'bn' ? 'আপনার অ্যাকাউন্টের জন্য প্রি-বিল্ট ভেক্টর অবতার থেকে যেকোনো একটি বেছে নিন:' : 'Pick any pre-designed vector avatar for your profile:' }}
          </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-3 pt-1">
          <button
            v-for="avatar in PREBUILT_AVATARS"
            :key="avatar.id"
            type="button"
            @click="profileForm.avatar = avatar.id"
            :class="[
              'p-3 rounded-2xl border transition-all text-left flex flex-col items-center gap-2.5 cursor-pointer relative group',
              profileForm.avatar === avatar.id
                ? 'bg-[#D4AF37]/10 border-[#D4AF37] ring-2 ring-[#D4AF37]/30 shadow-md'
                : 'bg-[var(--bg-deep)] border-[var(--border-subtle)] hover:border-[var(--brand-gold)]/60 hover:bg-[var(--bg-elevated)]'
            ]"
          >
            <!-- Checkmark badge if selected -->
            <div
              v-if="profileForm.avatar === avatar.id"
              class="absolute top-2 right-2 w-5 h-5 rounded-full bg-[#D4AF37] text-slate-950 flex items-center justify-center text-xs font-black shadow-xs"
            >
              ✓
            </div>

            <!-- Avatar Image Icon -->
            <img
              :src="avatar.svgDataUri"
              :alt="avatar.name_en"
              class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl object-contain drop-shadow-sm transition-transform group-hover:scale-105"
            />

            <!-- Avatar Title -->
            <div class="text-center w-full">
              <p :class="['text-xs font-bold truncate', profileForm.avatar === avatar.id ? 'text-[var(--brand-gold)]' : 'text-[var(--text-primary)]']">
                {{ themeStore.locale === 'bn' ? avatar.name_bn : avatar.name_en }}
              </p>
              <p class="text-[10px] text-[var(--text-muted)] capitalize truncate mt-0.5">
                {{ avatar.category }}
              </p>
            </div>
          </button>
        </div>
      </div>

      <form @submit.prevent="updateProfile" class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase tracking-wider mb-2">{{ $t('student.full_name') }}</label>
            <input
              v-model="profileForm.name"
              type="text"
              required
              class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-base sm:text-sm focus:outline-none focus:border-[var(--brand-gold)] transition-colors min-h-[44px]"
              :placeholder="themeStore.locale === 'bn' ? 'আপনার নাম লিখুন' : 'Enter your name'"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase tracking-wider mb-2">{{ $t('student.email_readonly') }}</label>
            <input
              :value="profileForm.email"
              disabled
              type="email"
              class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)]/50 border border-[var(--border-subtle)] text-[var(--text-muted)] text-base sm:text-sm cursor-not-allowed min-h-[44px]"
            />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase tracking-wider mb-2">{{ $t('student.phone') }}</label>
            <input
              v-model="profileForm.phone"
              type="tel"
              required
              class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-base sm:text-sm focus:outline-none focus:border-[var(--brand-gold)] transition-colors min-h-[44px]"
              placeholder="017XXXXXXXX"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase tracking-wider mb-2">{{ $t('student.headline') }}</label>
            <input
              v-model="profileForm.headline"
              type="text"
              class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-base sm:text-sm focus:outline-none focus:border-[var(--brand-gold)] transition-colors min-h-[44px]"
              :placeholder="themeStore.locale === 'bn' ? 'উদাঃ এয়ার টিকেটিং ট্রেইনি' : 'e.g. Air Ticketing Trainee'"
            />
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase tracking-wider mb-2">{{ $t('student.bio') }}</label>
          <textarea
            v-model="profileForm.bio"
            rows="3"
            class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-base sm:text-sm focus:outline-none focus:border-[var(--brand-gold)] transition-colors"
            :placeholder="themeStore.locale === 'bn' ? 'আপনার সংক্ষিপ্ত পরিচয়...' : 'Write a short bio...'"
          ></textarea>
        </div>

        <div class="pt-2">
          <button
            type="submit"
            :disabled="savingProfile"
            class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-bold text-sm hover:brightness-110 shadow-md transition-all disabled:opacity-50 cursor-pointer touch-target w-full sm:w-auto"
          >
            <span v-if="savingProfile">{{ $t('student.updating') }}</span>
            <span v-else>{{ $t('student.update_profile') }}</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Tab 2: Password Form -->
    <div v-if="activeTab === 'password'" class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-6 sm:p-8 space-y-6 shadow-xs">
      <div>
        <h3 class="text-lg font-bold text-[var(--text-primary)] mb-1">
          {{ themeStore.locale === 'bn' ? 'পাসওয়ার্ড পরিবর্তন করুন' : 'Change Password' }}
        </h3>
        <p class="text-xs text-[var(--text-secondary)]">
          {{ themeStore.locale === 'bn' ? 'নিরাপত্তার স্বার্থে নিয়মিত পাসওয়ার্ড পরিবর্তন করার পরামর্শ দেওয়া হচ্ছে।' : 'It is recommended to update your password regularly for security.' }}
        </p>
      </div>

      <form @submit.prevent="updatePassword" class="space-y-5 max-w-md">
        <AppInput
          v-model="passwordForm.current_password"
          :label="$t('student.current_password')"
          type="password"
          required
          placeholder="••••••••"
          autocomplete="current-password"
        >
          <template #prefix>
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </template>
        </AppInput>

        <div class="space-y-1.5">
          <AppInput
            v-model="passwordForm.password"
            :label="$t('student.new_password')"
            type="password"
            required
            minlength="8"
            :placeholder="themeStore.locale === 'bn' ? 'কমপক্ষে ৮ ডিজিটের শক্তিশালী পাসওয়ার্ড' : 'Minimum 8 characters strong password'"
            autocomplete="new-password"
          >
            <template #prefix>
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
            </template>
          </AppInput>

          <p v-if="passwordForm.password && passwordForm.password.length < 8" class="text-[11px] text-amber-500 font-medium flex items-center gap-1">
            <span>{{ themeStore.locale === 'bn' ? 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে' : 'Password must be at least 8 characters' }}</span>
          </p>
        </div>

        <div class="space-y-1.5">
          <AppInput
            v-model="passwordForm.password_confirmation"
            :label="$t('student.confirm_password')"
            type="password"
            required
            minlength="8"
            placeholder="••••••••"
            autocomplete="new-password"
          >
            <template #prefix>
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                <path d="m9 12 2 2 4-4"/>
              </svg>
            </template>
          </AppInput>

          <!-- Password Match Status Alert -->
          <div v-if="passwordForm.password_confirmation" class="pt-0.5">
            <div
              v-if="isSettingsPasswordMatching"
              class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-500 text-xs font-bold transition-all"
            >
              <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span>{{ themeStore.locale === 'bn' ? '✓ পাসওয়ার্ড দুটি মিলেছে' : '✓ Passwords match' }}</span>
            </div>
            <div
              v-else
              class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/25 text-amber-500 text-xs font-semibold transition-all"
            >
              <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              <span>{{ themeStore.locale === 'bn' ? '✕ পাসওয়ার্ড দুটি এখনও মেলেনি' : '✕ Passwords do not match yet' }}</span>
            </div>
          </div>
        </div>

        <div class="pt-4">
          <button
            type="submit"
            :disabled="savingPassword"
            class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-bold text-sm hover:brightness-110 shadow-md transition-all disabled:opacity-50 cursor-pointer"
          >
            <span v-if="savingPassword">{{ $t('student.updating') }}</span>
            <span v-else>{{ $t('student.save_password') }}</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Tab 3: Connected Devices & Sessions -->
    <div v-if="activeTab === 'devices'" class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-6 sm:p-8 space-y-6 shadow-xs">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[var(--border-subtle)]">
        <div>
          <h3 class="text-lg font-bold text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'সংযুক্ত ডিভাইস ও সক্রিয় সেশন' : 'Connected Devices & Active Sessions' }}
          </h3>
          <p class="text-xs text-[var(--text-secondary)] mt-1">
            {{ themeStore.locale === 'bn' ? 'অ্যাকাউন্ট সিকিউরিটির জন্য শিক্ষার্থী অ্যাকাউন্টে সর্বোচ্চ ২টি ডিভাইসে সক্রিয় লগইন অনুমোদিত।' : 'For account security, student accounts allow a maximum of 2 active devices simultaneously.' }}
          </p>
        </div>

        <div class="flex items-center gap-3">
          <span class="px-3 py-1.5 rounded-xl bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] text-xs font-bold text-[var(--brand-gold)]">
            {{ sessionsData.total_active }} / {{ sessionsData.max_allowed }} {{ themeStore.locale === 'bn' ? 'ডিভাইস সক্রিয়' : 'Active Devices' }}
          </span>

          <button
            v-if="sessionsData.total_active > 1"
            @click="revokeAllOtherSessions"
            :disabled="revokingSession"
            class="px-3.5 py-1.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 hover:bg-red-500/20 text-xs font-bold transition-all cursor-pointer disabled:opacity-50"
          >
            {{ themeStore.locale === 'bn' ? 'অন্যান্য ডিভাইস লগআউট' : 'Revoke Others' }}
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loadingSessions" class="py-8 text-center text-xs text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'ডিভাইস সেশন লোড হচ্ছে...' : 'Loading active sessions...' }}
      </div>

      <!-- Devices List -->
      <div v-else-if="sessionsData.sessions && sessionsData.sessions.length > 0" class="space-y-3">
        <div
          v-for="session in sessionsData.sessions"
          :key="session.id"
          :class="[
            'p-4 rounded-2xl border transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4',
            session.is_current
              ? 'bg-[var(--bg-elevated)] border-[#D4AF37]/50 shadow-sm'
              : 'bg-[var(--bg-deep)] border-[var(--border-subtle)]'
          ]"
        >
          <div class="flex items-center gap-3.5">
            <div :class="['w-10 h-10 rounded-xl flex items-center justify-center shrink-0', session.is_current ? 'bg-[var(--brand-gold-subtle)] text-[var(--brand-gold)]' : 'bg-slate-800/40 text-slate-400']">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect width="20" height="14" x="2" y="3" rx="2"/>
                <line x1="8" x2="16" y1="21" y2="21"/>
                <line x1="12" x2="12" y1="17" y2="21"/>
              </svg>
            </div>

            <div>
              <div class="flex items-center gap-2">
                <h4 class="font-bold text-sm text-[var(--text-primary)]">{{ session.device_name || 'Web Browser' }}</h4>
                <span v-if="session.is_current" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                  {{ themeStore.locale === 'bn' ? 'বর্তমান ডিভাইস' : 'Current Device' }}
                </span>
              </div>
              <p class="text-[11px] text-[var(--text-secondary)] mt-0.5">
                {{ themeStore.locale === 'bn' ? 'সর্বশেষ ব্যবহার:' : 'Last active:' }} {{ session.last_used_at }}
                <span v-if="session.created_at"> • {{ themeStore.locale === 'bn' ? 'শুরু:' : 'Logged in:' }} {{ session.created_at }}</span>
              </p>
            </div>
          </div>

          <div v-if="!session.is_current">
            <button
              @click="revokeSingleSession(session.id)"
              :disabled="revokingSession"
              class="px-3 py-1.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 hover:bg-red-500/20 text-xs font-semibold transition-all cursor-pointer"
            >
              {{ themeStore.locale === 'bn' ? 'সেশন বাতিল করুন' : 'Revoke Session' }}
            </button>
          </div>
        </div>
      </div>

      <div v-else class="py-8 text-center text-xs text-[var(--text-secondary)]">
        {{ themeStore.locale === 'bn' ? 'কোনো সক্রিয় সেশন তথ্য পাওয়া যায়নি।' : 'No active session data found.' }}
      </div>
    </div>

    <!-- Tab 4: Appearance & Language Settings -->
    <div v-if="activeTab === 'appearance'" class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-6 sm:p-8 space-y-8 shadow-xs">
      
      <!-- Theme Selection Grid -->
      <div class="space-y-4">
        <div>
          <h3 class="text-lg font-bold text-[var(--text-primary)] mb-1">{{ $t('student.theme_heading') }}</h3>
          <p class="text-xs text-[var(--text-secondary)]">{{ $t('student.theme_sub') }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
          <!-- Light Theme Card -->
          <div
            @click="themeStore.setTheme('light')"
            :class="[
              'p-5 rounded-2xl border-2 transition-all cursor-pointer flex flex-col items-center text-center space-y-3 group',
              themeStore.mode === 'light'
                ? 'border-[var(--brand-gold)] bg-[var(--bg-elevated)] shadow-md'
                : 'border-[var(--border-subtle)] bg-[var(--bg-deep)] hover:border-[#D4AF37]/40'
            ]"
          >
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center border border-amber-500/20 group-hover:scale-105 transition-transform">
              <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="4"/>
                <path d="M12 2v2"/>
                <path d="M12 20v2"/>
                <path d="m4.93 4.93 1.41 1.41"/>
                <path d="m17.66 17.66 1.41 1.41"/>
                <path d="M2 12h2"/>
                <path d="M20 12h2"/>
                <path d="m6.34 17.66-1.41 1.41"/>
                <path d="m19.07 4.93-1.41 1.41"/>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-sm text-[var(--text-primary)]">{{ $t('student.theme_light') }}</h4>
              <p class="text-[11px] text-[var(--text-secondary)] mt-0.5">{{ $t('student.theme_light_desc') }}</p>
            </div>
            <span v-if="themeStore.mode === 'light'" class="text-xs font-bold text-[var(--brand-gold)] flex items-center gap-1">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span>{{ themeStore.locale === 'bn' ? 'সক্রিয়' : 'Active' }}</span>
            </span>
          </div>

          <!-- Dark Theme Card -->
          <div
            @click="themeStore.setTheme('dark')"
            :class="[
              'p-5 rounded-2xl border-2 transition-all cursor-pointer flex flex-col items-center text-center space-y-3 group',
              themeStore.mode === 'dark'
                ? 'border-[var(--brand-gold)] bg-[var(--bg-elevated)] shadow-md'
                : 'border-[var(--border-subtle)] bg-[var(--bg-deep)] hover:border-[#D4AF37]/40'
            ]"
          >
            <div class="w-12 h-12 rounded-xl bg-[#D4AF37]/10 text-[#D4AF37] flex items-center justify-center border border-[#D4AF37]/20 group-hover:scale-105 transition-transform">
              <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-sm text-[var(--text-primary)]">{{ $t('student.theme_dark') }}</h4>
              <p class="text-[11px] text-[var(--text-secondary)] mt-0.5">{{ $t('student.theme_dark_desc') }}</p>
            </div>
            <span v-if="themeStore.mode === 'dark'" class="text-xs font-bold text-[var(--brand-gold)] flex items-center gap-1">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span>{{ themeStore.locale === 'bn' ? 'সক্রিয়' : 'Active' }}</span>
            </span>
          </div>

          <!-- System Theme Card -->
          <div
            @click="themeStore.setTheme('system')"
            :class="[
              'p-5 rounded-2xl border-2 transition-all cursor-pointer flex flex-col items-center text-center space-y-3 group',
              themeStore.mode === 'system'
                ? 'border-[var(--brand-gold)] bg-[var(--bg-elevated)] shadow-md'
                : 'border-[var(--border-subtle)] bg-[var(--bg-deep)] hover:border-[#D4AF37]/40'
            ]"
          >
            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center border border-cyan-500/20 group-hover:scale-105 transition-transform">
              <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect width="20" height="14" x="2" y="3" rx="2"/>
                <line x1="8" x2="16" y1="21" y2="21"/>
                <line x1="12" x2="12" y1="17" y2="21"/>
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-sm text-[var(--text-primary)]">{{ $t('student.theme_system') }}</h4>
              <p class="text-[11px] text-[var(--text-secondary)] mt-0.5">{{ $t('student.theme_system_desc') }}</p>
            </div>
            <span v-if="themeStore.mode === 'system'" class="text-xs font-bold text-[var(--brand-gold)] flex items-center gap-1">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              <span>{{ themeStore.locale === 'bn' ? 'সক্রিয়' : 'Active' }}</span>
            </span>
          </div>
        </div>
      </div>

      <!-- Language Preference Section -->
      <div class="space-y-3 pt-4 border-t border-[var(--border-subtle)]">
        <div>
          <h3 class="text-base font-bold text-[var(--text-primary)] mb-1">
            {{ themeStore.locale === 'bn' ? 'ভাষা নির্বাচন (Language Preference)' : 'Language Preference' }}
          </h3>
          <p class="text-xs text-[var(--text-secondary)]">
            {{ themeStore.locale === 'bn' ? 'আপনার ইন্টারফেসের জন্য পছন্দের ভাষা নির্বাচন করুন।' : 'Select your preferred language for the portal.' }}
          </p>
        </div>

        <div class="max-w-xs pt-1">
          <LanguageToggle variant="segmented" />
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive, computed } from 'vue';
import apiClient from '../../api/client';
import { useAuthStore } from '../../stores/auth';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import LanguageToggle from '../../components/shared/LanguageToggle.vue';
import AppInput from '../../components/ui/AppInput.vue';
import { PREBUILT_AVATARS, getAvatarUriById } from '../../utils/avatars';

const authStore = useAuthStore();
const toast = useToastStore();
const themeStore = useThemeStore();
const activeTab = ref<'profile' | 'password' | 'devices' | 'appearance'>('profile');

const profileForm = reactive({
  name: '',
  email: '',
  phone: '',
  avatar: 'gold_crest',
  headline: '',
  bio: '',
});

const passwordForm = reactive({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const sessionsData = reactive({
  total_active: 1,
  max_allowed: 2,
  sessions: [] as Array<{
    id: number;
    device_name: string;
    is_current: boolean;
    last_used_at: string;
    created_at?: string;
  }>,
});

const isSettingsPasswordMatching = computed(() => {
  if (!passwordForm.password || !passwordForm.password_confirmation) return false;
  return passwordForm.password === passwordForm.password_confirmation;
});

const savingProfile = ref(false);
const savingPassword = ref(false);
const loadingSessions = ref(false);
const revokingSession = ref(false);

async function loadProfile() {
  try {
    const res = await apiClient.get('/auth/me');
    if (res.data.status === 'success') {
      const u = res.data.data.user || res.data.data;
      profileForm.name = u.name || '';
      profileForm.email = u.email || '';
      profileForm.phone = u.phone || '';
      profileForm.avatar = u.avatar || 'gold_crest';
      profileForm.headline = u.profile?.headline || '';
      profileForm.bio = u.profile?.bio || '';
    }
  } catch (err) {
    toast.error(themeStore.locale === 'bn' ? 'প্রোফাইল তথ্য লোড করতে ব্যর্থ হয়েছে' : 'Failed to load profile data');
  }
}

async function loadSessions() {
  try {
    loadingSessions.value = true;
    const res = await apiClient.get('/auth/sessions');
    if (res.data.status === 'success') {
      sessionsData.total_active = res.data.data?.total_active || 1;
      sessionsData.max_allowed = res.data.data?.max_allowed || 2;
      sessionsData.sessions = res.data.data?.sessions || [];
    }
  } catch (err) {
    // Non-fatal
  } finally {
    loadingSessions.value = false;
  }
}

async function revokeSingleSession(id: number) {
  try {
    revokingSession.value = true;
    const res = await apiClient.delete(`/auth/sessions/${id}`);
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'সেশনটি সফলভাবে বাতিল করা হয়েছে।' : 'Session revoked successfully.');
      await loadSessions();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'সেশন বাতিল ব্যর্থ হয়েছে' : 'Failed to revoke session'));
  } finally {
    revokingSession.value = false;
  }
}

async function revokeAllOtherSessions() {
  try {
    revokingSession.value = true;
    const res = await apiClient.post('/auth/sessions/revoke-others');
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'অন্যান্য সকল ডিভাইস থেকে সফলভাবে লগআউট করা হয়েছে।' : 'Logged out from all other devices.');
      await loadSessions();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'অপারেশনটি সম্পন্ন করা যায়নি' : 'Failed to revoke other sessions'));
  } finally {
    revokingSession.value = false;
  }
}

async function updateProfile() {
  try {
    savingProfile.value = true;
    const res = await apiClient.put('/auth/profile', {
      name: profileForm.name,
      email: profileForm.email,
      phone: profileForm.phone,
      avatar: profileForm.avatar,
      headline: profileForm.headline,
      bio: profileForm.bio,
    });
    if (res.data.status === 'success') {
      const updated = res.data.data?.user || res.data.data;
      if (authStore.user) {
        authStore.user.name = updated?.name || profileForm.name;
        authStore.user.phone = updated?.phone || profileForm.phone;
        authStore.user.avatar = updated?.avatar || profileForm.avatar;
      }
      toast.success(themeStore.locale === 'bn' ? 'প্রোফাইল সফলভাবে আপডেট করা হয়েছে!' : 'Profile updated successfully!');
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'প্রোফাইল আপডেট ব্যর্থ হয়েছে' : 'Failed to update profile'));
  } finally {
    savingProfile.value = false;
  }
}

async function updatePassword() {
  if (passwordForm.password !== passwordForm.password_confirmation) {
    toast.error(themeStore.locale === 'bn' ? 'নতুন পাসওয়ার্ড দুটি মিলছে না!' : 'New passwords do not match!');
    return;
  }

  try {
    savingPassword.value = true;
    const res = await apiClient.put('/auth/password', {
      current_password: passwordForm.current_password,
      password: passwordForm.password,
      password_confirmation: passwordForm.password_confirmation,
    });
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে!' : 'Password changed successfully!');
      passwordForm.current_password = '';
      passwordForm.password = '';
      passwordForm.password_confirmation = '';
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'পাসওয়ার্ড পরিবর্তন ব্যর্থ হয়েছে' : 'Failed to update password'));
  } finally {
    savingPassword.value = false;
  }
}

onMounted(() => {
  loadProfile();
  loadSessions();
});
</script>
