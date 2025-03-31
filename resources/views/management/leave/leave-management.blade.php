@extends('layouts.dashboard-layout')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@section('title', 'Leave Management')

@section('content')

@if(session('success') )
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";

        if (successMessage) {
            showNotification(successMessage, 'success');
        }
        if (errorMessage) {
            showNotification(errorMessage, 'error');
        }
    });

    async function showNotification(message) {
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');

        // Set the message
        notificationMessage.textContent = message;
        if (type === 'success') {
            notification.style.backgroundColor = '#4CAF50'; // Green for success
        } else if (type === 'error') {
            notification.style.backgroundColor = '#F44336'; // Red for error
        }
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
            @if(session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul>
                    {{session('error')}}
                </ul>
            </div>
        @endif





            <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true" data-bs-keyboard="false">
                <button type="button" class="btn-close position-absolute top-0 end-0" data-bs-dismiss="modal" aria-label="Close"></button>
                
                  <div class="modal-dialog modal-dialog-left " style="padding: 0;">
                    <div class="rounded-3xl" style="padding-top: 60px;" id="editAttendanceContent">
                      <!-- Close Button -->
                      <!-- Dynamically loaded content will be injected here -->
                      <div class="text-center py-4">
                        <p>Loading...</p>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="flex flex-col items-start justify-start w-full">
                
                <div class="w-full ">
                  <div class="flex items-center justify-between w-full">
                    <div class="flex ">
                    <p class="md:text-6xl text-4xl font-bold text-black nunito-">Leaves</p>
                    </div>
                    <div class="flex items-center space-x-4">
                
        
                
                    <!-- Add record Button -->
                
                    <button 
                    class="flex items-center justify-center nunito- space-x-2 px-8 py-2 text-white md:text-2xl text-xl bg-gradient-to-r from-[#184E77] to-[#52B69A] rounded-xl shadow-sm hover:from-[#1B5A8A] hover:to-[#60C3A8]"
                    onclick="openAddModal()">
                    <p class="text-3xl"><i class="ri-add-fill"></i></p>
                    <span>Add Leave</span>
                    </button>
                
                    
                    </div>
                
                  </div>
                </div>
                <nav class="flex py-3" aria-label="Breadcrumb">
                  <ol class="inline-flex items-center space-x-1 md:space-x-3 nunito-">
                    <li class="inline-flex items-center">
                      <a href="#" class="inline-flex items-center text-3xl font-medium text-[#00000080] hover:text-blue-600">
                      Leave
                      </a>
                    </li>
                    <li>
                      <div class="flex items-center">
                        <p class="text-[#00000080] text-3xl"><i class="ri-arrow-right-wide-line"></i></p>
                        <a href="#" class="ml-1 font-medium text-[#00000080] text-3xl hover:text-blue-600">Employee Leaves</a>
                      </div>
                    </li>
                  </ol>
                </nav>
                
                
                <div class="w-full flex justify-end items-end pt-2 pb-2">
                  <button id="print-table" class="flex items-center justify-center space-x-2 px-6 py-2 text-[#184E77] border-2 border-[#184E77] text-2xl bg-white rounded-xl shadow-sm hover:from-[#1B5A8A] hover:to-[#60C3A8]">
                        <span>Generate Report</span>
                    </button>
                  </div>
                
                  <div class="w-full flex justify-between items-center  space-x-8 md:space-x-0 py-2">
                
                <!-- Search Bar on the Left -->
                <div class="w-[300px]">
                  <input
                  id="custom-search-input"
                    type="text"
                    placeholder="Search..."
                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#184E77] focus:border-[#184E77]"
                  />
                
                </div>
         
                <!-- Calendar Input on the Right -->
                <div class="relative w-[300px] nunito-">
           <!-- Approval Status Filter -->
  <div class="w-full flex justify-end items-center mb-3 ">
    <div class="relative flex justify-end  w-[300px] nunito- z-10">
<select
id="approvalStatusFilter"
class="w-1/2  px-4 py-2  text-black border-2 border-[#184E77] rounded-xl shadow-sm"
>
<option value="">Select Status </option>
<option value="Approved">Approved</option>
<option value="Pending">Pending</option>
<option value="Rejected">Rejected</option>
</select>
</div>
 </div>
                    
                  <button
                    id="calendarButton"
                    class="flex items-center justify-between w-full px-4 py-2 text-left text-black border-2 border-[#184E77] rounded-xl shadow-sm hover:bg-[#f0f8ff]"
                  >
                    <div class="flex items-center space-x-4">
                      <span class="iconify text-[#184E77]" data-icon="mdi:calendar-outline" style="font-size: 20px;"></span>
                      <div>
                        <span class="text-sm text-[#184E77]">Select a day</span>
                        <p id="selectedDate" class="text-lg font-bold">13.03.2021</p>
                      </div>
                    </div>
                    <span class="iconify text-black" data-icon="mdi:chevron-down"></span>
                  </button>
                
                  <!-- Hidden Calendar Input -->
                  <input
                    id="calendarInput"
                    type="text"
                    class="absolute z-10 opacity-0 pointer-events-none"
                  />
                  <button
                  id="resetCalendarButton"
                  class="px-4 py-2 text-white bg-red-600 rounded-xl shadow-sm hover:bg-red-700 mt-2"
                >
                  Reset
                </button>
                </div>
                
                </div>
                
                
                
                <table class="w-full nunito- border-separate" style="border-spacing: 0 12px; width: 100%;" id="attendance-table">
                  <thead class="w-full">
                    <tr class="bg-white">
                      <th class="text-xl text-black font-bold px-4 py-2 text-left align-middle">Employee Name:</th>
                      <th class="text-xl text-black font-bold px-4 py-2 text-left align-middle">Leave Type:</th>
                      <th class="text-xl text-black font-bold px-4 py-2 text-left align-middle">Date From:</th>
                      <th class="text-xl text-black font-bold px-4 py-2 text-left align-middle">Date To:</th>
                      <th class="text-xl text-black font-bold px-4 py-2 text-left align-middle">Duration:</th>
                      <th class="text-xl text-black font-bold px-4 py-2 text-left align-middle">Approval Status:</th>
                      <th class="text-xl text-black font-bold px-4 py-2 text-left align-middle">Supporting Docs:</th>
                      <th class="text-xl w-1/2 text-black font-bold px-4 py-2 text-center align-left hidden">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($leaves as $leave)
                  <tr class="hover:shadow-popup hover:rounded-xl hover:scale-101 hover:bg-white transition duration-300 hover:border-r-4 hover:border-b-4 hover:border-gray-500">
                  <td class="text-xl text-black px-4 py-2 text-left align-middle bg-[#D9D9D966] rounded-l-xl">
                  {{ $leave->employee_name }} 
                  </td>
                  <td class="text-xl text-black px-4 py-2 text-left align-middle bg-[#D9D9D966]">
                  {{ $leave->leave_type }}
                  </td>
                  <td class="text-xl text-black px-4 py-2 text-left align-middle bg-[#D9D9D966]">
                   {{ $leave->start_date }}
                  </td>
                  <td class="text-xl text-black px-4 py-2 text-left align-middle bg-[#D9D9D966]">
                  {{ $leave->end_date }}
                  </td>
                  <td class="text-xl text-black px-4 py-2 text-left align-middle bg-[#D9D9D966]">
                  {{ $leave->duration }}
                  </td>
                  
                  <td class="text-xl text-[#3569C3] px-4 py-2 text-left align-middle bg-[#D9D9D966]">
                
                    
                    @if (strtolower($leave->status ) === 'approved')
                  
                  <p class="text-[#47B439] border-2 border-[#47B439] bg-[#47B43933] rounded-xl px-2 py-1 shadow-sm">
                      Approved 
                  </p>
                  @elseif (strtolower($leave->status ) === 'pending')
                  <p class="text-[#FFBF00] border-2 border-[#FFBF00] bg-[#FFBF0033] rounded-xl px-2 py-1 shadow-sm">
                      Pending
                  </p>
                  @elseif (strtolower($leave->status) === 'rejected')
                  <p class="text-[#FF0000] border-2 border-[#FF0000] bg-[#FF000033] rounded-xl px-2 py-1 shadow-sm">
                      Rejected
                  </p>
                  @endif
                      </td>
                  <td class="text-xl text-[#3569C3] px-4 py-2 text-left align-middle bg-[#D9D9D966]">
                  @if(!empty($leave->supporting_documents) && is_string($leave->supporting_documents))
                    @php
                      $documents = json_decode($leave->supporting_documents, true);
                    @endphp
                    @if(is_array($documents))
                      <ul>
                        @foreach ($documents as $document)
                          @if(is_string($document))
                            <li>
                              @php
                                $fileName = basename($document);
                              @endphp
                              <a href="{{ asset('storage/' . $document) }}" target="_blank" class="text-blue-500 underline document-link">
                                {{ $fileName }}
                              </a>
                            </li>
                          @endif
                        @endforeach
                      </ul>
                    @else
                      <p class="text-gray-500">Invalid documents data</p>
                    @endif
                  @else
                    <p class="text-gray-500">No documents uploaded</p>
                  @endif
                </td>
                <td class="text-xl text-[#3569C3] px-4 py-2 text-left align-middle bg-[#D9D9D966]">
                
                <!-- dropdown start-->
                
                <div class="relative inline-block text-center">
                        <!-- Toggle Button -->
                        <button id="dropdownButton"  class="dropdown-trigger p-2 rounded hover:bg-gray-300">
                        <span class="iconify" data-icon="qlementine-icons:menu-dots-16" style="width: 16px; height: 16px;"></span>
                    </button>
                
                        <!-- Dropdown Menu -->
                        <div id="dropdown-menu" 
                             class="absolute top-0 right-3 mt-12 w-30 bg-white border border-gray-100 rounded-xl shadow-lg hidden z-10">
                            <ul class=" text-gray-700">
                                <li><a href="#" class="block px-2 py-2 hover:bg-gray-100" onclick="openViewModal({
                        employeeName: '{{ $leave->employee_name }}',
                        employeeId: '{{ $leave->employment_ID }}',
                        leaveType: '{{ $leave->leave_type }}',
                        approvedBy: '{{ $leave->approved_person }}',
                        dataFrom: '{{ $leave->start_date }}',
                        dataTo: '{{ $leave->end_date }}',
                        status: '{{ $leave->status}}',
                        description: '{{ $leave->description }}',
                        supportingDoc: '{{ $leave->supporting_documents }}',
                
                    })">
                            View</a></li>
                                <li class="cursor-pointer" ><a onclick="openEditModal({{ $leave->id }})"  class="block px-2 py-2 hover:bg-gray-100">Edit</a></li>
                                <li class="bg-red">
                                  
                                <form action="{{ route('leave.destroy', $leave->id) }}" method="POST" class="m-0 p-0">
                @csrf
                            @method('DELETE')
                            <button type="submit" 
                    class="block px-2 py-2 w-full rounded-xl bg-red-600 text-white hover:bg-red-700">
                    Delete
                </button>
                            </form></li>
                            </ul>
                        </div>
                    </div>
                
                <!--dropdown end-->
                
                
                
                
                
                  </td>
                </tr>
                @endforeach
                  </tbody>
                </table>
                
                
                
                </div>
                
                
                <!--View record form start-->
                <div id="view-attendance-modal-container" class=" fixed inset-0 bg-black bg-opacity-50 w-full opacity-0 transition-opacity duration-300 flex justify-center items-center hidden z-50">
                
                <div class="w-full flex justify-center items-center rounded-3xl">
                  <!-- Close Button -->
                  <div id="modal-container" class="w-1/3 flex flex-col justify-start items-center relative bg-white nunito- p-2 rounded-3xl bg-gradient-to-r from-[#184E77] to-[#52B69A]">
                    <button onclick="closeAddNewModal()" id="close-button" class="absolute top-4 right-4 text-black font-medium rounded-full text-xl p-4 inline-flex items-center">
                        <span class="iconify" data-icon="ic:baseline-close" style="width: 16px; height: 16px;"></span>
                    </button>
                    <div class="w-full flex flex-col justify-start items-center bg-white p-8 rounded-3xl space-y-8">
                      <div class="flex flex-col justify-center items-center space-y-4">
                        <p class="text-5xl text-black font-bold">Leave</p>
                        <p class="text-3xl text-[#00000080]">Enter the Information about Leaves </p>
                      </div>
                        <div class="w-full flex mx-auto pb-8 pt-8 px-16">
                            <div class="w-1/2 flex flex-col space-y-8">
                                <!-- Employee ID -->
                            <p class="text-xl font-bold text-black">Employee Name :</p>
                            <p class="text-xl font-bold text-black">Employee ID :</p>
                            <p class="text-xl font-bold text-black">Leave Type :</p>
                            <p class="text-xl font-bold text-black">Approved By :</p>
                            <p class="text-xl font-bold text-black">Data From :</p>
                            <p class="text-xl font-bold text-black">Data To :</p>
                            <p class="text-xl font-bold text-black">Status :</p>
                            <p class="text-xl font-bold text-black">Description : </p>
                            <p class="text-xl font-bold text-black">Supporting Doc :</p>
                            </div>
                            <div class="w-1/2 flex flex-col space-y-8">
                            <p class="text-xl font-bold text-black modal-employee-name"></p>
                            <p class="text-xl font-bold text-black modal-employee-id"></p>
                            <p class="text-xl font-bold text-black modal-leave-type"></p>
                            <p class="text-xl font-bold text-black modal-approved-by"></p>
                            <p class="text-xl font-bold text-black modal-data-from"></p>
                            <p class="text-xl font-bold text-black modal-data-to"></p>
                            <p class="text-xl font-bold text-black modal-status"></p>
                            <p class="text-xl font-bold text-black modal-description"></p>
                            <p class="text-xl font-bold text-black modal-supporting-doc"></p>
                            </div>
                          
                        </div>
                        <!-- Submit Button -->
                        <div class="w-full text-center px-16">
                          <button
                            onclick="closeAddNewModal()"
                            class="w-full bg-gradient-to-r from-[#184E77] to-[#52B69A] text-xl text-white font-bold py-4 px-4 rounded-xl hover:from-blue-600 hover:to-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                          >
                          Done
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
                <!--View record form end-->

