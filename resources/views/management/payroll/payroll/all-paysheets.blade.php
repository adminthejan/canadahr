<!DOCTYPE html>
<html>
<head>
    <title>All Paysheets</title>
</head>
<body>
    @foreach ($records as $record)
        <h1>{{ $record->employee_name }}'s Paysheet</h1>
        <p>Month: {{ $record->payroll_month }}</p>
        <p>Basic Salary: {{ $record->basic }}</p>
        <p>Allowances: {{ $record->total_allowances }}</p>
        <p>Deductions: {{ $record->total_deductions }}</p>
        <p>Net Salary: {{ $record->net_salary }}</p>
        <hr>
    @endforeach
</body>
</html>
