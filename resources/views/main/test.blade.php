@extends('layouts.main')
@section('content')
    
<form action="{{ route('ajaxSend') }}" method="post">
    @csrf
    <input type="number" name="i1" value="">
    <input type="number" name="i2" value="">
    <input type="number" name="color">
    <button class="component_btn primary" type="submit">決定</button>
</form>

@endsection