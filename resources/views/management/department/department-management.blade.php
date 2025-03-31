@extends('layouts.dashboard-layout')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@section('title', 'Department Management')

@section('content')


@if(session('success'))
<script>
     
    document.addEventListener("DOMContentLoaded", () => {
        showNotification("{{ session('success') }}");
    });

    async function showNotification(message) {
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');

        // Set the message
        notificationMessage.textContent = message;

        // Slide the notification down
        setTimeout(() => {
        // Slide the notification down
        notification.style.top = '20px';

        // Hide the notification after an additional 3 seconds
        setTimeout(() => {
            notification.style.top = '-100px';
        }, 3000);
        }, 5000);

        // Optionally send the message to the backend
        try {
            const response = await fetch("{{ route('notify') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message }),
            });

            if (!response.ok) {
                console.error('Failed to send notification:', response.statusText);
            }
        } catch (error) {
            console.error('Error sending notification:', error);
        }
    }
    </script>
     @endif
    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true" data-bs-keyboard="false">
<button type="button" class="btn-close position-absolute top-0 end-0" data-bs-dismiss="modal" aria-label="Close"></button>

  <div class="modal-dialog modal-dialog-centered " style="padding: 0;">
    <div class="rounded-3xl" style="padding-top: 0;" id="editDepartmentContent">
      <!-- Close Button -->
      <!-- Dynamically loaded content will be injected here -->
      <div class="text-center py-4">
        <p>Loading...</p>
      </div>
    </div>
  </div>
</div>

    <div class="flex flex-col items-start justify-start w-full px-16">

<div class="w-full pt-8">
  <div class="flex items-center justify-between w-full">
    <div class="flex ">
    <p class="text-6xl font-bold text-black nunito-">Departments</p>
    </div>
    <div class="flex items-center space-x-4">

    <!-- Add Employee Button -->
    <button class="flex items-center justify-center space-x-2 px-8 py-2 text-white text-2xl bg-gradient-to-r from-[#184E77] to-[#52B69A] rounded-xl shadow-sm hover:from-[#1B5A8A] hover:to-[#60C3A8]" onclick="openAddModal()">
        <p class="text-3xl"><i class="ri-add-fill"></i></p>
        <span>Add Department</span>
    </button>
    </div>

  </div>
</div>
<nav class="flex py-3" aria-label="Breadcrumb">
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

@foreach ($departments->chunk(4) as $departmentChunk)
  <div class="grid items-center w-full grid-cols-1 gap-8 pt-8 pb-8 md:grid-cols-4 nunito-">
  @foreach ($departmentChunk as $department)
  <a href="{{ route('department.show', $department->department_id) }}" class="block relative">
      <div class="border-2 border-[#00000080] p-8 space-y-4">
        <div class="flex flex-col items-center justify-center w-full">
          <p class="text-4xl text-black font-bold">{{ explode(' ', $department->name)[0] }}</p>
          <p class="text-2xl text-[#00000099]">Department ID: {{ substr($department->department_id, 0, 5) }}</p>
        </div>
        <div class="flex flex-col w-full pt-4 space-y-2 border-l-4 border-[#34A0A4] pl-4">
          <p class="text-xl font-bold text-black nunito-">Total Number of Employees</p>
          <p class="text-3xl nunito- text-[#34A0A4] font-bold">{{ $department->employees_count }} </p>
        </div>
        <div class="flex flex-col w-full pt-4 space-y-2 border-l-4 border-[#34A0A4] pl-4">
          <p class="text-xl font-bold text-black nunito-">Total Number of Branches</p>
          @php
            
          @endphp
          <p class="text-3xl nunito- text-[#34A0A4] font-bold">{{ $department->branch_count }}</p>
        </div>
      </div>
      </a>
    @endforeach
  </div>
@endforeach

  
</div>

<div class="w-full flex justify-center items-center pt-4">
    {{ $departments->links('vendor.pagination.tailwind') }}
</div>

</div>

<script>
  function toggleGradientText() {
    const textElement = document.getElementById('payrollText');
    if (textElement.classList.contains('text-black')) {
      // Apply gradient
      textElement.classList.remove('text-black');
      textElement.classList.add('bg-gradient-to-r', 'from-[#184E77]', 'to-[#52B69A]', 'text-transparent', 'bg-clip-text');
    } else {
      // Revert to black
      textElement.classList.add('text-black');
      textElement.classList.remove('bg-gradient-to-r', 'from-[#184E77]', 'to-[#52B69A]', 'text-transparent', 'bg-clip-text');
    }
  }
  
  function toggleMenu(menuId) {
    const menu = document.getElementById(menuId);
    menu.classList.toggle('hidden');
  }
  const textElements = document.querySelectorAll('span.text-xl');

textElements.forEach((element) => {
    element.addEventListener('click', function () {
        // Reset all text elements to black
        textElements.forEach((el) => {
            el.classList.remove('bg-gradient-to-r', 'from-[#184E77]', 'to-[#52B69A]', 'text-transparent', 'bg-clip-text');
            el.classList.add('text-black');
        });

        // Apply gradient to the clicked element
        this.classList.remove('text-black');
        this.classList.add('bg-gradient-to-r', 'from-[#184E77]', 'to-[#52B69A]', 'text-transparent', 'bg-clip-text');
    });
});

function openAddModal() {
    const modal = new bootstrap.Modal(document.getElementById('editDepartmentModal'));
    modal.show();

    const modalContent = document.getElementById('editDepartmentContent');
    modalContent.innerHTML = '<div class="text-center "><p>Loading...</p></div>';

    // Fetch content from the server
    fetch(`http://127.0.0.1:8000/dashboard/departments/department/create`)
      .then(response => response.text())
      .then(html => {
        modalContent.innerHTML = html;

         // Reinitialize any necessary scripts or event listeners if required
      })
      .catch(error => {
        modalContent.innerHTML = '<div class="text-center py-4 text-danger"><p>Error loading content. Please try again later.</p></div>';
        console.error('Error:', error);
      });
}

</script>
  


@endsection
