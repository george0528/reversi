@php
    $count = 8;
    $reversi = $room->board->getContent();
@endphp 
@extends('layouts.main')
@section('title', 'top')
@section('content')
    <input type="hidden" id="room_id" value="{{ $room->id }}">
    <span class="color" data-color="1"></span>
    @include('components.content.players')
    @include('components.content.board')
    <script src="{{ asset('js/double.js') }}"></script>
@endsection