<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan locale Indonesia agar data nama dan alamat terlihat lokal
        $faker = Faker::create('id_ID');

        // Beberapa kota populer di Indonesia untuk tempat lahir
        $kota = ['Jakarta', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan', 'Makassar', 'Malang', 'Bogor', 'Tangerang'];

        for ($i = 1; $i <= 20; $i++) {
            
            // 1. Tentukan Jenis Kelamin & Nama yang Sesuai
            $gender = $faker->randomElement(['Laki-laki', 'Perempuan']);
            $genderFaker = ($gender === 'Laki-laki') ? 'male' : 'female';
            $nama = $faker->name($genderFaker);

            // 2. Buat Email Unik Berdasarkan Nama Guru
            $cleanName = strtolower(str_replace(' ', '', $nama));
            $email = $cleanName . $i . '@gmail.com'; // Ditambah index $i agar dijamin unique

            // 3. Insert ke Tabel 'users' (Menggunakan DB facade agar kompatibel tanpa harus setting model)
            $userId = DB::table('users')->insertGetId([
                'name'              => $nama,
                'email'             => $email,
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'), // Password default untuk semua guru
                'role'              => 'guru',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // 4. Insert ke Tabel 'gurus'
            DB::table('gurus')->insert([
                'user_id'       => $userId,
                'foto_profil'   => null, // Di-set null sesuai definisi nullable
                'nip'           => '199' . $faker->numerify('######') . '202' . $faker->numerify('###'), // Format NIP tiruan
                'jenis_kelamin' => $gender,
                'nama'          => $nama,
                'tempat_lahir'  => $faker->randomElement($kota),
                'tanggal_lahir' => $faker->date('Y-m-d', '2000-01-01'), // Maksimal kelahiran tahun 2000
                'alamat'        => $faker->address,
                'no_hp'         => $faker->phoneNumber,
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}