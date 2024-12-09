@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'salary_form'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Add employee salary</h4>
        <hr>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
        <h5 class="text-center font-weight-bold mb-3">Add employee salary</h5>
        <form action="{{ route('admin.salarysubmit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                      <div class="form-group">
                        <label for="employee_id" class="form-label">Employee ID</label>
                        <select id="role_id" class="form-control"  name="employee_id" required>
                            <option value="">Choose...</option>
                            @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected': '' }}>
                            {{ $employee->name }}
                            </option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-12 col-lg-6">
                      <div class="form-group">
                        <label for="email">Basic Salary:</label>
                        <input type="number" class="form-control" id="basic_salary" name="basic_salary" value="{{ old('basic_salary') }}" placeholder="Enter basic salary" required>
                      </div>
                      @error('basic_salary')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                      <div class="form-group">
                        <label for="allowances" class="form-label">allowances</label>
                        <input type="number" class="form-control" name="allowances" value="{{ old('allowances') }}"  />
                      </div>
                      @error('allowances')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    </div>
                    <div class="col-sm-12 col-lg-6">
                      <div class="form-group">
                        <label for="overtime_pay">OverTime Pay:</label>
                        <input type="number" class="form-control" id="overtime_pay" name="overtime_pay" value="{{ old('overtime_pay') }}" placeholder="Enter overtime pay" required>
                      </div>
                      @error('overtime_pay')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group">
                             <button class="btn-primary px-5">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="checkInOrOutModal" tabindex="-1" role="dialog" aria-labelledby="checkInOrOutModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkInOrOutModalLabel">Check In / Out</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('attendances.store') }}" method="POST" class="d-inline-block">
        @csrf
        <div class="modal-body">
          <div class="form-check">
            <input type="hidden" name="sick" value="0">
            <input type="checkbox" class="form-check-input @error('sick') is-invalid @enderror" id="sick" name="sick" value="1"  {{ old('sick' ? 'checked' : '') }}>
            <label class="form-check-label" for="sick">
              Is Sick?
            </label>
          </div>
          <div class="form-group">
            <label for="message">Message:</label>
            <input type="text" name="message" id="message" value="{{ old('message') }}" placeholder="Enter message" class="form-control @error('message') is-invalid @enderror">
            @error('message')
              <div class="alert alert-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success mr-2 px-5">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection