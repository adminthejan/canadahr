

@extends('layouts.dashboard-layout')

@section('title', 'EPF/ETF Management')

@section('content')
@if($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
<form method="POST" action="{{ route('contribution.store') }}" class="p-0 m-0">
    @csrf
<div class="flex flex-col items-start justify-start w-full px-4">

    <div class="w-full pt-2">
      <div class="flex items-center justify-between w-full">
        <div class="flex ">
        <p class="text-6xl font-bold text-black nunito-">Contribution</p>
        </div>
        <div class="flex items-center space-x-4">

    
        <!-- Add Employee Button -->
        <button class="flex items-center justify-center nunito- space-x-2 px-8 py-2 text-white text-2xl bg-gradient-to-r from-[#184E77] to-[#52B69A] rounded-xl shadow-sm hover:from-[#1B5A8A] hover:to-[#60C3A8]">
        <p class="text-3xl"><i class="ri-add-fill"></i></p>
            <span>Save</span>
        </button>
        </div>
    
      </div>
    </div>
    <nav class="flex py-3" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-3 nunito-">
        <li class="inline-flex items-center">
          <a href="#" class="inline-flex items-center text-3xl font-medium text-[#00000080] hover:text-blue-600">
          Contribution
          </a>
        </li>
        <li>
          <div class="flex items-center">
            <p class="text-[#00000080] text-3xl"><i class="ri-arrow-right-wide-line"></i></p>
            <a href="#" class="ml-1 font-medium text-[#00000080] text-3xl hover:text-blue-600">Add New Record</a>
          </div>
        </li>
      </ol>
    </nav>
    <div class="w-full flex flex-col justify-center items-center space-y-4">
    <div class="w-full flex justify-start items-center space-x-12 nunito-"> 
          <div class="w-full mx-auto p-6 bg-[#D9D9D966] rounded-3xl p-8 space-y-4">
          <div class="w-full flex justify-start items-center pl-16">
              <p class="text-3xl font-bold text-black">Employee Details :</p>
            </div>
            <div class="w-full flex space-x-48 px-16">
              <!-- Employee ID -->
              <div class="w-full">
                <label for="employee_id" class="block text-xl text-black font-bold">Employee ID :</label>
                <input
                  type="text"
                  id="employee_id"
                  name="employee_id"
                  placeholder="Enter your Employee ID"
                  class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl"
                />
              </div>
              <div class="w-full">
                <label for="basic_salary" class="block text-xl text-black font-bold">Basic Salary :</label>
                <input
                  type="number"
                  id="basic_salary"
                  name="basic_salary"
                  placeholder="Enter basic salary"
                  oninput="calculateContributions()"
                  class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl"
                />
              </div>

            </div>
                                 <!-- Contribution Details -->
        <div id="contribution_details" class="hidden w-full mt-4 bg-[#F3F4F6] p-8 rounded-xl">
            <p class="text-xl font-bold text-black mb-2">Contribution Details:</p>
            <div class="flex justify-between">
              <div class="text-lg font-semibold text-gray-800">
                <span>EPF (Employee - 8%): </span>
                <span id="epf_employee" class="text-gray-900"> 0.00</span>
              </div>
          
            </div>
         
              <div class="text-lg font-semibold text-gray-800">
                <span>EPF (Employer - 12%): </span>
                <span id="epf_employer" class="text-gray-900"> 0.00</span>
              </div>
          
            <div class="text-lg font-semibold text-gray-800">
                <span>ETF (Employer - 3%):</span>
                <span id="etf_employer" class="text-gray-900"> 0.00</span>
              </div>
              <div class="text-lg font-semibold text-gray-800">
                <span>Net Salary (After 8% EPF):</span>
                <span id="net_salary" class="text-gray-900"> 0.00</span>
              </div>
            </div>
          </div>
          </div>
         
        </div>
        <div class="w-full mt-4 mx-auto bg-[#D9D9D966] rounded-3xl p-8 space-y-4 nunito-">
            <div class="w-full flex justify-start items-center">
              <p class="text-3xl font-bold text-black pl-16">Contribution Details :</p>
            </div>
            <div class="w-full flex justify-between items-center space-x-48 px-16">
              <div class="w-1/3 flex flex-col space-y-4">
                 <!-- Claim Date -->
              <div>
                <label for="epf_number" class="block text-xl text-black font-bold">EPF number :</label>
                <input
                  type="number"
                  id="epf_number"
                  name="epf_number"
                  placeholder="Enter the number here"
                  class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] text-[#0000008C] font-bold rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xl"
                />
              </div>
               <!-- Amount -->
               <div>
                <label for="etf_number" class="block text-xl text-black font-bold">ETF number :</label>
                <input
                  type="number"
                  id="etf_number"
                  name="etf_number"
                  placeholder="Enter the number here"
                  class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xl"
                />
              </div>
    
              </div>
            

            </div>
            </div>
          
   
    </div>
  </div>
  </form>

  
  <script>document.getElementById('add-deduction').addEventListener('click', function () {
    const description = document.getElementById('deduction-description').value;
    const amount = document.getElementById('deduction-amount').value;
  
    if (description && amount) {
      const list = document.getElementById('deduction-list');
      const item = document.createElement('div');
      item.className = 'flex justify-between items-center p-2 bg-gray-100 rounded-xl mt-2';
      item.innerHTML = `
        <span class="text-xl font-bold">${description} - LKR ${amount}</span>
        <button type="button" class="text-red-500" onclick="this.parentElement.remove(); removeHiddenInput(this);">&times;</button>
      `;
      list.appendChild(item);
  
        // Create hidden inputs for form submission
        const form = document.querySelector('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'deductions[]'; // Group description and amount in one entry
        hiddenInput.value = JSON.stringify({ description, amount }); // Use JSON to group data
        hiddenInput.className = 'deduction-hidden';

  
    
      item.appendChild(hiddenInput);
    }
  });
  
  document.getElementById('add-allowance').addEventListener('click', function () {
    const description = document.getElementById('allowance-description').value;
    const amount = document.getElementById('allowance-amount').value;
  
    if (description && amount) {
      const list = document.getElementById('allowance-list');
      const item = document.createElement('div');
      item.className = 'flex justify-between items-center p-2 bg-gray-100 rounded-xl mt-2';
      item.innerHTML = `
        <span class="text-xl font-bold">${description} - LKR ${amount}</span>
        <button type="button" class="text-red-500" onclick="this.parentElement.remove(); removeHiddenInput(this);">&times;</button>
      `;
      list.appendChild(item);
  
      // Create hidden inputs for form submission
      const form = document.querySelector('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'allowances[]'; // Group description and amount in one entry
        hiddenInput.value = JSON.stringify({ description, amount }); // Use JSON to group data
        hiddenInput.className = 'allowance-hidden';
  
      item.appendChild(hiddenInput);
    }
  });
  
  // Function to remove associated hidden inputs
  function removeHiddenInput(button) {
    const hiddenInputs = button.parentElement.querySelectorAll('input[type="hidden"]');
    hiddenInputs.forEach(input => input.remove());
  }
  
  function calculateContributions() {
    const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;

    // Calculate EPF and ETF
    const epfEmployee = (basicSalary * 8) / 100; // 8% Employee EPF
    const epfEmployer = (basicSalary * 12) / 100; // 12% Employer EPF
    const etfEmployer = (basicSalary * 3) / 100;  // 3% Employer ETF

    // Calculate Net Salary
    const netSalary = basicSalary - epfEmployee; // Basic Salary after deducting 8% EPF

    // Update Contribution Details
    document.getElementById('epf_employee').innerText = epfEmployee.toFixed(2);
    document.getElementById('epf_employer').innerText = epfEmployer.toFixed(2);
    document.getElementById('etf_employer').innerText = etfEmployer.toFixed(2);
    document.getElementById('net_salary').innerText = netSalary.toFixed(2);

    // Show Contribution Details if Basic Salary is entered
    const contributionDetails = document.getElementById('contribution_details');
    if (basicSalary > 0) {
      contributionDetails.classList.remove('hidden');
    } else {
      contributionDetails.classList.add('hidden');
    }
  }
  </script>
  @endsection




