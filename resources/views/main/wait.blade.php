@extends('layouts.main')
@section('title', '対戦相手待ち')
@section('content')
    <h1>対戦相手待ち</h1>
    <form action="{{ route('onlineLeave') }}" method="post">
        @csrf
        <button type="submit">戻る</button>
    </form>
    @livewire('wait')
    <script src="{{ asset('js/websocket.js') }}"></script>
@endsection