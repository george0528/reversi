<?php

namespace Tests\Unit;

use App\Http\Logic\RequestLogic;
use PHPUnit\Framework\TestCase;

class RequestLogicTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $logic = new RequestLogic;
        // true
        $this->assertTrue($logic->range(0));
        $this->assertTrue($logic->range(7));
        $this->assertTrue($logic->range(3));
        // false
        $this->assertFalse($logic->range(-1));
        $this->assertFalse($logic->range(8));
        $this->assertFalse($logic->range(100));
        
    }
}
