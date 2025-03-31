<?php


namespace App\Exports;

use App\Models\EmployeeSalaryDetail;
use App\Models\SalaryDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeSalariesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return SalaryDetails::all(['employee_id', 'employee_name', 'basic', 'budget_allowance', 'gross_salary', 'transport_allowance', 'attendance_allowance', 'phone_allowance', 'production_bonus', 'car_allowance', 'loan_payment', 'total_earnings', 'epf_8_percent', 'epf_12_percent', 'etf_3_percent', 'advance_payment', 'total_deductions', 'net_salary']);
    }

    public function headings(): array
    {
        return ['Employee ID', 'Employee Name', 'Basic Salary', 'Budget Allowance', 'Gross Salary', 'Transport Allowance', 'Attendance Allowance', 'Phone Allowance', 'Production Bonus', 'Car Allowance', 'Loan Payment', 'Total Earnings', 'EPF (8%)', 'EPF (12%)', 'ETF (3%)', 'Advance Payment', 'Total Deductions', 'Net Salary'];
    }
}
