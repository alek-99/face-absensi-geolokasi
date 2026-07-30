<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil data ID dari tabel relasi
        $guruIds  = DB::table('gurus')->pluck('id')->toArray();
        $mapelIds = DB::table('mapels')->pluck('id')->toArray();
        $kelasIds = DB::table('kelas')->pluck('id')->toArray();

        // Validasi ketersediaan data pendukung
        if (empty($guruIds) || empty($mapelIds) || empty($kelasIds)) {
            $this->command->warn('Seeder Jadwal gagal! Pastikan tabel gurus, mapels, dan kelas sudah memiliki data.');
            return;
        }

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Sesi jam pelajaran harian (Jam Masuk => Jam Pulang)
        $sesiJam = [
            ['masuk' => '07:30:00', 'pulang' => '09:00:00'],
            ['masuk' => '09:15:00', 'pulang' => '10:45:00'],
            ['masuk' => '11:00:00', 'pulang' => '12:30:00'],
            ['masuk' => '13:15:00', 'pulang' => '14:45:00'],
        ];

        // Generate jadwal untuk setiap kelas
        foreach ($kelasIds as $kelasId) {
            foreach ($hariList as $hari) {
                foreach ($sesiJam as $sesi) {
                    DB::table('jadwals')->insert([
                        'guru_id'    => $faker->randomElement($guruIds),
                        'hari'       => $hari,
                        'jam_masuk'  => $sesi['masuk'],
                        'jam_pulang' => $sesi['pulang'],
                        'mapel_id'   => $faker->randomElement($mapelIds),
                        'kelas_id'   => $kelasId,
                        'is_active'  => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}