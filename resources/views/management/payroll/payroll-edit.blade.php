@extends('layouts.dashboard-layout')

@section('title', 'Edit Payroll')

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
<form method="POST" action="{{ route('payroll.update', $record->id) }}" class="p-0 m-0">
    @csrf
    @method('PUT')
    <div class="flex flex-col items-start justify-start w-full px-4">
        <div class="w-full pt-2">
            <div class="flex items-center justify-between w-full">
                <div class="flex ">
                    <p class="text-6xl font-bold text-black nunito-">Edit Payroll</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="flex items-center justify-center nunito- space-x-2 px-8 py-2 text-white text-2xl bg-gradient-to-r from-[#184E77] to-[#52B69A] rounded-xl shadow-sm hover:from-[#1B5A8A] hover:to-[#60C3A8]">
                        <p class="text-3xl"><i class="ri-save-fill"></i></p>
                        <span>Save Changes</span>
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
                        <a href="#" class="ml-1 font-medium text-[#00000080] text-3xl hover:text-blue-600">Edit Record</a>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="w-full flex flex-col justify-center items-center space-y-4">
            <!-- Employee Details -->
            <div class="w-full mx-auto p-6 bg-[#D9D9D966] rounded-3xl p-8 space-y-4">
                <div class="w-full flex justify-start items-center pl-16">
                    <p class="text-3xl font-bold text-black">Employee Details :</p>
                </div>
                <div class="w-full flex space-x-48 px-16">
                    <div class="w-full">
                        <label for="employee_id" class="block text-xl text-black font-bold">Employee ID :</label>
                        <input type="text" id="employee_id" name="employee_id" value="{{ $record->employee_id }}" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                    </div>
                    <div class="w-full">
                        <label for="employee_name" class="block text-xl text-black font-bold">Employee Name :</label>
                        <input type="text" id="employee_name" name="employee_name" value="{{ $record->employee_name }}" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                    </div>
                </div>
                <div class="w-full flex space-x-48 px-16">
                    <div class="w-full">
                        <label for="pay_date" class="block text-xl text-black font-bold">Pay Date :</label>
                        <input type="date" id="pay_date" name="pay_date" value="{{ $record->pay_date }}" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                    </div>
                    <div class="w-full">
                        <label for="payed_month" class="block text-xl text-black font-bold">Paid Month :</label>
                        <input type="month" id="payed_month" name="payed_month" value="{{ $record->payed_month }}" placeholder="Enter Paid Month" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                    </div>
                </div>
            </div>

            <!-- Salary and Allowances Section -->
            <div class="w-full mx-auto bg-[#D9D9D966] rounded-3xl p-8 space-y-4">
                <div class="w-full flex justify-start items-center">
                    <p class="text-3xl font-bold text-black pl-16">Salary and Allowances :</p>
                </div>
                <div class="w-full flex justify-between items-center space-x-48 px-16">
                    <div class="w-1/2 flex flex-col space-y-4">
                        <div>
                            <label for="basic" class="block text-xl text-black font-bold">Basic Salary :</label>
                            <input type="number" id="basic" name="basic" value="{{ $record->basic }}" oninput="calculateNetSalary()" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                        </div>
                        <div>
                            <input type="hidden" id="net" name="net" value="{{ $record->net_salary }}" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                        </div>
                        <div>
                            <label for="budget_allowance" class="block text-xl text-black font-bold">Budget Allowance :</label>
                            <input type="number" id="budget_allowance" name="budget_allowance" value="{{ $record->budget_allowance }}" oninput="calculateNetSalary()" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                        </div>

                        <div>
                            <label for="ot_hours" class="block text-xl text-black font-bold">OT Hours :</label>
                            <input type="number" id="ot_hours" name="ot_hours" value="{{ $record->ot_hours ?? 0 }}" oninput="calculateOTPayment()" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl text-xl" />
                        </div>

                        <div>
                            <label for="ot_payment" class="block text-xl text-black font-bold">OT Payment Amount :</label>
                            <input type="number" id="ot_payment" name="ot_payment" value="{{ $record->ot_payment ?? 0 }}" value="0" readonly class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl text-xl bg-gray-200" />
                        </div>
                    </div>
                    <div class="w-1/2 flex flex-col space-y-4">
                        <div>
                            <label for="advance_payment" class="block text-xl text-black font-bold">Advance Payment :</label>
                            <input type="number" id="advance_payment" name="advance_payment" value="{{ $record->advance_payment }}" oninput="calculateNetSalary()" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                        </div>
                        <div>
                            <label for="loan_payment" class="block text-xl text-black font-bold">Loan Payment :</label>
                            <input type="number" id="loan_payment" name="loan_payment" value="{{ $record->loan_payment }}" oninput="calculateNetSalary()" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deductions Section -->
            <div class="w-full mx-auto bg-[#D9D9D966] rounded-3xl p-8 space-y-4">
                <div class="w-full flex justify-start items-center">
                    <p class="text-3xl font-bold text-black pl-16">Deductions :</p>
                </div>
                <div class="w-full flex justify-between items-center space-x-48 px-16">
                    <div class="w-1/2 flex flex-col space-y-4">
                        <div>
                            <label for="stamp_duty" class="block text-xl text-black font-bold">Stamp Duty :</label>
                            <input type="number" id="stamp_duty" name="stamp_duty" value="{{ $record->stamp_duty }}" oninput="calculateNetSalary()" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                        </div>
                        <div>
                            <label for="no_pay" class="block text-xl text-black font-bold">No Pay :</label>
                            <input type="number" id="no_pay" name="no_pay" value="{{ $record->no_pay }}" oninput="calculateNetSalary()" class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                        </div>
                    </div>
                    <div class="w-1/2 flex flex-col space-y-4">
                        <div>
                            <label for="net_salary" class="block text-xl text-black font-bold">Net Salary :</label>
                            <input type="number" id="net_salary" name="net_salary" value="{{ $record->net_salary }}" readonly class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 text-xl" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function calculateNetSalary() {
        const basic = parseFloat(document.getElementById('basic').value) || 0;
        const budgetAllowance = parseFloat(document.getElementById('budget_allowance').value) || 0;
        const advancePayment = parseFloat(document.getElementById('advance_payment').value) || 0;
        const loanPayment = parseFloat(document.getElementById('loan_payment').value) || 0;
        const stampDuty = parseFloat(document.getElementById('stamp_duty').value) || 0;
        const noPay = parseFloat(document.getElementById('no_pay').value) || 0;
        const otpay = parseFloat(document.getElementById('ot_payment').value) || 0;

        const totalDeductions = advancePayment + loanPayment + stampDuty + (noPay * 1000);
        const netSalary = basic + budgetAllowance + otpay- totalDeductions;

        document.getElementById('net_salary').value = netSalary.toFixed(2);
    }

    function calculateOTPayment() {
        const basic = parseFloat(document.getElementById('basic').value) || 0;
        const budgetAllowance = parseFloat(document.getElementById('budget_allowance').value) || 0;

        const gross = basic+budgetAllowance;
        const otHours = parseFloat(document.getElementById('ot_hours').value) || 0;
        const otRate = 0.0041667327; // 0.41667327% as a decimal
        const otPayment = otHours * (gross * 1.5 * otRate);
        document.getElementById('ot_payment').value = otPayment.toFixed(2);

        calculateNetSalary();
    }
</script>
@endsection
