<?php

namespace ThaoHR\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use ThaoHR\Listeners\Announcement\SendEmailNotification;
use ThaoHR\Listeners\Announcement\ActivityLogSubscriber;
use ThaoHR\Events\Announcement\EmailNotificationRequested;
use ThaoHR\Events\User\Banned;
use ThaoHR\Events\User\LoggedIn;
use ThaoHR\Listeners\PermissionEventsSubscriber;
use ThaoHR\Listeners\UserEventsSubscriber;
use ThaoHR\Listeners\Users\ActivateUser;
use ThaoHR\Listeners\Users\InvalidateSessionsAndTokens;
use ThaoHR\Listeners\Login\UpdateLastLoginTimestamp;
use ThaoHR\Listeners\Registration\SendSignUpNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use ThaoHR\Listeners\RoleEventsSubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SendSignUpNotification::class,
        ],
        LoggedIn::class => [
            UpdateLastLoginTimestamp::class
        ],
        Banned::class => [
            InvalidateSessionsAndTokens::class
        ],
        Verified::class => [
            ActivateUser::class
        ],
        EmailNotificationRequested::class => [
            SendEmailNotification::class
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        PermissionEventsSubscriber::class,
        RoleEventsSubscriber::class,
        UserEventsSubscriber::class,
        ActivityLogSubscriber::class
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
