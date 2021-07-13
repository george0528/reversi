<?php 

namespace App\Http\Logic;

class RequestLogic {
    // 複数の関数をまとめた関数
    // 置ける場合のチェック
    public function check($i1,$i2,$content,$color) {
        // ファーストチェックで問題がある場合
        if($this->firstCheck($i1,$i2,$content)) {
            return null;
        }
        // セカンドチェックで問題がある場合
        $datas = $this->secondcheck($i1,$i2,$content,$color);
        if(empty($datas)) {
            return null;
        }
        return $datas;
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
    // 隣接の色とその反対側に自分のコマがあるかチェック　セカンドチェック
    public function secondcheck($i1,$i2,$content,$color) {
        // 座標をマイナス1する事で 置かれた場所の左端からスタート
        $origI1 = $i1;
        $origI2 = $i2;
        $i1--;
        $i2--;
        $count = 0;
        $datas = [];
        // 縦
        for ($parentI=0; $parentI < 3; $parentI++) { 
            // 横
            for ($childI=0; $childI < 3; $childI++) { 
                if(isset($content[$i1][$i2])) {
                    // 自分の色と違う時
                    if($content[$i1][$i2] != $color) {
                        $diffs = $this->diff($i1,$i2,$origI1,$origI2);
                        $data = $this->pick($i1,$i2,$content,$diffs,$color);
                        // フラッグがtrueの時
                        if($data['f']) {
                            // ひっくり返すコマの座標をデータ（$datas）に入れる
                            foreach($data['data'] as $d) {
                                $datas[] = $d;
                            }
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
    // 次に置ける場所の座標を取得
    public function nextCoords($color,$content) {
        $max = 8;
        $datas = [];
        $count = 0;
        // 次の相手の色に変換
        if($color == 1) {
            $color = 2;
        } elseif($color == 2) {
            $color = 1;
        }
        // 縦
        for ($i1=0; $i1 < $max; $i1++) { 
            // 横
            for ($i2=0; $i2 < $max; $i2++) { 
                // コンテントが空の場合　置ける場合
                if(!isset($content[$i1][$i2])) {
                    //　おけるかチェック
                    $changes = $this->secondcheck($i1,$i2,$content,$color);
                    // おける箇所がある場合
                    if(isset($changes)) {
                        // 次に置ける箇所の座標を保存
                        $datas[$count]['coord'] = [$i1,$i2];
                        // 次に置ける箇所に置いた時にひっくり返る数を保存
                        $datas[$count]['count'] = count($changes);
                        $count++;
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

    // 関数のコンポーネント
    // ファーストチェックの関数のコンポーネント
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


// セカンドチェックの関数のコンポーネント
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
            // 道筋を保存
            $data[] = [$i1,$i2];
            // 増処理
            $i1 += $diffs[1];
            $i2 += $diffs[2];
            $count++;
        }
        return ['f' => false];
    }

// 実際にひっくりかえす
    public function reverse($color,$changes,$content,$i1,$i2) {
        // ユーザーが置いた所に配置
        $content[$i1][$i2] = $color;
        // 変える列の数分回す
        foreach($changes as $c) {
            // その列の変更する個数分回す
            $content[$c[0]][$c[1]] = $color;
        }
        // 新しい盤面と変更点
        return $content;
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
}
?>