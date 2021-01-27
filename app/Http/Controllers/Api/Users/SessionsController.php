<?php

namespace ThaoHR\Http\Controllers\Api\Users;

use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\Repositories\Session\SessionRepository;
use ThaoHR\Transformers\SessionTransformer;
use ThaoHR\User;

/**
 * Class SessionsController
 * @package ThaoHR\Http\Controllers\Api\Users
 */
class SessionsController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:users.manage');
        $this->middleware('session.database');
    }

    /**
     * Get sessions for specified user.
     * @param User $user
     * @param SessionRepository $sessions
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user, SessionRepository $sessions)
    {
        return $this->respondWithCollection(
            $sessions->getUserSessions($user->id),
            new SessionTransformer
        );
    }
}
