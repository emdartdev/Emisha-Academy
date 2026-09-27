<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">{{ $t('admin.courses_management') }}</h1>
        <p class="text-sm text-[var(--text-secondary)] mt-1">
          {{ themeStore.locale === 'bn' ? 'কোর্স তৈরি, এডিট, সিলেবাস, মডিউল, লেসন, ব্যাচ, মেন্টর ও রিভিউ সম্পূর্ণ ম্যানেজ করুন।' : 'Create, edit, and manage all course details, curriculum, batches, mentors, and student reviews.' }}
        </p>
      </div>

      <div class="flex flex-wrap items-center gap-3">
        <!-- Manage Categories Button -->
        <router-link
          to="/admin/categories"
          class="px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-deep)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-[var(--text-primary)] font-bold text-xs transition-all inline-flex items-center gap-2 cursor-pointer touch-target shadow-xs"
        >
          <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'ক্যাটাগরি ও ট্র্যাক' : 'Manage Categories' }}</span>
        </router-link>

        <!-- Add New Instructor Button -->
        <button
          type="button"
          @click="openAddInstructorModal"
          class="px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-deep)] border border-[var(--border-subtle)] hover:border-[#D4AF37] text-[var(--text-primary)] font-bold text-xs transition-all inline-flex items-center gap-2 cursor-pointer touch-target shadow-xs"
        >
          <svg class="w-4 h-4 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span>{{ themeStore.locale === 'bn' ? '+ নতুন মেন্টর প্রোফাইল' : '+ New Mentor Profile' }}</span>
        </button>

        <!-- Create Course Button -->
        <button
          type="button"
          @click="openCreateModal"
          class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg hover:shadow-[#D4AF37]/20 transition-all inline-flex items-center gap-2 cursor-pointer touch-target shadow-xs"
        >
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <span>{{ $t('admin.create_course') }}</span>
        </button>
      </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-[var(--bg-surface)] border border-[var(--border-subtle)] p-4 rounded-2xl shadow-xs">
      <div class="w-full sm:w-80">
        <input
          v-model="searchQuery"
          @input="fetchCourses"
          type="text"
          :placeholder="themeStore.locale === 'bn' ? 'কোর্সের নাম দিয়ে খুঁজুন...' : 'Search courses by name...'"
          class="w-full px-4 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] transition-colors"
        />
      </div>

      <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
        <button
          type="button"
          @click="statusFilter = ''; fetchCourses()"
          :class="[
            'px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors cursor-pointer',
            statusFilter === '' ? 'bg-[var(--bg-elevated)] text-[var(--brand-gold)] border border-[var(--border-accent)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
        >
          {{ $t('common.all') }}
        </button>
        <button
          type="button"
          @click="statusFilter = 'published'; fetchCourses()"
          :class="[
            'px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors cursor-pointer',
            statusFilter === 'published' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
        >
          {{ themeStore.locale === 'bn' ? 'প্রকাশিত' : 'Published' }}
        </button>
        <button
          type="button"
          @click="statusFilter = 'draft'; fetchCourses()"
          :class="[
            'px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors cursor-pointer',
            statusFilter === 'draft' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
          ]"
        >
          {{ themeStore.locale === 'bn' ? 'ড্রাফট' : 'Draft' }}
        </button>
      </div>
    </div>

    <!-- Loading Skeleton -->
    <div v-if="loading" class="space-y-3">
      <div v-for="i in 4" :key="i" class="h-24 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] animate-pulse"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="courses.length === 0" class="text-center py-16 bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl p-8">
      <p class="text-sm text-[var(--text-secondary)]">{{ $t('courses.no_courses_found') }}</p>
    </div>

    <!-- Courses Table -->
    <div v-else class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-2xl overflow-hidden shadow-sm">
      <!-- Phone / small tablet: course cards with the full Course Builder action set -->
      <ul class="lg:hidden divide-y divide-[var(--border-subtle)]">
        <li v-for="course in courses" :key="`m-${course.id}`" class="p-4 space-y-3">
          <div class="flex items-start justify-between gap-3">
            <img
              v-if="course.thumbnail"
              :src="course.thumbnail"
              alt=""
              class="w-20 aspect-video rounded-lg object-cover border border-[var(--border-subtle)] shrink-0"
              loading="lazy"
            />
            <div class="min-w-0 flex-1">
              <p class="text-sm font-black text-[var(--text-primary)] break-words">{{ getLocalized(course, 'title', themeStore.locale) }}</p>
              <p class="text-[11px] text-[var(--text-muted)] font-mono break-all">{{ course.slug }}</p>
            </div>
            <span
              v-if="course.is_published || course.status === 'published'"
              class="shrink-0 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20"
            >{{ themeStore.locale === 'bn' ? 'প্রকাশিত' : 'Published' }}</span>
            <span v-else class="shrink-0 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
              {{ themeStore.locale === 'bn' ? 'ড্রাফট' : 'Draft' }}
            </span>
          </div>

          <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-[var(--text-secondary)]">
            <span class="font-bold text-[var(--text-primary)]">{{ formatCurrency(course.sale_price || course.regular_price, themeStore.locale) }}</span>
            <span v-if="course.sale_price && course.sale_price < course.regular_price" class="line-through">{{ formatCurrency(course.regular_price, themeStore.locale) }}</span>
            <span>• {{ getLocalized(course.category, 'name', themeStore.locale) || getLocalized(course.category, 'title', themeStore.locale) || '—' }}</span>
            <span>• {{ formatNumber(course.batches?.length || 0, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি ব্যাচ' : 'batches' }}</span>
            <span>• {{ formatNumber(course.instructors?.length || (course.instructor ? 1 : 0), themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'জন মেন্টর' : 'mentors' }}</span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs font-bold">
            <button type="button" @click="openCurriculumModal(course)" class="col-span-2 sm:col-span-1 min-h-[44px] rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-600 dark:text-blue-400 cursor-pointer">
              📚 {{ themeStore.locale === 'bn' ? 'সিলেবাস / লেসন' : 'Syllabus / Lessons' }}
            </button>
            <button type="button" @click="openEditCourseModal(course)" class="min-h-[44px] rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--brand-gold)] cursor-pointer">
              ✎ {{ themeStore.locale === 'bn' ? 'এডিট' : 'Edit' }}
            </button>
            <button type="button" @click="openBatchesModal(course)" class="min-h-[44px] rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-emerald-600 dark:text-emerald-400 cursor-pointer">
              {{ themeStore.locale === 'bn' ? 'ব্যাচ' : 'Batches' }}
            </button>
            <button type="button" @click="openAssignMentorsModal(course)" class="min-h-[44px] rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-purple-500 dark:text-purple-400 cursor-pointer">
              {{ themeStore.locale === 'bn' ? 'মেন্টর' : 'Mentors' }}
            </button>
            <button type="button" @click="openReviewsModal(course)" class="min-h-[44px] rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-amber-500 cursor-pointer">
              {{ themeStore.locale === 'bn' ? 'রিভিউ' : 'Reviews' }}
            </button>
            <router-link :to="`/courses/${course.slug}`" target="_blank" class="min-h-[44px] rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-secondary)] flex items-center justify-center">
              {{ themeStore.locale === 'bn' ? 'লাইভ পেজ' : 'Live page' }} ↗
            </router-link>
            <button type="button" @click="confirmDeleteCourse(course)" class="min-h-[44px] rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500 cursor-pointer">
              {{ themeStore.locale === 'bn' ? 'মুছুন' : 'Delete' }}
            </button>
          </div>
        </li>
      </ul>

      <div class="table-responsive-container hidden lg:block">
        <table class="w-full text-left text-xs min-w-[1000px]">
          <thead class="bg-[var(--bg-elevated)] border-b border-[var(--border-subtle)] text-[var(--text-secondary)] uppercase tracking-wider">
            <tr>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'কোর্স বিবরণ' : 'Course Details' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'ক্যাটাগরি' : 'Category' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'মূল্য (রেগুলার / অফার)' : 'Price (Regular / Sale)' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'মেন্টর' : 'Mentors' }}</th>
              <th class="py-4 px-6">{{ themeStore.locale === 'bn' ? 'ব্যাচ ও শিডিউল' : 'Batches' }}</th>
              <th class="py-4 px-6">{{ $t('common.status') }}</th>
              <th class="py-4 px-6 text-right">{{ $t('common.actions') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-subtle)]">
            <tr v-for="course in courses" :key="course.id" class="hover:bg-[var(--bg-elevated)]/40 transition-colors">
              <!-- Title & Slug -->
              <td class="py-4 px-6">
                <div class="flex items-start gap-3">
                <div class="w-20 aspect-video rounded-lg overflow-hidden border border-[var(--border-subtle)] bg-[var(--bg-elevated)] shrink-0 flex items-center justify-center">
                  <img v-if="course.thumbnail" :src="course.thumbnail" alt="" class="w-full h-full object-cover" loading="lazy" />
                  <svg v-else class="w-5 h-5 text-[var(--text-muted)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <div class="min-w-0">
                <div class="font-bold text-[var(--text-primary)] text-sm">
                  {{ getLocalized(course, 'title', themeStore.locale) }}
                </div>
                <div class="text-[11px] text-[var(--text-secondary)] font-mono mt-0.5">{{ course.slug }}</div>
                <div class="flex items-center gap-2 mt-1">
                  <span v-if="course.is_featured" class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30">
                    Featured
                  </span>
                  <span v-if="course.duration_weeks || course.total_hours" class="text-[11px] text-[var(--text-muted)]">
                    <template v-if="course.duration_weeks">{{ course.duration_weeks }} {{ themeStore.locale === 'bn' ? 'সপ্তাহ' : 'wks' }}</template>
                    <template v-if="course.duration_weeks && course.total_hours"> • </template>
                    <template v-if="course.total_hours">{{ course.total_hours }}h lab</template>
                  </span>
                </div>
                </div>
                </div>
              </td>

              <!-- Category -->
              <td class="py-4 px-6 text-[var(--text-primary)] whitespace-nowrap">
                {{ getLocalized(course.category, 'name', themeStore.locale) || getLocalized(course.category, 'title', themeStore.locale) || '—' }}
              </td>

              <!-- Price -->
              <td class="py-4 px-6 whitespace-nowrap">
                <span class="font-bold text-[var(--text-primary)]">
                  {{ formatCurrency(course.sale_price || course.regular_price, themeStore.locale) }}
                </span>
                <span v-if="course.sale_price && course.sale_price < course.regular_price" class="text-[10px] text-[var(--text-secondary)] line-through ml-1.5">
                  {{ formatCurrency(course.regular_price, themeStore.locale) }}
                </span>
              </td>

              <!-- Instructors -->
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  <div class="flex -space-x-2 overflow-hidden">
                    <template v-if="course.instructors && course.instructors.length > 0">
                      <img
                        v-for="inst in course.instructors.slice(0, 3)"
                        :key="inst.id"
                        :src="inst.avatar || getInitialsAvatar(inst.name_en || inst.name_bn || 'Mentor')"
                        :title="themeStore.locale === 'bn' ? inst.name_bn : inst.name_en"
                        class="inline-block h-7 w-7 rounded-full ring-2 ring-[var(--bg-surface)] object-cover bg-slate-900"
                        @error="onImageError($event, 'avatar', inst.name_en || inst.name_bn)"
                      />
                    </template>
                    <template v-else-if="course.instructor">
                      <img
                        :src="course.instructor.avatar || getInitialsAvatar(course.instructor.name_en || course.instructor.name_bn || 'Mentor')"
                        :title="themeStore.locale === 'bn' ? course.instructor.name_bn : course.instructor.name_en"
                        class="inline-block h-7 w-7 rounded-full ring-2 ring-[var(--bg-surface)] object-cover bg-slate-900"
                        @error="onImageError($event, 'avatar', course.instructor.name_en || course.instructor.name_bn)"
                      />
                    </template>
                    <span v-else class="text-[11px] text-[var(--text-muted)] italic">
                      {{ themeStore.locale === 'bn' ? 'মেন্টর নেই' : 'No mentors' }}
                    </span>
                  </div>

                  <span v-if="course.instructors && course.instructors.length > 0" class="text-[11px] font-semibold text-[var(--text-primary)]">
                    {{ course.instructors.length }} {{ themeStore.locale === 'bn' ? 'জন' : 'mentors' }}
                  </span>
                </div>
              </td>

              <!-- Batches -->
              <td class="py-4 px-6 whitespace-nowrap">
                <span class="px-2.5 py-1 rounded-md bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] font-mono text-[11px]">
                  {{ formatNumber(course.batches?.length || 0, themeStore.locale) }} {{ themeStore.locale === 'bn' ? 'টি ব্যাচ' : 'Batches' }}
                </span>
              </td>

              <!-- Status -->
              <td class="py-4 px-6 whitespace-nowrap">
                <span v-if="course.is_published || course.status === 'published'" class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                  {{ themeStore.locale === 'bn' ? 'প্রকাশিত' : 'Published' }}
                </span>
                <span v-else class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                  {{ themeStore.locale === 'bn' ? 'ড্রাফট' : 'Draft' }}
                </span>
              </td>

              <!-- Actions -->
              <td class="py-4 px-6 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                  <!-- Edit Full Course Button -->
                  <button
                    type="button"
                    @click="openEditCourseModal(course)"
                    class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[#D4AF37]/15 hover:border-[#D4AF37]/50 text-[var(--brand-gold)] text-[11px] font-bold border border-[var(--border-subtle)] transition-colors inline-flex items-center gap-1.5 touch-target cursor-pointer"
                    title="Edit Course Details & Learn Points"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'এডিট' : 'Edit' }}</span>
                  </button>

                  <!-- Curriculum (Modules & Lessons) -->
                  <button
                    type="button"
                    @click="openCurriculumModal(course)"
                    class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-blue-500/15 hover:border-blue-500/40 text-blue-500 dark:text-blue-400 text-[11px] font-bold border border-[var(--border-subtle)] transition-colors inline-flex items-center gap-1.5 touch-target cursor-pointer"
                    title="Manage Syllabus, Modules & Lessons"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'সিলেবাস' : 'Syllabus' }}</span>
                  </button>

                  <!-- Batches Manager -->
                  <button
                    type="button"
                    @click="openBatchesModal(course)"
                    class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-emerald-500/15 hover:border-emerald-500/40 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold border border-[var(--border-subtle)] transition-colors inline-flex items-center gap-1.5 touch-target cursor-pointer"
                    title="Manage Course Batches"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'ব্যাচ' : 'Batches' }}</span>
                  </button>

                  <!-- Mentors Assign -->
                  <button
                    type="button"
                    @click="openAssignMentorsModal(course)"
                    class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-purple-500/15 hover:border-purple-500/40 text-purple-500 dark:text-purple-400 text-[11px] font-bold border border-[var(--border-subtle)] transition-colors inline-flex items-center gap-1.5 touch-target cursor-pointer"
                    title="Assign Mentors to Course"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'মেন্টর' : 'Mentors' }}</span>
                  </button>

                  <!-- Reviews Manager -->
                  <button
                    type="button"
                    @click="openReviewsModal(course)"
                    class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-amber-500/15 hover:border-amber-500/40 text-amber-500 text-[11px] font-bold border border-[var(--border-subtle)] transition-colors inline-flex items-center gap-1.5 touch-target cursor-pointer"
                    title="Manage Student Reviews & Testimonials"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'রিভিউ' : 'Reviews' }}</span>
                  </button>

                  <!-- View Live Page -->
                  <router-link
                    :to="`/courses/${course.slug}`"
                    target="_blank"
                    class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[var(--bg-deep)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-[11px] font-medium border border-[var(--border-subtle)] transition-colors inline-flex items-center gap-1 touch-target"
                    title="View Public Course Page"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </router-link>

                  <!-- Delete Course Button -->
                  <button
                    type="button"
                    @click="confirmDeleteCourse(course)"
                    class="px-2 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 text-[11px] font-bold border border-rose-500/20 transition-colors touch-target cursor-pointer"
                    title="Delete Course"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 1: CREATE / EDIT COMPREHENSIVE COURSE MODAL -->
    <!-- ========================================================================= -->
    <div v-if="showCourseModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-4xl w-full max-h-[92dvh] overflow-y-auto p-5 sm:p-8 relative shadow-2xl space-y-6 safe-bottom">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-4">
          <div>
            <h2 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">
              {{ isEditingCourse ? (themeStore.locale === 'bn' ? 'কোর্স বিবরণ ও তথ্য আপডেট করুন' : 'Edit Course Information') : (themeStore.locale === 'bn' ? 'নতুন কোর্স তৈরি করুন' : 'Create New Course') }}
            </h2>
            <p class="text-xs text-[var(--text-secondary)] mt-0.5">
              {{ themeStore.locale === 'bn' ? 'কোর্সের নাম, মূল্য, বিবরণ, যা যা শিখবেন, পূর্বশর্ত ও টার্গেট অডিয়েন্স এডিট করুন।' : 'Manage course basic information, pricing, description, syllabus highlights, prerequisites, and target audience.' }}
            </p>
          </div>
          <button @click="showCourseModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <!-- Course Tabs in Modal -->
        <div class="flex items-center gap-2 border-b border-[var(--border-subtle)] pb-2 overflow-x-auto">
          <button
            type="button"
            @click="courseModalTab = 'basic'"
            :class="[
              'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap inline-flex items-center gap-1.5',
              courseModalTab === 'basic' ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
            ]"
          >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
            <span>{{ themeStore.locale === 'bn' ? '১. বেসিক তথ্য ও মূল্য' : '1. Basic Info & Pricing' }}</span>
          </button>
          <button
            type="button"
            @click="courseModalTab = 'description'"
            :class="[
              'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap inline-flex items-center gap-1.5',
              courseModalTab === 'description' ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
            ]"
          >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            <span>{{ themeStore.locale === 'bn' ? '২. কোর্স পরিচিতি ও রূপরেখা' : '2. Description & Scope' }}</span>
          </button>
          <button
            type="button"
            @click="courseModalTab = 'features'"
            :class="[
              'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap inline-flex items-center gap-1.5',
              courseModalTab === 'features' ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
            ]"
          >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
            <span>{{ themeStore.locale === 'bn' ? '৩. কোর্সে যা যা শিখবেন' : '3. What You Will Learn' }}</span>
          </button>
          <button
            type="button"
            @click="courseModalTab = 'requirements'"
            :class="[
              'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap inline-flex items-center gap-1.5',
              courseModalTab === 'requirements' ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/40' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]'
            ]"
          >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
            <span>{{ themeStore.locale === 'bn' ? '৪. পূর্বশর্ত ও অডিয়েন্স' : '4. Prerequisites & Audience' }}</span>
          </button>
        </div>

        <form @submit.prevent="submitCourseForm" class="space-y-6">
          
          <!-- TAB 1: BASIC INFO & PRICING -->
          <div v-show="courseModalTab === 'basic'" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'কোর্সের নাম (বাংলা) *' : 'Course Title (Bangla) *' }}
                </label>
                <input
                  v-model="courseForm.title_bn"
                  type="text"
                  required
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                  :placeholder="themeStore.locale === 'bn' ? 'যেমন: এয়ার টিকেটিং ও ভিসা প্রসেসিং প্রফেশনাল কোর্স' : 'e.g. এয়ার টিকেটিং ও ভিসা প্রসেসিং প্রফেশনাল কোর্স'"
                />
              </div>

              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'কোর্সের নাম (English) *' : 'Course Title (English) *' }}
                </label>
                <input
                  v-model="courseForm.title_en"
                  type="text"
                  required
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                  placeholder="e.g. Air Ticketing & Visa Processing Professional Course"
                />
              </div>
            </div>

            <!-- Subtitle -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'সাবটাইটেল / সারসংক্ষেপ (বাংলা)' : 'Subtitle (Bangla)' }}
                </label>
                <input
                  v-model="courseForm.subtitle_bn"
                  type="text"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                  placeholder="সম্পূর্ণ Practical, Job-Oriented & Industry-Focused Training..."
                />
              </div>

              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'সাবটাইটেল (English)' : 'Subtitle (English)' }}
                </label>
                <input
                  v-model="courseForm.subtitle_en"
                  type="text"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                  placeholder="Complete Practical, Job-Oriented & Industry-Focused Training..."
                />
              </div>
            </div>

            <!-- Category & Format & Level -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'ক্যাটাগরি *' : 'Category *' }}
                </label>
                <select
                  v-model="courseForm.category_id"
                  required
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                >
                  <option v-for="cat in availableCategories" :key="cat.id" :value="cat.id">
                    {{ themeStore.locale === 'bn' ? cat.name_bn : cat.name_en }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'কোর্স ফরম্যাট' : 'Course Format' }}
                </label>
                <select
                  v-model="courseForm.format"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                >
                  <option value="hybrid">Hybrid (Lab + Online)</option>
                  <option value="live">Live Batch</option>
                  <option value="recorded">Recorded Class</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'লেভেল / স্তর' : 'Skill Level' }}
                </label>
                <select
                  v-model="courseForm.level"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                >
                  <option value="all_levels">All Levels (সবাই)</option>
                  <option value="beginner">Beginner (প্রাথমিক)</option>
                  <option value="intermediate">Intermediate (মধ্যম)</option>
                  <option value="advanced">Advanced (উচ্চতর)</option>
                </select>
              </div>
            </div>

            <!-- Pricing & Hours -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'রেগুলার ফি (টাকা) *' : 'Regular Fee (BDT) *' }}
                </label>
                <input
                  v-model.number="courseForm.regular_price"
                  type="number"
                  required
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                  placeholder="30000"
                />
              </div>

              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'অফার ফি (টাকা)' : 'Sale Fee (BDT)' }}
                </label>
                <input
                  v-model.number="courseForm.sale_price"
                  type="number"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                  placeholder="16500"
                />
              </div>

              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'মোট ল্যাব ঘণ্টা (Hours)' : 'Total Lab Hours' }}
                </label>
                <input
                  v-model.number="courseForm.total_hours"
                  type="number"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                  placeholder="32"
                />
              </div>

              <div>
                <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                  {{ themeStore.locale === 'bn' ? 'কোর্সের মেয়াদ (সপ্তাহ)' : 'Duration (Weeks)' }}
                </label>
                <input
                  v-model="courseForm.duration_weeks"
                  type="text"
                  class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                  placeholder="8"
                />
              </div>
            </div>

            <!-- Published & Featured Toggles -->
            <div class="flex flex-wrap items-center gap-6 pt-3 border-t border-[var(--border-subtle)]">
              <label class="flex items-center gap-2 cursor-pointer text-xs text-[var(--text-primary)]">
                <input v-model="courseForm.is_published" type="checkbox" class="w-4 h-4 rounded accent-[#D4AF37]" />
                <span class="font-bold">{{ themeStore.locale === 'bn' ? 'ওয়েবসাইটে সরাসরি প্রকাশ করুন (Published)' : 'Published on Website' }}</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer text-xs text-[var(--text-primary)]">
                <input v-model="courseForm.is_featured" type="checkbox" class="w-4 h-4 rounded accent-[#D4AF37]" />
                <span>{{ themeStore.locale === 'bn' ? 'হোমপেজে ফিচার্ড হিসেবে রাখুন (Featured)' : 'Featured on Homepage' }}</span>
              </label>
            </div>
          </div>

          <!-- TAB 2: COURSE DESCRIPTION & SCOPE -->
          <div v-show="courseModalTab === 'description'" class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'কোর্স পরিচিতি ও রূপরেখা (বাংলা) *' : 'Course Overview & Scope (Bangla) *' }}
              </label>
              <textarea
                v-model="courseForm.description_bn"
                rows="5"
                class="w-full px-4 py-3 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] leading-relaxed"
                :placeholder="themeStore.locale === 'bn' ? 'ইমিশা একাডেমি নিয়ে এসেছে এভিয়েশন ও ট্রাভেল ইন্ডাস্ট্রির সবচেয়ে বাস্তবমুখী ও প্র্যাকটিক্যাল কোর্স...' : 'Full course description in Bangla...'"
              ></textarea>
            </div>

            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'কোর্স পরিচিতি ও রূপরেখা (English)' : 'Course Overview & Scope (English)' }}
              </label>
              <textarea
                v-model="courseForm.description_en"
                rows="4"
                class="w-full px-4 py-3 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] leading-relaxed"
                placeholder="Comprehensive course overview and description in English..."
              ></textarea>
            </div>

            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'প্রোমো ভিডিও URL (YouTube/Vimeo)' : 'Promo Video URL' }}
              </label>
              <input
                v-model="courseForm.promo_video_url"
                type="text"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                placeholder="https://www.youtube.com/watch?v=..."
              />
            </div>

            <!-- Course thumbnail: upload a file or paste an image URL -->
            <div class="space-y-2">
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase">
                {{ themeStore.locale === 'bn' ? 'কোর্স থাম্বনেইল' : 'Course Thumbnail' }}
              </label>
              <div class="flex flex-col sm:flex-row gap-3 p-3 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)]">
                <!-- Preview (16:9, same ratio as course cards) -->
                <div class="relative w-full sm:w-56 aspect-video rounded-xl overflow-hidden bg-[var(--bg-elevated)] border border-[var(--border-subtle)] shrink-0 flex items-center justify-center">
                  <img
                    v-if="courseForm.thumbnail && !thumbnailPreviewError"
                    :src="courseForm.thumbnail"
                    alt="Thumbnail preview"
                    class="w-full h-full object-cover"
                    @error="thumbnailPreviewError = true"
                    @load="thumbnailPreviewError = false"
                  />
                  <div v-else class="flex flex-col items-center gap-1 text-[var(--text-muted)] text-[11px] font-bold p-3 text-center">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <span v-if="courseForm.thumbnail && thumbnailPreviewError" class="text-rose-500">{{ themeStore.locale === 'bn' ? 'ছবিটি লোড হচ্ছে না — URL চেক করুন' : 'Image failed to load — check the URL' }}</span>
                    <span v-else>{{ themeStore.locale === 'bn' ? 'কোনো থাম্বনেইল নেই' : 'No thumbnail' }}</span>
                  </div>
                  <div v-if="thumbnailUploadProgress > 0 && thumbnailUploadProgress < 100" class="absolute inset-x-0 bottom-0 h-1.5 bg-black/40">
                    <div class="h-full bg-[#D4AF37] transition-all" :style="{ width: `${thumbnailUploadProgress}%` }"></div>
                  </div>
                </div>

                <div class="flex-1 min-w-0 space-y-2.5">
                  <div class="flex flex-wrap gap-2">
                    <label
                      class="inline-flex items-center justify-center gap-1.5 min-h-[40px] px-4 rounded-xl bg-[#D4AF37] text-slate-950 font-bold text-xs cursor-pointer hover:brightness-105"
                      :class="{ 'opacity-60 pointer-events-none': uploadingThumbnail }"
                    >
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                      <span>{{ uploadingThumbnail ? (themeStore.locale === 'bn' ? `আপলোড হচ্ছে... ${thumbnailUploadProgress}%` : `Uploading... ${thumbnailUploadProgress}%`) : (themeStore.locale === 'bn' ? 'ছবি আপলোড করুন' : 'Upload image') }}</span>
                      <input type="file" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden" @change="uploadCourseThumbnail" />
                    </label>
                    <button
                      v-if="courseForm.thumbnail"
                      type="button"
                      class="min-h-[40px] px-4 rounded-xl bg-rose-500/10 border border-rose-500/25 text-rose-500 font-bold text-xs cursor-pointer"
                      @click="courseForm.thumbnail = ''; thumbnailPreviewError = false"
                    >{{ themeStore.locale === 'bn' ? 'সরিয়ে দিন' : 'Remove' }}</button>
                  </div>
                  <div>
                    <input
                      v-model.trim="courseForm.thumbnail"
                      type="url"
                      inputmode="url"
                      class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                      :placeholder="themeStore.locale === 'bn' ? 'অথবা ছবির লিংক পেস্ট করুন: https://...' : 'Or paste an image URL: https://...'"
                      @input="thumbnailPreviewError = false"
                    />
                  </div>
                  <p class="text-[10px] text-[var(--text-muted)] leading-relaxed">
                    {{ themeStore.locale === 'bn'
                      ? 'JPG, PNG, WebP বা GIF • সর্বোচ্চ ৫MB • সবচেয়ে ভালো দেখাবে ১২৮০×৭২০ (16:9) সাইজে।'
                      : 'JPG, PNG, WebP or GIF • max 5 MB • looks best at 1280×720 (16:9).' }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 3: WHAT YOU WILL LEARN (FEATURES LIST BUILDER) -->
          <div v-show="courseModalTab === 'features'" class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase">
                  {{ themeStore.locale === 'bn' ? 'কোর্সে যেসব প্র্যাকটিক্যাল দক্ষতা অর্জন করবেন (What You Will Learn)' : 'Key Practical Skills & Competencies' }}
                </h3>
                <p class="text-[11px] text-[var(--text-muted)]">
                  {{ themeStore.locale === 'bn' ? 'শিক্ষার্থীরা কোর্সের মূল পৃষ্ঠায় এই পয়েন্টগুলো টিকচিহ্ন আকারে দেখতে পাবেন।' : 'These bullet points will appear under "What you will learn" with checkmarks on the course page.' }}
                </p>
              </div>

              <button
                type="button"
                @click="addFeatureItem"
                class="px-3 py-1.5 rounded-lg bg-[#D4AF37]/15 hover:bg-[#D4AF37]/25 text-[#D4AF37] text-xs font-bold border border-[#D4AF37]/30 transition-all cursor-pointer inline-flex items-center gap-1"
              >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'নতুন দক্ষতা যোগ' : 'Add Skill' }}</span>
              </button>
            </div>

            <div class="space-y-2.5 max-h-80 overflow-y-auto p-2 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)]">
              <div
                v-for="(feat, fIdx) in courseForm.features_bn"
                :key="fIdx"
                class="flex items-center gap-2 p-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]"
              >
                <span class="w-6 h-6 rounded-lg bg-[var(--bg-elevated)] text-[#D4AF37] flex items-center justify-center text-xs font-bold shrink-0">
                  {{ fIdx + 1 }}
                </span>
                <input
                  v-model="courseForm.features_bn[fIdx]"
                  type="text"
                  class="flex-1 px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                  :placeholder="themeStore.locale === 'bn' ? 'যেমন: Sabre ও Galileo GDS Live Software Practice' : 'Practical skill in Bangla...'"
                />
                <button
                  type="button"
                  @click="removeFeatureItem(fIdx)"
                  class="w-7 h-7 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500/20 text-xs font-bold transition-colors cursor-pointer flex items-center justify-center"
                  title="Remove"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>

              <div v-if="courseForm.features_bn.length === 0" class="text-center py-6 text-xs text-[var(--text-muted)]">
                {{ themeStore.locale === 'bn' ? 'কোনো দক্ষতা যোগ করা হয়নি। "+ নতুন দক্ষতা যোগ করুন" বাটনে ক্লিক করুন।' : 'No skills added yet. Click "+ Add Skill Point".' }}
              </div>
            </div>
          </div>

          <!-- TAB 4: PREREQUISITES & TARGET AUDIENCE BUILDER -->
          <div v-show="courseModalTab === 'requirements'" class="space-y-6">
            <!-- Prerequisites -->
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-xs font-bold text-[var(--text-primary)] uppercase flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"/><path d="m17 5-5-3-5 3v6c0 5 5 8 5 8s5-3 5-8Z"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'কোর্সের পূর্বশর্ত (Prerequisites)' : 'Course Prerequisites' }}</span>
                  </h4>
                  <p class="text-[11px] text-[var(--text-muted)]">
                    {{ themeStore.locale === 'bn' ? 'কোর্সে ভর্তির জন্য যেসব যোগ্যতা প্রয়োজন' : 'Requirements needed to enroll in this course' }}
                  </p>
                </div>
                <button
                  type="button"
                  @click="addPrerequisiteItem"
                  class="px-3 py-1.5 rounded-lg bg-amber-500/15 hover:bg-amber-500/25 text-amber-500 text-xs font-bold border border-amber-500/30 transition-all cursor-pointer inline-flex items-center gap-1"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  <span>{{ themeStore.locale === 'bn' ? 'পূর্বশর্ত যোগ' : 'Add Requirement' }}</span>
                </button>
              </div>

              <div class="space-y-2 max-h-48 overflow-y-auto p-2 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)]">
                <div
                  v-for="(item, pIdx) in courseForm.prerequisites_bn"
                  :key="pIdx"
                  class="flex items-center gap-2 p-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]"
                >
                  <svg class="w-4 h-4 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  <input
                    v-model="courseForm.prerequisites_bn[pIdx]"
                    type="text"
                    class="flex-1 px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                    :placeholder="themeStore.locale === 'bn' ? 'যেমন: কম্পিউটার ও বেসিক ইন্টারনেট চালানোর ধারণা' : 'Requirement in Bangla...'"
                  />
                  <button
                    type="button"
                    @click="removePrerequisiteItem(pIdx)"
                    class="w-7 h-7 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500/20 text-xs font-bold transition-colors cursor-pointer flex items-center justify-center"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Target Audience -->
            <div class="space-y-3 pt-4 border-t border-[var(--border-subtle)]">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-xs font-bold text-[var(--text-primary)] uppercase flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                    <span>{{ themeStore.locale === 'bn' ? 'যাদের জন্য এই কোর্স (Target Audience)' : 'Target Audience' }}</span>
                  </h4>
                  <p class="text-[11px] text-[var(--text-muted)]">
                    {{ themeStore.locale === 'bn' ? 'কোর্সটি যেসব পেশা বা আগ্রহের মানুষের জন্য প্রযোজ্য' : 'Who will benefit most from this course' }}
                  </p>
                </div>
                <button
                  type="button"
                  @click="addAudienceItem"
                  class="px-3 py-1.5 rounded-lg bg-sky-500/15 hover:bg-sky-500/25 text-sky-500 text-xs font-bold border border-sky-500/30 transition-all cursor-pointer inline-flex items-center gap-1"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  <span>{{ themeStore.locale === 'bn' ? 'অডিয়েন্স যোগ' : 'Add Audience' }}</span>
                </button>
              </div>

              <div class="space-y-2 max-h-48 overflow-y-auto p-2 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)]">
                <div
                  v-for="(item, aIdx) in courseForm.target_audience_bn"
                  :key="aIdx"
                  class="flex items-center gap-2 p-2 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]"
                >
                  <svg class="w-4 h-4 text-sky-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  <input
                    v-model="courseForm.target_audience_bn[aIdx]"
                    type="text"
                    class="flex-1 px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                    :placeholder="themeStore.locale === 'bn' ? 'যেমন: ট্রাভেল এজেন্সি ও এয়ারলাইন্সে চাকরিপ্রত্যাশী' : 'Target audience in Bangla...'"
                  />
                  <button
                    type="button"
                    @click="removeAudienceItem(aIdx)"
                    class="w-7 h-7 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500/20 text-xs font-bold transition-colors cursor-pointer flex items-center justify-center"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Action Buttons -->
          <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-[var(--border-subtle)]">
            <button
              type="button"
              @click="showCourseModal = false"
              class="px-4 py-2.5 rounded-xl text-xs font-semibold text-[var(--text-secondary)] hover:text-[var(--text-primary)] touch-target text-center cursor-pointer"
            >
              {{ $t('common.cancel') }}
            </button>
            <button
              type="submit"
              :disabled="submittingCourse"
              class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg transition-all disabled:opacity-50 touch-target cursor-pointer"
            >
              <span v-if="submittingCourse">{{ $t('student.updating') }}</span>
              <span v-else>{{ isEditingCourse ? (themeStore.locale === 'bn' ? 'আপডেট সংরক্ষণ করুন' : 'Save Changes') : (themeStore.locale === 'bn' ? 'কোর্স তৈরি করুন' : 'Create Course') }}</span>
            </button>
          </div>

        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: CURRICULUM (MODULES & LESSONS) MANAGER -->
    <!-- ========================================================================= -->
    <div v-if="showCurriculumModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-4xl w-full max-h-[92dvh] overflow-y-auto p-5 sm:p-8 relative shadow-2xl space-y-6 safe-bottom">
        
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-4">
          <div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
              <h2 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">
                {{ themeStore.locale === 'bn' ? 'সিলেবাস, মডিউল ও লেসন ম্যানেজমেন্ট' : 'Curriculum & Lessons Management' }}
              </h2>
            </div>
            <p class="text-xs text-[#D4AF37] font-semibold mt-0.5">
              {{ getLocalized(selectedCourseForCurriculum, 'title', themeStore.locale) }}
            </p>
          </div>

          <div class="flex items-center gap-2">
            <button
              type="button"
              @click="openAddModuleModal"
              class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg transition-all cursor-pointer inline-flex items-center gap-1.5"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              <span>{{ themeStore.locale === 'bn' ? '+ নতুন মডিউল' : '+ Module' }}</span>
            </button>
            <button @click="showCurriculumModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
        </div>

        <!-- Modules Accordion in Admin -->
        <div v-if="curriculumModules.length === 0" class="text-center py-12 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] p-6 space-y-3">
          <p class="text-xs text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'এই কোর্সে এখনও কোনো মডিউল তৈরি করা হয়নি।' : 'No modules created for this course yet.' }}</p>
          <button
            type="button"
            @click="openAddModuleModal"
            class="px-4 py-2 rounded-xl bg-[#D4AF37] text-slate-950 font-bold text-xs cursor-pointer inline-flex items-center gap-1.5"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>{{ themeStore.locale === 'bn' ? 'প্রথম মডিউল যোগ করুন' : 'Add First Module' }}</span>
          </button>
        </div>

        <div v-else class="space-y-4">
          <div
            v-for="(mod, mIdx) in curriculumModules"
            :key="mod.id"
            class="rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] overflow-hidden shadow-xs"
          >
            <!-- Module Header -->
            <div class="p-4 bg-[var(--bg-elevated)]/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[var(--border-subtle)]">
              <div class="space-y-0.5 min-w-0 flex-1">
                <div class="flex items-center gap-2">
                  <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30 uppercase">
                    {{ themeStore.locale === 'bn' ? `মডিউল 0${mIdx + 1}` : `Module 0${mIdx + 1}` }}
                  </span>
                  <span class="text-xs font-bold text-[var(--text-primary)] truncate">
                    {{ themeStore.locale === 'bn' ? mod.title_bn : mod.title_en }}
                  </span>
                </div>
                <p v-if="mod.summary_bn" class="text-[11px] text-[var(--text-secondary)] line-clamp-1">
                  {{ themeStore.locale === 'bn' ? mod.summary_bn : mod.summary_en }}
                </p>
              </div>

              <div class="flex items-center gap-1.5 shrink-0">
                <button
                  type="button"
                  @click="openAddLessonModal(mod)"
                  class="px-2.5 py-1 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold border border-emerald-500/20 cursor-pointer inline-flex items-center gap-1"
                >
                  <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  <span>{{ themeStore.locale === 'bn' ? 'লেসন' : 'Lesson' }}</span>
                </button>
                <button
                  type="button"
                  @click="openEditModuleModal(mod)"
                  class="px-2.5 py-1 rounded-lg bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] text-[var(--brand-gold)] text-[11px] font-bold border border-[var(--border-subtle)] cursor-pointer"
                  title="Edit Module"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                </button>
                <button
                  type="button"
                  @click="deleteModule(mod.id)"
                  class="px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 text-[11px] font-bold border border-rose-500/20 cursor-pointer"
                  title="Delete Module"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
              </div>
            </div>

            <!-- Lessons Inside Module -->
            <div class="p-3 space-y-2">
              <div
                v-for="(lesson, lIdx) in mod.lessons"
                :key="lesson.id"
                class="flex items-center justify-between p-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] gap-3 text-xs"
              >
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                  <span class="w-5 h-5 rounded bg-[var(--bg-elevated)] text-[#D4AF37] flex items-center justify-center text-[10px] font-bold shrink-0">
                    {{ lIdx + 1 }}
                  </span>
                  <div class="min-w-0 flex-1">
                    <p class="font-bold text-[var(--text-primary)] truncate">
                      {{ themeStore.locale === 'bn' ? lesson.title_bn : lesson.title_en }}
                    </p>
                    <div class="flex items-center gap-2 text-[10px] text-[var(--text-muted)]">
                      <span class="inline-flex items-center gap-1">
                        <svg class="w-3 h-3 text-[var(--brand-gold)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>{{ lesson.duration || '30m' }}</span>
                      </span>
                      <span v-if="lesson.is_free_preview" class="text-emerald-500 font-bold">• Free Preview</span>
                      <span class="px-1.5 py-px rounded font-bold" :class="lessonTypeBadge(lesson.lesson_type).cls">{{ lessonTypeBadge(lesson.lesson_type).label }}</span>
                      <span v-if="lesson.resources?.length" class="text-[#D4AF37] font-bold">📎 {{ lesson.resources.length }}</span>
                      <span v-if="lesson.is_published === false" class="text-amber-500 font-bold">• Draft</span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                  <button
                    type="button"
                    @click="openEditLessonModal(mod, lesson)"
                    class="px-2 py-1 rounded bg-[var(--bg-elevated)] text-[var(--text-secondary)] hover:text-[var(--text-primary)] cursor-pointer"
                    title="Edit Lesson"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                  </button>
                  <button
                    type="button"
                    @click="deleteLesson(lesson.id)"
                    class="px-2 py-1 rounded bg-rose-500/10 text-rose-500 hover:bg-rose-500/20 cursor-pointer"
                    title="Delete Lesson"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </div>
              </div>

              <div v-if="!mod.lessons || mod.lessons.length === 0" class="text-center py-3 text-[11px] text-[var(--text-muted)] italic">
                {{ themeStore.locale === 'bn' ? 'এই মডিউলে এখনও কোনো লেসন যুক্ত করা হয়নি।' : 'No lessons in this module yet.' }}
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 3: BATCHES MANAGER -->
    <!-- ========================================================================= -->
    <div v-if="showBatchesModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-3xl w-full max-h-[92dvh] overflow-y-auto p-5 sm:p-8 relative shadow-2xl space-y-6 safe-bottom">
        
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-4">
          <div>
            <h2 class="text-base sm:text-lg font-bold text-[var(--text-primary)] flex items-center gap-2">
              <svg class="w-5 h-5 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'কোর্স ব্যাচ ও সিট শিডিউল ম্যানেজমেন্ট' : 'Course Batches & Enrollment Schedules' }}</span>
            </h2>
            <p class="text-xs text-[#D4AF37] font-semibold mt-0.5">
              {{ getLocalized(selectedCourseForBatches, 'title', themeStore.locale) }}
            </p>
          </div>

          <div class="flex items-center gap-2">
            <button
              type="button"
              @click="openAddBatchModal"
              class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg cursor-pointer inline-flex items-center gap-1.5"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'নতুন ব্যাচ তৈরি' : 'Create Batch' }}</span>
            </button>
            <button @click="showBatchesModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
        </div>

        <div v-if="courseBatches.length === 0" class="text-center py-12 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] p-6 space-y-3">
          <p class="text-xs text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'এই কোর্সে কোনো ব্যাচ পাওয়া যায়নি।' : 'No batches found.' }}</p>
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="batch in courseBatches"
            :key="batch.id"
            class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all"
          >
            <div class="space-y-1">
              <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-500 border border-amber-500/30">
                  {{ batch.batch_number }}
                </span>
                <span class="text-xs sm:text-sm font-bold text-[var(--text-primary)]">
                  {{ themeStore.locale === 'bn' ? (batch.title_bn || batch.batch_number) : (batch.title_en || batch.batch_number) }}
                </span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" :class="batch.status === 'enrolling' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-slate-500/10 text-slate-400'">
                  {{ batch.status }}
                </span>
              </div>

              <div class="flex flex-wrap items-center gap-3 text-[11px] text-[var(--text-secondary)] pt-1">
                <span class="inline-flex items-center gap-1"><svg class="w-3 h-3 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> শুরু: {{ batch.start_date }}</span>
                <span v-if="batch.class_days" class="inline-flex items-center gap-1"><svg class="w-3 h-3 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> {{ batch.class_days }}</span>
                <span v-if="batch.class_time" class="inline-flex items-center gap-1"><svg class="w-3 h-3 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> {{ batch.class_time }}</span>
                <span class="inline-flex items-center gap-1"><svg class="w-3 h-3 text-purple-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> সিট: {{ batch.enrolled_students || 0 }} / {{ batch.seat_capacity || 30 }}</span>
              </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
              <button
                type="button"
                @click="openEditBatchModal(batch)"
                class="px-3 py-1.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-deep)] text-[var(--brand-gold)] text-xs font-bold border border-[var(--border-subtle)] cursor-pointer inline-flex items-center gap-1"
              >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                <span>{{ themeStore.locale === 'bn' ? 'এডিট' : 'Edit' }}</span>
              </button>
              <button
                type="button"
                @click="deleteBatch(batch.id)"
                class="px-3 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 text-xs font-bold border border-rose-500/20 cursor-pointer"
              >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 4: REVIEWS & TESTIMONIALS MANAGER -->
    <!-- ========================================================================= -->
    <div v-if="showReviewsModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-3xl w-full max-h-[92dvh] overflow-y-auto p-5 sm:p-8 relative shadow-2xl space-y-6 safe-bottom">
        
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-4">
          <div>
            <h2 class="text-base sm:text-lg font-bold text-[var(--text-primary)] flex items-center gap-2">
              <svg class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'শিক্ষার্থীদের কোর্স রিভিউ ম্যানেজমেন্ট' : 'Course Reviews & Testimonials' }}</span>
            </h2>
            <p class="text-xs text-[#D4AF37] font-semibold mt-0.5">
              {{ getLocalized(selectedCourseForReviews, 'title', themeStore.locale) }}
            </p>
          </div>

          <div class="flex items-center gap-2">
            <button
              type="button"
              @click="openAddReviewModal"
              class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg cursor-pointer inline-flex items-center gap-1.5"
            >
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              <span>{{ themeStore.locale === 'bn' ? 'নতুন রিভিউ' : 'Add Review' }}</span>
            </button>
            <button @click="showReviewsModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
        </div>

        <div v-if="courseReviewsList.length === 0" class="text-center py-12 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] p-6 space-y-3">
          <p class="text-xs text-[var(--text-secondary)]">{{ themeStore.locale === 'bn' ? 'এই কোর্সে কোনো রিভিউ তৈরি করা হয়নি।' : 'No reviews recorded in database yet.' }}</p>
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="rev in courseReviewsList"
            :key="rev.id"
            class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 space-y-3"
          >
            <div class="flex items-start justify-between gap-3">
              <div class="flex items-center gap-3">
                <img
                  :src="rev.user?.avatar || getInitialsAvatar(rev.user?.name || 'Student')"
                  class="w-9 h-9 rounded-full object-cover border border-[#D4AF37]/40 bg-slate-900"
                  @error="onImageError($event, 'avatar', rev.user?.name)"
                />
                <div>
                  <p class="text-xs font-bold text-[var(--text-primary)]">{{ rev.user?.name || 'Student / Graduate' }}</p>
                  <div class="flex items-center gap-2 text-[10px] text-amber-500 font-bold">
                    <span class="inline-flex items-center gap-1"><svg class="w-3 h-3 fill-current text-[#D4AF37]" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>{{ rev.rating || 5 }}.0</span>
                    <span class="text-[var(--text-muted)]">• {{ rev.is_approved ? 'Approved' : 'Pending' }}</span>
                  </div>
                </div>
              </div>

              <div class="flex items-center gap-1.5">
                <button
                  type="button"
                  @click="toggleReviewApproval(rev)"
                  :class="[
                    'px-2.5 py-1 rounded-lg text-[10px] font-bold border transition-colors cursor-pointer',
                    rev.is_approved ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-amber-500/10 text-amber-500 border-amber-500/20'
                  ]"
                >
                  {{ rev.is_approved ? 'Approved' : 'Approve' }}
                </button>
                <button
                  type="button"
                  @click="deleteReview(rev.id)"
                  class="px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-500 text-[10px] font-bold border border-rose-500/20 cursor-pointer"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
              </div>
            </div>

            <p class="text-xs text-[var(--text-secondary)] leading-relaxed bg-[var(--bg-deep)] p-3 rounded-xl border border-[var(--border-subtle)]">
              "{{ rev.comment }}"
            </p>
          </div>
        </div>

      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 5: ASSIGN MENTORS MODAL -->
    <!-- ========================================================================= -->
    <div v-if="showAssignMentorsModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-xl w-full max-h-[90dvh] overflow-y-auto p-5 sm:p-8 relative shadow-2xl space-y-6 safe-bottom">
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-4">
          <div>
            <h2 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">
              {{ themeStore.locale === 'bn' ? 'কোর্স মেন্টর ও ট্রেইনার নির্ধারণ' : 'Assign Course Mentors' }}
            </h2>
            <p class="text-xs text-[#D4AF37] mt-0.5">
              {{ getLocalized(selectedCourseForMentors, 'title', themeStore.locale) }}
            </p>
          </div>
          <button @click="showAssignMentorsModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <div class="space-y-4">
          <p class="text-xs text-[var(--text-secondary)]">
            {{ themeStore.locale === 'bn' ? 'এই কোর্সের জন্য যেসব প্রশিক্ষক ক্লাস পরিচালনা করবেন তাদের নির্বাচন করুন:' : 'Select instructors and mentors who will conduct classes for this course:' }}
          </p>

          <div class="space-y-2.5 max-h-72 overflow-y-auto p-2 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)]">
            <label
              v-for="inst in availableInstructors"
              :key="inst.id"
              class="flex items-center gap-3.5 p-3 rounded-2xl bg-[var(--bg-surface)] hover:bg-[var(--bg-elevated)] border border-[var(--border-subtle)] hover:border-[#D4AF37]/50 cursor-pointer transition-all"
            >
              <input
                type="checkbox"
                :value="inst.id"
                v-model="assignedMentorIds"
                class="w-5 h-5 rounded accent-[#D4AF37]"
              />
              <img
                :src="inst.avatar || getInitialsAvatar(inst.name_en || inst.name_bn || 'Mentor')"
                class="w-11 h-11 rounded-2xl object-cover border border-[#D4AF37]/30 shrink-0 bg-slate-900"
                @error="onImageError($event, 'avatar', inst.name_en || inst.name_bn)"
              />
              <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                  <p class="text-xs sm:text-sm font-bold text-[var(--text-primary)]">{{ themeStore.locale === 'bn' ? inst.name_bn : inst.name_en }}</p>
                  <span class="text-[10px] font-bold text-amber-500 inline-flex items-center gap-0.5"><svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>{{ inst.rating || '5.0' }}</span>
                </div>
                <p class="text-[11px] text-[var(--text-secondary)] truncate">{{ themeStore.locale === 'bn' ? inst.title_bn : inst.title_en }}</p>
                <p v-if="inst.organization || inst.experience_years" class="text-[10px] text-[var(--text-muted)]">{{ [inst.organization, inst.experience_years ? `${inst.experience_years} ${themeStore.locale === 'bn' ? 'বছরের অভিজ্ঞতা' : 'years exp'}` : ''].filter(Boolean).join(' • ') }}</p>
              </div>
            </label>
          </div>

          <div class="flex items-center justify-between pt-4 border-t border-[var(--border-subtle)]">
            <button
              type="button"
              @click="openAddInstructorModal"
              class="text-xs text-[var(--brand-gold)] font-bold hover:underline cursor-pointer"
            >
              {{ themeStore.locale === 'bn' ? '+ নতুন মেন্টর প্রোফাইল তৈরি করুন' : '+ Create New Mentor Profile' }}
            </button>

            <div class="flex items-center gap-2">
              <button
                type="button"
                @click="showAssignMentorsModal = false"
                class="px-4 py-2 rounded-xl text-xs font-semibold text-[var(--text-secondary)] hover:text-[var(--text-primary)] cursor-pointer"
              >
                {{ $t('common.cancel') }}
              </button>
              <button
                type="button"
                @click="submitAssignMentors"
                :disabled="savingMentors"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg transition-all disabled:opacity-50 cursor-pointer"
              >
                <span v-if="savingMentors">{{ $t('student.updating') }}</span>
                <span v-else>{{ themeStore.locale === 'bn' ? 'সংরক্ষণ করুন' : 'Save Mentors' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 6: CREATE / EDIT INSTRUCTOR PROFILE -->
    <!-- ========================================================================= -->
    <div v-if="showAddInstructorModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-xl w-full max-h-[90dvh] overflow-y-auto p-5 sm:p-8 relative shadow-2xl space-y-6 safe-bottom">
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-4">
          <h2 class="text-base sm:text-lg font-bold text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'নতুন মেন্টর / ইন্সট্রাক্টর প্রোফাইল তৈরি' : 'Add New Instructor / Mentor' }}
          </h2>
          <button @click="showAddInstructorModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitCreateInstructor" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'মেন্টরের নাম (বাংলা) *' : 'Mentor Name (Bangla) *' }}
              </label>
              <input
                v-model="newInstructorForm.name_bn"
                type="text"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                placeholder="যেমন: তানভীর রহমান"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'মেন্টরের নাম (English) *' : 'Mentor Name (English) *' }}
              </label>
              <input
                v-model="newInstructorForm.name_en"
                type="text"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                placeholder="e.g. Tanvir Rahman"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'পদবী / স্পেশালাইজেশন (বাংলা) *' : 'Title / Role (Bangla) *' }}
              </label>
              <input
                v-model="newInstructorForm.title_bn"
                type="text"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                placeholder="যেমন: লিড এভিয়েশন ও জিডিএস ট্রেইনার"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'পদবী / স্পেশালাইজেশন (English) *' : 'Title / Role (English) *' }}
              </label>
              <input
                v-model="newInstructorForm.title_en"
                type="text"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                placeholder="e.g. Lead Aviation & GDS Trainer"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
              {{ themeStore.locale === 'bn' ? 'ছবি / Avatar Image URL' : 'Avatar Image URL' }}
            </label>
            <input
              v-model="newInstructorForm.avatar"
              type="text"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
              placeholder="https://images.unsplash.com/..."
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'কাজের অভিজ্ঞতা (Years)' : 'Experience (Years)' }}
              </label>
              <input
                v-model="newInstructorForm.experience_years"
                type="text"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                placeholder="10+"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
                {{ themeStore.locale === 'bn' ? 'প্রতিষ্ঠান / এজেন্সি' : 'Organization' }}
              </label>
              <input
                v-model="newInstructorForm.organization"
                type="text"
                class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37]"
                placeholder="Emisha Tours & Travels"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">
              {{ themeStore.locale === 'bn' ? 'মেন্টর পরিচিতি / বায়ো (বাংলা)' : 'Biography (Bangla)' }}
            </label>
            <textarea
              v-model="newInstructorForm.bio_bn"
              rows="3"
              class="w-full px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-xs focus:outline-none focus:border-[#D4AF37] resize-none"
              placeholder="১০+ বছরের আন্তর্জাতিক এয়ারলাইন্স, জিডিএস (Sabre, Galileo) এবং গ্লোবাল ভিসা প্রসেসিং ইন্ডাস্ট্রির অভিজ্ঞ প্রশিক্ষক..."
            ></textarea>
          </div>

          <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-[var(--border-subtle)]">
            <button
              type="button"
              @click="showAddInstructorModal = false"
              class="px-4 py-2.5 rounded-xl text-xs font-semibold text-[var(--text-secondary)] hover:text-[var(--text-primary)] touch-target text-center cursor-pointer"
            >
              {{ $t('common.cancel') }}
            </button>
            <button
              type="submit"
              :disabled="submittingInstructor"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg transition-all disabled:opacity-50 touch-target cursor-pointer"
            >
              <span v-if="submittingInstructor">{{ $t('student.updating') }}</span>
              <span v-else>{{ themeStore.locale === 'bn' ? 'মেন্টর সংরক্ষণ করুন' : 'Save Mentor' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- SUB-MODAL: MODULE CREATE / EDIT -->
    <!-- ========================================================================= -->
    <div v-if="showModuleEditor" class="fixed inset-0 z-60 bg-black/85 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-lg w-full max-h-[92dvh] overflow-y-auto p-5 sm:p-6 space-y-4 shadow-2xl safe-bottom">
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
          <h3 class="text-sm font-bold text-[var(--text-primary)]">
            {{ isEditingModule ? (themeStore.locale === 'bn' ? 'মডিউল এডিট করুন' : 'Edit Module') : (themeStore.locale === 'bn' ? 'নতুন মডিউল তৈরি' : 'New Module') }}
          </h3>
          <button @click="showModuleEditor = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitModuleForm" class="space-y-3">
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">মডিউল শিরোনাম (বাংলা) *</label>
            <input v-model="moduleForm.title_bn" required type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="যেমন: মডিউল ০১: এয়ার টিকেটিং বেসিক ও Sabre GDS" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">মডিউল শিরোনাম (English) *</label>
            <input v-model="moduleForm.title_en" required type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="e.g. Module 01: Air Ticketing & Sabre GDS" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">মডিউল সারসংক্ষেপ (বাংলা)</label>
            <textarea v-model="moduleForm.summary_bn" rows="2" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="ইন্টারন্যাশনাল এয়ারলাইন্স কোডস, এয়ারপোর্ট কোডস, Sabre GDS সাইন-ইন ও PNR ক্রিয়েশন।"></textarea>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-[var(--border-subtle)]">
            <button type="button" @click="showModuleEditor = false" class="px-4 py-2 rounded-xl text-xs text-[var(--text-secondary)] cursor-pointer">বাতিল</button>
            <button type="submit" class="px-5 py-2 rounded-xl bg-[#D4AF37] text-slate-950 font-bold text-xs cursor-pointer">সংরক্ষণ করুন</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- SUB-MODAL: LESSON CREATE / EDIT (Video / Text / Quiz + optional resources) -->
    <!-- ========================================================================= -->
    <div v-if="showLessonEditor" class="fixed inset-0 z-60 bg-black/85 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-3xl w-full max-h-[94dvh] overflow-y-auto p-5 sm:p-6 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
          <h3 class="text-sm font-bold text-[var(--text-primary)]">
            {{ isEditingLesson ? (themeStore.locale === 'bn' ? 'লেসন এডিট করুন' : 'Edit Lesson') : (themeStore.locale === 'bn' ? 'নতুন লেসন তৈরি' : 'New Lesson') }}
          </h3>
          <button type="button" @click="showLessonEditor = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitLessonForm" class="space-y-4">
          <!-- Lesson type -->
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">লেসনের ধরন (Lesson Type) *</label>
            <div class="grid grid-cols-3 gap-2">
              <button
                v-for="opt in lessonTypeOptions"
                :key="opt.value"
                type="button"
                @click="lessonForm.lesson_type = opt.value"
                :class="[
                  'p-3 rounded-xl border text-left transition-all cursor-pointer',
                  lessonForm.lesson_type === opt.value ? 'border-[#D4AF37] bg-[#D4AF37]/10' : 'border-[var(--border-subtle)] bg-[var(--bg-elevated)] hover:border-[#D4AF37]/50'
                ]"
              >
                <span class="text-lg">{{ opt.icon }}</span>
                <span class="block text-xs font-bold text-[var(--text-primary)] mt-1">{{ opt.label }}</span>
                <span class="block text-[10px] text-[var(--text-muted)]">{{ opt.hint }}</span>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">লেসন শিরোনাম (বাংলা) *</label>
              <input v-model="lessonForm.title_bn" required type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="যেমন: ০১. এভিয়েশন ইন্ডাস্ট্রি পরিচিতি" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">লেসন শিরোনাম (English)</label>
              <input v-model="lessonForm.title_en" type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="e.g. 01. Aviation Industry Overview" />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">সময়কাল (Duration)</label>
              <input v-model="lessonForm.duration" type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="30 মিনিট" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">সংক্ষিপ্ত সারাংশ (শিক্ষার্থীরা "পাঠের নোট ও সারাংশ"-এ দেখবে)</label>
              <input v-model="lessonForm.short_description" type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="এই লেসনে কী শিখবেন — এক লাইনে" />
            </div>
          </div>

          <!-- ============ VIDEO ============ -->
          <div v-if="lessonForm.lesson_type === 'video'" class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-3">
            <div class="flex items-center gap-2 text-xs">
              <button
                type="button"
                @click="videoSource = 'link'"
                :class="['px-3 py-1.5 rounded-lg font-bold cursor-pointer', videoSource === 'link' ? 'bg-[#D4AF37] text-slate-950' : 'bg-[var(--bg-elevated)] text-[var(--text-secondary)]']"
              >🔗 ভিডিও লিংক</button>
              <button
                type="button"
                @click="videoSource = 'upload'"
                :class="['px-3 py-1.5 rounded-lg font-bold cursor-pointer', videoSource === 'upload' ? 'bg-[#D4AF37] text-slate-950' : 'bg-[var(--bg-elevated)] text-[var(--text-secondary)]']"
              >⬆️ ভিডিও আপলোড</button>
            </div>

            <template v-if="videoSource === 'link'">
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                  <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">প্রোভাইডার</label>
                  <select v-model="lessonForm.video_provider" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]">
                    <option value="youtube">YouTube</option>
                    <option value="vimeo">Vimeo</option>
                    <option value="bunny">Bunny CDN</option>
                    <option value="html5">Direct Video (MP4)</option>
                  </select>
                </div>
                <div class="sm:col-span-2">
                  <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">ভিডিও URL</label>
                  <input v-model="lessonForm.video_url" type="url" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="https://youtu.be/..." />
                </div>
              </div>
            </template>

            <template v-else>
              <label class="flex flex-col items-center justify-center gap-1.5 p-5 rounded-xl border-2 border-dashed border-[var(--border-subtle)] hover:border-[#D4AF37] bg-[var(--bg-elevated)] cursor-pointer text-center">
                <span class="text-2xl">🎬</span>
                <span class="text-xs font-bold text-[var(--text-primary)]">MP4 / WebM / MOV ফাইল নির্বাচন করুন</span>
                <span class="text-[10px] text-[var(--text-muted)]">সর্বোচ্চ ৫০০MB (সার্ভারের আপলোড লিমিট অনুযায়ী)</span>
                <input type="file" accept="video/mp4,video/webm,video/quicktime,.m4v" class="hidden" @change="uploadLessonVideo" />
              </label>
              <div v-if="mediaUploadProgress > 0 && mediaUploadProgress < 100" class="space-y-1">
                <div class="h-2 rounded-full bg-[var(--bg-elevated)] overflow-hidden">
                  <div class="h-full bg-[#D4AF37] transition-all" :style="{ width: `${mediaUploadProgress}%` }"></div>
                </div>
                <p class="text-[10px] text-[var(--text-muted)]">আপলোড হচ্ছে... {{ mediaUploadProgress }}%</p>
              </div>
            </template>

            <p v-if="lessonForm.video_url" class="text-[11px] text-emerald-500 break-all">✓ বর্তমান ভিডিও: {{ lessonForm.video_url }}</p>

            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">লেসন নোটস / বিবরণ (ঐচ্ছিক)</label>
              <RichTextEditor v-model="lessonForm.content" compact min-height="120px" placeholder="ভিডিওর সাথে শিক্ষার্থীদের জন্য গুরুত্বপূর্ণ পয়েন্ট লিখুন..." />
            </div>
          </div>

          <!-- ============ TEXT ============ -->
          <div v-else-if="lessonForm.lesson_type === 'text'" class="space-y-1.5">
            <label class="block text-xs font-semibold text-[var(--text-secondary)]">লেসনের লেখা (Text Content) *</label>
            <RichTextEditor v-model="lessonForm.content" min-height="300px" placeholder="লেসনের সম্পূর্ণ লেখা এখানে লিখুন বা পেস্ট করুন — হেডিং, তালিকা, টেবিল, লিংক সব সাপোর্ট করে।" />
          </div>

          <!-- ============ QUIZ ============ -->
          <div v-else-if="lessonForm.lesson_type === 'quiz'" class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-2">
              <p class="text-xs font-bold text-[var(--text-primary)]">প্রশ্নসমূহ ({{ lessonForm.quiz_data.questions.length }})</p>
              <label class="flex items-center gap-2 text-xs text-[var(--text-secondary)]">
                পাস মার্ক
                <input v-model.number="lessonForm.quiz_data.pass_percentage" type="number" min="0" max="100" class="w-16 px-2 py-1 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]" />
                %
              </label>
            </div>

            <div
              v-for="(q, qIdx) in lessonForm.quiz_data.questions"
              :key="qIdx"
              class="p-3 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-2"
            >
              <div class="flex items-start gap-2">
                <span class="w-6 h-6 rounded-lg bg-[#D4AF37]/15 text-[#D4AF37] text-[11px] font-black flex items-center justify-center shrink-0 mt-1">{{ qIdx + 1 }}</span>
                <textarea v-model="q.question" rows="2" required class="flex-1 px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="প্রশ্ন লিখুন"></textarea>
                <button type="button" @click="removeQuizQuestion(qIdx)" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-500/10 cursor-pointer" title="প্রশ্ন মুছুন">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                </button>
              </div>

              <div class="pl-8 space-y-1.5">
                <div v-for="(_, oIdx) in q.options" :key="oIdx" class="flex items-center gap-2">
                  <input
                    type="radio"
                    :name="`correct-${qIdx}`"
                    :checked="q.correct_index === oIdx"
                    @change="q.correct_index = oIdx"
                    class="w-4 h-4 accent-emerald-500 shrink-0 cursor-pointer"
                    title="সঠিক উত্তর"
                  />
                  <input v-model="q.options[oIdx]" required type="text" :class="['flex-1 px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]', q.correct_index === oIdx ? 'border-emerald-500/60' : 'border-[var(--border-subtle)]']" :placeholder="`অপশন ${oIdx + 1}`" />
                  <button v-if="q.options.length > 2" type="button" @click="removeQuizOption(q, oIdx)" class="text-[var(--text-muted)] hover:text-rose-500 cursor-pointer px-1">✕</button>
                </div>
                <div class="flex flex-wrap items-center gap-3 pt-1">
                  <button v-if="q.options.length < 6" type="button" @click="q.options.push('')" class="text-[11px] font-bold text-[#D4AF37] hover:underline cursor-pointer">+ অপশন যোগ করুন</button>
                  <span class="text-[10px] text-emerald-500">● সবুজ রেডিও = সঠিক উত্তর</span>
                </div>
                <input v-model="q.explanation" type="text" class="w-full px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[11px] text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="ব্যাখ্যা (ঐচ্ছিক) — উত্তর জমা দেওয়ার পর শিক্ষার্থী দেখবে" />
              </div>
            </div>

            <button type="button" @click="addQuizQuestion" class="w-full py-2.5 rounded-xl border-2 border-dashed border-[var(--border-subtle)] hover:border-[#D4AF37] text-xs font-bold text-[var(--text-secondary)] hover:text-[#D4AF37] cursor-pointer">
              + নতুন প্রশ্ন যোগ করুন
            </button>
          </div>

          <div class="flex flex-wrap gap-x-5 gap-y-2 pt-1">
            <label class="flex items-center gap-2 cursor-pointer text-xs text-[var(--text-primary)]">
              <input v-model="lessonForm.is_published" type="checkbox" class="w-4 h-4 rounded accent-[#D4AF37]" />
              <span>প্রকাশিত (শিক্ষার্থীরা দেখবে ও নোটিফিকেশন পাবে)</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer text-xs text-[var(--text-primary)]">
              <input v-model="lessonForm.is_free_preview" type="checkbox" class="w-4 h-4 rounded accent-[#D4AF37]" />
              <span>ফ্রি প্রিভিউ</span>
            </label>
          </div>

          <!-- ============ OPTIONAL DOWNLOADABLE RESOURCES ============ -->
          <div class="p-4 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] space-y-3">
            <div class="flex items-center justify-between gap-2">
              <div>
                <p class="text-xs font-bold text-[var(--text-primary)]">📎 ডাউনলোডযোগ্য রিসোর্স <span class="text-[10px] font-normal text-[var(--text-muted)]">(ঐচ্ছিক)</span></p>
                <p class="text-[10px] text-[var(--text-muted)]">PDF, স্লাইড, শিট, ZIP ফাইল অথবা যেকোনো লিংক যুক্ত করুন।</p>
              </div>
            </div>

            <p v-if="!currentLessonId" class="text-[11px] text-amber-500">
              লেসনটি প্রথমে সংরক্ষণ করুন — তারপর এখানে রিসোর্স যুক্ত করা যাবে।
            </p>

            <template v-else>
              <div v-if="lessonResources.length" class="space-y-1.5">
                <div v-for="res in lessonResources" :key="res.id" class="flex items-center justify-between gap-2 p-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs">
                  <div class="min-w-0 flex items-center gap-2">
                    <span>{{ res.resource_type === 'link' ? '🔗' : '📄' }}</span>
                    <div class="min-w-0">
                      <a :href="res.file_path" target="_blank" rel="noopener" class="font-bold text-[var(--text-primary)] hover:text-[#D4AF37] truncate block">{{ res.title }}</a>
                      <p class="text-[10px] text-[var(--text-muted)] truncate">{{ res.resource_type === 'link' ? res.file_path : `${(res.file_type || '').toUpperCase()} · ${formatFileSize(res.file_size_bytes)}` }}</p>
                    </div>
                  </div>
                  <button type="button" @click="deleteLessonResource(res.id)" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-500/10 cursor-pointer shrink-0" title="মুছুন">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-[auto_1fr] gap-2 items-start">
                <div class="flex gap-1.5">
                  <button type="button" @click="resourceForm.resource_type = 'file'" :class="['px-3 py-2 rounded-lg text-[11px] font-bold cursor-pointer', resourceForm.resource_type === 'file' ? 'bg-[#D4AF37] text-slate-950' : 'bg-[var(--bg-elevated)] text-[var(--text-secondary)]']">ফাইল</button>
                  <button type="button" @click="resourceForm.resource_type = 'link'" :class="['px-3 py-2 rounded-lg text-[11px] font-bold cursor-pointer', resourceForm.resource_type === 'link' ? 'bg-[#D4AF37] text-slate-950' : 'bg-[var(--bg-elevated)] text-[var(--text-secondary)]']">লিংক</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <input v-model="resourceForm.title" type="text" class="px-3 py-2 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="রিসোর্সের নাম *" />
                  <input v-if="resourceForm.resource_type === 'link'" v-model="resourceForm.url" type="url" class="px-3 py-2 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="https://..." />
                  <input v-else ref="resourceFileInput" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.zip,.rar,.7z,.jpg,.jpeg,.png,.webp,.gif,.mp3,.mp4,.m4a" class="text-[11px] text-[var(--text-secondary)] file:mr-2 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-[var(--bg-elevated)] file:text-[var(--text-primary)] file:text-[11px] file:font-bold" @change="onResourceFileChange" />
                  <input v-model="resourceForm.description" type="text" class="sm:col-span-2 px-3 py-2 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" placeholder="সংক্ষিপ্ত বিবরণ (ঐচ্ছিক)" />
                </div>
              </div>
              <div class="flex justify-end">
                <button type="button" @click="addLessonResource" :disabled="addingResource" class="px-4 py-2 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 font-bold text-xs cursor-pointer disabled:opacity-50">
                  {{ addingResource ? 'যুক্ত হচ্ছে...' : '+ রিসোর্স যুক্ত করুন' }}
                </button>
              </div>
            </template>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-[var(--border-subtle)]">
            <button type="button" @click="showLessonEditor = false" class="px-4 py-2 rounded-xl text-xs text-[var(--text-secondary)] cursor-pointer">বন্ধ করুন</button>
            <button type="submit" :disabled="savingLesson" class="px-5 py-2 rounded-xl bg-[#D4AF37] text-slate-950 font-bold text-xs cursor-pointer disabled:opacity-50">
              {{ savingLesson ? 'সংরক্ষণ হচ্ছে...' : 'সংরক্ষণ করুন' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- SUB-MODAL: BATCH CREATE / EDIT -->
    <!-- ========================================================================= -->
    <div v-if="showBatchEditor" class="fixed inset-0 z-60 bg-black/85 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-lg w-full max-h-[92dvh] overflow-y-auto p-5 sm:p-6 space-y-4 shadow-2xl safe-bottom">
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
          <h3 class="text-sm font-bold text-[var(--text-primary)]">
            {{ isEditingBatch ? (themeStore.locale === 'bn' ? 'ব্যাচ এডিট করুন' : 'Edit Batch') : (themeStore.locale === 'bn' ? 'নতুন ব্যাচ তৈরি' : 'Create Batch') }}
          </h3>
          <button @click="showBatchEditor = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitBatchForm" class="space-y-3">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">ব্যাচ নম্বর *</label>
              <input v-model="batchForm.batch_number" required type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]" placeholder="Batch-01" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">স্ট্যাটাস</label>
              <select v-model="batchForm.status" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]">
                <option value="enrolling">ভর্তি চলছে (Enrolling)</option>
                <option value="upcoming">আসন্ন (Upcoming)</option>
                <option value="ongoing">চলমান (Ongoing)</option>
                <option value="completed">সম্পন্ন (Completed)</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">ব্যাচের শিরোনাম (বাংলা)</label>
            <input v-model="batchForm.title_bn" type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]" placeholder="অফলাইন উইকেন্ড ইভনিং ব্যাচ (মিরপুর ক্যাম্পাস)" />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">ক্লাস শুরুর তারিখ *</label>
              <input v-model="batchForm.start_date" required type="date" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">আসন সংখ্যা *</label>
              <input v-model.number="batchForm.seat_capacity" required type="number" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]" placeholder="30" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">ক্লাসের দিন</label>
              <input v-model="batchForm.class_days" type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]" placeholder="শুক্র ও শনিবার" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">ক্লাসের সময়</label>
              <input v-model="batchForm.class_time" type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]" placeholder="বিকাল ৩:০০ - সন্ধ্যা ৬:০০" />
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-[var(--border-subtle)]">
            <button type="button" @click="showBatchEditor = false" class="px-4 py-2 rounded-xl text-xs text-[var(--text-secondary)] cursor-pointer">বাতিল</button>
            <button type="submit" class="px-5 py-2 rounded-xl bg-[#D4AF37] text-slate-950 font-bold text-xs cursor-pointer">সংরক্ষণ করুন</button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- SUB-MODAL: REVIEW CREATE -->
    <!-- ========================================================================= -->
    <div v-if="showReviewEditor" class="fixed inset-0 z-60 bg-black/85 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-lg w-full max-h-[92dvh] overflow-y-auto p-5 sm:p-6 space-y-4 shadow-2xl safe-bottom">
        <div class="flex items-center justify-between border-b border-[var(--border-subtle)] pb-3">
          <h3 class="text-sm font-bold text-[var(--text-primary)]">নতুন কোর্স রিভিউ যোগ করুন</h3>
          <button @click="showReviewEditor = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitReviewForm" class="space-y-3">
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">রেটিং (1-5 Star) *</label>
            <select v-model.number="reviewForm.rating" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] font-bold text-amber-500">
              <option :value="5">5 Stars (অসাধারণ)</option>
              <option :value="4">4 Stars (খুব ভালো)</option>
              <option :value="3">3 Stars (সন্তোষজনক)</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">রিভিউ মন্তব্য *</label>
            <textarea v-model="reviewForm.comment" required rows="3" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)]" placeholder="ইমিশা একাডেমি থেকে এয়ার টিকেটিং ও ভিসা প্রসেসিং কোর্সটি করে আমি এখন একটি স্বনামধন্য ট্রাভেল এজেন্সিতে কর্মরত..."></textarea>
          </div>
          <div class="pt-1">
            <label class="flex items-center gap-2 cursor-pointer text-xs text-[var(--text-primary)]">
              <input v-model="reviewForm.is_approved" type="checkbox" class="w-4 h-4 rounded accent-[#D4AF37]" />
              <span>অনুমোদিত হিসেবে সরাসরি প্রদর্শন করুন (Approved)</span>
            </label>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-[var(--border-subtle)]">
            <button type="button" @click="showReviewEditor = false" class="px-4 py-2 rounded-xl text-xs text-[var(--text-secondary)] cursor-pointer">বাতিল</button>
            <button type="submit" class="px-5 py-2 rounded-xl bg-[#D4AF37] text-slate-950 font-bold text-xs cursor-pointer">রিভিউ যোগ করুন</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive } from 'vue';
