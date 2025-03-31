<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use Illuminate\Validation\Rule;
use App\Models\Employee;

class LeaveController extends Controller
{
    public function create()
{
    return view('management.leave.leave-create'); 
}

public function store(Request $request)
{  
    try {
        $validated = $request->validate([
           
            'employment_ID' => 'required|string|max:255',
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'approved_person' => 'required|string|max:255',
            'status' => ['required',Rule::in(['pending', 'approved', 'rejected']),],
            'description' => 'nullable|string',
            'supporting_documents' => 'nullable|array',
        ]);            
    logger('Validation passed');
    } catch (\Illuminate\Validation\ValidationException $e) {
        logger('Validation failed', $e->errors());
        return redirect()->route('leave.management')->with('error', 'Error adding record!'.$e->getMessage());
    }
    $employee = Employee::where('employee_id', $request->employment_ID)->first();
    if (!$employee) {
        return back()->withErrors(['employment_ID' => 'Invalid Employee ID.'])->withInput();
    }

    $start_date = \Carbon\Carbon::parse($request->start_date);
    $end_date = \Carbon\Carbon::parse($request->end_date);
    $duration = $end_date->diffInDays($start_date) + 1; 
    $avatarPath = null;

    try {  
        $uploadedFiles = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $filePath = $file->storeAs(
                    'leave-documents', 
                    time() . '_' . $file->getClientOriginalName(), 
                    'public' 
                );
                $uploadedFiles[] = $filePath;
            }
        }
        

    } catch (\Exception $e) {
        // Dump the error message and stack trace for debugging
        return redirect()->route('leave.management')->with('error', 'Error adding record!'.$e);

    }
    
    $leave = new Leave();
    $leave -> employee_id = $employee -> id;
    $leave -> employee_name = $employee -> full_name;
    $leave -> employment_ID = $validated['employment_ID'];
    $leave -> leave_type = $validated['leave_type'];
    $leave -> approved_person = $validated['approved_person'];
    $leave -> start_date = $validated['start_date'];
    $leave -> end_date = $validated['end_date'];
    $leave -> duration = $duration;
    $leave -> status = $validated['status'];
    $leave -> description = $validated['description'] ?? null;
    $leave->supporting_documents = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;

    $leave->save();
        
    return redirect()->route('leave.management')->with('success', 'Leave added successfully with supporting documents.');
}
    

     // Display details of a specific payroll record
    public function show($id)
    {
        $leave = Leave::with('employee')->findOrFail($id); // Load the related employee
        return view('management.leave.leave-details', compact('leave'));
    }

    public function edit($id)
    {
        $leave = Leave::with('employee')->findOrFail($id);
        return view('management.leave.leave-edit', compact('leave'));
    }
 
    public function update(Request $request, $id)
{
    $leave = Leave::findOrFail($id);
    $validated = $request->validate([
        
            'employment_ID' => 'required|string|max:255',
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'approved_person' => 'required|string|max:255',
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'description' => 'nullable|string',
            'supporting_documents' => 'nullable|array',
        ]);
     

        $employee = Employee::where('employee_id', $validated['employment_ID'])->first();
        if (!$employee) {
            return back()->withErrors(['employment_ID' => 'Invalid Employee ID.'])->withInput();
        }else{
           // $leave->update($validated);

        $start_date = \Carbon\Carbon::parse($validated['start_date']);
        $end_date = \Carbon\Carbon::parse($validated['end_date']);
        $duration = $end_date->diffInDays($start_date) + 1;

        $currentFiles = is_string($leave->supporting_documents) 
        ? json_decode($leave->supporting_documents, true) ?: [] 
        : (is_array($leave->supporting_documents) ? $leave->supporting_documents : []);
    
    $remainingFiles = is_string($request->input('existing_files')) 
        ? json_decode($request->input('existing_files', '[]'), true) ?: [] 
        : (is_array($request->input('existing_files')) ? $request->input('existing_files') : []);
    
    $newFiles = [];
    
    if ($request->hasFile('supporting_documents')) {
        foreach ($request->file('supporting_documents') as $file) {
            try {
                $filePath = $file->storeAs(
                    'leave-documents',
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
    


        $leave->employee_name = $employee->full_name;
        $leave->employee_id = $employee->id;
        $leave->employment_ID = $request->employment_ID;
        $leave->leave_type = $request->leave_type;
        $leave->approved_person = $request->approved_person;
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->duration = $duration;
        $leave->status = $request->status;
        $leave->description = $request->description ?? null;
        $leave->supporting_documents = !empty($finalFiles) ? json_encode(array_values(array_unique($finalFiles))) : null;
        
        $leave->save();
        return redirect()->route('leave.management')->with('success', 'Leave updated successfully.');

}
}

    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();

        return redirect()->route('leave.management')->with('success', 'Leave record deleted successfully.');
    }

    public function getLeaveFiles($leaveId)
{
    $leave = Leave::with('documents')->findOrFail($leaveId);
    return response()->json($leave->documents);
}

 
}
