<template>
  <div class="space-y-6 sm:space-y-8">
    
    <!-- 1. Header & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-xl sm:text-2xl font-black text-[var(--text-primary)] tracking-tight">
            {{ themeStore.locale === 'bn' ? 'শিক্ষার্থী এনরোলমেন্ট ও ব্যাচ রেজিস্টার' : 'Student Enrollments & Batches' }}
          </h1>
          <span class="px-2.5 py-0.5 rounded-full bg-[var(--brand-gold-subtle)] text-[#D4AF37] border border-[var(--border-accent)] text-[10px] font-black uppercase tracking-wider">
            Live Database
          </span>
        </div>
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] mt-1">
          {{ themeStore.locale === 'bn'
            ? 'সকল শিক্ষার্থীর সরাসরি কোর্স ভর্তি, ব্যাচ নির্ধারণ, পড়াশোনার অগ্রগতি ও এনরোলমেন্ট স্ট্যাটাস পরিচালনা।'
            : 'Manage direct student enrollments, batch assignments, progress tracking, and classroom permissions.' }}
        </p>
      </div>

      <div class="flex items-center gap-2.5 self-start sm:self-auto">
        <!-- Refresh Button -->
        <button
          @click="fetchEnrollments"
          class="px-3.5 py-2 rounded-xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer touch-target shadow-xs"
          :title="themeStore.locale === 'bn' ? 'রিফ্রেশ করুন' : 'Refresh'"
        >
          <svg class="w-3.5 h-3.5" :class="{ 'animate-spin': loading }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
          <span class="hidden sm:inline">{{ themeStore.locale === 'bn' ? 'রিফ্রেশ' : 'Refresh' }}</span>
        </button>

        <!-- New Enrollment Modal Trigger -->
        <button
          @click="openEnrollModal()"
          class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all flex items-center gap-1.5 cursor-pointer touch-target shadow-md"
        >
          <span class="text-sm">+</span>
          <span>{{ themeStore.locale === 'bn' ? 'নতুন শিক্ষার্থী এনরোল করুন' : 'Enroll Student' }}</span>
        </button>
      </div>
    </div>

    <!-- 2. KPI Metrics Overview HUD -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
      
      <!-- Total Enrollments -->
      <div
        @click="statusFilter = 'all'; fetchEnrollments()"
        class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 transition-all shadow-xs cursor-pointer group"
      >
        <div class="flex items-center justify-between text-[11px] text-[var(--text-muted)] font-semibold">
          <span>{{ themeStore.locale === 'bn' ? 'মোট এনরোলমেন্ট' : 'Total Enrollments' }}</span>
          <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        </div>
        <div class="text-xl sm:text-2xl font-black text-[var(--text-primary)] mt-1.5">
          {{ formatNumber(metrics.total_enrollments, themeStore.locale) }}
        </div>
      </div>

      <!-- Active Enrollments -->
      <div
        @click="statusFilter = 'active'; fetchEnrollments()"
        class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-emerald-500/50 transition-all shadow-xs cursor-pointer group"
      >
        <div class="flex items-center justify-between text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
          <span>{{ themeStore.locale === 'bn' ? 'সক্রিয় (Active)' : 'Active Students' }}</span>
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        </div>
        <div class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1.5">
          {{ formatNumber(metrics.active_enrollments, themeStore.locale) }}
        </div>
      </div>

      <!-- Completed Courses -->
      <div
        @click="statusFilter = 'completed'; fetchEnrollments()"
        class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-teal-500/50 transition-all shadow-xs cursor-pointer group"
      >
        <div class="flex items-center justify-between text-[11px] text-teal-600 dark:text-teal-400 font-semibold">
          <span>{{ themeStore.locale === 'bn' ? 'কোর্স সম্পন্ন (Graduated)' : 'Completed' }}</span>
          <svg class="w-4 h-4 text-teal-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="text-xl sm:text-2xl font-black text-teal-600 dark:text-teal-400 mt-1.5">
          {{ formatNumber(metrics.completed_enrollments, themeStore.locale) }}
        </div>
      </div>

      <!-- Enrolled This Month -->
      <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] shadow-xs">
        <div class="flex items-center justify-between text-[11px] text-sky-600 dark:text-sky-400 font-semibold">
          <span>{{ themeStore.locale === 'bn' ? 'চলতি মাসের ভর্তি' : 'This Month' }}</span>
          <svg class="w-4 h-4 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div class="text-xl sm:text-2xl font-black text-sky-600 dark:text-sky-400 mt-1.5">
          {{ formatNumber(metrics.enrolled_this_month, themeStore.locale) }}
        </div>
      </div>

    </div>

    <!-- 3. Advanced Search & Filter Toolbar -->
    <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-3.5 shadow-xs">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
        
        <!-- Live Search -->
        <div class="lg:col-span-2 relative">
          <input
            v-model="searchQuery"
            @input="debounceSearch"
            type="text"
            :placeholder="themeStore.locale === 'bn' ? 'শিক্ষার্থীর নাম, ফোন, ইমেইল বা কোর্স দিয়ে খুঁজুন...' : 'Search by student name, phone, email, or course...'"
            class="w-full px-4 py-2.5 pl-9 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:outline-none focus:border-[#D4AF37] transition-colors"
          />
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)]">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </span>
          <button
            v-if="searchQuery"
            @click="searchQuery = ''; fetchEnrollments()"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer"
          >✕</button>
        </div>

        <!-- Course Filter -->
        <div>
          <select
            v-model="courseFilter"
            @change="fetchEnrollments"
            class="w-full px-3 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-medium focus:outline-none focus:border-[#D4AF37] cursor-pointer"
          >
            <option value="all">{{ themeStore.locale === 'bn' ? 'সকল কোর্স' : 'All Courses' }}</option>
            <option v-for="c in coursesList" :key="c.id" :value="c.id">
              {{ themeStore.locale === 'bn' ? (c.title_bn || c.title_en) : (c.title_en || c.title_bn) }}
            </option>
          </select>
        </div>

        <!-- Status Filter -->
        <div>
          <select
            v-model="statusFilter"
            @change="fetchEnrollments"
            class="w-full px-3 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-medium focus:outline-none focus:border-[#D4AF37] cursor-pointer"
          >
            <option value="all">{{ themeStore.locale === 'bn' ? 'সকল স্ট্যাটাস' : 'All Statuses' }}</option>
            <option value="active">{{ themeStore.locale === 'bn' ? 'সক্রিয় (Active)' : 'Active' }}</option>
            <option value="completed">{{ themeStore.locale === 'bn' ? 'সম্পন্ন (Completed)' : 'Completed' }}</option>
            <option value="suspended">{{ themeStore.locale === 'bn' ? 'স্থগিত (Suspended)' : 'Suspended' }}</option>
            <option value="cancelled">{{ themeStore.locale === 'bn' ? 'বাতিল (Cancelled)' : 'Cancelled' }}</option>
          </select>
        </div>

      </div>

      <div class="flex items-center justify-between pt-2 border-t border-[var(--border-subtle)] text-[11px]">
        <span class="text-[var(--text-muted)] font-medium">
          {{ formatNumber(pagination.total || enrollments.length, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি এনরোলমেন্ট রেকর্ড পাওয়া গেছে' : 'enrollments found' }}
        </span>
      </div>
    </div>

    <!-- 4. Loading Skeleton -->
    <div v-if="loading" class="space-y-3">
      <div v-for="i in 5" :key="i" class="h-20 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
    </div>

    <!-- 5. Empty State -->
    <div v-else-if="enrollments.length === 0" class="text-center py-16 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-8 space-y-4">
      <div class="w-14 h-14 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[#D4AF37] flex items-center justify-center mx-auto shadow-sm">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
      </div>
      <p class="text-sm font-bold text-[var(--text-primary)]">
        {{ themeStore.locale === 'bn' ? 'কোনো শিক্ষার্থী এনরোলমেন্ট পাওয়া যায়নি।' : 'No student enrollments found.' }}
      </p>
      <p class="text-xs text-[var(--text-secondary)] max-w-sm mx-auto">
        {{ themeStore.locale === 'bn'
          ? 'নতুন শিক্ষার্থীকে যেকোনো কোর্সে সরাসরি ভর্তি করতে ওপরের বোতামে ক্লিক করুন।'
          : 'Click the button above to manually enroll a student into a course and batch.' }}
      </p>
      <button
        @click="openEnrollModal()"
        class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 text-xs font-bold transition-all cursor-pointer shadow-md"
      >
        + {{ themeStore.locale === 'bn' ? 'শিক্ষার্থী এনরোল করুন' : 'Enroll Student Now' }}
      </button>
    </div>

    <!-- 6. Enrollments Table View -->
    <div v-else class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-2xl overflow-hidden shadow-sm">
      <div class="table-responsive-container">
        <table class="w-full text-left text-xs min-w-[900px]">
          <thead class="bg-[var(--bg-elevated)] border-b border-[var(--border-subtle)] text-[var(--text-secondary)] uppercase tracking-wider font-extrabold text-[10px]">
            <tr>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'শিক্ষার্থী' : 'Student' }}</th>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'কোর্স ও ব্যাচ' : 'Course & Batch' }}</th>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'অগ্রগতি (Progress)' : 'Progress' }}</th>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'ভর্তির তারিখ' : 'Enrolled At' }}</th>
              <th class="py-3.5 px-5">{{ themeStore.locale === 'bn' ? 'স্ট্যাটাস' : 'Status' }}</th>
              <th class="py-3.5 px-5 text-right">{{ $t('common.actions') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-subtle)]">
            <tr
              v-for="enr in enrollments"
              :key="enr.id"
              class="hover:bg-[var(--bg-elevated)]/50 transition-colors"
            >
              <!-- 1. Student Info -->
              <td class="py-4 px-5">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-[#D4AF37]/15 border border-[var(--border-accent)] text-[#D4AF37] flex items-center justify-center font-bold text-sm shrink-0">
                    {{ enr.user?.name?.charAt(0) || 'S' }}
                  </div>
                  <div>
                    <div class="font-bold text-[var(--text-primary)] text-sm">
                      {{ enr.user?.name || 'Student' }}
                    </div>
                    <div class="text-[11px] font-mono text-[var(--text-secondary)]">
                      {{ enr.user?.phone || enr.user?.email }}
                    </div>
                  </div>
                </div>
              </td>

              <!-- 2. Course & Batch -->
              <td class="py-4 px-5">
                <div class="space-y-1">
                  <div class="font-bold text-[var(--text-primary)] text-xs">
                    {{ themeStore.locale === 'bn' ? (enr.course?.title_bn || enr.course?.title_en) : (enr.course?.title_en || enr.course?.title_bn) }}
                  </div>
                  <div v-if="enr.batch" class="flex items-center gap-1.5 text-[11px] text-[var(--brand-gold)] font-medium">
                    <span class="px-2 py-0.5 rounded bg-[var(--bg-elevated)] border border-[var(--border-subtle)]">
                      {{ themeStore.locale === 'bn' ? (enr.batch.title_bn || enr.batch.title_en || enr.batch.batch_number) : (enr.batch.title_en || enr.batch.title_bn || enr.batch.batch_number) }}
                    </span>
                    <span v-if="enr.batch.schedule_day_time" class="text-[10px] text-[var(--text-muted)] truncate max-w-[150px]">
                      {{ enr.batch.schedule_day_time }}
                    </span>
                  </div>
                  <div v-else class="text-[11px] text-[var(--text-muted)] italic">
                    {{ themeStore.locale === 'bn' ? 'কোনো ব্যাচ নির্ধারিত নেই' : 'No batch assigned' }}
                  </div>
                </div>
              </td>

              <!-- 3. Progress Bar -->
              <td class="py-4 px-5 whitespace-nowrap">
                <div class="w-32 space-y-1">
                  <div class="flex items-center justify-between text-[10px] font-bold">
                    <span class="text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'সম্পন্ন' : 'Completed' }}</span>
                    <span class="text-emerald-500 font-mono">{{ formatNumber(Number(enr.progress_percentage || 0).toFixed(0), themeStore.locale) }}%</span>
                  </div>
                  <div class="w-full h-1.5 bg-[var(--bg-deep)] rounded-full overflow-hidden border border-[var(--border-subtle)]">
                    <div
                      class="h-full bg-gradient-to-r from-[#D4AF37] to-emerald-500 rounded-full transition-all duration-500"
                      :style="{ width: `${enr.progress_percentage || 0}%` }"
                    ></div>
                  </div>
                </div>
              </td>

              <!-- 4. Enrolled Date -->
              <td class="py-4 px-5 whitespace-nowrap text-xs text-[var(--text-secondary)] font-mono">
                {{ formatDate(enr.enrolled_at || enr.created_at) }}
              </td>

              <!-- 5. Status Pill -->
              <td class="py-4 px-5 whitespace-nowrap">
                <select
                  :value="enr.status"
                  @change="updateStatus(enr.id, ($event.target as HTMLSelectElement).value)"
                  class="px-2.5 py-1 rounded-xl border text-[11px] font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer transition-colors"
                  :class="getStatusSelectClass(enr.status)"
                >
                  <option value="active">{{ themeStore.locale === 'bn' ? 'সক্রিয় (Active)' : 'Active' }}</option>
                  <option value="completed">{{ themeStore.locale === 'bn' ? 'সম্পন্ন (Completed)' : 'Completed' }}</option>
                  <option value="suspended">{{ themeStore.locale === 'bn' ? 'স্থগিত (Suspended)' : 'Suspended' }}</option>
                  <option value="cancelled">{{ themeStore.locale === 'bn' ? 'বাতিল (Cancelled)' : 'Cancelled' }}</option>
                </select>
              </td>

              <!-- 6. Actions -->
              <td class="py-4 px-5 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-1.5">
                  <button
                    @click="openEditModal(enr)"
                    class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[var(--border-subtle)] text-[var(--text-primary)] font-bold text-[11px] border border-[var(--border-subtle)] transition-colors cursor-pointer"
                    :title="themeStore.locale === 'bn' ? 'ব্যাচ ও তথ্য পরিবর্তন' : 'Edit Batch / Status'"
                  >
                    {{ themeStore.locale === 'bn' ? 'এডিট' : 'Edit' }}
                  </button>

                  <button
                    @click="deleteEnrollment(enr.id)"
                    class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-500/10 transition-colors cursor-pointer"
                    :title="themeStore.locale === 'bn' ? 'এনরোলমেন্ট বাতিল করুন' : 'Cancel Enrollment'"
                  >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" class="p-4 border-t border-[var(--border-subtle)] flex items-center justify-between text-xs text-[var(--text-secondary)]">
        <span>
          {{ themeStore.locale === 'bn' ? 'পৃষ্ঠা' : 'Page' }} {{ formatNumber(pagination.current_page, themeStore.locale) }} / {{ formatNumber(pagination.last_page, themeStore.locale) }}
        </span>
        
        <div class="flex items-center gap-2">
          <button
            :disabled="pagination.current_page <= 1"
            @click="changePage(pagination.current_page - 1)"
            class="px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] disabled:opacity-50 cursor-pointer font-bold"
          >
            ← {{ themeStore.locale === 'bn' ? 'পূর্ববর্তী' : 'Previous' }}
          </button>
          <button
            :disabled="pagination.current_page >= pagination.last_page"
            @click="changePage(pagination.current_page + 1)"
            class="px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] disabled:opacity-50 cursor-pointer font-bold"
          >
            {{ themeStore.locale === 'bn' ? 'পরবর্তী' : 'Next' }} →
          </button>
        </div>
      </div>
    </div>

    <!-- 7. Manual Enrollment Modal (Create New Student or Select Existing) -->
    <AppModal
      v-model="isEnrollModalOpen"
      :title="themeStore.locale === 'bn' ? 'নতুন শিক্ষার্থী এনরোল করুন' : 'Enroll Student to Course'"
      size="lg"
    >
      <form @submit.prevent="submitEnrollment" class="space-y-5">
        
        <!-- Student Selection Mode Toggle -->
        <div class="flex items-center gap-2 p-1.5 bg-[var(--bg-deep)] rounded-xl border border-[var(--border-subtle)] text-xs">
          <button
            type="button"
            @click="enrollMode = 'new'"
            :class="[
              'flex-1 py-2 rounded-lg font-bold transition-all cursor-pointer text-center',
              enrollMode === 'new' ? 'bg-[var(--bg-surface)] text-[#D4AF37] border border-[var(--border-accent)] shadow-xs' : 'text-[var(--text-secondary)]'
            ]"
          >
            {{ themeStore.locale === 'bn' ? '১. নতুন শিক্ষার্থীর তথ্য' : '1. New Student' }}
          </button>
          <button
            type="button"
            @click="enrollMode = 'existing'"
            :class="[
              'flex-1 py-2 rounded-lg font-bold transition-all cursor-pointer text-center',
              enrollMode === 'existing' ? 'bg-[var(--bg-surface)] text-[#D4AF37] border border-[var(--border-accent)] shadow-xs' : 'text-[var(--text-secondary)]'
            ]"
          >
            {{ themeStore.locale === 'bn' ? '২. নিবন্ধিত শিক্ষার্থী খুঁজুন' : '2. Existing Student' }}
          </button>
        </div>

        <!-- Mode A: New Student Inputs -->
        <div v-if="enrollMode === 'new'" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <AppInput
              v-model="enrollForm.student_name"
              :label="themeStore.locale === 'bn' ? 'শিক্ষার্থীর পূর্ণ নাম *' : 'Student Full Name *'"
              placeholder="e.g. Md. Tanvir Hasan"
              required
            />
          </div>

          <div>
            <AppInput
              v-model="enrollForm.student_phone"
              :label="themeStore.locale === 'bn' ? 'মোবাইল / WhatsApp নম্বর *' : 'Phone / WhatsApp *'"
              placeholder="01805464293"
              required
            />
          </div>

          <div>
            <AppInput
              v-model="enrollForm.student_email"
              :label="themeStore.locale === 'bn' ? 'ইমেইল অ্যাড্রেস (ঐচ্ছিক)' : 'Email Address (Optional)'"
              type="email"
              placeholder="student@example.com"
            />
          </div>
        </div>

        <!-- Mode B: Existing Student Autocomplete -->
        <div v-else class="space-y-2">
          <label class="block text-xs font-bold text-[var(--text-secondary)]">
            {{ themeStore.locale === 'bn' ? 'শিক্ষার্থী খুঁজুন (নাম / ফোন / ইমেইল) *' : 'Search Student (Name / Phone / Email) *' }}
          </label>
          <input
            v-model="studentSearchKeyword"
            @input="debounceStudentSearch"
            type="text"
            :placeholder="themeStore.locale === 'bn' ? 'টাইপ করে শিক্ষার্থী খুঁজুন...' : 'Type to search student...'"
            class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
          />

          <!-- Search Results Dropdown -->
          <div v-if="studentSearchResults.length > 0" class="max-h-40 overflow-y-auto rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] divide-y divide-[var(--border-subtle)] text-xs">
            <div
              v-for="s in studentSearchResults"
              :key="s.id"
              @click="selectStudent(s)"
              class="p-2.5 hover:bg-[var(--bg-elevated)] cursor-pointer flex items-center justify-between"
            >
              <div>
                <div class="font-bold text-[var(--text-primary)]">{{ s.name }}</div>
                <div class="text-[10px] text-[var(--text-secondary)]">{{ s.phone || s.email }}</div>
              </div>
              <span v-if="enrollForm.user_id === s.id" class="text-[#D4AF37] font-bold">✓ নির্বাচিত</span>
            </div>
          </div>

          <div v-if="selectedStudentObject" class="p-3 rounded-xl bg-[var(--brand-gold-subtle)] border border-[var(--border-accent)] flex items-center justify-between text-xs">
            <div>
              <span class="text-[10px] font-bold text-[#D4AF37] uppercase">নির্বাচিত শিক্ষার্থী:</span>
              <div class="font-bold text-[var(--text-primary)]">{{ selectedStudentObject.name }} ({{ selectedStudentObject.phone }})</div>
            </div>
            <button type="button" @click="clearSelectedStudent" class="text-xs text-rose-500 hover:underline">রিসেট</button>
          </div>
        </div>

        <!-- Course & Batch Selection -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-[var(--border-subtle)]">
          <div>
            <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1.5">
              {{ themeStore.locale === 'bn' ? 'কোর্স নির্বাচন করুন *' : 'Select Course *' }}
            </label>
            <select
              v-model="enrollForm.course_id"
              @change="onCourseSelectChange"
              required
              class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer"
            >
              <option value="" disabled>{{ themeStore.locale === 'bn' ? 'কোর্স নির্বাচন করুন' : 'Choose Course' }}</option>
              <option v-for="c in coursesList" :key="c.id" :value="c.id">
                {{ themeStore.locale === 'bn' ? (c.title_bn || c.title_en) : (c.title_en || c.title_bn) }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1.5">
              {{ themeStore.locale === 'bn' ? 'ব্যাচ নির্বাচন করুন' : 'Select Batch' }}
            </label>
            <select
              v-model="enrollForm.batch_id"
              class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer"
            >
              <option value="">{{ themeStore.locale === 'bn' ? 'চলতি ব্যাচ (স্বয়ংক্রিয়)' : 'Current Enrolling Batch' }}</option>
              <option v-for="b in courseBatches" :key="b.id" :value="b.id">
                {{ themeStore.locale === 'bn' ? (b.title_bn || b.title_en || b.batch_number) : (b.title_en || b.title_bn || b.batch_number) }} 
                ({{ b.enrolled_students || 0 }}/{{ b.seat_capacity || 30 }} সিট)
              </option>
            </select>
          </div>
        </div>

        <!-- Fee & Notes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <AppInput
              v-model="enrollForm.fee_amount"
              :label="themeStore.locale === 'bn' ? 'ভর্তি ফি (টাকা)' : 'Course Fee (BDT)'"
              type="number"
              placeholder="e.g. 5000"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1.5">
              {{ themeStore.locale === 'bn' ? 'পেমেন্ট মেথড' : 'Payment Method' }}
            </label>
            <select
              v-model="enrollForm.payment_method"
              class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer"
            >
              <option value="manual_admin">অফিস ক্যাশ / সরাসরি (Office Cash)</option>
              <option value="bkash">bKash</option>
              <option value="nagad">Nagad</option>
              <option value="bank_transfer">ব্যাংক ট্রান্সফার (Bank Transfer)</option>
            </select>
          </div>
        </div>

        <div>
          <AppInput
            v-model="enrollForm.notes"
            :label="themeStore.locale === 'bn' ? 'অ্যাডমিন নোট (ঐচ্ছিক)' : 'Internal Notes'"
            placeholder="e.g. বিশেষ স্কলারশিপে ভর্তি নিশ্চিত করা হলো..."
          />
        </div>

        <!-- Modal Footer Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[var(--border-subtle)]">
          <button
            type="button"
            @click="isEnrollModalOpen = false"
            class="px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-xs font-bold transition-all cursor-pointer"
          >
            {{ $t('common.cancel') }}
          </button>
          <button
            type="submit"
            :disabled="submitting"
            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#D4AF37] text-slate-950 font-black text-xs hover:shadow-lg transition-all cursor-pointer disabled:opacity-50"
          >
            {{ submitting ? (themeStore.locale === 'bn' ? 'ভর্তি হচ্ছে...' : 'Enrolling...') : (themeStore.locale === 'bn' ? 'ভর্তি নিশ্চিত করুন' : 'Confirm Enrollment') }}
          </button>
        </div>

      </form>
    </AppModal>

    <!-- 8. Edit Enrollment Modal (Batch & Status) -->
    <AppModal
      v-model="isEditModalOpen"
      :title="themeStore.locale === 'bn' ? 'এনরোলমেন্ট তথ্য আপডেট' : 'Update Enrollment'"
      size="md"
    >
      <form v-if="editingEnrollment" @submit.prevent="submitEditEnrollment" class="space-y-4">
        
        <div class="p-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-1 text-xs">
          <div class="font-bold text-[var(--text-primary)]">{{ editingEnrollment.user?.name }}</div>
          <div class="text-[var(--text-secondary)]">{{ editingEnrollment.course?.title_bn || editingEnrollment.course?.title_en }}</div>
        </div>

        <div>
          <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1.5">
            {{ themeStore.locale === 'bn' ? 'ব্যাচ পরিবর্তন করুন' : 'Change Batch' }}
          </label>
          <select
            v-model="editForm.batch_id"
            class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer"
          >
            <option :value="null">{{ themeStore.locale === 'bn' ? 'কোনো ব্যাচ ছাড়া' : 'None' }}</option>
            <option v-for="b in editBatchesList" :key="b.id" :value="b.id">
              {{ themeStore.locale === 'bn' ? (b.title_bn || b.title_en || b.batch_number) : (b.title_en || b.title_bn || b.batch_number) }}
            </option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1.5">
            {{ themeStore.locale === 'bn' ? 'এনরোলমেন্ট স্ট্যাটাস' : 'Status' }}
          </label>
          <select
            v-model="editForm.status"
            class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold focus:outline-none focus:border-[#D4AF37] cursor-pointer"
          >
            <option value="active">সক্রিয় (Active)</option>
            <option value="completed">সম্পন্ন (Completed)</option>
            <option value="suspended">স্থগিত (Suspended)</option>
            <option value="cancelled">বাতিল (Cancelled)</option>
          </select>
        </div>

        <div>
          <AppInput
            v-model="editForm.progress_percentage"
            :label="themeStore.locale === 'bn' ? 'কোর্স অগ্রগতি (%)' : 'Course Progress (%)'"
            type="number"
            min="0"
            max="100"
          />
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[var(--border-subtle)]">
          <button
            type="button"
            @click="isEditModalOpen = false"
            class="px-4 py-2 rounded-xl bg-[var(--bg-elevated)] text-[var(--text-secondary)] text-xs font-bold cursor-pointer"
          >
            {{ $t('common.cancel') }}
          </button>
          <button
            type="submit"
            :disabled="submitting"
            class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-black text-xs cursor-pointer"
          >
            {{ themeStore.locale === 'bn' ? 'সংরক্ষণ করুন' : 'Save Changes' }}
          </button>
        </div>

      </form>
    </AppModal>

  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { apiClient } from '../../api/client';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import { formatNumber, formatDate } from '../../utils/locale';
import AppModal from '../../components/ui/AppModal.vue';
import AppInput from '../../components/ui/AppInput.vue';

const toast = useToastStore();
const themeStore = useThemeStore();

const enrollments = ref<any[]>([]);
const coursesList = ref<any[]>([]);
const courseBatches = ref<any[]>([]);
const editBatchesList = ref<any[]>([]);
const loading = ref(true);
const submitting = ref(false);

const searchQuery = ref('');
const courseFilter = ref('all');
const statusFilter = ref('all');

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  total: 0,
});

