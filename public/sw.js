/**
 * Emisha Academy - Production Progressive Web App (PWA) Service Worker
 * Version: 1.0.1
 */

const CACHE_VERSION = 'emisha-pwa-v1.0.3';
const STATIC_CACHE_NAME = `${CACHE_VERSION}-static`;
const RUNTIME_CACHE_NAME = `${CACHE_VERSION}-runtime`;
const IMAGE_CACHE_NAME = `${CACHE_VERSION}-images`;
const API_CACHE_NAME = `${CACHE_VERSION}-api`;

// Core static assets to cache during installation
const PRECACHE_ASSETS = [
  '/',
  '/manifest.json',
  '/favicon.png',
  '/favicon.ico',
  '/favicon-32x32.png',
  '/favicon-16x16.png',
  '/apple-touch-icon.png',
  '/android-chrome-192x192.png',
  '/android-chrome-512x512.png',
  '/images/logo-light.png',
  '/images/logo-dark.png'
];

// Max items for dynamic caches to prevent storage exhaustion
const MAX_RUNTIME_ITEMS = 75;
const MAX_IMAGE_ITEMS = 60;
const MAX_API_ITEMS = 50;

async function trimCache(cacheName, maxItems) {
  try {
    const cache = await caches.open(cacheName);
    const keys = await cache.keys();
    if (keys.length > maxItems) {
      await cache.delete(keys[0]);
      await trimCache(cacheName, maxItems);
    }
  } catch (e) {
    // Ignore trim errors
  }
}

// 1. INSTALL EVENT
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(STATIC_CACHE_NAME).then(async (cache) => {
      try {
        await cache.addAll(PRECACHE_ASSETS);
      } catch (err) {
        console.warn('[SW] Pre-cache partial failure:', err);
      }
      return self.skipWaiting();
    })
  );
});

// 2. ACTIVATE EVENT - Clean up old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          if (!key.startsWith(CACHE_VERSION)) {
            console.log('[SW] Removing old cache:', key);
            return caches.delete(key);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// 3. FETCH EVENT
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET requests and non-http(s) schemes (e.g. chrome-extension)
  if (request.method !== 'GET' || !request.url.startsWith('http')) {
    return;
  }

  // A. Handle API Requests (Network First, Cache Fallback)
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(
      fetch(request)
        .then(async (response) => {
          if (response && response.status === 200) {
            const responseClone = response.clone();
            const cache = await caches.open(API_CACHE_NAME);
            await cache.put(request, responseClone);
            trimCache(API_CACHE_NAME, MAX_API_ITEMS);
          }
          return response;
        })
        .catch(async () => {
          const cachedResponse = await caches.match(request);
          if (cachedResponse) {
            return cachedResponse;
          }
          return new Response(JSON.stringify({ offline: true, message: 'Currently offline. Data may be cached locally.' }), {
            status: 503,
            headers: { 'Content-Type': 'application/json' }
          });
        })
    );
    return;
  }

  // B. Handle Images (Cache First, Network Fallback)
  if (
    request.destination === 'image' ||
    url.pathname.match(/\.(png|jpg|jpeg|svg|webp|gif|ico)$/i)
  ) {
    event.respondWith(
      caches.match(request).then((cachedResponse) => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(request)
          .then(async (networkResponse) => {
            if (networkResponse && networkResponse.status === 200) {
              const responseClone = networkResponse.clone();
              const cache = await caches.open(IMAGE_CACHE_NAME);
              await cache.put(request, responseClone);
              trimCache(IMAGE_CACHE_NAME, MAX_IMAGE_ITEMS);
            }
            return networkResponse;
          })
          .catch(() => {
            // If image fails offline, fallback to cached logo if available
            return caches.match('/favicon.png');
          });
      })
    );
    return;
  }

  // C. Handle Static Assets (CSS, JS, Fonts) - Stale While Revalidate
  if (
    request.destination === 'style' ||
    request.destination === 'script' ||
    request.destination === 'font' ||
    url.pathname.startsWith('/build/') ||
    url.hostname.includes('fonts.googleapis.com') ||
    url.hostname.includes('fonts.gstatic.com')
  ) {
    event.respondWith(
      caches.match(request).then((cachedResponse) => {
        const fetchPromise = fetch(request)
          .then(async (networkResponse) => {
            if (networkResponse && networkResponse.status === 200) {
              const responseClone = networkResponse.clone();
              const cache = await caches.open(RUNTIME_CACHE_NAME);
              await cache.put(request, responseClone);
              trimCache(RUNTIME_CACHE_NAME, MAX_RUNTIME_ITEMS);
            }
            return networkResponse;
          })
          .catch(() => cachedResponse);

        return cachedResponse || fetchPromise;
      })
    );
    return;
  }

  // D. Handle Navigation / HTML Pages (Network First, Cache Fallback for SPA App Shell)
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then(async (networkResponse) => {
          if (networkResponse && networkResponse.status === 200) {
            const responseClone = networkResponse.clone();
            const cache = await caches.open(STATIC_CACHE_NAME);
            await cache.put(request, responseClone);
          }
          return networkResponse;
        })
        .catch(async () => {
          const cachedResponse = await caches.match(request);
          if (cachedResponse) {
            return cachedResponse;
          }
          // Fallback to cached root SPA shell
          return caches.match('/');
        })
    );
    return;
  }

  // E. Default Stale-While-Revalidate
  event.respondWith(
    caches.match(request).then((cachedResponse) => {
      return (
        cachedResponse ||
        fetch(request).then(async (networkResponse) => {
          if (networkResponse && networkResponse.status === 200) {
            const responseClone = networkResponse.clone();
            const cache = await caches.open(RUNTIME_CACHE_NAME);
            await cache.put(request, responseClone);
          }
          return networkResponse;
        })
      );
    })
  );
});

// 4. MESSAGE LISTENER (e.g. skipWaiting on update)
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
