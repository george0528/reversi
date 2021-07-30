<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    @isset($waitRooms)
    @foreach ($waitRooms as $r)
        <div>
            <p>ID:{{ $r->id }}</p>
            <form action="{{ route('onlineJoin',['room_id' => $r->id]) }}" method="post">
                @csrf
                <button class="component_btn primary" type="submit">入室</button>
            </form>
        </div>
    @endforeach
    @endisset
</div>
