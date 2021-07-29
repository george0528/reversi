<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <input type="number" wire:model='num'>{{ $num }}{{ var_dump($ary) }}
    <button wire:click='add({{ $num }})'>追加</button>
    @for ($i = 0; $i < 10; $i++)
        @if (empty($ary[$i]))
            <h1>空</h1>
        @else
            <h1>{{ $ary[$i] }}</h1>
        @endif
    @endfor
</div>
