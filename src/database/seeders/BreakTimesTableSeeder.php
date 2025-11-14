<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BreakTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendances = DB::table('attendances')->get();

        foreach ($attendances as $attendance) {
            // 出勤日の中からランダムに1回休憩を入れる例
            $start = Carbon::parse($attendance->start)->addHours(3);
            $end = (clone $start)->addMinutes(60);

            DB::table('break_times')->insert([
                'attendance_id' => $attendance->id,
                'start' => $start,
                'end' => $end,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