<script>

let selectedAddFiles = new DataTransfer();
function openAddModal() {
    const modal = new bootstrap.Modal(document.getElementById('editAttendanceModal'));
    modal.show();

    const modalContent = document.getElementById('editAttendanceContent');
    modalContent.innerHTML = '<div class="text-center "><p>Loading...</p></div>';

    selectedAddFiles = new DataTransfer();
    // Fetch content from the server
    fetch(`${window.location.origin}/dashboard/leaves/leave/create`)
      .then(response => response.text())
      .then(html => {
        modalContent.innerHTML = html;

         // Reinitialize any necessary scripts or event listeners
         setTimeout(() => {
                // Ensure file input works after content is loaded
                const docFilesInput = document.getElementById('doc-files');
                if (docFilesInput) {
                    docFilesInput.addEventListener('change', function () {
                        console.log('File input changed');
                        handleAddFileSelection(this);
                    });
                }
            }, 100);
      })
      .catch(error => {
        modalContent.innerHTML = '<div class="text-center py-4 text-danger"><p>Error loading content. Please try again later.</p></div>';
        console.error('Error:', error);
      });
  }

  // Global variables for file management
let existingFilesList = [];
let selectedFiles = new DataTransfer();

// Initialize existing files when modal opens
function initializeExistingFiles(existingFilesJson) {
    try {
        existingFilesList = JSON.parse(existingFilesJson || '[]');
        refreshFileList();
    } catch (error) {
        console.error('Error initializing existing files:', error);
    }
}