import apiClient from '../../api/client';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';
import { formatCurrency, formatNumber, getLocalized } from '../../utils/locale';
import { onImageError, getInitialsAvatar } from '../../utils/imageFallback';
import RichTextEditor from '../../components/ui/RichTextEditor.vue';

const courses = ref<any[]>([]);
const availableCategories = ref<any[]>([]);
const availableInstructors = ref<any[]>([]);
const loading = ref(true);
const searchQuery = ref('');
const statusFilter = ref('');

// 1. Comprehensive Course Modal (Create & Edit)
const showCourseModal = ref(false);
const isEditingCourse = ref(false);
const editingCourseId = ref<number | null>(null);
const courseModalTab = ref('basic');
const submittingCourse = ref(false);

const courseForm = reactive({
  title_bn: '',
  title_en: '',
  subtitle_bn: '',
  subtitle_en: '',
  category_id: 1,
  format: 'hybrid',
  level: 'all_levels',
  regular_price: 0,
  sale_price: 0,
  total_hours: 0,
  duration_weeks: '',
  description_bn: '',
  description_en: '',
  promo_video_url: '',
  thumbnail: '',
  features_bn: [] as string[],
  features_en: [] as string[],
  prerequisites_bn: [] as string[],
  prerequisites_en: [] as string[],
  target_audience_bn: [] as string[],
  target_audience_en: [] as string[],
  is_published: true,
  is_featured: false,
});

