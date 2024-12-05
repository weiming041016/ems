<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('pay_date'); // 发薪日期
            $table->decimal('basic_salary', 10, 2); // 基本工资
            $table->decimal('allowances', 10, 2)->default(0); // 津贴
            $table->decimal('overtime_hours', 5, 2)->default(0); // 加班时间（小时）
            $table->decimal('overtime_pay', 10, 2)->default(0); // 加班工资
            $table->decimal('deductions', 10, 2)->default(0); // 其他扣除
            $table->decimal('gross_salary', 10, 2); // 总收入
            $table->decimal('epf_employee', 10, 2); // 员工的 EPF
            $table->decimal('epf_employer', 10, 2); // 雇主的 EPF
            $table->decimal('socso_employee', 10, 2); // 员工的 SOCSO
            $table->decimal('socso_employer', 10, 2); // 雇主的 SOCSO
            $table->decimal('eis_employee', 10, 2); // 员工的 EIS
            $table->decimal('eis_employer', 10, 2); // 雇主的 EIS
            $table->decimal('income_tax', 10, 2)->default(0); // 预扣税
            $table->decimal('net_salary', 10, 2); // 净薪资
            $table->timestamps();
        });

        // 法定扣除利率表
        Schema::create('statutory_deductions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 类型: EPF, SOCSO, EIS, PCB
            $table->decimal('salary_threshold', 10, 2)->nullable(); // 工资区间阈值
            $table->boolean('is_stage_2')->default(false); // 是否为 Stage 2
            $table->decimal('employee_rate', 5, 2); // 员工比例
            $table->decimal('employer_rate', 5, 2); // 雇主比例
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('statutory_deductions');
    }
}
