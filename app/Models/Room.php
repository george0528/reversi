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
    public function waitRooms() {
        return $this->where('status', 2)->get();
    }
    // 空きの部屋があるかどうか
    public function free() {
        return $this->where('status', 1)->firstOr(function () {
            $borad = new Borad;
            $content = $this->reset();
            $b = $borad->create([
                'next_color' => 1,
                'content' => $content,
            ]);
            return $this->create([
                'status' => 2,
                'borad_id' => $b->id,
            ]);
        });
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
    // DB Boradテーブルに紐づけ
    public function borad() {
        return $this->belongsTo(Borad::class, 'borad_id');
    }
}
