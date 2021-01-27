<?php

namespace ThaoHR\Events\Announcement;

use Illuminate\Foundation\Events\Dispatchable;
use ThaoHR\Announcement;

class EmailNotificationRequested
{
    use Dispatchable;

    /**
     * @var Announcement
     */
    public $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }
}
