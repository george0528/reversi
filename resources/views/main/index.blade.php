@php
    $count = 7;
    $reversi = json_decode($room->borad->borad, true);
@endphp 
@extends('layouts.main')
@section('title', 'top')
@section('content')
    <main class="main">
        <h1>赤色</h1>
        <table class="table">
            @for ($i1 = 0; $i1 <= $count; $i1++)
            <tr trIndex='{{ $i1 }}'>
                @for ($i2 = 0; $i2 <= $count; $i2++)
                @if(isset($reversi[$i1][$i2]))
                    @if ( $reversi[$i1][$i2] == 1)
                        <td class="black" tdIndex="{{ $i2 }}">●</td>
                    @elseif ($reversi[$i1][$i2] == 2)
                        <td class="white" tdIndex="{{ $i2 }}">○</td>
                    @endif
                @else
                    <td tdIndex="{{ $i2 }}"></td>
                @endif
                @endfor
            </tr>
            @endfor
        </table>
    </main>
    <script src="{{ asset('js/index.js') }}"></script>
@endsection