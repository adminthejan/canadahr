<?php
namespace App\Http\Controllers;

use App\Models\SalaryDetails;
use App\Models\Attendance;
use App\Models\Payroll;
use Illuminate\Http\Request;
use ZipArchive;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\BankDetailsExport;

class PayrollExportController extends Controller
{

    public function export(Request $request)
    {
        $selectedMonth = $request->query('selected_month');

        if (!$selectedMonth) {
            return back()->withErrors(['error' => 'Please select a month to export.']);
        }
        $payrolls = DB::table('employee_salary_details')
        ->join('bank_details', 'employee_salary_details.employee_id', '=', 'bank_details.employee_id')
        ->where('employee_salary_details.payed_month', '=', $selectedMonth)
        ->select(
            'bank_details.company_ref',
            'bank_details.account_holder_name as beneficiary_name',
            'bank_details.account_number',
            'bank_details.bank_code',
            'bank_details.branch_code',
            'employee_salary_details.net_salary'
        )
        ->get();

     
  // ✅ Export the payroll data to an Excel file
  return Excel::download(new BankDetailsExport($payrolls), 'bank_details.xlsx');
    }
    public function downloadPaysheets(Request $request)
    {

        $selectedMonth = $request->query('selected_month'); // Get the month from the query
        if (!$selectedMonth) {
            return back()->with('error', 'Please select a valid month.');
        }

        $employees = SalaryDetails::where('payed_month', $selectedMonth)->with('employee')->get();

        if ($employees->isEmpty()) {
            return back()->with('error', 'No records found for the selected month.');
        }

        $zipFileName = "employee_paysheets_{$selectedMonth}.zip";
        $zip = new ZipArchive;

        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($employees as $employee) {
                $fileName = "paysheet_{$employee->employee_id}.pdf";
                $pdf = Pdf::loadView('management.payroll.paysheet', ['record' => $employee]);
                $zip->addFromString($fileName, $pdf->output());
            }
            $zip->close();

            return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Failed to create zip file.');
    }

