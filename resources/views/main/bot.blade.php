@php
    $count = 8;
    $reversi = $room->board->getContent();
@endphp 
@extends('layouts.main')
@section('title', 'bot')
@section('content')
@include('components.content.board')
    <script src="{{ asset('js/bot.js') }}"></script>
@endsection