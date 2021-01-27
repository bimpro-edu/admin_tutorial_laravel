<?php

namespace ThaoHR\Http\Controllers\Web\Activity;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use ThaoHR\Repositories\Activity\ActivityRepository;
use ThaoHR\Http\Controllers\Controller;

/**
 * Class ActivityController
 * @package ThaoHR\Http\Controllers
 */
class ActivityController extends Controller
{
    /**
     * @var ActivityRepository
     */
    private $activities;

    /**
     * ActivityController constructor.
     * @param ActivityRepository $activities
     */
    public function __construct(ActivityRepository $activities)
    {
        $this->activities = $activities;
    }

    /**
     * Displays the page with activities for all system users.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $activities = $this->activities->paginateActivities($perPage = 20, $request->search);

        return view('activity.index', [
            'adminView' => true,
            'activities' => $activities
        ]);
    }
}
