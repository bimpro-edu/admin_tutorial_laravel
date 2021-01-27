<?php

namespace ThaoHR\Http\Controllers\Api\Activity;

use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\User;
use ThaoHR\Http\Requests\Activity\GetActivitiesRequest;
use ThaoHR\Repositories\Activity\ActivityRepository;
use ThaoHR\Transformers\ActivityTransformer;

/**
 * Class ActivityController
 * @package ThaoHR\Http\Controllers\Api\Users
 */
class UserActivityController extends ApiController
{
    /**
     * @var ActivityRepository
     */
    private $activities;

    public function __construct(ActivityRepository $activities)
    {
        $this->middleware('auth');
        $this->middleware('permission:users.activity');

        $this->activities = $activities;
    }

    /**
     * Get activities for specified user.
     * @param User $user
     * @param GetActivitiesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user, GetActivitiesRequest $request)
    {
        $activities = $this->activities->paginateActivitiesForUser(
            $user->id,
            $request->per_page ?: 20,
            $request->search
        );

        return $this->respondWithPagination(
            $activities,
            new ActivityTransformer
        );
    }
}
