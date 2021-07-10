<?php

namespace App\Http\Controllers;

use App\Models\Borad;
use App\Models\Room;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    // 置く場所を選択したとき
    public function send(Request $request, Room $room) {
        $i1 = $request->i1;
        $i2 = $request->i2;
        $room = $room->find(1);
        $borad = $room->borad->getBorad();
        // 前提チェック　問題があるか
        if($this->firstCheck($i1,$i2,$borad)) {
            return response()->json(['problem' => true]);
        }
        // ユーザー　白か黒　判断する　ロジックを組む
        $usercolor = 1;
        // データベースに保存
        $revesi[$i1][$i2] = 1;
        $f = false;
        $count = $this->check($i1,$i2,$borad,$usercolor);
        // if($this->check($i1,$i2,$borad)) {
        //     $f = true;
        // }
        return response()->json([
            'i1' => $i1,
            'i2' => $i2,
            'user' => $usercolor,
            'f' => $f,
            'count' => $count,
            'borad' => $borad,
        ]);
    }



// 自作関数

// 前提チェック
    // 範囲内チェック関数
    public function range($int) {
        $min = 0;
        $max = 7;
        if(($min <= $int) && ($int <= $max)) {
            return true;
        } else {
            return false;
        }
    }
    // すでにあるかチェック
    public function already($i1,$i2,$borad) {
        if(isset($borad[$i1][$i2])) {
            return true;
        } 
        return false;
    }
    // 前提のチェック 問題があるかどうか
    public function firstCheck($i1,$i2,$borad) {
        // 範囲内の番号かチェック
        if(!($this->range($i1) && $this->range($i2))) {
            return true;
        }
        // すでにあるか
        if($this->already($i1,$i2,$borad)) {
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
    public function adjacent($i1,$i2,$borad,$color) {
        // マイナス1をスタート
        $origI1 = $i1;
        $origI2 = $i1;
        $i1--;
        $i2--;
        $count = 0;
        for ($parentI=0; $parentI < 3; $parentI++) { 
            for ($childI=0; $childI < 3; $childI++) { 
                if(isset($borad[$i1][$i2])) {
                    // 自分の色と違う時
                    if($borad[$i1][$i2] != $color) {
                        $diff = diff($i1,$i2,$origI1,$origI2);

                        $count++;
                    }
                }
                $i2++;
            }
            $i2 = $i2 -3;
            $i1++;
        }
        return $count;
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
    public function pick($i1,) {

    }
    // 判定チェック
    public function check($i1,$i2,$borad,$color) {
        if($this->middle($i1,$i2)) {
            //　真ん中の処理
            return $this->adjacent($i1,$i2,$borad,$color);
        } else {
            // 端の処理
        }
    }
}