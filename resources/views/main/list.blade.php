@extends('layouts.main')
@section('title', '待機画面')
@section('content')
    <h1>待機画面</h1>
    <h2>ルーム作成</h2>
    <form action="{{ route('roomCreate') }}" method="post">
        @csrf
        <button type="submit">作成</button>
    </form>
    @isset($waitRooms)
        @foreach ($waitRooms as $r)
            <p>ID:{{ $r->id }}</p>
        @endforeach
    @endisset
@endsection