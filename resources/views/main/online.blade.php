@extends('layouts.livewire')
@section('title', 'online')
@section('content')
<h1>ルーム状況</h1>

@isset($users)
@foreach ($users as $u)
    @if (session()->getId() ==  $u->session)
        <h2>自分</h2>
        <p>色：{{ $u->color }}</p>
    @else
        <h2>相手</h2>
        <p>色：{{ $u->color }}</p>
    @endif
@endforeach
@endisset

{{-- @include('components.content.board') --}}
@livewire('board')
    <script src="{{ asset('js/websocket.js') }}"></script>
@endsection