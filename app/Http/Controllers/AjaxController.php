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
        $i1 = $request->i1;
        $i2 = $request->i2;
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
        $columns = $this->check($i1,$i2,$content,$usercolor);
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
        // テスト
        return response()->json([
            'i1' => $i1,
            'i2' => $i2,
            'user' => $usercolor,
            'changes' => $changes,
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
    // 端かどうかのチェック関数
    public function middle($i1,$i2) {
        if($i1 == 0 || $i2 == 0 || $i1 == 7 || $i2 == 7) {
            return false;
        } else {
            return true;
        }
    }
    // 隣接の色のチェック 端の場合
    public function adjacent($i1,$i2,$content,$color) {
        // マイナス1をスタート
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
                            // 本名データ（$datas）に入れる
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
            if(!isset($content[$i1][$i2])){
                return ['f' => false];
            }
            // 軌跡を保存
            $data[$count][1] = $i1;
            $data[$count][2] = $i2;
            // 増処理
            $i1 += $diffs[1];
            $i2 += $diffs[2];
            $count++;
        }
    }
    // 判定チェック
    public function check($i1,$i2,$content,$color) {
        if($this->middle($i1,$i2)) {
            //　真ん中の処理
            return $this->adjacent($i1,$i2,$content,$color);
        } else {
            // 端の処理
            return $this->adjacent($i1,$i2,$content,$color);
        }
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
}