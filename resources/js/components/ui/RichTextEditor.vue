<template>
  <div
    :class="[
      'rte rounded-2xl border border-[var(--border-subtle)] bg-[var(--bg-surface)] overflow-hidden flex flex-col',
      isFullscreen ? 'fixed inset-2 sm:inset-6 z-[1000] shadow-2xl' : 'relative',
    ]"
  >
    <!-- Toolbar -->
    <div
      v-if="!readonly"
      class="flex flex-wrap items-center gap-0.5 px-2 py-1.5 border-b border-[var(--border-subtle)] bg-[var(--bg-elevated)] text-[var(--text-secondary)] select-none"
      @mousedown="onToolbarMouseDown"
    >
      <button type="button" class="rte-btn" :title="t('Undo (Ctrl+Z)', 'আনডু (Ctrl+Z)')" @click="exec('undo')">
        <svg viewBox="0 0 24 24"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 0 11H11"/></svg>
      </button>
      <button type="button" class="rte-btn" :title="t('Redo (Ctrl+Y)', 'রিডু (Ctrl+Y)')" @click="exec('redo')">
        <svg viewBox="0 0 24 24"><path d="m15 14 5-5-5-5"/><path d="M20 9H9.5a5.5 5.5 0 0 0 0 11H13"/></svg>
      </button>
      <span class="rte-sep"></span>

      <select
        class="rte-select"
        :value="activeBlock"
        :title="t('Text style', 'টেক্সট স্টাইল')"
        @change="setBlock(($event.target as HTMLSelectElement).value)"
      >
        <option value="p">{{ t('Normal text', 'সাধারণ লেখা') }}</option>
        <option value="h1">{{ t('Title', 'শিরোনাম') }}</option>
        <option value="h2">{{ t('Heading 1', 'হেডিং ১') }}</option>
        <option value="h3">{{ t('Heading 2', 'হেডিং ২') }}</option>
        <option value="h4">{{ t('Heading 3', 'হেডিং ৩') }}</option>
      </select>

      <select
        v-if="!compact"
        class="rte-select w-[4.5rem]"
        :title="t('Font size', 'ফন্ট সাইজ')"
        value=""
        @change="setFontSize(($event.target as HTMLSelectElement).value); ($event.target as HTMLSelectElement).value = ''"
      >
        <option value="" disabled>{{ t('Size', 'সাইজ') }}</option>
        <option value="2">{{ t('Small', 'ছোট') }}</option>
        <option value="3">{{ t('Normal', 'সাধারণ') }}</option>
        <option value="5">{{ t('Large', 'বড়') }}</option>
        <option value="6">{{ t('Huge', 'অনেক বড়') }}</option>
      </select>
      <span class="rte-sep"></span>

      <button type="button" class="rte-btn font-black" :class="{ 'rte-on': active.bold }" :title="t('Bold (Ctrl+B)', 'বোল্ড (Ctrl+B)')" @click="exec('bold')">B</button>
      <button type="button" class="rte-btn italic font-serif" :class="{ 'rte-on': active.italic }" :title="t('Italic (Ctrl+I)', 'ইটালিক (Ctrl+I)')" @click="exec('italic')">I</button>
      <button type="button" class="rte-btn underline" :class="{ 'rte-on': active.underline }" :title="t('Underline (Ctrl+U)', 'আন্ডারলাইন (Ctrl+U)')" @click="exec('underline')">U</button>
      <button type="button" class="rte-btn line-through" :class="{ 'rte-on': active.strikeThrough }" :title="t('Strikethrough', 'স্ট্রাইকথ্রু')" @click="exec('strikeThrough')">S</button>

      <!-- Text colour -->
      <label class="rte-btn relative cursor-pointer" :title="t('Text colour', 'লেখার রং')">
        <span class="font-bold leading-none" :style="{ borderBottom: `3px solid ${textColor}` }">A</span>
        <input type="color" class="absolute inset-0 opacity-0 cursor-pointer" v-model="textColor" @change="applyColor('foreColor', textColor)" />
      </label>
      <!-- Highlight -->
      <label class="rte-btn relative cursor-pointer" :title="t('Highlight', 'হাইলাইট')">
        <svg viewBox="0 0 24 24"><path d="m9 11-6 6v3h9l3-3"/><path d="m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4"/></svg>
        <span class="absolute bottom-1 left-1.5 right-1.5 h-[3px] rounded" :style="{ background: highlightColor }"></span>
        <input type="color" class="absolute inset-0 opacity-0 cursor-pointer" v-model="highlightColor" @change="applyColor('hiliteColor', highlightColor)" />
      </label>
      <span class="rte-sep"></span>

      <button type="button" class="rte-btn" :class="{ 'rte-on': active.justifyLeft }" :title="t('Align left', 'বামে')" @click="exec('justifyLeft')">
        <svg viewBox="0 0 24 24"><path d="M21 6H3M15 12H3M17 18H3"/></svg>
      </button>
      <button type="button" class="rte-btn" :class="{ 'rte-on': active.justifyCenter }" :title="t('Center', 'মাঝে')" @click="exec('justifyCenter')">
        <svg viewBox="0 0 24 24"><path d="M21 6H3M17 12H7M19 18H5"/></svg>
      </button>
      <button type="button" class="rte-btn" :class="{ 'rte-on': active.justifyRight }" :title="t('Align right', 'ডানে')" @click="exec('justifyRight')">
        <svg viewBox="0 0 24 24"><path d="M21 6H3M21 12H9M21 18H7"/></svg>
      </button>
      <button v-if="!compact" type="button" class="rte-btn" :class="{ 'rte-on': active.justifyFull }" :title="t('Justify', 'সমান')" @click="exec('justifyFull')">
        <svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>
      <span class="rte-sep"></span>

      <button type="button" class="rte-btn" :class="{ 'rte-on': active.insertUnorderedList }" :title="t('Bulleted list', 'বুলেট তালিকা')" @click="exec('insertUnorderedList')">
        <svg viewBox="0 0 24 24"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
      </button>
      <button type="button" class="rte-btn" :class="{ 'rte-on': active.insertOrderedList }" :title="t('Numbered list', 'নম্বর তালিকা')" @click="exec('insertOrderedList')">
        <svg viewBox="0 0 24 24"><path d="M10 6h11M10 12h11M10 18h11M4 6h1v4M4 10h2M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>
      </button>
      <button type="button" class="rte-btn" :title="t('Checklist', 'চেকলিস্ট')" @click="insertChecklist">
        <svg viewBox="0 0 24 24"><path d="m3 7 2 2 4-4M3 17l2 2 4-4M13 6h8M13 12h8M13 18h8"/></svg>
      </button>
      <template v-if="!compact">
        <button type="button" class="rte-btn" :title="t('Decrease indent', 'ইনডেন্ট কমান')" @click="exec('outdent')">
          <svg viewBox="0 0 24 24"><path d="M21 6H11M21 12H11M21 18H11M7 8l-4 4 4 4"/></svg>
        </button>
        <button type="button" class="rte-btn" :title="t('Increase indent', 'ইনডেন্ট বাড়ান')" @click="exec('indent')">
          <svg viewBox="0 0 24 24"><path d="M21 6H11M21 12H11M21 18H11M3 8l4 4-4 4"/></svg>
        </button>
      </template>
      <span class="rte-sep"></span>

      <button type="button" class="rte-btn" :class="{ 'rte-on': activeBlock === 'blockquote' }" :title="t('Quote', 'উদ্ধৃতি')" @click="setBlock('blockquote')">
        <svg viewBox="0 0 24 24"><path d="M3 21c3 0 7-1 7-8V5H3v7h4c0 4-2 6-4 6zM14 21c3 0 7-1 7-8V5h-7v7h4c0 4-2 6-4 6z"/></svg>
      </button>
      <button v-if="!compact" type="button" class="rte-btn" :class="{ 'rte-on': activeBlock === 'pre' }" :title="t('Code block', 'কোড ব্লক')" @click="setBlock('pre')">
        <svg viewBox="0 0 24 24"><path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/></svg>
      </button>
      <button type="button" class="rte-btn" :title="t('Insert link', 'লিংক যুক্ত করুন')" @click="insertLink">
        <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
      </button>
      <template v-if="!compact">
        <button type="button" class="rte-btn" :title="t('Horizontal line', 'বিভাজক লাইন')" @click="exec('insertHorizontalRule')">
          <svg viewBox="0 0 24 24"><path d="M3 12h18"/></svg>
        </button>
        <button type="button" class="rte-btn" :title="t('Insert table', 'টেবিল যুক্ত করুন')" @click="insertTable">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/></svg>
        </button>
      </template>
      <button type="button" class="rte-btn" :title="t('Clear formatting', 'ফরম্যাটিং মুছুন')" @click="clearFormatting">
        <svg viewBox="0 0 24 24"><path d="M4 7V4h16v3M5 20h6M13 4 8 20M15 15l5 5M20 15l-5 5"/></svg>
      </button>

      <div class="ml-auto flex items-center gap-0.5">
        <slot name="toolbar-end"></slot>
        <button type="button" class="rte-btn" :title="isFullscreen ? t('Exit full screen', 'ফুলস্ক্রিন বন্ধ') : t('Full screen', 'ফুলস্ক্রিন')" @click="toggleFullscreen">
          <svg v-if="!isFullscreen" viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 0 0-2 2v3M21 8V5a2 2 0 0 0-2-2h-3M3 16v3a2 2 0 0 0 2 2h3M16 21h3a2 2 0 0 0 2-2v-3"/></svg>
          <svg v-else viewBox="0 0 24 24"><path d="M8 3v3a2 2 0 0 1-2 2H3M21 8h-3a2 2 0 0 1-2-2V3M3 16h3a2 2 0 0 1 2 2v3M16 21v-3a2 2 0 0 1 2-2h3"/></svg>
        </button>
      </div>
    </div>

    <!-- Page -->
    <div class="flex-1 overflow-y-auto bg-[var(--bg-deep)]/40" :class="isFullscreen ? 'p-3 sm:p-8' : 'p-0'">
      <div
        ref="editorRef"
        :contenteditable="!readonly"
        class="rich-content rte-page outline-none px-4 sm:px-6 py-4 text-sm text-[var(--text-primary)] leading-relaxed"
        :class="isFullscreen ? 'max-w-3xl mx-auto bg-[var(--bg-surface)] rounded-xl shadow-lg min-h-full sm:px-12 sm:py-10' : ''"
        :style="{ minHeight: isFullscreen ? undefined : minHeight }"
        :data-placeholder="placeholder"
        spellcheck="true"
        @input="onInput"
        @paste="onPaste"
        @keydown="onKeydown"
        @click="onEditorClick"
        @keyup="refreshActiveState"
        @mouseup="refreshActiveState"
        @focus="isFocused = true"
        @blur="onBlur"
      ></div>
    </div>

    <!-- Status bar -->
    <div class="flex items-center justify-between gap-3 px-3 py-1.5 border-t border-[var(--border-subtle)] bg-[var(--bg-elevated)] text-[10px] text-[var(--text-muted)]">
      <span>{{ wordCount }} {{ t('words', 'শব্দ') }} · {{ charCount }} {{ t('characters', 'অক্ষর') }}</span>
      <slot name="status"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import { useThemeStore } from '../../stores/theme';
