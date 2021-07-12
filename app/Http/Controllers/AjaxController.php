<?php

namespace App\Http\Controllers;

use App\Models\Borad;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{
    // 置く場所を選択したとき
    public function send(Request $request, Room $room) {
        // 置かれた座標を取得
        $i1 = $request->i1;
        $i2 = $request->i2;
        // ルームをとる
        $room = $room->find(1);
        $borad = $room->borad;
        $content = $borad->getContent();
        // 前提チェック　問題があるか
        if($this->firstCheck($i1,$i2,$content)) {
            return response()->json(['problem' => true]);
        }
        // ユーザー　白か黒　判断する　ロジックを組む
        $usercolor = intval($request->color);
        //　おけるかチェック
        $columns = $this->adjacent($i1,$i2,$content,$usercolor);
        // おけない場合
        if($columns == null) {
            return response()->json(['problem' => true]);
        }
        // 実際にひっくりかえす
        $data = $this->reverse($usercolor,$columns,$content,$i1,$i2);
        $content = $data['content'];
        $changes = $data['changes'];
        // データベースに保存
        $borad->fillContent($content);
        // 次に置ける場所を特定する
        $nextCoords = $this->nextCoords($usercolor,$content);
        // 次に置ける場所がない時
        if($nextCoords == null) {
            return response()->json([
                'i1' => $i1,
                'i2' => $i2,
                'user' => $usercolor,
                'changes' => $changes,
                'pass' => true,
            ]);
        }
        // レスポンス
        return response()->json([
            'i1' => $i1,
            'i2' => $i2,
            'user' => $usercolor,
            'changes' => $changes,
            'nextCoords' => $nextCoords,
            ]);
    }










// 自作関数

// 前提チェック
    // 範囲内チェック関数
    public function range($int) {
        $min = 0;
        $max = 7;
        // 範囲内なら
        if(($min <= $int) && ($int <= $max)) {
            return true;
        } else {
            return false;
        }
    }
    // すでにあるかチェック
    public function already($i1,$i2,$content) {
        if(isset($content[$i1][$i2])) {
            return true;
        } 
        return false;
    }
    // 前提のチェック 問題があるかどうか
    public function firstCheck($i1,$i2,$content) {
        // そもそも番号があるかチェック
        if(!(isset($i1) && isset($i2))) {
            return true;
        }
        // 範囲内の番号かチェック
        if(!($this->range($i1) && $this->range($i2))) {
            return true;
        }
        // すでにあるか
        if($this->already($i1,$i2,$content)) {
            return true;
        }
        // 問題が無ければ
        return false;
    }


// ひっくりかえせるか
    // 隣接の色のチェック
    public function adjacent($i1,$i2,$content,$color) {
        // 座標をマイナス1する事で 置かれた場所の左端からスタート
        $origI1 = $i1;
        $origI2 = $i2;
        $i1--;
        $i2--;
        $count = 0;
        $datas = [];
        for ($parentI=0; $parentI < 3; $parentI++) { 
            for ($childI=0; $childI < 3; $childI++) { 
                if(isset($content[$i1][$i2])) {
                    // 自分の色と違う時
                    if($content[$i1][$i2] != $color) {
                        $diffs = $this->diff($i1,$i2,$origI1,$origI2);
                        $data = $this->pick($i1,$i2,$content,$diffs,$color);
                        // フラッグがtrueの時
                        if($data['f']) {
                            // ひっくり返せる方向の座標のデータ（$datas）に入れる
                            $datas[] = $data['data'];
                            $count++;
                        }
                    }
                }
                $i2++;
            }
            $i2 = $i2 -3;
            $i1++;
        }
        // ひっくり返せる駒？が無ければ
        if($count == 0) {
            return null;
        }
        return $datas;
    }
    // 差分を計算
    public function diff($i1,$i2,$origI1,$origI2) {
        $diff1 = $i1 - $origI1;
        $diff2 = $i2 - $origI2;
        return [
            1 => $diff1,
            2 => $diff2,
        ];
    }
    // 反対側に自分の色のオセロがあるか
    public function pick($i1,$i2,$content,$diffs,$color) {
        $data = [];
        $count = 0;
        // 範囲を超えていない時にループ
        while ($this->range($i1) && $this->range($i2)) {
            // データがセットされている（何かおかれている時）　かつ　同じカラーの時
            if(isset($content[$i1][$i2]) && $content[$i1][$i2] == $color) {
                return ['f' => true, 'data' => $data];
            }
            // セットされていなかったら
            if(!isset($content[$i1][$i2])){
                break;
            }
            // 軌跡を保存
            $data[$count][1] = $i1;
            $data[$count][2] = $i2;
            // 増処理
            $i1 += $diffs[1];
            $i2 += $diffs[2];
            $count++;
        }
        return ['f' => false];
    }


// 実際にひっくりかえす
    public function reverse($color,$columns,$content,$i1,$i2) {
        // ユーザーが置いた所に配置
        $content[$i1][$i2] = $color;
        // 変える列の数分回す
        foreach($columns as $coords) {
            // その列の変更する個数分回す
            foreach($coords as $c) {
                $content[$c[1]][$c[2]] = $color;
                $changes[] = $c;
            }
        }
        return ['content' => $content, 'changes' => $changes];
    }


    // 全ての置ける場所が存在しない時
    public function allNone($content) {
        $count = 8;
        // 縦
        for ($i1=0; $i1 < $count; $i1++) { 
            // 横
            for ($i2=0; $i2 < $count; $i2++) { 
                // コンテントが空の場合　置ける場合
                if(!isset($content[$i1][$i2])) {
                    return false;
                }
            }
        }
        // 置ける箇所が無かった場合
        return true;
    }

    // 次に置ける場所の座標を取得
    public function nextCoords($color,$content) {
        $count = 8;
        $datas = [];
        // 次の相手の色に変換
        if($color == 1) {
            $color = 2;
        } elseif($color == 2) {
            $color = 1;
        }
        // 縦
        for ($i1=0; $i1 < $count; $i1++) { 
            // 横
            for ($i2=0; $i2 < $count; $i2++) { 
                // コンテントが空の場合　置ける場合
                if(!isset($content[$i1][$i2])) {
                    //　おけるかチェック
                    $columns = $this->adjacent($i1,$i2,$content,$color);
                    // おける箇所がある場合
                    if($columns != null) {
                        $datas[] = [$i1,$i2];
                    }
                }
            }
        }
        // 置ける場所が0の時
        if(count($datas) == 0) {
            // 全ての置ける場所が0の時
            return null;
            if($this->allNone($content)) {
                return '終了';
            }
        }
        return $datas;
    }
}