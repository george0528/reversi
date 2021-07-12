<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class MainController extends Controller
{
    // topページ
    public function index(Room $room) {
        $room = $room->find(1);
        $borad = $room->borad;
        $b = $borad->getContent();
        return view('main.index', compact('room'));
    }
    // リセット
    public function reset(Room $room) {
        $reversi[3][3] = 1;
        $reversi[3][4] = 2;
        $reversi[4][3] = 2;
        $reversi[4][4] = 1;
        $room = $room->find(1);
        $borad = $room->borad;
        $borad->fillContent($reversi);
        return redirect()->route('index');
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