import { sanitizeHtml, htmlToText } from '../../utils/sanitizeHtml';

const props = withDefaults(
  defineProps<{
    modelValue: string;
    placeholder?: string;
    minHeight?: string;
    readonly?: boolean;
    compact?: boolean;
  }>(),
  {
    placeholder: '',
    minHeight: '220px',
    readonly: false,
    compact: false,
  }
);

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
  (e: 'blur'): void;
}>();

const themeStore = useThemeStore();
const t = (en: string, bn: string) => (themeStore.locale === 'bn' ? bn : en);

const editorRef = ref<HTMLDivElement | null>(null);
const isFullscreen = ref(false);
const isFocused = ref(false);
const textColor = ref('#D4AF37');
const highlightColor = ref('#FDE68A');
const activeBlock = ref('p');
const plainText = ref('');
let savedRange: Range | null = null;

const active = reactive<Record<string, boolean>>({
  bold: false,
  italic: false,
  underline: false,
  strikeThrough: false,
  justifyLeft: false,
  justifyCenter: false,
  justifyRight: false,
  justifyFull: false,
  insertUnorderedList: false,
  insertOrderedList: false,
});

const wordCount = computed(() => (plainText.value ? plainText.value.split(/\s+/).filter(Boolean).length : 0));
const charCount = computed(() => plainText.value.length);

