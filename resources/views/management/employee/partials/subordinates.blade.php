<!-- Partial View: Subordinates -->
<div class="employees-row flex justify-center w-full relative" style="min-width: max-content;">
    @if ($employees->count() > 1)
        <!-- Horizontal Line Connecting Dots -->
        <div class="absolute top-0 left-0 w-full h-0.5 bg-gray-300 z-0"></div>
    @endif

    <div class="flex items-center">
        @foreach ($employees as $employee)
            <div class="flex flex-col items-center mx-8 relative" style="min-width: 150px;">
                <!-- Circular Dot -->
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-4 h-4 bg-blue-500 rounded-full border-2 border-white z-10"></div>

                <!-- Subordinate Card -->
                <div class="flex flex-col items-center mt-2">
                    <img src="{{ asset('/employee.png') }}" class="w-20 h-20 rounded-full border-2 border-green-500">
                    <p class="text-sm font-bold mt-2">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                    <p class="text-xs text-gray-600">Employee ID: {{ $employee->id }}</p>
                    <p class="text-xs text-gray-600">{{ $employee->title ?? 'No Title' }}</p>
                </div>

                <!-- Recursive Subordinates -->
                @if ($employee->subordinates->isNotEmpty())
                    <div class="flex justify-center mt-4 w-full">
                        @include('management.employee.partials.subordinates', ['employees' => $employee->subordinates])
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
