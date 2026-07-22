const CACHE_NAME = "absensi-v1";

const urlsToCache = [
    "/",
    "/offline"
];

self.addEventListener("install", event => {

    event.waitUntil(

        caches.open(CACHE_NAME)

        .then(cache => {

            return cache.addAll(urlsToCache);

        })

    );

});

self.addEventListener("fetch", event => {

    event.respondWith(

        caches.match(event.request)

        .then(response => {

            return response || fetch(event.request);

        })

    );

});

// PERBAIKAN: sebelumnya listener 'push' dan 'notificationclick' didaftarkan
// DUA KALI di file ini. Meskipun tidak fatal, ini bisa menyebabkan notifikasi
// tampil dobel di beberapa browser. Sekarang hanya didaftarkan sekali.
self.addEventListener('push', function (event) {
    if (!event.data) {
        return;
    }

    // Data seharusnya JSON dari backend Laravel. Tapi bisa juga teks polos
    // (misalnya saat testing pakai tombol "Push" di Chrome DevTools, atau
    // provider lain yang kirim raw text) -> jangan biarkan sw.js crash.
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

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});

// NOTE: Pendaftaran subscription harus dilakukan dari halaman (client) karena service worker
// file (public/sw.js) tidak diproses oleh Blade. Script pendaftaran dipasang di layout/guru.blade.php.
// Service Worker hanya menangani event push & notificationclick di sini.
//
// PENTING: saat melakukan subscribe di client (registration.pushManager.subscribe),
// ikut simpan contentEncoding-nya, contoh:
//   const contentEncoding = (PushManager.supportedContentEncodings || ['aes128gcm'])[0];
// lalu kirim bersama hasil subscription.toJSON() ke server, karena
// PushNotificationService di backend sekarang membaca field ini untuk
// menentukan cara enkripsi payload yang benar (aes128gcm, bukan aesgcm lagi).

// Fungsi pembantu untuk konversi VAPID key
// PERBAIKAN: `window` tidak tersedia di dalam Service Worker (worker thread,
// bukan document/window). Sebelumnya pakai window.atob() yang akan
// menyebabkan ReferenceError saat fungsi ini dipanggil. Diganti ke self.atob().
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = self.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}