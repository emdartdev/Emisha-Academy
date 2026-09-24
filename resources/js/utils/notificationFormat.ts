export function notificationIcon(type: string): string {
  switch (type) {
    case 'notice':
      return '📢';
    case 'module':
      return '📚';
    case 'lesson':
      return '🎬';
    default:
      return '🔔';
  }
}

export function notificationIconClass(type: string): string {
  switch (type) {
    case 'notice':
      return 'bg-amber-500/10 text-amber-500';
    case 'module':
      return 'bg-sky-500/10 text-sky-500';
    case 'lesson':
      return 'bg-emerald-500/10 text-emerald-500';
    default:
      return 'bg-[var(--bg-elevated)]';
  }
}

export function notificationTimeAgo(dateStr: string, locale: string): string {
  const diff = Math.max(0, (Date.now() - new Date(dateStr).getTime()) / 1000);
  const bn = locale === 'bn';
  if (diff < 60) return bn ? 'এইমাত্র' : 'just now';
  const units: [number, string, string][] = [
    [60, 'minute', 'মিনিট'],
    [3600, 'hour', 'ঘণ্টা'],
    [86400, 'day', 'দিন'],
  ];
  let value = 0;
  let label: [string, string] = ['minute', 'মিনিট'];
  for (const [secs, en, bnLabel] of units) {
    if (diff >= secs) {
      value = Math.floor(diff / secs);
      label = [en, bnLabel];
    }
  }
  if (value >= 7 && label[0] === 'day') {
    return new Date(dateStr).toLocaleDateString(bn ? 'bn-BD' : 'en-GB', { day: 'numeric', month: 'short' });
  }
  if (bn) {
    return `${value.toLocaleString('bn-BD')} ${label[1]} আগে`;
  }
  return `${value} ${label[0]}${value > 1 ? 's' : ''} ago`;
}