// 2. Curriculum Modal
const showCurriculumModal = ref(false);
const selectedCourseForCurriculum = ref<any>(null);
const curriculumModules = ref<any[]>([]);

// Sub-modal for Module
const showModuleEditor = ref(false);
const isEditingModule = ref(false);
const currentModuleId = ref<number | null>(null);
const moduleForm = reactive({
  title_bn: '',
  title_en: '',
  summary_bn: '',
  summary_en: '',
});

// Sub-modal for Lesson
const showLessonEditor = ref(false);
const isEditingLesson = ref(false);
const currentLessonParentModuleId = ref<number | null>(null);
const currentLessonId = ref<number | null>(null);
type QuizQuestion = { question: string; options: string[]; correct_index: number; explanation: string };

const lessonForm = reactive({
  title_bn: '',
  title_en: '',
  lesson_type: 'video' as 'video' | 'text' | 'quiz',
  duration: '30 মিনিট',
  video_provider: 'youtube',
  video_url: '',
  short_description: '',
  content: '',
  quiz_data: { pass_percentage: 60, questions: [] as QuizQuestion[] },
  is_free_preview: false,
  is_published: true,
});
const savingLesson = ref(false);
const videoSource = ref<'link' | 'upload'>('link');
const mediaUploadProgress = ref(0);

