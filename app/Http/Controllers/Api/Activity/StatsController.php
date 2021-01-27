<?php

namespace ThaoHR\Http\Controllers\Api\Activity;

use Auth;
use Carbon\Carbon;
use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\Repositories\Activity\ActivityRepository;

/**
 * Class ActivityController
 * @package ThaoHR\Http\Controllers\Api\Users
 */
class StatsController extends ApiController
{
    /**
     * @var ActivityRepository
     */
    private $activities;

    public function __construct(ActivityRepository $activities)
    {
        $this->middleware('auth');

        $this->activities = $activities;
    }

    /**
     * Get activities for specified user.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return $this->activities->userActivityForPeriod(
            Auth::user()->id,
            Carbon::now()->subWeeks(2),
            Carbon::now()
        );
    }
}
