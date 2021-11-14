@extends('layouts.main')
@section('title', 'online')
@section('content')
<h1>ルーム状況</h1>
@livewire('board')
<script src="{{ asset('js/websocket.js') }}"></script>
@endsection