<div>
  @php
      $player_count = 1;
      $colors = ['黒', '白'];
  @endphp
  @foreach ($players as $player)
  <div class="player_info">
    <h4><span class="player{{$player_count}}_name">{{ $player['name'] }}</span></h4>
    <div>{{ $colors[$player_count - 1] }}</div>
    <div><span class="player{{$player_count}}_count">{{ $player['count'] }}</span></div>
  </div>
  @php
      $player_count++;
  @endphp
  @endforeach
</div>