const lessonTypeOptions = [
  { value: 'video' as const, icon: '🎬', label: 'ভিডিও', hint: 'লিংক বা আপলোড' },
  { value: 'text' as const, icon: '📝', label: 'টেক্সট', hint: 'রিচ টেক্সট আর্টিকেল' },
  { value: 'quiz' as const, icon: '❓', label: 'কুইজ', hint: 'MCQ প্রশ্ন ও স্কোর' },
];

// Optional downloadable resources for the lesson being edited
const lessonResources = ref<any[]>([]);
const addingResource = ref(false);
const resourceFileInput = ref<HTMLInputElement | null>(null);
const resourceForm = reactive({
  resource_type: 'file' as 'file' | 'link',
  title: '',
  url: '',
  description: '',
  file: null as File | null,
});

// 3. Batches Modal
const showBatchesModal = ref(false);
const selectedCourseForBatches = ref<any>(null);
const courseBatches = ref<any[]>([]);
const showBatchEditor = ref(false);
const isEditingBatch = ref(false);
const currentBatchId = ref<number | null>(null);
const batchForm = reactive({
  batch_number: '',
  title_bn: '',
  title_en: '',
  start_date: '',
  seat_capacity: 30,
  class_days: 'শুক্র ও শনিবার',
  class_time: 'বিকাল ৩:০০ - সন্ধ্যা ৬:০০',
  status: 'enrolling',
});