const metrics = reactive({
  total_enrollments: 0,
  active_enrollments: 0,
  completed_enrollments: 0,
  suspended_enrollments: 0,
  enrolled_this_month: 0,
});

// Modal state
const isEnrollModalOpen = ref(false);
const isEditModalOpen = ref(false);
const enrollMode = ref<'new' | 'existing'>('new');
const editingEnrollment = ref<any>(null);

const studentSearchKeyword = ref('');
const studentSearchResults = ref<any[]>([]);
const selectedStudentObject = ref<any>(null);

const enrollForm = reactive({
  user_id: null as number | null,
  student_name: '',
  student_phone: '',
  student_email: '',
  course_id: '' as string | number,
  batch_id: '' as string | number,
  status: 'active',
  fee_amount: '',
  payment_method: 'manual_admin',
  notes: '',
});

const editForm = reactive({
  batch_id: null as number | null,
  status: 'active',
  progress_percentage: 0,
});

let searchDebounceTimer: any = null;
let studentSearchDebounceTimer: any = null;

async function fetchEnrollments(page = 1) {
  try {
    loading.value = true;
    const res = await apiClient.get('/admin/enrollments', {
      params: {
        page,
        search: searchQuery.value || undefined,
        course_id: courseFilter.value !== 'all' ? courseFilter.value : undefined,
        status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
      },
    });

    if (res.data.status === 'success') {
      enrollments.value = res.data.data.data || [];
      pagination.current_page = res.data.data.current_page;
      pagination.last_page = res.data.data.last_page;
      pagination.total = res.data.data.total;

      if (res.data.metrics) {
        Object.assign(metrics, res.data.metrics);
      }
    }
  } catch (err: any) {
    toast.error(themeStore.locale === 'bn' ? 'এনরোলমেন্ট লোড করতে সমস্যা হয়েছে' : 'Failed to load enrollments');
  } finally {
    loading.value = false;
  }
}