function syncFromModel(value: string) {
  const el = editorRef.value;
  if (!el) return;
  const clean = sanitizeHtml(value || '');
  if (el.innerHTML !== clean) {
    el.innerHTML = clean;
  }
  plainText.value = htmlToText(clean);
}

watch(
  () => props.modelValue,
  (value) => {
    // Don't clobber the caret while the user is typing
    const current = editorRef.value?.innerHTML ?? '';
    const bothEmpty = !value && !htmlToText(current) && !/<(img|hr|table)/i.test(current);
    if (editorRef.value && value !== current && !bothEmpty) {
      syncFromModel(value);
    }
  }
);

function emitChange() {
  const html = editorRef.value?.innerHTML || '';
  const isEmpty = !htmlToText(html) && !/<(img|hr|table)/i.test(html);
  plainText.value = htmlToText(html);
  emit('update:modelValue', isEmpty ? '' : html);
}

function onInput() {
  emitChange();
  refreshActiveState();
}

function onBlur() {
  isFocused.value = false;
  emit('blur');
}

// Keep the selection inside the editor while toolbar buttons are clicked
function onToolbarMouseDown(e: MouseEvent) {
  const target = e.target as HTMLElement;
  if (target.closest('select') || target.closest('input')) {
    saveSelection();
    return;
  }
  e.preventDefault();
}

