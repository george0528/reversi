<?php 

namespace App\Http\Logic;

Class BotLogic {
    // 一番多くひっくりかえせる場所の座標を取る
    public function maxCoord($nexts) {
        //countのmax値を取りそれと同じものインデックス番号を取る
        $counts = array_column($nexts, 'count');
        $indexs = array_keys($counts,max($counts));
        return $nexts[$indexs[0]]['coord'];
    }
}






?>