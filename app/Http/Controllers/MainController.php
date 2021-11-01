<?php

namespace App\Http\Controllers;

use App\Events\RoomEvent;
use App\Models\Board;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    // topページ モード選択画面
    public function index() {
        return view('main.index');
    }
    // ボット
    public function bot(Room $room, Board $board) {
        // ルームを作成
        $room = $room->free();
        $content = $room->reset();
        $b = $board->create([
            'next_color' => 1,
            'content' => $content,
        ]);
        $room = $room->fill([
            'mode_id' => 1,
            'board_id' => $b->id,
        ]);
        $players = [
            ['name' => 'ゲスト', 'count' => 2, 'color' => 1],
            ['name' => 'ボット', 'count' => 2, 'color' => 2]
        ];
        // ログインしていたら戦績を保存する
        if(auth()->check()) {
            $user_id = auth()->user()->id;
            $board->fill([
                'user1' => $user_id
            ])->save();
        }
        $room->save();
        return view('main.bot', compact('room', 'players'));
    }
    // 二人オフライン対戦
    public function double(Request $request,Room $room, Board $board) {
        $room = $room->free();
        $content = $room->reset();
        $b = $board->create([
            'next_color' => 1,
            'content' => $content,
        ]);
        $room = $room->fill([
            'mode_id' => 2,
            'board_id' => $b->id,
        ]);
        $room->save();
        $players = [
            ['name' => 'ゲスト', 'count' => 2, 'color' => 1],
            ['name' => 'ゲスト', 'count' => 2, 'color' => 2]
        ];
        if (auth()->check()) {
            $players[0]['name'] = auth()->user()->name;
        }
        return view('main.double', compact('room', 'players'));
    }
    // 二人オンライン対戦
    // 対戦相手待ちリスト
    public function onlineList() {
        return view('main.list');
    }
    // ルーム作成
    public function roomCreate(Room $room, Request $request, User $user) {
        $mode_id = $request->mode_id;
        if($mode_id != 3 && $mode_id != 4) {
            return redirect()->route('onlineList');
        }
        // ルームを作成
        $room = $room->free();
        // 状態を変更
        $room = $room->fill([
            'is_wait' => 1,
            'mode_id' => $mode_id,
        ]);
        $room->save();
        $user = $user->join_room($room);
        return redirect()->route('onlineWait', ['room_id' => $room->id]);
    }
    // ルーム　待機所
    public function onlineWait() {
        return view('main.wait');
    }
    // オンライン対戦画面
    public function onlineBattle(Request $request, Room $room) {
        $room_id = auth()->user()->room->id;
        $users = $room->find($room_id)->users;
        return view('main.online', compact('users'));
    }
    // 対戦ルーム参加
    public function onlineJoin(Request $request, Room $room) {
        $room_id = $request->room_id;
        $room = $room->join($room_id);
        if(empty($room)) {
            return redirect()->route('onlineList')->with('message', 'そのルームは現在ありません。');
        }
        broadcast(new RoomEvent);
        return redirect()->route('onlineBattle');
    }
    // 待機画面　退出
    public function onlineLeave(Room $room) {
        // ユーザーのroom_idを削除する　　
        $user = auth()->user();
        $room_id = $user->room_id;
        $user->room_id = null;
        $user->save();
        $room->destroy($room_id);
        return redirect()->route('onlineList');
    }
    // 戦績
    public function record() {
        return view('main.record');
    }
    // リセット
    public function reset(Room $room) {
        $reversi[3][3] = 1;
        $reversi[3][4] = 2;
        $reversi[4][3] = 2;
        $reversi[4][4] = 1;
        $rooms = $room->all();
        foreach($rooms as $r) {
            $board = $r->board;
            $board->fillContent($reversi);
        }   
        return redirect()->back();
    }
    // テスト
    public function test(Request $request) {
        dd(session()->getId());
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
