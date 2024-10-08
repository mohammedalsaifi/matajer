<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'mohammed alsaifi',
            'email' => 'mohammed@saifi.ps',
            'password' => Hash::make('password'),
            'phone_number' => '0595719719',
        ]);

        DB::table('users')->insert([
            'name' => 'ahmed alsaifi',
            'email' => 'ahmed@saifi.ps',
            'password' => Hash::make('password'),
            'phone_number' => '0598641710',
        ]);
    }
}
