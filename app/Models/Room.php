<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Room extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    // 対戦待ち
    public function waitRooms() {
        return $this->where('is_battle', 0)->where('mode_id', 3)->get();
    }
    // 空きの部屋があるかどうか
    public function free() {
        $borad = new Borad;
        $content = $this->reset();
        $b = $borad->create([
            'next_color' => 1,
            'content' => $content,
        ]);
        return $this->create([
            'is_battle' => 0,
            'borad_id' => $b->id,
        ]);
    }
    // オセロの最初の盤面
    public function reset() {
        $reversi[3][3] = 1;
        $reversi[3][4] = 2;
        $reversi[4][3] = 2;
        $reversi[4][4] = 1;
        $reversi = json_encode($reversi);
        return $reversi;
    }
    // 
    public function join($room_id, $request) {
        $room = $this->where('id', $room_id)->where('is_battle', 0)->firstOr(function() {
            return null;
        });
        if(empty($room)) {
            return null;
        }
        $room->fill([
            'is_battle' => 1,
        ]);
        $room->save();
        $user = new User;
        $user->join_room($room, $request);
        return $room;
    }
    // DB Boradテーブルに紐づけ
    public function borad() {
        return $this->belongsTo(Borad::class, 'borad_id');
    }
    // DB Userテーブルに紐づけ
    public function users() {
        return $this->hasMany(User::class, 'room_id');
    }
}
