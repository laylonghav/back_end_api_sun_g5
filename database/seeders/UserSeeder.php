<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                "name" => "laylonghav",
                "email" => "laylonghav@gmail.com",
                "password" => Hash::make("laylonghav123"),
                "role" => "admin"
            ],
            [
                "name" => "John",
                "email" => "john@gmail.com",
                "password" => Hash::make("john123"),
                "role" => "user"
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
