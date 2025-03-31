@extends('layouts.dashboard-layout')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@section('title', 'Settings Management ')

@section('content')


<form method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data" class="flex flex-col items-start justify-start w-full md:px-16">
  @csrf
  @method('PUT')

<div class="flex flex-col items-start justify-start w-full px-1">

<div class="w-full pt-2">
<div class="flex items-center justify-between w-full">
  <div class="flex">
    <p class="text-6xl font-bold text-black nunito-">Settings</p>
  </div>
  <div class="ml-auto">
    <button class="bg-[#184E77] flex flex-col text-white px-8 py-3 rounded-xl hover:bg-blue-700 focus:ring focus:ring-blue-300">
      <div class="w-full flex space-x-4">
        <p class="text-xl">Save</p>
      </div>
    </button>
  </div>
</div>
</div>



<div class="w-full flex flex-col pt-8 space-y-2 nunito-">
<p class="text-4xl text-black font-bold">Personal Info</p>
<p class="text-2xl text-black">Update your profile, contact details and preferences to personalize your experience.</p>
</div>
<div class="w-full pt-8 nunito- md:px-16">
<div class="w-full flex justify-between space-x-8">
<div class="w-2/3 flex space-x-8">
  <!-- Profile Picture -->
<div class="relative flex justify-center items-center">
  <img
    id="profileImage"
    src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('/bg1.png') }}"
    alt="Profile Picture"
    class="md:w-36 md:h-36 w-20 h-16 rounded-full object-cover"
  />
</div>
<!-- Details and Buttons -->
<div class="flex flex-col justify-start items-start pt-8 space-y-4">
  <h3 class="text-3xl font-bold text-black">Profile Picture</h3>
  <p class="text-black text-2xl">Upload your image here.</p>
  
</div>
</div>

<div class="mt-2 flex space-x-8 items-end">
    <!-- Upload Button -->
    <button type="button"
      class="bg-[#184E77] flex flex-col text-white px-8 py-3 rounded-xl hover:bg-blue-700 focus:ring focus:ring-blue-300" onclick="triggerFileInput()"
    >
    <div class="w-full flex space-x-4">
    <p class="text-xl"><i class="ri-upload-2-line"></i></p>
    <p class="text-xl" >Upload Image</p>
    </div>
    </button>
    <input
          type="file"
          id="image"
          name="profile_image"
          style="display: none"
          accept="image/*"
          onchange="previewImage(event)"
      />
    <!-- Remove Button -->
    <button
      class="bg-gray-100 text-gray-600 px-8 py-3 rounded-xl hover:bg-gray-200 focus:ring focus:ring-gray-300" 
    >
    <p class="text-xl text-black font-bold">Remove</p>
    </button>
  </div>
</div>
<div class="w-full mx-auto py-8 px-16 bg-[#D9D9D966] rounded-xl nunito- mt-8">
      <!-- Full Name -->
  <div>
    <label for="name" class="block text-black text-xl font-bold">Full Name:</label>
    <input
      type="text"
      id="name"
      name = "name"
      value="{{ old('name', $user->name) }}"
      placeholder="Enter your full name"
      class="mt-1 mb-4 block w-2/3 px-4 py-3 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder:text-xl placeholder:text-[#0000008C]"
      disabled
    />
  </div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  

  <!-- Email -->
  <div>
    <label for="email" class="block text-xl text-black font-bold">Email:</label>
    <input
      type="email"
      id="email"
      name = "email"
      value="{{ old('email', $user->email) }}"
      placeholder="Enter your email"
      class="mt-1 block w-2/3 px-4 py-3 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder:text-[#0000008C] placeholder:text-xl"
      disabled
    />
  </div>

  <div class="flex items-center justify-center">
    <button id="changeEmailButton"  type="button" class="inline-flex items-center px-4 py-2 border border-[#D9D9D980] rounded-xl bg-[#D9D9D966] shadow-sm text-black font-bold hover:bg-gray-50"  onclick="changeEmail()">
      Change Email
    </button>
  </div>

  

  <!-- Contact Number -->
  <div>
    <label for="contactNumber" class="block text-xl font-bold text-black">Contact Number:</label>
    <input
      type="text"
      id="contactNumber"
      name = "contactNumber"
      value="{{ old('contactNumber', $user->contactNumber) }}"
      placeholder="Enter your contact number"
      class="mt-1 block w-2/3 px-4 py-3 border-2 border-[#1C1B1F80] rounded-md shadow-sm placeholder:text-xl focus:ring-blue-500 focus:border-blue-500 placeholder:text-[#0000008C]"
      disabled
    />
  </div>

  <div class="flex items-center justify-center">
    <button id="changeContactButton"  type="button" class="inline-flex items-center px-4 py-2 border border-[#D9D9D980] rounded-md bg-[#D9D9D966] shadow-sm text-black font-bold hover:bg-gray-50" onclick="changeContact()">
      Change contact number
    </button>
  </div>

  <div>
    <label for="company_name" class="block text-black text-xl font-bold">Company Name:</label>
    <input
      type="text"
      id="company_name"
      name = "company_name"
      value="{{ old('company_name', $user->company_name) }}"
      placeholder="Enter your Company Name"
      class="mt-1 mb-4 block w-2/3 px-4 py-3 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder:text-xl placeholder:text-[#0000008C]"
    />
  </div>

</div>
</div>
</form> 



<form method="POST" action="{{ route('new-password.update') }}" enctype="multipart/form-data" >
  @csrf
  @method('PUT')

