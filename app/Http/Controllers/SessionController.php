<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    //
    public function addName(Request $request) {
         $name = $request->name;
         $id = $request->session()->getId();
         $mode = $request->session()->get('mode');
         $request->session()->put('name', $name);
         switch($mode) {
             case 1:
                $mode = 'bot';
                break;
            case 2:
                $mode = 'double';
                break;
            case 3:
                // オンライン対戦
                break;
            default :
                $mode = 'onlineWait';
            break;
         }
         return redirect(route($mode));
    }
}
