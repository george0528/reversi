<div wire:poll.3s>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    {{-- <div wire:loading.class="displaynone" class="component_load_circle"></div> --}}
    <div class="component_flash_text">自動更新中…</div>
    @isset($waitRooms)
    @foreach ($waitRooms as $r)
        @php
            if($r->mode_id === 3) {
                $mode_name = 'ノーマルオセロ';
            } elseif ($r->mode_id === 4) {
                $mode_name = '二択オセロ';
            }
        @endphp
        <div>
            <p>ID：{{ $r->id }}</p>
            <p>モード：{{ $mode_name }}</p>
            <form action="{{ route('onlineJoin',['room_id' => $r->id]) }}" method="post">
                @csrf
                <button wire:loading.attr="disabled" class="component_btn primary" type="submit">入室</button>
            </form>
        </div>
    @endforeach
    @endisset
</div>
