<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Incident;


class IncidentController extends Controller
{
    public function create()
    {
        // Fetch all employees to associate with the incident record
     //   $incidents = Employee::all();

        // Return the view to create a new incident record
       // return redirect('management/incident-management')->with('success', 'Registration successful! You are now logged in.');

        return view('management.incident.incident-create');
    }

    public function edit($id)
    {
        // Flash a success message to the session
      //  session()->flash('notification', 'Record added successfully!');
        // Find the incident record by ID
        $incident = Incident::findOrFail($id);
      //  dd($incident);


        // Retrieve the employee associated with this incident record
        $employee = Employee::findOrFail($incident->employee_id);

        // Return the edit view with both incident and employee data
        return view('management.incident.incident-edit', compact('incident', 'employee'));
    }

    public function store(Request $request)
    {
       //dd($request->all());
        // Validate input to ensure correct format
        $request->validate([
            'employee_id' => 'required',
            'incident_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'supporting_documents' => 'nullable|array',
            'incident_date' => 'required|date',
            'resolution_status' => 'nullable|string|max:255',
        ]);
   
        $uploadedFiles = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $filePath = $file->storeAs(
                    'incident-documents', 
                    time() . '_' . $file->getClientOriginalName(), 
                    'public' 
                );
                $uploadedFiles[] = $filePath;
            }
        }
//dd(json_encode($uploadedFiles));	
        try {
            $incident = new Incident();
            // Create the incident record
    /*         Incident::create([
                'employee_id' => $request->input('employee_id'),
                'incident_type' => $request->input('incident_type'),
                'description' => $request->input('description'),
                'supporting_documents' => !empty($uploadedFiles) ? json_encode($uploadedFiles) : null,
                'incident_date' => $request->input('incident_date'),
                'resolution_status' => $request->input('resolution_status'),
            ]);
 */     $employee = Employee::where('employee_id', $request['employee_id'])->first();
        // Handle file uploads if supporting_documents exist
      
            $incident -> employee_id = $employee->id;
            $incident -> incident_type = $request['incident_type'];
            $incident -> resolution_status = $request['resolution_status'];
            $incident -> incident_date = $request['incident_date'];
            $incident -> description = $request['description'] ?? null;

            $incident->supporting_documents = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;
        
            $incident->save();

            return redirect()->route('incident.management')->with('success', 'Incident added successfully!');
        } catch (\Exception $e) {
           // dd($e->getMessage());
            // Log the error and display a message
            \Log::error('Exception: ' . $e->getMessage());
            return redirect()->route('incident.management')->with('error', $e->getMessage().'Please check your data any try again!');
        }
    }public function update(Request $request, $id)
    {
      // dd($request->all());
        $incident = Incident::findOrFail($id);
    
        // Validate input to ensure correct format
        $request->validate([
            'incident_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'supporting_documents' => 'nullable|array',
            'incident_date' => 'required|date',
            'resolution_status' => 'nullable|string|max:255',
        ]);
    
        // Retrieve the existing supporting documents for the incident
        $currentFiles = is_string($incident->supporting_documents) 
        ? json_decode($incident->supporting_documents, true) ?: [] 
        : (is_array($incident->supporting_documents) ? $incident->supporting_documents : []);
    
    $remainingFiles = is_string($request->input('existing_files')) 
        ? json_decode($request->input('existing_files', '[]'), true) ?: [] 
        : (is_array($request->input('existing_files')) ? $request->input('existing_files') : []);
    
    $newFiles = [];
    
    if ($request->hasFile('supporting_documents')) {
        foreach ($request->file('supporting_documents') as $file) {
            try {
                $filePath = $file->storeAs(
                    'incident-documents',
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
    

    $employee = Employee::where('employee_id', $request['employee_id'])->first();
    // Handle file uploads if supporting_documents exist
  
    
        try {
            // Merge existing documents with newly uploaded ones
           // $allDocuments = array_merge($existingDocuments, $uploadedFiles);
    
            // Update the incident record
            $incident->update([
                'employee_id' => $employee->id,
                'incident_type' => $request->input('incident_type'),
                'description' => $request->input('description'),
                'supporting_documents' => !empty($finalFiles) ? json_encode($finalFiles) : null,
                'incident_date' => $request->input('incident_date'),
                'resolution_status' => $request->input('resolution_status'),
            ]);
    
            return redirect()->route('incident.management')->with('success', 'Incident updated successfully!');
        } catch (\Exception $e) {
            // Log the error and display a message
            \Log::error('Exception: ' . $e->getMessage());
            return redirect()->route('incident.management')->with('error', 'An error occurred while updating the incident record.');
        }
    }
    

    public function destroy($id)
{
    try {
        // Find the incident record by ID
        $incident = Incident::findOrFail($id);
//dd($incident);
        // Decode the JSON stored in `supporting_documents` to get file paths
        $supportingDocuments = json_decode($incident->supporting_documents, true);

        if (!empty($supportingDocuments)) {
            // Delete the files from storage
            foreach ($supportingDocuments as $filePath) {
                if (\Storage::disk('public')->exists($filePath)) {
                    \Storage::disk('public')->delete($filePath);
                }
            }
        }

        // Delete the incident record
        $incident->delete();

        return redirect()->route('incident.management')->with('success', 'Incident deleted successfully!');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->route('incident.management')->with('error', 'Incident not found.');
    } catch (\Exception $e) {
        // Log the error and display a generic message
        \Log::error('Exception: ' . $e->getMessage());
        return redirect()->route('incident.management')->with('error', 'An error occurred while deleting the incident.');
    }
}

}
