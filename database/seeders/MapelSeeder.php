<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daftarMapel = [
            ['nama_mapel' => 'Matematika', 'catatan' => 'Mata pelajaran wajib'],
            ['nama_mapel' => 'Bahasa Indonesia', 'catatan' => 'Mata pelajaran wajib'],
            ['nama_mapel' => 'Bahasa Inggris', 'catatan' => 'Mata pelajaran wajib'],
            ['nama_mapel' => 'Fisika', 'catatan' => 'Mata pelajaran peminatan IPA'],
            ['nama_mapel' => 'Kimia', 'catatan' => 'Mata pelajaran peminatan IPA'],
            ['nama_mapel' => 'Biologi', 'catatan' => 'Mata pelajaran peminatan IPA'],
            ['nama_mapel' => 'Ekonomi', 'catatan' => 'Mata pelajaran peminatan IPS'],
            ['nama_mapel' => 'Sosiologi', 'catatan' => 'Mata pelajaran peminatan IPS'],
            ['nama_mapel' => 'Geografi', 'catatan' => 'Mata pelajaran peminatan IPS'],
            ['nama_mapel' => 'Pendidikan Agama Islam', 'catatan' => 'Mata pelajaran agama'],
            ['nama_mapel' => 'Pancasila & Kewarganegaraan', 'catatan' => 'Mata pelajaran wajib'],
            ['nama_mapel' => 'Pendidikan Jasmani', 'catatan' => 'Praktik & Teori'],
        ];

        foreach ($daftarMapel as $mapel) {
            DB::table('mapels')->insert([
                'nama_mapel' => $mapel['nama_mapel'],
                'catatan'    => $mapel['catatan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}