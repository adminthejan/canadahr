@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center">
        <h1 class="text-6xl font-extrabold text-red-600">404</h1>
        <p class="mt-4 text-xl font-semibold text-gray-800">Page Not Found</p>
        <p class="mt-2 text-gray-600">
            Sorry, the page you are looking for does not exist or may have been moved.
        </p>
        <div class="mt-6">
            <a href="{{ url('/login') }}" class="px-6 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                Go to Login page
            </a>
        </div>
    </div>
</div>
@endsection
