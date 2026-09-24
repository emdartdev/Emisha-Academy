/**
 * Service Worker Registration & Lifecycle Management for Emisha Academy PWA
 */

import { useNetworkStore } from '../stores/network';
import { offlineSync } from './offlineSync';

export function registerPwa() {
  if (typeof window === 'undefined') return;

  const networkStore = useNetworkStore();

  // 1. Initialize Sync Engine
  offlineSync.init();

  // 2. Listen for PWA install prompt
  window.addEventListener('beforeinstallprompt', (e: any) => {
    e.preventDefault();
    networkStore.setDeferredPrompt(e);
  });

  window.addEventListener('appinstalled', () => {
    networkStore.clearInstallPrompt();
    networkStore.isInstalled = true;
    console.log('[PWA] Emisha Academy PWA successfully installed.');
  });

  // 3. Register Service Worker if supported
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker
        .register('/sw.js')
        .then((registration) => {
          console.log('[SW] Service Worker registered with scope:', registration.scope);

          // Check for waiting updates
          if (registration.waiting) {
            networkStore.setUpdateAvailable(registration.waiting);
          }

          registration.addEventListener('updatefound', () => {
            const installingWorker = registration.installing;
            if (installingWorker) {
              installingWorker.addEventListener('statechange', () => {
                if (installingWorker.state === 'installed' && navigator.serviceWorker.controller) {
                  networkStore.setUpdateAvailable(installingWorker);
                }
              });
            }
          });
        })
        .catch((error) => {
          console.warn('[SW] Service Worker registration failed:', error);
        });
    });

    let refreshing = false;
    navigator.serviceWorker.addEventListener('controllerchange', () => {
      if (!refreshing) {
        refreshing = true;
        window.location.reload();
      }
    });
  }
}
