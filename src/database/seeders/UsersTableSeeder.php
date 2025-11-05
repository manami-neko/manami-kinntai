<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            [
                'name' => '管理者',
                'email' => 'admin@example.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'email_verified_at' => now(),

            ],
            [
                'id' => 1,
                'name' => 'テスト太郎',
                'email' => 'test@12345.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'email_verified_at' => now(),

            ],
            [
                'id' => 2,
                'name' => 'テスト花子',
                'email' => 'test@6789.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'email_verified_at' => now(),

            ],
        ];
        DB::table('users')->insert($param);
    }
}