// Refresh the complete file list display
function refreshFileList() {
    const fileListDisplay = document.getElementById('file-list-items');
    fileListDisplay.innerHTML = '';

    // Display existing files
    existingFilesList.forEach(filePath => {
        const fileName = filePath.split('/').pop();
        addFileToDisplay(fileName, filePath, true);
    });

    // Display newly selected files
    Array.from(selectedFiles.files).forEach(file => {
        addFileToDisplay(file.name, null, false);
    });

    // Update hidden inputs
    updateHiddenInputs();
}

// Add individual file to display
function addFileToDisplay(fileName, filePath, isExisting) {
    const fileListDisplay = document.getElementById('file-list-items');
    const listItem = document.createElement('li');
    listItem.className = 'flex items-center space-x-2 mb-2';
    
    listItem.innerHTML = isExisting ? 
        `<span class="text-2xl">
            <i class="ri-file-pdf-2-fill"></i>
        </span>
        <a href="/storage/${filePath}" target="_blank" class="text-blue-500 underline">${fileName}</a>
        <span onclick="removeFile('${filePath}', true)" class="text-red-500 cursor-pointer ml-2">✖</span>` :
        `<span class="text-2xl">
            <i class="ri-file-pdf-2-fill"></i>
        </span>
        <span class="text-blue-500">${fileName}</span>
        <span onclick="removeFile('${fileName}', false)" class="text-red-500 cursor-pointer ml-2">✖</span>`;
    
    fileListDisplay.appendChild(listItem);
}