async function fetchCourses() {
  try {
    const res = await apiClient.get('/admin/courses');
    if (res.data.status === 'success') {
      const d = res.data.data;
      coursesList.value = d?.courses || d?.data || (Array.isArray(d) ? d : []);
    }
  } catch (e) {
    // silent fallback
  }
}

async function onCourseSelectChange() {
  if (!enrollForm.course_id) {
    courseBatches.value = [];
    return;
  }
  try {
    const res = await apiClient.get(`/admin/courses/${enrollForm.course_id}/batches`);
    if (res.data.status === 'success') {
      courseBatches.value = res.data.data || [];
      const defaultB = courseBatches.value.find((b) => b.status === 'enrolling') || courseBatches.value[0];
      if (defaultB) {
        enrollForm.batch_id = defaultB.id;
      }
    }
  } catch (e) {
    courseBatches.value = [];
  }
}

function debounceSearch() {
  clearTimeout(searchDebounceTimer);
  searchDebounceTimer = setTimeout(() => {
    fetchEnrollments(1);
  }, 350);
}

function debounceStudentSearch() {
  clearTimeout(studentSearchDebounceTimer);
  studentSearchDebounceTimer = setTimeout(async () => {
    if (!studentSearchKeyword.value.trim()) {
      studentSearchResults.value = [];
      return;
    }
    try {
      const res = await apiClient.get('/admin/enrollments/search-students', {
        params: { q: studentSearchKeyword.value },
      });
      studentSearchResults.value = res.data.data || [];
    } catch (e) {
      studentSearchResults.value = [];
    }
  }, 300);
}

