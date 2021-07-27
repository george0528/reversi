@extends('layouts.main')
@section('title', '待機画面')
@section('content')
    @if (session('message'))
        <div>{{ session('message') }}</div>
    @endif
    <h1>待機画面</h1>
    <h2>ルーム作成</h2>
    <form action="{{ route('roomCreate') }}" method="post">
        @csrf
        <button type="submit">作成</button>
    </form>
    @isset($waitRooms)
        @foreach ($waitRooms as $r)
            <div>
                <p>ID:{{ $r->id }}</p>
                <form action="{{ route('onlineJoin',['room_id' => $r->id]) }}" method="post">
                    @csrf
                    <button class="component_btn primary" type="submit">入室</button>
                </form>
            </div>
        @endforeach
    @endisset
@endsection