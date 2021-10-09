<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class JetstreamTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $this->assertGuest();
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
    public function test_logined_login()
    {
        // ログイン
        $user = User::factory()->create();
        Auth::loginUsingId($user->id);
        $this->assertAuthenticated();
        $response = $this->get('/login');
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
    public function test_register()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertOk();
    }
    public function test_logined_register()
    {
        $current_url = '/mode/switch';
        $this->get($current_url);
        $user = User::factory()->create();
        Auth::loginUsingId($user->id);
        $this->assertAuthenticated();
        $response = $this->get('/register');
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
}