    public function generatePreviousMonth(Request $request) 
    {
        $selectedMonth = $request->query('selected_month');
        $previousMonth = date('Y-m', strtotime('-1 month', strtotime($selectedMonth)));
    
        $payrolls = SalaryDetails::where('payed_month', $previousMonth)->get();
    
        foreach ($payrolls as $payroll) {
            // Calculate new loan and advance balances after deductions
            $newLoanBalance = max(0, $payroll->loan_balance - $payroll->loan_payment);
            $newAdvanceBalance = max(0, $payroll->advance_balance - $payroll->advance_payment);
    
        // Get attendance records for OT calculation (from 5th of selected month to 5th of next month)
        $startDate = date('Y-m-05', strtotime($selectedMonth));
        $endDate = date('Y-m-05', strtotime('+1 month', strtotime($selectedMonth)));
          //  dd($startDate);
            $attendanceRecords = Attendance::where('employee_id', $payroll->employee_id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();
   // dd($attendanceRecords);
            // Calculate total OT hours and late_by hours
            $totalOTHours = $attendanceRecords->sum('overtime_seconds') / 3600;
            $totalLateByHours = $attendanceRecords->sum('late_by_seconds') / 3600;
    
            // Deduct late hours from OT hours
            $finalOTHours = max(0, $totalOTHours);
    
            // Calculate OT Payment
            $grossSalary = $payroll->basic + $payroll->budget_allowance;
            $otRate = 0.0041667327; // 0.41667327% as a decimal
            $otPayment = $finalOTHours * ($grossSalary * 1.5 * $otRate);
    
            // Calculate deductions and net salary
            //$noPayDeductions = ($payroll->no_pay ?? 0) * 1000;
            if($newLoanBalance >0){
                $totalDeductions = (
                    ($payroll->epf_8_percent ?? 0) +
                    ($payroll->loan_payment ?? 0) +
                    ($payroll->stamp_duty ?? 0)
                );
        

            }else{
                $totalDeductions = (
                    ($payroll->epf_8_percent ?? 0) +
                    ($payroll->epf_8_percent ?? 0) +
                    ($payroll->stamp_duty ?? 0)
                );
        

            }
         
            $totalEarnings = (
                $grossSalary +
                ($payroll->transport_allowance ?? 0) +
                ($payroll->attendance_allowance ?? 0) +
                ($payroll->phone_allowance ?? 0) +
                ($payroll->car_allowance ?? 0) +
                ($payroll->production_bonus ?? 0) +
                $otPayment // Adding OT payment to earnings
            );
    
            $netSalary = $totalEarnings - $totalDeductions;
           // dd($netSalary);
            // Create new salary record
            SalaryDetails::create([
                'employee_name' => $payroll->employee_name,
                'employee_id' => $payroll->employee_id,
                'known_name' => $payroll->known_name,
                'epf_no' => $payroll->epf_no,
                'basic' => $payroll->basic,
                'budget_allowance' => $payroll->budget_allowance,
                'gross_salary' => $payroll->gross_salary,
                'transport_allowance' => $payroll->transport_allowance,
                'attendance_allowance' => $payroll->attendance_allowance,
                'phone_allowance' => $payroll->phone_allowance,
                'production_bonus' => $payroll->production_bonus,
                'car_allowance' => $payroll->car_allowance,
                'loan_payment' => $payroll->loan_payment,
                'advance_payment' => 0,
                'ot_payment' => $otPayment,
                'total_earnings' => $totalEarnings,
                'epf_8_percent' => $payroll->epf_8_percent,
                'epf_12_percent' => $payroll->epf_12_percent,
                'etf_3_percent' => $payroll->etf_3_percent,
                'stamp_duty' => $payroll->stamp_duty,
                'no_pay' => $payroll->no_pay,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'loan_balance' => $newLoanBalance,
                'advance_balance' => $newAdvanceBalance,
                'pay_date' => now(),
                'payed_month' => $selectedMonth,
                'status' => $payroll->status,
            ]);
        }
    
        return redirect()->route('payroll.management')->with('success', 'Records generated for the selected month.');
    }
    

    public function exportSalarySpreadsheet(Request $request)
    {//dd($request->all());
        $selectedMonth = $request->query('selected_month'); // Get the month from the query
        if (!$selectedMonth) {
            return back()->with('error', 'Please select a valid month.');
        }
       // dd($selectedMonth);
        $employees = SalaryDetails::where('payed_month', $selectedMonth)->get();
      //  dd($employees);
        if ($employees->isEmpty()) {

            return back()->with('error', 'No records found for the selected month.');
        }

        $filePath = storage_path("app/public/employee_salaries_{$selectedMonth}.xlsx");
        $writer = SimpleExcelWriter::create($filePath);

        // Add column headings
        $writer->addHeader([
            'Employee ID', 'Employee Name', 'Known Name', 'EPF No', 'Basic Salary',
            'Budget Allowance', 'Gross Salary', 'Transport Allowance', 'Attendance Allowance',
            'Phone Allowance', 'Production Bonus', 'Car Allowance', 'Loan Payment', 'ot_payment',
            'Total Earnings', 'EPF (8%)', 'EPF (12%)', 'ETF (3%)', 'Advance Payment',
            'Stamp Duty', 'No Pay', 'Total Deductions', 'Net Salary', 'Loan Balance',
            'Pay Date', 'Paid Month'
        ]);

        // Add rows
        foreach ($employees as $record) {
            $writer->addRow([
                $record->employee_id,
                $record->employee_name,
                $record->known_name,
                $record->epf_no,
                $record->basic,
                $record->budget_allowance,
                $record->gross_salary,
                $record->transport_allowance,
                $record->attendance_allowance,
                $record->phone_allowance,
                $record->production_bonus,
                $record->car_allowance,
                $record->loan_payment,
                $record->ot_payment,
                $record->total_earnings,
                $record->epf_8_percent,
                $record->epf_12_percent,
                $record->etf_3_percent,
                $record->advance_payment,
                $record->stamp_duty,
                $record->no_pay,
                $record->total_deductions,
                $record->net_salary,
                $record->loan_balance,
                $record->pay_date,
                $record->payed_month,
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
