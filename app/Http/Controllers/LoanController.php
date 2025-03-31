<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Employee;
use Illuminate\Validation\Rule;


class LoanController extends Controller
{
    public function create()
    {
        return view('management.advance.advance-create'); 
    }

    public function store(Request $request)
{
    
    try {
        
    $validated = $request->validate([
        'employment_ID' => 'required|string|max:255',
        'loan_amount' => 'required|numeric|min:0.01',
        'interest_rate' => 'nullable|numeric|min:0.01|max:100',
        'loan_start_date' => 'required|date',
        'duration' => 'required|integer|min:1',
        'status' => 'required|string',
        'description' => 'nullable|string',
        'advance_documents' => 'nullable|array',
    ]);            
    logger('Validation passed');
    } catch (\Illuminate\Validation\ValidationException $e) {
        logger('Validation failed', $e->errors());
        dd($e->errors(),  $request->all());
    }
    $employee = Employee::where('employee_id', $request->employment_ID)->first();
    if (!$employee) {
        return back()->withErrors(['employment_ID' => 'Invalid Employee ID.'])->withInput();
    }

    $loan_start_date = \Carbon\Carbon::parse($request->loan_start_date);
    $loan_duration_months = (int)$request->duration; 
    $loan_end_date = $loan_start_date->copy()->addMonths($loan_duration_months);
    $loan_amount = $request->loan_amount; 
    $annual_interest_rate = $request->interest_rate; 
    $monthly_interest_rate = $annual_interest_rate / 100 / 12;

    if ($monthly_interest_rate > 0) {
        $monthly_payment = $loan_amount * $monthly_interest_rate * pow(1 + $monthly_interest_rate, $loan_duration_months) /
            (pow(1 + $monthly_interest_rate, $loan_duration_months) - 1);
    } else {
        // If interest rate is 0, simply divide the loan amount by the number of months
        $monthly_payment = $loan_amount / $loan_duration_months;
    }
    

    try {  
        $uploadedFiles = [];
        if ($request->hasFile('advance_documents')) {
            foreach ($request->file('advance_documents') as $file) {
                $filePath = $file->storeAs(
                    'advance-documents', 
                    time() . '_' . $file->getClientOriginalName(), 
                    'public' 
                );
                $uploadedFiles[] = $filePath;
            }
        }
        

    } catch (\Exception $e) {
        dd([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
    
    $advance = new Loan();
    $advance -> employee_id = $employee -> id;
    $advance -> employee_name = $employee -> full_name;
    $advance -> employment_ID = $validated['employment_ID'];
    $advance -> loan_amount = $validated['loan_amount'];
    $advance -> interest_rate = $validated['interest_rate'];
    $advance -> loan_start_date = $validated['loan_start_date'];
    $advance -> duration = $validated['duration'];
    $advance -> loan_end_date = $loan_end_date;
    $advance -> monthly_paid = $monthly_payment;
    $advance -> status = $validated['status'];
    $advance -> description = $validated['description'] ?? null;
    $advance->advance_documents = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;

    $advance->save();
        
    return redirect()->route('advance.management')->with('success', 'Advance added successfully with supporting documents.');
}

    public function edit($id)
    {
        $advance = Loan::with('employee')->findOrFail($id);
       // dd($advance);
        return view('management.advance.advance-edit', compact('advance'));
    }

    public function update(Request $request, $id)
    {
    $advance = Loan::findOrFail($id);
    $validated = $request->validate([
            'employment_ID' => 'required|string|max:255',
            'loan_amount' => 'required|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
            'loan_start_date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'description' => 'nullable|string',
            'advance_documents' => 'nullable|array',
        ]);
        $advance->update($validated);

        $employee = Employee::where('employee_id', $validated['employment_ID'])->first();
        if (!$employee) {
            return back()->withErrors(['employment_ID' => 'Invalid Employee ID.'])->withInput();
        }

        $loan_start_date = \Carbon\Carbon::parse($request->loan_start_date);
        $loan_duration_months = (int)$request->duration; 
        $loan_end_date = $loan_start_date->copy()->addMonths($loan_duration_months);
        $loan_amount = $request->loan_amount; 
        $annual_interest_rate = $request->interest_rate; 
        $monthly_interest_rate = $annual_interest_rate / 100 / 12;

        if ($monthly_interest_rate > 0) {
            $monthly_payment = $loan_amount * $monthly_interest_rate * pow(1 + $monthly_interest_rate, $loan_duration_months) /
                (pow(1 + $monthly_interest_rate, $loan_duration_months) - 1);
        } else {
            // If interest rate is 0, simply divide the loan amount by the number of months
            $monthly_payment = $loan_amount / $loan_duration_months;
        }

        $currentFiles = is_string($advance->advance_documents) 
        ? json_decode($advance->advance_documents, true) ?: [] 
        : (is_array($advance->advance_documents) ? $advance->advance_documents : []);
    
    $remainingFiles = is_string($request->input('existing_files')) 
        ? json_decode($request->input('existing_files', '[]'), true) ?: [] 
        : (is_array($request->input('existing_files')) ? $request->input('existing_files') : []);
    
    $newFiles = [];
    
    if ($request->hasFile('advance_documents')) {
        foreach ($request->file('advance_documents') as $file) {
            try {
                $filePath = $file->storeAs(
                    'advance-documents',
                    time() . '_' . $file->getClientOriginalName(),
                    'public'
                );
                $newFiles[] = $filePath;
            } catch (\Exception $e) {
                \Log::error('File upload error: ' . $e->getMessage());
                return back()->withErrors(['file_upload' => 'Error uploading file: ' . $file->getClientOriginalName()]);
            }
        }
    }
    
    $finalFiles = array_values(array_unique(array_merge($remainingFiles, $newFiles)));
    
        $advance->employment_ID = $request->employment_ID;
        $advance->loan_amount = $request->loan_amount;
        $advance->interest_rate = $request->interest_rate;
        $advance->loan_start_date = $request->loan_start_date;
        $advance->duration = $request->duration;
        $advance->loan_end_date  = $loan_end_date ;
        $advance->monthly_paid  = $monthly_payment ;
        $advance->status =  $request->status;
        $advance->description = $request->description;
        $advance->advance_documents = !empty($finalFiles) ? json_encode($finalFiles) : null;
        
        $advance->save();
        
        return redirect()->route('advance.management')->with('success', 'Leave updated successfully.');
    }
    public function destroy($id)
    {
        $advance = Loan::findOrFail($id);
        $advance->delete();

        return redirect()->route('advance.management')->with('success', 'Loan record deleted successfully.');
    }
}
