<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\employees_salary;
use App\Models\StatutoryDeduction;
use Illuminate\Auth\Events\Validated;
use App\Http\Requests\StoreEmployeesalary;

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
        return view("pages.employee_salary",compact("employees"));
    }
    public function salarysubmitform()
    {
        $employees=$this->employees->all();
        return view("pages.employee_salary_create",compact("employees"));
    }
    

    public function show(Employee $employee){
        $payrolls=$employee->employeePayroll()->get();
        
        return view("pages.employee_salary_detail",compact("payrolls"));
    }

    public function showpayslip(Payroll $payslip){
        $employee=Employee::findOrFail($payslip->employee_id); 
        $payslip->pay_date=Carbon::parse($payslip->pay_date);
        return view("pages.employee_data_payslip",compact("employee","payslip"));
    }
    

    public function calculatesalary(){
        $employeesalary=employees_salary::all();
        foreach($employeesalary as $salary){
            $basic_salary=$salary->basic_salary;
            $allowance=$salary->allowances;
            $age=Carbon::parse($salary->employee->employeeDetail->date_of_birth)->age;
            $is_state_2=$age<=60 ? 0:1; 
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

            $gross_salary = $basic_salary + $allowance;

            $total_statutory_deductions = $EPFemployee_deduction + $SOCSOemployee_deduction;

            $net_salary = $gross_salary -  $total_statutory_deductions;
            
            $payrolls=new Payroll();
            $payrolls->employee_id=$salary->employee_id;
            $payrolls->pay_date=date("Y-m-d");
            $payrolls->basic_salary=$basic_salary;
            $payrolls->allowances=$allowance;
            $payrolls->overtime_hours=0;
            $payrolls->overtime_pay=0;
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

    public function print(Payroll $payslip)
    {
        // dd($payslip);
        $employee=Employee::findOrFail($payslip->employee_id);
        $payslip->pay_date=Carbon::parse($payslip->pay_date);
        return view("pages.employees-salary_print",compact("employee","payslip"));
    }
    
}
