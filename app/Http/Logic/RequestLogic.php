<?php 

namespace App\Http\Logic;

use function PHPUnit\Framework\callback;

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
        if(isset($datas['problem'])) {
            return null;
        }
        return $datas['changes'];
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
                // 自分の色と違う時
                if(isset($content[$i1][$i2]) && $content[$i1][$i2] != $color) {
                    $diffs = $this->diff($i1,$i2,$origI1,$origI2);
                    $data = $this->pick($i1,$i2,$content,$diffs,$color);
                    // フラッグがtrueの時
                    if($data['f']) {
                        // ひっくり返すコマの座標をデータ（$datas）に入れる
                        foreach($data['data'] as $d) {
                            $datas['changes'][] = $d;
                        }
                        $count++;
                    }
                }
                $i2++;
            }
            $i2 = $i2 -3;
            $i1++;
        }
        // ひっくり返せる駒？が無ければ
        if($count == 0) {
            $datas['problem'] = true;
        }
        return $datas;
    }
    // 次に置ける場所かパスか終了をとる
    public function nextCheck($nexts, $json) {
        // 次に置ける場所がない時 パスをtrueにする
        if(isset($nexts['pass'])) {
            $json['pass'] = true;
        } else {
            // 次に置ける箇所の座標をとる
            $nextCoords = array_column($nexts['coords'], 'coord');
            $json['nextCoords'] = $nextCoords;
        }
        return $json;
    }
    // 次に置ける場所の座標を取得
    public function nextCoords($color,$content) {
        $max = 8;
        $datas = [];
        $count = 0;
        // 次の相手の色に変換
        $color = $this->turnColor($color);
        // 縦
        for ($i1=0; $i1 < $max; $i1++) { 
            // 横
            for ($i2=0; $i2 < $max; $i2++) { 
                // コンテントが空の場合　置ける場合
                if(!isset($content[$i1][$i2])) {
                    //　おけるかチェック
                    $changes = $this->secondcheck($i1,$i2,$content,$color);
                    // おける箇所がある場合
                    if(empty($changes['problem'])) {
                        // 次に置ける箇所の座標を保存
                        $datas['coords'][$count]['coord'] = [$i1,$i2];
                        // 次に置ける箇所に置いた時にひっくり返る数を保存
                        $datas['coords'][$count]['count'] = count($changes['changes']);
                        $count++;
                    }
                }
            }
        }
        // 置ける場所が0の時
        if(count($datas) == 0) {
            $datas['pass'] = true;
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
                // コンテントがある場合　置けない場合
                if(isset($content[$i1][$i2])) {
                    return true;
                }
            }
        }
        // 置ける場所があった場合
        return false;
    }
    public function put_place_none($i1,$i2,$content) {
        if(isset($content[$i1][$i2])) {
            return false;
        }
    }
    // 全てのコマに適用するコールバック関数
    public function allCheck($fun,$ary) {
        $count = 8;
        // 縦
        for ($i1=0; $i1 < $count; $i1++) { 
            // 横
            for ($i2=0; $i2 < $count; $i2++) { 
                // if文　条件分岐
                $data = call_user_func($fun,$i1,$i2,$ary);
                if($data['f']) {
                    $datas[] = $data['data'];
                }
            }
        }
        // 全てが終わったら
        return $datas;
    }
    // コマの数を数える
    public function judge($content) {
        $data = $this->allCheck([$this,'judge_component'],[$content]);
        $counts = array_count_values($data);
        $datas['counts'] = $counts;
        // 中身がnullの時0を入れる
        foreach([1,2] as $num) {
            if(empty($datas['counts'][$num])) {
                $datas['counts'][$num] = 0;
            }
        }
        if($datas['counts'][1] > $datas['counts'][2]) {
            $datas['winner'] = 1;
        } else {
            $datas['winner'] = 2;
        }
        return $datas;
    }
    // コマの数を数えるコンポーネント関数
    public function judge_component($i1,$i2,$ary) {
        $content = $ary[0];
        // 中身があればその値を返す
        if(isset($content[$i1][$i2])) {
            $data['f'] = true;
            $data['data'] = $content[$i1][$i2];
        } else {
            $data['f'] = false;
        }
        return $data;
    }
    // 終了か判定処理
    public function judge_finish($content,$color) {
        $enemy_nexts = $this->nextCoords($color, $content);
        $color = $this->turnColor($color);
        $my_nexts = $this->nextCoords($color, $content);
        if(empty($enemy_nexts['coords']) && empty($my_nexts['coords'])) {
            return true;
        }
        return false;
    }
    // 色反転
    public function turnColor($color) {
        if($color == 1) {
            $color = 2;
        } elseif($color == 2) {
            $color = 1;
        }
        return $color;
    }
}
?>