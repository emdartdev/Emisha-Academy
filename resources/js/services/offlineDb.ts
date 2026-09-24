/**
 * Emisha Academy - IndexedDB Local-First Database Service
 * Provides robust offline storage and queue management for the PWA.
 */

const DB_NAME = 'emisha_academy_offline_db';
const DB_VERSION = 1;

export interface OfflineQueueItem {
  id?: number;
  type: 'lead_enroll' | 'lead_webinar' | 'lead_general' | 'contact_message' | 'lesson_progress';
  endpoint: string;
  payload: Record<string, any>;
  createdAt: number;
  status: 'pending' | 'syncing' | 'failed';
  retryCount: number;
}

export interface CachedItem<T = any> {
  key: string;
  data: T;
  cachedAt: number;
}

class OfflineDbService {
  private dbPromise: Promise<IDBDatabase> | null = null;

  private getDB(): Promise<IDBDatabase> {
    if (this.dbPromise) {
      return this.dbPromise;
    }

    this.dbPromise = new Promise((resolve, reject) => {
      if (typeof window === 'undefined' || !window.indexedDB) {
        return reject(new Error('IndexedDB not supported in this browser.'));
      }

      const request = indexedDB.open(DB_NAME, DB_VERSION);

      request.onupgradeneeded = (event) => {
        const db = (event.target as IDBOpenDBRequest).result;

        // 1. Store for sync queue items
        if (!db.objectStoreNames.contains('sync_queue')) {
          const queueStore = db.createObjectStore('sync_queue', { keyPath: 'id', autoIncrement: true });
          queueStore.createIndex('status', 'status', { unique: false });
          queueStore.createIndex('createdAt', 'createdAt', { unique: false });
        }

        // 2. Store for cached API responses (courses, webinars, etc.)
        if (!db.objectStoreNames.contains('cache_store')) {
          db.createObjectStore('cache_store', { keyPath: 'key' });
        }

        // 3. Store for offline user preferences & notes
        if (!db.objectStoreNames.contains('user_data')) {
          db.createObjectStore('user_data', { keyPath: 'key' });
        }
      };

      request.onsuccess = () => resolve(request.result);
      request.onerror = () => reject(request.error);
    });

    return this.dbPromise;
  }

  // --- QUEUE MANAGEMENT ---

  /**
   * Enqueues an action/lead when offline
   */
  async enqueue(item: Omit<OfflineQueueItem, 'id' | 'createdAt' | 'status' | 'retryCount'>): Promise<number> {
    const db = await this.getDB();
    return new Promise((resolve, reject) => {
      const tx = db.transaction('sync_queue', 'readwrite');
      const store = tx.objectStore('sync_queue');
      const record: OfflineQueueItem = {
        ...item,
        createdAt: Date.now(),
        status: 'pending',
        retryCount: 0,
      };

      const req = store.add(record);
      req.onsuccess = () => resolve(req.result as number);
      req.onerror = () => reject(req.error);
    });
  }

  /**
   * Gets all pending queue items
   */
  async getPendingQueue(): Promise<OfflineQueueItem[]> {
    const db = await this.getDB();
    return new Promise((resolve, reject) => {
      const tx = db.transaction('sync_queue', 'readonly');
      const store = tx.objectStore('sync_queue');
      const req = store.getAll();

      req.onsuccess = () => {
        const items = (req.result as OfflineQueueItem[]) || [];
        resolve(items.filter((i) => i.status === 'pending' || i.status === 'failed'));
      };
      req.onerror = () => reject(req.error);
    });
  }

  /**
   * Remove item from queue after successful sync
   */
  async removeFromQueue(id: number): Promise<void> {
    const db = await this.getDB();
    return new Promise((resolve, reject) => {
      const tx = db.transaction('sync_queue', 'readwrite');
      const store = tx.objectStore('sync_queue');
      const req = store.delete(id);
      req.onsuccess = () => resolve();
      req.onerror = () => reject(req.error);
    });
  }

  /**
   * Updates queue item status / retry count
   */
  async updateQueueItem(item: OfflineQueueItem): Promise<void> {
    const db = await this.getDB();
    return new Promise((resolve, reject) => {
      const tx = db.transaction('sync_queue', 'readwrite');
      const store = tx.objectStore('sync_queue');
      const req = store.put(item);
      req.onsuccess = () => resolve();
      req.onerror = () => reject(req.error);
    });
  }

  /**
   * Gets count of pending items in queue
   */
  async getPendingCount(): Promise<number> {
    const items = await this.getPendingQueue();
    return items.length;
  }

  // --- LOCAL CATALOG CACHING ---

  /**
   * Save structured data into IndexedDB cache
   */
  async setCache<T>(key: string, data: T): Promise<void> {
    const db = await this.getDB();
    return new Promise((resolve, reject) => {
      const tx = db.transaction('cache_store', 'readwrite');
      const store = tx.objectStore('cache_store');
      const record: CachedItem<T> = {
        key,
        data,
        cachedAt: Date.now(),
      };
      const req = store.put(record);
      req.onsuccess = () => resolve();
      req.onerror = () => reject(req.error);
    });
  }

  /**
   * Retrieve structured data from IndexedDB cache
   */
  async getCache<T>(key: string): Promise<T | null> {
    const db = await this.getDB();
    return new Promise((resolve, reject) => {
      const tx = db.transaction('cache_store', 'readonly');
      const store = tx.objectStore('cache_store');
      const req = store.get(key);
      req.onsuccess = () => {
        const result = req.result as CachedItem<T> | undefined;
        resolve(result ? result.data : null);
      };
      req.onerror = () => reject(req.error);
    });
  }

  // --- USER DATA / NOTES / BOOKMARKS ---

  async setUserData<T>(key: string, value: T): Promise<void> {
    const db = await this.getDB();
    return new Promise((resolve, reject) => {
      const tx = db.transaction('user_data', 'readwrite');
      const store = tx.objectStore('user_data');
      const req = store.put({ key, value, updatedAt: Date.now() });
      req.onsuccess = () => resolve();
      req.onerror = () => reject(req.error);
    });
  }

  async getUserData<T>(key: string): Promise<T | null> {
    const db = await this.getDB();
    return new Promise((resolve, reject) => {
      const tx = db.transaction('user_data', 'readonly');
      const store = tx.objectStore('user_data');
      const req = store.get(key);
      req.onsuccess = () => {
        const res = req.result;
        resolve(res ? (res.value as T) : null);
      };
      req.onerror = () => reject(req.error);
    });
  }

  /**
   * Export all local data to JSON for backup
   */
  async exportBackup(): Promise<string> {
    const db = await this.getDB();
    const backup: Record<string, any> = {
      exportedAt: new Date().toISOString(),
      app: 'Emisha Academy',
      version: '1.0.0',
    };

    return new Promise((resolve, reject) => {
      const tx = db.transaction(['sync_queue', 'cache_store', 'user_data'], 'readonly');
      const qReq = tx.objectStore('sync_queue').getAll();
      const uReq = tx.objectStore('user_data').getAll();

      tx.oncomplete = () => {
        backup.sync_queue = qReq.result;
        backup.user_data = uReq.result;
        resolve(JSON.stringify(backup, null, 2));
      };
      tx.onerror = () => reject(tx.error);
    });
  }
}

export const offlineDb = new OfflineDbService();
