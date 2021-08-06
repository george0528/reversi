@extends('layouts.main')
@section('content')
    
<form action="{{ route('ajaxSend') }}" method="post">
    @csrf
    <label for="">座標１</label><input type="number" name="i1" value="">
    <label for="">座標２</label><input type="number" name="i2" value="">
    <label for="">色</label><input type="number" name="color">
    <label for="">ルームID</label><input type="number" name="room">
    <label for="">パス</label><input type="checkbox" name="pass" id="">
    <button class="component_btn primary" type="submit">決定</button>
</form>

@endsection