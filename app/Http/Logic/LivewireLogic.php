<?php 

namespace App\Http\Logic;

use App\Models\Room;

Class LivewireLogic {
    public function put($i1, $i2, $color, $room_id) {
        $rLogic = new RequestLogic;
        $room = new Room;
        $room = $room->find($room_id);
        $board = $room->board;
        $content = $board->getContent();
        // 置けるかチェック
        $changes = $rLogic->check($i1, $i2, $content, $color);
        // 置けない場合
        if(empty($changes)) {
            return [
                'problem' => true,
            ];
        }
        // 実際にひっくりかえす
        $content = $rLogic->reverse($color,$changes,$content,$i1,$i2);
        // DBに保存
        $board->fillContent($content);
        return [
            'content' => $content,
        ];
    }
    public function nexts($color, $content) {
        $Logic = new RequestLogic;
        if($color == 1) {
            $color = 2;
        } elseif($color == 2) {
            $color = 1;
        }
        $nexts = $Logic->nextCoords($color, $content);
        if(empty($nexts['coords'])) {
            return null;
        } else {
            return array_column($nexts['coords'], 'coord');
        }
    }
}