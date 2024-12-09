@extends('layouts.admin', ['accesses' => $accesses, 'active' => 'attendances'])

@section('_content')
<div class="container-fluid mt-2 px-4">
  <div class="row">
    <div class="col-12">
        <h4 class="font-weight-bold">Generate Salary form</h4>
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
              <th scope="col" class="table-dark">Employee Name</th>
              <th scope="col" class="table-dark">Monthly</th>
              <th scope="col" class="table-dark">attachment</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        {{-- {{ $attendances->links() }}   --}}
      </div>
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