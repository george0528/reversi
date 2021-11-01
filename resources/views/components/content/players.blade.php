<div class="players">
  @php
      $player_count = 1;
      $colors = ['●', '○'];
  @endphp
  @isset($players)
    @foreach ($players as $player)
    <div class="player_info">
      <h4><span class="player{{$player_count}}_name">{{ $player['name'] }}</span></h4>
      <div class="player_color">{{ $colors[$player_count - 1] }}</div>
      <div><span class="player{{$player_count}}_count">{{ $player['count'] }}</span></div>
    </div>
    @php
        $player_count++;
        @endphp
    @endforeach
  @endisset
</div>