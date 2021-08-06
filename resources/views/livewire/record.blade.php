<div>
    {{-- Do your work, then step back. --}}
    @if (empty($records))
        <div class="component_alert danger">戦績がありません</div>
    @else
        @foreach ($records as $record)
            <div class="">
                <p>ID：{{ $record->id }}</p>
                @php
                    if($user_id == $record->winner) {
                        $result = '勝ち';
                    } else {
                        $result = '負け';
                    }
                @endphp
                <p>勝敗：{{ $result }}</p>
            </div>
        @endforeach
    @endif
</div>