function saveSelection() {
  const sel = window.getSelection();
  if (sel && sel.rangeCount > 0 && editorRef.value?.contains(sel.anchorNode)) {
    savedRange = sel.getRangeAt(0).cloneRange();
  }
}

function restoreSelection() {
  editorRef.value?.focus();
  if (savedRange) {
    const sel = window.getSelection();
    sel?.removeAllRanges();
    sel?.addRange(savedRange);
  }
}

function exec(command: string, value?: string) {
  if (props.readonly) return;
  editorRef.value?.focus();
  document.execCommand(command, false, value);
  emitChange();
  refreshActiveState();
}

function setBlock(tag: string) {
  restoreSelection();
  // Toggle quote / code back to a paragraph
  const next = activeBlock.value === tag && (tag === 'blockquote' || tag === 'pre') ? 'p' : tag;
  document.execCommand('formatBlock', false, `<${next}>`);
  emitChange();
  refreshActiveState();
}

function setFontSize(size: string) {
  if (!size) return;
  restoreSelection();
  document.execCommand('fontSize', false, size);
  emitChange();
}

function applyColor(command: 'foreColor' | 'hiliteColor', color: string) {
  restoreSelection();
  document.execCommand('styleWithCSS', false, 'true');
  document.execCommand(command, false, color);
  document.execCommand('styleWithCSS', false, 'false');
  emitChange();
}

function insertLink() {
  saveSelection();
  const url = window.prompt(t('Enter link URL (https://...)', 'লিংক URL দিন (https://...)'), 'https://');
  if (!url || url === 'https://') return;
  if (!/^(https?:|mailto:|tel:)/i.test(url)) {
    return;
  }
  restoreSelection();
  const sel = window.getSelection();
  if (sel && sel.isCollapsed) {
    document.execCommand('insertHTML', false, `<a href="${encodeURI(url)}" target="_blank" rel="noopener">${url.replace(/</g, '&lt;')}</a>`);
  } else {
    document.execCommand('createLink', false, url);
  }
  emitChange();
}

