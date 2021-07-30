<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    @isset($message)
        <div class="component_alert danger">{{ $message }}</div>
    @endisset
    @isset($nexts)
        <div class="component_alert success">nextsの中身あり</div>
    @endisset
    <h1>{{ $puttedCoord[0] ?? 'null'}} {{ $puttedCoord[1] ?? 'null'}}</h1>
    <button wire:click="$emit('test')">テスト</button>
    <button wire:click="$emit('nexts')">nextsssssssss</button>
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
    <a class="component_btn danger" href="{{ route('reset') }}">リセット</a>
    @isset($pass)
        <button type="submit" class="component_btn primary">パス</button>
    @endisset
</div>