// Handle new file selection
function handleFileSelection(input) {
    const files = Array.from(input.files);
    
    files.forEach(file => {
        // Check for duplicates
        const isDuplicateNew = Array.from(selectedFiles.files)
            .some(existing => existing.name === file.name);
        const isDuplicateExisting = existingFilesList
            .some(existing => existing.includes(file.name));

        if (isDuplicateNew || isDuplicateExisting) {
            alert(`File "${file.name}" is already selected.`);
            return;
        }

        selectedFiles.items.add(file);
    });

    refreshFileList();
    input.value = ''; // Clear the input for future selections
}

// Remove file (both existing and new)
function removeFile(identifier, isExisting) {
    if (isExisting) {
        existingFilesList = existingFilesList.filter(path => path !== identifier);
    } else {
        const newDataTransfer = new DataTransfer();
        Array.from(selectedFiles.files).forEach(file => {
            if (file.name !== identifier) {
                newDataTransfer.items.add(file);
            }
        });
        selectedFiles = newDataTransfer;
    }
    
    refreshFileList();
}

// Update all hidden inputs
function updateHiddenInputs() {
    // Update existing files input
    const existingFilesInput = document.getElementById('existing-files');
    existingFilesInput.value = JSON.stringify(existingFilesList);

    // Update new files input
    const hiddenFilesInput = document.getElementById('hidden-files');
    hiddenFilesInput.files = selectedFiles.files;
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('doc-files');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            handleFileSelection(this);
        });
    }

    const existingFilesData = document.getElementById('existing-files-data');
    if (existingFilesData) {
        initializeExistingFiles(existingFilesData.value);
    }
});
  function openEditModal(leaveId) {
    const modal = new bootstrap.Modal(document.getElementById('editAttendanceModal'));
    modal.show();

    const modalContent = document.getElementById('editAttendanceContent');
    modalContent.innerHTML = '<div class="text-center "><p>Loading...</p></div>';

    selectedFiles = new DataTransfer();
    existingFilesList = [];

    // Fetch content from the server
    fetch(`${window.location.origin}/dashboard/leaves/${leaveId}/edit`)
      .then(response => response.text())
      .then(html => {
        modalContent.innerHTML = html;

        setTimeout(() => {
                // Ensure file input works after content is loaded
                const docFilesInput = document.getElementById('doc-files');
                if (docFilesInput) {
                    docFilesInput.addEventListener('change', function() {
                        handleFileSelection(this);
                    });
                }
                const existingFilesData = document.getElementById('existing-files-data');
                if (existingFilesData) {
                    initializeExistingFiles(existingFilesData.value);
                }
            }, 100);
      })
      .catch(error => {
        modalContent.innerHTML = '<div class="text-center py-4 text-danger"><p>Error loading content. Please try again later.</p></div>';
        console.error('Error:', error);
      });
  }

  function openViewModal(data) {
    // Populate modal fields with the passed data
    document.querySelector('#view-attendance-modal-container .modal-employee-name').textContent = data.employeeName;
    document.querySelector('#view-attendance-modal-container .modal-employee-id').textContent = data.employeeId;
    document.querySelector('#view-attendance-modal-container .modal-leave-type').textContent = data.leaveType;
    document.querySelector('#view-attendance-modal-container .modal-approved-by').textContent = data.approvedBy;
    document.querySelector('#view-attendance-modal-container .modal-data-from').textContent = data.dataFrom;
    document.querySelector('#view-attendance-modal-container .modal-data-to').textContent = data.dataTo;
    document.querySelector('#view-attendance-modal-container .modal-status').textContent = data.status;
    document.querySelector('#view-attendance-modal-container .modal-description').textContent = data.description;
    
    const supportingDocContainer = document.querySelector('#view-attendance-modal-container .modal-supporting-doc');
    supportingDocContainer.innerHTML = ''; // Clear previous content
    
    try {
        const documents = JSON.parse(data.supportingDoc);
        if (Array.isArray(documents) && documents.length > 0) {
            const docList = document.createElement('ul');
            docList.className = 'space-y-2';
            
            documents.forEach(doc => {
                if (typeof doc === 'string') {
                    const li = document.createElement('li');
                    const link = document.createElement('a');
                    link.href = `/storage/${doc}`;
                    link.target = '_blank';
                    link.className = 'text-blue-500 hover:text-blue-700 underline';
                    link.textContent = doc.split('/').pop(); // Get filename from path
                    li.appendChild(link);
                    docList.appendChild(li);
                }
            });
            
            supportingDocContainer.appendChild(docList);
        } else {
            supportingDocContainer.textContent = 'No documents available';
        }
    } catch (e) {
        supportingDocContainer.textContent = 'No documents available';
    }

    // Show the modal
    const modal = document.getElementById('view-attendance-modal-container');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0', 'scale-95');
    }, 10);
}


   function toggleDropdown(button) {
    const dropdown = button.nextElementSibling; // Find the next sibling element (dropdown menu)
    dropdown.classList.toggle('hidden'); // Toggle the 'hidden' class
}

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
// Initialize Flatpickr$(document).ready(function () {
    // Initialize DataTable

    $(document).ready(function () {
      // Initialize DataTable
    var table = $('#attendance-table').DataTable({
      dom: '<"top"f>rt<"bottom"p><"clear">', // Custom layout: search box on top, pagination on bottom
    paging: true,
    pageLength: 10, 
    pagingType: 'simple', 
    searching: true,
        buttons: [
            {
                extend: 'print',
                text: 'Print Table',
                title: 'Leave Report', // Optional title
                exportOptions: {
                    columns: ':visible', // Export all visible columns
                }
            }
        ],
        language: {
        paginate: {
            previous: '<span class="custom-prev-button"><i class="ri-arrow-left-s-line"></i> Prev</span>',
            next: '<span class="custom-next-button">Next <i class="ri-arrow-right-s-line"></i></span>',
        },
    },
    columnDefs: [
        { 
            targets: 0, 
            className: 'employee-column' 
        },
        { 
            targets: 1, 
            className: 'leave-type-column' 
        },
        { 
            targets: 2, 
            className: 'date-from-column' 
        },
        { 
            targets: 3, 
            className: 'date-to-column' 
        },
        { 
            targets: 4, 
            className: 'duration-column' 
        },
        { 
            targets: 5, 
            className: 'approval-column' 
        },
        { 
            targets: 6, 
            className: 'documents-column' 
        },
        { 
            targets: 7, 
            className: 'actions-column' 
        }
    ]
    });

    // Attach Print functionality to the custom button
    $('#print-table').on('click', function () {
        table.button('.buttons-print').trigger(); // Trigger the print button
    });

// Attach a keyup event listener to the custom search input element
$('#custom-search-input').on('keyup', function () {
    const searchTerm = $(this).val(); // Get the value of the search input
    table.search(searchTerm).draw(); // Trigger the DataTable search with the entered term
});


  // Approval Status Filter
  $('#approvalStatusFilter').on('change', function () {
    const status = $(this).val();
    table.columns(5).search(status).draw(); // Assuming status is in the 6th column (index 5)
  });

$('#resetCalendarButton').on('click', function () {
    selectedDate.textContent = '13.03.2021'; // Reset displayed date
    calendarInput._flatpickr.clear(); // Clear Flatpickr input
    table.columns(3).search('').draw(); // Clear DataTable date filter
  });
//pagination controls
table.on('draw', function () {
    const pagination = $('.dataTables_paginate');
    const pageInfo = table.page.info();

    // Clear existing pagination
    pagination.html('');

    // Build custom pagination
    let customPagination = `
        <div class="flex items-center justify-end w-full space-x-4 mt-8">
            <button class="flex items-center px-2 py-1 text-gray-500 hover:text-black focus:outline-none ${
                pageInfo.page === 0 ? 'cursor-not-allowed text-gray-400' : ''
            }" data-action="prev" ${pageInfo.page === 0 ? 'disabled' : ''}>
                <i class="ri-arrow-left-s-line"></i>
                <span class="ml-1">Prev</span>
            </button>
            <div class="flex items-center space-x-2">
    `;

    for (let i = 0; i < pageInfo.pages; i++) {
        customPagination += `
            <button class="flex items-center justify-center w-8 h-8 ${
                i === pageInfo.page
                    ? 'font-bold text-black bg-[#52B69A80] rounded-full focus:outline-none'
                    : 'text-black rounded-full hover:bg-gray-200 focus:outline-none'
            }" data-page="${i}">
                ${i + 1}
            </button>
        `;
    }

    customPagination += `
            </div>
            <button class="flex items-center px-2 py-1 text-gray-500 hover:text-black focus:outline-none ${
                pageInfo.page + 1 === pageInfo.pages
                    ? 'cursor-not-allowed text-gray-400'
                    : ''
            }" data-action="next" ${
        pageInfo.page + 1 === pageInfo.pages ? 'disabled' : ''
    }>
                <span class="mr-1">Next</span>
                <i class="ri-arrow-right-s-line"></i>
            </button>
        </div>
    `;

    pagination.html(customPagination);
});

// Handle custom pagination button clicks
$(document).on('click', '.dataTables_paginate button', function () {
    const action = $(this).data('action');
    const page = $(this).data('page');

    if (action === 'prev') {
        table.page('previous').draw('page');
    } else if (action === 'next') {
        table.page('next').draw('page');
    } else if (page !== undefined) {
        table.page(page).draw('page');
    }
});
//pagination controls end




    // Initialize Flatpickr for Date Selection
    const calendarInput = document.getElementById("calendarInput");
    const selectedDate = document.getElementById("selectedDate");
    const calendarButton = document.getElementById("calendarButton");

    flatpickr(calendarInput, {
        dateFormat: "Y-m-d",
        onChange: function (selectedDates, dateStr) {
            selectedDate.textContent = dateStr;
            // Filter DataTable by selected date
            table.search(dateStr).draw();
        },
    });

    calendarButton.addEventListener("click", () => {
        calendarInput._flatpickr.open();
    });

    table.draw('page');
});



