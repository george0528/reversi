<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];
    // 盤面の配列を保存する (旧)
    public function fillContent($data) {
        $data = json_encode($data);
        return $this->fill(['content' => $data])->save();
    }
    // 盤面を更新する (新)
    public function updateContent($content, $color) {
        $content = json_encode($content);
        $next_color = $this->turnColor($color);
        return $this->fill(['content' => $content,'next_color' => $next_color])->save();
    }
    // 盤面の配列をとる
    public function getContent() {
        return json_decode($this->content, true);
    }
    public function changeNextColor($color) {
        $next_color = $this->turnColor($color);
        return $this->fill(['next_color' => $next_color])->save();
    }
    // 色の反転
    public function turnColor($color) {
        if($color == 1) {
            $color = 2;
        } elseif($color == 2) {
            $color = 1;
        }
        return $color;
    }
}
