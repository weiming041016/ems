<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeLeaveRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\EmployeeLeave;
use App\Models\EmployeeLeaveRequest;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeLeaveRequestsController extends Controller
{
    private $employeeLeaveRequests;

    public function __construct()
    {
        $this->middleware('auth');  

        $this->employeeLeaveRequests = resolve(EmployeeLeaveRequest::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employeeLeaveRequests = $this->employeeLeaveRequests->paginate();

        return view('pages.employees-leave-request', compact('employeeLeaveRequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employee = auth()->user()->employee;  // Assuming the user has a related 'employee' model
        $gender = $employee ? $employee->employeeDetail->gender : null;  // Fetch gender from the employee model
        $leaves = EmployeeLeave::where('employee_id', $employee->id)
        ->get()
        ->map(function ($leave) {
            $leave->remaining_days = $leave->total_days - $leave->used_days;
            return $leave;
        });

        return view('pages.employees-leave-request_create', compact('gender','leaves'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeLeaveRequest $request)
    {
        $employeeLeave = EmployeeLeave::where('employee_id', $request->input('employee_id'))
            ->where('type_of_leave', $request->input('type_of_leave'))
            ->first();
    
        if (!$employeeLeave) {
            return back()->with('status', 'Leave type not found for the employee.');
        }
    
        $from = Carbon::parse($request->input('from'));
        $to = Carbon::parse($request->input('to'));
    
        Carbon::setWeekendDays([Carbon::SUNDAY, Carbon::SATURDAY]);
    
        // 如果 from 和 to 是同一天，假期天数设为 1；否则正常计算
        $daysRequested = $from->diffInWeekdays($to) + 1;
    
        // 检查是否有足够的假期
        if ($employeeLeave->total_days - $employeeLeave->used_days < $daysRequested) {
            return back()->with('status', 'Insufficient leave days available.');
        }
    
        // 更新 used_days
        $employeeLeave->increment('used_days', $daysRequested);
    
        // 创建请假请求
        $this->employeeLeaveRequests->create([
            'employee_id' => $request->input('employee_id'),
            'from' => $from,
            'to' => $to,
            'message' => $request->input('message'),
            'type_of_leave' => $request->input('type_of_leave'),
            'status' => 'WAITING_FOR_APPROVAL',
        ]);
    
        // 记录日志
        Log::create([
            'description' => auth()->user()->employee->name . " created a leave request from '{$from}' to '{$to}'"
        ]);
    
        return redirect()->route('employees-leave-request')->with('status', 'Successfully created an employee leave request.');
    }
    
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeLeaveRequest  $employeeLeaveRequest
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeLeaveRequest $employeeLeaveRequest)
    {
        $employeeLeaveRequest->load('employee', 'checkedBy');

        $employeeLeave = EmployeeLeave::where('employee_id', $employeeLeaveRequest->employee_id)->first();

        $from = Carbon::parse($employeeLeaveRequest->from);
        $to = Carbon::parse($employeeLeaveRequest->to);

        Carbon::setWeekendDays([Carbon::SUNDAY, Carbon::SATURDAY]);

        $diff = $from->diffInWeekdays($to)+1;

        return view('pages.employees-leave-request_show', compact('employeeLeaveRequest', 'employeeLeave', 'diff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeLeaveRequest  $employeeLeaveRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeLeaveRequest $employeeLeaveRequest)
    {
        return view('pages.employees-leave-request_edit', compact('employeeLeaveRequest'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeLeaveRequest  $employeeLeaveRequest
     * @return \Illuminate\Http\Response
     */
    public function update(StoreEmployeeLeaveRequest $request, EmployeeLeaveRequest $employeeLeaveRequest)
    {
        if($request->type == 'edit') {
            $this->employeeLeaveRequests->where('id', $employeeLeaveRequest->id)
                ->update([
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'message' => $request->input('message')
                ]);

            Log::create([
                'description' => auth()->user()->employee->name . " updated a leave request's detail"
            ]);

            return redirect()->route('employees-leave-request')->with('status', 'Successfully updated employee leave request.');
        } else if ($request->type == 'accept') {
            $employeeLeave = EmployeeLeave::where('employee_id', $employeeLeaveRequest->employee_id)->first();

            $from = Carbon::parse($employeeLeaveRequest->from);
            $to = Carbon::parse($employeeLeaveRequest->to);
    
            Carbon::setWeekendDays([Carbon::SUNDAY, Carbon::SATURDAY]);
    
            $diff = $from->diffInWeekdays($to)+1;

            $this->employeeLeaveRequests->where('id', $employeeLeaveRequest->id)
                ->update([
                'status' => 'APPROVED',
                'checked_by' => $request->input('checked_by'),
                'comment' => $request->input('comment')
                ]);

            $employeeLeave->update(['used_leaves' => $employeeLeave->used_leaves + $diff]);

            Log::create([
                'description' => auth()->user()->employee->name . " approved ". $employeeLeaveRequest->employee->name  ."'s leave request from '" . $employeeLeaveRequest->from . "' to '" . $employeeLeaveRequest->to . "'"
            ]);
        
            return redirect()->route('employees-leave-request')->with('status', 'Successfully accepted employee leave request.');
        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeLeaveRequest  $employeeLeaveRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeLeaveRequest $employeeLeaveRequest)
    {
        $this->employeeLeaveRequests->where('id', $employeeLeaveRequest->id)
            ->update([
                'status' => 'REJECTED',
                'checked_by' => auth()->user()->employee->id,
                'comment' => request()->input('comment')
            ]);

        Log::create([
            'description' => auth()->user()->employee->name . " rejected ". $employeeLeaveRequest->employee->name  ."'s leave request from '" . $employeeLeaveRequest->from . "' to '" . $employeeLeaveRequest->to . "'"
        ]);
        
        return redirect()->route('employees-leave-request')->with('status', 'Successfully rejected employee leave request.');   
    }

    public function print () 
    {
        $employeeLeaveRequests = EmployeeLeaveRequest::with('employee', 'checkedBy')->latest()->get();

        return view('pages.employees-leave-request_print', compact('employeeLeaveRequests'));
    }
}
