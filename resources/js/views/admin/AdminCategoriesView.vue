<template>
  <div class="space-y-8 max-w-7xl mx-auto pb-16">
    
    <!-- 1. TOP HEADER & ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[var(--bg-surface)] p-6 rounded-3xl border border-[var(--border-subtle)] shadow-xs">
      <div>
        <div class="flex items-center gap-2">
          <span class="px-2.5 py-1 rounded-lg bg-[#D4AF37]/15 text-[#D4AF37] text-xs font-bold uppercase tracking-wider">
            {{ themeStore.locale === 'bn' ? 'কোর্স আর্কিটেকচার' : 'Course Architecture' }}
          </span>
          <span class="text-xs text-[var(--text-muted)]">• {{ categories.length }} {{ themeStore.locale === 'bn' ? 'টি ক্যাটাগরি' : 'Categories' }}</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-[var(--text-primary)] tracking-tight mt-1">
          {{ themeStore.locale === 'bn' ? 'কোর্স ক্যাটাগরি ও ক্যারিয়ার ট্র্যাক ম্যানেজমেন্ট' : 'Course Categories & Career Tracks' }}
        </h1>
        <p class="text-xs sm:text-sm text-[var(--text-secondary)] mt-1">
          {{ themeStore.locale === 'bn' ? 'কোর্স ফিল্টারিং, ক্যারিয়ার ট্র্যাক ও ক্যাটাগরি ক্রিয়েট, এডিট, রিমুভ ও কাস্টমাইজ করুন।' : 'Create, customize, edit, and organize course categories and career tracks.' }}
        </p>
      </div>

      <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
        <router-link
          to="/admin/courses"
          class="px-4 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] transition-all touch-target inline-flex items-center gap-1.5"
        >
          <span>← {{ themeStore.locale === 'bn' ? 'কোর্স তালিকা' : 'Courses List' }}</span>
        </router-link>

        <button
          type="button"
          @click="openCreateModal"
          class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-extrabold text-xs hover:brightness-110 shadow-md shadow-[#D4AF37]/20 flex items-center gap-2 transition-all touch-target cursor-pointer shrink-0"
        >
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          <span>{{ themeStore.locale === 'bn' ? 'নতুন ক্যাটাগরি যোগ করুন' : 'Add New Category' }}</span>
        </button>
      </div>
    </div>

    <!-- 2. STATS OVERVIEW CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
      <div class="p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
        <p class="text-xs text-[var(--text-muted)] font-medium">{{ themeStore.locale === 'bn' ? 'মোট ক্যাটাগরি' : 'Total Categories' }}</p>
        <h3 class="text-2xl font-black text-[var(--text-primary)]">{{ categories.length }}</h3>
      </div>
      <div class="p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
        <p class="text-xs text-[var(--text-muted)] font-medium">{{ themeStore.locale === 'bn' ? 'সক্রিয় ক্যাটাগরি' : 'Active Categories' }}</p>
        <h3 class="text-2xl font-black text-emerald-500">{{ categories.filter(c => c.is_active).length }}</h3>
      </div>
      <div class="p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
        <p class="text-xs text-[var(--text-muted)] font-medium">{{ themeStore.locale === 'bn' ? 'আসন্ন / ডেভেলপিং ট্র্যাক' : 'Upcoming Tracks' }}</p>
        <h3 class="text-2xl font-black text-amber-500">{{ categories.filter(c => c.status === 'upcoming' || c.courses_count === 0).length }}</h3>
      </div>
      <div class="p-5 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-1">
        <p class="text-xs text-[var(--text-muted)] font-medium">{{ themeStore.locale === 'bn' ? 'সংযুক্ত মোট কোর্স' : 'Connected Courses' }}</p>
        <h3 class="text-2xl font-black text-[#D4AF37]">{{ totalConnectedCourses }}</h3>
      </div>
    </div>

    <!-- 3. PUBLIC CATALOG PREVIEW STRIP -->
    <div class="p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-[var(--bg-elevated)] via-[var(--bg-card)] to-[var(--bg-surface)] border border-[var(--border-subtle)] space-y-4 shadow-sm">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="text-base">👁️</span>
          <h3 class="text-sm sm:text-base font-bold text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'ওয়েবসাইটে যেভাবে ৫টি ক্যাটাগরি প্রদর্শিত হচ্ছে (Live Frontend Preview)' : 'Live Public Catalog Preview (5 Categories)' }}
          </h3>
        </div>
        <router-link to="/courses" target="_blank" class="text-xs text-[#D4AF37] font-semibold hover:underline inline-flex items-center gap-1">
          <span>{{ themeStore.locale === 'bn' ? 'কোর্স পেজে দেখুন ↗' : 'View Public Page ↗' }}</span>
        </router-link>
      </div>

      <!-- Preview Chips Grid -->
      <div class="grid grid-cols-1 min-[380px]:grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5">
        <!-- 1. All Categories Card -->
        <div class="p-3.5 rounded-2xl bg-gradient-to-r from-[#D4AF37] via-[#E5C158] to-[#F7E7A9] text-slate-950 border border-[#D4AF37] shadow-sm flex flex-col justify-between gap-2">
          <div class="flex items-center justify-between">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-950/15">
              {{ totalConnectedCourses }}
            </span>
          </div>
          <div>
            <p class="text-xs font-black">{{ themeStore.locale === 'bn' ? 'সকল ক্যাটাগরি' : 'All Categories' }}</p>
            <p class="text-[10px] text-slate-950/80 font-medium">{{ themeStore.locale === 'bn' ? 'সকল ক্যারিয়ার ট্র্যাক' : 'All Career Tracks' }}</p>
          </div>
        </div>

        <!-- 2-5 Dynamic Cards -->
        <div
          v-for="cat in categories"
          :key="cat.id"
          class="p-3.5 rounded-2xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] flex flex-col justify-between gap-2"
        >
          <div class="flex items-center justify-between">
            <span class="text-base">{{ getCategoryIconEmoji(cat.icon) }}</span>
            <span
              :class="cat.courses_count > 0 ? 'bg-[var(--bg-surface)] text-[var(--brand-gold)] border border-[var(--border-accent)]' : 'bg-amber-500/10 text-amber-500 border border-amber-500/20'"
              class="px-2 py-0.5 rounded-full text-[10px] font-extrabold"
            >
              {{ cat.badge_text_bn || (cat.courses_count > 0 ? cat.courses_count : 'আসন্ন') }}
            </span>
          </div>
          <div>
            <p class="text-xs font-bold text-[var(--text-primary)] truncate">{{ themeStore.locale === 'bn' ? cat.name_bn : cat.name_en }}</p>
            <p class="text-[10px] text-[var(--text-muted)] truncate mt-0.5 font-medium">
              {{ themeStore.locale === 'bn' ? (cat.track_title_bn || 'প্রফেশনাল স্কিল') : (cat.track_title_en || 'Professional Track') }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. CATEGORIES TABLE & CRUD ACTIONS -->
    <div class="bg-[var(--bg-surface)] rounded-3xl border border-[var(--border-subtle)] overflow-hidden shadow-xs">
      <div class="p-5 sm:p-6 border-b border-[var(--border-subtle)] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
          <h2 class="text-lg font-bold text-[var(--text-primary)]">
            {{ themeStore.locale === 'bn' ? 'সকল ক্যাটাগরি তালিকা' : 'All Categories List' }}
          </h2>
          <p class="text-xs text-[var(--text-secondary)]">
            {{ themeStore.locale === 'bn' ? 'যেকোনো ক্যাটাগরি এডিট, সক্রিয়/নিষ্ক্রিয় অথবা নতুন ট্র্যাক কনফিগার করুন।' : 'Manage track subtitles, badge texts, order, and status.' }}
          </p>
        </div>

        <button
          type="button"
          @click="fetchCategories"
          class="text-xs text-[var(--brand-gold)] hover:underline flex items-center gap-1 font-semibold cursor-pointer"
        >
          <span>↻ {{ themeStore.locale === 'bn' ? 'রিফ্রেশ করুন' : 'Refresh' }}</span>
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="p-8 text-center space-y-3">
        <div class="w-8 h-8 border-2 border-[var(--brand-gold)] border-t-transparent rounded-full animate-spin mx-auto"></div>
        <p class="text-xs text-[var(--text-muted)]">{{ themeStore.locale === 'bn' ? 'ক্যাটাগরি লোড হচ্ছে...' : 'Loading categories...' }}</p>
      </div>

      <!-- Categories Table -->
      <div v-else-if="categories.length > 0" class="overflow-x-auto">
        <table class="w-full text-left text-xs sm:text-sm">
          <thead class="bg-[var(--bg-deep)] text-[var(--text-secondary)] font-bold uppercase text-[10px] tracking-wider border-b border-[var(--border-subtle)]">
            <tr>
              <th class="px-5 py-4 w-12 text-center">ক্রম</th>
              <th class="px-5 py-4">ক্যাটাগরি ও আইকন</th>
              <th class="px-5 py-4">ক্যারিয়ার ট্র্যাক (Subtitle)</th>
              <th class="px-5 py-4">ব্যাজ টেক্সট</th>
              <th class="px-5 py-4 text-center">সংযুক্ত কোর্স</th>
              <th class="px-5 py-4 text-center">স্ট্যাটাস</th>
              <th class="px-5 py-4 text-right">অ্যাকশন</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-subtle)]">
            <tr
              v-for="cat in categories"
              :key="cat.id"
              class="hover:bg-[var(--bg-elevated)]/50 transition-colors"
            >
              <!-- Order -->
              <td class="px-5 py-4 text-center font-bold text-[var(--text-muted)]">
                <span class="w-6 h-6 rounded-lg bg-[var(--bg-elevated)] inline-flex items-center justify-center text-xs">
                  {{ cat.order_index }}
                </span>
              </td>

              <!-- Category Name & Slug -->
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-accent)] flex items-center justify-center text-lg shrink-0">
                    {{ getCategoryIconEmoji(cat.icon) }}
                  </div>
                  <div class="min-w-0">
                    <p class="font-bold text-[var(--text-primary)] text-sm">{{ cat.name_bn }}</p>
                    <p class="text-xs text-[var(--text-secondary)]">{{ cat.name_en }}</p>
                    <span class="text-[10px] text-[var(--text-muted)] font-mono">slug: {{ cat.slug }}</span>
                  </div>
                </div>
              </td>

              <!-- Track Title / Subtitle -->
              <td class="px-5 py-4">
                <div>
                  <p class="font-bold text-[#D4AF37]">{{ cat.track_title_bn || '—' }}</p>
                  <p class="text-xs text-[var(--text-muted)]">{{ cat.track_title_en || '—' }}</p>
                </div>
              </td>

              <!-- Badge Text -->
              <td class="px-5 py-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30">
                  {{ cat.badge_text_bn || (cat.courses_count > 0 ? cat.courses_count : 'আসন্ন') }}
                </span>
              </td>

              <!-- Courses Count & Link -->
              <td class="px-5 py-4 text-center">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)]">
                  <span class="font-black text-[var(--text-primary)]">{{ cat.courses_count }}</span>
                  <span class="text-[10px] text-[var(--text-muted)]">কোর্স</span>
                </div>
              </td>

              <!-- Status Switch -->
              <td class="px-5 py-4 text-center">
                <button
                  type="button"
                  @click="toggleCategoryStatus(cat)"
                  :class="[
                    'px-3 py-1 rounded-full text-xs font-extrabold cursor-pointer transition-all border',
                    cat.is_active
                      ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30 hover:bg-emerald-500/25'
                      : 'bg-rose-500/15 text-rose-400 border-rose-500/30 hover:bg-rose-500/25'
                  ]"
                >
                  {{ cat.is_active ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Inactive)' }}
                </button>
              </td>

              <!-- Actions (Edit & Delete) -->
              <td class="px-5 py-4 text-right space-x-2">
                <button
                  type="button"
                  @click="openEditModal(cat)"
                  class="px-3 py-1.5 rounded-lg bg-[var(--bg-elevated)] hover:bg-[#D4AF37] text-[var(--text-primary)] hover:text-slate-950 font-bold text-xs border border-[var(--border-subtle)] transition-all cursor-pointer"
                >
                  ✏️ {{ themeStore.locale === 'bn' ? 'এডিট' : 'Edit' }}
                </button>

                <button
                  type="button"
                  @click="openDeleteModal(cat)"
                  class="px-3 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white font-bold text-xs border border-rose-500/20 transition-all cursor-pointer"
                >
                  🗑️ {{ themeStore.locale === 'bn' ? 'মুছুন' : 'Delete' }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-else class="p-12 text-center space-y-4">
        <div class="w-16 h-16 rounded-full bg-[var(--bg-elevated)] flex items-center justify-center text-2xl mx-auto">📂</div>
        <h3 class="text-base font-bold text-[var(--text-primary)]">কোনো কোর্স ক্যাটাগরি পাওয়া যায়নি</h3>
        <button
          type="button"
          @click="openCreateModal"
          class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-bold text-xs cursor-pointer shadow-md"
        >
          + নতুন ক্যাটাগরি তৈরি করুন
        </button>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 5. CREATE / EDIT CATEGORY MODAL -->
    <!-- ========================================================================= -->
    <div v-if="showFormModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-2xl w-full max-h-[90dvh] overflow-y-auto p-6 sm:p-8 relative shadow-2xl space-y-6 safe-bottom">
        
        <!-- Modal Header -->
        <div class="flex items-start justify-between border-b border-[var(--border-subtle)] pb-4">
          <div>
            <span class="text-xs text-[#D4AF37] font-bold uppercase tracking-wider">কোর্স ক্যাটাগরি কনফিগারেশন</span>
            <h3 class="text-xl font-black text-[var(--text-primary)] mt-0.5">
              {{ isEditing ? 'ক্যাটাগরি সম্পাদনা করুন (Edit Category)' : 'নতুন কোর্স ক্যাটাগরি তৈরি করুন (Create Category)' }}
            </h3>
          </div>
          <button @click="showFormModal = false" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] p-2 touch-target cursor-pointer">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <!-- Form Fields -->
        <form @submit.prevent="saveCategory" class="space-y-5">
          
          <!-- Names (BN & EN) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[var(--text-primary)] mb-1.5">
                ক্যাটাগরির নাম (বাংলা) <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="form.name_bn"
                type="text"
                required
                class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-sm focus:outline-none focus:border-[var(--brand-gold)]"
                placeholder="যেমন: এয়ার টিকেটিং ও এভিয়েশন"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-[var(--text-primary)] mb-1.5">
                ক্যাটাগরির নাম (English) <span class="text-rose-500">*</span>
              </label>
              <input
                v-model="form.name_en"
                type="text"
                required
                class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-sm focus:outline-none focus:border-[var(--brand-gold)]"
                placeholder="e.g. Air Ticketing & Aviation"
              />
            </div>
          </div>

          <!-- Career Track Subtitle (BN & EN) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[var(--text-primary)] mb-1.5">
                ক্যারিয়ার ট্র্যাক / সাবটাইটেল (বাংলা)
              </label>
              <input
                v-model="form.track_title_bn"
                type="text"
                class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-sm focus:outline-none focus:border-[var(--brand-gold)]"
                placeholder="যেমন: Sabre & Galileo GDS, গ্লোবাল ভিসা"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-[var(--text-primary)] mb-1.5">
                ক্যারিয়ার ট্র্যাক / সাবটাইটেল (English)
              </label>
              <input
                v-model="form.track_title_en"
                type="text"
                class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-sm focus:outline-none focus:border-[var(--brand-gold)]"
                placeholder="e.g. Sabre & Galileo GDS, Creative Design"
              />
            </div>
          </div>

          <!-- Slug & Icon -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[var(--text-primary)] mb-1.5">
                ইউআরএল স্লাগ (URL Slug)
              </label>
              <input
                v-model="form.slug"
                type="text"
                class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-sm focus:outline-none focus:border-[var(--brand-gold)]"
                placeholder="auto-generated or custom-slug"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-[var(--text-primary)] mb-1.5">
                আইকন নির্বাচন করুন
              </label>
              <select
                v-model="form.icon"
                class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-sm focus:outline-none focus:border-[var(--brand-gold)]"
              >
                <option value="Plane">✈️ Plane (Aviation / Ticketing)</option>
                <option value="Passport">🛂 Passport (Visa & Tourism)</option>
                <option value="Palette">🎨 Palette (Graphic Design)</option>
                <option value="TrendingUp">📈 TrendingUp (Digital Marketing)</option>
                <option value="Laptop">💻 Laptop (Tech / IT)</option>
                <option value="BookOpen">📖 BookOpen (Academy)</option>
                <option value="Sparkles">✨ Sparkles (Special Track)</option>
                <option value="Globe">🌍 Globe (Global Studies)</option>
              </select>
            </div>
          </div>

          <!-- Badges (BN & EN) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[var(--text-primary)] mb-1.5">
                কাস্টম ব্যাজ টেক্সট (বাংলা)
              </label>
              <input
                v-model="form.badge_text_bn"
                type="text"
                class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-sm focus:outline-none focus:border-[var(--brand-gold)]"
                placeholder="যেমন: ১, আসন্ন, নতুন, জনপ্রিয়"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-[var(--text-primary)] mb-1.5">
                অর্ডারিং ইনডেক্স (Order Index)
              </label>
              <input
                v-model.number="form.order_index"
                type="number"
                min="1"
                class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-sm focus:outline-none focus:border-[var(--brand-gold)]"
              />
            </div>
          </div>

          <!-- Description (BN) -->
          <div>
            <label class="block text-xs font-bold text-[var(--text-primary)] mb-1.5">
              ক্যাটাগরি বিবরণ (বাংলা)
            </label>
            <textarea
              v-model="form.description_bn"
              rows="2"
              class="w-full px-4 py-3 rounded-xl bg-[var(--bg-deep)] border border-[var(--border-subtle)] text-[var(--text-primary)] text-sm focus:outline-none focus:border-[var(--brand-gold)]"
              placeholder="ক্যাটাগরির সংক্ষিপ্ত উদ্দেশ্য ও ফোকাস..."
            ></textarea>
          </div>

          <!-- Status Checkbox -->
          <div class="flex items-center gap-3 pt-2">
            <input
              id="is_active_check"
              v-model="form.is_active"
              type="checkbox"
              class="w-5 h-5 rounded border-gray-700 text-[#D4AF37] focus:ring-[#D4AF37] cursor-pointer"
            />
            <label for="is_active_check" class="text-xs font-bold text-[var(--text-primary)] cursor-pointer">
              ক্যাটাগরিটি ওয়েবসাইটে সক্রিয় ও ফিল্টারিংয়ে দৃশ্যমান থাকবে
            </label>
          </div>

          <!-- Submit Buttons -->
          <div class="flex justify-end gap-3 pt-4 border-t border-[var(--border-subtle)]">
            <button
              type="button"
              @click="showFormModal = false"
              class="px-5 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-card)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] cursor-pointer"
            >
              বাতিল (Cancel)
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#E5C158] text-slate-950 font-bold text-xs hover:brightness-110 shadow-md transition-all cursor-pointer disabled:opacity-50"
            >
              <span v-if="saving">সংরক্ষণ হচ্ছে...</span>
              <span v-else>{{ isEditing ? 'আপডেট সম্পন্ন করুন' : 'ক্যাটাগরি তৈরি করুন' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 6. DELETE CONFIRMATION MODAL -->
    <!-- ========================================================================= -->
    <div v-if="categoryToDelete" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="bg-[var(--bg-surface)] border border-[var(--border-subtle)] rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl text-center">
        <div class="w-14 h-14 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-500 text-2xl flex items-center justify-center mx-auto">
          ⚠️
        </div>
        <div class="space-y-1">
          <h3 class="text-lg font-black text-[var(--text-primary)]">ক্যাটাগরি মুছে ফেলতে চান?</h3>
          <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
            আপনি কি নিশ্চিতভাবে <strong class="text-[var(--text-primary)]">"{{ categoryToDelete.name_bn }}"</strong> মুছে ফেলতে চান?
          </p>
          <p v-if="categoryToDelete.courses_count > 0" class="text-xs text-amber-500 font-semibold mt-2">
            ⚠️ এই ক্যাটাগরিতে {{ categoryToDelete.courses_count }}টি কোর্স রয়েছে। মুছে ফেললে কোর্সগুলো ক্যাটাগরি-মুক্ত হয়ে যাবে।
          </p>
        </div>
        <div class="flex items-center justify-center gap-3 pt-2">
          <button
            type="button"
            @click="categoryToDelete = null"
            class="px-5 py-2.5 rounded-xl bg-[var(--bg-elevated)] hover:bg-[var(--bg-card)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-primary)] cursor-pointer"
          >
            বাতিল
          </button>
          <button
            type="button"
            @click="confirmDelete"
            :disabled="deleting"
            class="px-5 py-2.5 rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-xs cursor-pointer disabled:opacity-50 shadow-md shadow-rose-500/20"
          >
            <span v-if="deleting">মুছে ফেলা হচ্ছে...</span>
            <span v-else>হ্যাঁ, মুছে ফেলুন</span>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue';
import apiClient from '../../api/client';
import { useToastStore } from '../../stores/toast';
import { useThemeStore } from '../../stores/theme';

const toast = useToastStore();
const themeStore = useThemeStore();

const loading = ref(true);
const saving = ref(false);
const deleting = ref(false);
const categories = ref<any[]>([]);

const showFormModal = ref(false);
const isEditing = ref(false);
const currentEditId = ref<number | null>(null);
const categoryToDelete = ref<any | null>(null);

const form = reactive({
  name_bn: '',
  name_en: '',
  slug: '',
  track_title_bn: '',
  track_title_en: '',
  icon: 'Plane',
  badge_text_bn: '',
  badge_text_en: '',
  description_bn: '',
  description_en: '',
  order_index: 1,
  is_active: true,
  status: 'active',
});

const totalConnectedCourses = computed(() => {
  return categories.value.reduce((sum, cat) => sum + (cat.courses_count || 0), 0);
});

const getCategoryIconEmoji = (icon: string) => {
  switch (icon) {
    case 'Plane': return '✈️';
    case 'Passport': return '🛂';
    case 'Palette': return '🎨';
    case 'TrendingUp': return '📈';
    case 'Laptop': return '💻';
    case 'BookOpen': return '📖';
    case 'Sparkles': return '✨';
    case 'Globe': return '🌍';
    default: return '📁';
  }
};

const fetchCategories = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/admin/categories');
    categories.value = res.data.data || [];
  } catch (err: any) {
    toast.error('ক্যাটাগরি ডাটা লোড করতে সমস্যা হয়েছে');
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  isEditing.value = false;
  currentEditId.value = null;
  form.name_bn = '';
  form.name_en = '';
  form.slug = '';
  form.track_title_bn = '';
  form.track_title_en = '';
  form.icon = 'Plane';
  form.badge_text_bn = '';
  form.badge_text_en = '';
  form.description_bn = '';
  form.description_en = '';
  form.order_index = (categories.value.length || 0) + 1;
  form.is_active = true;
  form.status = 'active';
  showFormModal.value = true;
};

const openEditModal = (cat: any) => {
  isEditing.value = true;
  currentEditId.value = cat.id;
  form.name_bn = cat.name_bn || '';
  form.name_en = cat.name_en || '';
  form.slug = cat.slug || '';
  form.track_title_bn = cat.track_title_bn || '';
  form.track_title_en = cat.track_title_en || '';
  form.icon = cat.icon || 'Plane';
  form.badge_text_bn = cat.badge_text_bn || '';
  form.badge_text_en = cat.badge_text_en || '';
  form.description_bn = cat.description_bn || '';
  form.description_en = cat.description_en || '';
  form.order_index = cat.order_index || 1;
  form.is_active = Boolean(cat.is_active);
  form.status = cat.status || 'active';
  showFormModal.value = true;
};

const saveCategory = async () => {
  try {
    saving.value = true;
    if (isEditing.value && currentEditId.value) {
      const res = await apiClient.put(`/admin/categories/${currentEditId.value}`, form);
      toast.success(res.data.message || 'ক্যাটাগরি সফলভাবে আপডেট করা হয়েছে');
    } else {
      const res = await apiClient.post('/admin/categories', form);
      toast.success(res.data.message || 'নতুন ক্যাটাগরি তৈরি হয়েছে');
    }
    showFormModal.value = false;
    await fetchCategories();
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'ক্যাটাগরি সংরক্ষণ ব্যর্থ হয়েছে');
  } finally {
    saving.value = false;
  }
};

const toggleCategoryStatus = async (cat: any) => {
  try {
    const updatedStatus = !cat.is_active;
    await apiClient.put(`/admin/categories/${cat.id}`, {
      is_active: updatedStatus,
    });
    cat.is_active = updatedStatus;
    toast.success(`ক্যাটাগরি ${updatedStatus ? 'সক্রিয়' : 'নিষ্ক্রিয়'} করা হয়েছে`);
  } catch (err) {
    toast.error('স্ট্যাটাস পরিবর্তন ব্যর্থ হয়েছে');
  }
};

const openDeleteModal = (cat: any) => {
  categoryToDelete.value = cat;
};

const confirmDelete = async () => {
  if (!categoryToDelete.value) return;
  try {
    deleting.value = true;
    await apiClient.delete(`/admin/categories/${categoryToDelete.value.id}`);
    toast.success('ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে');
    categoryToDelete.value = null;
    await fetchCategories();
  } catch (err: any) {
    toast.error(err.response?.data?.message || 'ক্যাটাগরি মুছতে ব্যর্থ হয়েছে');
  } finally {
    deleting.value = false;
  }
};

onMounted(() => {
  fetchCategories();
});
</script>
