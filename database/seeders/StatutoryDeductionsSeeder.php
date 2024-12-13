<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatutoryDeductionsSeeder extends Seeder
{
    public function run()
    {
        // EPF 数据
        DB::table('statutory_deductions')->insert([
            [
                'type' => 'EPF',
                'salary_threshold' => 5000.00, // RM5,000 及以下
                'is_stage_2' => false,
                'employee_rate' => 11.00,
                'employer_rate' => 13.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'EPF',
                'salary_threshold' => null, // 超过 RM5,000
                'is_stage_2' => false,
                'employee_rate' => 11.00,
                'employer_rate' => 12.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'EPF',
                'salary_threshold' => null,
                'is_stage_2' => true, // Stage 2（60 岁及以上）
                'employee_rate' => 0.00,
                'employer_rate' => 4.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // SOCSO 数据
        DB::table('statutory_deductions')->insert([
            [
                'type' => 'SOCSO',
                'salary_threshold' => 5000.00,
                'is_stage_2' => false,
                'employee_rate' => 0.50,
                'employer_rate' => 1.75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'SOCSO',
                'salary_threshold' => null,
                'is_stage_2' => true, // Stage 2（60 岁及以上）
                'employee_rate' => 0.00,
                'employer_rate' => 1.25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // EIS 数据
        DB::table('statutory_deductions')->insert([
            [
                'type' => 'EIS',
                'salary_threshold' => null, // 没有工资限制
                'is_stage_2' => false,
                'employee_rate' => 0.20,
                'employer_rate' => 0.40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // PCB 数据
        DB::table('statutory_deductions')->insert([
            [
                'type' => 'PCB',
                'salary_threshold' => null, // PCB 没有特定的工资范围限制
                'is_stage_2' => false,
                'employee_rate' => 5.00, // 示例税率（实际需要根据 PCB 指引调整）
                'employer_rate' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
