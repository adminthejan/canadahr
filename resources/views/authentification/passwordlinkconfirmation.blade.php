@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<section class="w-full h-screen flex">
<div class="relative">
    <!-- Background Image -->
    <img src="./bg1.png" class="w-[800px] h-full">

    <!-- Overlay -->
    <div class="w-full md:flex flex-col hidden items-center justify-center space-y-8 bg-[#D9D9D966]">
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
    <div class="w-1/2 h-full bg-white flex flex-col justify-center items-center absolute inset-0 md:hidden">
        <div class="w-full flex flex-col justify-center items-center space-y-4 absolute inset-0">
            <p class="text-6xl nunito- text-black font-bold">Forgot Password</p>
            <p class="mb-6">A password reset link has been sent to your email address: </p>
            <p class="text-2xl text-black nunito- font-bold">{{ $email }}</p>
        </div>
        <div class="w-1/2 p-8 flex justify-center items-center">
    <a href="/login" 
       class="w-1/2 px-4 py-4 text-2xl font-bold bg-white hover:opacity-90 focus:outline-none focus:ring focus:ring-[#52B69A] block text-center"
       style="border: 2px solid transparent; border-image: linear-gradient(90deg, #184E7780, #52B69A80); border-image-slice: 1; background: linear-gradient(90deg, #184E77, #52B69A); -webkit-background-clip: text; color: transparent;">
       Back to login
    </a>
</div>
            <div class="w-full flex justify-center items-center pt-4">
                <p class="text-xl">Didn’t receive the mail? <span class="bg-gradient-to-r from-[#184E77] via-[#52B69A] to-[#184E77] bg-clip-text text-transparent font-bold cursor-pointer" onclick=""> Click to resend</span></p>
            </div>
            </form>
  </div>
</div>

</div>
    <div class="w-1/2 h-full bg-white md:flex flex-col hidden justify-center items-center">
        <div class="w-full flex flex-col justify-center items-center space-y-4">
            <p class="text-6xl nunito- text-black font-bold">Forgot Password</p>
            <p class="mb-6">A password reset link has been sent to your email address: </p>
            <p class="text-2xl text-black nunito- font-bold">{{ $email }}</p>
        </div>
        <div class="w-1/2 p-8 flex justify-center items-center">
    <a href="/login" 
       class="w-1/2 px-4 py-4 text-2xl font-bold bg-white hover:opacity-90 focus:outline-none focus:ring focus:ring-[#52B69A] block text-center"
       style="border: 2px solid transparent; border-image: linear-gradient(90deg, #184E7780, #52B69A80); border-image-slice: 1; background: linear-gradient(90deg, #184E77, #52B69A); -webkit-background-clip: text; color: transparent;">
       Back to login
    </a>
</div>
            <div class="w-full flex justify-center items-center pt-4">
                <p class="text-xl">Didn’t receive the mail? <span class="bg-gradient-to-r from-[#184E77] via-[#52B69A] to-[#184E77] bg-clip-text text-transparent font-bold cursor-pointer" onclick=""> Click to resend</span></p>
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