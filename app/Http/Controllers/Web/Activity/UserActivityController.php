<?php

namespace ThaoHR\Http\Controllers\Web\Activity;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use ThaoHR\User;
use ThaoHR\Repositories\Activity\ActivityRepository;
use ThaoHR\Http\Controllers\Controller;

/**
 * Class ActivityController
 * @package ThaoHR\Http\Controllers
 */
class UserActivityController extends Controller
{
    /**
     * @var ActivityRepository
     */
    private $activities;

    /**
     * @param ActivityRepository $activities
     */
    public function __construct(ActivityRepository $activities)
    {
        $this->activities = $activities;
    }

    /**
     * Displays the activity log page for specific user.
     *
     * @param User $user
     * @param Request $request
     * @return Factory|View
     */
    public function index(User $user, Request $request)
    {
        $activities = $this->activities->paginateActivitiesForUser(
            $user->id,
            $perPage = 20,
            $request->search
        );

        return view('activity.index', [
            'user' => $user,
            'adminView' => true,
            'activities' => $activities
        ]);
    }

    /**
     * Display user activity log.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function show(Request $request)
    {
        $user = auth()->user();

        $activities = $this->activities->paginateActivitiesForUser(
            $user->id,
            $perPage = 20,
            $request->get('search')
        );

        return view('activity.index', compact('activities', 'user'));
    }
}