// 4. Reviews Modal
const showReviewsModal = ref(false);
const selectedCourseForReviews = ref<any>(null);
const courseReviewsList = ref<any[]>([]);
const showReviewEditor = ref(false);
const reviewForm = reactive({
  rating: 5,
  comment: '',
  is_approved: true,
});

// 5. Assign Mentors Modal
const showAssignMentorsModal = ref(false);
const selectedCourseForMentors = ref<any>(null);
const assignedMentorIds = ref<number[]>([]);
const savingMentors = ref(false);

// 6. Add Instructor Modal
const showAddInstructorModal = ref(false);
const submittingInstructor = ref(false);
const newInstructorForm = reactive({
  name_bn: '',
  name_en: '',
  title_bn: '',
  title_en: '',
  avatar: '',
  experience_years: '8+',
  organization: 'Emisha Tours & Travels',
  bio_bn: '',
  bio_en: '',
});

const toast = useToastStore();
const themeStore = useThemeStore();

// Fetch Course List
async function fetchCourses() {
  try {
    loading.value = true;
    const res = await apiClient.get('/admin/courses', {
      params: {
        search: searchQuery.value || undefined,
        status: statusFilter.value || undefined,
      },
    });
    if (res.data.status === 'success') {
      courses.value = res.data.data.courses || res.data.data.data || res.data.data || [];
    }
  } catch (err) {
    console.error('Failed to load courses list', err);
  } finally {
    loading.value = false;
  }
}

