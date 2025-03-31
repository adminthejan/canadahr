@extends('layouts.dashboard-layout')

@section('title', 'Payroll details')

@section('content')

    <div id="content-section" class="flex flex-col items-start justify-start w-full px-2">

    <div class="w-full pt-1">
      <div class="flex items-center justify-between w-full">
        <div class="flex ">
        <p class="text-6xl font-bold text-black nunito-">Payroll</p>
        </div>
        <div class="flex items-center space-x-4">

    
        <!-- Add Employee Button -->
        <button class="flex items-center justify-center nunito- space-x-2 px-8 py-2 text-white text-2xl bg-gradient-to-r from-[#184E77] to-[#52B69A] rounded-xl shadow-sm hover:from-[#1B5A8A] hover:to-[#60C3A8]">
        <p class="text-3xl"><i class="ri-add-fill"></i></p>
            <span>Edit Payslip</span>
        </button>



                <!-- Print Button -->
                <button id="print-button" class="flex items-center justify-center nunito- space-x-2 px-8 py-2 text-white text-2xl bg-gradient-to-r from-[#1B5A8A] to-[#60C3A8] rounded-xl shadow-sm hover:from-[#184E77] hover:to-[#52B69A]">
                  <p class="text-3xl"><i class="ri-printer-fill"></i></p>
                  <span>Print Payslip</span>
              </button>
        </div>
    
      </div>
    </div>
    <nav class="flex py-3" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-3 nunito-">
        <li class="inline-flex items-center">
          <a href="#" class="inline-flex items-center text-3xl font-medium text-[#00000080] hover:text-blue-600">
          Payroll
          </a>
        </li>
        <li>
          <div class="flex items-center">
            <p class="text-[#00000080] text-3xl"><i class="ri-arrow-right-wide-line"></i></p>
            <a href="#" class="ml-1 font-medium text-[#00000080] text-3xl hover:text-blue-600">Employee Payslip</a>
          </div>
        </li>
      </ol>
    </nav>
    <div class="w-full flex justify-between items-center nunito- pt-8">
    <div class="w-1/2 flex flex-col space-y-8">
        <div class="w-full flex flex-col space-y-4">
            <p class="text-2xl text-[#00000099] font-bold">Employee Name :</p>
            <p class="text-2xl text-black font-bold">{{$payroll->employee->first_name}} {{$payroll->employee->last_name}}</p>
        </div>
        <div class="w-full flex flex-col space-y-4">
            <p class="text-2xl text-[#00000099] font-bold">Employee ID :</p>
            <p class="text-2xl text-black font-bold">{{$payroll->employee->id}} </p>
        </div>
        <div class="w-full flex flex-col space-y-4">
            <p class="text-2xl text-[#00000099] font-bold">Employee Department :</p>
            <p class="text-2xl text-black font-bold">UI / UX Department</p>
        </div>
        <div class="w-full flex flex-col space-y-4">
            <p class="text-2xl text-[#00000099] font-bold">Total work hours :</p>
            <p class="text-2xl text-black font-bold">{{$payroll->total_hours}}</p>
        </div>
        
    </div>
    <div class="w-1/2 flex flex-col justify-end items-end space-y-8">
        <div class="w-1/3 flex flex-col space-y-4 justify-end">
            <p class="text-2xl text-[#00000099] font-bold">Account Holder Name :</p>
            <p class="text-2xl text-black font-bold ">{{$payroll->employee->account_holder_name}}</p>
        </div>
        <div class="w-1/3 flex flex-col space-y-4">
            <p class="text-2xl text-[#00000099] font-bold">Account No :</p>
            <p class="text-2xl text-black font-bold">{{$payroll->employee->account_no}}</p>
        </div>
        <div class="w-1/3 flex flex-col space-y-4">
            <p class="text-2xl text-[#00000099] font-bold">Bank :</p>
            <p class="text-2xl text-black font-bold">{{$payroll->employee->bank_name}}</p>
        </div>
        <div class="w-1/3 flex flex-col space-y-4">
            <p class="text-2xl text-[#00000099] font-bold">Branch :</p>
            <p class="text-2xl text-black font-bold">{{$payroll->employee->branch_name}}</p>
        </div>
    
    </div>
    </div>
    <table class="w-full border border-[#00000033] nunito- mt-8" style="border-spacing: 0 10px; table-layout: fixed; width: 100%;">
      <thead>
        <tr class="bg-white border-b border-[#00000033]">
          <th class="text-xl text-black font-bold px-4 py-2 bg-[#D9D9D9] text-left align-middle border-r border-[#00000033]">Description</th>
          <th class="text-xl text-black font-bold px-4 py-2 bg-[#D9D9D9] text-right align-middle border-r border-[#00000033]">Earnings</th>
          <th class="text-xl text-black font-bold px-4 py-2 bg-[#D9D9D9] text-right align-middle">Deductions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-xl text-black px-4 py-2 pt-8 text-left align-middle">
            Salary Amount
          </td>
          <td class="text-xl text-black px-4 py-2 pt-8 text-right align-middle">{{$payroll->net_salary}}</td>
          <td class="text-xl text-black px-4 py-2 pt-8 text-right align-middle"></td>
        </tr>
        <tr>
          <td class="text-xl text-black px-4 py-2 text-left align-middle">
            Basic Salary
          </td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle">{{$payroll->basic_salary}}</td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle"></td>
        </tr>
        @foreach ($payroll->allowances as $allowance)
        <tr>
          <td class="text-xl text-black px-4 py-2 text-left align-middle">
            {{ $allowance->description }}
          </td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle">{{ number_format($allowance->amount, 2) }} /=</td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle"></td>
        </tr>
        @endforeach
        <tr>
          <td class="text-xl text-black px-4 py-2 text-left align-middle">
          Bonus</td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle">{{ number_format($payroll->bonus, 2) }} /=</td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle"></td>
        </tr>
        <tr>
          <td class="text-xl text-black px-4 py-2 text-left align-middle">
          Loan
          </td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle"></td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle">{{ number_format($payroll->bonus, 2) }} /=</td>
        </tr>
        @foreach ($payroll->deductions as $deduction)
        <tr>
          <td class="text-xl text-black px-4 py-2 text-left align-middle">
            {{ $deduction->description }}
          </td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle"></td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle">{{ number_format($deduction->amount, 2) }} /=</td>
        </tr>
        @endforeach
        <tr class="border-t border-[#00000033]">
          <td class="text-xl text-black px-4 py-2 text-left align-middle">
          Tax
          </td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle"></td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle">{{ number_format($payroll->tax, 2) }} /=</td>
        </tr>
        <tr class="border border-[#00000033]">
          <td class="text-xl text-black px-4 py-2 text-left align-middle">
          Total
          </td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle">{{ number_format($payroll->payable, 2) }} /=</td>
          <td class="text-xl text-black px-4 py-2 text-right align-middle"></td>
        </tr>
      </tbody>
    </table>
    
    </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('print-button').addEventListener('click', printPayslip);
});

function printPayslip() {
    const contentToPrint = document.getElementById('content-section');
    if (!contentToPrint) {
        console.error('Content section not found. Ensure the ID matches the element.');
        return;
    }
    const originalContent = document.body.innerHTML; // Save the original page content

    document.body.innerHTML = contentToPrint.outerHTML; // Replace body with only the content section
    window.print(); // Trigger the print dialog
    document.body.innerHTML = originalContent; // Restore the original page content
    location.reload(); // Reload the page to reinitialize scripts and styles
}

  </script>
  
    <style>
       td {
        position: relative; /* Required for pseudo-element positioning */
      }
    
      td::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 1px; /* Line thickness */
        background-color: #00000033; /* Line color */
      }
    
      /* Remove the line for the last column to avoid duplicate lines */
      tr td:last-child::after {
        display: none;
      }
    </style>
    
@endsection