function openAddNewModal() {
  const modal = document.getElementById('view-attendance-modal-container');
  modal.classList.remove('hidden'); // Make visible
  setTimeout(() => {
    modal.classList.remove('opacity-0', 'scale-95'); // Apply transition effects
  }, 10);
}

function closeAddNewModal() {
  const modal = document.getElementById('view-attendance-modal-container');
  modal.classList.add('opacity-0', 'scale-95'); // Apply transition effects
  setTimeout(() => {
    modal.classList.add('hidden'); // Hide modal after transition
  }, 300); // Match the transition duration
}


function handleAddFileSelection(input) {
    const fileList = document.getElementById('file-list-items');
    const hiddenFilesInput = document.getElementById('hidden-files');
    if (!fileList || !hiddenFilesInput) {
        console.error('Required DOM elements not found.');
        return;
    }

    const files = Array.from(input.files);

    // Add newly selected files to the DataTransfer object
    files.forEach(file => {
        selectedAddFiles.items.add(file);
    });

    // Update the displayed list
    refreshAddFileList();

    // Update the hidden input's files
    hiddenFilesInput.files = selectedAddFiles.files;

    // Clear the original file input so the same file can be reselected if needed
    input.value = '';
}

function refreshAddFileList() {
    const fileListDisplay = document.getElementById('file-list-items');
    if (!fileListDisplay) {
        console.error('File list display element not found.');
        return;
    }

    // Clear the list before re-rendering
    fileListDisplay.innerHTML = '';

    // Display all files in `selectedAddFiles`
    Array.from(selectedAddFiles.files).forEach(file => {
        addFileToDisplayAdd(file.name, null);
    });
}

