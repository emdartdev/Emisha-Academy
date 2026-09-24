/**
 * Universal Image Error & Fallback Handler for Emisha Academy
 * Ensures NO image ever renders broken, missing, or with a browser broken-image icon.
 */

export function getInitialsAvatar(name: string = 'Emisha'): string {
  const cleanName = (name || 'E').trim();
  const initials = cleanName.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase() || 'EA';
  
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
    <defs>
      <linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#1e293b"/>
        <stop offset="100%" stop-color="#0f172a"/>
      </linearGradient>
      <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#D4AF37"/>
        <stop offset="100%" stop-color="#F7E7A9"/>
      </linearGradient>
    </defs>
    <rect width="120" height="120" rx="24" fill="url(#g)"/>
    <circle cx="60" cy="60" r="48" fill="none" stroke="url(#gold)" stroke-width="2" stroke-dasharray="4 2"/>
    <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" fill="url(#gold)" font-family="system-ui, -apple-system, sans-serif" font-weight="900" font-size="38" letter-spacing="1">${initials}</text>
  </svg>`;
  
  return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
}

export function getCourseFallbackThumbnail(categoryName: string = 'Aviation'): string {
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="800" height="450">
    <defs>
      <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#0b1120"/>
        <stop offset="50%" stop-color="#1e293b"/>
        <stop offset="100%" stop-color="#0b1120"/>
      </linearGradient>
      <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#D4AF37"/>
        <stop offset="100%" stop-color="#F7E7A9"/>
      </linearGradient>
    </defs>
    <rect width="800" height="450" fill="url(#bg)"/>
    <circle cx="700" cy="80" r="180" fill="#D4AF37" opacity="0.06"/>
    <circle cx="100" cy="380" r="220" fill="#38bdf8" opacity="0.05"/>
    <g transform="translate(350, 150) scale(1.5)">
      <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z" fill="url(#gold)"/>
    </g>
    <text x="400" y="320" text-anchor="middle" fill="#FFFFFF" font-family="system-ui, sans-serif" font-weight="800" font-size="28" letter-spacing="0.5">EMISHA ACADEMY</text>
    <text x="400" y="360" text-anchor="middle" fill="#D4AF37" font-family="system-ui, sans-serif" font-weight="600" font-size="16" letter-spacing="2">AIR TICKETING &amp; VISA TRAINING</text>
  </svg>`;
  return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
}

export function getEbookFallbackCover(title: string = 'Guidebook'): string {
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 560" width="400" height="560">
    <defs>
      <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#0f172a"/>
        <stop offset="50%" stop-color="#1e1b4b"/>
        <stop offset="100%" stop-color="#0b1120"/>
      </linearGradient>
      <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#D4AF37"/>
        <stop offset="100%" stop-color="#F7E7A9"/>
      </linearGradient>
    </defs>
    <rect width="400" height="560" rx="8" fill="url(#bg)"/>
    <rect x="0" y="0" width="24" height="560" fill="#000000" opacity="0.35"/>
    <rect x="36" y="36" width="328" height="488" rx="6" fill="none" stroke="url(#gold)" stroke-width="1.5" opacity="0.6"/>
    <g transform="translate(160, 140) scale(2.2)">
      <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z" fill="none" stroke="url(#gold)" stroke-width="2"/>
      <path d="M6 6h10" stroke="url(#gold)" stroke-width="2"/>
      <path d="M6 10h10" stroke="url(#gold)" stroke-width="2"/>
    </g>
    <text x="200" y="270" text-anchor="middle" fill="#FFFFFF" font-family="system-ui, sans-serif" font-weight="900" font-size="20">EMISHA ACADEMY</text>
    <text x="200" y="300" text-anchor="middle" fill="#D4AF37" font-family="system-ui, sans-serif" font-weight="700" font-size="13" letter-spacing="1">OFFICIAL STUDY GUIDE</text>
    <rect x="80" y="330" width="240" height="2" fill="url(#gold)" opacity="0.5"/>
    <text x="200" y="380" text-anchor="middle" fill="#94a3b8" font-family="system-ui, sans-serif" font-weight="600" font-size="12">AIR TICKETING &amp; GDS</text>
    <text x="200" y="410" text-anchor="middle" fill="#38bdf8" font-family="system-ui, sans-serif" font-weight="700" font-size="11">SABRE &amp; GALILEO EDITION</text>
    <rect x="120" y="470" width="160" height="28" rx="14" fill="#D4AF37" opacity="0.15"/>
    <text x="200" y="488" text-anchor="middle" fill="#D4AF37" font-family="system-ui, sans-serif" font-weight="800" font-size="10" letter-spacing="1">FREE PDF EBOOK</text>
  </svg>`;
  return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
}

export function getWebinarFallbackThumbnail(): string {
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="800" height="450">
    <defs>
      <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#0f172a"/>
        <stop offset="50%" stop-color="#1e293b"/>
        <stop offset="100%" stop-color="#0f172a"/>
      </linearGradient>
      <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#D4AF37"/>
        <stop offset="100%" stop-color="#F7E7A9"/>
      </linearGradient>
    </defs>
    <rect width="800" height="450" fill="url(#bg)"/>
    <circle cx="400" cy="200" r="50" fill="#D4AF37" opacity="0.2"/>
    <polygon points="390,185 420,200 390,215" fill="url(#gold)"/>
    <text x="400" y="300" text-anchor="middle" fill="#FFFFFF" font-family="system-ui, sans-serif" font-weight="900" font-size="26">LIVE MASTERCLASS &amp; WEBINAR</text>
    <text x="400" y="340" text-anchor="middle" fill="#D4AF37" font-family="system-ui, sans-serif" font-weight="700" font-size="15">EMISHA ACADEMY</text>
  </svg>`;
  return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
}

export function getBlogFallbackThumbnail(): string {
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="800" height="450">
    <defs>
      <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#020617"/>
        <stop offset="50%" stop-color="#0f172a"/>
        <stop offset="100%" stop-color="#1e293b"/>
      </linearGradient>
      <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#D4AF37"/>
        <stop offset="100%" stop-color="#F7E7A9"/>
      </linearGradient>
    </defs>
    <rect width="800" height="450" fill="url(#bg)"/>
    <text x="400" y="210" text-anchor="middle" fill="#FFFFFF" font-family="system-ui, sans-serif" font-weight="900" font-size="28">AVIATION &amp; TRAVEL BLOG</text>
    <text x="400" y="260" text-anchor="middle" fill="#D4AF37" font-family="system-ui, sans-serif" font-weight="700" font-size="16">PRACTICAL GUIDES &amp; INDUSTRY INSIGHTS</text>
  </svg>`;
  return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
}

export function onImageError(e: Event, type: 'avatar' | 'course' | 'ebook' | 'webinar' | 'blog' | 'logo' = 'course', name?: string) {
  const img = e.target as HTMLImageElement;
  if (!img) return;

  // Prevent infinite error loops
  if (img.dataset.hasFallback === 'true') return;
  img.dataset.hasFallback = 'true';

  switch (type) {
    case 'avatar':
      img.src = getInitialsAvatar(name || 'EA');
      break;
    case 'ebook':
      img.src = getEbookFallbackCover(name);
      break;
    case 'webinar':
      img.src = getWebinarFallbackThumbnail();
      break;
    case 'blog':
      img.src = getBlogFallbackThumbnail();
      break;
    case 'logo':
      img.src = '/favicon.png';
      break;
    case 'course':
    default:
      img.src = getCourseFallbackThumbnail(name);
      break;
  }
}
