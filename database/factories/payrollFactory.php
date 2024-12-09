<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Payroll;

use Illuminate\Database\Eloquent\Factories\Factory;

class payrollFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     * 
     */
    protected $model=Payroll::class;
    public function definition()
    {
        // return [
        //     "employee_id"=>function(){
        //         return Employee::factory()->create()->id;
        //     },
        //     "pay_date"=>$this->faker->date(),
        //     "basic_salary"=>$this->faker->numberBetween(1000, 10000),
        //     "allowances"=>$this->faker->numberBetween(200, 500),
        //     "deductions"=>function(){

        //     }
        //     "gross_salary"=>

            
        // ];
    }
}
