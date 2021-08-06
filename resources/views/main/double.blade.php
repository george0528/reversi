@php
    $count = 8;
    $reversi = $room->board->getContent();
@endphp 
@extends('layouts.main')
@section('title', 'top')
@section('content')
    @include('components.content.board')
    <script src="{{ asset('js/double.js') }}"></script>
@endsection