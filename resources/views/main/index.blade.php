@extends('layouts.main')
@section('title', 'モード選択画面')
@section('content')
    <h1>モード選択画面</h1>
    <a href="{{ route('mode', ['mode' => 1]) }}">ボットと対戦</a>
    <a href="{{ route('mode', ['mode' => 2]) }}">オフライン二人対戦</a>
    <a href="{{ route('mode', ['mode' => 3]) }}">オンライン対戦</a>
@endsection