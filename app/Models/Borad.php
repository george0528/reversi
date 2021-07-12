<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Borad extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    // 盤面の配列を保存する
    public function fillContent($data) {
        $data = json_encode($data);
        $this->fill(['content' => $data]);
        return $this->save();
    }
    // 盤面の配列をとる
    public function getContent() {
        return json_decode($this->content, true);
    }
}
