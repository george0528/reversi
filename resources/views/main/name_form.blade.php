@extends('layouts.main')
@section('title', 'トップページ')
@section('content')
    <form action="{{ route('addName') }}" method="post">
        @csrf
        <label for="">名前</label><input type="text" name="name" autocomplete="off">
        <button type="submit">決定</button>
    </form>
@endsection