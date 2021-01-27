<?php

namespace ThaoHR\Listeners\Users;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use ThaoHR\Events\User\Banned;
use ThaoHR\Events\User\LoggedIn;
use ThaoHR\Repositories\Session\SessionRepository;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Services\Auth\Api\Token;

class InvalidateSessionsAndTokens
{
    /**
     * @var SessionRepository
     */
    private $sessions;

    public function __construct(SessionRepository $sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * Handle the event.
     *
     * @param Banned $event
     * @return void
     */
    public function handle(Banned $event)
    {
        $user = $event->getBannedUser();

        $this->sessions->invalidateAllSessionsForUser($user->id);

        Token::where('user_id', $user->id)->delete();
    }
}
