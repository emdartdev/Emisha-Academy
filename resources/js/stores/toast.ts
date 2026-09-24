import { defineStore } from 'pinia';

export interface Toast {
  id: string;
  type: 'success' | 'error' | 'warning' | 'info';
  title?: string;
  message: string;
  duration?: number;
}

export const useToastStore = defineStore('toast', {
  state: () => ({
    toasts: [] as Toast[],
  }),
  actions: {
    show(toast: Omit<Toast, 'id'>) {
      const id = Math.random().toString(36).substring(2, 9);
      const duration = toast.duration ?? 4000;
      const newToast: Toast = {
        ...toast,
        id,
        duration,
      };

      this.toasts.push(newToast);

      if (duration > 0) {
        setTimeout(() => {
          this.remove(id);
        }, duration);
      }
    },
    success(message: string, title?: string) {
      this.show({ type: 'success', message, title });
    },
    error(message: string, title?: string) {
      this.show({ type: 'error', message, title });
    },
    warning(message: string, title?: string) {
      this.show({ type: 'warning', message, title });
    },
    info(message: string, title?: string) {
      this.show({ type: 'info', message, title });
    },
    remove(id: string) {
      this.toasts = this.toasts.filter((t) => t.id !== id);
    },
  },
});
