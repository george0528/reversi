<body>
<header class="header navbar">
    {{-- ログイン --}}
    @if (Route::has('login'))
    <div class="header_left">
        {{-- <img src="" alt="" class="logo"> --}}
        <h1>logoの代替</h1>
    </div>
    <div class="header_right">
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