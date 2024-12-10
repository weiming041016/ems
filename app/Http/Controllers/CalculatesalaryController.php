<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeesalary;
use App\Models\Employee;
use App\Models\employees_salary;
use App\Models\Payroll;
use App\Models\StatutoryDeduction;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class CalculatesalaryController extends Controller
{
    
    private $employees;
    private $totalday;
    private $currect_month;

    public function __construct()
    {
        $this->middleware('auth');
        $this->employees=resolve(Employee::class);
        $this->totalday=26;
        $this->currect_month=date("m");
    }

    public function index(){
        $employees=employees_salary::latest()->paginate(10);

        // dd($employees);
        return view("pages.employee_salary",compact("employees"));
    }
    public function salarysubmitform()
    {
        $employees=$this->employees->all();
        return view("pages.employee_salary_create",compact("employees"));
    }

    public function salarysubmit(StoreEmployeesalary $request){
        employees_salary::create($request->all());
        return redirect()->route("admin.salarydata")->with('status', 'Successfully add salary detail.');
    }
    

    public function calculatesalary(){
        $employeesalary=employees_salary::all();
        foreach($employeesalary as $salary){
            $basic_salary=$salary->basic_salary;
            $allowance=$salary->allowances;
            $overtime_pay=$salary->overtime_pay;
            $is_state_2=$salary->employee->employeeDetail->age<=60 ? 0:1; 
            // dd($is_state_2);
            $EPF=StatutoryDeduction::where('type', "EPF")
            ->where("salary_threshold",">=",$basic_salary)
            ->where("is_stage_2",$is_state_2)
            ->first();
           
            $EPFemployee_deduction=$basic_salary*($EPF->employee_rate/100);
            $EPFemployer_deduction=$basic_salary*($EPF->employer_rate/100);

            $SOCSO=StatutoryDeduction::where('type', "SOCSO")
            ->where("is_stage_2",$is_state_2)
            ->first();
            
            $SOCSOemployee_deduction = $SOCSO ? $basic_salary*($SOCSO->employee_rate/100) : 0;
            $SOCSOemployer_deduction = $SOCSO ? $basic_salary*($SOCSO->employer_rate/100) : 0;

            $EIS=StatutoryDeduction::where('type', "EIS")->first();

            $EIS_employee_deduction=$basic_salary*($EIS->employee_rate/100);
            $EIS_employer_deduction=$basic_salary*($EIS->employer_rate/100);

            $gross_salary = $basic_salary + $allowance + $overtime_pay;

            $total_statutory_deductions = $EPFemployee_deduction + $SOCSOemployee_deduction;

            $net_salary = $gross_salary -  $total_statutory_deductions;
            
            $payrolls=new Payroll();
            $payrolls->employee_id=$salary->employee_id;
            $payrolls->pay_date=date("Y-m-d");
            $payrolls->basic_salary=$basic_salary;
            $payrolls->allowances=$allowance;
            $payrolls->overtime_hours=0;
            $payrolls->overtime_pay=$overtime_pay;
            $payrolls->deductions=$total_statutory_deductions;
            $payrolls->gross_salary=$gross_salary;
            $payrolls->epf_employee=$EPFemployee_deduction;
            $payrolls->epf_employer=$EPFemployer_deduction;
            $payrolls->socso_employee=$SOCSOemployee_deduction;
            $payrolls->socso_employer=$SOCSOemployer_deduction;
            $payrolls->eis_employee=$EIS_employee_deduction;
            $payrolls->eis_employer=$EIS_employer_deduction;
            $payrolls->income_tax=0;
            $payrolls->net_salary=$net_salary;
            $payrolls->save();
        }
        // return redirect()->route("admin.salarydata")->with('status', 'Successfully calculate salary.');
        return response("successfully");
    }
    
}