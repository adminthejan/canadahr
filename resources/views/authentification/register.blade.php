@extends('layouts.app')

@section('title', 'Login')

@section('content')
<link rel="stylesheet" href="http://127.0.0.1:5173/resources/css/app.css">
<body class="bg-gray-100 flex md:items-center md:justify-center justify-start items-start h-screen">
<section class="w-full h-screen flex">
    <div class="relative">
        <!-- Background Image -->
        <img src="bg1.png" class="w-[800px] h-full">
    
        <!-- Overlay -->
        <!-- Visible on desktop and hidden on mobile -->
        <div class="w-full flex flex-col hidden md:flex items-center justify-center space-y-8 bg-[#D9D9D966]">
            <!-- Layered Box -->
            <div class="w-[350px] h-[300px] absolute bg-[#D9D9D966] top-[30%] left-[28%] rounded-3xl shadow-md">
                <div class="w-full flex flex-col justify-center items-center pt-24">
                    <div class="w-full flex flex-col justify-center items-center space-y-2">
                        <p class="text-white text-5xl font-bold">Digital Platform for</p>
                        <p class="text-black text-5xl font-bold">Human Resources</p>
                    </div>
                    <div class="w-full flex flex-col justify-center items-center space-y-4 pt-4">
                        <p class="text-3xl text-[#00000099]">Simplifying Human</p>
                        <p class="text-3xl text-[#00000099]">Resources Management</p>
                        <p class="text-3xl text-[#00000099]">for your Company</p>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Visible only on mobile -->
        <div class="w-full flex flex-col justify-center items-center absolute inset-0 space-y-8 md:hidden">
            <div class="w-full h-full bg-[#D9D9D966] absolute inset-0 flex flex-col justify-center items-center">
                <div class="w-full flex flex-col justify-center items-center space-y-4">
                    <p class="text-6xl nunito- text-black font-bold">Hey, Hello!</p>
                    <p class="text-2xl text-[#00000099] nunito-">
                        Enter the information you entered while registering
                    </p>
                </div>
                <div class="w-2/3">
                    <form method="POST" action="{{ route('register') }}" class="mt-6">
                        @csrf
                        @if($errors->any())
                        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
    
                        <div class="mb-4">
                            <label for="company_name" class="block text-xl font-bold text-black nunito-">Company Name</label>
                            <input type="text" name="company_name" id="company_name" class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
                        </div>
                        <div class="mb-6">
                            <label for="name" class="block text-xl font-bold text-black nunito-">Full Name</label>
                            <input type="name" id="name" name="name" class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
                        </div>
                        <div class="mb-6">
                            <label for="email" class="block text-xl font-bold text-black nunito-">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
                        </div>
                        <div class="w-full flex justify-between items-center">
                            <div class="mb-6">
                                <label for="password" class="block text-xl font-bold text-black nunito-">Password</label>
                                <input type="password" id="password" name="password" class="w-full px-8 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
                            </div>
                            <div class="mb-6">
                                <label for="password_confirmation" class="block text-xl font-bold text-black nunito-">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-8 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="relative">
                                    <input type="checkbox" id="terms" name="terms" class="h-8 w-8 appearance-none border border-[#1C1B1F] rounded focus:ring-indigo-500 checked:bg-gradient-to-r checked:from-[#184E77] checked:via-[#52B69A] checked:to-[#184E77] checked:boredr-gray-300">
                                    <i class="ri-check-line absolute inset-0 m-auto text-white opacity-0 pointer-events-none" aria-hidden="true"></i>
                                </div>
                                <label for="remember" class="ml-2 block text-gray-700 nunito-">
                                    By Creating account, you are accepting
                                    <span class="bg-gradient-to-r from-[#184E77] via-[#52B69A] to-[#184E77] bg-clip-text text-transparent font-bold cursor-pointer">terms & conditions</span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="w-full px-4 py-4 text-white text-2xl font-bold nunito- rounded-xl bg-gradient-to-r from-[#184E77] to-[#52B69A] hover:opacity-90 focus:outline-none focus:ring focus:ring-[#52B69A]">
                            Register
                        </button>
                        <div class="w-full pt-8">
                            <p class="text-xl">
                                Already have an account?
                                <span class="bg-gradient-to-r from-[#184E77] via-[#52B69A] to-[#184E77] bg-clip-text text-transparent font-bold cursor-pointer">
                                    <a href="{{ route('login.form') }}" class="bg-gradient-to-r from-[#184E77] via-[#52B69A] to-[#184E77] bg-clip-text text-transparent font-bold cursor-pointer">
                                        Login
                                    </a>
                                </span>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="w-1/2 h-full bg-white hidden md:flex flex-col justify-center items-center">
        <div class="w-full flex flex-col justify-center items-center space-y-4">
            <p class="text-6xl nunito- text-black font-bold">Hey, Hello!</p>
            <p class="text-2xl text-[#00000099] nunito-">Enter the information you entered while registering</p>
        </div>
        <div class="w-1/2">
            <form method="POST" action="{{ route('register') }}" class="mt-6">
                @csrf
                @if($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
    
                <div class="mb-4">
                    <label for="company_name" class="block text-xl font-bold text-black nunito-">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
                </div>
                <div class="mb-6">
                    <label for="name" class="block text-xl font-bold text-black nunito-">Full Name</label>
                    <input type="name" id="name" name="name" class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
                </div>
                <div class="mb-6">
                    <label for="email" class="block text-xl font-bold text-black nunito-">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
                </div>
                <div class="w-full flex justify-between items-center">
                    <div class="mb-6">
                        <label for="password" class="block text-xl font-bold text-black nunito-">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-8 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
                    </div>
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-xl font-bold text-black nunito-">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-8 py-4 mt-1 border-2 border-[#1C1B1F80] rounded-xl focus:outline-none focus:ring focus:ring-indigo-300" required>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center" >
                        <div class="relative">
                            <input type="checkbox" id="terms" name="terms" class="h-8 w-8 appearance-none border border-[#1C1B1F] rounded focus:ring-indigo-500 checked:bg-gradient-to-r checked:from-[#184E77] checked:via-[#52B69A] checked:to-[#184E77] checked:boredr-gray-300">
                            <i class="ri-check-line absolute inset-0 m-auto text-white opacity-0 pointer-events-none" aria-hidden="true"></i>
                        </div>
                        <label for="remember" class="ml-2 block text-gray-700 nunito-">By Creating account, you are accepting
                            <span class="bg-gradient-to-r from-[#184E77] via-[#52B69A] to-[#184E77] bg-clip-text text-transparent font-bold cursor-pointer">terms & conditions</span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="w-full px-4 py-4 text-white text-2xl font-bold nunito- rounded-xl bg-gradient-to-r from-[#184E77] to-[#52B69A] hover:opacity-90 focus:outline-none focus:ring focus:ring-[#52B69A]">Register</button>
                <div class="w-full pt-8">
                    <p class="text-xl">Already have an account? <span class="bg-gradient-to-r from-[#184E77] via-[#52B69A] to-[#184E77] bg-clip-text text-transparent font-bold cursor-pointer">
                            <a href="{{ route('login.form') }}" class="bg-gradient-to-r from-[#184E77] via-[#52B69A] to-[#184E77] bg-clip-text text-transparent font-bold cursor-pointer">
                                Login
                            </a>
                        </span></p>
                </div>
            </form>
        </div>
    </div>
    
    </div>
</section>

</body>
</html>
