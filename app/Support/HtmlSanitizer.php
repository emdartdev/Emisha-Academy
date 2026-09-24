<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Allowlist-based HTML cleaner for rich-text written in the in-app editors
 * (student notes, text lessons). Strips scripts, event handlers, unsafe URLs and unknown styles.
 */
class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'div', 'span', 'b', 'strong', 'i', 'em', 'u', 's', 'strike', 'del', 'mark', 'sub', 'sup',
        'h1', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'blockquote', 'pre', 'code', 'a', 'hr', 'img', 'font',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
    ];

    private const ALLOWED_ATTRS = [
        '*' => ['style'],
        'a' => ['href', 'target', 'rel'],
        'img' => ['src', 'alt', 'width', 'height'],
        'li' => ['data-checked'],
        'ul' => ['data-checklist'],
        'td' => ['colspan', 'rowspan'],
        'th' => ['colspan', 'rowspan'],
        'font' => ['color'],
    ];

    private const ALLOWED_STYLES = [
        'color', 'background-color', 'text-align', 'font-weight', 'font-style', 'text-decoration', 'font-size', 'margin-left', 'padding-left',
    ];

    /** Tags whose content is dropped entirely rather than unwrapped. */
    private const DROP_WITH_CONTENT = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'svg', 'math', 'link', 'meta', 'noscript', 'template'];

    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $doc = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8"><div id="__root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementById('__root');
        if (!$root) {
            return '';
        }

        self::walk($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return $out;
    }

    private static function walk(DOMNode $node): void
    {
        // Iterate over a static copy since we mutate the tree
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_COMMENT_NODE || $child->nodeType === XML_PI_NODE) {
                $node->removeChild($child);
                continue;
            }
            if (!$child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
                $node->removeChild($child);
                continue;
            }

            self::walk($child);

            if (!in_array($tag, self::ALLOWED_TAGS, true)) {
                // Unwrap: keep text/children, drop the element itself
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            self::cleanAttributes($child, $tag);
        }
    }

    private static function cleanAttributes(DOMElement $el, string $tag): void
    {
        $allowed = array_merge(self::ALLOWED_ATTRS['*'], self::ALLOWED_ATTRS[$tag] ?? []);

        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->name);
            if (!in_array($name, $allowed, true)) {
                $el->removeAttribute($attr->name);
                continue;
            }

            $value = trim($attr->value);

            if ($name === 'href' || $name === 'src') {
                if (!self::isSafeUrl($value, $name === 'src')) {
                    $el->removeAttribute($attr->name);
                }
            } elseif ($name === 'style') {
                $style = self::cleanStyle($value);
                $style === '' ? $el->removeAttribute('style') : $el->setAttribute('style', $style);
            } elseif ($name === 'target') {
                $el->setAttribute('target', '_blank');
            } elseif ($name === 'color' && !preg_match('/^#?[0-9a-zA-Z]{1,20}$/', $value)) {
                $el->removeAttribute('color');
            }
        }

        if ($tag === 'a' && $el->hasAttribute('href')) {
            $el->setAttribute('target', '_blank');
            $el->setAttribute('rel', 'noopener noreferrer nofollow');
        }
    }

    private static function isSafeUrl(string $url, bool $isImage): bool
    {
        if ($url === '' || preg_match('/[\x00-\x1f]/', $url)) {
            return false;
        }
        if (str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return !str_starts_with($url, '//');
        }
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return $isImage ? in_array($scheme, ['http', 'https'], true) : in_array($scheme, ['http', 'https', 'mailto', 'tel'], true);
    }

    private static function cleanStyle(string $style): string
    {
        $kept = [];
        foreach (explode(';', $style) as $decl) {
            if (!str_contains($decl, ':')) {
                continue;
            }
            [$prop, $val] = array_map('trim', explode(':', $decl, 2));
            $prop = strtolower($prop);
            if (!in_array($prop, self::ALLOWED_STYLES, true)) {
                continue;
            }
            // Plain values only: colours, keywords, lengths — no url(), expression(), etc.
            if (!preg_match('/^[#a-zA-Z0-9.,%()\s-]{1,60}$/', $val) || preg_match('/url|expression|javascript/i', $val)) {
                continue;
            }
            $kept[] = "{$prop}: {$val}";
        }

        return implode('; ', $kept);
    }
}
