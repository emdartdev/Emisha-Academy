import { defineStore } from 'pinia';
import apiClient from '../api/client';

export interface StudentNotification {
  id: number;
  type: 'notice' | 'lesson' | 'module' | string;
  title: string;
  message: string | null;
  link: string | null;
  data: Record<string, any> | null;
  read_at: string | null;
  created_at: string;
}

const POLL_MS = 60_000;
let pollTimer: ReturnType<typeof setInterval> | null = null;

export const useNotificationStore = defineStore('studentNotifications', {
  state: () => ({
    items: [] as StudentNotification[],
    unreadCount: 0,
    loading: false,
    hasMore: false,
    page: 1,
  }),
  actions: {
    async fetchLatest(page = 1) {
      this.loading = true;
      try {
        const res = await apiClient.get('/student/notifications', { params: { page, per_page: 15 } });
        const paginated = res.data.data.notifications;
        this.items = page === 1 ? paginated.data : [...this.items, ...paginated.data];
        this.page = paginated.current_page;
        this.hasMore = paginated.current_page < paginated.last_page;
        this.unreadCount = res.data.data.unread_count;
      } catch {
        // keep previous state
      } finally {
        this.loading = false;
      }
    },

    async refreshCount() {
      try {
        const res = await apiClient.get('/student/notifications/unread-count');
        const count = res.data.data.unread_count;
        // New arrivals: refresh the list so the dropdown is current
        if (count > this.unreadCount) {
          this.fetchLatest(1);
        }
        this.unreadCount = count;
      } catch {
        // ignore transient errors
      }
    },

    async markRead(id: number) {
      const item = this.items.find((n) => n.id === id);
      if (item && !item.read_at) {
        item.read_at = new Date().toISOString();
        this.unreadCount = Math.max(0, this.unreadCount - 1);
      }
      try {
        const res = await apiClient.post(`/student/notifications/${id}/read`);
        this.unreadCount = res.data.data.unread_count;
      } catch {
        // optimistic
      }
    },

    async markAllRead() {
      this.items.forEach((n) => {
        if (!n.read_at) n.read_at = new Date().toISOString();
      });
      this.unreadCount = 0;
      try {
        await apiClient.post('/student/notifications/read-all');
      } catch {
        // optimistic
      }
    },

    startPolling() {
      this.fetchLatest(1);
      if (pollTimer) return;
      pollTimer = setInterval(() => {
        if (document.visibilityState === 'visible') this.refreshCount();
      }, POLL_MS);
    },

    stopPolling() {
      if (pollTimer) clearInterval(pollTimer);
      pollTimer = null;
    },
  },
});

export default useNotificationStore;
