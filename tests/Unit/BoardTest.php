<?php

namespace Tests\Unit;

use App\Models\Board;
use Tests\TestCase;

class BoardTest extends TestCase
{
    /*
     * A basic unit test example.
     *
     * 
     */
    public function test_method()
    {
        $board = new Board;
        $board_sample_1 = $board->create($this->return_test_data());
        $test_content = [];
        $test_content[0][0] = 1;
        $test_content[0][1] = 1;
        $saved_content_result = $board_sample_1->fillContent($test_content);
        // fillContent
        $this->assertTrue($saved_content_result);
        $content = $board_sample_1->getContent();
        // getContent
        $this->assertSame($test_content, $content);
        // changeNextColor
        $changeNextColor_result = $board_sample_1->changeNextColor(1);
        $this->assertTrue($changeNextColor_result);
        $this->assertSame($board_sample_1->next_color, 2);
        // turnColor
        $this->assertSame($board_sample_1->turnColor(1), 2);
    }
    public function return_test_data()
    {
        return [
            'next_color' => 1,
            'next_coords' => null,
            'content' => null,
            'user1' => null,
            'user2' => null,
            'winner' => null
        ];
    }
}
