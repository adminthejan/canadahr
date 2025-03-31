<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseClaim;
use Illuminate\Support\Facades\Storage;
use App\Models\Employee;

class ExpenseClaimsController extends Controller
{
    // Show the form to add a new expense
    public function create()
    {
        return view('management.expenses.expenses-create');
    }

    // Store the new expense in the database

    public function store(Request $request)
    {
   
        try {
        // Validate the incoming request
        $request->validate([
            'employee_id' => 'required|string|max:255', // Ensure employee exists
            'expense_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'approved_by' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_status' => 'required|in:pending,approved,rejected',
            'supporting_documents' => 'nullable|array', // Ensure avatar is an array
        ]);


//dd($request->all());
        logger('Validation passed');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->route('expense.management')->with('error', 'A validation error occurred while adding the expense record.'.$e->getMessage());
    }
    $avatarPath = null;
    $employee = Employee::where('employee_id', $request->employee_id)->first();
    if (!$employee) {
        return back()->withErrors(['employment_ID' => 'Invalid Employee ID.'])->withInput();
    }
    try {
        
        $uploadedFiles = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $filePath = $file->storeAs(
                    'expense-documents', 
                    time() . '_' . $file->getClientOriginalName(), 
                    'public' 
                );
                $uploadedFiles[] = $filePath;
            }
        }
        
      //  dd(gettype($avatarPath), $avatarPath);

    } catch (\Exception $e) {
        // Dump the error message and stack trace for debugging
        return redirect()->route('expense.management')->with('error', 'An error occurred while adding the expense record.'.$e->getMessage());

    }
    
    try {
        
        $expense = new ExpenseClaim();

        $expense -> employee_id =   $employee->id;
        $expense -> category = $request['expense_type'];
        $expense -> description = $request['description'];
        $expense -> approved_by = $request['approved_by'];
        $expense -> amount = $request['amount'];
        $expense -> submitted_date = $request['expense_date'];

        $expense -> status = $request['expense_status'];


        $expense->supporting_documents = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;
       // dd($expense);

        $expense->save();

        return redirect()->route('expense.management')->with('success', 'Expense added successfully!');
    } catch (\Exception $e) {
        // Log the error and display a message
        \Log::error('Exception: ' . $e->getMessage());
        return redirect()->route('expense.management')->with('error', 'An error occurred while adding the expense record.'.$e->getMessage());
    }

  
           }
    



     // Display details of a specific ExpenseClaim record
     public function show($id)
     {
         $expense = ExpenseClaim::findOrFail($id);
         return view('management.expenses.expenses-details', compact('expense'));
     }
 
     public function edit($id)
     {
         $expense = ExpenseClaim::findOrFail($id);

         $employee = Employee::findOrFail($expense->employee_id);
        
         return view('management.expenses.expenses-edit', compact('expense', 'employee'));
     }
 
     public function update(Request $request, $id)
     {  
        //dd($request->all());
         $expense = ExpenseClaim::findOrFail($id);
    
        // Validate input to ensure correct format
        try {
            // Validate the incoming request
            $request->validate([
                'employee_id' => 'required|exists:employees,id', // Ensure employee exists
                'expense_type' => 'required|string|max:255',
                'description' => 'nullable|string',
                'expense_date' => 'required|date',
                'approved_by' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'expense_status' => 'required|in:pending,approved,rejected',
                'supporting_documents' => 'nullable|array', // Ensure avatar is an array
            ]);
    
    
    //dd($request->all());
            logger('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            logger('Validation failed', $e->errors());
            dd($e->errors(),  $request->all());
        }


        $employee = Employee::where('employee_id', $request->employee_id)->first();
        if (!$employee) {
            return back()->withErrors(['employment_ID' => 'Invalid Employee ID.'])->withInput();
        }
        // Retrieve the existing supporting documents for the incident
        $currentFiles = is_string($expense->supporting_documents) 
        ? json_decode($expense->supporting_documents, true) ?: [] 
        : (is_array($expense->supporting_documents) ? $expense->supporting_documents : []);
    
    $remainingFiles = is_string($request->input('existing_files')) 
        ? json_decode($request->input('existing_files', '[]'), true) ?: [] 
        : (is_array($request->input('existing_files')) ? $request->input('existing_files') : []);
    
    $newFiles = [];
    
    if ($request->hasFile('supporting_documents')) {
        foreach ($request->file('supporting_documents') as $file) {
            try {
                $filePath = $file->storeAs(
                    'expense-documents',
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
    


    
        try {
            // Merge existing documents with newly uploaded ones
           // $allDocuments = array_merge($existingDocuments, $uploadedFiles);
    
            // Update the incident record
            $expense->update([
                'employee_id' => $employee->id,
                'category' => $request->input('expense_type'),
                'supporting_documents' => !empty($finalFiles) ? json_encode($finalFiles) : null,
                'description' => $request->input('description'),
                'approved_by' => $request->input('approved_by'),
                'amount' => $request->input('amount'),
                'status' => $request->input('expense_status'),
            ]);
    

            return redirect()->route('expense.management')->with('success', 'Expense updated successfully!');
        } catch (\Exception $e) {
            // Log the error and display a message
            \Log::error('Exception: ' . $e->getMessage());
            return redirect()->route('expense.management')->with('error', 'An error occurred while updating the expense record.');
        }
    }
 
     public function destroy($id)
     {
         try {
            // Find the incident record by ID
            $expense = ExpenseClaim::findOrFail($id);

 
            // Decode the JSON stored in `supporting_documents` to get file paths
            $supportingDocuments = json_decode($expense->supporting_documents, true);
    
            if (!empty($supportingDocuments)) {
                // Delete the files from storage
                foreach ($supportingDocuments as $filePath) {
                    if (\Storage::disk('public')->exists($filePath)) {
                        \Storage::disk('public')->delete($filePath);
                    }
                }
            }
    
            // Delete the incident record
            $expense->delete();

    
            return redirect()->route('expense.management')->with('success', 'Expense deleted successfully!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('expense.management')->with('error', 'Expense not found.');
        } catch (\Exception $e) {
            // Log the error and display a generic message
            \Log::error('Exception: ' . $e->getMessage());
            return redirect()->route('expense.management')->with('error', 'An error occurred while deleting the Expense record.');
        }
    }
}
