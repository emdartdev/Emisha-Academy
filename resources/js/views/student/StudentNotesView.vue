<template>
  <div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <h1 class="text-lg sm:text-xl font-black text-[var(--text-primary)]">{{ t('My Notes', 'আমার নোটস') }}</h1>
        <p class="text-xs text-[var(--text-secondary)] mt-0.5">
          {{ t('All your lesson notes in one place — edit, pin, download or delete them any time.', 'আপনার সব পাঠের নোট এক জায়গায় — যেকোনো সময় এডিট, পিন, ডাউনলোড বা মুছে ফেলুন।') }}
        </p>
      </div>
      <button
        type="button"
        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg transition-all cursor-pointer"
        @click="startNewNote"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>{{ t('New note', 'নতুন নোট') }}</span>
      </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-5 items-start">
      <!-- Notes list -->
      <aside class="rounded-2xl bg-[var(--bg-card)] border border-[var(--border-subtle)] p-3 space-y-3" :class="{ 'hidden lg:block': activeNote && isMobileEditing }">
        <div class="space-y-2">
          <input
            v-model="search"
            type="search"
            :placeholder="t('Search notes…', 'নোট খুঁজুন…')"
            class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
            @input="debouncedFetch"
          />
          <select
            v-model="courseFilter"
            class="w-full px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
            @change="fetchNotes"
          >
            <option value="">{{ t('All courses', 'সব কোর্স') }}</option>
            <option v-for="c in courses" :key="c.id" :value="c.id">{{ loc(c, 'title') }}</option>
          </select>
        </div>

        <div v-if="listLoading" class="space-y-2">
          <div v-for="i in 4" :key="i" class="h-16 rounded-xl bg-[var(--bg-elevated)] animate-pulse"></div>
        </div>

        <div v-else-if="notes.length === 0" class="py-10 text-center text-xs text-[var(--text-muted)] space-y-2">
          <p>{{ t('No notes yet.', 'এখনও কোনো নোট নেই।') }}</p>
          <p class="text-[11px]">{{ t('Open a lesson in the classroom and write in "Lesson Notes & Summary".', 'ক্লাসরুমে কোনো লেসন খুলে "পাঠের নোট ও সারাংশ" অংশে লিখুন।') }}</p>
        </div>

        <ul v-else class="space-y-1.5 max-h-[65vh] overflow-y-auto pr-0.5">
          <li v-for="note in notes" :key="note.id">
            <button
              type="button"
              class="w-full text-left p-3 rounded-xl border transition-colors cursor-pointer"
              :class="activeNote?.id === note.id ? 'bg-[#D4AF37]/10 border-[#D4AF37]/40' : 'bg-[var(--bg-elevated)] border-transparent hover:border-[var(--border-subtle)]'"
              @click="openNote(note.id)"
            >
              <div class="flex items-start justify-between gap-2">
                <p class="text-xs font-bold text-[var(--text-primary)] line-clamp-1">
                  <span v-if="note.is_pinned" class="text-[#D4AF37]">📌 </span>{{ note.title }}
                </p>
                <span class="text-[10px] text-[var(--text-muted)] shrink-0">{{ formatDate(note.updated_at) }}</span>
              </div>
              <p v-if="note.course" class="text-[10px] text-[#D4AF37] font-semibold mt-0.5 line-clamp-1">{{ loc(note.course, 'title') }}</p>
              <p class="text-[11px] text-[var(--text-secondary)] mt-1 line-clamp-2">{{ note.excerpt || t('Empty note', 'খালি নোট') }}</p>
            </button>
          </li>
        </ul>
      </aside>

      <!-- Editor -->
      <section class="space-y-3" :class="{ 'hidden lg:block': !activeNote && !isMobileEditing }">
        <div v-if="!activeNote" class="rounded-2xl bg-[var(--bg-card)] border border-dashed border-[var(--border-subtle)] p-12 text-center text-xs text-[var(--text-muted)]">
          {{ t('Select a note on the left or create a new one.', 'বাম পাশ থেকে একটি নোট নির্বাচন করুন অথবা নতুন নোট তৈরি করুন।') }}
        </div>

        <template v-else>
          <div class="flex flex-wrap items-center gap-2">
            <button type="button" class="lg:hidden px-3 py-2 rounded-xl bg-[var(--bg-elevated)] text-xs font-bold text-[var(--text-secondary)] cursor-pointer" @click="closeEditor">
              ← {{ t('Back', 'ফিরে যান') }}
            </button>
            <input
              v-model="activeNote.title"
              type="text"
              maxlength="255"
              :placeholder="t('Untitled note', 'শিরোনামহীন নোট')"
              class="flex-1 min-w-[12rem] px-3 py-2 rounded-xl bg-transparent border border-transparent hover:border-[var(--border-subtle)] focus:border-[#D4AF37] text-base sm:text-lg font-black text-[var(--text-primary)] focus:outline-none"
              @input="scheduleSave"
            />
            <div class="flex items-center gap-1.5">
              <button
                type="button"
                class="px-3 py-2 rounded-xl border text-xs font-bold cursor-pointer"
                :class="activeNote.is_pinned ? 'bg-[#D4AF37]/15 border-[#D4AF37]/40 text-[#D4AF37]' : 'bg-[var(--bg-elevated)] border-[var(--border-subtle)] text-[var(--text-secondary)]'"
                @click="togglePin"
              >📌 {{ activeNote.is_pinned ? t('Pinned', 'পিন করা') : t('Pin', 'পিন') }}</button>
              <router-link
                v-if="activeNote.course_id && activeNote.lesson_id"
                :to="`/student/courses/${activeNote.course_id}/learn?lesson=${activeNote.lesson_id}`"
                class="px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)]"
              >{{ t('Open lesson', 'লেসনে যান') }}</router-link>
              <button type="button" class="px-3 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] cursor-pointer" @click="printNote">
                {{ t('Print / PDF', 'প্রিন্ট / PDF') }}
              </button>
              <button type="button" class="px-3 py-2 rounded-xl bg-rose-500/10 border border-rose-500/20 text-xs font-bold text-rose-500 cursor-pointer" @click="deleteNote">
                {{ t('Delete', 'মুছুন') }}
              </button>
            </div>
          </div>

          <p v-if="activeNote.lesson" class="text-[11px] text-[var(--text-muted)] px-1">
            {{ loc(activeNote.course, 'title') }} › {{ loc(activeNote.lesson, 'title') }}
          </p>

          <div v-if="noteLoading" class="h-96 rounded-2xl bg-[var(--bg-elevated)] animate-pulse"></div>
          <RichTextEditor
            v-else
            :key="activeNote.id || 'new'"
            v-model="activeNote.content"
            min-height="420px"
            :placeholder="t('Start typing…', 'লেখা শুরু করুন…')"
            @update:model-value="scheduleSave"
          >
            <template #status>
              <span :class="statusClass">{{ statusLabel }}</span>
            </template>
            <template #toolbar-end>
              <button type="button" class="rte-btn" :title="t('Download as Word document', 'Word ডকুমেন্ট হিসেবে ডাউনলোড')" @click="downloadNote">
                <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              </button>
            </template>
          </RichTextEditor>
        </template>
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';
import { sanitizeHtml } from '../../utils/sanitizeHtml';
import RichTextEditor from '../../components/ui/RichTextEditor.vue';

