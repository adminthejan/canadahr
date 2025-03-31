@extends('layouts.dashboard-layout')

@section('title', 'HR Dashboard')

@section('content')
<!-- Include necessary CSS/JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Total Employees -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Total Employees</p>
                <p class="text-2xl font-bold">{{ $totalEmployees }}</p>
            </div>
        </div>
    </div>

    <!-- Total Departments -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500">
                <i class="fas fa-building text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Departments</p>
                <p class="text-2xl font-bold">{{ $totalDepartments }}</p>
            </div>
        </div>
    </div>

    <!-- New Employees -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">New Employees (This Month)</p>
                <p class="text-2xl font-bold">{{ $newEmployees }}</p>
            </div>
        </div>
    </div>

    <!-- Pending Incidents -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-500">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Pending Incidents</p>
                <p class="text-2xl font-bold">{{ $totalIncidents }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Headcount Trend -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Employee Headcount Trend</h3>
        <canvas id="headcountChart"></canvas>
    </div>

    <!-- Department Distribution -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Department Distribution</h3>
        <canvas id="departmentChart"></canvas>
    </div>

</div>


    <!-- Add this after your existing charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Salary Distribution -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Salary Distribution</h3>
        <canvas id="salaryChart"></canvas>
    </div>

    <!-- Attendance Overview -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">30-Day Attendance Overview</h3>
        <canvas id="attendanceChart"></canvas>
    </div>

    <!-- Employee Turnover -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Employee Turnover</h3>
        <canvas id="turnoverChart"></canvas>
    </div>
</div>

<!-- Todo List Section -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">My Todo List</h3>
        <button onclick="openTodoModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
            Add Todo
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($todos as $todo)
        <div class="border rounded-lg p-4 {{ $todo->status === 'completed' ? 'bg-gray-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-semibold {{ $todo->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                        {{ $todo->title }}
                    </h4>
                    <p class="text-sm text-gray-500">Due: {{ $todo->due_date->format('M d, Y') }}</p>
                    <span class="inline-block px-2 py-1 text-xs rounded-full mt-2
                        {{ $todo->priority === 'high' ? 'bg-red-100 text-red-800' : 
                           ($todo->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                        {{ ucfirst($todo->priority) }}
                    </span>
                </div>
                <input type="checkbox" 
                       {{ $todo->status === 'completed' ? 'checked' : '' }}
                       onchange="updateTodoStatus({{ $todo->id }}, this.checked)"
                       class="ml-4">
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Todo Modal -->
<div id="todoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Add New Todo</h3>
        <form id="todoForm" onsubmit="submitTodo(event)">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Title</label>
                <input type="text" name="title" required class="w-full border rounded-lg p-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Due Date</label>
                <input type="date" name="due_date" required class="w-full border rounded-lg p-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Priority</label>
                <select name="priority" required class="w-full border rounded-lg p-2">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeTodoModal()" class="px-4 py-2 border rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Add Todo</button>
            </div>
        </form>
    </div>

</div>

<script>
// Headcount Line Chart
const headcountCtx = document.getElementById('headcountChart').getContext('2d');
new Chart(headcountCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($headcountData->pluck('month')) !!},
        datasets: [{
            label: 'Employee Headcount',
            data: {!! json_encode($headcountData->pluck('count')) !!},
            borderColor: '#4F46E5',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Department Pie Chart
const deptCtx = document.getElementById('departmentChart').getContext('2d');
new Chart(deptCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($departmentData->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($departmentData->pluck('count')) !!},
            backgroundColor: [
                '#4F46E5',
                '#10B981',
                '#F59E0B',
                '#EF4444',
                '#8B5CF6',
                '#EC4899'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});



// Salary Distribution Chart
new Chart(document.getElementById('salaryChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(collect($salaryRanges)->pluck('range')) !!},
        datasets: [{
            label: 'Employees',
            data: {!! json_encode(collect($salaryRanges)->pluck('count')) !!},
            backgroundColor: '#4F46E5'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Attendance Donut Chart
new Chart(document.getElementById('attendanceChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($attendance->pluck('status')) !!},
        datasets: [{
            data: {!! json_encode($attendance->pluck('count')) !!},
            backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Employee Turnover Chart
new Chart(document.getElementById('turnoverChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(collect($turnoverData)->pluck('month')) !!},
        datasets: [{
            label: 'Net Change',
            data: {!! json_encode(collect($turnoverData)->pluck('net_change')) !!},
            backgroundColor: d => d.raw >= 0 ? '#10B981' : '#EF4444'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Todo Management Functions
function openTodoModal() {
    document.getElementById('todoModal').classList.remove('hidden');
}

function closeTodoModal() {
    document.getElementById('todoModal').classList.add('hidden');
}

async function submitTodo(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('{{ route("todos.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });

        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function updateTodoStatus(todoId, completed) {
    try {
        const response = await fetch(`/todos/${todoId}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status: completed ? 'completed' : 'pending'
            })
        });

        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>
@endsection