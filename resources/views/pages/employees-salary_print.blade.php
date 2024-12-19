@extends('layouts.print')
@section('_content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12 text-center">
            <h4>COMPANY LOGO & NAME</h4>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <p><strong>Name:</strong> {{ $employee->name }}</p>
            <p><strong>Position:</strong> {{ $employee->position->name }}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Date:</strong> {{ now()->format('d M Y') }}</p>
            <p><strong>Period:</strong> {{ $payslip->period }}</p>
            <p><strong>Department:</strong> {{ $employee->department->name }}</p>
            <p><strong>EPF No.:</strong> {{ $employee->epf_no }}</p>
            <p><strong>SOCSO No.:</strong> {{ $employee->socso_no }}</p>
            <p><strong>Income Tax No.:</strong> {{ $employee->income_tax_no }}</p>
        </div>
    </div>

    <div class="row mt-4">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th colspan="2">Income</th>
                    <th colspan="2">Deduction</th>
                    <th colspan="2">Contribution</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>BASIC PAY</td>
                    <td>{{ number_format($payslip->basic_salary, 2) }}</td>
                    <td>Employee EPF</td>
                    <td>{{ number_format($payslip->epf_employee, 2) }}</td>
                    <td>Employer EPF</td>
                    <td>{{ number_format($payslip->epf_employer, 2) }}</td>
                </tr>
                <tr>
                    <td>Allowance</td>
                    <td>{{ number_format($payslip->allowances, 2) }}</td>
                    <td>Employee SOCSO</td>
                    <td>{{ number_format($payslip->socso_employee, 2) }}</td>
                    <td>Employer SOCSO</td>
                    <td>{{ number_format($payslip->socso_employer, 2) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Income Tax</td>
                    <td>{{ number_format($payslip->income_tax, 2) }}</td>
                    <td>Employer EIS</td>
                    <td>{{ number_format($payslip->eis_employer, 2) }}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{{ number_format(($payslip->basic_salary+$payslip->allowances), 2) }}</td>
                    <td>Total</td>
                    <td>{{ number_format($payslip->deductions, 2) }}</td>
                    <td>Total</td>
                    <td>{{ number_format(($payslip->epf_employer+$payslip->socso_employer+$payslip->eis_employer), 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row mt-4">
        <div class="col-md-12 text-right">
            <h5><strong>Nett Amount (RM):</strong> {{ number_format($payslip->net_salary, 2) }}</h5>
        </div>
    </div>
</div>
@endsection

@section('_script')
    <script>
      window.onload = function () {
        window.print();
      }
    </script>
@endsection