<?php 

namespace App\Http\Logic;

use App\Http\Logic\RequestLogic;

Class BotLogic {
    public function botreverse($nexts, $borad, $usercolor, $content) {
        // 置く場所を決める
        $maxCoord = $this->maxCoord($nexts);
        
        // 色を反転
        if($usercolor == 1) {
            $usercolor = 2;
        } elseif($usercolor == 2) {
            $usercolor = 1;
        }
        $requestLogic = new RequestLogic;
        //　おけるかチェック
        $changes = $requestLogic->check($maxCoord[0],$maxCoord[1],$content,$usercolor);
        // 実際にひっくりかえす
        $content = $requestLogic->reverse($usercolor,$changes,$content,$maxCoord[0],$maxCoord[1]);
        // データベースに保存
        $borad->fillContent($content);
        
        // 次に置ける場所を特定する
        $nexts = $requestLogic->nextCoords($usercolor,$content);

        return ['changes' => $changes, 'nexts' => $nexts, 'coord' => $maxCoord];
    }
    // 一番多くひっくりかえせる場所の座標を取る
    public function maxCoord($nexts) {
        //countのmax値を取りそれと同じものインデックス番号を取る
        $counts = array_column($nexts['coords'], 'count');
        $indexs = array_keys($counts,max($counts));
        return $nexts['coords'][$indexs[0]]['coord'];
    }
}






?>