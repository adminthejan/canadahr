@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<section class="w-full h-screen flex">
<div class="relative">
    <!-- Background Image -->
    <img src="/bg1.png" class="w-[800px] h-full">

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
<div class="w-full h-full bg-white flex flex-col justify-center items-center absolute inset-0 md:hidden">
    <div class="w-full flex flex-col justify-center items-center space-y-4 absolute inset-0">
        <p class="text-6xl nunito- text-black font-bold">Set a New Password</p>
        <p class="text-2xl text-[#00000099] nunito-">New password must be different from your previous used password </p>
    </div>
    <div class="w-1/2 p-8">
    <form method="POST" action="{{ route('password.update') }}" class="mt-6">
            @csrf
            <div class="mb-6">
                <label for="email" class="block text-xl font-bold text-black nunito-">Email</label>
                <input type="email" id="email" name="email" 
                       class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300"
                       required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-xl font-bold text-black nunito-">New Password</label>
                <input type="password" id="password" name="password" 
                       class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300"
                       required>
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-xl font-bold text-black nunito-">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300"
                       required>
            </div>
            @if ($errors->any())
                <div class="mb-4 text-red-500">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="w-full space-y-4 pt-8">
                <button type="submit" 
                        class="w-full px-4 py-4 text-white text-2xl font-bold nunito- rounded-xl bg-gradient-to-r from-[#184E77] to-[#52B69A] hover:opacity-90 focus:outline-none focus:ring focus:ring-[#52B69A]">
                    Reset Password
                </button>
            </div>
        </form>
</div>
</div>
    <div class="w-1/2 h-full bg-white hidden md:flex flex-col justify-center items-center">
        <div class="w-full flex flex-col justify-center items-center space-y-4">
            <p class="text-6xl nunito- text-black font-bold">Set a New Password</p>
            <p class="text-2xl text-[#00000099] nunito-">New password must be different from your previous used password </p>
        </div>
        <div class="w-1/2 p-8">
        <form method="POST" action="{{ route('password.update') }}" class="mt-6">
                @csrf
                <div class="mb-6">
                    <label for="email" class="block text-xl font-bold text-black nunito-">Email</label>
                    <input type="email" id="email" name="email" 
                           class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300"
                           required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-xl font-bold text-black nunito-">New Password</label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300"
                           required>
                </div>
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-xl font-bold text-black nunito-">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300"
                           required>
                </div>
                @if ($errors->any())
                    <div class="mb-4 text-red-500">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="w-full space-y-4 pt-8">
                    <button type="submit" 
                            class="w-full px-4 py-4 text-white text-2xl font-bold nunito- rounded-xl bg-gradient-to-r from-[#184E77] to-[#52B69A] hover:opacity-90 focus:outline-none focus:ring focus:ring-[#52B69A]">
                        Reset Password
                    </button>
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

    body {
        background-color: #f9f9f9;
    }
</style>

</section>


@endsection
