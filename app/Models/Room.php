<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Room extends Model
{
    use HasFactory;
    protected $guard = [
        'id'
    ];
    public function room() {
        return $this->free();
    }
    // 空きの部屋があるかどうか
    public function free() {
        return $this->where('status', 1)->firstOr(function () {
            return $this->create();
        });
    }
    // DB Boradテーブルに紐づけ
    public function borad() {
        return $this->belongsTo(Borad::class, 'borad_id');
    }
}
