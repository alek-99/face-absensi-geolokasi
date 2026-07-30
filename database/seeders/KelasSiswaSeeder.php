<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class KelasSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan lokal Indonesia

        // Pastikan tabel gurus memiliki data terlebih dahulu
        // Jika belum ada data guru, kita ambil ID guru yang ada atau buat sampel ID 1-5
        $guruIds = DB::table('gurus')->pluck('id')->toArray();

        // Jika tabel guru kosong, berikan fallback ID 1
        if (empty($guruIds)) {
            $this->command->warn('Tabel gurus kosong! Pastikan GuruSeeder dijalankan terlebih dahulu. Menggunakan ID fallback 1.');
            $guruIds = [1];
        }

        // Daftar kelas yang akan dibuat
        $daftarKelas = [
            ['nama' => '10 IPA 1', 'ruangan' => 'R-101'],
            ['nama' => '10 IPA 2', 'ruangan' => 'R-102'],
            ['nama' => '10 IPS 1', 'ruangan' => 'R-103'],
            ['nama' => '11 IPA 1', 'ruangan' => 'R-201'],
            ['nama' => '11 IPS 1', 'ruangan' => 'R-202'],
        ];

        foreach ($daftarKelas as $dataKelas) {
            // 1. Buat Data Kelas
            $kelasId = DB::table('kelas')->insertGetId([
                'nama_kelas'   => $dataKelas['nama'],
                'wali_kelas'   => $faker->randomElement($guruIds),
                'jumlah_siswa' => '10', // Tipe data string sesuai skema
                'ruangan'      => $dataKelas['ruangan'],
                'catatan'      => $faker->sentence(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            // 2. Buat 10 Siswa untuk Kelas Ini
            for ($i = 1; $i <= 10; $i++) {
                $jenisKelamin = $faker->randomElement(['laki-laki', 'perempuan']);
                $genderFaker = ($jenisKelamin == 'laki-laki') ? 'male' : 'female';

                DB::table('siswas')->insert([
                    'nama'           => $faker->name($genderFaker),
                    'nisn'           => $faker->unique()->numerify('00######'),
                    'nik'            => $faker->nik(),
                    'nipd'           => $faker->unique()->numerify('2024###'),
                    'kelas_id'       => $kelasId,
                    'alamat'         => $faker->address(),
                    'no_hp'          => $faker->phoneNumber(),
                    'jenis_kelamin'  => $jenisKelamin,
                    'tempat_lahir'   => $faker->city(),
                    'tanggal_lahir'  => $faker->date('Y-m-d', '2008-12-31'),
                    'agama'          => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
                    'sekolah_asal'   => 'SMPN ' . $faker->numberBetween(1, 10) . ' ' . $faker->city(),
                    'nama_ayah'      => $faker->name('male'),
                    'nama_ibu'       => $faker->name('female'),
                    'pekerjaan_ayah' => $faker->jobTitle(),
                    'pekerjaan_ibu'  => $faker->jobTitle(),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }
    }
}