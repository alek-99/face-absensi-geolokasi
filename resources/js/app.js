import './bootstrap';

//  UI & Utility
import $ from 'jquery';
import Alpine from 'alpinejs';

window.$ = window.jQuery = $;
window.Alpine = Alpine;

// fontawesome
import '@fortawesome/fontawesome-free/js/all.js';
import * as faceapi from 'face-api.js';

window.faceapi = faceapi;

// Service Worker Registration
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js');
    });
}

Alpine.start();