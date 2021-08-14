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
            foreach($next_put_coords as $next_coord) {
                if($next_coord === [$i1, $i2]) {
                    $next_coord = true;
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
        // nextsをDBに保存
        $next_color = $board->next_color;
        $nexts = $this->next_nexts($next_color, $content);
        $nexts = json_encode($nexts);
        $board->fill(['next_coords' => $nexts])->save();
        // 終了かチェック
        $judge_finish = $rLogic->judge_finish($content,$next_color);
        return [
            'content' => $content,
            'finish' => $judge_finish,
        ];
    }
    public function pass($color) {
        $room = auth()->user()->room;
        $board = $room->board;
        $board->changeNextColor($color);
        // 次のネクストを特定する処理を行う
        $next_color = $board->next_color;
        $content = $board->content;
        $nexts = $this->next_nexts($next_color, $content);
        $nexts = json_encode($nexts);
        $board->fill(['next_coords' => $nexts]);
        $board->save();
    }
    public function nexts($next_color, $content) {
        $Logic = new RequestLogic;
        $my_color = $Logic->turnColor($next_color);
        $nexts = $Logic->nextCoords($my_color, $content);
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
    public function next_nexts($next_color, $content) {
        $rLogic = new RequestLogic;
        $nexts = $rLogic->nextCoords($next_color, $content);
        if(empty($nexts['coords'])) {
            $nexts = null;
        } else {
            $nexts = array_column($nexts['coords'], 'coord');
        }
        return $nexts;
    }
}