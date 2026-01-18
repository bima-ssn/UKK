<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Alat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default users (updateOrCreate untuk prevent duplicate)
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $petugas = User::updateOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'name' => 'Petugas',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ]
        );

        $peminjam = User::updateOrCreate(
            ['email' => 'peminjam@example.com'],
            [
                'name' => 'Peminjam',
                'password' => Hash::make('password'),
                'role' => 'peminjam',
            ]
        );

        // Create categories
        $kategori1 = Kategori::firstOrCreate(
            ['nama_kategori' => 'Perkakas'],
            ['keterangan' => 'Berbagai macam perkakas untuk pekerjaan']
        );

        $kategori2 = Kategori::firstOrCreate(
            ['nama_kategori' => 'Elektronik'],
            ['keterangan' => 'Alat-alat elektronik']
        );

        $kategori3 = Kategori::firstOrCreate(
            ['nama_kategori' => 'Kendaraan'],
            ['keterangan' => 'Alat transportasi']
        );

        // Create tools (hanya jika belum ada)
        $tools = [
            ['kategori_id' => $kategori1->id, 'nama_alat' => 'Palu', 'stok' => 10, 'kondisi' => 'baik'],
            ['kategori_id' => $kategori1->id, 'nama_alat' => 'Obeng', 'stok' => 15, 'kondisi' => 'baik'],
            ['kategori_id' => $kategori1->id, 'nama_alat' => 'Tang', 'stok' => 8, 'kondisi' => 'baik'],
            ['kategori_id' => $kategori1->id, 'nama_alat' => 'Gergaji', 'stok' => 5, 'kondisi' => 'baik'],
            ['kategori_id' => $kategori2->id, 'nama_alat' => 'Multimeter', 'stok' => 6, 'kondisi' => 'baik'],
            ['kategori_id' => $kategori2->id, 'nama_alat' => 'Solder', 'stok' => 4, 'kondisi' => 'baik'],
            ['kategori_id' => $kategori2->id, 'nama_alat' => 'Oscilloscope', 'stok' => 2, 'kondisi' => 'baik'],
            ['kategori_id' => $kategori3->id, 'nama_alat' => 'Mobil', 'stok' => 2, 'kondisi' => 'baik'],
            ['kategori_id' => $kategori3->id, 'nama_alat' => 'Motor', 'stok' => 5, 'kondisi' => 'baik'],
        ];

        foreach ($tools as $tool) {
            Alat::firstOrCreate(
                ['nama_alat' => $tool['nama_alat']],
                $tool
            );
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Petugas: petugas@example.com / password');
        $this->command->info('Peminjam: peminjam@example.com / password');
    }
}
