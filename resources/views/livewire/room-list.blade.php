<div wire:poll.5000ms>
    @if (session('is_join'))
        <div class="component_alert danger">参加中です</div>
        <button wire:click="delete" class="component_btn danger">is_joinを消す</button>
    @endif
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    {{-- <div wire:loading.class="displaynone" class="component_load_circle"></div> --}}
    <div class="component_flash_text">自動更新中…</div>
    @isset($waitRooms)
    @foreach ($waitRooms as $r)
        <div wire:loading.class="displaynone">
            <p>ID:{{ $r->id }}</p>
            <form action="{{ route('onlineJoin',['room_id' => $r->id]) }}" method="post">
                @csrf
                <button wire:loading.attr="disabled" class="component_btn primary" type="submit">入室</button>
            </form>
        </div>
    @endforeach
    @endisset
</div>
