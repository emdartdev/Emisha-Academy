/**
 * Pre-built Curated Profile Avatars for Emisha Academy
 * Ensures 100% offline, crisp, scalable SVG avatars without any external stock photos.
 */

export interface PrebuiltAvatar {
  id: string;
  name_bn: string;
  name_en: string;
  category: 'aviation' | 'specialist' | 'executive' | 'student';
  svgDataUri: string;
}

export const PREBUILT_AVATARS: PrebuiltAvatar[] = [
  {
    id: 'pilot_captain',
    name_bn: 'এভিয়েশন পাইলট / ক্যাপ্টেন',
    name_en: 'Aviation Captain / Pilot',
    category: 'aviation',
    svgDataUri: `data:image/svg+xml;utf8,${encodeURIComponent(`
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
        <defs>
          <linearGradient id="bg_pilot" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#091428"/>
            <stop offset="100%" stop-color="#1A2D4C"/>
          </linearGradient>
          <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#D4AF37"/>
            <stop offset="100%" stop-color="#FCEBA4"/>
          </linearGradient>
        </defs>
        <rect width="120" height="120" rx="28" fill="url(#bg_pilot)"/>
        <!-- Pilot Cap -->
        <path d="M30 48 Q60 30 90 48 L86 58 Q60 48 34 58 Z" fill="#0B1320"/>
        <path d="M26 48 C26 44 40 38 60 38 C80 38 94 44 94 48 L92 53 C80 47 40 47 28 53 Z" fill="#1E293B"/>
        <circle cx="60" cy="45" r="7" fill="url(#gold)"/>
        <!-- Face -->
        <circle cx="60" cy="62" r="18" fill="#F8D7B0"/>
        <!-- Hair/Cap side -->
        <path d="M42 58 Q46 72 44 76" stroke="#332211" stroke-width="3" fill="none"/>
        <path d="M78 58 Q74 72 76 76" stroke="#332211" stroke-width="3" fill="none"/>
        <!-- Eyes & Smile -->
        <circle cx="53" cy="62" r="2" fill="#1E293B"/>
        <circle cx="67" cy="62" r="2" fill="#1E293B"/>
        <path d="M55 70 Q60 74 65 70" stroke="#8A4A28" stroke-width="2" fill="none" stroke-linecap="round"/>
        <!-- Uniform & Tie -->
        <path d="M34 105 Q36 84 60 84 Q84 84 86 105 Z" fill="#0F172A"/>
        <polygon points="56,84 64,84 62,105 58,105" fill="url(#gold)"/>
        <polygon points="50,84 60,94 56,84" fill="#FFFFFF"/>
        <polygon points="70,84 60,94 64,84" fill="#FFFFFF"/>
      </svg>
    `)}`
  },
  {
    id: 'ticketing_specialist',
    name_bn: 'টিকেটিং ও জিডিএস স্পেশালিস্ট',
    name_en: 'Ticketing & GDS Specialist',
    category: 'aviation',
    svgDataUri: `data:image/svg+xml;utf8,${encodeURIComponent(`
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
        <defs>
          <linearGradient id="bg_ticket" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#042F2E"/>
            <stop offset="100%" stop-color="#115E59"/>
          </linearGradient>
          <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#D4AF37"/>
            <stop offset="100%" stop-color="#FCEBA4"/>
          </linearGradient>
        </defs>
        <rect width="120" height="120" rx="28" fill="url(#bg_ticket)"/>
        <!-- Headset -->
        <path d="M34 58 A26 26 0 0 1 86 58" fill="none" stroke="url(#gold)" stroke-width="4" stroke-linecap="round"/>
        <rect x="30" y="52" width="7" height="15" rx="3" fill="#334155"/>
        <rect x="83" y="52" width="7" height="15" rx="3" fill="#334155"/>
        <path d="M34 64 Q46 80 54 78" stroke="url(#gold)" stroke-width="2" fill="none"/>
        <circle cx="54" cy="78" r="3" fill="#EF4444"/>
        <!-- Face & Hair -->
        <path d="M42 45 Q60 32 78 45 Q82 58 78 72 Q60 88 42 72 Z" fill="#ECC39E"/>
        <path d="M38 46 Q60 28 82 46 Q70 38 38 46" fill="#1C1917"/>
        <circle cx="51" cy="58" r="2.2" fill="#0F172A"/>
        <circle cx="69" cy="58" r="2.2" fill="#0F172A"/>
        <path d="M54 68 Q60 72 66 68" stroke="#7C2D12" stroke-width="2" fill="none" stroke-linecap="round"/>
        <!-- Shirt -->
        <path d="M32 105 Q34 82 60 82 Q86 82 88 105 Z" fill="#0F766E"/>
        <path d="M52 82 L60 94 L68 82" fill="#FFFFFF"/>
      </svg>
    `)}`
  },
  {
    id: 'visa_consultant',
    name_bn: 'ভিসা ও ট্যুরিজম কনসালট্যান্ট',
    name_en: 'Visa & Tourism Consultant',
    category: 'specialist',
    svgDataUri: `data:image/svg+xml;utf8,${encodeURIComponent(`
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
        <defs>
          <linearGradient id="bg_visa" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#1E1B4B"/>
            <stop offset="100%" stop-color="#3730A3"/>
          </linearGradient>
          <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#D4AF37"/>
            <stop offset="100%" stop-color="#FCEBA4"/>
          </linearGradient>
        </defs>
        <rect width="120" height="120" rx="28" fill="url(#bg_visa)"/>
        <!-- Passport / Stamp Accent Behind -->
        <circle cx="95" cy="30" r="16" fill="url(#gold)" opacity="0.2"/>
        <!-- Face & Modern Hijab / Scarf Style -->
        <circle cx="60" cy="60" r="17" fill="#F4D0AB"/>
        <path d="M38 52 C38 32 82 32 82 52 C82 78 78 86 60 86 C42 86 38 78 38 52 Z" fill="#312E81" opacity="0.95"/>
        <circle cx="60" cy="58" r="14" fill="#F4D0AB"/>
        <!-- Glasses -->
        <rect x="47" y="53" width="10" height="7" rx="2" fill="none" stroke="url(#gold)" stroke-width="1.5"/>
        <rect x="63" y="53" width="10" height="7" rx="2" fill="none" stroke="url(#gold)" stroke-width="1.5"/>
        <line x1="57" y1="56" x2="63" y2="56" stroke="url(#gold)" stroke-width="1.5"/>
        <!-- Eyes & Smile -->
        <circle cx="52" cy="56" r="1.5" fill="#1E1B4B"/>
        <circle cx="68" cy="56" r="1.5" fill="#1E1B4B"/>
        <path d="M55 64 Q60 67 65 64" stroke="#9A3412" stroke-width="1.5" fill="none" stroke-linecap="round"/>
        <!-- Professional Blazer -->
        <path d="M30 105 Q35 84 60 84 Q85 84 90 105 Z" fill="#1E1B4B"/>
        <polygon points="50,84 60,102 70,84" fill="#FFFFFF"/>
        <circle cx="60" cy="94" r="3" fill="url(#gold)"/>
      </svg>
    `)}`
  },
  {
    id: 'executive_male',
    name_bn: 'এভিয়েশন এক্সিকিউটিভ (পুরুষ)',
    name_en: 'Aviation Executive (Male)',
    category: 'executive',
    svgDataUri: `data:image/svg+xml;utf8,${encodeURIComponent(`
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
        <defs>
          <linearGradient id="bg_exec" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#0F172A"/>
            <stop offset="100%" stop-color="#334155"/>
          </linearGradient>
          <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#D4AF37"/>
            <stop offset="100%" stop-color="#FCEBA4"/>
          </linearGradient>
        </defs>
        <rect width="120" height="120" rx="28" fill="url(#bg_exec)"/>
        <!-- Head & Hair -->
        <path d="M42 46 Q60 26 78 46 Q80 62 76 74 Q60 88 44 74 Z" fill="#E8BD96"/>
        <path d="M38 46 Q60 22 82 42 Q78 30 50 30 Q38 34 38 46" fill="#1E293B"/>
        <!-- Eyes & Eyebrows -->
        <line x1="48" y1="52" x2="55" y2="51" stroke="#0F172A" stroke-width="1.8" stroke-linecap="round"/>
        <line x1="65" y1="51" x2="72" y2="52" stroke="#0F172A" stroke-width="1.8" stroke-linecap="round"/>
        <circle cx="52" cy="57" r="2.2" fill="#0F172A"/>
        <circle cx="68" cy="57" r="2.2" fill="#0F172A"/>
        <path d="M54 68 Q60 73 66 68" stroke="#883B1E" stroke-width="2" fill="none" stroke-linecap="round"/>
        <!-- Suit, Shirt & Gold Tie -->
        <path d="M30 105 Q34 82 60 82 Q86 82 90 105 Z" fill="#0F172A"/>
        <polygon points="50,82 60,98 70,82" fill="#FFFFFF"/>
        <polygon points="58,84 62,84 64,105 56,105" fill="url(#gold)"/>
      </svg>
    `)}`
  },
  {
    id: 'executive_female',
    name_bn: 'এভিয়েশন এক্সিকিউটিভ (নারী)',
    name_en: 'Aviation Executive (Female)',
    category: 'executive',
    svgDataUri: `data:image/svg+xml;utf8,${encodeURIComponent(`
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
        <defs>
          <linearGradient id="bg_exec_f" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#4C1D95"/>
            <stop offset="100%" stop-color="#7C3AED"/>
          </linearGradient>
          <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#D4AF37"/>
            <stop offset="100%" stop-color="#FCEBA4"/>
          </linearGradient>
        </defs>
        <rect width="120" height="120" rx="28" fill="url(#bg_exec_f)"/>
        <!-- Long Hair Back -->
        <path d="M36 50 Q30 85 40 98 L80 98 Q90 85 84 50 Z" fill="#18181B"/>
        <!-- Face -->
        <circle cx="60" cy="58" r="17" fill="#F8D7B0"/>
        <!-- Hair Front Style -->
        <path d="M38 46 Q60 28 82 46 Q75 36 60 36 Q42 36 38 46" fill="#18181B"/>
        <!-- Eyes & Eyelashes -->
        <path d="M48 56 Q52 53 56 56" stroke="#0F172A" stroke-width="2" fill="none"/>
        <path d="M64 56 Q68 53 72 56" stroke="#0F172A" stroke-width="2" fill="none"/>
        <circle cx="52" cy="57" r="1.8" fill="#0F172A"/>
        <circle cx="68" cy="57" r="1.8" fill="#0F172A"/>
        <!-- Warm Smile -->
        <path d="M54 66 Q60 71 66 66" stroke="#BE185D" stroke-width="2" fill="none" stroke-linecap="round"/>
        <!-- Gold Earrings -->
        <circle cx="41" cy="62" r="2.5" fill="url(#gold)"/>
        <circle cx="79" cy="62" r="2.5" fill="url(#gold)"/>
        <!-- Modern Navy Top with Gold Pin -->
        <path d="M32 105 Q35 82 60 82 Q85 82 88 105 Z" fill="#1E1B4B"/>
        <circle cx="60" cy="88" r="3.5" fill="url(#gold)"/>
      </svg>
    `)}`
  },
  {
    id: 'student_male',
    name_bn: 'লার্নার ও স্কলার (পুরুষ)',
    name_en: 'Learner & Scholar (Male)',
    category: 'student',
    svgDataUri: `data:image/svg+xml;utf8,${encodeURIComponent(`
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
        <defs>
          <linearGradient id="bg_stud_m" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#0369A1"/>
            <stop offset="100%" stop-color="#0284C7"/>
          </linearGradient>
          <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#D4AF37"/>
            <stop offset="100%" stop-color="#FCEBA4"/>
          </linearGradient>
        </defs>
        <rect width="120" height="120" rx="28" fill="url(#bg_stud_m)"/>
        <!-- Face & Hair -->
        <circle cx="60" cy="60" r="18" fill="#F8D7B0"/>
        <!-- Modern haircut -->
        <path d="M38 48 Q60 26 82 46 Q80 34 60 32 Q40 34 38 48" fill="#451A03"/>
        <!-- Friendly Eyes & Smile -->
        <circle cx="52" cy="58" r="2.2" fill="#0C4A6E"/>
        <circle cx="68" cy="58" r="2.2" fill="#0C4A6E"/>
        <path d="M53 67 Q60 74 67 67" stroke="#9A3412" stroke-width="2.2" fill="none" stroke-linecap="round"/>
        <!-- Hoodie / Casual Tech Learner -->
        <path d="M30 105 Q34 82 60 82 Q86 82 90 105 Z" fill="#075985"/>
        <path d="M50 82 Q60 94 70 82" fill="#BAE6FD"/>
      </svg>
    `)}`
  },
  {
    id: 'student_female',
    name_bn: 'লার্নার ও স্কলার (নারী)',
    name_en: 'Learner & Scholar (Female)',
    category: 'student',
    svgDataUri: `data:image/svg+xml;utf8,${encodeURIComponent(`
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
        <defs>
          <linearGradient id="bg_stud_f" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#9D174D"/>
            <stop offset="100%" stop-color="#DB2777"/>
          </linearGradient>
          <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#D4AF37"/>
            <stop offset="100%" stop-color="#FCEBA4"/>
          </linearGradient>
        </defs>
        <rect width="120" height="120" rx="28" fill="url(#bg_stud_f)"/>
        <!-- Hair -->
        <circle cx="60" cy="58" r="22" fill="#27272A"/>
        <circle cx="60" cy="60" r="16" fill="#F8D7B0"/>
        <path d="M42 46 Q60 36 78 46" fill="#27272A"/>
        <!-- Eyes & Smile -->
        <circle cx="53" cy="58" r="2" fill="#831843"/>
        <circle cx="67" cy="58" r="2" fill="#831843"/>
        <path d="M54 67 Q60 72 66 67" stroke="#BE123C" stroke-width="2" fill="none" stroke-linecap="round"/>
        <!-- Casual Student Top -->
        <path d="M32 105 Q35 84 60 84 Q85 84 88 105 Z" fill="#831843"/>
        <polygon points="54,84 60,94 66,84" fill="#FCE7F3"/>
      </svg>
    `)}`
  },
  {
    id: 'gold_crest',
    name_bn: 'ইমিশা গোল্ড মেম্বার ক্রেস্ট',
    name_en: 'Emisha Gold Academy Crest',
    category: 'executive',
    svgDataUri: `data:image/svg+xml;utf8,${encodeURIComponent(`
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
        <defs>
          <linearGradient id="bg_crest" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#0F172A"/>
            <stop offset="50%" stop-color="#1E293B"/>
            <stop offset="100%" stop-color="#090E17"/>
          </linearGradient>
          <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#D4AF37"/>
            <stop offset="50%" stop-color="#FCEBA4"/>
            <stop offset="100%" stop-color="#AA8418"/>
          </linearGradient>
        </defs>
        <rect width="120" height="120" rx="28" fill="url(#bg_crest)"/>
        <!-- Royal Outer Border Shield -->
        <circle cx="60" cy="60" r="46" fill="none" stroke="url(#gold)" stroke-width="2" stroke-dasharray="3 2"/>
        <circle cx="60" cy="60" r="38" fill="#0B132B" stroke="url(#gold)" stroke-width="1.5"/>
        <!-- Airplane & Graduation Crest -->
        <g transform="translate(42, 42) scale(1.5)">
          <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z" fill="url(#gold)"/>
        </g>
      </svg>
    `)}`
  }
];

export function getAvatarUriById(avatarId?: string | null, userName: string = 'Emdad'): string {
  if (avatarId && avatarId.startsWith('data:image/svg')) {
    return avatarId;
  }
  const found = PREBUILT_AVATARS.find(a => a.id === avatarId);
  if (found) {
    return found.svgDataUri;
  }
  // If user.avatar is an http url or custom path, return it if it's not a generic stock placeholder
  if (avatarId && !avatarId.includes('unsplash.com') && (avatarId.startsWith('http') || avatarId.startsWith('/'))) {
    return avatarId;
  }
  // Default fallback is the gold monogram initials avatar
  const cleanName = (userName || 'E').trim();
  const initials = cleanName.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase() || 'EA';
  
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
    <defs>
      <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#0F172A"/>
        <stop offset="100%" stop-color="#1E293B"/>
      </linearGradient>
      <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#D4AF37"/>
        <stop offset="100%" stop-color="#FCEBA4"/>
      </linearGradient>
    </defs>
    <rect width="120" height="120" rx="28" fill="url(#bg)"/>
    <circle cx="60" cy="60" r="46" fill="none" stroke="url(#gold)" stroke-width="2" stroke-dasharray="4 2"/>
    <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="url(#gold)" font-family="system-ui, -apple-system, sans-serif" font-weight="900" font-size="36" letter-spacing="1">${initials}</text>
  </svg>`;

  return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
}
