@extends('layouts.dashboard-layout')

@section('title', 'Department Details')

@section('content')
<!-- Main Content -->

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="flex flex-col items-start justify-start w-full px-16">
    <nav class="flex px-5 py-3" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center text-3xl font-medium text-[#00000080] hover:text-blue-600">
                    Department
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <p class="text-[#00000080] text-3xl"><i class="ri-arrow-right-wide-line"></i></p>
                    <a href="#" class="ml-1 font-medium text-[#00000080] text-3xl hover:text-blue-600">Department Management</a>
                </div>
            </li>
        </ol>
    </nav>
    
    <div class="w-full flex space-x-16 pt-8">
        <!-- Department Info Card -->
        <div class="w-full flex flex-col space-y-8 p-8 bg-[#D9D9D980] rounded-3xl cursor-pointer nunito focus:outline-none focus:ring-2 focus:ring-[#52B69A] focus:border-[#184E77] shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out">
            <!-- Department Title -->
            <div class="w-full flex pl-8">
                <p class="text-3xl font-bold text-black">
                    Department Information For 
                    <span class="text-[#184E77]">{{ $departments->first()->first()->name }}</span> 
                    ({{ $departments->first()->first()->department_id }})
                </p>
            </div>

            <div class="w-full flex">
                <div class="w-3/4 space-y-8 pl-16 text-black font-bold">   
                    @if($departments->isNotEmpty())
                        @foreach ($departments as $departmentGroup)
                            <!-- Only display branch information once -->
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-3xl font-semibold text-black">
                                    <span class="text-3xl font-bold text-gray-700">Branch:</span> {{ $departmentGroup->first()->branch }}
                                </p>
                                <form method="POST" action="{{ route('department.branch.delete', ['branch' => $departmentGroup->first()->branch, 'department_id' => $departmentGroup->first()->department_id]) }}" onsubmit="return confirmBranchDelete()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center space-x-4 px-4 py-2 border border-[#D9D9D980] bg-gray-100 text-red-600 font-medium rounded-xl hover:bg-red-50 focus:outline-none">
                                        <p><i class="ri-delete-bin-5-line"></i></p>
                                        <p class="text-xl font-bold">Delete Branch</p>
                                    </button>
                                </form>
                            </div>

                            <h4 class="text-2xl font-bold text-black mb-4">
                                Employees in this Branch:
                            </h4>

                            <!-- List of Employees for this Branch -->
                            <ul class="space-y-2 text-xl">
                                @foreach ($departmentGroup as $employee)
                                    <li class="flex justify-between py-2 px-4 hover:bg-gray-100 rounded-md transition-all duration-200 ease-in-out">
                                        <span>{{ $employee->full_name }}</span>
                                        <span class="text-gray-600">{{ $employee->email }}</span>
                                    </li>
                                @endforeach
                            </ul>

                        @endforeach
                    @else
                        <p class="text-xl text-gray-500">No department found with the given ID.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmBranchDelete() {
        return confirm('Are you sure you want to delete this branch? Deleting this branch will require removing all employees associated with it. Proceed?');
    }
</script>
@endsection