function selectStudent(student: any) {
  enrollForm.user_id = student.id;
  selectedStudentObject.value = student;
  studentSearchResults.value = [];
  studentSearchKeyword.value = '';
}

function clearSelectedStudent() {
  enrollForm.user_id = null;
  selectedStudentObject.value = null;
}

function openEnrollModal(courseId?: number, batchId?: number) {
  enrollMode.value = 'new';
  selectedStudentObject.value = null;
  studentSearchResults.value = [];
  studentSearchKeyword.value = '';

  enrollForm.user_id = null;
  enrollForm.student_name = '';
  enrollForm.student_phone = '';
  enrollForm.student_email = '';
  enrollForm.course_id = courseId || (coursesList.value[0]?.id || '');
  enrollForm.batch_id = batchId || '';
  enrollForm.status = 'active';
  enrollForm.fee_amount = '';
  enrollForm.payment_method = 'manual_admin';
  enrollForm.notes = '';

  if (enrollForm.course_id) {
    onCourseSelectChange();
  }

  isEnrollModalOpen.value = true;
}

async function submitEnrollment() {
  try {
    submitting.value = true;
    const res = await apiClient.post('/admin/enrollments', enrollForm);

    if (res.data.status === 'success') {
      toast.success(res.data.message || (themeStore.locale === 'bn' ? 'এনরোলমেন্ট সফল হয়েছে' : 'Enrollment successful'));
      isEnrollModalOpen.value = false;
      fetchEnrollments();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'এনরোলমেন্ট ব্যর্থ হয়েছে' : 'Failed to enroll student'));
  } finally {
    submitting.value = false;
  }
}

