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
              <th scope="col" class="table-dark">#</th>
              <th scope="col" class="table-dark">Name</th>
              <th scope="col" class="table-dark">Basic salary</th>
              <th scope="col" class="table-dark">allowances</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($employees as $employee)
            <tr>
              <th scope="row">{{ $loop->iteration + $employees->firstItem() - 1 }}</th>
              {{-- <td><a href="{{ route('employees-data.show', ['employee' => $employee->id]) }}">{{ $employee->name }}</td> --}}
            
              <td><a href="{{ route('salary-data.salarydetail',$employee->employee->id) }}">{{ $employee->employee->name }}</a></td>
              <td>{{ $employee->basic_salary }}</td>
              <td>{{ $employee->allowances }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        {{ $employees->links() }}  
      </div>
    </div>
  </div>
</div>
@endsection