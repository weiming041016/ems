<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutoryDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',            // 扣除类型: EPF, SOCSO, EIS, PCB
        'salary_threshold', // 工资区间阈值
        'is_stage_2',      // 是否为 Stage 2
        'employee_rate',   // 员工比例
        'employer_rate',   // 雇主比例
    ];

    /**
     * Scope to filter by deduction type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by stage (below or above 60 years old).
     */
    public function scopeStage($query, bool $isStage2)
    {
        return $query->where('is_stage_2', $isStage2);
    }
}
