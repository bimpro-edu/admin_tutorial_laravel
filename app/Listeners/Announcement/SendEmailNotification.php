<?php

namespace ThaoHR\Listeners\Announcement;

use Mail;
use ThaoHR\Announcement;
use ThaoHR\Events\Announcement\EmailNotificationRequested;
use ThaoHR\Mail\AnnouncementEmail;
use ThaoHR\User;

class SendEmailNotification
{
    /**
     * Handle the event.
     *
     * @param EmailNotificationRequested $event
     * @return void
     */
    public function handle(EmailNotificationRequested $event)
    {
        User::chunk(200, function ($users) use ($event) {
            foreach ($users as $user) {
                $this->sendEmailTo($user, $event->announcement);
            }
        });
    }

    private function sendEmailTo(User $user, Announcement $announcement)
    {
        Mail::to($user)->send(new AnnouncementEmail($announcement));
    }
}
