@extends('layouts.dashboard-layout')

@section('title', 'Add New Employee')

@section('content')

@if(session('success'))
<script>
    document.addEventListener("DOMContentLoaded", () => { showNotification("{{ session('success') }}"); });
    async function showNotification(message) {
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        notificationMessage.textContent = message;
        setTimeout(() => {
            notification.style.top = '20px';
            setTimeout(() => { notification.style.top = '-100px'; }, 3000);
        }, 500);
        try {
            await fetch("{{ route('notify') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ message }),
            });
        } catch (error) { console.error('Error sending notification:', error); }
    }
</script>
@endif

{{-- Validation Errors --}}
@if($errors->any())
<div class="mx-16 mt-6 bg-red-50 border border-red-300 text-red-800 p-4 rounded-xl">
    <p class="font-bold text-xl mb-2">Please fix the following errors:</p>
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li class="text-lg">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="flex flex-col items-start justify-start w-full px-16 pb-16">

    {{-- Header --}}
    <div class="w-full flex items-center justify-between pt-6 pb-2">
        <nav aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="{{ route('employee.management') }}" class="text-2xl text-[#00000080] hover:text-[#184E77]">Employee</a></li>
                <li><span class="text-[#00000080] text-2xl mx-1">›</span></li>
                <li><a href="{{ route('employee.management') }}" class="text-2xl text-[#00000080] hover:text-[#184E77]">Employee Management</a></li>
                <li><span class="text-[#00000080] text-2xl mx-1">›</span></li>
                <li><span class="text-2xl text-[#184E77] font-semibold">Add New Employee</span></li>
            </ol>
        </nav>
    </div>

    {{-- Legend --}}
    <div class="w-full flex items-center space-x-6 mb-6 mt-2">
        <div class="flex items-center space-x-2">
            <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
            <span class="text-lg text-gray-600">Required field</span>
        </div>
        <div class="flex items-center space-x-2">
            <span class="w-3 h-3 rounded-full bg-gray-400 inline-block"></span>
            <span class="text-lg text-gray-600">Optional field</span>
        </div>
    </div>

    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data" class="w-full space-y-8">
        @csrf

        {{-- SECTION 1: PROFILE & LEGAL DOCUMENTS --}}
        <div class="w-full flex space-x-8">

            {{-- Profile Card --}}
            <div class="w-1/2 bg-[#D9D9D980] rounded-3xl p-8 space-y-6">
                <h2 class="text-3xl font-bold text-black">Profile</h2>

                <div class="flex items-center space-x-6">
                    <div class="relative cursor-pointer" onclick="document.getElementById('image').click()">
                        <img id="profileImage"
                             src="{{ asset('build/assets/bg1.png') }}"
                             class="w-32 h-32 rounded-full object-cover border-4 border-white shadow">
                        <div class="absolute bottom-0 right-0 bg-[#184E77] text-white rounded-full p-1">
                            <i class="ri-camera-line text-lg"></i>
                        </div>
                    </div>
                    <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                    <p class="text-lg text-gray-500">Click photo to change<br><span class="text-gray-400 text-base">JPG, PNG, BMP — max 4MB</span></p>
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>First Name <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                           placeholder="e.g. John"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Last Name <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                           placeholder="e.g. Doe"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Employee ID <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="text" name="employee_id" value="{{ old('employee_id') }}"
                           placeholder="e.g. EMP-001"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Description <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <textarea name="description" rows="3" placeholder="Short bio or notes..."
                              class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Legal Documents Card --}}
            <div class="w-1/2 bg-[#D9D9D980] rounded-3xl p-8 space-y-4 flex flex-col">
                <div class="flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-black">Legal Documents</h2>
                    <label for="doc-files" class="flex items-center px-4 py-2 bg-[#184E77] text-white text-lg rounded-xl cursor-pointer hover:bg-[#1B5A8A]">
                        <i class="ri-upload-2-line mr-2"></i> Upload PDF
                    </label>
                </div>
                <p class="text-base text-gray-500">PDF files only &mdash; <span class="text-gray-400">optional</span></p>
                <input type="file" id="doc-files" accept="application/pdf" class="hidden" multiple />
                <input type="file" name="legal_documents[]" id="hidden-files" class="hidden" multiple />
                <ul id="file-list-items" class="space-y-2 flex-1"></ul>
            </div>
        </div>

        {{-- SECTION 2: PERSONAL INFORMATION --}}
        <div class="bg-[#D9D9D980] rounded-3xl p-8">
            <h2 class="text-3xl font-bold text-black mb-6">Personal Information</h2>
            <div class="grid grid-cols-2 gap-6">

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>
                        <span>Full Name <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}"
                           placeholder="e.g. John Michael Doe" required
                           class="w-full p-3 text-xl border-2 border-red-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 bg-red-50" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Email Address <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="e.g. john@example.com"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>NIC <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="text" name="nic" value="{{ old('nic') }}"
                           placeholder="e.g. 199012345678"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Phone Number <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="e.g. +94 77 123 4567"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Gender <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <select name="gender" class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]">
                        <option value="">— Select Gender —</option>
                        <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Date of Birth <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="col-span-2 space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Living Address <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <textarea name="address" rows="2" placeholder="e.g. 123 Main St, Colombo"
                              class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]">{{ old('address') }}</textarea>
                </div>

            </div>
        </div>

        {{-- SECTION 3: EMPLOYMENT INFORMATION --}}
        <div class="bg-[#D9D9D980] rounded-3xl p-8">
            <h2 class="text-3xl font-bold text-black mb-6">Employment Information</h2>
            <div class="grid grid-cols-2 gap-6">

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Department <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <select name="name" class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]">
                        <option value="">— Select Department —</option>
                        @foreach($departments->unique('name') as $department)
                            <option value="{{ $department->name }}" {{ old('name') == $department->name ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Branch <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <select name="branch" class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]">
                        <option value="">— Select Branch —</option>
                        @foreach($departments->unique('branch') as $department)
                            <option value="{{ $department->branch }}" {{ old('branch') == $department->branch ? 'selected' : '' }}>
                                {{ $department->branch }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Job Title <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           placeholder="e.g. Software Engineer"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Employment Type <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <select name="employment_type" class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]">
                        <option value="">— Select Type —</option>
                        <option value="full time"  {{ old('employment_type') == 'full time'  ? 'selected' : '' }}>Full Time</option>
                        <option value="part time"  {{ old('employment_type') == 'part time'  ? 'selected' : '' }}>Part Time</option>
                        <option value="contract"   {{ old('employment_type') == 'contract'   ? 'selected' : '' }}>Contract</option>
                        <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Status <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="text" name="status" value="{{ old('status') }}"
                           placeholder="e.g. Active"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                @if(!$isFirstEmployee)
                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Manager <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <select name="manager_id" class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]">
                        <option value="">— Select Manager —</option>
                        @foreach($employees->unique('employee_id') as $emp)
                            <option value="{{ $emp->id }}" {{ old('manager_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->employee_id }} — {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Employment Start Date <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="date" name="employment_start_date" value="{{ old('employment_start_date') }}"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Employment End Date <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="date" name="employment_end_date" value="{{ old('employment_end_date') }}"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Probation Start Date <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="date" name="probation_start_date" value="{{ old('probation_start_date') }}"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Probation Period (months) <span class="text-gray-400 font-normal text-base">(optional)</span></span>
                    </label>
                    <input type="number" name="probation_period" value="{{ old('probation_period') }}"
                           placeholder="e.g. 3"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

            </div>
        </div>

        {{-- SECTION 4: BANK DETAILS (all required) --}}
        <div class="bg-[#D9D9D980] rounded-3xl p-8">
            <h2 class="text-3xl font-bold text-black mb-2">Bank Details</h2>
            <p class="text-lg text-red-500 mb-6">All bank fields are required.</p>
            <div class="grid grid-cols-2 gap-6">

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>
                        <span>Account Holder Name <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text" name="account_holder_name" value="{{ old('account_holder_name') }}"
                           placeholder="e.g. John Doe" required
                           class="w-full p-3 text-xl border-2 border-red-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 bg-red-50" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>
                        <span>Bank Name <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                           placeholder="e.g. Commercial Bank" required
                           class="w-full p-3 text-xl border-2 border-red-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 bg-red-50" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>
                        <span>Account Number <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text" name="account_no" value="{{ old('account_no') }}"
                           placeholder="e.g. 1234567890" required
                           class="w-full p-3 text-xl border-2 border-red-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 bg-red-50" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-700 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>
                        <span>Branch Name <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text" name="branch_name" value="{{ old('branch_name') }}"
                           placeholder="e.g. Colombo 03" required
                           class="w-full p-3 text-xl border-2 border-red-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 bg-red-50" />
                </div>

            </div>
        </div>

        {{-- SECTION 5: EDUCATION & EXPERIENCE (all optional) --}}
        <div class="bg-[#D9D9D980] rounded-3xl p-8">
            <h2 class="text-3xl font-bold text-black mb-2">Education & Experience</h2>
            <p class="text-lg text-gray-500 mb-6">All fields in this section are optional.</p>
            <div class="grid grid-cols-2 gap-6">

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Degree</span>
                    </label>
                    <input type="text" name="degree" value="{{ old('degree') }}"
                           placeholder="e.g. Bachelor of Science"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Institution</span>
                    </label>
                    <input type="text" name="institution" value="{{ old('institution') }}"
                           placeholder="e.g. University of Colombo"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Graduation Year</span>
                    </label>
                    <input type="number" name="graduation_year" value="{{ old('graduation_year') }}"
                           placeholder="e.g. 2020" min="1950" max="2099"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Work Experience (years)</span>
                    </label>
                    <input type="text" name="work_experience_years" value="{{ old('work_experience_years') }}"
                           placeholder="e.g. 3"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Work Experience Role</span>
                    </label>
                    <input type="text" name="work_experience_role" value="{{ old('work_experience_role') }}"
                           placeholder="e.g. Junior Developer"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Work Experience Company</span>
                    </label>
                    <input type="text" name="work_experience_company" value="{{ old('work_experience_company') }}"
                           placeholder="e.g. XYZ Solutions"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

            </div>
        </div>

        {{-- SECTION 6: TRAINING & CERTIFICATION (all optional) --}}
        <div class="bg-[#D9D9D980] rounded-3xl p-8">
            <h2 class="text-3xl font-bold text-black mb-2">Training & Certification</h2>
            <p class="text-lg text-gray-500 mb-6">All fields in this section are optional.</p>
            <div class="grid grid-cols-2 gap-6">

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Course Name</span>
                    </label>
                    <input type="text" name="course_name" value="{{ old('course_name') }}"
                           placeholder="e.g. AWS Cloud Practitioner"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Training Provider</span>
                    </label>
                    <input type="text" name="training_provider" value="{{ old('training_provider') }}"
                           placeholder="e.g. Amazon Web Services"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Completion Date</span>
                    </label>
                    <input type="date" name="completion_date" value="{{ old('completion_date') }}"
                           class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]" />
                </div>

                <div class="space-y-1">
                    <label class="text-lg font-semibold text-gray-600 flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
                        <span>Certification Status</span>
                    </label>
                    <select name="certification_status" class="w-full p-3 text-xl border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#52B69A]">
                        <option value="">— Select Status —</option>
                        <option value="merit"       {{ old('certification_status') == 'merit'       ? 'selected' : '' }}>Merit</option>
                        <option value="distinction" {{ old('certification_status') == 'distinction' ? 'selected' : '' }}>Distinction</option>
                        <option value="pass"        {{ old('certification_status') == 'pass'        ? 'selected' : '' }}>Pass</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- Save / Cancel --}}
        <div class="flex items-center space-x-4 pb-8">
            <button type="submit"
                    class="flex items-center space-x-2 px-10 py-3 text-white text-2xl bg-gradient-to-r from-[#184E77] to-[#52B69A] rounded-xl shadow hover:from-[#1B5A8A] hover:to-[#60C3A8]">
                <i class="ri-add-fill text-3xl"></i>
                <span>Save Employee</span>
            </button>
            <a href="{{ route('employee.management') }}"
               class="flex items-center space-x-2 px-8 py-3 text-[#184E77] text-2xl border-2 border-[#184E77] rounded-xl hover:bg-gray-100">
                <i class="ri-close-line text-3xl"></i>
                <span>Cancel</span>
            </a>
        </div>

    </form>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('profileImage').src = e.target.result; };
        reader.readAsDataURL(file);
    }
}