// Fetch Categories and Instructors
async function fetchMetadata() {
  try {
    const [catRes, instRes] = await Promise.all([
      apiClient.get('/admin/categories').catch(() => ({ data: { data: [] } })),
      apiClient.get('/admin/instructors').catch(() => ({ data: { data: [] } })),
    ]);

    availableCategories.value = catRes.data.data || [];
    if (availableCategories.value.length > 0 && !courseForm.category_id) {
      courseForm.category_id = availableCategories.value[0].id;
    }

    availableInstructors.value = instRes.data.data || [];
  } catch (err) {
    console.error('Failed to load admin metadata', err);
  }
}

// Open Create Modal
function openCreateModal() {
  isEditingCourse.value = false;
  editingCourseId.value = null;
  courseModalTab.value = 'basic';

  courseForm.title_bn = '';
  courseForm.title_en = '';
  courseForm.subtitle_bn = '';
  courseForm.subtitle_en = '';
  courseForm.category_id = availableCategories.value[0]?.id || 1;
  courseForm.format = 'hybrid';
  courseForm.level = 'all_levels';
  courseForm.regular_price = 0;
  courseForm.sale_price = 0;
  courseForm.total_hours = 0;
  courseForm.duration_weeks = '';
  courseForm.description_bn = '';
  courseForm.description_en = '';
  courseForm.promo_video_url = '';
  courseForm.thumbnail = '';
  thumbnailPreviewError.value = false;
  courseForm.features_bn = [];
  courseForm.features_en = [];
  courseForm.prerequisites_bn = [];
  courseForm.prerequisites_en = [];
  courseForm.target_audience_bn = [];
  courseForm.target_audience_en = [];
  courseForm.is_published = true;
  courseForm.is_featured = false;

  showCourseModal.value = true;
}

