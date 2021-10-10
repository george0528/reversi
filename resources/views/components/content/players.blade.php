<div>
  @php
      $player_count = 1
  @endphp
  @foreach ($players as $player)
  <div>
    <h4 class="player{{$player_count}}_name">{{ $player['name'] }}</h4>
    <div class="player{{$player_count}}_count">{{ $player['count'] }}</div>
  </div>
  @php
      $player_count++;
  @endphp
  @endforeach
</div>