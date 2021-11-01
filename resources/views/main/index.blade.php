@extends('layouts.main')
@section('title', 'モード選択画面')
@section('content')
    <div class="center">
        <h1>モード選択画面</h1>
        <a class="select_bar" href="{{ route('bot') }}">ボットと対戦</a>
        <a class="select_bar" href="{{ route('double') }}">オフライン二人対戦</a>
        <a class="select_bar" href="{{ route('onlineList') }}">オンライン対戦</a>
    </div>
@endsection