// Open Edit Course Modal
async function openEditCourseModal(course: any) {
  try {
    isEditingCourse.value = true;
    editingCourseId.value = course.id;
    courseModalTab.value = 'basic';

    // Fetch full course details to get all arrays
    const res = await apiClient.get(`/admin/courses/${course.id}`);
    const data = res.data.data;

    courseForm.title_bn = data.title_bn || '';
    courseForm.title_en = data.title_en || '';
    courseForm.subtitle_bn = data.subtitle_bn || '';
    courseForm.subtitle_en = data.subtitle_en || '';
    courseForm.category_id = data.category_id || availableCategories.value[0]?.id || 1;
    courseForm.format = data.format || 'hybrid';
    courseForm.level = data.level || 'all_levels';
    courseForm.regular_price = Number(data.regular_price) || 0;
    courseForm.sale_price = Number(data.sale_price) || 0;
    courseForm.total_hours = data.total_hours || 0;
    courseForm.duration_weeks = data.duration_weeks || '';
    courseForm.description_bn = data.description_bn || '';
    courseForm.description_en = data.description_en || '';
    courseForm.promo_video_url = data.promo_video_url || '';
    courseForm.thumbnail = data.thumbnail || '';
    thumbnailPreviewError.value = false;
    courseForm.features_bn = data.features_bn && data.features_bn.length > 0 ? [...data.features_bn] : [];
    courseForm.features_en = data.features_en || [];
    courseForm.prerequisites_bn = data.prerequisites_bn && data.prerequisites_bn.length > 0 ? [...data.prerequisites_bn] : [];
    courseForm.prerequisites_en = data.prerequisites_en || [];
    courseForm.target_audience_bn = data.target_audience_bn && data.target_audience_bn.length > 0 ? [...data.target_audience_bn] : [];
    courseForm.target_audience_en = data.target_audience_en || [];
    courseForm.is_published = data.status === 'published';
    courseForm.is_featured = !!data.is_featured;

    showCourseModal.value = true;
  } catch (err) {
    toast.error('কোর্স তথ্য লোড করতে ব্যর্থ হয়েছে');
  }
}

// Dynamic Array Helpers
function addFeatureItem() {
  courseForm.features_bn.push('');
}
function removeFeatureItem(idx: number) {
  courseForm.features_bn.splice(idx, 1);
}
function addPrerequisiteItem() {
  courseForm.prerequisites_bn.push('');
}
function removePrerequisiteItem(idx: number) {
  courseForm.prerequisites_bn.splice(idx, 1);
}
function addAudienceItem() {
  courseForm.target_audience_bn.push('');
}
function removeAudienceItem(idx: number) {
  courseForm.target_audience_bn.splice(idx, 1);
}

// Course thumbnail upload
const uploadingThumbnail = ref(false);
const thumbnailUploadProgress = ref(0);
const thumbnailPreviewError = ref(false);

async function uploadCourseThumbnail(e: Event) {
  const input = e.target as HTMLInputElement;
  const file = input.files?.[0];
  input.value = '';
  if (!file) return;
  if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type)) {
    toast.error(themeStore.locale === 'bn' ? 'শুধু JPG, PNG, WebP বা GIF ছবি আপলোড করা যাবে।' : 'Only JPG, PNG, WebP or GIF images are allowed.');
    return;
  }
  if (file.size > 5 * 1024 * 1024) {
    toast.error(themeStore.locale === 'bn' ? 'ছবির সাইজ সর্বোচ্চ ৫MB হতে পারবে।' : 'Image must be 5 MB or smaller.');
    return;
  }

  const form = new FormData();
  form.append('image', file);
  form.append('folder', 'course_thumbnails');
  uploadingThumbnail.value = true;
  thumbnailUploadProgress.value = 1;
  try {
    const res = await apiClient.post('/admin/uploads/image', form, {
      headers: { 'Content-Type': 'multipart/form-data' },
      timeout: 0,
      onUploadProgress: (evt) => {
        if (evt.total) thumbnailUploadProgress.value = Math.min(99, Math.round((evt.loaded / evt.total) * 100));
      },
    });
    courseForm.thumbnail = res.data.data.url;
    thumbnailPreviewError.value = false;
    thumbnailUploadProgress.value = 100;
    toast.success(themeStore.locale === 'bn' ? 'থাম্বনেইল আপলোড হয়েছে — সংরক্ষণ করতে ভুলবেন না।' : 'Thumbnail uploaded — remember to save the course.');
  } catch (err: any) {
    const errors = err.response?.data?.errors;
    toast.error(errors?.image?.[0] || err.response?.data?.message || (themeStore.locale === 'bn' ? 'ছবি আপলোড ব্যর্থ হয়েছে।' : 'Image upload failed.'));
  } finally {
    uploadingThumbnail.value = false;
    thumbnailUploadProgress.value = 0;
  }
}

// Submit Course Form (Create or Update)
async function submitCourseForm() {
  if (courseForm.thumbnail && !/^(https?:\/\/|\/storage\/)/i.test(courseForm.thumbnail)) {
    toast.error(themeStore.locale === 'bn' ? 'থাম্বনেইলের লিংক https:// দিয়ে শুরু হতে হবে।' : 'Thumbnail URL must start with https://');
    courseModalTab.value = 'basic';
    return;
  }
  try {
    submittingCourse.value = true;
    const payload = {
      ...courseForm,
      thumbnail: courseForm.thumbnail.trim() || null,
      // Empty numbers go as null (the API rejects 0 hours)
      total_hours: Number(courseForm.total_hours) > 0 ? Number(courseForm.total_hours) : null,
      duration_weeks: courseForm.duration_weeks ? String(courseForm.duration_weeks) : null,
      features_bn: courseForm.features_bn.filter(s => s && s.trim()),
      prerequisites_bn: courseForm.prerequisites_bn.filter(s => s && s.trim()),
      target_audience_bn: courseForm.target_audience_bn.filter(s => s && s.trim()),
      status: courseForm.is_published ? 'published' : 'draft',
    };

    if (isEditingCourse.value && editingCourseId.value) {
      await apiClient.put(`/admin/courses/${editingCourseId.value}`, payload);
      toast.success(themeStore.locale === 'bn' ? 'কোর্স তথ্য সফলভাবে আপডেট হয়েছে!' : 'Course updated successfully!');
    } else {
      await apiClient.post('/admin/courses', payload);
      toast.success(themeStore.locale === 'bn' ? 'কোর্স সফলভাবে তৈরি হয়েছে!' : 'Course created successfully!');
    }

    showCourseModal.value = false;
    fetchCourses();
  } catch (err: any) {
    toast.error(err.response?.data?.message || (themeStore.locale === 'bn' ? 'সংরক্ষণ ব্যর্থ হয়েছে' : 'Failed to save'));
  } finally {
    submittingCourse.value = false;
  }
}

// Delete Course
async function confirmDeleteCourse(course: any) {
  const confirmMsg = themeStore.locale === 'bn'
    ? `আপনি কি নিশ্চিত যে "${getLocalized(course, 'title', 'bn')}" কোর্সটি মুছে ফেলতে চান?`
    : `Are you sure you want to delete "${getLocalized(course, 'title', 'en')}"?`;

  if (confirm(confirmMsg)) {
    try {
      await apiClient.delete(`/admin/courses/${course.id}`);
      toast.success(themeStore.locale === 'bn' ? 'কোর্স সফলভাবে মুছে ফেলা হয়েছে।' : 'Course deleted successfully.');
      fetchCourses();
    } catch (err) {
      toast.error('কোর্স ডিলিট ব্যর্থ হয়েছে');
    }
  }
}

// =========================================================================
// CURRICULUM MANAGEMENT (MODULES & LESSONS)
// =========================================================================
async function openCurriculumModal(course: any) {
  selectedCourseForCurriculum.value = course;
  await loadCurriculum();
  showCurriculumModal.value = true;
}

async function loadCurriculum() {
  if (!selectedCourseForCurriculum.value) return;
  try {
    const res = await apiClient.get(`/admin/courses/${selectedCourseForCurriculum.value.id}/curriculum`);
    curriculumModules.value = res.data.data.modules || [];
  } catch (err) {
    console.error('Failed to load curriculum', err);
  }
}

function openAddModuleModal() {
  isEditingModule.value = false;
  currentModuleId.value = null;
  moduleForm.title_bn = '';
  moduleForm.title_en = '';
  moduleForm.summary_bn = '';
  moduleForm.summary_en = '';
  showModuleEditor.value = true;
}

function openEditModuleModal(mod: any) {
  isEditingModule.value = true;
  currentModuleId.value = mod.id;
  moduleForm.title_bn = mod.title_bn;
  moduleForm.title_en = mod.title_en;
  moduleForm.summary_bn = mod.summary_bn || '';
  moduleForm.summary_en = mod.summary_en || '';
  showModuleEditor.value = true;
}

async function submitModuleForm() {
  try {
    if (isEditingModule.value && currentModuleId.value) {
      await apiClient.put(`/admin/modules/${currentModuleId.value}`, moduleForm);
      toast.success('মডিউল আপডেট হয়েছে!');
    } else if (selectedCourseForCurriculum.value) {
      await apiClient.post(`/admin/courses/${selectedCourseForCurriculum.value.id}/modules`, moduleForm);
      toast.success('নতুন মডিউল তৈরি হয়েছে!');
    }
    showModuleEditor.value = false;
    await loadCurriculum();
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'মডিউল সংরক্ষণ ব্যর্থ হয়েছে');
  }
}

async function deleteModule(moduleId: number) {
  if (confirm('আপনি কি এই মডিউল ও এর ভেতরের সকল লেসন মুছে ফেলতে চান?')) {
    try {
      await apiClient.delete(`/admin/modules/${moduleId}`);
      toast.success('মডিউল মুছে ফেলা হয়েছে।');
      await loadCurriculum();
    } catch (err) {
      toast.error('মডিউল ডিলিট ব্যর্থ হয়েছে');
    }
  }
}

function lessonTypeBadge(type: string) {
  switch (type) {
    case 'text':
      return { label: 'টেক্সট', cls: 'bg-sky-500/10 text-sky-500' };
    case 'quiz':
      return { label: 'কুইজ', cls: 'bg-purple-500/10 text-purple-500' };
    default:
      return { label: 'ভিডিও', cls: 'bg-rose-500/10 text-rose-500' };
  }
}

function formatFileSize(bytes: number) {
  if (!bytes) return '';
  if (bytes < 1024 * 1024) return `${Math.max(1, Math.round(bytes / 1024))} KB`;
  return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}

function resetResourceForm() {
  resourceForm.title = '';
  resourceForm.url = '';
  resourceForm.description = '';
  resourceForm.file = null;
  if (resourceFileInput.value) resourceFileInput.value.value = '';
}

function fillLessonForm(lesson: any | null) {
  lessonForm.title_bn = lesson?.title_bn || '';
  lessonForm.title_en = lesson?.title_en || '';
  lessonForm.lesson_type = (['video', 'text', 'quiz'].includes(lesson?.lesson_type) ? lesson.lesson_type : 'video');
  lessonForm.duration = lesson?.duration || '30 মিনিট';
  lessonForm.video_provider = lesson?.video_provider || 'youtube';
  lessonForm.video_url = lesson?.video_url || '';
  lessonForm.short_description = lesson?.short_description || '';
  lessonForm.content = lesson?.content || '';
  lessonForm.quiz_data = {
    pass_percentage: lesson?.quiz_data?.pass_percentage ?? 60,
    questions: (lesson?.quiz_data?.questions || []).map((q: any) => ({
      question: q.question || '',
      options: [...(q.options || ['', ''])],
      correct_index: Number(q.correct_index ?? 0),
      explanation: q.explanation || '',
    })),
  };
  lessonForm.is_free_preview = !!lesson?.is_free_preview;
  lessonForm.is_published = lesson ? lesson.is_published !== false : true;
  videoSource.value = lesson?.video_provider === 'html5' && lesson?.video_url?.startsWith('/storage/') ? 'upload' : 'link';
  mediaUploadProgress.value = 0;
  lessonResources.value = [...(lesson?.resources || [])];
  resourceForm.resource_type = 'file';
  resetResourceForm();
}

function openAddLessonModal(mod: any) {
  isEditingLesson.value = false;
  currentLessonParentModuleId.value = mod.id;
  currentLessonId.value = null;
  fillLessonForm(null);
  showLessonEditor.value = true;
}

function openEditLessonModal(mod: any, lesson: any) {
  isEditingLesson.value = true;
  currentLessonParentModuleId.value = mod.id;
  currentLessonId.value = lesson.id;
  fillLessonForm(lesson);
  showLessonEditor.value = true;
}

function addQuizQuestion() {
  lessonForm.quiz_data.questions.push({ question: '', options: ['', '', '', ''], correct_index: 0, explanation: '' });
}

function removeQuizQuestion(index: number) {
  lessonForm.quiz_data.questions.splice(index, 1);
}

