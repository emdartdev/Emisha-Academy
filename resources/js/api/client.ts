import axios from 'axios';
import { offlineDb } from '../services/offlineDb';
import { offlineSync } from '../services/offlineSync';

export const apiClient = axios.create({
  baseURL: '/api/v1',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
  timeout: 15000,
});

// 1. Request Interceptor: Add Active Locale, Auth Token and CSRF
apiClient.interceptors.request.use((config) => {
  const locale = localStorage.getItem('emisha_locale') || 'bn';
  config.headers['X-App-Locale'] = locale;
  
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers['Authorization'] = `Bearer ${token}`;
  }
  
  return config;
});

// 2. Response Interceptor: Offline Caching & Background Queueing
apiClient.interceptors.response.use(
  async (response) => {
    // Automatically cache successful GET responses in IndexedDB
    if (response.config.method?.toLowerCase() === 'get' && response.status === 200) {
      const cacheKey = `${response.config.url}?${JSON.stringify(response.config.params || {})}`;
      try {
        await offlineDb.setCache(cacheKey, response.data);
      } catch (e) {
        // Ignore cache storage errors
      }
    }
    return response;
  },
  async (error) => {
    const config = error.config;

    // A. 401 Unauthorized Handling
    if (error.response?.status === 401) {
      const currentPath = typeof window !== 'undefined' ? window.location.pathname : '';
      if (
        currentPath.startsWith('/student') ||
        currentPath.startsWith('/admin') ||
        currentPath.startsWith('/manager') ||
        currentPath.startsWith('/worker')
      ) {
        window.location.href = '/login?redirect=' + encodeURIComponent(currentPath);
      }
      return Promise.reject(error);
    }

    // B. Offline / Network Failure Handling
    const isNetworkError = !error.response || error.code === 'ERR_NETWORK' || !navigator.onLine;

    if (isNetworkError && config) {
      const method = config.method?.toLowerCase();

      // Case 1: GET requests -> serve from IndexedDB cache
      if (method === 'get') {
        const cacheKey = `${config.url}?${JSON.stringify(config.params || {})}`;
        try {
          const cachedData = await offlineDb.getCache(cacheKey);
          if (cachedData) {
            console.log('[PWA Offline] Serving from IndexedDB:', cacheKey);
            return {
              data: cachedData,
              status: 200,
              statusText: 'OK (From Offline Cache)',
              headers: {},
              config,
              fromCache: true,
            };
          }
        } catch (dbErr) {
          console.warn('[PWA Offline] Cache fetch failed:', dbErr);
        }
      }

      // Case 2: POST/PUT mutation requests -> queue in IndexedDB
      if (method === 'post' || method === 'put') {
        try {
          const payload = typeof config.data === 'string' ? JSON.parse(config.data) : config.data || {};
          let type: any = 'lead_general';
          if (config.url?.includes('lead')) type = 'lead_enroll';
          else if (config.url?.includes('contact')) type = 'contact_message';
          else if (config.url?.includes('lesson')) type = 'lesson_progress';

          await offlineDb.enqueue({
            type,
            endpoint: config.url || '/api/v1/public/lead',
            payload,
          });

          await offlineSync.refreshPendingCount();

          return {
            data: {
              success: true,
              offline_queued: true,
              message: 'আপনার তথ্য অফলাইনে সংরক্ষিত হয়েছে। ইন্টারনেট চালু হলে স্বয়ংক্রিয়ভাবে সার্ভারে সিঙ্ক হবে।',
              data: payload,
            },
            status: 200,
            statusText: 'OK (Queued Offline)',
            headers: {},
            config,
          };
        } catch (qErr) {
          console.error('[PWA Offline] Enqueue failed:', qErr);
        }
      }
    }

    return Promise.reject(error);
  }
);

export default apiClient;
