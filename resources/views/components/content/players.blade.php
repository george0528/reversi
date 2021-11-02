<div class="players">
  @php
      $colors = ['black', 'white'];
  @endphp
  @isset($players)
    @foreach ($players as $index => $player)
    <div class="player_info">
      <h4><span class="player{{$index + 1}}_name">{{ $player['name'] }}</span></h4>
      <div class="player_color"><span class="{{$colors[$index]}}"></span></div>
      <div><span class="player{{$index + 1}}_count">{{ $player['count'] }}</span></div>
    </div>
    @endforeach
  @endisset
</div>