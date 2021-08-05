@extends('layouts.main')
@section('title', 'モード選択画面')
@section('content')
    <h1>モード選択画面</h1>
    <a href="{{ route('bot') }}">ボットと対戦</a>
    <a href="{{ route('double') }}">オフライン二人対戦</a>
    <a href="{{ route('onlineList') }}">オンライン対戦</a>
@endsection