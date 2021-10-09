<?php

namespace Tests\Unit;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_login_pattern()
    {
        $user_data_empty_all = [
            'name' => '',
            'password' => ''
        ];
        $response = $this->post('/login', ['name' => 'aaaaaaaaa', 'password' => 'aaaaaaaaaaa']);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
