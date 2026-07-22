import './bootstrap';
import * as faceapi from 'face-api.js';


import Alpine from 'alpinejs';
window.faceapi = faceapi;
window.Alpine = Alpine;

if ('serviceWorker' in navigator) {

    window.addEventListener('load', () => {

        navigator.serviceWorker.register('/sw.js');

    });

}
Alpine.start();