<div class="w-full border border-[#00000080] mt-8"></div>
<div class="w-full flex flex-col pt-8 space-y-2 nunito-">
<p class="text-4xl text-black font-bold">Password</p>
<p class="text-2xl text-black">Modify your current password</p>
</div>

<div class="w-full flex items-center justify-center space-x-12 bg-gray-100 py-8 px-12 mt-8 rounded-xl">
<!-- Current Password -->
<div class="w-full flex flex-col items-start space-y-2 mr-8">
<label for="password" class="text-xl text-black font-bold">Current Password :</label>
<div class="relative w-2/3">
  <span class="absolute inset-y-0 left-0 flex items-center pl-3">
    <i class="ri-lock-line"></i>
  </span>
  <input
    type="password"
    id="password"
    name="password"
    class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-xl"
  />
  <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3">
    <i class="ri-eye-fill"></i>
  </button>
  
</div>

</div>

<!-- New Password -->
<div class="w-full flex flex-col items-start space-y-2">
  <label for="new_password" class="text-xl text-black font-bold">New Password :</label>
  <div class="relative w-2/3">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
    <i class="ri-lock-line"></i>
    </span>
    <input
      type="password"
      id="new_password"
      name="new_password"
      class="w-full pl-10 pr-10 py-2 text-xl border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
    />
    
  </div>
  
</div>
</div>
<div class="flex justify-end mt-4">
  <button  class="bg-[#184E77] text-white px-4 py-2 rounded-xl hover:bg-blue-700" >
      Save Password
  </button>
</div>
</form>


<div class="w-full border border-[#00000080] mt-8"></div>

<div class="w-full flex flex-col pt-8 space-y-2 nunito-">
<p class="text-4xl text-black font-bold">Notification Settings</p>
<p class="text-2xl text-black">Manage your current notification settings.</p>
</div>
<div class="w-full flex bg-[#D9D9D966] rounded-xl py-8 px-12 mt-8">
<div class="w-5/6">
<p class="text-2xl font-bold text-black">Message Notifications</p>
<p class="text-xl">Get messages to find out what’s going on when you’re not online.</p>
<p class="text-xl">You can turn this off.</p>
</div>
<div class="w-1/6 flex items-center justify-center">
<label class="relative inline-flex items-center cursor-pointer">
  <input type="checkbox" class="sr-only peer">
  <div class="w-10 h-6 bg-[#184E77] rounded-full peer dark:bg-blue-600 peer-checked:after:translate-x-4 peer-checked:after:bg-gray-300 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
</label>
</div>

</div>
<div class="w-full flex flex-col pt-8 space-y-2 nunito-">
<p class="text-4xl text-black font-bold">Account Security </p>
<p class="text-2xl text-black">Manage your account security.</p>
</div>
<div class="flex space-x-4 py-8">
<!-- Log out button -->
<form method="POST" action="{{ route('logout') }}">
  @csrf
  <button class="flex items-center space-x-4 px-4 py-2 border border-[#D9D9D980] bg-gray-100 text-black font-medium rounded-xl hover:bg-gray-200 focus:outline-none">
      <p><i class="ri-logout-box-r-line"></i></p>
      <p class="text-black text-xl font-bold">Logout</p>
  </button>
</form>


<!-- Delete my account button -->
<form method="POST" action="{{ route('account.delete') }}">
  @csrf
  @method('DELETE') <!-- Use the DELETE method to follow RESTful conventions -->
  <button type="submit" class="flex items-center space-x-4 px-4 py-2 border border-[#D9D9D980] bg-gray-100 text-red-600 font-medium rounded-xl hover:bg-red-50 focus:outline-none">
      <p><i class="ri-delete-bin-5-line"></i></p>
      <p class="text-xl font-bold">Delete my account</p>
  </button>
</form>

</div>

</div>
</div>


<script>


function triggerFileInput() {
    document.getElementById('image').click();
}

// Preview the selected image in the img element
function previewImage(event) {
    const file = event.target.files[0];  // Get the selected file
    if (file) {
        const reader = new FileReader();  // Create a FileReader object
        reader.onload = function(e) {
            // Update the profile image with the selected file
            document.getElementById('profileImage').src = e.target.result;
        };
        reader.readAsDataURL(file);  

    }
}

// Show the email input as editable when the "Change Email" button is clicked
function changeEmail() {
    const emailInput = document.getElementById('email');
    const newEmailDiv = document.getElementById('email');
    
    // Enable the email input field and show the save button
    emailInput.disabled = false;  // Make the email input field editable
    emailInput.focus();  // Focus on the email input for immediate editing

    // Hide the 'Change Email' button
    document.getElementById('changeEmailButton').style.display = 'none';

    // Show the save button for saving the new email
    newEmailDiv.style.display = 'block';
}

function changeContact() {
    const contactInput = document.getElementById('contactNumber');
    const newContactDiv = document.getElementById('contactNumber');
    
    // Enable the email input field and show the save button
    contactInput.disabled = false;  // Make the email input field editable
    contactInput.focus();  // Focus on the email input for immediate editing

    // Hide the 'Change Email' button
    document.getElementById('changeContactButton').style.display = 'none';

    // Show the save button for saving the new email
    newContactDiv.style.display = 'block';
}

document.getElementById('toggle-password').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('ri-eye-fill', 'ri-eye-off-fill');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('ri-eye-off-fill', 'ri-eye-fill');
    }
});

// Prevent form submission if passwords don't meet requirements
document.querySelector('form').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    if (newPassword.length < 8) {
        e.preventDefault();
        alert('New password must be at least 8 characters long');
    }
});

</script>  
@endsection
