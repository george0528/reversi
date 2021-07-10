<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Borad extends Model
{
    use HasFactory;
    protected $guard = [
        'id'
    ];
    // 盤面の配列を保存する
    public function fillBorad($data) {
        $data = json_encode($data);
        $this->fill([$data]);
        return $this->save();
    }
    // 盤面の配列をとる
    public function getBorad() {
        return json_decode($this->borad, true);
    }
}
