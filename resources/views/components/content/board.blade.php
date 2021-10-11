<main class="main">
    <h1>現在のカラー　：　<span class="color" data-color="1">黒</span></h1>
    <table class="table">
        @for ($i1 = 0; $i1 < $count; $i1++)
        <tr data-tr-index='{{ $i1 }}'>
            @for ($i2 = 0; $i2 < $count; $i2++)
            @if(isset($reversi[$i1][$i2]))
                @if ($reversi[$i1][$i2] == 1)
                    <td class="black" data-td-index="{{ $i2 }}">●</td>
                @elseif ($reversi[$i1][$i2] == 2)
                    <td class="white" data-td-index="{{ $i2 }}">○</td>
                @endif
            @else
                <td data-td-index="{{ $i2 }}"></td>
            @endif
            @endfor
        </tr>
        @endfor
    </table>
</main>
<div class="pass">
    <h3>パスするしかありません</h3>
    <button type="submit" class="component_btn primary">パス</button>
</div>
<div class="finish">
    <div class="count"></div>
    <div class="winner"></div>
    <button class="component_btn danger" id="delete">消す</button>
</div>
<a href="{{ route('index') }}" class="component_btn primary">モード選択画面</a>