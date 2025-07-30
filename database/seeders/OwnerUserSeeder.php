<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OwnerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
        'fullname'    => 'Amera Raya',
        'phonenumber' => '0997701042',
        'password'    => Hash::make('192837amera'),
        'role'        => 'owner',
    ]);
    }
}
