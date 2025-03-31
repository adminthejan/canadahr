@extends('layouts.dashboard-layout')

@section('title', 'Employee Hierarchy')

@section('content')
<div class="w-full h-screen overflow-visible relative">
    <!-- Zoom Controls -->
    <div class="absolute top-4 right-4 z-10 flex space-x-4">
        <button id="zoom-in" class="px-4 py-2 bg-green-500 text-white rounded">Zoom In</button>
        <button id="zoom-out" class="px-4 py-2 bg-red-500 text-white rounded">Zoom Out</button>
    </div>

    <!-- Hierarchy Container -->
    <div class="parent-container">
    <div id="hierarchy-container" class="min-h-screen overflow-visible bg-gray-100 p-8 flex justify-center items-start relative">        <div class="flex flex-col items-center relative" style="min-width: max-content;">
            <!-- CEO -->
            <div class="flex flex-col items-center mb-8">
                <img src="{{ asset('/employee.png') }}" class="w-24 h-24 rounded-full border-2 border-green-500">
                <p class="text-lg font-bold mt-2">{{ $ceo->first_name }} {{ $ceo->last_name }}</p>
                <p class="text-sm text-gray-600">Employee ID: {{ $ceo->id }}</p>
                <p class="text-sm text-gray-600">{{ $ceo->title ?? 'CEO' }}</p>
            </div>

            <!-- Subordinates -->
            @if ($ceo->subordinates->isNotEmpty())
                <div class="flex justify-center w-full">
                    @include('management.employee.partials.subordinates', ['employees' => $ceo->subordinates])
                </div>
            @endif
        </div>
    </div>
    </div>
</div>

<script>
const container = document.getElementById('hierarchy-container');
let scale = 1;

// Adjust the transform-origin to center
container.style.transformOrigin = 'center';

// Zoom In
document.getElementById('zoom-in').addEventListener('click', () => {
    scale += 0.1;
    container.style.transform = `scale(${scale})`;
    centerScroll();
});

// Zoom Out
document.getElementById('zoom-out').addEventListener('click', () => {
    scale = Math.max(scale - 0.1, 0.1);
    container.style.transform = `scale(${scale})`;
    centerScroll();
});

// Function to keep the tree centered
function centerScroll() {
    const containerParent = container.parentElement; // The parent of #hierarchy-container
    containerParent.scrollLeft = (container.scrollWidth - containerParent.clientWidth) / 2;
    containerParent.scrollTop = (container.scrollHeight - containerParent.clientHeight) / 2;
}

</script>
@endsection  
