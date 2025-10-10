<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Update or create akan mencari data berdasarkan email yg sudah masuk ke table user
        //kalo emai sudah ada maka data akan di update sesuai dengan value yg baru
        //kalo email baru belum ada maka data baru akan di buat
        $user1 = User::where('email', 'admin@example.com')->first();
        $user2 = User::where('email', 'customer@example.com')->first();
        Profile::updateOrCreate(
            ['user_id' => $user1->id], // cek berdasarkan user_id (unik)
            [
                'namaLengkap' => 'Admin',
                'alamat' => 'Utama',
                
            ]
        );
        Profile::updateOrCreate(
            ['user_id' => $user2->id],
            [
                'namaLengkap' => 'Customer',
                'alamat' => 'Satu',
                
            ]
        );
    }
}
