<div>
    <h1>あなたの色：<span class="color"></span></h1>
    <table class="table">
        @for ($i1 = 0; $i1 < 8; $i1++)
        <tr data-tr-index='{{ $i1 }}'>
            @for ($i2 = 0; $i2 < 8; $i2++)
                @if (isset($content[$i1][$i2]) && $content[$i1][$i2] == 1)
                    @if (!empty($puttedCoord[0]) && $puttedCoord[0] == $i1 && $puttedCoord[1] == $i2)
                        <td class="black put" data-td-index="{{ $i2 }}">●</td>
                    @else
                        <td class="black" data-td-index="{{ $i2 }}">●</td>
                    @endif
                @elseif (isset($content[$i1][$i2]) && $content[$i1][$i2] == 2)
                    @if (!empty($puttedCoord[0]) && $puttedCoord[0] == $i1 && $puttedCoord[1] == $i2)
                        <td class="white put" data-td-index="{{ $i2 }}">○</td>
                    @else
                        <td class="white" data-td-index="{{ $i2 }}">○</td>
                    @endif
                @else
                    @php
                        $next_flag = false;
                        if(isset($nexts)) {
                            foreach ($nexts as $next) {
                                if($next[0] == $i1 && $next[1] == $i2) {
                                    $next_flag = true;
                                }
                            }
                        }
                    @endphp
                    {{-- nextflag --}}
                    @if ($next_flag)
                        <td class="next" wire:click='put({{ $i1 }}, {{ $i2 }})' data-td-index="{{ $i2 }}"></td>
                    @else
                        <td wire:click='put({{ $i1 }}, {{ $i2 }})' data-td-index="{{ $i2 }}"></td>
                    @endif
                @endif
            @endfor
        </tr>
        @endfor
    </table>
</div>
