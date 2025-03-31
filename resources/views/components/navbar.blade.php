<nav class="bg-blue-500 text-white p-4">
    <a href="{{ route('home') }}" class="px-4">Home</a>
    @auth
        <a href="{{ route('dashboard') }}" class="px-4">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="px-4">Logout</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="px-4">Login</a>
        <a href="{{ route('register') }}" class="px-4">Register</a>
    @endauth
</nav>
