<?php

namespace ThaoHR\Tests\Unit;

use Tests\TestCase;
use ThaoHR\Announcement;

class AnnouncementTest extends TestCase
{
    /** @test */
    public function testParsedBody()
    {
        $announcement = new Announcement([
            'title' => 'foo',
            'body' => '#test'
        ]);

        $this->assertEquals("<h1>test</h1>", $announcement->parsed_body);
    }
}
