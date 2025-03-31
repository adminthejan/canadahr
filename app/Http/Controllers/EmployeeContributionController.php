<?php

namespace App\Http\Controllers;

use App\Models\EmployeeContribution;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\SalaryDetails;
use Illuminate\Http\Request;

class EmployeeContributionController extends Controller
{
    // List all contributions
    public function index()
    {
        $contributions = EmployeeContribution::with('employee')->get();

           // Loop through contributions to calculate and update totals
    foreach ($contributions as $contribution) {
        // Fetch related payroll records for the employee
        $payrolls = Payroll::where('employee_id', $contribution->employee_id)->get();

        // Calculate total EPF (Employee - 8%) and ETF (Employer - 3%)
        $totalEPF = $payrolls->sum(function ($payroll) {
            return $payroll->basic_salary * 0.08; // 8% of basic salary
        });

        $totalETF = $payrolls->sum(function ($payroll) {
            return $payroll->basic_salary * 0.03; // 3% of basic salary
        });

        // Update the contribution record in the database
        $contribution->total_epf_contributed = $totalEPF;
        $contribution->total_etf_contributed = $totalETF;
        $contribution->save();
    }
        return view('management.employee_contributions.contribution-management', compact('contributions'));
    }

    // Show form to create or edit contributions
    public function createOrEdit($id)
    {
        $employee = Employee::findOrFail($id);
        $contribution = EmployeeContribution::firstOrNew(['employee_id' => $id]);

        return view('management.employee_contributions.form', compact('employee', 'contribution'));
    }

    public function create()
    {

        return view('management.employee_contributions.form');
    }
    public function edit($id)
    {
        $contribution = EmployeeContribution::with('employee')->findOrFail($id);
        return response()->json($contribution);
    }


    public function getContributions($employeeId)
    {
        $contributions = EmployeeContribution::where('employee_id', $employeeId)->get();
        return response()->json($contributions);
    }
   public function store(Request $request)
    {
        $request->validate([
           'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'epf_number' => 'nullable|string|max:255|unique:employee_contributions,epf_number',
            'etf_number' => 'nullable|string|max:255|unique:employee_contributions,etf_number',
        ]);

        // Create or update the record in the database
        EmployeeContribution::updateOrCreate(
            ['employee_id' => $request->employee_id],
            $request->only(
                'basic_salary',
                'epf_number',
                'etf_number',
                'total_epf_contributed',
            )
        );

        return redirect()->back()->with('success', 'Employee contribution saved successfully.');
    }
    // Store or update contribution
    public function storeOrUpdate(Request $request, $id)
    {
        $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'epf_number' => 'nullable|string|max:255|unique:employee_contributions,epf_number',
            'etf_number' => 'nullable|string|max:255|unique:employee_contributions,etf_number',
            'total_epf_contributed' => 'nullable|numeric|min:0',
            'total_etf_contributed' => 'nullable|numeric|min:0',
        ]);

        $contribution = EmployeeContribution::updateOrCreate(
            ['employee_id' => $id],
            $request->only('basic_salary', 'epf_number', 'etf_number', 'total_epf_contributed', 'total_etf_contributed')
        );

        return redirect()->route('employee_contributions.index')->with('success', 'Employee contribution updated successfully.');
    }

    public function destroy($id)
{
    try {
        // Find the record by ID
        $contribution = EmployeeContribution::findOrFail($id);

        // Delete the record
        $contribution->delete();

        // Return success response
        return redirect()->route('employee_contributions.index')->with('success', 'Contribution record deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to delete contribution record: ' . $e->getMessage());
    }
}

public function getEmployeeDetails($id)
{
    // Find the employee
    $employee = Employee::find($id);
    
    if (!$employee) {
        return response()->json(['error' => 'Employee not found'], 404);
    }

    // Find or create employee contribution record
    $contribution = SalaryDetails::firstOrNew(['employee_id' => $id]);

    // Return employee data in JSON format
    return response()->json([
        'basic_salary' => $employee->basic_salary, // Assuming Employee has basic_salary
        'epf_number' => $contribution->epf_number,
    ]);
}


}
