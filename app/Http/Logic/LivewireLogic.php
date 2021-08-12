<?php 

namespace App\Http\Logic;

use App\Models\Room;
use GuzzleHttp\Psr7\Request;

Class LivewireLogic {
    public function put($i1, $i2, $color, $room_id) {
        $rLogic = new RequestLogic;
        $room = new Room;
        $room = $room->find($room_id);
        $mode_id = $room->mode_id;
        $board = $room->board;
        $content = $board->getContent();
        $next_color = $board->next_color;
        //　その色が置く番か
        if($color != $next_color) {
            return [
                'problem' => true,
            ];
        }
        // 置けるかチェック
        $changes = $rLogic->check($i1, $i2, $content, $color);
        // 二択かどうかチェック
        if($mode_id === 4) {
            $next_put_coords = $board->next_put_coords;
            $next_flag = false;
            foreach($next_put_coords as $next_cooard) {
                if($next_cooard === [$i1, $i2]) {
                    $next_cooard = true;
                }
            }
            if(!$next_flag) {
                return [
                    'problem' => true,
                ];
            }
        }
        // 置けない場合
        if(empty($changes)) {
            return [
                'problem' => true,
            ];
        }
        // 実際にひっくりかえす
        $content = $rLogic->reverse($color,$changes,$content,$i1,$i2);
        // DBに保存
        $board->updateContent($content,$color);
        // 終了かチェック
        $judge_finish = $rLogic->judge_finish($content,$color);
        return [
            'content' => $content,
            'finish' => $judge_finish,
        ];
    }
    public function pass($color) {
        $room = new Room;
        $room_id = session('room_id');
        $room = $room->find($room_id);
        $board = $room->board;
        $board->changeNextColor($color);
    }
    public function nexts($color, $content) {
        $Logic = new RequestLogic;
        $color = $Logic->turnColor($color);
        $nexts = $Logic->nextCoords($color, $content);
        if(empty($nexts['coords'])) {
            return null;
        } else {
            return array_column($nexts['coords'], 'coord');
        }
    }
    public function diff_time($action_start_time, $has_time) {
        $action_time = time() - $action_start_time;
        $time = $has_time - $action_time;
        return $time;
    }
}