function addFileToDisplayAdd(fileName, filePath) {
    const fileListDisplay = document.getElementById('file-list-items');
    if (!fileListDisplay) {
        console.error('File list display element not found.');
        return;
    }

    const listItem = document.createElement('li');
    listItem.className = 'flex items-center space-x-2 mb-2';

    listItem.innerHTML = filePath
        ? `<span class="text-2xl">
            <i class="ri-file-pdf-2-fill"></i>
        </span>
        <a href="/storage/${filePath}" target="_blank" class="text-blue-500 underline">${fileName}</a>
        <span onclick="removeFileAdd('${filePath}', true)" class="text-red-500 cursor-pointer ml-2">✖</span>`
        : `<span class="text-2xl">
            <i class="ri-file-pdf-2-fill"></i>
        </span>
        <span class="text-blue-500">${fileName}</span>
        <span onclick="removeFileAdd('${fileName}', false)" class="text-red-500 cursor-pointer ml-2">✖</span>`;

    fileListDisplay.appendChild(listItem);
}

// The removeFile function to remove files from selectedAddFiles
function removeFileAdd(fileIdentifier, isFilePath) {
    let updatedFiles;

    if (isFilePath) {
        // If it's a file path, remove the file from DataTransfer using the file path
        updatedFiles = Array.from(selectedAddFiles.files).filter(file => file.name !== fileIdentifier);
    } else {
        // If it's the file name, remove by name
        updatedFiles = Array.from(selectedAddFiles.files).filter(file => file.name !== fileIdentifier);
    }

    // Reset the DataTransfer object with updated files
    selectedAddFiles = new DataTransfer();
    updatedFiles.forEach(file => selectedAddFiles.items.add(file));

    // Update the displayed file list
    refreshAddFileList();

    // Update the hidden input's files to reflect the changes
    const hiddenFilesInput = document.getElementById('hidden-files');
    if (hiddenFilesInput) {
        hiddenFilesInput.files = selectedAddFiles.files;
    }
}


