const CACHE_NAME = "absensi-v1";

const urlsToCache = [
    "/",
    "/offline"
];

self.addEventListener("install", event => {
    // Biarkan Service Worker langsung aktif tanpa menunggu halaman di-reload
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME)
        .then(cache => {
            return cache.addAll(urlsToCache);
        })
    );
});

self.addEventListener("activate", event => {
    // Ambil alih kontrol halaman langsung
    event.waitUntil(self.clients.claim());
});

self.addEventListener("fetch", event => {
    // SANGAT PENTING: Jangan cache request POST atau API! 
    // Biarkan request POST (seperti save subscription) langsung lolos ke jaringan/server.
    if (event.request.method !== 'GET' || event.request.url.includes('/push-subscription/')) {
        return; // Kembalikan ke penanganan standar browser (network direct)
    }

    event.respondWith(
        caches.match(event.request)
        .then(response => {
            return response || fetch(event.request);
        })
    );
});

// EVENT PUSH
self.addEventListener('push', function (event) {
    if (!event.data) return;

    let data;
    try {
        data = event.data.json();
    } catch (e) {
        data = {
            title: 'Notifikasi',
            body: event.data.text(),
        };
    }

    const options = {
        body: data.body || '',
        icon: data.icon || '/icons/icon-192x192.png',
        badge: '/icons/icon-96x96.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/'
        }
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'Notifikasi', options)
    );
});

// EVENT CLICK NOTIFIKASI
self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});