function insertChecklist() {
  exec('insertHTML', `<ul data-checklist="true"><li data-checked="false">${t('To-do item', 'করণীয়')}</li></ul><p><br></p>`);
}

function insertTable() {
  const cell = '<td><br></td>';
  const row = `<tr>${cell.repeat(3)}</tr>`;
  exec('insertHTML', `<table><tbody>${row.repeat(3)}</tbody></table><p><br></p>`);
}

function clearFormatting() {
  exec('removeFormat');
  document.execCommand('formatBlock', false, '<p>');
  emitChange();
}

function toggleFullscreen() {
  isFullscreen.value = !isFullscreen.value;
  document.body.style.overflow = isFullscreen.value ? 'hidden' : '';
  setTimeout(() => editorRef.value?.focus(), 50);
}

function onKeydown(e: KeyboardEvent) {
  if (e.key === 'Escape' && isFullscreen.value) {
    toggleFullscreen();
    return;
  }
  // Tab indents inside lists instead of leaving the editor
  if (e.key === 'Tab') {
    e.preventDefault();
    exec(e.shiftKey ? 'outdent' : 'indent');
  }
}

function onPaste(e: ClipboardEvent) {
  if (!e.clipboardData) return;
  e.preventDefault();
  const html = e.clipboardData.getData('text/html');
  const text = e.clipboardData.getData('text/plain');
  if (html) {
    document.execCommand('insertHTML', false, sanitizeHtml(html));
  } else {
    document.execCommand('insertText', false, text);
  }
  emitChange();
}

// Tick / untick checklist items by clicking the box area
function onEditorClick(e: MouseEvent) {
  const li = (e.target as HTMLElement).closest('li[data-checked]') as HTMLElement | null;
  if (!li || !editorRef.value?.contains(li)) {
    refreshActiveState();
    return;
  }
  const rect = li.getBoundingClientRect();
  if (e.clientX - rect.left < 26) {
    li.setAttribute('data-checked', li.getAttribute('data-checked') === 'true' ? 'false' : 'true');
    emitChange();
  }
}

function refreshActiveState() {
  if (!editorRef.value) return;
  const sel = window.getSelection();
  if (!sel || !sel.anchorNode || !editorRef.value.contains(sel.anchorNode)) return;
  saveSelection();
  Object.keys(active).forEach((cmd) => {
    try {
      active[cmd] = document.queryCommandState(cmd);
    } catch {
      active[cmd] = false;
    }
  });
  let node: Node | null = sel.anchorNode;
  let block = 'p';
  while (node && node !== editorRef.value) {
    if (node.nodeType === Node.ELEMENT_NODE) {
      const tag = (node as HTMLElement).tagName.toLowerCase();
      if (['h1', 'h2', 'h3', 'h4', 'blockquote', 'pre'].includes(tag)) {
        block = tag;
        break;
      }
    }
    node = node.parentNode;
  }
  activeBlock.value = block;
}

function onSelectionChange() {
  if (isFocused.value) refreshActiveState();
}

onMounted(() => {
  syncFromModel(props.modelValue);
  document.addEventListener('selectionchange', onSelectionChange);
  try {
    document.execCommand('defaultParagraphSeparator', false, 'p');
  } catch {
    // older browsers
  }
});

onBeforeUnmount(() => {
  document.removeEventListener('selectionchange', onSelectionChange);
  if (isFullscreen.value) document.body.style.overflow = '';
});

defineExpose({
  focus: () => editorRef.value?.focus(),
  getHtml: () => editorRef.value?.innerHTML || '',
});
</script>

