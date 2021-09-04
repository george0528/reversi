<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;
class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = auth()->loginUsingId(1);
        Log::notice($user);
        $this->assertTrue(true);
    }
}
