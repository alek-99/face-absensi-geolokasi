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

        // 1. Ambil semua ID guru yang tersedia di database
        $guruIds = DB::table('gurus')->pluck('id')->toArray();

        if (empty($guruIds)) {
            $this->command->warn('Data guru kosong! Silakan jalankan GuruSeeder terlebih dahulu.');
            return;
        }

        // Daftar mata pelajaran sekolah
        $mataPelajaranList = [
            'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 'Kimia', 'Biologi',
            'Sejarah', 'Geografi', 'Ekonomi', 'Sosiologi', 'Pendidikan Agama', 'PPKn',
            'Seni Budaya', 'Penjaskes', 'Teknologi Informasi (TIK)', 'Prakarya'
        ];

        // Daftar kelas
        $kelasList = [
            'X IPA 1', 'X IPA 2', 'X IPS 1', 'X IPS 2',
            'XI MIPA 1', 'XI MIPA 2', 'XI IIS 1', 'XI IIS 2',
            'XII MIPA 1', 'XII MIPA 2', 'XII IIS 1', 'XII IIS 2'
        ];

        // Daftar ruangan
        $ruanganList = [
            'R. Kelas 101', 'R. Kelas 102', 'R. Kelas 201', 'R. Kelas 202', 'R. Kelas 301',
            'Lab Fisika', 'Lab Biologi', 'Lab Komputer 1', 'Lab Komputer 2', 'R. Multimedia'
        ];

        // Hari yang tersedia sesuai enum di migration (tanpa Minggu)
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Jam pelajaran pilihan untuk variasi waktu masuk & pulang (format H:i:s)
        $pilihanJam = [
            ['masuk' => '07:00:00', 'pulang' => '09:30:00'],
            ['masuk' => '09:45:00', 'pulang' => '12:15:00'],
            ['masuk' => '13:00:00', 'pulang' => '15:30:00'],
        ];

        // 2. Loop setiap guru untuk dibuatkan 3 jadwal berbeda
        foreach ($guruIds as $guruId) {
            
            // Acak 3 hari unik dari daftar hari (agar hari tidak bentrok untuk guru yang sama)
            $hariTerpilih = $faker->randomElements($hariList, 3);
            
            // Acak 3 mata pelajaran unik untuk variasi mengajar
            $mapelTerpilih = $faker->randomElements($mataPelajaranList, 3);

            for ($i = 0; $i < 3; $i++) {
                $jam = $faker->randomElement($pilihanJam);

                DB::table('jadwals')->insert([
                    'guru_id'        => $guruId,
                    'hari'           => $hariTerpilih[$i],
                    'jam_masuk'      => $jam['masuk'],
                    'jam_pulang'     => $jam['pulang'],
                    'mata_pelajaran' => $mapelTerpilih[$i],
                    'kelas'          => $faker->randomElement($kelasList),
                    'ruangan'        => $faker->randomElement($ruanganList),
                    'is_active'      => true,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }
    }
}