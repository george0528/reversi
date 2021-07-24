<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class MainController extends Controller
{
    // topページ モード選択画面
    public function index(Room $room) {
        $room = $room->find(1);
        return view('main.index', compact('room'));
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
    // 二人オンライン対戦
    // 対戦相手待ちリスト
    public function onlineList(Room $room) {
        $waitRooms = $room->waitRooms();
        return view('main.list', compact('waitRooms'));
    }
    // 名前入力フォーム
    public function name_form(Request $request) {
        return view('main.name_form');
    }
    // ルーム作成
    public function roomCreate(Room $room, Request $request) {
        $user_id = $request->session()->getId();
        // 空きルームを取得か作成
        $room = $room->free();
        // 状態を変更
        $room = $room->fill([
            'user1_id' => $user_id,
            'status' => 2,
        ]);
        $room->save();
        $request->session()->put('room_id', $room->id);
        return view('main.wait');
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
    public function test(Request $request) {
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
