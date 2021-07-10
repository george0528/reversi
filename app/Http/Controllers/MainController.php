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
}
