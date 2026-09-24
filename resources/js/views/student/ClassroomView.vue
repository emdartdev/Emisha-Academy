<template>
  <div v-if="loading" class="py-20 text-center">
    <div class="inline-block animate-spin w-8 h-8 border-4 border-[#D4AF37] border-t-transparent rounded-full"></div>
    <p class="text-xs text-[var(--text-secondary)] mt-4">{{ $t('classroom.preparing') }}</p>
  </div>

  <div v-else-if="course" class="space-y-6">

    <!-- Top Bar: Course Title & Overall Progress -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 sm:p-5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] shadow-sm">
      <div class="space-y-1 min-w-0 w-full sm:w-auto sm:flex-1">
        <router-link to="/student/courses" class="text-xs text-[#D4AF37] font-semibold hover:underline inline-flex items-center gap-1.5">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          <span>{{ $t('classroom.back_to_my_courses') }}</span>
        </router-link>
        <h2 class="text-base sm:text-lg font-bold text-[var(--text-primary)] line-clamp-2 break-words">
          {{ loc(course, 'title') }}
        </h2>
      </div>

      <!-- Progress Meter -->
      <div class="flex items-center gap-3 w-full sm:w-64 shrink-0">
        <div class="flex-1">
          <div class="flex items-center justify-between text-[11px] mb-1">
            <span class="text-[var(--text-muted)]">{{ $t('classroom.overall_progress') }}</span>
            <span class="text-emerald-500 font-bold">{{ formatNumber(Number(enrollment?.progress_percentage || 0).toFixed(0), themeStore.locale) }}%</span>
          </div>
          <div class="w-full bg-[var(--bg-surface)] h-2.5 rounded-full overflow-hidden">
            <div
              class="h-full bg-gradient-to-r from-[#D4AF37] to-emerald-400 rounded-full transition-all duration-500"
              :style="{ width: `${enrollment?.progress_percentage || 0}%` }"
            ></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty curriculum -->
    <div v-if="!currentLesson" class="p-10 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] text-center text-xs text-[var(--text-muted)]">
      {{ t('No lessons have been published for this course yet.', 'এই কোর্সে এখনও কোনো লেসন প্রকাশ করা হয়নি।') }}
    </div>

    <!-- Main Grid: Lesson Stage + Curriculum Sidebar -->
    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

      <!-- Left (2 Cols): Lesson Stage & Details -->
      <div class="lg:col-span-2 space-y-6">

        <!-- ============ VIDEO LESSON ============ -->
        <div v-if="lessonType === 'video'" class="aspect-video w-full rounded-2xl overflow-hidden bg-black border border-[var(--border-subtle)] shadow-2xl relative">
          <iframe
            v-if="currentLesson.video_url && isEmbeddable(currentLesson.video_url)"
            :key="`if-${currentLesson.id}`"
            class="w-full h-full"
            :src="getEmbedUrl(currentLesson.video_url)"
            title="Lesson Video"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
          ></iframe>
          <video
            v-else-if="currentLesson.video_url"
            :key="`v-${currentLesson.id}`"
            ref="videoRef"
            class="w-full h-full object-contain"
            controls
            controlsList="nodownload"
            :src="currentLesson.video_url"
            @loadedmetadata="resumePlayback"
            @pause="savePlayback"
            @ended="savePlayback"
          ></video>
          <div v-else class="w-full h-full flex flex-col items-center justify-center text-[var(--text-muted)] space-y-2 p-4 text-center">
            <svg class="w-12 h-12 text-[var(--text-muted)] opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
            <p class="text-xs">{{ $t('classroom.no_video') }}</p>
          </div>
        </div>

        <!-- ============ TEXT LESSON ============ -->
        <article v-else-if="lessonType === 'text'" class="p-5 sm:p-8 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] shadow-sm">
          <div class="flex items-center gap-2 mb-4 text-[10px] font-bold uppercase tracking-wider text-sky-500">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            <span>{{ t('Reading lesson', 'পাঠ্য লেসন') }}</span>
          </div>
          <div v-if="currentLesson.content" class="rich-content text-sm sm:text-[15px] text-[var(--text-primary)] leading-relaxed" v-html="toDisplayHtml(currentLesson.content)"></div>
          <p v-else class="text-xs text-[var(--text-muted)]">{{ t('Lesson content will be added soon.', 'লেসনের বিষয়বস্তু শীঘ্রই যুক্ত হবে।') }}</p>
        </article>

        <!-- ============ QUIZ LESSON ============ -->
        <section v-else-if="lessonType === 'quiz'" class="p-5 sm:p-7 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] shadow-sm space-y-5">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-purple-500">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              <span>{{ t('Quiz', 'কুইজ') }} · {{ formatNumber(quizQuestions.length, themeStore.locale) }} {{ t('questions', 'টি প্রশ্ন') }}</span>
            </div>
            <span class="text-[11px] px-2.5 py-1 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-secondary)]">
              {{ t('Pass mark', 'পাস মার্ক') }}: <b class="text-[var(--text-primary)]">{{ formatNumber(currentLesson.quiz?.pass_percentage ?? 60, themeStore.locale) }}%</b>
            </span>
          </div>

          <!-- Previous attempt banner -->
          <div
            v-if="currentLesson.last_quiz_attempt && !quizResult"
            class="p-3 rounded-xl text-xs border"
            :class="currentLesson.last_quiz_attempt.passed ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 border-amber-500/30 text-amber-600 dark:text-amber-400'"
          >
            {{ t('Last attempt', 'সর্বশেষ চেষ্টা') }}:
            <b>{{ formatNumber(currentLesson.last_quiz_attempt.score, themeStore.locale) }}/{{ formatNumber(currentLesson.last_quiz_attempt.total, themeStore.locale) }}</b>
            ({{ formatNumber(Math.round(currentLesson.last_quiz_attempt.percentage), themeStore.locale) }}%) —
            {{ currentLesson.last_quiz_attempt.passed ? t('Passed', 'উত্তীর্ণ') : t('Not passed yet', 'এখনও উত্তীর্ণ হননি') }}
          </div>

          <!-- Result summary -->
          <div
            v-if="quizResult"
            class="p-4 rounded-xl border flex flex-col sm:flex-row sm:items-center justify-between gap-3"
            :class="quizResult.passed ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-rose-500/10 border-rose-500/30'"
          >
            <div>
              <p class="text-sm font-black" :class="quizResult.passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500'">
                {{ quizResult.passed ? t('🎉 You passed!', '🎉 আপনি উত্তীর্ণ হয়েছেন!') : t('Not passed — try again', 'উত্তীর্ণ হননি — আবার চেষ্টা করুন') }}
              </p>
              <p class="text-xs text-[var(--text-secondary)] mt-0.5">
                {{ t('Score', 'স্কোর') }}: {{ formatNumber(quizResult.score, themeStore.locale) }}/{{ formatNumber(quizResult.total, themeStore.locale) }}
                ({{ formatNumber(Math.round(quizResult.percentage), themeStore.locale) }}%)
              </p>
            </div>
            <button
              type="button"
              class="px-4 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] hover:border-[#D4AF37] cursor-pointer"
              @click="resetQuiz"
            >
              {{ t('Retake quiz', 'আবার কুইজ দিন') }}
            </button>
          </div>

          <!-- Questions -->
          <ol class="space-y-4">
            <li
              v-for="(q, qIdx) in quizQuestions"
              :key="`${currentLesson.id}-${qIdx}`"
              class="p-4 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-3"
            >
              <p class="text-sm font-bold text-[var(--text-primary)]">
                <span class="text-[#D4AF37] mr-1">{{ formatNumber(qIdx + 1, themeStore.locale) }}.</span>{{ q.question }}
              </p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <label
                  v-for="(opt, oIdx) in q.options"
                  :key="oIdx"
                  class="flex items-center gap-2.5 p-2.5 rounded-lg border text-xs transition-colors"
                  :class="optionClass(qIdx, oIdx)"
                >
                  <input
                    type="radio"
                    class="accent-[#D4AF37] w-4 h-4 shrink-0"
                    :name="`q-${currentLesson.id}-${qIdx}`"
                    :value="oIdx"
                    v-model="quizAnswers[qIdx]"
                    :disabled="!!quizResult"
                  />
                  <span class="text-[var(--text-primary)]">{{ opt }}</span>
                </label>
              </div>
              <p
                v-if="quizResult && reviewFor(qIdx)?.explanation"
                class="text-[11px] text-[var(--text-secondary)] bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-lg p-2.5"
              >
                💡 {{ reviewFor(qIdx)?.explanation }}
              </p>
            </li>
          </ol>

          <div v-if="!quizResult" class="flex items-center justify-between gap-3 pt-1">
            <span class="text-[11px] text-[var(--text-muted)]">
              {{ formatNumber(answeredCount, themeStore.locale) }}/{{ formatNumber(quizQuestions.length, themeStore.locale) }} {{ t('answered', 'টির উত্তর দেওয়া হয়েছে') }}
            </span>
            <AppButton variant="gold" size="md" :loading="submittingQuiz" :disabled="answeredCount === 0" @click="submitQuiz">
              {{ t('Submit answers', 'উত্তর জমা দিন') }}
            </AppButton>
          </div>
        </section>

        <!-- Other lesson types (pdf / audio / live) fall back to media link -->
        <div v-else class="p-6 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] text-center space-y-3">
          <p class="text-xs text-[var(--text-secondary)]">{{ currentLesson.short_description || t('Open the lesson material below.', 'নিচের লিংক থেকে লেসনের উপকরণ দেখুন।') }}</p>
          <a
            v-if="currentLesson.media_url || currentLesson.video_url"
            :href="currentLesson.media_url || currentLesson.video_url"
            target="_blank"
            rel="noopener"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#D4AF37] text-slate-950 text-xs font-bold"
          >{{ t('Open material', 'উপকরণ খুলুন') }}</a>
        </div>

        <!-- Lesson Header & Next Button -->
        <div class="p-4 sm:p-6 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div class="space-y-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="text-[10px] text-[#D4AF37] uppercase tracking-wider font-bold">{{ $t('classroom.current_lesson') }}</span>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold border" :class="typeMeta(lessonType).badge">{{ typeMeta(lessonType).label }}</span>
              <span v-if="currentLesson.is_completed" class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 inline-flex items-center gap-1">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ t('Completed', 'সম্পন্ন') }}</span>
              </span>
            </div>
            <h3 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">
              {{ loc(currentLesson, 'title') }}
            </h3>
            <p v-if="currentLesson.duration" class="text-xs text-[var(--text-muted)] inline-flex items-center gap-1">
              <svg class="w-3.5 h-3.5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span>{{ $t('classroom.duration') }}: {{ currentLesson.duration }}</span>
            </p>
          </div>

          <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
            <button
              v-if="prevLesson"
              type="button"
              class="px-3 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] cursor-pointer"
              @click="selectLesson(prevLesson)"
            >← {{ t('Previous', 'পূর্ববর্তী') }}</button>
            <AppButton
              v-if="lessonType !== 'quiz' || currentLesson.is_completed"
              variant="gold"
              size="md"
              :loading="isCompleting"
              @click="completeAndNext"
              class="touch-target flex-1 sm:flex-none"
            >
              {{ currentLesson.is_completed ? t('Next Lesson →', 'পরবর্তী পাঠে যান →') : $t('classroom.complete_and_next') }}
            </AppButton>
          </div>
        </div>

        <!-- Lesson Tabs: Notes & Summary, Resources -->
        <div class="p-4 sm:p-6 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] space-y-5">
          <div class="flex items-center gap-4 sm:gap-6 border-b border-[var(--border-subtle)] overflow-x-auto no-scrollbar">
            <button
              type="button"
              class="pb-3 -mb-px text-xs sm:text-sm font-bold transition-colors touch-target whitespace-nowrap cursor-pointer inline-flex items-center gap-1.5 border-b-2"
              :class="activeTab === 'notes' ? 'text-[#D4AF37] border-[#D4AF37]' : 'text-[var(--text-secondary)] border-transparent hover:text-[var(--text-primary)]'"
              @click="activeTab = 'notes'"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
              <span>{{ $t('classroom.lesson_notes') }}</span>
            </button>
            <button
              type="button"
              class="pb-3 -mb-px text-xs sm:text-sm font-bold transition-colors touch-target whitespace-nowrap cursor-pointer inline-flex items-center gap-1.5 border-b-2"
              :class="activeTab === 'resources' ? 'text-[#D4AF37] border-[#D4AF37]' : 'text-[var(--text-secondary)] border-transparent hover:text-[var(--text-primary)]'"
              @click="activeTab = 'resources'"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
              <span>{{ $t('classroom.downloadable_resources') }}</span>
              <span v-if="currentLesson.resources?.length" class="px-1.5 py-0.5 rounded-full text-[10px] bg-[#D4AF37]/15 text-[#D4AF37]">{{ formatNumber(currentLesson.resources.length, themeStore.locale) }}</span>
            </button>
          </div>

          <!-- Tab: Notes & Summary -->
          <div v-show="activeTab === 'notes'" class="space-y-5">
            <!-- Instructor summary -->
            <div v-if="lessonSummary" class="p-4 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] space-y-1.5">
              <p class="text-[10px] font-black uppercase tracking-wider text-[#D4AF37]">{{ t('Lesson summary', 'পাঠের সারাংশ') }}</p>
              <div class="rich-content text-xs sm:text-sm text-[var(--text-secondary)] leading-relaxed" v-html="lessonSummary"></div>
            </div>

            <!-- Personal notes editor -->
            <div class="space-y-2">
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                  <p class="text-sm font-bold text-[var(--text-primary)]">{{ t('My notes', 'আমার নোট') }}</p>
                  <p class="text-[11px] text-[var(--text-muted)]">{{ t('Private to you. Saved automatically while you type.', 'শুধু আপনি দেখতে পাবেন। লেখার সাথে সাথে স্বয়ংক্রিয়ভাবে সংরক্ষিত হয়।') }}</p>
                </div>
                <router-link to="/student/notes" class="text-[11px] font-bold text-[#D4AF37] hover:underline">{{ t('All my notes →', 'আমার সব নোট →') }}</router-link>
              </div>

              <div v-if="noteLoading" class="h-56 rounded-2xl bg-[var(--bg-elevated)] animate-pulse"></div>
              <RichTextEditor
                v-else
                v-model="noteContent"
                :placeholder="t('Start writing your notes for this lesson…', 'এই পাঠের জন্য আপনার নোট লেখা শুরু করুন…')"
                min-height="240px"
                @update:model-value="scheduleNoteSave"
              >
                <template #status>
                  <span class="inline-flex items-center gap-1" :class="noteStatusClass">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ noteStatusLabel }}
                  </span>
                </template>
                <template #toolbar-end>
                  <button type="button" class="rte-btn" :title="t('Save now', 'এখনই সংরক্ষণ')" @click="saveNote">
                    <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                  </button>
                  <button type="button" class="rte-btn" :title="t('Download as document', 'ডকুমেন্ট হিসেবে ডাউনলোড')" @click="downloadNote">
                    <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  </button>
                </template>
              </RichTextEditor>
            </div>
          </div>

          <!-- Tab: Resources -->
          <div v-show="activeTab === 'resources'" class="space-y-2">
            <template v-if="currentLesson.resources && currentLesson.resources.length > 0">
              <div
                v-for="res in currentLesson.resources"
                :key="res.id"
                class="flex items-center justify-between p-3 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs gap-3"
              >
                <div class="flex items-center gap-3 min-w-0">
                  <span class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 text-[10px] font-black uppercase" :class="res.resource_type === 'link' ? 'bg-sky-500/10 text-sky-500' : 'bg-[#D4AF37]/10 text-[#D4AF37]'">
                    <svg v-if="res.resource_type === 'link'" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    <template v-else>{{ (res.file_type || 'file').slice(0, 4) }}</template>
                  </span>
                  <div class="min-w-0">
                    <p class="text-[var(--text-primary)] font-bold truncate">{{ res.title }}</p>
                    <p class="text-[10px] text-[var(--text-muted)] truncate">
                      <template v-if="res.description">{{ res.description }} · </template>
                      <template v-if="res.resource_type === 'link'">{{ t('External link', 'এক্সটার্নাল লিংক') }}</template>
                      <template v-else-if="res.file_size_bytes">{{ formatBytes(res.file_size_bytes) }}</template>
                    </p>
                  </div>
                </div>
                <a
                  :href="res.file_path"
                  target="_blank"
                  rel="noopener"
                  :download="res.resource_type === 'link' ? undefined : ''"
                  class="px-3 py-1.5 rounded-lg bg-[#D4AF37]/10 text-[#D4AF37] font-bold hover:bg-[#D4AF37]/20 transition-colors touch-target shrink-0 inline-flex items-center gap-1"
                >
                  <svg v-if="res.resource_type === 'link'" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                  <svg v-else class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  <span>{{ res.resource_type === 'link' ? t('Open', 'খুলুন') : $t('common.download') }}</span>
                </a>
              </div>
            </template>
            <p v-else class="text-xs text-[var(--text-muted)]">{{ $t('classroom.no_resources') }}</p>
          </div>
        </div>

      </div>

      <!-- Right (1 Col): Curriculum Sidebar -->
      <div class="space-y-4 lg:sticky lg:top-20">
        <div class="p-4 sm:p-5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] space-y-4 shadow-xl">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-[var(--text-primary)] flex items-center gap-1.5">
              <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
              <span>{{ $t('classroom.curriculum_index') }}</span>
            </h3>
            <span class="text-[11px] text-[var(--text-muted)]">{{ formatNumber(allLessons.length, themeStore.locale) }} {{ $t('classroom.lessons_count') }}</span>
          </div>

          <div class="space-y-3 max-h-[500px] sm:max-h-[600px] overflow-y-auto pr-1">
            <div
              v-for="mod in course.modules"
              :key="mod.id"
              class="rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] overflow-hidden text-xs"
            >
              <div class="p-3 bg-[var(--bg-surface)] font-bold text-[var(--text-primary)] border-b border-[var(--border-subtle)] flex items-center justify-between gap-2">
                <span class="min-w-0">{{ loc(mod, 'title') }}</span>
                <span class="text-[10px] text-[var(--text-muted)] font-normal shrink-0">{{ formatNumber(mod.completed_lessons || 0, themeStore.locale) }}/{{ formatNumber(mod.lessons?.length || 0, themeStore.locale) }}</span>
              </div>

              <div class="p-2 space-y-1">
                <button
                  v-for="lesson in mod.lessons"
                  :key="lesson.id"
                  type="button"
                  class="w-full text-left p-2.5 rounded-lg flex items-center justify-between transition-colors touch-target cursor-pointer"
                  :class="currentLesson?.id === lesson.id ? 'bg-[#D4AF37]/20 text-[#D4AF37] font-bold border border-[#D4AF37]/40' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-hover)]'"
                  @click="selectLesson(lesson)"
                >
                  <div class="flex items-center gap-2 min-w-0">
                    <span v-if="lesson.is_completed" class="text-emerald-500 shrink-0">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </span>
                    <span v-else class="shrink-0" :class="typeMeta(lesson.lesson_type).icon">
                      <svg v-if="lesson.lesson_type === 'text'" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                      <svg v-else-if="lesson.lesson_type === 'quiz'" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                      <svg v-else class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="6 4 19 12 6 20 6 4"/></svg>
                    </span>
                    <span class="truncate">{{ loc(lesson, 'title') }}</span>
                  </div>
                  <span class="text-[10px] text-[var(--text-muted)] shrink-0 ml-2">{{ lesson.lesson_type === 'quiz' ? t('Quiz', 'কুইজ') : (lesson.duration || '') }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';
import { formatNumber } from '../../utils/locale';
import { toDisplayHtml, htmlToText } from '../../utils/sanitizeHtml';
import AppButton from '../../components/ui/AppButton.vue';
import RichTextEditor from '../../components/ui/RichTextEditor.vue';

const route = useRoute();
const router = useRouter();
const themeStore = useThemeStore();
const toastStore = useToastStore();
const t = (en: string, bn: string) => (themeStore.locale === 'bn' ? bn : en);
const loc = (obj: any, field: string) => (themeStore.locale === 'bn' ? obj?.[`${field}_bn`] : obj?.[`${field}_en`] || obj?.[`${field}_bn`]);

const loading = ref(true);
const isCompleting = ref(false);
const enrollment = ref<any>(null);
const course = ref<any>(null);
const currentLesson = ref<any>(null);
const activeTab = ref<'notes' | 'resources'>('notes');
const videoRef = ref<HTMLVideoElement | null>(null);

const allLessons = computed<any[]>(() => (course.value?.modules || []).flatMap((m: any) => m.lessons || []));
const currentIndex = computed(() => allLessons.value.findIndex((l) => l.id === currentLesson.value?.id));
const prevLesson = computed(() => (currentIndex.value > 0 ? allLessons.value[currentIndex.value - 1] : null));
const nextLesson = computed(() => (currentIndex.value !== -1 && currentIndex.value + 1 < allLessons.value.length ? allLessons.value[currentIndex.value + 1] : null));
const lessonType = computed(() => currentLesson.value?.lesson_type || 'video');

// Summary: instructor short description, plus the lesson content for non-text lessons
const lessonSummary = computed(() => {
  const l = currentLesson.value;
  if (!l) return '';
  const parts: string[] = [];
  if (l.short_description) parts.push(toDisplayHtml(l.short_description));
  if (lessonType.value !== 'text' && l.content) parts.push(toDisplayHtml(l.content));
  return parts.join('');
});

function typeMeta(type: string) {
  switch (type) {
    case 'text':
      return { label: t('Text', 'টেক্সট'), badge: 'bg-sky-500/10 text-sky-500 border-sky-500/20', icon: 'text-sky-500' };
    case 'quiz':
      return { label: t('Quiz', 'কুইজ'), badge: 'bg-purple-500/10 text-purple-500 border-purple-500/20', icon: 'text-purple-500' };
    default:
      return { label: t('Video', 'ভিডিও'), badge: 'bg-rose-500/10 text-rose-500 border-rose-500/20', icon: 'text-[var(--text-muted)]' };
  }
}

function formatBytes(bytes: number) {
  if (!bytes) return '';
  const units = ['B', 'KB', 'MB', 'GB'];
  let i = 0;
  let n = bytes;
  while (n >= 1024 && i < units.length - 1) {
    n /= 1024;
    i++;
  }
  return `${n.toFixed(i === 0 ? 0 : 1)} ${units[i]}`;
}

const isEmbeddable = (url: string) =>
  url.includes('youtube.com') || url.includes('youtu.be') || url.includes('vimeo.com');

const getEmbedUrl = (url: string) => {
  if (url.includes('youtube.com/watch?v=')) {
    const id = url.split('v=')[1]?.split('&')[0];
    return `https://www.youtube.com/embed/${id}`;
  }
  if (url.includes('youtu.be/')) {
    const id = url.split('youtu.be/')[1]?.split('?')[0];
    return `https://www.youtube.com/embed/${id}`;
  }
  if (url.includes('vimeo.com/') && !url.includes('player.vimeo.com')) {
    const id = url.split('vimeo.com/')[1]?.split('?')[0];
    return `https://player.vimeo.com/video/${id}`;
  }
  return url;
};

// ---------------- Video playback resume ----------------
function resumePlayback() {
  const pos = currentLesson.value?.last_playback_position || 0;
  if (videoRef.value && pos > 5 && pos < (videoRef.value.duration || 0) - 5) {
    videoRef.value.currentTime = pos;
  }
}

async function savePlayback() {
  if (!videoRef.value || !currentLesson.value || !course.value) return;
  const position = Math.floor(videoRef.value.currentTime || 0);
  currentLesson.value.last_playback_position = position;
  try {
    await apiClient.post(`/student/courses/${course.value.id}/lessons/${currentLesson.value.id}/progress`, { position_seconds: position });
  } catch {
    // non-blocking
  }
}

// ---------------- Lesson navigation ----------------
async function selectLesson(lesson: any) {
  if (!lesson || lesson.id === currentLesson.value?.id) return;
  await flushNoteSave();
  if (videoRef.value) savePlayback();
  currentLesson.value = lesson;
  router.replace({ query: { ...route.query, lesson: String(lesson.id) } });
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function applyProgress(percentage: number | undefined) {
  if (enrollment.value && percentage !== undefined) {
    enrollment.value.progress_percentage = percentage;
  }
  course.value?.modules?.forEach((m: any) => {
    m.completed_lessons = (m.lessons || []).filter((l: any) => l.is_completed).length;
  });
}

async function completeAndNext() {
  if (!currentLesson.value || !course.value) return;

  if (currentLesson.value.is_completed) {
    if (nextLesson.value) selectLesson(nextLesson.value);
    return;
  }

  isCompleting.value = true;
  try {
    const res = await apiClient.post(`/student/courses/${course.value.id}/lessons/${currentLesson.value.id}/complete`);
    currentLesson.value.is_completed = true;
    applyProgress(res.data.data?.progress_percentage);
    toastStore.success(res.data.message || t('Lesson completed.', 'পাঠ সম্পন্ন হয়েছে।'));
    if (nextLesson.value) selectLesson(nextLesson.value);
  } catch (err: any) {
    toastStore.error(err.response?.data?.message || t('Failed to save progress.', 'অগ্রগতি সংরক্ষণ করা যায়নি।'));
  } finally {
    isCompleting.value = false;
  }
}

// ---------------- Quiz ----------------
const quizAnswers = reactive<Record<number, number | null>>({});
const quizResult = ref<any>(null);
const submittingQuiz = ref(false);
const quizQuestions = computed<any[]>(() => currentLesson.value?.quiz?.questions || []);
const answeredCount = computed(() => quizQuestions.value.filter((_, i) => quizAnswers[i] !== undefined && quizAnswers[i] !== null).length);

function resetQuiz() {
  Object.keys(quizAnswers).forEach((k) => delete quizAnswers[Number(k)]);
  quizResult.value = null;
}

function reviewFor(qIdx: number) {
  return quizResult.value?.review?.find((r: any) => r.index === qIdx);
}

function optionClass(qIdx: number, oIdx: number) {
  const review = reviewFor(qIdx);
  if (review) {
    if (oIdx === review.correct_index) return 'border-emerald-500/60 bg-emerald-500/10';
    if (oIdx === review.selected_index) return 'border-rose-500/60 bg-rose-500/10';
    return 'border-[var(--border-subtle)] bg-[var(--bg-surface)] opacity-70';
  }
  return quizAnswers[qIdx] === oIdx
    ? 'border-[#D4AF37] bg-[#D4AF37]/10 cursor-pointer'
    : 'border-[var(--border-subtle)] bg-[var(--bg-surface)] hover:border-[#D4AF37]/50 cursor-pointer';
}

async function submitQuiz() {
  if (!currentLesson.value || !course.value) return;
  if (answeredCount.value < quizQuestions.value.length && !window.confirm(t('Some questions are unanswered. Submit anyway?', 'কিছু প্রশ্নের উত্তর দেওয়া হয়নি। তবুও জমা দেবেন?'))) {
    return;
  }
  submittingQuiz.value = true;
  try {
    const answers = quizQuestions.value.map((_, i) => (quizAnswers[i] ?? null));
    const res = await apiClient.post(`/student/courses/${course.value.id}/lessons/${currentLesson.value.id}/quiz`, { answers });
    const data = res.data.data;
    quizResult.value = data;
    currentLesson.value.last_quiz_attempt = {
      score: data.score,
      total: data.total,
      percentage: data.percentage,
      passed: data.passed,
    };
    if (data.passed) {
      currentLesson.value.is_completed = true;
      applyProgress(data.progress_percentage);
      toastStore.success(res.data.message);
    } else {
      toastStore.error(res.data.message);
    }
  } catch (err: any) {
    toastStore.error(err.response?.data?.message || t('Could not submit the quiz.', 'কুইজ জমা দেওয়া যায়নি।'));
  } finally {
    submittingQuiz.value = false;
  }
}

// ---------------- Personal notes (autosave) ----------------
const noteContent = ref('');
const noteLoading = ref(false);
const noteStatus = ref<'idle' | 'dirty' | 'saving' | 'saved' | 'error'>('idle');
let noteTimer: ReturnType<typeof setTimeout> | null = null;
let noteLessonId: number | null = null;
let lastSavedContent = '';

const noteStatusLabel = computed(() => ({
  idle: t('Not saved yet', 'এখনও সংরক্ষিত হয়নি'),
  dirty: t('Unsaved changes', 'অসংরক্ষিত পরিবর্তন'),
  saving: t('Saving…', 'সংরক্ষণ হচ্ছে…'),
  saved: t('All changes saved', 'সব পরিবর্তন সংরক্ষিত'),
  error: t('Save failed — retrying on next edit', 'সংরক্ষণ ব্যর্থ — আবার চেষ্টা করুন'),
}[noteStatus.value]));

const noteStatusClass = computed(() => ({
  idle: 'text-[var(--text-muted)]',
  dirty: 'text-amber-500',
  saving: 'text-sky-500',
  saved: 'text-emerald-500',
  error: 'text-rose-500',
}[noteStatus.value]));

async function loadNote() {
  if (!currentLesson.value || !course.value) return;
  noteLessonId = currentLesson.value.id;
  noteLoading.value = true;
  try {
    const res = await apiClient.get(`/student/courses/${course.value.id}/lessons/${noteLessonId}/note`);
    if (noteLessonId !== currentLesson.value?.id) return;
    noteContent.value = res.data.data?.content || '';
    lastSavedContent = noteContent.value;
    noteStatus.value = res.data.data ? 'saved' : 'idle';
  } catch {
    noteContent.value = '';
    lastSavedContent = '';
    noteStatus.value = 'idle';
  } finally {
    noteLoading.value = false;
  }
}

function scheduleNoteSave() {
  if (noteContent.value === lastSavedContent) return;
  noteStatus.value = 'dirty';
  if (noteTimer) clearTimeout(noteTimer);
  noteTimer = setTimeout(saveNote, 1200);
}

async function saveNote() {
  if (noteTimer) {
    clearTimeout(noteTimer);
    noteTimer = null;
  }
  if (!course.value || !noteLessonId) return;
  const content = noteContent.value;
  // Don't create empty notes
  if (!htmlToText(content) && !lastSavedContent) {
    noteStatus.value = 'idle';
    return;
  }
  noteStatus.value = 'saving';
  try {
    await apiClient.put(`/student/courses/${course.value.id}/lessons/${noteLessonId}/note`, { content });
    lastSavedContent = content;
    noteStatus.value = noteContent.value === content ? 'saved' : 'dirty';
  } catch {
    noteStatus.value = 'error';
  }
}

async function flushNoteSave() {
  if (noteTimer || noteStatus.value === 'dirty') {
    await saveNote();
  }
}

function downloadNote() {
  const title = loc(currentLesson.value, 'title') || 'note';
  const html = `<!DOCTYPE html><html><head><meta charset="utf-8"><title>${title}</title></head><body><h1>${title}</h1>${noteContent.value}</body></html>`;
  const blob = new Blob(['﻿', html], { type: 'application/msword' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `${title.replace(/[\\/:*?"<>|]+/g, '_').slice(0, 80)}.doc`;
  a.click();
  URL.revokeObjectURL(url);
}

function onBeforeUnload(e: BeforeUnloadEvent) {
  if (noteStatus.value === 'dirty' || noteStatus.value === 'saving') {
    saveNote();
    e.preventDefault();
  }
}

watch(
  () => currentLesson.value?.id,
  (id) => {
    if (!id) return;
    resetQuiz();
    loadNote();
  }
);

// ---------------- Load ----------------
async function fetchClassroom() {
  loading.value = true;
  try {
    const res = await apiClient.get(`/student/courses/${route.params.id}/learn`);
    enrollment.value = res.data.data.enrollment;
    course.value = res.data.data.course;

    const requestedId = Number(route.query.lesson);
    const requested = requestedId ? allLessons.value.find((l) => l.id === requestedId) : null;
    const firstIncomplete = allLessons.value.find((l) => !l.is_completed);
    currentLesson.value = requested || firstIncomplete || allLessons.value[0] || null;
  } catch (err: any) {
    toastStore.error(err.response?.data?.message || t('Failed to load classroom.', 'ক্লাসরুম লোড করা যায়নি।'));
  } finally {
    loading.value = false;
  }
}

// Notification deep-links (?lesson=ID) while already inside the classroom
watch(
  () => route.query.lesson,
  (val) => {
    const id = Number(val);
    if (id && id !== currentLesson.value?.id) {
      const target = allLessons.value.find((l) => l.id === id);
      if (target) selectLesson(target);
    }
  }
);

onMounted(() => {
  fetchClassroom();
  window.addEventListener('beforeunload', onBeforeUnload);
});

onBeforeUnmount(() => {
  window.removeEventListener('beforeunload', onBeforeUnload);
  flushNoteSave();
  if (videoRef.value) savePlayback();
});
</script>
