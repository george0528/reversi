<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('private', function ($user) {
    return true;
});
Broadcast::channel('match.{room_id}', function ($u, $room_id) {
    if(session('room_id') == $room_id) {
        return true;
    }
});
Broadcast::channel('battle.{room_id}', function ($u, $room_id) {
    if(session('room_id') == $room_id) {
        return true;
    }
});

Broadcast::channel('test.{room_id}', function($user, $room_id) {
    return ['id' => $user->id, 'name' => $user->name, 'test_data' => 'aaaaaa'];
});
