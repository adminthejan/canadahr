<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\Education;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;



class EmployeeController extends Controller
{

    public function GetAllEmployees()
    {
        // Fetch all employees with their departments and paginate (10 per page)
        $employees = Employee::with(['department', 'position'])->paginate(8);
    
        return view('management.employee-management', compact('employees'));
    }
     // Display the employee management card view
     public function index()
     {
         $employees = Employee::with(['position', 'department'])->paginate(8); // Load relationships
         return view('management.employee.employee-management', compact('employees'));
     }
 
     // Display the detailed view for a specific employee
     public function show($id)
     {
       
         $employee = Employee::with([ 'department','education'])->findOrFail($id);
         //dd($employee->image);
         return view('management.employee.employee-details', compact('employee'));
     }

     public function edit($id){
        $employee = Employee::with(['department','education'])->findOrFail($id);
        $departments = Department::all();
        return view('management.employee.employee-edit', compact('employee', 'departments'));
     }

     public function update(Request $request, $id)
     {
        $employee = Employee::findOrFail($id);
        $education = Education::findOrFail($employee->education_id);

         $validated = $request->validate([
             'full_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
             'first_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
             'last_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
             'email' => 'required|email|regex:/^[\w\.-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,6}$/|unique:employees,email,' . $id, 
             'phone' => 'nullable|string|max:15',
             'address' => 'nullable|string',
             'date_of_birth' => 'nullable|date',
             'nic' => 'nullable|string',
             'gender' => 'nullable|string',
             'title' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
             'employment_type' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
             'image' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
             'employee_id' => 'nullable|string|max:255',
             'description' => 'nullable|string|max:255',
             'branch' => 'required|string',
             'name' => 'required|string',
             'probation_start_date' => 'nullable|date',
             'probation_period' => 'nullable|integer|min:1',
             'department_id' => 'nullable|exists:departments,id',
             'manager_id' => 'nullable|exists:employees,id',
             'education_id' => 'nullable|exists:education,id',
             'employment_start_date' => 'nullable|date',
             'employment_end_date' => 'nullable|date',
             'status' => 'nullable|string|max:255',
             'legal_documents' => 'nullable|array', 
             'account_holder_name' => 'required|string|regex:/^[a-zA-Z\s]+$/',
             'bank_name' => 'required|string|regex:/^[a-zA-Z\s]+$/',
             'account_no' => 'required|integer|min:1',
             'branch_name' => 'required|string',
     
             'degree' => 'nullable|string|max:255|',
             'institution' => 'nullable|string|max:255',
             'graduation_year' => 'nullable|integer',
             'work_experience_years' => 'nullable|integer',
             'work_experience_role' => 'nullable|string|max:255',
             'work_experience_company' => 'nullable|string|max:255',
             'course_name' => 'nullable|string|max:255',
             'training_provider' => 'nullable|string|max:255',
             'completion_date' => 'nullable|date',
             'certification_status' => 'nullable|string|max:255',
         ]);


            $imagePath = null;

            if ($request->hasFile('image')) {
                // Store the new image and get its path
                $imagePath = $request->file('image')->storeAs(
                    'images', time() . '_' . $request->file('image')->getClientOriginalName(), 'public'
                );
        
                // Delete the old image from storage if it exists
                if ($employee->image) {
                    Storage::disk('public')->delete($employee->image);
                }
            }

            $currentFiles = is_string($employee->legal_documents)
            ? json_decode($employee->legal_documents, true) ?: []
            : (is_array($employee->legal_documents) ? $employee->legal_documents : []);

        // Retrieve the remaining files from the request
        $remainingFiles = is_string($request->input('existing_files'))
            ? json_decode($request->input('existing_files', '[]'), true) ?: []
            : (is_array($request->input('existing_files')) ? $request->input('existing_files') : []);

       

        // Handle new file uploads
        $newFiles = [];
        if ($request->hasFile('legal_documents')) {
            foreach ($request->file('legal_documents') as $file) {
                try {
                    $filePath = $file->storeAs(
                        'legal-documents',
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
        
     
         

         if ($imagePath) {
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $employee->image = $imagePath;  
        }


     
         // Update the education record
         $education->degree = $validated['degree'];
         $education->institution = $validated['institution'];
         $education->graduation_year = $validated['graduation_year'];
         $education->work_experience_years = $validated['work_experience_years'];
         $education->work_experience_role = $validated['work_experience_role'];
         $education->work_experience_company = $validated['work_experience_company'];
         $education->course_name = $validated['course_name'];
         $education->training_provider = $validated['training_provider'];
         $education->completion_date = $validated['completion_date'];
         $education->certification_status = $validated['certification_status'] ?? null;
         $education->save();
     
         // Find the department
         $department = Department::where('name', $validated['name'])
             ->where('branch', $validated['branch'])
             ->first();
     
         if (!$department) {
             return redirect()->back()->withErrors(['department_id' => 'Invalid department details provided.']);
         }
     
         // Update the employee record
         $employee->full_name = $validated['full_name'];
         $employee->first_name = $validated['first_name'] ?? null;
         $employee->last_name = $validated['last_name'] ?? null;
         $employee->email = $validated['email'];
         $employee->phone = $validated['phone'] ?? null;
         $employee->address = $validated['address'] ?? null;
         $employee->date_of_birth = $validated['date_of_birth'] ?? null;
         $employee->age = $validated['age'] ?? null;
         $employee->nic = $validated['nic'] ?? null;
         $employee->gender = $validated['gender'] ?? null;
         $employee->title = $validated['title'] ?? null;
         $employee->employment_type = $validated['employment_type'] ?? null;
         $employee->employee_id = $validated['employee_id'] ?? null;
         $employee->description = $validated['description'] ?? null;
         $employee->probation_start_date = $validated['probation_start_date'] ?? null;
         $employee->probation_period = $validated['probation_period'] ?? null;
         $employee->department_id = $department->id;
         $employee->manager_id = $validated['manager_id'] ?? null;
         $employee->education_id = $education->id;
         $employee->employment_start_date = $validated['employment_start_date'] ?? null;
         $employee->employment_end_date = $validated['employment_end_date'] ?? null;
         $employee->status = $validated['status'] ?? null;
         $employee->account_holder_name = $validated['account_holder_name'] ;
         $employee->bank_name = $validated['bank_name'] ;
         $employee->account_no = $validated['account_no'] ;
         $employee->branch_name = $validated['branch_name'] ;
         $employee->image = $imagePath ?? $employee->image; 
         $employee->legal_documents = !empty($finalFiles) ? json_encode($finalFiles) : null;
     
         $employee->save();
     
         return redirect()->route('employee.show', $id)->with('success', 'Employee updated successfully!');
     }

     

    public function delete($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully']);
    }

    public function GetSearchEmployees(Request $request)
{
  
    // Retrieve the search query
    $search = $request->input('search');

    // Build the query with relationships
    $query = Employee::with(['department']);

    // If a search query exists, filter results
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('first_name', 'LIKE', "%{$search}%")
              ->orWhere('last_name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%")
              ->orWhereHas('department', function($q) use ($search) {
                  $q->where('name', 'LIKE', "%{$search}%");
              });
              
        });
    }

    // Paginate results
    $employees = $query->paginate(8);


        return view('management.employee.employee-management', compact('search', 'employees'));
}


    // Show the form to add a new employee
    public function create()
    {
        $isFirstEmployee = Employee::count()===0;
        // Fetch all departments and positions
        $departments = Department::all();
        $employees = Employee::all();
        $education = Education::all();
    
        // Pass them to the view
        return view('management.employee.create-employee', compact('departments', 'employees', 'education','isFirstEmployee'));
    }

    

    public function store(Request $request)
{ 
    $isFirstEmployee = Employee::count()===0;
    // Validate the form data
    $validated = $request->validate([
        'full_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
        'first_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
        'last_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
        'email' => 'required|email|regex:/^[\w\.-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,6}$/|unique:employees,email',
        'phone' => 'nullable|string|max:15',
        'address' => 'nullable|string',
        'date_of_birth' => 'nullable|date',
        'nic' => 'nullable|string',
        'gender' => 'nullable|string',
        'title' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
        'employment_type' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
        'image' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
        'employee_id' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:255',
        'branch' => 'required|string',
        'name' => 'required|string',
        'probation_start_date' => 'required|date',
        'probation_period' => 'nullable|integer',
        'department_id' => 'nullable|exists:departments,id',
        'manager_id' => $isFirstEmployee ? 'nullable' : 'nullable|exists:employees,id',
        'education_id' => 'nullable|exists:education,id',
        'employment_start_date' => 'nullable|date',
        'employment_end_date' => 'nullable|date',
        'status' => 'nullable|string|max:255',
        'legal_documents' => 'nullable|array', 
        'account_holder_name' => 'required|string|regex:/^[a-zA-Z\s]+$/',
        'bank_name' => 'required|string|regex:/^[a-zA-Z\s]+$/',
        'account_no' => 'required|integer',
        'branch_name' => 'required|string',

        'degree' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
        'institution' => 'nullable|string|max:255',
        'graduation_year' => 'nullable|integer',
        'work_experience_years' => 'nullable|integer|min:1|max:365',
        'work_experience_role' => 'nullable|string|max:255',
        'work_experience_company' => 'nullable|string|max:255',
        'course_name' => 'nullable|string|max:255',
        'training_provider' => 'nullable|string|max:255',
        'completion_date' => 'nullable|date',
        'certification_status' => 'nullable|string|max:255',

        
    ]);


    try {

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->storeAs(
                'images',  
                time() . '_' . $image->getClientOriginalName(),
                'public'
            );
        }

        $uploadedFiles = [];
        if ($request->hasFile('legal_documents')) {
            foreach ($request->file('legal_documents') as $file) {
                $filePath = $file->storeAs(
                    'legal-documents',
                    time() . '_' . $file->getClientOriginalName(),
                    'public'
                );
                $uploadedFiles[] = $filePath;
            }
        }
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
    

    // Create a new education record
    $education = new Education();
    $education->degree = $validated['degree'];
    $education->institution = $validated['institution'];
    $education->graduation_year = $validated['graduation_year'];
    $education->work_experience_years = $validated['work_experience_years'];
    $education->work_experience_role = $validated['work_experience_role'];
    $education->work_experience_company = $validated['work_experience_company'];
    $education->course_name = $validated['course_name'];
    $education->training_provider = $validated['training_provider'];
    $education->completion_date = $validated['completion_date'];
    $education->certification_status = $validated['certification_status'] ?? null;
    $education->save();


   $department = Department::where('name', $validated['name'])
        ->where('branch', $validated['branch'])
        ->first();

    if (!$department) {
        return redirect()->back()->withErrors(['department_id' => 'Invalid department details provided.']);
    }

    // Create a new employee record
    $employee = new Employee();
    $employee->full_name = $validated['full_name'];
    $employee->first_name = $validated['first_name'] ?? null;
    $employee->last_name = $validated['last_name'] ?? null;
    $employee->email = $validated['email'];
    $employee->phone = $validated['phone'] ?? null;
    $employee->address = $validated['address'] ?? null;
    $employee->date_of_birth = $validated['date_of_birth'] ?? null;
    if (!empty($validated['date_of_birth'])) {
        $dateOfBirth = Carbon::parse($validated['date_of_birth']); 
        $employee->age = $dateOfBirth->age; 
    } else {
        $employee->age = null; 
    }
    $employee->nic = $validated['nic'] ?? null;
    $employee->gender = $validated['gender'] ?? null;
    $employee->title = $validated['title'] ?? null;
    $employee->employment_type = $validated['employment_type'] ?? null;
    $employee->employee_id = $validated['employee_id'] ?? null;
    $employee->description = $validated['description'] ?? null;
    $employee->probation_start_date = $validated['probation_start_date'] ?? null;
    $employee->probation_period = $validated['probation_period'] ?? null;
    $employee->department_id = $department->id; 
    if ($isFirstEmployee) {
        $employee->manager_id = $employee->id; 
    } else {
        $employee->manager_id = $validated['manager_id'] ?? null; 
    }
    $employee->education_id = $education->id;
    $employee->employment_start_date = $validated['employment_start_date'] ?? null;
    $employee->employment_end_date = $validated['employment_end_date'] ?? null;
    $employee->status = $validated['status'] ?? null;
    $employee->account_holder_name = $validated['account_holder_name'] ;
    $employee->bank_name = $validated['bank_name'] ;
    $employee->account_no = $validated['account_no'] ;
    $employee->branch_name = $validated['branch_name'] ;
    $employee->image = $imagePath; 
    $employee->legal_documents = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;

    $employee->save();

    return redirect()->route('employee.management')->with('success', 'Employee added successfully.');
}



public function destroy($id)
{
    try {
        $employee = Employee::findOrFail($id);
        if ($employee->image) {
            Storage::disk('public')->delete($employee->image); // Delete the image
        }
        if ($employee->legal_documents) {
            $files = json_decode($employee->legal_documents, true);
            foreach ($files as $file) {
                Storage::disk('public')->delete($file); // Delete legal documents
            }
        }
        $employee->delete(); // Delete the employee record

        return redirect()->route('employee.management')->with('success', 'Employee deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('employee.management')->with('error', 'Failed to delete employee.');
    }
}



public function hierarchy()
    {
        // Fetch the highest-level managers (managers with no manager_id)
      // Fetch the CEO (only one manager with manager_id = null)
    $ceo = Employee::whereNull('manager_id')
        ->with([
            'subordinates' => function ($query) {
                $query->orderBy('manager_id')->with('subordinates');
            },
        ])
        ->first(); // Assuming there is exactly one CEO

    // Return the CEO and hierarchy to the view
    return view('management.employee.employee-hierarchy', compact('ceo'));

    
    }


    public function showHierarchy()
    {
        $hierarchyTree = Employee::getHierarchyTree();
        
        return view('employees.hierarchy', [
            'hierarchyTree' => json_encode($hierarchyTree)
        ]);
    }
}
