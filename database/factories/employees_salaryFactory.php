<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class employees_salaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "employee_id"=>function (){
                return Employee::factory()->create()->id();
            },
            "basic_salary"=>$this->faker->numberBetween(1000, 10000),
            "allowances"=>$this->faker->numberBetween(100,500),
        ];
    }
}
