<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [];

        // 3か月分 (例: 2025年10月～12月)
        $startDate = Carbon::create(2025, 10, 1);
        $endDate = Carbon::create(2025, 12, 31);

        // user_id = 1〜6 のユーザーに勤怠を作成
        for ($userId = 1; $userId <= 6; $userId++) {

            // Carbonは参照型なので、毎回copy()を使う
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // 土日はスキップ（勤務なしにしたい場合）
                if ($date->isSaturday() || $date->isSunday()) {
                    continue;
                }

                $records[] = [
                    'user_id' => $userId,
                    'day' => $date->format('Y-m-d'),
                    'start' => $date->copy()->setTime(9, 0, 0),   // 09:00
                    'end'   => $date->copy()->setTime(18, 0, 0),  // 18:00

                    'status' => 'finished', // 全て勤務完了済みとして登録
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        // 一括挿入
        DB::table('attendances')->insert($records);
    }
}
