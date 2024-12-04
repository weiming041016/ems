<?php

namespace App\Http\Controllers;

use App\Models\EmployeeLeave;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeLeavesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employee = auth()->user()->employee;  // Assuming the user has a related 'employee' model
        $gender = $employee ? $employee->employeeDetail->gender : null;

        return view('pages.employees-leave-request_create', compact('gender'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeLeave  $employeeLeave
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeLeave $employeeLeave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeLeave  $employeeLeave
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeLeave $employeeLeave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeLeave  $employeeLeave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeLeave $employeeLeave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeLeave  $employeeLeave
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeLeave $employeeLeave)
    {
        //
    }
}
