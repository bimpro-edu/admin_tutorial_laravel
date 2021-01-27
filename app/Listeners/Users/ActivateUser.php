<?php

namespace ThaoHR\Listeners\Users;

use Illuminate\Auth\Events\Verified;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Support\Enum\UserStatus;

class ActivateUser
{
    /**
     * @var UserRepository
     */
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Handle the event.
     *
     * @param Verified $event
     * @return void
     */
    public function handle(Verified $event)
    {
        $this->users->update($event->user->id, [
            'status' => UserStatus::ACTIVE
        ]);
    }
}
