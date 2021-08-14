<body>
<header class="header">
    {{-- ログイン --}}
    @if (Route::has('login'))
    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
        @auth
            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
            @endif
        @endauth
    </div>
@endif
</header>
<div class="component">
    @if (session('alert'))
    @php
        $alert = session('alert');
    @endphp
        @if ($alert['flag'])
            <div class="component_alert success">{{ $alert['message'] }}</div>
        @else
            <div class="component_alert danger">{{ $alert['message'] }}</div>
        @endif
    @endif
</div>