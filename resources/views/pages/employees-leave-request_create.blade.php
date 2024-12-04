@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'leave-request'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Employees' Leave Requests</h4>
        <hr>
    </div>
  </div>
  
  <div class="row">
    <div class="col-12">
        <h5 class="text-center font-weight-bold mb-3">Create A New Employee Leave Request</h5>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        {{-- <p>Available Leave : {{employee->leave}}</p> --}}
              <div class="row">
                  <div class="col-sm-12 col-lg-6">
                      <div class="form-group">
                          <label for="available_leave">Available Leave:</label>
                          <ul>
                            @foreach($leaves as $leave)
                            <li>{{ $leave->type_of_leave }} : {{ $leave->total_days }}</li>
                            @endforeach
                          </ul>
                      </div>
                  </div>
              </div>
        <form action="{{ route('employees-leave-request.store') }}" method="POST">
          @csrf
          <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
          <div class="mb-3">
            <div class="row">
              <div class="col-sm-12 col-lg-6">
                <div class="form-group">
                  <label for="from">From:</label>
                  <input type="date" name="from" id="from" class="form-control @error('from') is-invalid @enderror" value="{{ old('from') }}" placeholder="Enter start of contract date" required>
                </div>
                @error('from')
                  <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-sm-12 col-lg-6">
                <div class="form-group">
                  <label for="to">To:</label>
                  <input type="date" name="to" id="to" class="form-control @error('to') is-invalid @enderror" value="{{ old('to') }}" placeholder="Enter end of contract date" required>
                </div>
                @error('to')
                  <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12 col-lg-6">
                  <div class="form-group">
                      <label for="type_of_leave">Type of Leave:</label>
                      <select id="type_of_leave" class="form-control @error('type_of_leave') is-invalid @enderror" name="type_of_leave" required>
                          <option value="">Choose...</option>
                          <option value="annual" {{ old('type_of_leave') == 'annual' ? 'selected' : '' }}>Annual</option>
                          <option value="mc" {{ old('type_of_leave') == 'mc' ? 'selected' : '' }}>MC</option>
                          <option value="hospitalization" {{ old('type_of_leave') == 'hospitalization' ? 'selected' : '' }}>Hospitalization</option>
                          @if ($gender == 'F')
                            <option value="maternity" {{ old('type_of_leave') == 'maternity' ? 'selected' : '' }}>Maternity</option>
                          @elseif ($gender == 'M')
                            <option value="paternity" {{ old('type_of_leave') == 'paternity' ? 'selected' : '' }}>Paternity</option>
                          @endif
                          <option value="unpaid" {{ old('type_of_leave') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                      </select>
                  </div>
                  @error('type_of_leave')
                      <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
              </div>

              <div class="col-sm-12 col-lg-6">
                <div class="form-group">
                  <label for="attach">Attach:</label>
                  <input type="file" name="attach" id="attach" class="form-control-file @error('attach') is-invalid @enderror" required>
                </div>
                @error('attach')
                  <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
          </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="message">Message:</label>
                  <input type="text" name="message" id="message" class="form-control @error('message') is-invalid @enderror" value="{{ old('message') }}" placeholder="Enter message" required>
                </div>
                @error('message')
                  <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12 col-lg-6">
              <div class="form-group">
                <button type="submit" class="btn btn-primary px-5">Save</button>
              </div>
            </div>
          </div>
        </form>
    </div>
  </div>
</div>
@endsection