function removeQuizOption(q: QuizQuestion, index: number) {
  q.options.splice(index, 1);
  if (q.correct_index >= q.options.length) q.correct_index = q.options.length - 1;
  else if (q.correct_index > index) q.correct_index -= 1;
}

function buildLessonPayload() {
  const payload: Record<string, any> = {
    title_bn: lessonForm.title_bn,
    title_en: lessonForm.title_en || lessonForm.title_bn,
    lesson_type: lessonForm.lesson_type,
    duration: lessonForm.duration,
    short_description: lessonForm.short_description || null,
    content: lessonForm.content || null,
    is_free_preview: lessonForm.is_free_preview,
    is_published: lessonForm.is_published,
  };
  if (lessonForm.lesson_type === 'video') {
    payload.video_provider = lessonForm.video_provider;
    payload.video_url = lessonForm.video_url || null;
  }
  if (lessonForm.lesson_type === 'quiz') {
    payload.quiz_data = {
      pass_percentage: Number(lessonForm.quiz_data.pass_percentage) || 0,
      questions: lessonForm.quiz_data.questions.map((q) => ({
        question: q.question.trim(),
        options: q.options.map((o) => o.trim()),
        correct_index: q.correct_index,
        explanation: q.explanation?.trim() || null,
      })),
    };
  }
  return payload;
}

async function submitLessonForm() {
  if (lessonForm.lesson_type === 'quiz' && lessonForm.quiz_data.questions.length === 0) {
    toast.error('কুইজে অন্তত একটি প্রশ্ন যুক্ত করুন।');
    return;
  }
  if (lessonForm.lesson_type === 'text' && !lessonForm.content) {
    toast.error('টেক্সট লেসনের লেখা যুক্ত করুন।');
    return;
  }
  savingLesson.value = true;
  try {
    const payload = buildLessonPayload();
    if (isEditingLesson.value && currentLessonId.value) {
      await apiClient.put(`/admin/lessons/${currentLessonId.value}`, payload);
      toast.success('লেসন আপডেট হয়েছে!');
      showLessonEditor.value = false;
    } else if (currentLessonParentModuleId.value) {
      const res = await apiClient.post(`/admin/modules/${currentLessonParentModuleId.value}/lessons`, payload);
      // Stay in the editor so optional resources can be attached right away
      isEditingLesson.value = true;
      currentLessonId.value = res.data.data.id;
      lessonResources.value = res.data.data.resources || [];
      toast.success('লেসন যুক্ত হয়েছে! চাইলে এখন ডাউনলোডযোগ্য রিসোর্স যুক্ত করুন।');
    }
    await loadCurriculum();
  } catch (err: any) {
    const errors = err.response?.data?.errors;
    const first = errors ? (Object.values(errors)[0] as string[])?.[0] : null;
    toast.error(first || err.response?.data?.message || 'লেসন সংরক্ষণ ব্যর্থ হয়েছে');
  } finally {
    savingLesson.value = false;
  }
}

async function uploadLessonVideo(e: Event) {
  const input = e.target as HTMLInputElement;
  const file = input.files?.[0];
  if (!file) return;
  const form = new FormData();
  form.append('file', file);
  mediaUploadProgress.value = 1;
  try {
    const res = await apiClient.post('/admin/lessons/upload-media', form, {
      headers: { 'Content-Type': 'multipart/form-data' },
      timeout: 0,
      onUploadProgress: (evt) => {
        if (evt.total) mediaUploadProgress.value = Math.min(99, Math.round((evt.loaded / evt.total) * 100));
      },
    });
    lessonForm.video_url = res.data.data.url;
    lessonForm.video_provider = 'html5';
    mediaUploadProgress.value = 100;
    toast.success('ভিডিও আপলোড সম্পন্ন হয়েছে।');
  } catch (err: any) {
    mediaUploadProgress.value = 0;
    const errors = err.response?.data?.errors;
    toast.error(errors?.file?.[0] || err.response?.data?.message || 'ভিডিও আপলোড ব্যর্থ হয়েছে (সার্ভারের আপলোড লিমিট চেক করুন)।');
  } finally {
    input.value = '';
  }
}

function onResourceFileChange(e: Event) {
  const file = (e.target as HTMLInputElement).files?.[0] || null;
  resourceForm.file = file;
  if (file && !resourceForm.title) {
    resourceForm.title = file.name.replace(/\.[^.]+$/, '');
  }
}

async function addLessonResource() {
  if (!currentLessonId.value) return;
  if (!resourceForm.title.trim()) {
    toast.error('রিসোর্সের নাম দিন।');
    return;
  }
  const form = new FormData();
  form.append('title', resourceForm.title.trim());
  form.append('resource_type', resourceForm.resource_type);
  if (resourceForm.description) form.append('description', resourceForm.description);
  if (resourceForm.resource_type === 'file') {
    if (!resourceForm.file) {
      toast.error('একটি ফাইল নির্বাচন করুন।');
      return;
    }
    form.append('file', resourceForm.file);
  } else {
    if (!/^https?:\/\//i.test(resourceForm.url)) {
      toast.error('সঠিক লিংক (https://...) দিন।');
      return;
    }
    form.append('file_path', resourceForm.url);
  }

  addingResource.value = true;
  try {
    const res = await apiClient.post(`/admin/lessons/${currentLessonId.value}/resources`, form, {
      headers: { 'Content-Type': 'multipart/form-data' },
      timeout: 0,
    });
    lessonResources.value.push(res.data.data);
    resetResourceForm();
    toast.success('রিসোর্স যুক্ত হয়েছে।');
    await loadCurriculum();
  } catch (err: any) {
    const errors = err.response?.data?.errors;
    const first = errors ? (Object.values(errors)[0] as string[])?.[0] : null;
    toast.error(first || err.response?.data?.message || 'রিসোর্স যুক্ত করা যায়নি।');
  } finally {
    addingResource.value = false;
  }
}

async function deleteLessonResource(resourceId: number) {
  if (!confirm('এই রিসোর্সটি মুছে ফেলতে চান?')) return;
  try {
    await apiClient.delete(`/admin/resources/${resourceId}`);
    lessonResources.value = lessonResources.value.filter((r) => r.id !== resourceId);
    toast.success('রিসোর্স মুছে ফেলা হয়েছে।');
    await loadCurriculum();
  } catch {
    toast.error('রিসোর্স মুছে ফেলা যায়নি।');
  }
}

async function deleteLesson(lessonId: number) {
  if (confirm('আপনি কি এই লেসনটি মুছে ফেলতে চান?')) {
    try {
      await apiClient.delete(`/admin/lessons/${lessonId}`);
      toast.success('লেসন মুছে ফেলা হয়েছে।');
      await loadCurriculum();
    } catch (err) {
      toast.error('লেসন ডিলিট ব্যর্থ হয়েছে');
    }
  }
}

// =========================================================================
// BATCHES MANAGEMENT
// =========================================================================
async function openBatchesModal(course: any) {
  selectedCourseForBatches.value = course;
  await loadBatches();
  showBatchesModal.value = true;
}

async function loadBatches() {
  if (!selectedCourseForBatches.value) return;
  try {
    const res = await apiClient.get(`/admin/courses/${selectedCourseForBatches.value.id}/batches`);
    courseBatches.value = res.data.data || [];
  } catch (err) {
    console.error('Failed to load batches', err);
  }
}

function openAddBatchModal() {
  isEditingBatch.value = false;
  currentBatchId.value = null;
  batchForm.batch_number = `Batch-0${courseBatches.value.length + 1}`;
  batchForm.title_bn = 'অফলাইন উইকেন্ড ইভনিং ব্যাচ (মিরপুর ক্যাম্পাস)';
  batchForm.title_en = 'Offline Weekend Evening Batch (Mirpur Campus)';
  batchForm.start_date = new Date().toISOString().split('T')[0];
  batchForm.seat_capacity = 30;
  batchForm.class_days = 'শুক্র ও শনিবার';
  batchForm.class_time = 'বিকাল ৩:০০ - সন্ধ্যা ৬:০০';
  batchForm.status = 'enrolling';
  showBatchEditor.value = true;
}

function openEditBatchModal(batch: any) {
  isEditingBatch.value = true;
  currentBatchId.value = batch.id;
  batchForm.batch_number = batch.batch_number;
  batchForm.title_bn = batch.title_bn || '';
  batchForm.title_en = batch.title_en || '';
  batchForm.start_date = batch.start_date ? batch.start_date.split('T')[0] : '';
  batchForm.seat_capacity = batch.seat_capacity || 30;
  batchForm.class_days = batch.class_days || '';
  batchForm.class_time = batch.class_time || '';
  batchForm.status = batch.status || 'enrolling';
  showBatchEditor.value = true;
}

async function submitBatchForm() {
  try {
    if (isEditingBatch.value && currentBatchId.value) {
      await apiClient.put(`/admin/batches/${currentBatchId.value}`, batchForm);
      toast.success('ব্যাচ তথ্য আপডেট হয়েছে!');
    } else if (selectedCourseForBatches.value) {
      await apiClient.post(`/admin/courses/${selectedCourseForBatches.value.id}/batches`, batchForm);
      toast.success('নতুন ব্যাচ যুক্ত হয়েছে!');
    }
    showBatchEditor.value = false;
    await loadBatches();
    fetchCourses();
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'ব্যাচ সংরক্ষণ ব্যর্থ হয়েছে');
  }
}

async function deleteBatch(batchId: number) {
  if (confirm('আপনি কি এই ব্যাচটি মুছে ফেলতে চান?')) {
    try {
      await apiClient.delete(`/admin/batches/${batchId}`);
      toast.success('ব্যাচ মুছে ফেলা হয়েছে।');
      await loadBatches();
      fetchCourses();
    } catch (err) {
      toast.error('ব্যাচ ডিলিট ব্যর্থ হয়েছে');
    }
  }
}

// =========================================================================
// REVIEWS MANAGEMENT
// =========================================================================
async function openReviewsModal(course: any) {
  selectedCourseForReviews.value = course;
  await loadReviews();
  showReviewsModal.value = true;
}

async function loadReviews() {
  if (!selectedCourseForReviews.value) return;
  try {
    const res = await apiClient.get('/admin/reviews', {
      params: { course_id: selectedCourseForReviews.value.id },
    });
    courseReviewsList.value = res.data.data.reviews || res.data.data || [];
  } catch (err) {
    console.error('Failed to load reviews', err);
  }
}

function openAddReviewModal() {
  reviewForm.rating = 5;
  reviewForm.comment = '';
  reviewForm.is_approved = true;
  showReviewEditor.value = true;
}

async function submitReviewForm() {
  if (!selectedCourseForReviews.value) return;
  try {
    await apiClient.post('/admin/reviews', {
      course_id: selectedCourseForReviews.value.id,
      ...reviewForm,
    });
    toast.success('রিভিউ সফলভাবে যুক্ত হয়েছে!');
    showReviewEditor.value = false;
    await loadReviews();
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'রিভিউ তৈরি ব্যর্থ হয়েছে');
  }
}

async function toggleReviewApproval(rev: any) {
  try {
    await apiClient.put(`/admin/reviews/${rev.id}`, {
      is_approved: !rev.is_approved,
    });
    toast.success('রিভিউ স্ট্যাটাস আপডেট হয়েছে!');
    await loadReviews();
  } catch (err) {
    toast.error('স্ট্যাটাস আপডেট ব্যর্থ হয়েছে');
  }
}

async function deleteReview(revId: number) {
  if (confirm('আপনি কি এই রিভিউটি মুছে ফেলতে চান?')) {
    try {
      await apiClient.delete(`/admin/reviews/${revId}`);
      toast.success('রিভিউ মুছে ফেলা হয়েছে।');
      await loadReviews();
    } catch (err) {
      toast.error('রিভিউ ডিলিট ব্যর্থ হয়েছে');
    }
  }
}

// =========================================================================
// MENTORS ASSIGNMENT & CREATION
// =========================================================================
function openAssignMentorsModal(course: any) {
  selectedCourseForMentors.value = course;
  if (course.instructors && course.instructors.length > 0) {
    assignedMentorIds.value = course.instructors.map((i: any) => i.id);
  } else if (course.instructor_id) {
    assignedMentorIds.value = [course.instructor_id];
  } else {
    assignedMentorIds.value = [];
  }
  showAssignMentorsModal.value = true;
}

async function submitAssignMentors() {
  if (!selectedCourseForMentors.value) return;
  try {
    savingMentors.value = true;
    const res = await apiClient.put(`/admin/courses/${selectedCourseForMentors.value.id}`, {
      instructor_ids: assignedMentorIds.value,
    });
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'কোর্স মেন্টর সফলভাবে আপডেট করা হয়েছে!' : 'Course mentors updated successfully!');
      showAssignMentorsModal.value = false;
      fetchCourses();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'মেন্টর আপডেট ব্যর্থ হয়েছে');
  } finally {
    savingMentors.value = false;
  }
}

function openAddInstructorModal() {
  newInstructorForm.name_bn = '';
  newInstructorForm.name_en = '';
  newInstructorForm.title_bn = '';
  newInstructorForm.title_en = '';
  newInstructorForm.avatar = '';
  newInstructorForm.experience_years = '8+';
  newInstructorForm.organization = 'Emisha Tours & Travels';
  newInstructorForm.bio_bn = '';
  newInstructorForm.bio_en = '';
  showAddInstructorModal.value = true;
}

async function submitCreateInstructor() {
  try {
    submittingInstructor.value = true;
    const res = await apiClient.post('/admin/instructors', newInstructorForm);
    if (res.data.status === 'success') {
      toast.success(themeStore.locale === 'bn' ? 'নতুন মেন্টর সফলভাবে যুক্ত করা হয়েছে!' : 'New mentor added successfully!');
      showAddInstructorModal.value = false;
      await fetchMetadata();
    }
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'মেন্টর তৈরি ব্যর্থ হয়েছে');
  } finally {
    submittingInstructor.value = false;
  }
}

onMounted(() => {
  fetchCourses();
  fetchMetadata();
});
</script>
