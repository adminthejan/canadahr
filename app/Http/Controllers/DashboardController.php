<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function show($section)
    {
        $data = [];

        if ($section == 'employee') {
            $data = [
                (object)['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
                (object)['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ];
        } elseif ($section == 'payroll') {
            // Add payroll data
        } elseif ($section == 'leave') {
            // Add leave data
        }

        return view('management.employee-management', compact('section', 'data'));
    }


// Add Todo management methods
public function storeTodo(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'due_date' => 'required|date',
        'priority' => 'required|in:low,medium,high'
    ]);

    Todo::create([
        'user_id' => auth()->id(),
        'title' => $validated['title'],
        'due_date' => $validated['due_date'],
        'priority' => $validated['priority'],
        'status' => 'pending'
    ]);

    return response()->json(['success' => true]);
}

public function getEmployeeCountByDepartment()
{
    try {
        // Using Query Builder with joins to get all department fields
        $departmentsWithCount = DB::table('departments')
            ->leftJoin('employees', 'departments.id', '=', 'employees.department_id')
            ->select(
                'departments.*', // This will select all fields from departments table
                DB::raw('COUNT(employees.id) as employee_count')
            )
            ->groupBy('departments.id', 
                     'departments.name', 
                     'departments.description',
                     
                     // Add all other department columns here that you're grouping by
                     'departments.created_at',
                     'departments.updated_at'
                     // Add any other department columns you have
            )
            ->get();

        // Alternative using Eloquent if you prefer
        /*
        $departmentsWithCount = Department::select('departments.*')
            ->leftJoin('employees', 'departments.id', '=', 'employees.department_id')
            ->selectRaw('COUNT(employees.id) as employee_count')
            ->groupBy('departments.id', 
                     'departments.name',
                     'departments.created_at',
                     'departments.updated_at'
                     // Add any other department columns you have
            )
            ->get();
        */

        return response()->json([
            'status' => 'success',
            'data' => $departmentsWithCount,
            'message' => 'Departments with employee count retrieved successfully'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error retrieving departments with count: ' . $e->getMessage()
        ], 500);
    }
}

public function updateTodoStatus(Request $request, Todo $todo)
{
    $todo->update(['status' => $request->status]);
    return response()->json(['success' => true]);
}
}
