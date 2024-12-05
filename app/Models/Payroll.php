<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'pay_date',
        'basic_salary',
        'allowances',
        'overtime_hours',
        'overtime_pay',
        'deductions',
        'gross_salary',
        'epf_employee',
        'epf_employer',
        'socso_employee',
        'socso_employer',
        'eis_employee',
        'eis_employer',
        'income_tax',
        'net_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
