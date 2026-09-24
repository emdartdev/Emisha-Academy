/**
 * Emisha Academy - Robust PWA Background Sync Engine
 * Handles online/offline detection, background drain of queues, and sync events.
 */

import axios from 'axios';
import { offlineDb, OfflineQueueItem } from './offlineDb';
import { useNetworkStore } from '../stores/network';

class OfflineSyncEngine {
  private isSyncing = false;
  private syncTimer: any = null;

  init() {
    if (typeof window === 'undefined') return;

    const networkStore = useNetworkStore();
    networkStore.updateOnlineStatus(navigator.onLine);

    window.addEventListener('online', () => {
      networkStore.updateOnlineStatus(true);
      this.syncNow();
    });

    window.addEventListener('offline', () => {
      networkStore.updateOnlineStatus(false);
    });

    // Check queue count on boot
    this.refreshPendingCount();

    // Periodic sync attempt if online
    this.syncTimer = setInterval(() => {
      if (navigator.onLine && !this.isSyncing) {
        this.syncNow(true);
      }
    }, 30000);
  }

  async refreshPendingCount() {
    try {
      const count = await offlineDb.getPendingCount();
      const networkStore = useNetworkStore();
      networkStore.setPendingCount(count);
    } catch (e) {
      // Ignore count error
    }
  }

  async syncNow(silent = false) {
    if (this.isSyncing || !navigator.onLine) return;

    this.isSyncing = true;
    const networkStore = useNetworkStore();
    networkStore.setSyncStatus('syncing');

    try {
      const pendingItems = await offlineDb.getPendingQueue();

      if (pendingItems.length === 0) {
        networkStore.setSyncStatus('synced');
        networkStore.setPendingCount(0);
        this.isSyncing = false;
        return;
      }

      let successCount = 0;
      let failureCount = 0;

      for (const item of pendingItems) {
        try {
          // Set to syncing
          item.status = 'syncing';
          await offlineDb.updateQueueItem(item);

          // Perform actual API request
          const response = await axios.post(item.endpoint, item.payload, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-Offline-Synced': 'true',
            },
          });

          if (response.status >= 200 && response.status < 300) {
            if (item.id !== undefined) {
              await offlineDb.removeFromQueue(item.id);
            }
            successCount++;
          } else {
            throw new Error(`Server returned ${response.status}`);
          }
        } catch (err: any) {
          item.retryCount = (item.retryCount || 0) + 1;
          item.status = 'failed';
          await offlineDb.updateQueueItem(item);
          failureCount++;
          console.warn('[Sync] Item sync failed:', item, err);
        }
      }

      await this.refreshPendingCount();

      if (failureCount > 0) {
        networkStore.setSyncStatus('failed');
      } else {
        networkStore.setSyncStatus('synced');
        if (!silent && successCount > 0) {
          networkStore.showSyncSuccessToast(successCount);
        }
      }
    } catch (err) {
      console.error('[SyncEngine] Sync error:', err);
      networkStore.setSyncStatus('failed');
    } finally {
      this.isSyncing = false;
    }
  }
}

export const offlineSync = new OfflineSyncEngine();
