<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
| Tambahkan blok ini ke routes/channels.php yang sudah ada di project kamu
| (jangan timpa file yang sudah ada, cukup gabungkan).
*/

Broadcast::channel('admin-notifikasi', function ($user) {
    // Sesuaikan dengan mekanisme role admin di aplikasi kamu.
    // Contoh kalau ada kolom `role` di tabel users:
    return $user->role === 'admin';

    // Contoh kalau pakai package spatie/laravel-permission:
    // return $user->hasRole('admin');
});