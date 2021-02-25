<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
            'name' => 'pbuser',
            'email' => 'demo.progressbar.tw@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => new \DateTime(),
            'is_admin' => true
        ]);
    }
}