let selectedFiles = new DataTransfer();

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('doc-files').addEventListener('change', function () {
        Array.from(this.files).forEach(file => {
            if (Array.from(selectedFiles.files).some(f => f.name === file.name)) {
                alert('"' + file.name + '" is already attached.');
                return;
            }
            selectedFiles.items.add(file);
            addFileToList(file.name);
        });
        syncHiddenInput();
        this.value = '';
    });
});

function addFileToList(fileName) {
    const li = document.createElement('li');
    li.className = 'flex items-center space-x-3 py-2 border-b border-gray-200';
    li.id = 'file-' + fileName.replace(/[^a-z0-9]/gi, '_');
    li.innerHTML =
        '<i class="ri-file-pdf-2-fill text-red-500 text-2xl"></i>' +
        '<span class="text-gray-700 text-lg flex-1">' + fileName + '</span>' +
        '<button type="button" onclick="removeFile(\'' + fileName.replace(/'/g, "\\'") + '\')" class="text-red-400 hover:text-red-600 text-xl">&#10006;</button>';
    document.getElementById('file-list-items').appendChild(li);
}

function removeFile(fileName) {
    const dt = new DataTransfer();
    Array.from(selectedFiles.files).forEach(f => { if (f.name !== fileName) dt.items.add(f); });
    selectedFiles = dt;
    const li = document.getElementById('file-' + fileName.replace(/[^a-z0-9]/gi, '_'));
    if (li) li.remove();
    syncHiddenInput();
}

function syncHiddenInput() {
    document.getElementById('hidden-files').files = selectedFiles.files;
}
</script>

@endsection
