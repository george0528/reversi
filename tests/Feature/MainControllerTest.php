<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase;

class MainControllerTest extends TestCase
{
    use WithFaker;
    public $url_get_ary = [
        '/mode/online/list',
        '/mode/online/room/battle',
        '/user/profile/record',
        '/mode/online/wait',
    ];
    public $url_post_ary = [
        '/mode/online/room/join',
        '/mode/online/create',
        '/mode/online/room/leave'
    ];
    public function setUp():void
    {   
        parent::setUp();
        $user = User::factory()->create();
        Auth::loginUsingId($user->id);
    }
    public function test_index()
    {
        $response = $this->get('/');
        $response->assertOk();
    }
    public function test_onlineList()
    {
        $response = $this->get('/mode/online/list');
        $response->assertOk();
    }
    public function test_roomCreate()
    {
        $response = $this->post('/mode/online/create', [
            'mode_id' => 3
        ]);
        $room = new Room;
        $room = $room->orderBy('id', 'desc')->first();
        $room_id = $room->id + 1;
        $response->assertStatus(302);
        $response->assertRedirect(`/mode/online/wait?room_id=${room_id}`);
    }
    public function test_onlineJoin()
    {
        // $room = Room::factory()->has(Board::factory()->count(1))->create();
        $room = Room::orderBy('id', 'desc')->first();
        $response = $this->post('/mode/online/room/join', [
            'room_id' => $room->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/mode/online/room/battle');
    }
    public function test_auth_errors()
    {
        // ログアウト
        auth()->logout();
        $this->assertGuest();
        foreach ($this->url_get_ary as $key ) {
            $response = $this->get($key);
            $response->assertRedirect('/login');
        }
        foreach ($this->url_post_ary as $url) {
            $response = $this->post($url);
            $response->assertRedirect('/login');
        }
    }
}