async function openEditModal(enr: any) {
  editingEnrollment.value = enr;
  editForm.batch_id = enr.batch_id;
  editForm.status = enr.status;
  editForm.progress_percentage = Number(enr.progress_percentage || 0);

  try {
    const res = await apiClient.get(`/admin/courses/${enr.course_id}/batches`);
    if (res.data.status === 'success') {
      editBatchesList.value = res.data.data || [];
    }
  } catch (e) {
    editBatchesList.value = [];
  }

  isEditModalOpen.value = true;
}

async function submitEditEnrollment() {
  if (!editingEnrollment.value) return;
  try {
    submitting.value = true;
    const res = await apiClient.put(`/admin/enrollments/${editingEnrollment.value.id}`, editForm);

    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'এনরোলমেন্ট তথ্য আপডেট হয়েছে' : 'Enrollment updated successfully');
      isEditModalOpen.value = false;
      fetchEnrollments();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'আপডেট ব্যর্থ হয়েছে' : 'Update failed'));
  } finally {
    submitting.value = false;
  }
}

async function updateStatus(id: number, newStatus: string) {
  try {
    const res = await apiClient.put(`/admin/enrollments/${id}`, { status: newStatus });
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'স্ট্যাটাস আপডেট হয়েছে' : 'Status updated');
      fetchEnrollments();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'স্ট্যাটাস পরিবর্তন ব্যর্থ হয়েছে' : 'Status update failed'));
  }
}

async function deleteEnrollment(id: number) {
  const confirmMsg = themeStore.locale === 'bn'
    ? 'আপনি কি নিশ্চিত যে এই শিক্ষার্থীর এনরোলমেন্ট বাতিল করতে চান?'
    : 'Are you sure you want to cancel this enrollment?';
  if (!confirm(confirmMsg)) return;

  try {
    const res = await apiClient.delete(`/admin/enrollments/${id}`);
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'এনরোলমেন্ট বাতিল করা হয়েছে' : 'Enrollment cancelled');
      fetchEnrollments();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'বাতিল করতে সমস্যা হয়েছে' : 'Cancellation failed'));
  }
}

function changePage(page: number) {
  fetchEnrollments(page);
}

function getStatusSelectClass(status: string) {
  switch (status) {
    case 'active':
      return 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20';
    case 'completed':
      return 'bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-500/20';
    case 'suspended':
      return 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20';
    default:
      return 'bg-slate-500/10 text-slate-500 border-slate-500/20';
  }
}

onMounted(() => {
  fetchEnrollments();
  fetchCourses();
});
</script>
