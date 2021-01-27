<?php

namespace ThaoHR\Events\Announcement;

use Illuminate\Foundation\Events\Dispatchable;
use ThaoHR\Announcement;

class Created
{
    use Dispatchable;

    /**
     * @var Announcement
     */
    public $announcement;

    /**
     * @var bool
     */
    public $shouldSendEmailNotification;

    public function __construct(Announcement $announcement, $sendEmailNotification = false)
    {
        $this->announcement = $announcement;
        $this->shouldSendEmailNotification = $sendEmailNotification;
    }
}
