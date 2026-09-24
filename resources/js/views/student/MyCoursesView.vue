<template>
  <div class="space-y-8">
    <div class="space-y-1">
      <h2 class="text-2xl font-bold text-[var(--text-primary)]">{{ $t('student.my_enrolled_courses') }}</h2>
      <p class="text-xs text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'আপনার সকল ব্যাচ এবং পাঠ্যক্রমের অগ্রগতি এখান থেকে পরিচালনা করুন।' : 'Track and manage your progress across all enrolled batches and curriculums.' }}</p>
    </div>

    <!-- Filter Tabs (Horizontally scrollable on mobile) -->
    <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto no-scrollbar pb-1">
      <button
        class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all touch-target cursor-pointer whitespace-nowrap"
        :class="filterStatus === 'all' ? 'bg-[#D4AF37] text-slate-950 shadow-md' : 'bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-subtle)]'"
        @click="filterStatus = 'all'"
      >
        {{ $t('student.all_courses') }} ({{ formatNumber(enrollments.length, themeStore.locale) }})
      </button>
      <button
        class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all touch-target cursor-pointer whitespace-nowrap"
        :class="filterStatus === 'active' ? 'bg-[#D4AF37] text-slate-950 shadow-md' : 'bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-subtle)]'"
        @click="filterStatus = 'active'"
      >
        {{ $t('student.in_progress') }} ({{ formatNumber(activeEnrollments.length, themeStore.locale) }})
      </button>
      <button
        class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all touch-target cursor-pointer whitespace-nowrap"
        :class="filterStatus === 'completed' ? 'bg-[#D4AF37] text-slate-950 shadow-md' : 'bg-[var(--bg-card)] text-[var(--text-primary)] border border-[var(--border-subtle)]'"
        @click="filterStatus = 'completed'"
      >
        {{ $t('student.completed_courses') }} ({{ formatNumber(completedEnrollments.length, themeStore.locale) }})
      </button>
    </div>

    <!-- Courses Grid -->
    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="i in 3" :key="i" class="h-64 rounded-2xl bg-[var(--bg-elevated)] animate-pulse"></div>
    </div>

    <div v-else-if="filteredEnrollments.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="enr in filteredEnrollments"
        :key="enr.id"
        class="p-5 sm:p-6 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/40 shadow-lg transition-all flex flex-col justify-between space-y-4"
      >
        <div class="space-y-3">
          <div class="flex items-center justify-between text-xs">
            <span class="px-2.5 py-0.5 rounded-full bg-[#D4AF37]/10 text-[#D4AF37] font-bold">
              {{ themeStore.locale === 'bn' ? (enr.batch?.title_bn || enr.batch?.batch_number || 'Batch') : (enr.batch?.title_en || enr.batch?.batch_number || 'Batch') }}
            </span>
            <span class="text-emerald-500 font-bold">{{ formatNumber(Number(enr.progress_percentage).toFixed(0), themeStore.locale) }}%</span>
          </div>

          <h3 class="text-base font-bold text-[var(--text-primary)] line-clamp-2 leading-snug">
            {{ themeStore.locale === 'bn' ? (enr.course?.title_bn || enr.course?.title_en) : (enr.course?.title_en || enr.course?.title_bn) }}
          </h3>

          <p class="text-xs text-[var(--text-muted)]">
            {{ $t('student.schedule') }}: {{ enr.batch?.class_days || (themeStore.locale === 'bn' ? 'শুক্র ও শনি' : 'Fri & Sat') }} ({{ enr.batch?.class_time || '8:00 PM' }})
          </p>
        </div>

        <div class="space-y-3">
          <div class="w-full bg-[var(--bg-surface)] h-2 rounded-full overflow-hidden">
            <div
              class="h-full bg-gradient-to-r from-[#D4AF37] to-emerald-400 rounded-full"
              :style="{ width: `${enr.progress_percentage}%` }"
            ></div>
          </div>

          <router-link
            :to="`/student/courses/${enr.course_id}/learn`"
            class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-bold text-xs text-center block hover:brightness-110 shadow-md transition-all touch-target"
          >
            {{ $t('student.enter_classroom') }}
          </router-link>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-16 p-6 rounded-3xl bg-[var(--bg-card)] border border-[var(--border-subtle)]">
      <p class="text-xs sm:text-sm text-[var(--text-muted)]">{{ $t('common.no_data') }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { formatNumber } from '../../utils/locale';

const themeStore = useThemeStore();
const loading = ref(true);
const enrollments = ref<any[]>([]);
const filterStatus = ref('all');

const activeEnrollments = computed(() => enrollments.value.filter((e) => e.status === 'active'));
const completedEnrollments = computed(() => enrollments.value.filter((e) => e.status === 'completed'));

const filteredEnrollments = computed(() => {
  if (filterStatus.value === 'active') return activeEnrollments.value;
  if (filterStatus.value === 'completed') return completedEnrollments.value;
  return enrollments.value;
});

const fetchCourses = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/student/courses');
    enrollments.value = res.data.data;
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchCourses();
});
</script>
