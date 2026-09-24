import { defineStore } from 'pinia';

export type SyncStatus = 'synced' | 'pending' | 'syncing' | 'failed';

export const useNetworkStore = defineStore('network', {
  state: () => ({
    isOnline: typeof navigator !== 'undefined' ? navigator.onLine : true,
    syncStatus: 'synced' as SyncStatus,
    pendingCount: 0,
    isInstallPromptAvailable: false,
    deferredPrompt: null as any,
    isInstalled: false,
    isUpdateAvailable: false,
    waitingWorker: null as ServiceWorker | null,
    syncSuccessMessage: '',
  }),

  getters: {
    isOffline: (state) => !state.isOnline,
  },

  actions: {
    updateOnlineStatus(status: boolean) {
      this.isOnline = status;
      if (!status) {
        this.syncStatus = 'pending';
      }
    },

    setSyncStatus(status: SyncStatus) {
      this.syncStatus = status;
    },

    setPendingCount(count: number) {
      this.pendingCount = count;
      if (count > 0 && this.syncStatus === 'synced') {
        this.syncStatus = 'pending';
      }
    },

    setDeferredPrompt(event: any) {
      this.deferredPrompt = event;
      this.isInstallPromptAvailable = true;
    },

    clearInstallPrompt() {
      this.deferredPrompt = null;
      this.isInstallPromptAvailable = false;
    },

    setUpdateAvailable(worker: ServiceWorker) {
      this.isUpdateAvailable = true;
      this.waitingWorker = worker;
    },

    applyUpdate() {
      if (this.waitingWorker) {
        this.waitingWorker.postMessage({ type: 'SKIP_WAITING' });
      }
      window.location.reload();
    },

    showSyncSuccessToast(count: number) {
      this.syncSuccessMessage = `${count}টি পেন্ডিং অ্যাকশন সফলভাবে সার্ভারে সিঙ্ক হয়েছে।`;
      setTimeout(() => {
        this.syncSuccessMessage = '';
      }, 5000);
    },
  },
});
