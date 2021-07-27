@php
    $count = 8;
    $reversi = $room->borad->getContent();
@endphp 
@extends('layouts.main')
@section('title', 'top')
@section('content')
    @include('components.content.borad')
    <script src="{{ asset('js/double.js') }}"></script>
@endsection