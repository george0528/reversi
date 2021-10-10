@php
    $count = 8;
    $reversi = $room->board->getContent();
@endphp 
@extends('layouts.main')
@section('title', 'bot')
@section('content')
<input type="hidden" id="room_id" value="{{ $room->id }}">
@include('components.content.players')
@include('components.content.board')
    <script src="{{ asset('js/bot.js') }}"></script>
@endsection