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
                'id' => 1,
                'name' => '管理者',
                'email' => 'admin@example.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'email_verified_at' => now(),

            ],
            [
                'id' => 2,
                'name' => '西 怜奈',
                'email' => 'reina.n@coachtech.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'email_verified_at' => now(),

            ],
            [
                'id' => 3,
                'name' => '山田 太郎',
                'email' => 'taro.y@coachtech.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'email_verified_at' => now(),

            ],
            [
                'id' => 4,
                'name' => '増田 一世',
                'email' => 'issei.m@coachtech.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'email_verified_at' => now(),

            ],
            [
                'id' => 5,
                'name' => '山本 敬吉',
                'email' => 'keikichi.y@coachtech.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'email_verified_at' => now(),

            ],
            [
                'id' => 6,
                'name' => '秋田 朋美',
                'email' => 'tomomi.a@coachtech.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'email_verified_at' => now(),

            ],
            [
                'id' => 7,
                'name' => '中西 教夫',
                'email' => 'norio.n@coachtech.com',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'email_verified_at' => now(),

            ],
        ];
        DB::table('users')->insert($param);
    }
}
