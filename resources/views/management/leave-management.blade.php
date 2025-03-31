<h2 class="text-2xl font-bold mb-4">Leave Management</h2>
<table class="w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-200">
            <th class="border border-gray-300 px-4 py-2">ID</th>
            <th class="border border-gray-300 px-4 py-2">Employee</th>
            <th class="border border-gray-300 px-4 py-2">Leave Type</th>
            <th class="border border-gray-300 px-4 py-2">Start Date</th>
            <th class="border border-gray-300 px-4 py-2">End Date</th>
            <th class="border border-gray-300 px-4 py-2">Reason</th>
            <th class="border border-gray-300 px-4 py-2">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($leaves as $leave)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $leave->id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $leave->leave_type }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $leave->start_date }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $leave->end_date }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $leave->reason ?? 'N/A' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $leave->status }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="border border-gray-300 px-4 py-2 text-center">No leave records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
