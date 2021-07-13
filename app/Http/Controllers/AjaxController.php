<?php

namespace App\Http\Controllers;

use App\Http\Logic\BotLogic;
use App\Models\Borad;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Logic\RequestLogic;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{
    // 置く場所を選択したとき
    public function send(Request $request, Room $room, RequestLogic $requestLogic, BotLogic $botLogic) {
        // 置かれた座標を取得
        $i1 = $request->i1;
        $i2 = $request->i2;
        // ルームをとる
        $room = $room->find(1);
        $borad = $room->borad;
        $content = $borad->getContent();
        // ユーザー　白か黒　判断する　ロジックを組む
        $usercolor = intval($request->color);
        //　おけるかチェック
        $changes = $requestLogic->check($i1,$i2,$content,$usercolor);
        // おけない場合
        if(empty($changes)) {
            return response()->json(['problem' => true]);
        }
        // 実際にひっくりかえす
        $content = $requestLogic->reverse($usercolor,$changes,$content,$i1,$i2);
        // データベースに保存
        $borad->fillContent($content);
        // 最低限のデータ
        $json = [
            'i1' => $i1,
            'i2' => $i2,
            'user' => $usercolor,
            'changes' => $changes,
        ];
        // 次に置ける場所を特定する
        $nexts = $requestLogic->nextCoords($usercolor,$content);
        // 次に置ける場所がない時 パスをtrueにする
        if(empty($nexts)) {
            $json['pass'] = true;
        } else {
            // 次に置ける箇所の座標をとる
            $nextCoords = array_column($nexts, 'coord');
            $json['nextCoords'] = $nextCoords;
        }
        // モード別上限分岐
        if(isset($request->mode)) {
            switch ($request->mode) {
                // ボット対戦の時
                case 1:
                    // ボットの操作
                    $maxCoord = $botLogic->maxCoord($nexts);
                    $json['max'] = $maxCoord;
                    break;
                    // オフライン対戦
                    case 2:
                        // ..code
                        break;
                        // オンライン対戦時
                        case 3:
                            // ..code
                            break;
                        }
                    }
        // レスポンス
        return response()->json($json);
    }
}