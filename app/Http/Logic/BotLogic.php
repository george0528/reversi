<?php 

namespace App\Http\Logic;

use App\Http\Logic\RequestLogic;

Class BotLogic {
    public function botnexts($nexts, $board, $usercolor, $content, $json) {
        // botの操作
        // 置ける場所があれば
        if(!isset($nexts['pass'])) {
            $data = $this->botreverse($nexts, $board, $usercolor, $content);
            $json['botChanges'] = $data['changes'];
            $json['botCoord'] = $data['coord'];
            $content = $data['content'];
            $json['content'] = $content;
        } else {
            $json['botpass'] = true;
        }
        // jsonからパスとフィニッシュを削除
        unset($json['pass']);
        //　ユーザーの次に置ける場所を探す
        $requestLogic = new RequestLogic;
        // 次に置ける場所を特定する
        $nexts = $requestLogic->nextCoords($usercolor,$content);
        // 次に置ける場所をチェックする
        $json = $requestLogic->nextCheck($nexts, $json);
        // 黒がパスの場合　白のnextを調べる
        if(isset($json['pass'])) {
            $usercolor = $this->changeColor($usercolor);
            $nexts = $requestLogic->nextCoords($usercolor,$content);
            if(isset($nexts['pass'])) {
                $json['finish'] = true;
            }
        }
        // どちらもパスの場合終了
        if(isset($json['pass'])  && isset($json['botpass'])) {
            $json['finish'] = true;
        }
        return $json;
    }
    public function botreverse($nexts, $board, $usercolor, $content) {
        // 置く場所を決める
        $maxCoord = $this->maxCoord($nexts);
        $requestLogic = new RequestLogic;
        //　おけるかチェック
        $changes = $requestLogic->check($maxCoord[0],$maxCoord[1],$content,$usercolor);
        // 実際にひっくりかえす
        $content = $requestLogic->reverse($usercolor,$changes,$content,$maxCoord[0],$maxCoord[1]);
        // データベースに保存
        $board->fillContent($content);

        return ['changes' => $changes, 'content' => $content , 'coord' => $maxCoord];
    }
    // 一番多くひっくりかえせる場所の座標を取る
    public function maxCoord($nexts) {
        //countのmax値を取りそれと同じものインデックス番号を取る
        $counts = array_column($nexts['coords'], 'count');
        $indexs = array_keys($counts,max($counts));
        return $nexts['coords'][$indexs[0]]['coord'];
    }
    public function changeColor($color) {
        if($color == 1) {
            $color = 2;
        } elseif($color == 2) {
            $color = 1;
        }
        return $color;
    }
}






?>