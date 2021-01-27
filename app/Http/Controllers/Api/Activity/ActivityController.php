<?php

namespace ThaoHR\Http\Controllers\Api\Activity;

use ThaoHR\Http\Requests\Activity\GetActivitiesRequest;
use ThaoHR\Repositories\Activity\ActivityRepository;
use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\Transformers\ActivityTransformer;

/**
 * Class ActivityController
 * @package ThaoHR\Http\Controllers\Api
 */
class ActivityController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:users.activity');
    }

    /**
     * Paginate user activities.
     * @param GetActivitiesRequest $request
     * @param ActivityRepository $activities
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetActivitiesRequest $request, ActivityRepository $activities)
    {
        $result = $activities->paginateActivities(
            $request->per_page ?: 20,
            $request->search
        );

        return $this->respondWithPagination(
            $result,
            new ActivityTransformer
        );
    }
}
