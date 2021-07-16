<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class MainController extends Controller
{
    // topページ
    public function index(Room $room) {
        $room = $room->find(1);
        return view('main.index', compact('room'));
    }
    // モード選択画面
    public function mode() {
        return view('main.mode');
    }
    // ボット
    public function bot(Room $room) {
        $room = $room->find(1);
        return view('main.battle', compact('room'));
    }
    // 二人オフライン対戦
    public function double(Room $room) {
        $room = $room->find(2);
        return view('main.double', compact('room'));
    }
    // リセット
    public function reset(Room $room) {
        $reversi[3][3] = 1;
        $reversi[3][4] = 2;
        $reversi[4][3] = 2;
        $reversi[4][4] = 1;
        $rooms = $room->all();
        foreach($rooms as $r) {
            $borad = $r->borad;
            $borad->fillContent($reversi);
        }
        return redirect()->back();
    }
    // テスト
    public function test() {
        $data = [];
        $count = 0;
        while($count < 10) {
            if($count == 5) {
                break;
            }
            $data[] = $count;
            $count++;
        }
        return view('main.test');
    }
}
