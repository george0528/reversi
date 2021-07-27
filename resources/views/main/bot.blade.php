@php
    $count = 8;
    $reversi = $room->borad->getContent();
@endphp 
@extends('layouts.main')
@section('title', 'bot')
@section('content')
@include('components.content.borad')
    <script src="{{ asset('js/bot.js') }}"></script>
@endsection