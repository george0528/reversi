@extends('layouts.main')
@section('title', '待機画面')
@section('content')
    @if (session('message'))
        <div>{{ session('message') }}</div>
    @endif
    <h1 class="text-xl font-black">待機画面</h1>
    <h2 class="text-lg font-bold my-2">ルーム作成</h2>
    <form action="{{ route('roomCreate') }}" method="post">
        @csrf
        <select class="m-3 ml-0 inline-block" name="mode_id" id="">
            <option value="3">ノーマルオセロ</option>
            <option value="4">二択オセロ</option>
        </select>
        <button class="component_btn primary" type="submit">作成</button>
    </form>
    <a href="{{ route('index') }}" class="component_btn primary">モード選択画面</a>
    @livewire('room-list')
@endsection