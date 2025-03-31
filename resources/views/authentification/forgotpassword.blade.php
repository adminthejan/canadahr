@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<section class="w-full h-screen flex">
<div class="relative">
    <!-- Background Image -->
    <img src="bg1.png" class="w-[800px] h-full">

    <!-- Overlay -->
    <div class="w-full hidden md:flex flex-col items-center justify-center space-y-8 bg-[#D9D9D966]">
        <!-- Layered Box -->
        <div class="w-[350px] h-[300px] absolute bg-[#D9D9D966] top-[30%] left-[28%] rounded-3xl shadow-md">
        <div class="w-full flex flex-col justify-center items-center pt-24">
            <!-- Text Content -->
            <div class="w-full flex flex-col justify-center items-center space-y-2">
                <p class="text-white text-5xl font-bold nunito-">Digital Platform for</p>
                <p class="text-black text-5xl nunito- font-bold">Human Resources. </p>
                </div>
                <div class="w-full flex flex-col justify-center items-center space-y-4 pt-4">
                    <p class="text-3xl nunito- text-[#00000099]">Simplifying Human</p>
                    <p class="text-3xl nunito- text-[#00000099]">Resources Management</p>
                    <p class="text-3xl nunito- text-[#00000099]">for your Company</p>
                </div>
        </div>
    </div>
    
</div>
<!-- Visible only on mobile -->
<div class="w-full h-full flex flex-col justify-center items-center bg-[#D9D9D966] absolute inset-0 md:hidden">
    <!-- Forgot Password Header -->
    <div class="w-full flex flex-col justify-start items-center space-y-4 pt-8 absolute inset-0">
        <p class="text-6xl nunito- text-black font-bold">Forgot Password</p>
        <p class="text-2xl text-[#00000099] nunito-">No worries, we’ll send you instructions for reset.</p>
    </div>

    <!-- Form Section -->
    <div class="w-full p-8 absolute inset-0 mt-44 px-12">
        @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
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

        <form method="POST" action="{{ route('sendresetlink') }}" class="mt-6">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-xl font-bold text-black nunito">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" class="w-full px-4 py-4 mt-1 placeholder-[#0000008C] border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
            </div>

            <div class="w-full space-y-4 pt-8">
                <button type="submit" class="w-full px-4 py-4 text-white text-2xl font-bold nunito- rounded-xl bg-gradient-to-r from-[#184E77] to-[#52B69A] hover:opacity-90 focus:outline-none focus:ring focus:ring-[#52B69A]">
                    Reset Password
                </button>
                <a href="{{ route('login') }}" class="w-full inline-block text-center px-4 py-4 text-2xl font-bold nunito- rounded-xl bg-gradient-to-r from-[#184E7780] to-[#52B69A80] text-transparent bg-clip-text border-2 border-[#52B69A80] hover:opacity-90 focus:outline-none focus:ring focus:ring-[#52B69A]">
                    Back
                </a>
            </div>
        </form>
    </div>
</div>


</div>
    <div class="w-1/2 h-full bg-white hidden md:flex flex-col justify-center items-center">
        <div class="w-full flex flex-col justify-center items-center space-y-4">
            <p class="text-6xl nunito- text-black font-bold">Forgot Password</p>
            <p class="text-2xl text-[#00000099] nunito-">No worries, we’ll send you instructions for reset.</p>
        </div>

        
        <div class="w-1/2 p-8">
        @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
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
            <form method="POST" action="{{ route('sendresetlink') }}" class="mt-6">
                @csrf
            <div class="mb-4">
                <label for="email" class="block text-xl font-bold text-black nunito">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Enter your email" 
                        class="w-full px-4 py-4 mt-1 placeholder-[#0000008C] border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" 
                        required
                    >
            </div>

<div class="w-full space-y-4 pt-8">
    <button type="submit" class="w-full px-4 py-4 text-white text-2xl font-bold nunito- rounded-xl bg-gradient-to-r from-[#184E77] to-[#52B69A] hover:opacity-90 focus:outline-none focus:ring focus:ring-[#52B69A]">
        Reset Password
    </button>
    <a href="{{ route('login') }}" 
       class="w-full inline-block text-center px-4 py-4 text-2xl font-bold nunito- rounded-xl bg-gradient-to-r from-[#184E7780] to-[#52B69A80] text-transparent bg-clip-text border-2 border-[#52B69A80] hover:opacity-90 focus:outline-none focus:ring focus:ring-[#52B69A]">
        Back
    </a>
</div>

            
            </form>
  </div>
    </div>
    <style>
    .gradient-text {
        background: linear-gradient(to right, #184E77, #52B69A, #184E77);
        -webkit-text-fill-color: transparent;
        padding: 1rem;
        border-radius: 0.5rem;
    }
</style>

</section>

@endsection
