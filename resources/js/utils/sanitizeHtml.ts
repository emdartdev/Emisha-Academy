/**
 * Client-side allowlist sanitizer for rich text rendered with v-html
 * (text lessons, student notes). Mirrors App\Support\HtmlSanitizer on the server.
 */

const ALLOWED_TAGS = new Set([
  'P', 'BR', 'DIV', 'SPAN', 'B', 'STRONG', 'I', 'EM', 'U', 'S', 'STRIKE', 'DEL', 'MARK', 'SUB', 'SUP',
  'H1', 'H2', 'H3', 'H4', 'UL', 'OL', 'LI', 'BLOCKQUOTE', 'PRE', 'CODE', 'A', 'HR', 'IMG', 'FONT',
  'TABLE', 'THEAD', 'TBODY', 'TR', 'TH', 'TD',
]);

const DROP_WITH_CONTENT = new Set([
  'SCRIPT', 'STYLE', 'IFRAME', 'OBJECT', 'EMBED', 'FORM', 'INPUT', 'BUTTON', 'TEXTAREA', 'SELECT',
  'SVG', 'MATH', 'LINK', 'META', 'NOSCRIPT', 'TEMPLATE',
]);

const ALLOWED_ATTRS: Record<string, string[]> = {
  '*': ['style'],
  A: ['href', 'target', 'rel'],
  IMG: ['src', 'alt', 'width', 'height'],
  LI: ['data-checked'],
  UL: ['data-checklist'],
  TD: ['colspan', 'rowspan'],
  TH: ['colspan', 'rowspan'],
  FONT: ['color'],
};

const ALLOWED_STYLES = new Set([
  'color', 'background-color', 'text-align', 'font-weight', 'font-style', 'text-decoration', 'font-size', 'margin-left', 'padding-left',
]);

function isSafeUrl(url: string, isImage: boolean): boolean {
  const value = url.trim();
  if (!value || /[\x00-\x1f]/.test(value)) return false;
  if (value.startsWith('/') || value.startsWith('#')) return !value.startsWith('//');
  try {
    const scheme = new URL(value).protocol.replace(':', '').toLowerCase();
    return isImage ? ['http', 'https'].includes(scheme) : ['http', 'https', 'mailto', 'tel'].includes(scheme);
  } catch {
    return false;
  }
}

function cleanStyle(style: string): string {
  return style
    .split(';')
    .map((decl) => {
      const idx = decl.indexOf(':');
      if (idx === -1) return '';
      const prop = decl.slice(0, idx).trim().toLowerCase();
      const val = decl.slice(idx + 1).trim();
      if (!ALLOWED_STYLES.has(prop)) return '';
      if (!/^[#a-zA-Z0-9.,%()\s-]{1,60}$/.test(val) || /url|expression|javascript/i.test(val)) return '';
      return `${prop}: ${val}`;
    })
    .filter(Boolean)
    .join('; ');
}

function walk(node: Node) {
  Array.from(node.childNodes).forEach((child) => {
    if (child.nodeType === Node.COMMENT_NODE) {
      child.remove();
      return;
    }
    if (child.nodeType !== Node.ELEMENT_NODE) return;

    const el = child as HTMLElement;
    const tag = el.tagName.toUpperCase();

    if (DROP_WITH_CONTENT.has(tag)) {
      el.remove();
      return;
    }

    walk(el);

    if (!ALLOWED_TAGS.has(tag)) {
      el.replaceWith(...Array.from(el.childNodes));
      return;
    }

    const allowed = [...ALLOWED_ATTRS['*'], ...(ALLOWED_ATTRS[tag] || [])];
    Array.from(el.attributes).forEach((attr) => {
      const name = attr.name.toLowerCase();
      if (!allowed.includes(name)) {
        el.removeAttribute(attr.name);
        return;
      }
      if (name === 'href' || name === 'src') {
        if (!isSafeUrl(attr.value, name === 'src')) el.removeAttribute(attr.name);
      } else if (name === 'style') {
        const style = cleanStyle(attr.value);
        style ? el.setAttribute('style', style) : el.removeAttribute('style');
      }
    });

    if (tag === 'A' && el.hasAttribute('href')) {
      el.setAttribute('target', '_blank');
      el.setAttribute('rel', 'noopener noreferrer nofollow');
    }
  });
}

export function sanitizeHtml(html: string | null | undefined): string {
  if (!html) return '';
  const doc = new DOMParser().parseFromString(`<div>${html}</div>`, 'text/html');
  const root = doc.body.firstElementChild as HTMLElement | null;
  if (!root) return '';
  walk(root);
  return root.innerHTML;
}

function escapeHtml(text: string): string {
  return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

/**
 * Render lesson/note content: legacy plain-text content becomes paragraphs, HTML is sanitized.
 */
export function toDisplayHtml(content: string | null | undefined): string {
  if (!content) return '';
  const looksLikeHtml = /<\/?[a-z][\s\S]*>/i.test(content);
  if (!looksLikeHtml) {
    return content
      .split(/\n{2,}/)
      .map((para) => `<p>${escapeHtml(para).replace(/\n/g, '<br>')}</p>`)
      .join('');
  }
  return sanitizeHtml(content);
}

/** Plain text (for word counts / previews). */
export function htmlToText(html: string | null | undefined): string {
  if (!html) return '';
  const doc = new DOMParser().parseFromString(html, 'text/html');
  return (doc.body.textContent || '').replace(/\s+/g, ' ').trim();
}