<style>
.rte-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 1.9rem;
  height: 1.9rem;
  padding: 0 0.35rem;
  border-radius: 0.5rem;
  font-size: 0.8rem;
  color: var(--text-secondary);
  cursor: pointer;
  transition: background-color 0.15s, color 0.15s;
}
.rte-btn:hover {
  background: var(--bg-hover);
  color: var(--text-primary);
}
.rte-btn.rte-on {
  background: rgba(212, 175, 55, 0.18);
  color: #d4af37;
}
.rte-btn svg {
  width: 1rem;
  height: 1rem;
  fill: none;
  stroke: currentColor;
  stroke-width: 2;
  stroke-linecap: round;
  stroke-linejoin: round;
}
.rte-sep {
  width: 1px;
  height: 1.2rem;
  margin: 0 0.25rem;
  background: var(--border-subtle);
}
.rte-select {
  height: 1.9rem;
  padding: 0 0.4rem;
  border-radius: 0.5rem;
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--text-primary);
  background: var(--bg-surface);
  border: 1px solid var(--border-subtle);
  cursor: pointer;
}
.rte-page:empty::before {
  content: attr(data-placeholder);
  color: var(--text-muted);
  pointer-events: none;
}

/* Shared typography for editor + rendered rich content */
.rich-content h1 { font-size: 1.6em; font-weight: 800; margin: 0.6em 0 0.35em; line-height: 1.25; }
.rich-content h2 { font-size: 1.3em; font-weight: 700; margin: 0.6em 0 0.3em; line-height: 1.3; }
.rich-content h3 { font-size: 1.12em; font-weight: 700; margin: 0.55em 0 0.3em; }
.rich-content h4 { font-size: 1em; font-weight: 700; margin: 0.5em 0 0.25em; color: var(--text-secondary); }
.rich-content p { margin: 0.35em 0; }
.rich-content ul { list-style: disc; padding-left: 1.5em; margin: 0.4em 0; }
.rich-content ol { list-style: decimal; padding-left: 1.5em; margin: 0.4em 0; }
.rich-content li { margin: 0.15em 0; }
.rich-content ul[data-checklist] { list-style: none; padding-left: 0.2em; }
.rich-content li[data-checked] { position: relative; padding-left: 1.7em; }
.rich-content li[data-checked]::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0.2em;
  width: 1.05em;
  height: 1.05em;
  border: 2px solid var(--text-muted);
  border-radius: 0.3em;
  cursor: pointer;
}
.rich-content li[data-checked='true'] { color: var(--text-muted); text-decoration: line-through; }
.rich-content li[data-checked='true']::before {
  background: #10b981;
  border-color: #10b981;
  box-shadow: inset 0 0 0 2px var(--bg-surface);
}
.rich-content blockquote {
  border-left: 3px solid #d4af37;
  padding: 0.4em 0.9em;
  margin: 0.6em 0;
  background: rgba(212, 175, 55, 0.07);
  border-radius: 0 0.5em 0.5em 0;
  color: var(--text-secondary);
}
.rich-content pre {
  font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
  font-size: 0.85em;
  background: var(--bg-elevated);
  border: 1px solid var(--border-subtle);
  padding: 0.7em 0.9em;
  border-radius: 0.6em;
  white-space: pre-wrap;
  margin: 0.6em 0;
}
.rich-content code { font-family: ui-monospace, Menlo, Consolas, monospace; font-size: 0.9em; }
.rich-content a { color: #d4af37; text-decoration: underline; }
.rich-content hr { border: 0; border-top: 1px solid var(--border-medium, var(--border-subtle)); margin: 1em 0; }
.rich-content table { border-collapse: collapse; width: 100%; margin: 0.6em 0; font-size: 0.92em; }
.rich-content td, .rich-content th { border: 1px solid var(--border-subtle); padding: 0.4em 0.6em; min-width: 3em; vertical-align: top; }
.rich-content th { background: var(--bg-elevated); font-weight: 700; }
.rich-content img { max-width: 100%; border-radius: 0.5em; }
.rich-content mark { border-radius: 0.2em; padding: 0 0.1em; }
</style>
