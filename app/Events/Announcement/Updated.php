<?php

namespace ThaoHR\Events\Announcement;

use Illuminate\Foundation\Events\Dispatchable;
use ThaoHR\Announcement;

class Updated
{
    use Dispatchable;

    public $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }
}