const themeStore = useThemeStore();
const toast = useToastStore();
const t = (en: string, bn: string) => (themeStore.locale === 'bn' ? bn : en);
const loc = (obj: any, field: string) => (themeStore.locale === 'bn' ? obj?.[`${field}_bn`] : obj?.[`${field}_en`] || obj?.[`${field}_bn`]);

const notes = ref<any[]>([]);
const courses = ref<any[]>([]);
const listLoading = ref(true);
const noteLoading = ref(false);
const search = ref('');
const courseFilter = ref<string | number>('');
const activeNote = ref<any>(null);
const isMobileEditing = ref(false);
const status = ref<'saved' | 'dirty' | 'saving' | 'error'>('saved');
let saveTimer: ReturnType<typeof setTimeout> | null = null;
let searchTimer: ReturnType<typeof setTimeout> | null = null;

const statusLabel = computed(() => ({
  saved: t('All changes saved', 'সব পরিবর্তন সংরক্ষিত'),
  dirty: t('Unsaved changes', 'অসংরক্ষিত পরিবর্তন'),
  saving: t('Saving…', 'সংরক্ষণ হচ্ছে…'),
  error: t('Save failed', 'সংরক্ষণ ব্যর্থ'),
}[status.value]));
const statusClass = computed(() => ({
  saved: 'text-emerald-500',
  dirty: 'text-amber-500',
  saving: 'text-sky-500',
  error: 'text-rose-500',
}[status.value]));

function formatDate(value: string) {
  if (!value) return '';
  return new Date(value).toLocaleDateString(themeStore.locale === 'bn' ? 'bn-BD' : 'en-GB', { day: 'numeric', month: 'short' });
}

async function fetchNotes() {
  listLoading.value = true;
  try {
    const res = await apiClient.get('/student/notes', {
      params: { search: search.value || undefined, course_id: courseFilter.value || undefined },
    });
    notes.value = res.data.data.data || [];
  } catch {
    notes.value = [];
  } finally {
    listLoading.value = false;
  }
}

function debouncedFetch() {
  if (searchTimer) clearTimeout(searchTimer);
  searchTimer = setTimeout(fetchNotes, 300);
}

async function fetchCourses() {
  try {
    const res = await apiClient.get('/student/courses');
    courses.value = (res.data.data || []).map((e: any) => e.course).filter(Boolean);
  } catch {
    courses.value = [];
  }
}