// Select all dropdown triggers
const dropdownTriggers = document.querySelectorAll('.dropdown-trigger');

// Event listener for dropdown buttons
dropdownTriggers.forEach(button => {
    button.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent the click from propagating to the window

        const dropdownMenu = button.nextElementSibling; // The dropdown menu after the button

        // Close all other dropdowns
        document.querySelectorAll('#dropdown-menu').forEach(menu => {
            if (menu !== dropdownMenu) {
                menu.classList.add('hidden');
            }
        });

        // Toggle the current dropdown menu
        dropdownMenu.classList.toggle('hidden');
    });
});

// Close dropdowns when clicking anywhere outside
window.addEventListener('click', () => {
  
    document.querySelectorAll('#dropdown-menu').forEach(menu => {
        menu.classList.add('hidden');
    });
});


//document hover modal

// Document hover tooltip functionality
document.addEventListener('DOMContentLoaded', () => {
    const documentLinks = document.querySelectorAll('.document-link');
    
    documentLinks.forEach(link => {
        // Create tooltip element
        const tooltip = document.createElement('div');
        tooltip.classList.add('document-tooltip');
        tooltip.style.display = 'none';
        tooltip.style.position = 'absolute';
        tooltip.style.backgroundColor = 'black';
        tooltip.style.color = 'white';
        tooltip.style.padding = '5px';
        tooltip.style.borderRadius = '4px';
        tooltip.style.zIndex = '100';
        tooltip.style.maxWidth = '300px';
        tooltip.style.wordWrap = 'break-word';
        
        // Set full document name
        tooltip.textContent = link.getAttribute('data-full-name') || link.textContent;
        
        // Append tooltip to body
        document.body.appendChild(tooltip);
        
        // Event listeners for hover
        link.addEventListener('mouseenter', (e) => {
            const rect = link.getBoundingClientRect();
            tooltip.style.top = `${rect.bottom + window.scrollY + 5}px`;
            tooltip.style.left = `${rect.left + window.scrollX}px`;
            tooltip.style.display = 'block';
        });
        
        link.addEventListener('mouseleave', () => {
            tooltip.style.display = 'none';
        });
    });
});
</script>
  
  
@endsection
