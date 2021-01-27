<?php

namespace ThaoHR\Support\Plugins\Dashboard\Widgets;

use Auth;
use Carbon\Carbon;
use ThaoHR\Plugins\Widget;
use ThaoHR\User;
use ThaoHR\Repositories\Activity\ActivityRepository;

class UserActivity extends Widget
{
    /**
     * {@inheritdoc}
     */
    public $width = '12';

    /**
     * @var ActivityRepository
     */
    private $activities;

    /**
     * @var array The list of user activity records.
     */
    private $userActivity;

    public function __construct(ActivityRepository $activities)
    {
        $this->activities = $activities;

        $this->permissions(function (User $user) {
            return $user->hasRole('User');
        });
    }

    public function render()
    {
        return view('plugins.dashboard.widgets.user-activity', [
            'activities' => $this->getActivity()
        ]);
    }

    public function scripts()
    {
        return view('plugins.dashboard.widgets.user-activity-scripts', [
            'activities' => $this->getActivity()
        ]);
    }

    private function getActivity()
    {
        if ($this->userActivity) {
            return $this->userActivity;
        }

        return $this->userActivity = $this->activities->userActivityForPeriod(
            Auth::user()->id,
            Carbon::now()->subWeeks(2),
            Carbon::now()
        )->toArray();
    }
}