async function openNote(id: number) {
  await flushSave();
  noteLoading.value = true;
  isMobileEditing.value = true;
  try {
    const res = await apiClient.get(`/student/notes/${id}`);
    activeNote.value = res.data.data;
    activeNote.value.content = activeNote.value.content || '';
    status.value = 'saved';
  } catch (err: any) {
    toast.error(err.response?.data?.message || t('Could not open note.', 'নোট খোলা যায়নি।'));
  } finally {
    noteLoading.value = false;
  }
}

async function startNewNote() {
  await flushSave();
  try {
    const res = await apiClient.post('/student/notes', {
      title: t('Untitled note', 'শিরোনামহীন নোট'),
      content: '',
      course_id: courseFilter.value || undefined,
    });
    activeNote.value = { ...res.data.data, content: '' };
    isMobileEditing.value = true;
    status.value = 'saved';
    fetchNotes();
  } catch (err: any) {
    toast.error(err.response?.data?.message || t('Could not create note.', 'নোট তৈরি করা যায়নি।'));
  }
}

function scheduleSave() {
  status.value = 'dirty';
  if (saveTimer) clearTimeout(saveTimer);
  saveTimer = setTimeout(save, 1200);
}

async function save() {
  if (saveTimer) {
    clearTimeout(saveTimer);
    saveTimer = null;
  }
  const note = activeNote.value;
  if (!note?.id) return;
  status.value = 'saving';
  try {
    await apiClient.put(`/student/notes/${note.id}`, {
      title: (note.title || '').trim() || t('Untitled note', 'শিরোনামহীন নোট'),
      content: note.content || '',
      is_pinned: !!note.is_pinned,
    });
    status.value = 'saved';
    const listItem = notes.value.find((n) => n.id === note.id);
    if (listItem) {
      listItem.title = note.title;
      listItem.is_pinned = note.is_pinned;
      listItem.updated_at = new Date().toISOString();
      const div = document.createElement('div');
      div.innerHTML = sanitizeHtml(note.content || '');
      listItem.excerpt = (div.textContent || '').slice(0, 220);
    }
  } catch {
    status.value = 'error';
  }
}

async function flushSave() {
  if (saveTimer || status.value === 'dirty') await save();
}

async function togglePin() {
  if (!activeNote.value) return;
  activeNote.value.is_pinned = !activeNote.value.is_pinned;
  await save();
  fetchNotes();
}

async function deleteNote() {
  if (!activeNote.value) return;
  if (!window.confirm(t('Delete this note permanently?', 'এই নোটটি স্থায়ীভাবে মুছে ফেলবেন?'))) return;
  try {
    await apiClient.delete(`/student/notes/${activeNote.value.id}`);
    if (saveTimer) clearTimeout(saveTimer);
    saveTimer = null;
    status.value = 'saved';
    notes.value = notes.value.filter((n) => n.id !== activeNote.value.id);
    activeNote.value = null;
    isMobileEditing.value = false;
    toast.success(t('Note deleted.', 'নোট মুছে ফেলা হয়েছে।'));
  } catch (err: any) {
    toast.error(err.response?.data?.message || t('Could not delete note.', 'নোট মুছে ফেলা যায়নি।'));
  }
}

async function closeEditor() {
  await flushSave();
  isMobileEditing.value = false;
  activeNote.value = null;
}

function noteDocumentHtml() {
  const title = (activeNote.value?.title || 'note').replace(/</g, '&lt;');
  return `<!DOCTYPE html><html><head><meta charset="utf-8"><title>${title}</title>
<style>body{font-family:'Hind Siliguri',Arial,sans-serif;line-height:1.6;padding:24px;color:#111}blockquote{border-left:3px solid #D4AF37;padding-left:12px;color:#444}pre{background:#f4f4f4;padding:10px;border-radius:6px}table{border-collapse:collapse}td,th{border:1px solid #ccc;padding:4px 8px}li[data-checked="true"]{text-decoration:line-through;color:#777}</style>
</head><body><h1>${title}</h1>${sanitizeHtml(activeNote.value?.content || '')}</body></html>`;
}

function downloadNote() {
  const blob = new Blob(['﻿', noteDocumentHtml()], { type: 'application/msword' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `${(activeNote.value?.title || 'note').replace(/[\\/:*?"<>|]+/g, '_').slice(0, 80)}.doc`;
  a.click();
  URL.revokeObjectURL(url);
}

function printNote() {
  const win = window.open('', '_blank');
  if (!win) return;
  win.document.write(noteDocumentHtml());
  win.document.close();
  win.focus();
  setTimeout(() => win.print(), 300);
}

onBeforeRouteLeave(async () => {
  await flushSave();
});

onMounted(() => {
  fetchNotes();
  fetchCourses();
});

onBeforeUnmount(() => {
  flushSave();
});
</script>
