@php
    $count = 8;
    $reversi = $room->borad->getContent();
@endphp 
@extends('layouts.main')
@section('title', 'online')
@section('content')
<h1>ルーム状況</h1>
@foreach ($users as $u)
    @if (session()->getId() ==  $u->session)
        <h2>自分</h2>
        <p>色：{{ $u->color }}</p>
    @else
        <h2>相手</h2>
        <p>色：{{ $u->color }}</p>
    @endif
@endforeach
@include('components.content.borad')
    <script src="{{ asset('js/online.js') }}"></script>
    <script src="{{ asset('js/test.js') }}"></script>
@endsection