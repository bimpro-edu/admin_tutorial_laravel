<?php

namespace ThaoHR\Listeners\Announcement;

use Illuminate\Support\Str;
use ThaoHR\Events\Announcement\Created;
use ThaoHR\Events\Announcement\Deleted;
use ThaoHR\Events\Announcement\Updated;
use ThaoHR\Utils\ActivityLogger;

class ActivityLogSubscriber
{
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }

    public function onCreate(Created $event)
    {
        $this->logger->log(__('announcements::log.created_announcement', [
            'id' => $event->announcement->id,
            'title' => Str::limit($event->announcement->title, 50)
        ]));
    }

    public function onUpdate(Updated $event)
    {
        $this->logger->log(__('announcements::log.created_announcement', [
            'id' => $event->announcement->id
        ]));
    }

    public function onDelete(Deleted $event)
    {
        $this->logger->log(__('announcements::log.deleted_announcement', [
            'id' => $event->announcement->id
        ]));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $class = self::class;

        $events->listen(Created::class, "{$class}@onCreate");
        $events->listen(Updated::class, "{$class}@onUpdate");
        $events->listen(Deleted::class, "{$class}@onDelete");
    }
}
