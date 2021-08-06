<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    //
    public function addName(Request $request) {
         $name = $request->name;
         $id = $request->session()->getId();
         $request->session()->put('name', $name);
         return redirect(route('onlineList'));
    }
}
