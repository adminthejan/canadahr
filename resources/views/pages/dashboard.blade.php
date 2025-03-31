@extends('layouts.app')

@section('title', 'Login')

@section('content')
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded shadow-md mb-6">
            <p class="text-gray-700 text-lg">Welcome, <strong>{{ auth()->user()->name }}</strong>!</p>
            <p class="text-gray-600 mt-2">Your email: {{ auth()->user()->email }}</p>
        </div>

        <h2 class="text-2xl font-bold mb-4">Employee List</h2>
        <div class="bg-white p-6 rounded shadow-md">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">First Name</th>
                        <th class="border border-gray-300 px-4 py-2">Last Name</th>
                        <th class="border border-gray-300 px-4 py-2">Email</th>
                        <th class="border border-gray-300 px-4 py-2">Phone</th>
                        <th class="border border-gray-300 px-4 py-2">Position</th>
                        <th class="border border-gray-300 px-4 py-2">Department</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $employee->id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $employee->first_name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $employee->last_name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $employee->email }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $employee->phone }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $employee->position->title ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $employee->department->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="#" class="text-blue-500 hover:underline">Edit</a> |
                                <a href="#" class="text-red-500 hover:underline">Delete</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border border-gray-300 px-4 py-2 text-center">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

                <!-- Button to Employee Dashboard -->
                <div class="mb-6">
            <a href="{{ route('dashboard.section', 'employee') }}" class="bg-blue-500 text-white px-6 py-2 rounded shadow hover:bg-blue-600">
                Go to Employee Dashboard
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
        </form>
    </div>
</body>
@endsection
