@extends('layouts.main')
@section('title', 'livewireページ')
@section('content')
@php
    $count = 8
@endphp
@include('components.content.board')
@endsection