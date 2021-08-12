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
        return $this->where('is_battle', 0)->where('is_wait', 1)->whereBetween('mode_id', [3,4])->get();
    }
    // 空きの部屋があるかどうか
    public function free() {
        return $this->create([
            'is_battle' => 0,
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
    public function join($room_id) {
        $room = $this->where('id', $room_id)->where('is_wait', 1)->where('is_battle', 0)->firstOr(function() {
            return null;
        });
        if(empty($room)) {
            return null;
        }
        $content = $this->reset();
        $board = new Board;
        $b = $board->create([
            'next_color' => 1,
            'content' => $content,
        ]);
        $room->fill([
            'is_wait' => 0,
            'is_battle' => 1,
            'board_id' => $b->id,
        ]);
        $room->save();
        $user = auth()->user();
        $user->join_room($room);
        $users = $room->users;
        $this->give_color($users);
        return $room;
    }
    // 色決め
    public function give_color($users) {
        $colors = [1, 2];
        // シャッフル
        shuffle($colors);
        for ($i=0; $i < count($users); $i++) { 
            $user = $users[$i];
            $user->color = $colors[$i];
            $user->save();
        }
    }
    // バトル終了処理　データリセット
    public function finish($winner_color) {
        $this->is_battle = 0;
        $board = $this->board;
        $board->next_color = null;
        $users = $this->users;
        $user_color_black = $this->search_user($users, 1);
        $user_color_white = $this->search_user($users, 2);
        if($winner_color == 1) {
            $board->winner = $user_color_black->id;
        } elseif($winner_color == 2) {
            $board->winner = $user_color_white->id;
        }
        $board->user1 = $user_color_black->id;
        $board->user2 = $user_color_white->id;
        $board->save();
        $this->save();
    }
    // ユーザーを探す
    public function search_user($users,$color) {
        foreach($users as $user) {
            if($user->color == $color) {
                return $user;
            }
        }
    }
    // DB Boardテーブルに紐づけ
    public function board() {
        return $this->belongsTo(Board::class, 'board_id');
    }
    // DB Userテーブルに紐づけ
    public function users() {
        return $this->hasMany(User::class, 'room_id');
    }
}
