<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    
    public function create()
    {
        return view('management.department.department-create');
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'department_id' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'branch' => 'nullable|string|max:255',
            ]);

            logger('Validation passed');

        } catch (ValidationException $e) {  // Simplified exception
            logger('Validation failed', $e->errors());
            dd($e->errors(),  $request->all());
        }

        // Check if the department_id already exists
        $existingDepartments = Department::where('department_id', $request->department_id)->get();

        if ($existingDepartments->isNotEmpty()) {
            // Loop through each department with the same department_id
            foreach ($existingDepartments as $existingDepartment) {
                // If department_id and name match
                if ($existingDepartment->name == $request->name) {
                    // Check if the branch is the same
                    if ($existingDepartment->branch == $request->branch) {
                        return response()->json([
                            'errors' => [
                                'department_id' => 'The department ID already exists with the same name and branch.'
                            ]
                        ], 422);
                    }
                }
            }
        }
      
        // If department does not exist, create a new department
        $department = new Department();
        $department->department_id = $validated['department_id'];
        $department->name = $validated['name'];
        $department->branch = $validated['branch'];
        
        $department->save();
        
        return redirect()
            ->route('department.management')
            ->with('success', 'Department added successfully');
    }      
    public function show($department_id) {
        // Fetch the department and employees with branch details
        $departments = DB::table('departments')
            ->leftJoin('employees', 'departments.id', '=', 'employees.department_id')
            ->select(
                'departments.department_id',
                'employees.department_id',
                'departments.name',
                'departments.branch',
                'employees.full_name',
                'employees.email',
            )
            ->where('departments.department_id', $department_id)
            ->get()
            ->groupBy('branch'); 
 
            if ($departments->isEmpty()) {
                return redirect()->back()->with('error', 'No department found with the given ID.');
            }
        
        
            return view('management.department.department-details', compact('departments'));
    }
    
    
  
    public function deleteBranch($branch, $department_id){
        try {
            // Delete all employees in this branch of this department
            $department = Department::findOrFail($department_id);
            $department->delete();
            
            return redirect()->back()->with('success', 'Branch deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete branch');
        }
    }

    
}
