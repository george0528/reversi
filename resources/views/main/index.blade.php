@extends('layouts.main')
@section('title', 'トップページ')
@section('content')
    <form action="{{ route('bot') }}" method="get">
        <label for="">名前</label><input type="text">
        <button type="submit">決定</button>
    </form>
@endsection