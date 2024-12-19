@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'data'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Employees' Salary detail</h4>
        <hr>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12 mb-3">
      <div class="bg-light text-dark card p-3 overflow-auto">
        <div class="d-flex justify-content-between">
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <table class="table table-light table-striped table-hover table-bordered text-center">
          <thead>
            <tr>
              
              <th scope="col" class="table-dark">Name</th>
              <th scope="col" class="table-dark">salary paydate</th>
              <th scope="col" class="table-dark">Payslip</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->employee->name }}</td>
                    <td>{{ $payroll->pay_date }}</td>
                    <td><a class="btn btn-primary" href="{{ route('salary-data.payslip',$payroll->id ) }}">View</a></td>
                </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection